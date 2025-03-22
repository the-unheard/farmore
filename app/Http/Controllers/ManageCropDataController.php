<?php

namespace App\Http\Controllers;

use App\Models\CropData;
use App\Models\Plot;

class ManageCropDataController extends Controller
{

    public function index()
    {
        $cropData = CropData::orderBy('id')->paginate(10);

        return view('manage-crop-data.index', [
            'cropData' => $cropData,
        ]);
    }

    public function show($id)
    {
        $cropData = CropData::where('id', $id)->first();

        if (!$cropData) {
            return redirect()->route('manage-crop-data.index')->with('error', 'Crop data not found.');
        }

        return view('manage-crop-data.show', ['cropData' => $cropData]);
    }

    public function edit($id)
    {
        $cropData = CropData::findOrFail($id);

        return view('manage-crop-data.edit', [
            'cropData' => $cropData,
        ]);
    }

    public function update($id)
    {
        $validatedData = $this->validateInput();

        $cropData = CropData::findOrFail($id);
        $cropData->update($validatedData);

        return redirect('/manage-crop-data/' . $id)->with('success', 'Crop data record updated successfully.');
    }

    private function validateInput(): array
    {
        $validatedData = request()->validate([
            'crop_name' => [
                'required',
                'regex:/^[a-zA-Z\s]+$/', // Only letters and spaces
                'min:1',
                'max:30',
            ],
            'other_name' => [
                'required',
                'regex:/^[a-zA-Z\s]+$/', // Only letters and spaces
                'min:1',
                'max:30',
            ],
        ]);

        return $validatedData;
    }

}
