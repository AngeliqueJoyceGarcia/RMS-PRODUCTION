<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EOD; 

class EODController extends Controller
{
    public function read(){
        $eod = EOD::find(1);

        return view('eod.read', compact('eod'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the input data
            $request->validate([
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            // Retrieve the first EOD record, if it exists
            $eod = EOD::find(1);

            if (!$eod) {
                // If the record does not exist, you can create it here
                $eod = new EOD();
            }

            // Check if start_time has changed
            if ($eod->start_time != $request->input('start_time')) {
                $eod->start_time = $request->input('start_time');
            }

            // Check if end_time has changed
            if ($eod->end_time != $request->input('end_time')) {
                $eod->end_time = $request->input('end_time');
            }

            $eod->save();

            \Log::info("EOD start_time and end_time updated: Start Time - {$request->input('start_time')}, End Time - {$request->input('end_time')}");

            // Redirect back to the form with a success message
            return redirect()->route('eod.read')->with('toast_message', 'EOD record saved successfully');
        } catch (\Exception $e) {
            // Handle exceptions (e.g., log the error and return an error message)
            \Log::error('Error updating EOD record: ' . $e->getMessage());
            return redirect()->route('eod.read')->with('toast_message', 'An error occurred while saving the EOD record');
        }
    }

    

    
    

    
}
