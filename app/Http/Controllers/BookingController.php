<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\EntranceRate;
use App\Models\GuestDetails;
use App\Models\Billing;
use App\Models\ExtraChargeItem;
use App\Models\DayTourTimeSetting;
use App\Models\MaxPax;
use App\Models\DefaultEntranceRate;
use App\Models\CalendarLegends;
use App\Models\CustomEvent;
use App\Models\BankCommission;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function read(){
        $bookings = Booking::where('status', '!=', 'Canceled')->get();
        return view('booking.read', compact('bookings'));        
    }

    public function read_canceled(){
        $bookings = Booking::where('status', 'Canceled')->get();
        return view('booking.read_cancel', compact('bookings'));
    }

    public function create(Request $request) 
    {
        // Fetch all entrance rates from the database
        $entranceRates = EntranceRate::all();

        // Fetch all users from the database
        $users = User::all();

        // Check if today is a weekday (Monday to Friday)
        $today = Carbon::now();
        $isWeekday = $today->isWeekday();

        $commissions = BankCommission::all();
    
        // Retrieve the 'maximum_pax' value from the MaxPax model
        $maxPaxSetting = MaxPax::find(1); // Assuming you have a record with ID 1
    
        // Fetch all item names from the database
        $items = ExtraChargeItem::select('item_name', 'price')->get()->toArray();
    
        $totalCompanions = Booking::whereDate('check_in', '=', now()->toDateString())
            ->sum('total_companion');
    
        // Check if there's a custom rate set for today's date
        $customRate = CustomEvent::where('event_date', '=', now()->toDateString())->first();
    
        if ($customRate) {
            // Use the custom rate if found for today's date
            $entranceRate = EntranceRate::find($customRate->entrance_rate_id);
        } else {
            // Determine which rate to use based on whether today is a weekday or weekend
            $defaultRateField = $isWeekday ? 'weekday_rate_id' : 'weekend_rate_id';
            $defaultEntranceRate = DefaultEntranceRate::find(1); // Assuming you have only one row
    
            if ($defaultEntranceRate) {
                $rateId = $defaultEntranceRate->$defaultRateField;
                $entranceRate = EntranceRate::find($rateId);
            } else { //case if the default entrance rate is not yet set
                return redirect()->route('setDefault.view');
            }
        }
    
        if ($entranceRate) {
            $currentRate = [
                'rate_name' => $entranceRate->rate_name,
                'baseChildPrice' => $entranceRate->baseChildPrice,
                'baseAdultPrice' => $entranceRate->baseAdultPrice,
                'baseSeniorPrice' => $entranceRate->baseSeniorPrice,
                'vatsc_childprice' => $entranceRate->vatsc_childprice,
                'vatsc_adultprice' => $entranceRate->vatsc_adultprice,
                'vatsc_seniorprice' => $entranceRate->vatsc_seniorprice,
                'servicecharge' => $entranceRate->servicecharge,
                'vat' => $entranceRate->vat,
            ];
        } else {
            // Handle the case where the entrance rate with the retrieved ID is not found
            $currentRate = [
                'rate_name' => 'Default Rate (Not Found)',
                // Set other fields to default values or handle as needed
            ];
        }
    
        if ($maxPaxSetting) {
            // Calculate 5% of the maximum pax
            $maxPax = $maxPaxSetting->maximum_pax;
            // Show the warning popup if conditions are met
            $showWarningPopup = (
                $totalCompanions >= ($maxPax - 10) && $totalCompanions <= ($maxPax - 1) ||
                $totalCompanions == $maxPax ||
                $totalCompanions > $maxPax
            );
            
            return view('booking.create', [
                'currentRate' => $currentRate, 
                'items' => $items,
                'maxPaxSetting' => $maxPaxSetting,
                'showWarningPopup' => $showWarningPopup, // Pass the showWarningPopup variable to the view
                'totalCompanions' => $totalCompanions,
                'maxPax' => $maxPax,
                'entranceRates' => $entranceRates,
                'users' => $users,
                'commissions' => $commissions,
            ]);
        } else {
            session()->flash('toast_message', 'Set the Max Pax Setting first');
            // Redirect to the maxpax.create route
            return redirect()->route('maxpax.create');
        }
    }
    

    public function nextRate(Request $request)
    {
        // Fetch the entrance rates
        $entranceRates = EntranceRate::all();

        // Increment the current rate index in the session
        $currentIndex = $request->session()->get('current_rate_index', 0);
        $currentIndex = ($currentIndex + 1) % count($entranceRates);
        $request->session()->put('current_rate_index', $currentIndex);

        // Redirect back to the create page
        return redirect()->route('booking.create');
    }

    public function store(Request $request)
    {
        //dd($request);
        try {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'reservation_type' => 'required|string',
            'children_qty' => 'required|integer',
            'adult_qty' => 'required|integer',
            'senior_qty' => 'nullable|integer',
            'total_companion' => 'required|integer',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'name' => 'required|string',
            'contact_num' => 'required|string',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'fbname' => 'nullable|string',
            'bday' => 'nullable|date',
            'total_itemprice' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'payment_mode' => 'required|string',
            'commission' => 'nullable|string',
            'approval_code' => 'nullable|string',
            'reference_num' => 'nullable|string',
            'card_num' => 'nullable|string',
            'acc_name' => 'nullable|string',
            'file_attach' => 'nullable|file',
            'confirm_number' => 'nullable|string',
            'password_admin' => 'nullable|string',
            'gc_number' => 'nullable|string',
            'validity' => 'nullable|string',
            'worth' => 'nullable|string',
            'total_amount_paid' => 'required|numeric',
            'balance' => 'required|numeric',
            'status' => 'required|string',
            'item_names' => 'nullable|string',
            'quantities' => 'nullable|string',
            'prices' => 'nullable|string',
            'rate_name' => 'required|string',       
            'returnedTowelQty' => 'nullable|numeric',
            'returnedWristBandQty' => 'nullable|numeric',
            'claimableRefund' => 'nullable|numeric',
            'refundablePrice' => 'nullable|string',
            'baseChildPrice' => 'required|string',
            'baseAdultPrice' => 'required|string',
            'baseSeniorPrice' => 'required|string',
            'vatsc_childprice' => 'required|string',
            'vatsc_adultprice' => 'required|string',
            'vatsc_seniorprice' => 'required|string',
            'vat' => 'required|integer',
            'servicecharge' => 'required|integer',
            'total_prebookAmount' => 'required|numeric',
            'checkin_payment' => 'nullable|integer',
            'remarks'  => 'nullable|string',
            'vatCheckbox'  => 'nullable',
            'scCheckbox'  => 'nullable',
        ]);

            // Set the status based on the reservation type
            if ($validatedData['reservation_type'] === 'Walk-in') {
                $validatedData['status'] = 'check-in';
                // Make arrived_companion field required for Walk-in reservations
                $validatedData['arrived_companion'] = $request->input('arrived_companion');
            } elseif ($validatedData['reservation_type'] === 'Pre-book DayTour' || $validatedData['reservation_type'] === 'Pre-book Inhouse') {
                $validatedData['status'] = 'pending';
                // Make arrived_companion field nullable for Pre-book reservations
                $validatedData['arrived_companion'] = 0;
            } elseif ($validatedData['reservation_type'] === 'Open-book') {
                $validatedData['status'] = 'Open booking';
                // Make arrived_companion field nullable for Pre-book reservations
                $validatedData['arrived_companion'] = 0;
            }

         // Fetch the selected rate from the database based on the rate_name
         $selectedRate = EntranceRate::where('rate_name', $validatedData['rate_name'])->first();

         if (!$selectedRate) {
             // Handle the case where the selected rate is not found in the database
             return redirect()->back()->with('error', 'Selected rate not found.');
         }

          // Update the validated data with the rates and prices from the database
            $validatedData['baseChildPrice'] = $selectedRate->baseChildPrice;
            $validatedData['baseAdultPrice'] = $selectedRate->baseAdultPrice;
            $validatedData['baseSeniorPrice'] = $selectedRate->baseSeniorPrice;
            $validatedData['vatsc_childprice'] = $selectedRate->vatsc_childprice;
            $validatedData['vatsc_adultprice'] = $selectedRate->vatsc_adultprice;
            $validatedData['vatsc_seniorprice'] = $selectedRate->vatsc_seniorprice;
            $validatedData['vat'] = $selectedRate->vat;
            $validatedData['servicecharge'] = $selectedRate->servicecharge;

            // Check if the selected payment method is "Complimentary"
            if ($validatedData['payment_mode'] === 'Complimentary') {
                // Retrieve all users with the "SuperAdmin" role
                $superAdmins = User::whereHas('role', function ($query) {
                    $query->where('role_name', 'SuperAdmin');
                })->get();

                $correctPassword = false;

                // Check each SuperAdmin user's password
                foreach ($superAdmins as $superAdmin) {
                    if (Hash::check($validatedData['password_admin'], $superAdmin->password)) {
                        $correctPassword = true;
                        break; // Stop checking once we find a correct password
                    }
                }

                if ($correctPassword) {
                    // Set the total amount paid to 0
                    $validatedData['total_amount_paid'] = 0;
                } else {
                    // If no SuperAdmin has the correct password, you can handle it here (e.g., show an error message)
                    return redirect()->back()->with('error', 'SuperAdmin Password is incorrect.');
                }
            }

            // Check if "remarks" is null and set it to an empty string if it is
            if (is_null($validatedData['remarks'])) {
                $validatedData['remarks'] = '';
            }

            // Determine the 'status' value based on 'reservation_type'
            if ($validatedData['reservation_type'] === 'Open-book') {
                $validatedData['check_in'] = '';
                $validatedData['check_out'] = '';
            }


            // Check if the 'vatCheckbox' is present and has a truthy value
            if ($validatedData['vatCheckbox'] === 'false') {
                $validatedData['vat'] = 0;
            }

            // Check if the 'scCheckbox' is present and has a truthy value
            if ($validatedData['scCheckbox'] === 'false') {
                $validatedData['servicecharge'] = 0;
            }

            // Create a new Booking instance with the validated data
            $booking = Booking::create($validatedData);

            // Record for billing
            $billingData = [
                'booking_id' => $booking->id,
                'name' => $validatedData['name'],
                'bday' => $validatedData['bday'],

                'check_in' => $validatedData['check_in'],
                'check_out' => $validatedData['check_out'],
                'reservation_type' => $validatedData['reservation_type'],
                'children_qty' => $validatedData['children_qty'],
                'adult_qty' => $validatedData['adult_qty'],
                'senior_qty' => $validatedData['senior_qty'],
                
                'total_companion' => $validatedData['total_companion'],
                'arrived_companion' => $validatedData['arrived_companion'],

                'rate_name' => $validatedData['rate_name'],

                'baseChildPrice' => $validatedData['baseChildPrice'],
                'baseAdultPrice' => $validatedData['baseAdultPrice'],
                'baseSeniorPrice' => $validatedData['baseSeniorPrice'],

                'vat' => $validatedData['vat'],
                'service_charge' => $validatedData['servicecharge'],

                'vatsc_childprice' => $validatedData['vatsc_childprice'],
                'vatsc_adultprice' => $validatedData['vatsc_adultprice'],
                'vatsc_seniorprice' => $validatedData['vatsc_seniorprice'],

                'returnedTowelQty' => $validatedData['returnedTowelQty'],
                'returnedWristBandQty' => $validatedData['returnedWristBandQty'],
                'claimableRefund' => $validatedData['claimableRefund'],
          
                'refundablePrice' => $validatedData['refundablePrice'],

                'total_itemprice' => $validatedData['total_itemprice'],
                'total_amount' => $validatedData['total_amount'],

                'payment_mode' => $validatedData['payment_mode'],
                
                'commission' => $validatedData['commission'],
                'approval_code' => $validatedData['approval_code'],
                'gc_number' => $validatedData['gc_number'],
                'confirm_number' => $validatedData['confirm_number'],
                'password_admin' => $validatedData['password_admin'],
                'validity' => $validatedData['validity'],
                'worth' => $validatedData['worth'],
                'reference_num' => $validatedData['reference_num'],
                'card_num' => $validatedData['card_num'],
                'acc_name' => $validatedData['acc_name'],
                'total_amount_paid' => $validatedData['total_amount_paid'],
                'total_prebookAmount' => $validatedData['total_prebookAmount'],
                'balance' => $validatedData['balance'],
                'checkin_payment' => $validatedData['checkin_payment'],
                'status' => $validatedData['status'],
                'remarks' => $validatedData['remarks'],
            ];

            Billing::create($billingData);

            $guestDetails = [
                'booking_id' => $booking->id,
                'name' => $validatedData['name'],
                'contact_num' => $validatedData['contact_num'],
                'address' => $validatedData['address'],
                'email' => $validatedData['email'],
                'fbname' => $validatedData['fbname'],
                'bday' => $validatedData['bday'],
                'total_prebookAmount' => $validatedData['total_prebookAmount'],
            ];

            GuestDetails::create($guestDetails);

            


            // Redirect to a success page or return a response
            if (auth()->user()->role_id === 1) {
                return redirect()->route('booking.create')->with('success', 'Booking created successfully');
            } else {
                return redirect()->route('reguserbooking.create')->with('success', 'Booking created successfully');
            }
        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            // Handle exceptions here, you can log or display an error message
            return redirect()->back()->with('error', "There was an error saving the data to the database. ");
        }
    }

    public function edit(Booking $booking){
        $booking = Booking::findOrFail($booking->id);
        return view('booking.edit', ['booking' => $booking]);
    }

    public function update(Request $request, Booking $booking){
        $data = $request->validate([
            'arrived_companion' => 'required|integer',
        ]);

        // Update the arrived_companion field
        $booking->arrived_companion = $data['arrived_companion'];

        // Save the changes
        $booking->save();

        return redirect(route('booking.read'))->with('success', 'Updated Successfully');
    }

    public function view_cancelBook($booking)
    {
        // Retrieve the booking details based on the provided ID
        $canceledbook = Booking::find($booking);

        // Check if a booking with the given ID exists
        if (!$canceledbook) {
            abort(404); // Or handle the case when the booking is not found
        }

        $bookings = Booking::all();

        // Pass the booking details and the $bookings variable to the view
        return view('booking.cancel_booking', ['booking' => $canceledbook, 'bookings' => $bookings]);
    }

    public function update_cancelBook(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'remarks' => 'required'
        ]);
    
        $newStatus = 'Canceled';
    
        // Update the Booking status and remarks
        $booking->update([
            'status' => $newStatus,
            'remarks' => $data['remarks']
        ]);
    
        // Find the associated Billing record
        $billing = Billing::where('booking_id', $booking->id)->first();
    
        if ($billing) {
            // Update the Billing status and remarks
            $billing->update([
                'status' => $newStatus,
                'remarks' => $data['remarks']
            ]);
        }
    
        return redirect()->route(auth()->user()->role_id === 1 ? 'booking.read.cancel' : 'reguser.read.cancel')->with('success', 'Updated Successfully');
    }
    

    // prebook monitoring
    public function arrivalTracker()
    {
        $paginatedBookings = Booking::where('status', '!=', 'Canceled')->paginate(11);
    
        $bookings = Booking::where(function($query) {
            $query->where('reservation_type', 'Pre-book Inhouse')
                ->orWhere('reservation_type', 'Pre-book DayTour');
        })
        ->where('status', '!=', 'Canceled')
        ->with('billings') // Eager load the billings relationship
        ->get();
    
        return view('booking.arrival', ['bookings' => $bookings, 'paginatedBookings' => $paginatedBookings]);
    }
     

    public function departureTracker()
    {
        $paginatedBookings = Booking::paginate(11);
        $bookings = Booking::where('reservation_type', 'Pre-book Inhouse')
            ->orWhere('reservation_type', 'Pre-book DayTour')
            ->orWhere('reservation_type', 'Open-book')
            ->with('billings') // Eager load the billings relationship
            ->get();
    
        return view('booking.departure', ['bookings' => $bookings, 'paginatedBookings' => $paginatedBookings]);
    }
    
    public function arival_edit(Billing $billing)
    {
        $items = ExtraChargeItem::all();
        return view('booking.arrival_edit', compact('billing', 'items'));
    }

    public function arrival_update(Request $request, Billing $billing)
    {

        // Validate the form data
        $data = $request->validate([
            'arrived_companion' => 'required|integer',
            'checkin_payment' => 'required|numeric',
            'remarks' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Calculate the updated total_amount_paid
        $updatedTotalAmountPaid = $billing->total_amount_paid + $data['checkin_payment'];

        // Update the billing data
        $billing->update([
            'arrived_companion' => $data['arrived_companion'],
            'total_amount_paid' => $updatedTotalAmountPaid,
            'remarks' => $data['remarks'] ?? '', // Use an empty string if remarks is null
            'checkin_payment' => $data['checkin_payment'],
            'status' => $data['status'],
        ]);

        // Now, update the corresponding booking record
        $booking = $billing->booking; // Get the associated booking

        // Update the booking data based on billing
        $booking->update([
            'arrived_companion' => $data['arrived_companion'],
            'total_amount_paid' => $updatedTotalAmountPaid,
            'remarks' => $data['remarks'] ?? '',
            'status' => $data['status'],
        ]);

        if (auth()->user()->role_id === 1) {
            return redirect()->route('prebook.checkin')->with('success', 'Billing and Booking updated successfully');
        } else {
            return redirect()->route('reguser.checkin')->with('success', 'Billing and Booking updated successfully');
        }

    }


    public function departure_edit(Billing $billing)
    {
        return view('booking.departure_edit', compact('billing'));
    }
    
   
    public function departure_update(Request $request, Billing $billing)
    {
        // Validate the form data
        $data = $request->validate([
            'check_out' => 'required|date',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
    
        // Update the billing data
        $billing->update([
            'check_out' => $data['check_out'],
            'status' => $data['status'],
            'remarks' => $data['remarks'] ?? '', // Use an empty string if remarks is null
        ]);
    
        // Now, update the corresponding booking record
        $booking = $billing->booking; // Get the associated booking
    
        // Update the booking data based on billing
        $booking->update([
            'check_out' => $data['check_out'],
            'status' => $data['status'],
            'remarks' => $data['remarks'] ?? '',
        ]);
    
        if (auth()->user()->role_id === 1) {
            return redirect()->route('prebook.checkout')->with('success', 'Billing and Booking updated successfully');
        } else {
            return redirect()->route('reguser.checkout')->with('success', 'Billing and Booking updated successfully');
        }
    }

    public function checkin_read()
    {
       //get color from database > pending, inhouse
       $pendingPrebookInhouseLegend = CalendarLegends::where('name', 'Pending Pre-book Inhouse')->first();

       //get color from database > pending, daytour
       $pendingPrebookDayTourLegend = CalendarLegends::where('name', 'Pending Pre-book DayTour')->first();

       //get color from database > pending, openbook
       $pendingOpenBookLegend = CalendarLegends::where('name', 'Pending Open book')->first();

       //use color > pending, inhouse
       $pendingPrebookInhouseColor = $pendingPrebookInhouseLegend ? $pendingPrebookInhouseLegend->color : 'yellow';
 
       //use color > pending, daytour
       $pendingPrebookDayTourColor = $pendingPrebookDayTourLegend ? $pendingPrebookDayTourLegend->color : 'green';

       //use color > pending, open book
       $pendingOpenBookColor = $pendingOpenBookLegend ? $pendingOpenBookLegend->color : 'gray';

        // Fetch all booking records
        $bookings = Booking::all();

        // Separate bookings by reservation type
        $pendingInhousepreBookings = [];
        $pendingDayTourpreBookings = [];
        $pendingOpenBookings = [];
  

        foreach ($bookings as $booking) {
            if ($booking->reservation_type === 'Pre-book Inhouse' && $booking->status === 'pending') {
                $pendingInhousepreBookings[] = $booking;
            } elseif($booking->reservation_type === 'Pre-book DayTour' && $booking->status === 'pending'){
                $pendingDayTourpreBookings[] = $booking;
            } elseif ($booking->reservation_type === 'Open-book' && $booking->status === 'pending') {
                $pendingOpenBookings[] = $booking;
            } 
        }

        // Sort both arrays by check_in time
        usort($pendingInhousepreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });
        
        usort($pendingDayTourpreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        usort($pendingOpenBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        $items = ExtraChargeItem::all();


        // Pass the sorted bookings data to the calendarList view
        return view('booking.checkinList', compact('pendingInhousepreBookings',                                                  
                                                    'pendingDayTourpreBookings',                                                  
                                                    'pendingOpenBookings',                                                   
                                                    'pendingPrebookInhouseColor',                                                   
                                                    'pendingPrebookDayTourColor',
                                                    'pendingOpenBookColor',
                                                    'items',
                                                    'bookings',
                                                  ));
    }

    public function updateBookingDetails(Request $request)
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
            $booking->arrived_companion = $request->input('arrived_companion');
            $booking->check_in = $request->input('check_in');
            $booking->checkin_payment = $request->input('checkin_payment');
            $booking->remarks = $request->input('remarks') ?? '';
            $booking->status = 'check-in';

            $booking->total_amount_paid = $request->input('totalAmountPaid') + $request->input('checkin_payment');
    
            // Save the changes to the database
            $booking->save();
    
            $billing = Billing::where('booking_id', $bookingId)->first();
    
            if (!$billing) {
                return redirect()->back()->with('error', 'Billing record not found.');
            }
    
            // Update all fields in the Billing table unconditionally
            $billing->arrived_companion = $request->input('arrived_companion');
            $billing->check_in = $request->input('check_in');
            $billing->checkin_payment = $request->input('checkin_payment');
            $booking->remarks = $request->input('remarks') ?? '';
            $billing->status = 'check-in';

            $billing->total_amount_paid = $request->input('totalAmountPaid') + $request->input('checkin_payment');
    
            // Save the changes to the Billing table
            $billing->save();
    
            return redirect()->back()->with('success', 'Booking details updated successfully.')->refresh();
        } catch (\Exception $e) {
            // Debugging: Log any exceptions
            \Log::error('Error updating booking details: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'Error updating booking details.');
        }
    }

    public function checkout_read()
    {
       //get color from database > Walkin
       $walkinsLegend = CalendarLegends::where('name', 'Walk-ins')->first();

       //get color from database > checkin, inhouse
       $checkinPrebookInhouseLegend = CalendarLegends::where('name', 'Check-in Pre-book Inhouse')->first();

       //get color from database > checkin, daytour
       $checkinPrebookDayTourLegend = CalendarLegends::where('name', 'Check-in Pre-book DayTour')->first();

        //use color > Walkin
        $WalkinColor = $walkinsLegend ? $walkinsLegend->color : 'blue';

        //use color > checkin, inhouse
        $checkinPrebookInhouseColor = $checkinPrebookInhouseLegend ? $checkinPrebookInhouseLegend->color : 'orange';

        //use color > checkin, daytour
        $checkinPrebookDayTourColor = $checkinPrebookDayTourLegend ? $checkinPrebookDayTourLegend->color : 'pink';

        // Fetch all booking records
        $billings = Billing::all();

        // Separate bookings by reservation type
        $checkinInhousepreBookings = [];
        $checkinDayTourpreBookings = [];
        $walkInBookings = [];
  

        foreach ($billings as $billing) {
            if ($billing->reservation_type === 'Pre-book Inhouse' && $billing->status === 'check-in'){
                $checkinInhousepreBookings[] = $billing;
            } elseif($billing->reservation_type === 'Pre-book DayTour' && $billing->status === 'check-in'){
                $checkinDayTourpreBookings[] = $billing;
            } elseif ($billing->reservation_type === 'Walk-in' && $billing->status === 'check-in') {
                $walkInBookings[] = $billing;
            }
        }

        // Sort both arrays by check_in time
        usort($checkinInhousepreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });
        
        usort($checkinDayTourpreBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });

        usort($walkInBookings, function ($a, $b) {
            return strtotime($a->check_in) - strtotime($b->check_in);
        });


        $items = ExtraChargeItem::all();


        // Pass the sorted bookings data to the calendarList view
        return view('booking.checkoutList', compact('checkinInhousepreBookings',                                                  
                                                    'checkinDayTourpreBookings',                                                  
                                                    'walkInBookings',                                                   
                                                    'WalkinColor',                                                   
                                                    'checkinPrebookInhouseColor',
                                                    'checkinPrebookDayTourColor',
                                                    'items',
                                                    'billings',
                                                  ));
    }

    public function updateCheckout(Request $request)
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
            $booking->check_out = $request->input('check_out');
            $booking->remarks = $request->input('remarks') ?? '';
            $booking->status = 'check-out';
    
            // Save the changes to the database
            $booking->save();
    
            $billing = Billing::where('booking_id', $bookingId)->first();
    
            if (!$billing) {
                return redirect()->back()->with('error', 'Billing record not found.');
            }
    
            // Update all fields in the Billing table unconditionally
            $billing->check_out = $request->input('check_out');
            $booking->remarks = $request->input('remarks') ?? '';
            $billing->status = 'check-out';
    
            // Save the changes to the Billing table
            $billing->save();
    
            return redirect()->back()->with('success', 'Booking details updated successfully.')->refresh();
        } catch (\Exception $e) {
            // Debugging: Log any exceptions
            \Log::error('Error updating booking details: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'Error updating booking details.');
        }
    }


    


    // open book 
    public function openBookTracker()
    {
        $paginatedBookings = Booking::paginate(11);
        $bookings = Booking::where('reservation_type', 'open-book')
            ->where('status', '!=', 'Canceled') 
            ->with('billings') // Eager load the billings relationship
            ->get();
    
        return view('booking.open_booking', ['bookings' => $bookings, 'paginatedBookings' => $paginatedBookings]);
    }

    public function openBookCheckIn()
    {
        $paginatedBookings = Booking::paginate(11);
        $bookings = Booking::where('reservation_type', 'open-book')
            ->where('status', '!=', 'Canceled') 
            ->with('billings') // Eager load the billings relationship
            ->get();
    
        return view('booking.open_book_checkin', ['bookings' => $bookings, 'paginatedBookings' => $paginatedBookings]);
    }    

    public function openBookCheckOut()
    {
        $paginatedBookings = Booking::paginate(11);
        $bookings = Booking::where('reservation_type', 'open-book')
            ->where('status', '!=', 'Canceled') 
            ->with('billings') // Eager load the billings relationship
            ->get();
    
        return view('booking.open_book_checkout', ['bookings' => $bookings, 'paginatedBookings' => $paginatedBookings]);
    }

    public function openBookEdit(Billing $billing)
    {
        $rates = EntranceRate::all();
        return view('booking.open_book_edit', compact('billing', 'rates'));
    }

    public function openBookUpdate(Request $request, Billing $billing)
    {
        //dump($request);
        // Validate the form data
        $data = $request->validate([
            'rateNameSelect' => 'required',
            'childRate' => 'required',
            'adultRate' => 'required',
            'seniorRate' => 'required',
            'vat' => 'required',
            'serviceCharge' => 'required',
            'childRateVAT' => 'required',
            'adultRateVAT' => 'required',
            'seniorRateVAT' => 'required',
            'check_in' => 'required',
            'total_amount' => 'required',
            'balance' => 'required',
            'remarks' => 'nullable|string',
        ]);

        // Update the billing data
        $billing->update([
            'rate_name' => $data['rateNameSelect'],
            'baseChildPrice' => $data['childRate'],
            'baseAdultPrice' => $data['adultRate'],
            'baseSeniorPrice' => $data['seniorRate'],
            'vat' => $data['vat'],
            'service_charge' => $data['serviceCharge'],
            'vatsc_childprice' => $data['childRateVAT'],
            'vatsc_adultprice' => $data['adultRateVAT'],
            'vatsc_seniorprice' => $data['seniorRateVAT'],
            'check_in' => $data['check_in'],
            'total_prebookAmount' => $data['total_amount'],
            'balance' => $data['balance'],
            'status' => 'pending',
            'remarks' => $data['remarks'] ?? '', // Use an empty string if remarks is null
        ]);

        // Now, update the corresponding booking record
        $booking = $billing->booking; // Get the associated booking

        // Update the booking data based on billing
        $booking->update([
            'rate_name' => $data['rateNameSelect'],
            'baseChildPrice' => $data['childRate'],
            'baseAdultPrice' => $data['adultRate'],
            'baseSeniorPrice' => $data['seniorRate'],
            'vat' => $data['vat'],
            'service_charge' => $data['serviceCharge'],
            'vatsc_childprice' => $data['childRateVAT'],
            'vatsc_adultprice' => $data['adultRateVAT'],
            'vatsc_seniorprice' => $data['seniorRateVAT'],
            'check_in' => $data['check_in'],
            'total_prebookAmount' => $data['total_amount'],
            'balance' => $data['balance'],
            'status' => 'pending',
            'remarks' => $data['remarks'] ?? '',
        ]);

        if (auth()->user()->role_id === 1) {
            return redirect()->route('openbook.read')->with('success', 'Billing and Booking updated successfully');
        } else {
            return redirect()->route('reguser.openbook')->with('success', 'Billing and Booking updated successfully');
        }
    }

    public function openBookCheckinEdit(Billing $billing)
    {
        $items = ExtraChargeItem::all();
        return view('booking.edit_openbook_checkin', compact('billing', 'items'));
    }

    public function openBookCheckinUpdate(Request $request, Billing $billing)
    {

        // Validate the form data
        $data = $request->validate([
            'arrived_companion' => 'required|integer',
            'checkin_payment' => 'required|numeric',
            'remarks' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Calculate the updated total_amount_paid
        $updatedTotalAmountPaid = $billing->total_amount_paid + $data['checkin_payment'];

        // Get the current date and time
        $currentDateTime = Carbon::now();

        // Update the billing data
        $billing->update([
            'check_in' => $currentDateTime,
            'arrived_companion' => $data['arrived_companion'],
            'total_amount_paid' => $updatedTotalAmountPaid,
            'total_amount' => $updatedTotalAmountPaid,
            'remarks' => $data['remarks'] ?? '', // Use an empty string if remarks is null
            'checkin_payment' => $data['checkin_payment'],
            'status' => $data['status'],
        ]);

        // Now, update the corresponding booking record
        $booking = $billing->booking; // Get the associated booking

        // Update the booking data based on billing
        $booking->update([
            'check_in' => $currentDateTime,
            'arrived_companion' => $data['arrived_companion'],
            'total_amount_paid' => $updatedTotalAmountPaid,
            'remarks' => $data['remarks'] ?? '',
            'status' => $data['status'],
        ]);

        if (auth()->user()->role_id === 1) {
            return redirect()->route('openbook.checkin')->with('success', 'Billing and Booking updated successfully');
        } else {
            return redirect()->route('reguser.openbook.checkin')->with('success', 'Billing and Booking updated successfully');
        }

    }

    public function openBookCheckoutEdit(Billing $billing)
    {
        $items = ExtraChargeItem::all();
        return view('booking.edit_openbook_checkout', compact('billing', 'items'));
    }

    public function openBookCheckoutUpdate(Request $request, Billing $billing)
    {
        // Validate the form data
        $data = $request->validate([
            'check_out' => 'required',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
    
        // Update the billing data
        $billing->update([
            'check_out' => $data['check_out'],
            'status' => $data['status'],
            'remarks' => $data['remarks'] ?? '', // Use an empty string if remarks is null
        ]);
    
        // Now, update the corresponding booking record
        $booking = $billing->booking; // Get the associated booking
    
        // Update the booking data based on billing
        $booking->update([
            'check_out' => $data['check_out'],
            'status' => $data['status'],
            'remarks' => $data['remarks'] ?? '',
        ]);
    
        if (auth()->user()->role_id === 1) {
            return redirect()->route('openbook.checkout')->with('success', 'Billing and Booking updated successfully');
        } else {
            return redirect()->route('reguser.openbook.checkout')->with('success', 'Billing and Booking updated successfully');
        }
    }

}
