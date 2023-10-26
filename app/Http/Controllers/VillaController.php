<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villa;

class VillaController extends Controller
{
    public function read()
    {
        $villas = Villa::with('status')->get();// for getting role name in statuses table
        
        return view('villa.read', ['villas' => $villas]); 
    }

    public function create()
    {
        return view('villa.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'villaname' => 'required|string',
            'pricing' => 'required|integer',
            'capacity' => 'required|integer',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable', 
            'status_id' => 'required|integer',
        ]);

        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('villa_images', 'public');
                $imageUrls[] = asset('storage/' . $imagePath);
            }
            $validatedData['images'] = json_encode($imageUrls); // Convert array to JSON
        }

        Villa::create($validatedData); // Saving the data

        return redirect(route('villas.read'))->with('success', 'Successfully Save');
    }

    public function edit(Villa $villa)
    {
        return view('villa.edit', ['villa' => $villa]); 
    }

    public function update(Request $request, Villa $villa)
    {
        $validatedData = $request->validate([
            'villaname' => 'required|string',
            'pricing' => 'required|integer',
            'capacity' => 'required|integer',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
            'status_id' => 'required|integer',
        ]);

        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('villa_images', 'public');
                $imageUrls[] = asset('storage/' . $imagePath);
            }
            $validatedData['images'] = json_encode($imageUrls); // Convert array to JSON
        }

        $villa->update($validatedData);

        return redirect(route('villas.read'))->with('success', 'Updated Successfully');
    }

    public function destroy(Villa $villa) {

        $villa->delete();
        return redirect(route('villas.read'))->with('success', 'Successfully Deleted');

    }

    public function archives(){
        $villas = Villa::onlyTrashed()
            ->orderBy('id', 'desc')->get();

        return view('villa.archives', ['villas' => $villas]);
    }

    public function restore(Villa $villa, Request $request)
    {
        $villa->restore();

        return redirect(route('villas.read'))->with('success', 'Successfully Restored');
    }

}
