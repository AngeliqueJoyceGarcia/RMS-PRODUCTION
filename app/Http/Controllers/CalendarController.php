<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarLegends;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;

class CalendarController extends Controller
{
    public function saveLegend(Request $request)
    {
        // Validate the incoming request data if necessary
        $validatedData = $request->validate([
            'selectedData' => 'required|string',
            'selectedColor' => 'required|string',
        ]);

        // Use the model method to update or create the legend based on the selected data
        CalendarLegends::updateOrCreate(
            ['name' => $validatedData['selectedData']],
            ['color' => $validatedData['selectedColor']]
        );

        // Flash a success message
        Session::flash('success', 'Legend saved successfully.');

        // Redirect back to the calendar settings page with the selected data
        return redirect()->route('calendar.manage', ['selectedData' => $validatedData['selectedData']]);
    }

   public function getTotalBookingsForCalendar()
    {
        // Fetch data from the bookings table and group it by the check-in date
        $bookings = DB::table('bookings')
            ->select(
                DB::raw('DATE(check_in) as date'),
                //walkin
                DB::raw('SUM(CASE WHEN reservation_type = "Walk-in" AND status = "check-in" THEN arrived_companion ELSE 0 END) as total_walkins'),
                DB::raw('SUM(CASE WHEN reservation_type = "Walk-in" AND status = "check-in" THEN children_qty ELSE 0 END) as children_qty_walkin'),
                DB::raw('SUM(CASE WHEN reservation_type = "Walk-in" AND status = "check-in" THEN adult_qty ELSE 0 END) as adult_qty_walkin'),
                DB::raw('SUM(CASE WHEN reservation_type = "Walk-in" AND status = "check-in" THEN senior_qty ELSE 0 END) as senior_qty_walkin'),

                //pending, inhouse
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "pending" THEN total_companion ELSE 0 END) as total_prebookInhouse'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "pending"  THEN children_qty ELSE 0 END) as children_qty_prebookInhouse'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "pending"  THEN adult_qty ELSE 0 END) as adult_qty_prebookInhouse'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "pending"  THEN senior_qty ELSE 0 END) as senior_qty_prebookInhouse'),

                //checkin, inhouse
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "check-in" THEN arrived_companion ELSE 0 END) as total_checkinInhouse'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "check-in"  THEN children_qty ELSE 0 END) as children_qty_checkinInhouse'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "check-in"  THEN adult_qty ELSE 0 END) as adult_qty_checkinInhouse'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book Inhouse" AND status = "check-in"  THEN senior_qty ELSE 0 END) as senior_qty_checkinInhouse'),

                //pending, daytour
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "pending" THEN total_companion ELSE 0 END) as total_prebookDayTour'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "pending"  THEN children_qty ELSE 0 END) as children_qty_prebookDayTour'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "pending"  THEN adult_qty ELSE 0 END) as adult_qty_prebookDayTour'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "pending"  THEN senior_qty ELSE 0 END) as senior_qty_prebookDayTour'),

                //checkin, daytour
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "check-in" THEN arrived_companion ELSE 0 END) as total_checkinDayTour'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "check-in"  THEN children_qty ELSE 0 END) as children_qty_checkinDayTour'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "check-in"  THEN adult_qty ELSE 0 END) as adult_qty_checkinDayTour'),
                DB::raw('SUM(CASE WHEN reservation_type = "Pre-book DayTour" AND status = "check-in"  THEN senior_qty ELSE 0 END) as senior_qty_checkinDayTour'),

                //pending, openbook
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "pending" THEN total_companion ELSE 0 END) as total_pendingopenbook'),
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "pending" THEN children_qty ELSE 0 END) as children_qty_pendingopenbook'),
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "pending" THEN adult_qty ELSE 0 END) as adult_qty_pendingopenbook'),
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "pending" THEN senior_qty ELSE 0 END) as senior_qty_pendingopenbook'),    

                //checkin, openbook
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "check-in" THEN arrived_companion ELSE 0 END) as total_checkinopenbook'),
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "check-in" THEN children_qty ELSE 0 END) as children_qty_checkinopenbook'),
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "check-in" THEN adult_qty ELSE 0 END) as adult_qty_checkinopenbook'),
                DB::raw('SUM(CASE WHEN reservation_type = "Open-book" AND status = "check-in" THEN senior_qty ELSE 0 END) as senior_qty_checkinopenbook'),    

                // canceled
                DB::raw('SUM(CASE WHEN status = "Canceled" THEN 1 ELSE 0 END) as total_canceled'),
                DB::raw('SUM(CASE WHEN status = "Canceled" THEN children_qty ELSE 0 END) as children_qty_canceled'),
                DB::raw('SUM(CASE WHEN status = "Canceled" THEN adult_qty ELSE 0 END) as adult_qty_canceled'),
                DB::raw('SUM(CASE WHEN status = "Canceled" THEN senior_qty ELSE 0 END) as senior_qty_canceled')
            )
            ->groupBy('date')
            ->havingRaw('(total_walkins > 0 OR 
                          total_prebookInhouse > 0 OR 
                          total_checkinInhouse > 0 OR 
                          total_prebookDayTour > 0 OR 
                          total_checkinDayTour > 0 OR
                          total_pendingopenbook > 0 OR
                          total_checkinopenbook > 0 OR
                          total_canceled > 0)') // Exclude date if total is 0
            ->get();

            // dd($bookings);


        return $bookings;
    }


    public function showCalendar()
    {
        //get color from database > Walkin
        $walkinsLegend = CalendarLegends::where('name', 'Walk-ins')->first();

        //get color from database > pending, inhouse
        $pendingPrebookInhouseLegend = CalendarLegends::where('name', 'Pending Pre-book Inhouse')->first();

        //get color from database > checkin, inhouse
        $checkinPrebookInhouseLegend = CalendarLegends::where('name', 'Check-in Pre-book Inhouse')->first();

        //get color from database > pending, daytour
        $pendingPrebookDayTourLegend = CalendarLegends::where('name', 'Pending Pre-book DayTour')->first();
 
        //get color from database > checkin, daytour
        $checkinPrebookDayTourLegend = CalendarLegends::where('name', 'Check-in Pre-book DayTour')->first();
        
        //get color from database > pending, openbook
        $pendingOpenBookLegend = CalendarLegends::where('name', 'Pending Open book')->first();

        //get color from database > checkin, openbook
        $checkinOpenBookLegend = CalendarLegends::where('name', 'Check-in Open book')->first();

        //get color from database > canceled
        $canceledBookLegend = CalendarLegends::where('name', 'Canceled Books')->first();


        //use color > Walkin
        $WalkinColor = $walkinsLegend ? $walkinsLegend->color : 'blue';

        //use color > pending, inhouse
        $pendingPrebookInhouseColor = $pendingPrebookInhouseLegend ? $pendingPrebookInhouseLegend->color : 'yellow';
        
        //use color > checkin, inhouse
        $checkinPrebookInhouseColor = $checkinPrebookInhouseLegend ? $checkinPrebookInhouseLegend->color : 'orange';
 
        //use color > pending, daytour
        $pendingPrebookDayTourColor = $pendingPrebookDayTourLegend ? $pendingPrebookDayTourLegend->color : 'green';

        //use color > checkin, daytour
        $checkinPrebookDayTourColor = $checkinPrebookDayTourLegend ? $checkinPrebookDayTourLegend->color : 'pink';

        //use color > pending, open book
        $pendingOpenBookColor = $pendingOpenBookLegend ? $pendingOpenBookLegend->color : 'gray';

        //use color > checkin, open book
        $checkinOpenBookColor = $checkinOpenBookLegend ? $checkinOpenBookLegend->color : 'brown';

        //use color > canceled
        $canceledBookColor = $canceledBookLegend ? $canceledBookLegend->color : 'red';


        // Fetch data for both "Walk-in" and "Pre-book" records
        $bookings = $this->getTotalBookingsForCalendar();

        return view('calendar.calendar', compact('bookings', 
                                                'WalkinColor', 
                                                'pendingPrebookInhouseColor', 
                                                'checkinPrebookInhouseColor',
                                                'pendingPrebookDayTourColor',
                                                'checkinPrebookDayTourColor',
                                                'pendingOpenBookColor',
                                                'checkinOpenBookColor',
                                                'canceledBookColor',));
    }


    public function showLegends()
    {
        // Fetch color information based on the selected data
        $selectedData = request()->input('selectedData');
        $legend = CalendarLegends::where('name', $selectedData)->first();
    
        // If a legend with the selected data exists, use its color; otherwise, use a default color
        $selectedColor = $legend ? $legend->color : 'blue';
    
        //get color from database > Walkin
        $walkinsLegend = CalendarLegends::where('name', 'Walk-ins')->first();

        //get color from database > pending, inhouse
        $pendingPrebookInhouseLegend = CalendarLegends::where('name', 'Pending Pre-book Inhouse')->first();

        //get color from database > checkin, inhouse
        $checkinPrebookInhouseLegend = CalendarLegends::where('name', 'Check-in Pre-book Inhouse')->first();

        //get color from database > pending, daytour
        $pendingPrebookDayTourLegend = CalendarLegends::where('name', 'Pending Pre-book DayTour')->first();

        //get color from database > checkin, daytour
        $checkinPrebookDayTourLegend = CalendarLegends::where('name', 'Check-in Pre-book DayTour')->first();
        
        //get color from database > pending, openbook
        $pendingOpenBookLegend = CalendarLegends::where('name', 'Pending Open book')->first();

        //get color from database > checkin, openbook
        $checkinOpenBookLegend = CalendarLegends::where('name', 'Check-in Open book')->first();

        //get color from database > canceled
        $canceledBookLegend = CalendarLegends::where('name', 'Canceled Books')->first();

  
        return view('calendar.calendarsetting', compact('selectedData', 
                                                        'selectedColor', 
                                                        'walkinsLegend', 
                                                        'pendingPrebookInhouseLegend', 
                                                        'checkinPrebookInhouseLegend',
                                                        'pendingPrebookDayTourLegend',
                                                        'checkinPrebookDayTourLegend',
                                                        'pendingOpenBookLegend',
                                                        'checkinOpenBookLegend',
                                                        'canceledBookLegend',));
    }


    public function showCalendarList()
    {

       //get color from database > Walkin
       $walkinsLegend = CalendarLegends::where('name', 'Walk-ins')->first();

       //get color from database > pending, inhouse
       $pendingPrebookInhouseLegend = CalendarLegends::where('name', 'Pending Pre-book Inhouse')->first();

       //get color from database > checkin, inhouse
       $checkinPrebookInhouseLegend = CalendarLegends::where('name', 'Check-in Pre-book Inhouse')->first();

       //get color from database > pending, daytour
       $pendingPrebookDayTourLegend = CalendarLegends::where('name', 'Pending Pre-book DayTour')->first();

       //get color from database > checkin, daytour
       $checkinPrebookDayTourLegend = CalendarLegends::where('name', 'Check-in Pre-book DayTour')->first();
       
       //get color from database > pending, openbook
       $pendingOpenBookLegend = CalendarLegends::where('name', 'Pending Open book')->first();

       //get color from database > checkin, openbook
       $checkinOpenBookLegend = CalendarLegends::where('name', 'Check-in Open book')->first();

       //get color from database > canceled
       $canceledBookLegend = CalendarLegends::where('name', 'Canceled Books')->first();


       //use color > Walkin
       $WalkinColor = $walkinsLegend ? $walkinsLegend->color : 'blue';

       //use color > pending, inhouse
       $pendingPrebookInhouseColor = $pendingPrebookInhouseLegend ? $pendingPrebookInhouseLegend->color : 'yellow';
       
       //use color > checkin, inhouse
       $checkinPrebookInhouseColor = $checkinPrebookInhouseLegend ? $checkinPrebookInhouseLegend->color : 'orange';

       //use color > pending, daytour
       $pendingPrebookDayTourColor = $pendingPrebookDayTourLegend ? $pendingPrebookDayTourLegend->color : 'green';

       //use color > checkin, daytour
       $checkinPrebookDayTourColor = $checkinPrebookDayTourLegend ? $checkinPrebookDayTourLegend->color : 'pink';

       //use color > pending, open book
       $pendingOpenBookColor = $pendingOpenBookLegend ? $pendingOpenBookLegend->color : 'gray';

       //use color > checkin, open book
       $checkinOpenBookColor = $checkinOpenBookLegend ? $checkinOpenBookLegend->color : 'brown';

       //use color > canceled
       $canceledBookColor = $canceledBookLegend ? $canceledBookLegend->color : 'red';


        // Fetch all booking records
        $bookings = Booking::all();

        // Separate bookings by reservation type
        $pendingInhousepreBookings = [];
        $checkinInhousepreBookings = [];
        $pendingDayTourpreBookings = [];
        $checkinDayTourpreBookings = [];
        $walkInBookings = [];
        $pendingOpenBookings = [];
        $checkinOpenBookings = [];
        $canceledBookings = [];


        foreach ($bookings as $booking) {
            if ($booking->reservation_type === 'Pre-book Inhouse' && $booking->status === 'pending') {
                $pendingInhousepreBookings[] = $booking;
            } elseif($booking->reservation_type === 'Pre-book Inhouse' && $booking->status === 'check-in'){
                $checkinInhousepreBookings[] = $booking;
            } elseif($booking->reservation_type === 'Pre-book DayTour' && $booking->status === 'pending'){
                $pendingDayTourpreBookings[] = $booking;
            } elseif($booking->reservation_type === 'Pre-book DayTour' && $booking->status === 'check-in'){
                $checkinDayTourpreBookings[] = $booking;
            } elseif ($booking->reservation_type === 'Walk-in' && $booking->status === 'check-in') {
                $walkInBookings[] = $booking;
            }  elseif ($booking->reservation_type === 'Open-book' && $booking->status === 'pending') {
                $pendingOpenBookings[] = $booking;
            } elseif ($booking->reservation_type === 'Open-book' && $booking->status === 'check-in') {
                $checkinOpenBookings[] = $booking;
            }

            if ($booking->status === 'Canceled') {
                $canceledBookings[] = $booking;
            }
        }

        // Sort both arrays by check_in time
        usort($pendingInhousepreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });
        
        usort($checkinInhousepreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        usort($pendingDayTourpreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        usort($checkinDayTourpreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        usort($walkInBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        usort($pendingOpenBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        usort($checkinOpenBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });
        
        usort($canceledBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });



        // Pass the sorted bookings data to the calendarList view
        return view('calendar.calendarList', compact('pendingInhousepreBookings', 
                                                    'checkinInhousepreBookings', 
                                                    'pendingDayTourpreBookings', 
                                                    'checkinDayTourpreBookings', 
                                                    'walkInBookings',
                                                    'pendingOpenBookings',
                                                    'checkinOpenBookings',
                                                    'canceledBookings',
                                                    'pendingPrebookInhouseColor',
                                                    'checkinPrebookInhouseColor',
                                                    'WalkinColor',
                                                    'pendingPrebookDayTourColor',
                                                    'checkinPrebookDayTourColor',
                                                    'pendingOpenBookColor',
                                                    'checkinOpenBookColor',
                                                    'canceledBookColor',));
    }

    
}
