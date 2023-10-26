<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\GuestDetails;
use App\Models\Booking;
use App\Models\Billing;



class GuestController extends Controller
{
    public function read()
    {
        // Fetch all guests
        $guests = GuestDetails::all();
        $paginatedGuests = GuestDetails::paginate(11);

        // Fetch total companion and arrived companion for each guest
        foreach ($guests as $guest) {
            $guest->totalCompanion = Booking::where('name', $guest->name)->sum('total_companion');
            $guest->arrivedCompanion = Booking::where('name', $guest->name)->sum('arrived_companion');
        }

        // Fetch status and reservation type using a join query
        $guests = DB::table('guest_details')
            ->select('guest_details.*', 'bookings.status', 'bookings.reservation_type')
            ->join('bookings', 'guest_details.booking_id', '=', 'bookings.id')
            ->get();

        return view('guests.read', ['guests' => $guests, 'paginatedGuests' => $paginatedGuests]);
    }


    public function view($id)
    {
        // Fetch all guests
        $guests = GuestDetails::all();

        $paginatedGuests = GuestDetails::paginate(11);

        // Fetch the specific guest by ID
        $guest = GuestDetails::find($id);

        // Check if the guest exists
        if (!$guest) {
            // Handle the case where the guest with the given ID doesn't exist
            return redirect()->route('guests.read')->with('error', 'Guest not found.');
        }
    
        return view('guests.view', ['guests' => $guests, 'paginatedGuests' => $paginatedGuests, 'guest' => $guest]);
    }


    public function edit($id)
    {
        // Fetch all guests
        $guests = GuestDetails::all();

        $paginatedGuests = GuestDetails::paginate(11);

        // Fetch the specific guest by ID
        $guest = GuestDetails::find($id);

        // Check if the guest exists
        if (!$guest) {
            // Handle the case where the guest with the given ID doesn't exist
            return redirect()->route('reguserguests.view')->with('error', 'Guest not found.');
        }
    
        return view('guests.edit', ['guests' => $guests, 'paginatedGuests' => $paginatedGuests, 'guest' => $guest]);
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming request data as needed

        // Find the guest by ID
        $guest = GuestDetails::find($id);

        if (!$guest) {
            // Handle the case where the guest with the given ID doesn't exist
            return redirect()->route('reguserguests.view')->with('error', 'Guest not found.');
        }

        // Update the "Status" and "Arrived Companion" in the "Booking" table
        $guest->booking->status = $request->input('status');
        $guest->booking->arrived_companion = $request->input('arrivedCompanion');
        $guest->booking->save();

        // Update the "Status" and "Arrived Companion" in the "Billing" table
        $billing = Billing::where('booking_id', $guest->booking->id)->first();
        if ($billing) {
            $billing->status = $request->input('status');
            $billing->arrived_companion = $request->input('arrivedCompanion');
            $billing->save();
        }

        // Redirect back with a success message
        return redirect()->route('guests.read')->with('success', 'Guest details updated successfully.');
    }


 }

