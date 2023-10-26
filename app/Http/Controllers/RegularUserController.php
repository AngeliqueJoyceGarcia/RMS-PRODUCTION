<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\MaxPax;
use App\Models\CustomMaxPax;
use App\Models\Booking;

class RegularUserController extends Controller
{
    public function index(Request $request)
    {
        // Get the current date
        $today = Carbon::today();

        // dd($today);
        
        // Check if there is a record in CustomMaxPax for today with the name "Waterpark Capacity"
        $customMaxPaxToday = CustomMaxPax::where('event_date', $today)
            ->where('name', 'Waterpark Capacity')
            ->first();
        
        // If no CustomMaxPax record exists for today, use the data from MaxPax
        if (!$customMaxPaxToday) {
            $maxPax = MaxPax::where('name', 'Waterpark Capacity')->first();
        } else {
            $maxPax = $customMaxPaxToday;
        }
    
        // Calculate the total_companion for 'Pre-book Inhouse' bookings for today
        $totalCompanionPrebookInhouseToday = Booking::whereDate('check_in', $today)
            ->where('reservation_type', 'Pre-book Inhouse')
            ->sum('arrived_companion');
    
        // Calculate the total_companion for 'Pre-book DayTour' bookings for today
        $totalCompanionPrebookDayTourToday = Booking::whereDate('check_in', $today)
            ->where('reservation_type', 'Pre-book DayTour')
            ->sum('arrived_companion');
    
        // Calculate the total_companion for 'Walk-in' bookings for today
        $totalCompanionWalkinToday = Booking::whereDate('check_in', $today)
            ->where('reservation_type', 'Walk-in')
            ->sum('arrived_companion');
    
        // Calculate the total_companion for 'Open-book' bookings with status 'Open booking'
        $totalCompanionOpenBookings = Booking::where('reservation_type', 'Open-book')
            ->where('status', 'Open booking')
            ->sum('arrived_companion');
    
        // Calculate the total guests for 'Walk-in' bookings with status 'check-in' for today
        $totalGuestsWalkinToday = Booking::whereDate('check_in', $today)
            ->where('reservation_type', 'Walk-in')
            ->where('status', 'check-in')
            ->sum('arrived_companion');
    
        // Calculate the total guests for 'Pre-book Inhouse' bookings with status 'check-in' for today
        $totalGuestsPrebookInhouseToday = Booking::whereDate('check_in', $today)
            ->where('reservation_type', 'Pre-book Inhouse')
            ->where('status', 'check-in')
            ->sum('arrived_companion');
    
        // Calculate the total guests for 'Pre-book DayTour' bookings with status 'check-in' for today
        $totalGuestsPrebookDayTourToday = Booking::whereDate('check_in', $today)
            ->where('reservation_type', 'Pre-book DayTour')
            ->where('status', 'check-in')
            ->sum('arrived_companion');
    
        // Calculate the total guests for 'Open-book' bookings with status 'check-in' for today
        $totalGuestsOpenBookingsToday = Booking::whereDate('check_in', $today)
            ->where('reservation_type', 'Open-book')
            ->where('status', 'Open booking')
            ->sum('total_companion');

        // Calculate the total guests for the current week
        $totalGuestsWeekly = Booking::whereDate('check_in', '>=', $today->startOfWeek())
        ->whereDate('check_in', '<=', $today->endOfWeek())
        ->where('status', 'check-in')
        ->sum('arrived_companion');

        // Create a separate Carbon instance for the monthly calculation
        $monthlyDate = Carbon::now('Asia/Shanghai');

        // Calculate the total guests for the current month
        $totalGuestsMonthly = DB::table('bookings')
            ->whereYear('check_in', '=', $monthlyDate->year)
            ->whereMonth('check_in', '=', $monthlyDate->month)
            ->where('status', 'check-in')
            ->sum('arrived_companion');

        // Calculate the total guests for the current year
        $totalGuestsYearly = Booking::whereDate('check_in', '>=', $today->startOfYear())
            ->whereDate('check_in', '<=', $today->endOfYear())
            ->where('status', 'check-in')
            ->sum('arrived_companion');
        
    
        return view('admindashboard', [
            'maxPax' => $maxPax,
            'totalCompanionPrebookInhouseToday' => $totalCompanionPrebookInhouseToday,
            'totalCompanionPrebookDayTourToday' => $totalCompanionPrebookDayTourToday,
            'totalCompanionWalkinToday' => $totalCompanionWalkinToday,
            'totalCompanionOpenBookings' => $totalCompanionOpenBookings,
            'totalGuestsWalkinToday' => $totalGuestsWalkinToday,
            'totalGuestsPrebookInhouseToday' => $totalGuestsPrebookInhouseToday,
            'totalGuestsPrebookDayTourToday' => $totalGuestsPrebookDayTourToday,
            'totalGuestsOpenBookingsToday' => $totalGuestsOpenBookingsToday,
            'totalGuestsWeekly' => $totalGuestsWeekly,
            'totalGuestsMonthly' => $totalGuestsMonthly,
            'totalGuestsYearly' => $totalGuestsYearly,
        ]);
    }

}
