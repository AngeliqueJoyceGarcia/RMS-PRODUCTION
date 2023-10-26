<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DefaultEntranceRate;
use App\Models\EntranceRate;
use App\Models\CustomEvent;
use App\Models\CustomMaxPax;
use Illuminate\Support\Facades\DB;

class SetDefaultController extends Controller
{
    public function view()
    {
        $rates = EntranceRate::all();
        // Fetch the default entrance rates
        $defaultEntranceRate = DefaultEntranceRate::find(1); // Assuming you have only one row

        return view('entrance_rate.setdefault', compact('rates', 'defaultEntranceRate'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'weekday_default_rate' => 'required|exists:entrance_rates,id',
            'weekend_default_rate' => 'required|exists:entrance_rates,id',
        ]);

        // Store the selected rate IDs in the default_entrancerate table
        DefaultEntranceRate::updateOrCreate(
            ['id' => 1], // Assuming you have only one row in the default_entrancerate table
            [
                'weekday_rate_id' => $request->input('weekday_default_rate'),
                'weekend_rate_id' => $request->input('weekend_default_rate'),
            ]
        );

        // Redirect back to the form or any other page
        return redirect()->back()->with('success', 'Default rates have been set successfully.');
    }

    public function viewCustomRate()
    {
        $rates = EntranceRate::all();
        
        // Fetch custom events from the custom_event table
        $customEvents = CustomEvent::all();
    
        // Fetch custom max pax events from the custom_max_pax table
        $customMaxPaxEvents = CustomMaxPax::all();
    
        return view('entrance_rate.viewcustomrate', compact('rates', 'customEvents', 'customMaxPaxEvents'));
    }
    

    public function createEvent(Request $request)
    {
        // Validate the form data for creating custom events
        $request->validate([
            'rate_id' => 'required|exists:entrance_rates,id',
            'date' => 'required|date',
            'maxPaxqty' => 'required|integer', // Assuming quantity should be an integer
        ]);

        // Get the input values
        $rateId = $request->input('rate_id');
        $date = $request->input('date');

        $maxPaxqty = $request->input('maxPaxqty');

        // Use a try-catch block to handle any exceptions
        try {
            DB::beginTransaction();

            // Update or insert into the custom_max_pax table
            CustomMaxPax::updateOrInsert(
                [
                    'event_date' => $date,
                    'name' => 'Waterpark Capacity',
                ],
                [
                    'maximum_customers' => $maxPaxqty,
                ]
            );

            // Update or insert into the custom_event table
            CustomEvent::updateOrInsert(
                [
                    'event_date' => $date,
                ],
                [
                    'entrance_rate_id' => $rateId,
                ]
            );

            DB::commit();

            // Redirect to the desired page after successfully creating/updating the custom event
            return redirect()->route('setDefault.viewCustom')->with('success', 'Custom event created/updated successfully.');
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., database errors)
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }



}
