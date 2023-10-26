<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaxPax;
use App\Models\CustomMaxPax;

class MaxPaxController extends Controller
{
    public function create()
    {
        $maxPax = MaxPax::where('name', 'Waterpark Capacity')->first();
     

        return view('maxpax.create', compact('maxPax'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'maximum_pax' => 'required|integer',
        ]);
    
        // Update or create MaxPax records for Walk-in and Pre-book
        MaxPax::updateOrCreate(['name' => 'Waterpark Capacity'], ['maximum_customers' => $request->input('maximum_pax')]);
     
    
        // Redirect back to the create view with a success message
        return redirect()->route('maxpax.create')->with('success', 'Max Pax settings updated successfully');
    }

    
}
