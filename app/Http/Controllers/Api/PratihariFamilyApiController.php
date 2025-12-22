<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariFamily;
use App\Models\PratihariChildren;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PratihariFamilyApiController extends Controller
{

public function saveFamily(Request $request)
{
    try {
        $user = Auth::user();
        $pratihariId = $user?->pratihari_id;

        if (!$pratihariId) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        // Basic validation (extend as needed)
        $validator = Validator::make($request->all(), [
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:50',
            'spouse_name' => 'nullable|string|max:255',

            'father_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'mother_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'spouse_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',

            'children_id' => 'array',
            'children_id.*' => 'nullable|integer',

            'children_name' => 'array',
            'children_name.*' => 'nullable|string|max:255',

            'children_dob' => 'array',
            'children_dob.*' => 'nullable|date',

            'children_gender' => 'array',
            'children_gender.*' => 'nullable|string|max:50',

            'children_marital_status' => 'array',
            'children_marital_status.*' => 'nullable|string|max:50',

            'children_spouse_name' => 'array',
            'children_spouse_name.*' => 'nullable|string|max:255',

            'children_photo' => 'array',
            'children_photo.*' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = DB::transaction(function () use ($request, $pratihariId) {

            // ----------------------------
            // Family create/update
            // ----------------------------
            $family = PratihariFamily::where('pratihari_id', $pratihariId)->first();

            if (!$family) {
                $family = new PratihariFamily();
                $family->pratihari_id = $pratihariId;
            }

            $family->father_name = $request->father_name;
            $family->mother_name = $request->mother_name;

            // Keep your DB column name as-is (you used maritial_status)
            $family->maritial_status = $request->marital_status ?? $request->maritial_status;
            $family->spouse_name = $request->spouse_name;

            // Ensure folders exist
            $familyDir = public_path('uploads/family');
            $childrenDir = public_path('uploads/children');
            if (!is_dir($familyDir)) mkdir($familyDir, 0775, true);
            if (!is_dir($childrenDir)) mkdir($childrenDir, 0775, true);

            // Family photos (replace only if new file provided)
            if ($request->hasFile('father_photo')) {
                $fatherPhoto = $request->file('father_photo');
                $fatherPhotoName = Str::uuid() . '_father.' . $fatherPhoto->getClientOriginalExtension();
                $fatherPhoto->move($familyDir, $fatherPhotoName);
                $family->father_photo = 'uploads/family/' . $fatherPhotoName;
            }

            if ($request->hasFile('mother_photo')) {
                $motherPhoto = $request->file('mother_photo');
                $motherPhotoName = Str::uuid() . '_mother.' . $motherPhoto->getClientOriginalExtension();
                $motherPhoto->move($familyDir, $motherPhotoName);
                $family->mother_photo = 'uploads/family/' . $motherPhotoName;
            }

            if ($request->hasFile('spouse_photo')) {
                $spousePhoto = $request->file('spouse_photo');
                $spousePhotoName = Str::uuid() . '_spouse.' . $spousePhoto->getClientOriginalExtension();
                $spousePhoto->move($familyDir, $spousePhotoName);
                $family->spouse_photo = 'uploads/family/' . $spousePhotoName;
            }

            $family->save();

            // ----------------------------
            // Children create/update/delete logic
            // ----------------------------
            $childrenIds = $request->input('children_id', []);
            $childrenNames = $request->input('children_name', []);
            $childrenDobs = $request->input('children_dob', []);
            $childrenGenders = $request->input('children_gender', []);
            $childrenMarital = $request->input('children_marital_status', []);
            $childrenSpouse = $request->input('children_spouse_name', []);

            $processedChildIds = [];

            foreach ($childrenNames as $index => $name) {
                $name = trim((string) $name);

                // Skip empty row
                if ($name === '') {
                    continue;
                }

                $childId = $childrenIds[$index] ?? null;

                // If id provided, update that row (only if belongs to this pratihari)
                if ($childId) {
                    $child = PratihariChildren::where('id', $childId)
                        ->where('pratihari_id', $pratihariId)
                        ->first();
                } else {
                    $child = null;
                }

                if (!$child) {
                    $child = new PratihariChildren();
                    $child->pratihari_id = $pratihariId;
                }

                $child->children_name = $name;
                $child->date_of_birth = $childrenDobs[$index] ?? null;
                $child->gender = $childrenGenders[$index] ?? null;

                // Add extra children fields if your table/model supports them
                // (you previously mentioned marital_status + spouse_name in children model)
                $child->marital_status = $childrenMarital[$index] ?? null;
                $child->spouse_name = $childrenSpouse[$index] ?? null;

                // Photo upload (keep existing if not uploaded)
                if ($request->hasFile("children_photo.$index")) {
                    $photo = $request->file("children_photo.$index");
                    $photoName = Str::uuid() . "_child_{$index}." . $photo->getClientOriginalExtension();
                    $photo->move($childrenDir, $photoName);
                    $child->photo = 'uploads/children/' . $photoName;
                }

                $child->save();

                $processedChildIds[] = $child->id;
            }

            // Delete children not present in the submitted payload
            // (this replaces your previous "delete all and insert" approach)
            PratihariChildren::where('pratihari_id', $pratihariId)
                ->when(!empty($processedChildIds), function ($q) use ($processedChildIds) {
                    $q->whereNotIn('id', $processedChildIds);
                }, function ($q) {
                    // If no children rows were submitted (all empty), delete all
                    $q->whereNotNull('id');
                })
                ->delete();

            // Return fresh list
            $childrenList = PratihariChildren::where('pratihari_id', $pratihariId)->get();

            return [
                'family' => $family->fresh(),
                'children' => $childrenList,
            ];
        });

        return response()->json([
            'status' => 200,
            'message' => 'Family data saved successfully.',
            'data' => $result,
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error saving family data: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 500,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    public function show()
    {
        try {
            $families = PratihariFamily::where('status', 'active')
                ->get(['father_name','father_photo','mother_name','mother_photo']);

            $toUrl = function ($path) {
                if (empty($path)) return null;
                if (preg_match('/^https?:\/\//i', $path)) return $path;
                if (Storage::disk('public')->exists($path)) return Storage::disk('public')->url($path);
                return asset($path);
            };

            $fathers = [];
            $mothers = [];
            foreach ($families as $row) {
                $fathers[] = ['name' => $row->father_name, 'photo' => $toUrl($row->father_photo)];
                $mothers[] = ['name' => $row->mother_name, 'photo' => $toUrl($row->mother_photo)];
            }

            if ($families->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No family data found.',
                    'data' => ['father' => [], 'mother' => []],
                ], 200);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Family data get successfully.',
                'data' => ['father' => $fathers, 'mother' => $mothers],
            ], 200);

        } catch (\Throwable $e) {
            Log::error('PratihariFamily API error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 500, 'message' => 'Something went wrong.'], 500);
        }
    }
}
