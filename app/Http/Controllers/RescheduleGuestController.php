<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EntranceRate;
use App\Models\CalendarLegends;
use App\Models\Booking;
use App\Models\ExtraChargeItem;
use App\Models\Billing;


class RescheduleGuestController extends Controller
{
    public function checkin_read()
    {

        $rates = EntranceRate::all();

       //get color from database > pending, inhouse
       $pendingPrebookInhouseLegend = CalendarLegends::where('name', 'Pending Pre-book Inhouse')->first();

       //get color from database > pending, daytour
       $pendingPrebookDayTourLegend = CalendarLegends::where('name', 'Pending Pre-book DayTour')->first();

       //use color > pending, inhouse
       $pendingPrebookInhouseColor = $pendingPrebookInhouseLegend ? $pendingPrebookInhouseLegend->color : 'yellow';
 
       //use color > pending, daytour
       $pendingPrebookDayTourColor = $pendingPrebookDayTourLegend ? $pendingPrebookDayTourLegend->color : 'green';

        // Fetch all booking records
        $bookings = Booking::all();

        // Separate bookings by reservation type
        $pendingInhousepreBookings = [];
        $pendingDayTourpreBookings = [];
    

        foreach ($bookings as $booking) {
            if ($booking->reservation_type === 'Pre-book Inhouse' && $booking->status === 'pending') {
                $pendingInhousepreBookings[] = $booking;
            } elseif($booking->reservation_type === 'Pre-book DayTour' && $booking->status === 'pending'){
                $pendingDayTourpreBookings[] = $booking;
            } 
        }

        // Sort both arrays by check_in time
        usort($pendingInhousepreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });
        
        usort($pendingDayTourpreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

      

        $items = ExtraChargeItem::all();


        // Pass the sorted bookings data to the calendarList view
        return view('reschedule.resched', compact('pendingInhousepreBookings',                                                  
                                                    'pendingDayTourpreBookings',                                                   
                                                    'pendingPrebookInhouseColor',                                                   
                                                    'pendingPrebookDayTourColor',
                                                    'items',
                                                    'bookings',
                                                    'rates',
                                                  ));
    }

    public function updateCheckIn(Request $request)
    {
        try {
            // Debugging: Log the incoming request data
            \Log::info('Request data:', $request->all());
    
            // Validate the incoming request data as needed
            $bookingId = $request->input('bookingId');
    
            // Find the booking by ID
            $booking = Booking::find($bookingId);
    
            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found.');
            }
    
            // Update all fields unconditionally
            $booking->check_in = $request->input('check_in');
          
            // Save the changes to the database
            $booking->save();
    
            $billing = Billing::where('booking_id', $bookingId)->first();
    
            if (!$billing) {
                return redirect()->back()->with('error', 'Billing record not found.');
            }
    
            // Update all fields in the Billing table unconditionally
            $billing->check_in = $request->input('check_in');

            // Save the changes to the Billing table
            $billing->save();
    
            return redirect()->back()->with('success', 'Booking details updated successfully.')->refresh();
        } catch (\Exception $e) {
            // Debugging: Log any exceptions
            \Log::error('Error updating booking details: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'Error updating booking details.');
        }
    }


}
