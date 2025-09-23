<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariFamily;
use App\Models\PratihariChildren;
use Illuminate\Support\Facades\Auth;    
use Image; // Intervention\Image\Facades\Image
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PratihariFamilyApiController extends Controller
{

public function saveFamily(Request $request)
{
    // Guard early: in case server limits are OK but user not authed
    $user = Auth::user();
    if (!$user || !$user->pratihari_id) {
        return response()->json(['status' => 401, 'message' => 'Unauthorized. Please log in.'], 401);
    }
    $pratihariId = $user->pratihari_id;

    // ******* VALIDATION *******
    // Tip: keep these <= your Nginx/PHP limits. 7MB per file is a safe default.
    $request->validate([
        'father_name'     => 'nullable|string|max:100',
        'mother_name'     => 'nullable|string|max:100',
        'marital_status'  => 'nullable|string|in:single,married,separated,divorced,widowed',
        'spouse_name'     => 'nullable|string|max:100',

        'father_photo'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:7168',
        'mother_photo'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:7168',
        'spouse_photo'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:7168',

        'children_name'        => 'nullable|array',
        'children_name.*'      => 'nullable|string|max:100',
        'children_dob'         => 'nullable|array',
        'children_dob.*'       => 'nullable|date',
        'children_gender'      => 'nullable|array',
        'children_gender.*'    => 'nullable|string|in:male,female,other',
        'children_photo'       => 'nullable|array',
        'children_photo.*'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:7168',
    ]);

    // Optional: cap total request payload from app side (extra safety)
    $totalBytes = 0;
    foreach (['father_photo','mother_photo','spouse_photo'] as $k) {
        if ($request->hasFile($k)) $totalBytes += $request->file($k)->getSize();
    }
    if ($request->hasFile('children_photo')) {
        foreach ($request->file('children_photo') as $f) {
            if ($f) $totalBytes += $f->getSize();
        }
    }
    // 40MB soft cap – keep below Nginx/PHP
    if ($totalBytes > 40 * 1024 * 1024) {
        return response()->json([
            'status' => 413,
            'message' => 'Files too large. Please upload smaller images.'
        ], 413);
    }

    // ******* IMAGE STORAGE HELPERS *******
    // Compress to WEBP (small, widely supported); fallback to JPEG if needed.
    $storeCompressed = function ($uploadedFile, string $dir): string {
        $image = Image::make($uploadedFile->getPathname())->orientate();

        // Resize if very big (keeps aspect ratio)
        $maxW = 1600; $maxH = 1600;
        if ($image->width() > $maxW || $image->height() > $maxH) {
            $image->resize($maxW, $maxH, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Encode to webp with good quality/size tradeoff
        try {
            $encoded = $image->encode('webp', 82);
            $ext = 'webp';
        } catch (\Throwable $e) {
            // Fallback to jpeg
            $encoded = $image->encode('jpg', 82);
            $ext = 'jpg';
        }

        $name = Str::uuid().'.'.$ext;
        $path = $dir.'/'.$name;

        Storage::disk('public')->put($path, (string) $encoded);

        return $path; // relative to disk root => /storage/{path}
    };

    try {
        DB::beginTransaction();

        // Upsert family
        $family = PratihariFamily::firstOrNew(['pratihari_id' => $pratihariId]);
        $family->father_name    = $request->father_name;
        $family->mother_name    = $request->mother_name;
        $family->maritial_status = $request->marital_status; // keep your column name
        $family->spouse_name    = $request->spouse_name;

        // Replace photos with compressed versions
        if ($request->hasFile('father_photo')) {
            if ($family->father_photo) Storage::disk('public')->delete($family->father_photo);
            $family->father_photo = $storeCompressed($request->file('father_photo'), 'family');
        }
        if ($request->hasFile('mother_photo')) {
            if ($family->mother_photo) Storage::disk('public')->delete($family->mother_photo);
            $family->mother_photo = $storeCompressed($request->file('mother_photo'), 'family');
        }
        if ($request->hasFile('spouse_photo')) {
            if ($family->spouse_photo) Storage::disk('public')->delete($family->spouse_photo);
            $family->spouse_photo = $storeCompressed($request->file('spouse_photo'), 'family');
        }

        $family->save();

        // Rebuild children records (and their photos)
        $oldChildren = PratihariChildren::where('pratihari_id', $pratihariId)->get();
        foreach ($oldChildren as $oc) {
            if ($oc->photo) Storage::disk('public')->delete($oc->photo);
        }
        PratihariChildren::where('pratihari_id', $pratihariId)->delete();

        $childrenData = [];
        $names   = (array) ($request->children_name ?? []);
        $dobs    = (array) ($request->children_dob ?? []);
        $genders = (array) ($request->children_gender ?? []);

        foreach ($names as $i => $name) {
            if (!filled($name)) continue;

            $child = new PratihariChildren();
            $child->pratihari_id  = $pratihariId;
            $child->children_name = $name;
            $child->date_of_birth = $dobs[$i]   ?? null;
            $child->gender        = $genders[$i] ?? null;

            if ($request->hasFile("children_photo.$i")) {
                $child->photo = $storeCompressed($request->file("children_photo.$i"), 'children');
            }

            $child->save();
            $childrenData[] = $child;
        }

        DB::commit();

        return response()->json([
            'status'  => 200,
            'message' => 'Family data saved successfully.',
            'data'    => [
                'family'   => $family->fresh(),
                'children' => $childrenData,
            ],
        ], 200);

    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error('Error saving family data', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => 500,
            'message' => 'Something went wrong.',
            'error' => app()->environment('production') ? null : $e->getMessage(),
        ], 500);
    }
}
    
}
