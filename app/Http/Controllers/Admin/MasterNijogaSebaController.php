<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariSebaMaster;
use App\Models\PratihariNijogaMaster;
use App\Models\PratihariNijogaSebaAssign;
use App\Models\PratihariSebaBeddhaAssign;
use App\Models\PratihariBeddhaMaster;
use Illuminate\Support\Facades\DB;


class MasterNijogaSebaController extends Controller
{
    public function pratihariNijogaSeba()
    {
        $nijogas = PratihariNijogaMaster::all(); // Fetch all Nijoga names
        $sebas = PratihariSebaMaster::all(); // Fetch all Seba names

        return view('admin.master-nijoga-type-assign', compact('nijogas','sebas'));
    }
    

    public function storeSeba(Request $request)
    {
        $request->validate([
            'seba_name' => 'required|string|max:255',
        ]);

        PratihariSebaMaster::create([
            'seba_name' => $request->seba_name,
        ]);

        return redirect()->back()->with('success', 'Seba added successfully!');
    }

    public function storeNijoga(Request $request)
    {
        $request->validate([
            'nijoga_name' => 'required|string|max:255',
        ]);

        PratihariNijogaMaster::create([
            'nijoga_name' => $request->nijoga_name,
        ]);

        return redirect()->back()->with('success', 'Nijoga added successfully!');
    }

    public function saveNijogaSeba(Request $request)
    {
        $request->validate([
            'nijoga_type' => 'required',
            'seba_name' => 'required|array', // Ensure it's an array for multiple selections
        ]);

        $nijoga_id = $request->nijoga_type;
        $sebas = $request->seba_name; // Array of selected Seba IDs

        // Loop through each selected Seba and insert a row
        foreach ($sebas as $seba_id) {
            PratihariNijogaSebaAssign::create([
                'nijoga_id' => $nijoga_id,
                'seba_id' => $seba_id,
            ]);
        }

        return redirect()->back()->with('success', 'Nijoga and Seba assigned successfully!');
    }

    public function pratihariSebaBeddha()
    {
        $beddhas = PratihariBeddhaMaster::all(); // Fetch all Beddha names
        $sebas = PratihariSebaMaster::all(); // Fetch all Seba names

        return view('admin.master-seba-beddha-assign', compact('beddhas','sebas'));
    }
    
    public function storeBeddha(Request $request)
    {
        $request->validate([
            'beddha_count' => 'required|integer|min:1|max:100',
        ]);
    
        $count = $request->beddha_count;
        $addedCount = 0; // To track how many were actually added
    
        for ($i = 1; $i <= $count; $i++) {
            // Check if this beddha_name already exists
            $exists = PratihariBeddhaMaster::where('beddha_name', $i)->exists();
    
            if (!$exists) {
                PratihariBeddhaMaster::create([
                    'beddha_name' => $i,
                ]);
                $addedCount++;
            }
        }
    
        if ($addedCount > 0) {
            return redirect()->back()->with('success', "$addedCount Beddhas added successfully!");
        } else {
            return redirect()->back()->with('error', 'All selected Beddhas already exist. No new entries were added.');
        }
    }
    

    public function saveSebaBeddha(Request $request)
    {
      
        try {
            DB::beginTransaction();

            // Insert multiple rows for each selected Beddha
            foreach ($request->beddha_name as $beddha_id) {
                PratihariSebaBeddhaAssign::create([
                    'seba_id' => $request->seba_name,
                    'beddha_id' => $beddha_id
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Seba Beddha added successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }

}
