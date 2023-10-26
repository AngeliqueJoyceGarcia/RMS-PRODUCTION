<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Booking;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function fetchData()
    {
    //Bookings Analysis

        // Calculate daily, weekly, monthly, and yearly booking count with 'Confirmed' status
        $dailyBook = Booking::whereDate('created_at', today())->where('status', 'Confirmed')->count();
        $weeklyBook = Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'Confirmed')->count();
        $monthlyBook = Booking::whereMonth('created_at', now()->month)->where('status', 'Confirmed')->count();
        $yearlyBook = Booking::whereYear('created_at', now()->year)->where('status', 'Confirmed')->count();

        // Calculate daily, weekly, monthly, and yearly 'Cancelled' booking count
        $dailyCancelledBook = Booking::whereDate('created_at', today())->where('status', 'Cancelled')->count();
        $weeklyCancelledBook = Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'Cancelled')->count();
        $monthlyCancelledBook = Booking::whereMonth('created_at', now()->month)->where('status', 'Cancelled')->count();
        $yearlyCancelledBook = Booking::whereYear('created_at', now()->year)->where('status', 'Cancelled')->count();

    //Guest/Total Companion Anlaysis

        // Calculate daily, weekly, monthly, and yearly guest counts with 'Confirmed' Status
        $dailyGuestCount = Booking::whereDate('check_in', today())->where('status', 'Confirmed')->sum('total_companion');
        $weeklyGuestCount = Booking::whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'Confirmed')->sum('total_companion');
        $monthlyGuestCount = Booking::whereMonth('check_in', now()->month)->where('status', 'Confirmed')->sum('total_companion');
        $yearlyGuestCount = Booking::whereYear('check_in', now()->year)->where('status', 'Confirmed')->sum('total_companion');

        // Calculate daily, weekly, monthly, and yearly guest counts for "Cancelled" bookings
        $dailyCancelledGuestCount = Booking::whereDate('check_in', today())->where('status', 'Cancelled')->sum('total_companion');
        $weeklyCancelledGuestCount = Booking::whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'Cancelled')->sum('total_companion');
        $monthlyCancelledGuestCount = Booking::whereMonth('check_in', now()->month)->where('status', 'Cancelled')->sum('total_companion');
        $yearlyCancelledGuestCount = Booking::whereYear('check_in', now()->year)->where('status', 'Cancelled')->sum('total_companion');

    //Guest Arrived Analysis

        // Calculate daily, weekly, monthly, and yearly attendance counts
        $dailyAttendanceCount = Booking::whereDate('check_in', today())->where('status', 'Confirmed')->sum('arrived_companion');
        $weeklyAttendanceCount = Booking::whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'Confirmed')->sum('arrived_companion');
        $monthlyAttendanceCount = Booking::whereMonth('check_in', now()->month)->where('status', 'Confirmed')->sum('arrived_companion');
        $yearlyAttendanceCount = Booking::whereYear('check_in', now()->year)->where('status', 'Confirmed')->sum('arrived_companion');

    //Billing Analysis
        
        // Calculate daily, weekly, monthly, and yearly total amount for "Confirmed" bookings
        $dailyConfirmedTotalAmount = Booking::whereDate('created_at', today())->where('status', 'Confirmed')->sum('total_amount');
        $weeklyConfirmedTotalAmount = Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'Confirmed')->sum('total_amount');
        $monthlyConfirmedTotalAmount = Booking::whereMonth('created_at', now()->month)->where('status', 'Confirmed')->sum('total_amount');
        $yearlyConfirmedTotalAmount = Booking::whereYear('created_at', now()->year)->where('status', 'Confirmed')->sum('total_amount');

        // Calculate daily, weekly, monthly, and yearly total amount for "Cancelled" bookings
        $dailyCancelledTotalAmount = Booking::whereDate('created_at', today())->where('status', 'Cancelled')->sum('total_amount');
        $weeklyCancelledTotalAmount = Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'Cancelled')->sum('total_amount');
        $monthlyCancelledTotalAmount = Booking::whereMonth('created_at', now()->month)->where('status', 'Cancelled')->sum('total_amount');
        $yearlyCancelledTotalAmount = Booking::whereYear('created_at', now()->year)->where('status', 'Cancelled')->sum('total_amount');

        $data = [
            'dailyBook' => $dailyBook,
            'weeklyBook' => $weeklyBook,
            'monthlyBook' => $monthlyBook,
            'yearlyBook' => $yearlyBook,
            'dailyCancelledBook' => $dailyCancelledBook,
            'weeklyCancelledBook' => $weeklyCancelledBook,
            'monthlyCancelledBook' => $monthlyCancelledBook,
            'yearlyCancelledBook' => $yearlyCancelledBook,
            'dailyGuestCount' => $dailyGuestCount,
            'weeklyGuestCount' => $weeklyGuestCount,
            'monthlyGuestCount' => $monthlyGuestCount,
            'yearlyGuestCount' => $yearlyGuestCount,
            'dailyCancelledGuestCount' => $dailyCancelledGuestCount,
            'weeklyCancelledGuestCount' => $weeklyCancelledGuestCount,
            'monthlyCancelledGuestCount' => $monthlyCancelledGuestCount,
            'yearlyCancelledGuestCount' => $yearlyCancelledGuestCount,
            'dailyAttendanceCount' => $dailyAttendanceCount,
            'weeklyAttendanceCount' => $weeklyAttendanceCount,
            'monthlyAttendanceCount' => $monthlyAttendanceCount,
            'yearlyAttendanceCount' => $yearlyAttendanceCount,
            'dailyConfirmedTotalAmount' => $dailyConfirmedTotalAmount,
            'weeklyConfirmedTotalAmount' => $weeklyConfirmedTotalAmount,
            'monthlyConfirmedTotalAmount' => $monthlyConfirmedTotalAmount,
            'yearlyConfirmedTotalAmount' => $yearlyConfirmedTotalAmount,
            'dailyCancelledTotalAmount' => $dailyCancelledTotalAmount,
            'weeklyCancelledTotalAmount' => $weeklyCancelledTotalAmount,
            'monthlyCancelledTotalAmount' => $monthlyCancelledTotalAmount,
            'yearlyCancelledTotalAmount' => $yearlyCancelledTotalAmount,
        ];
    
        return $data;
    }

    public function read()
    {
        // Retrieve data using fetchData method
        //$data = $this->fetchData();
    
        return view('report.read');
    }


    public function generate(Request $request)
    {
        //dd($request);
        $reportType = $request->input('report_type');
        $specificDay = $request->input('datepicker');
        $specificMonth = $request->input('month');
        $specificYear = $request->input('year');

        // If no specific day is provided, use the current day
        if ($reportType === 'Daily' && !$specificDay) {
            $specificDay = now()->day;
        }

        // Create a new PhpWord instance
        $phpWord = new PhpWord();
    
        // Create a new section
        $section = $phpWord->addSection();
    
        // Add header with the selected report type
        $header = $section->addHeader();
        $imagePath = $imagePath = public_path('images\image\header.png'); // Assuming the image is in the public directory
        $header->addImage(
            $imagePath,
            [
                'width' => 440, // Set the width of the image
                'height' => 80, // Set the height of the image
                array('alignment' => 'center'), // Set the alignment
            ]
        );
        $header->addText(
            $reportType . ' Report',
            array('bold' => true),
            array('alignment' => 'center'),
        );
        $header->addText(
            'Date Generated: ' . now()->format('F j, Y'),
            array('bold' => true),
            array('alignment' => 'center'),
        );

        // Fetch data from the 'bookings' table 
        $bookingsData = Booking::all();

        // Fetch data based on the selected report type
        switch ($reportType) {
            case 'Daily':
                if ($reportType === 'Daily' && $specificDay) {
                    $section->addText('Date Covered: ' . $specificDay); // Add specific day if it's provided
                }
                $section->addTextBreak();

                $section->addText(
                    'This report provides a detailed breakdown of bookings on a daily basis.' . "\n" .
                    'It includes information such as the date, guest name, total companions,' . "\n" .
                    'arrived companions, and total amounts for each day.'
                );

                $section->addTextBreak();

                // Create a table
                $table = $section->addTable();

                $table->addRow();
                $table->addCell(2000)->addText('Date');
                $table->addCell(3000)->addText('Guest Name');
                $table->addCell(2000)->addText('Total Comp');
                $table->addCell(2000)->addText('Arrived Comp');
                $table->addCell(2000)->addText('Total Earnings');
                $table->addCell(2000)->addText('Status');

                // Convert the selected date to a Carbon instance
                $specificDate = Carbon::parse($specificDay);

                // Now you can use $specificDate to filter the data
                $bookingsData = Booking::whereDate('created_at', $specificDate->toDateString())
                    ->whereIn('status', ['check-in', 'check-out', 'Canceled'])
                    ->get();
        


                // Populate the table with data from the database
                foreach ($bookingsData as $booking) {
                    $table->addRow();
                    // Format the created_at date using Carbon
                    $formattedDate = Carbon::parse($booking->created_at)->format('F j, Y');
                    $table->addCell(2000)->addText($formattedDate);
                    $table->addCell(2500)->addText($booking->name);
                    $table->addCell(2000)->addText($booking->total_companion);
                    $table->addCell(2000)->addText($booking->arrived_companion);
                    $table->addCell(2000)->addText($booking->total_amount);
                    $table->addCell(2000)->addText($booking->status);
            }
            break;
    
            case 'Weekly':
                // Parse the selected date into a Carbon instance to extract year and month
                $year = $specificYear;
                $month = $specificMonth;
                $monthName = Carbon::create()->month($specificMonth)->monthName;

                $section->addText('Month Covered: ' . $monthName);
                
                $section->addTextBreak();
                $section->addText(
                    'This report offers insights into booking trends over a specified time range.' . "\n" .
                    'It aggregates data into weekly intervals, displaying confirmed walk-ins, cancelled walk-ins, and total amounts for each week.'
                );
                $section->addTextBreak();
            
                // Create a table
                $table = $section->addTable();
                $table->addRow();
                $table->addCell(2000)->addText('Week');
                $table->addCell(2500)->addText('Confirmed Walkins');
                $table->addCell(2500)->addText('Cancelled Walkins');
                $table->addCell(2000)->addText('Total Earnings');
            
                // Calculate the start and end dates for the selected month
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();

                $currentWeek = 1;

                // Initialize variables to store weekly totals
                $weeklyConfirmedWalkins = 0;
                $weeklyCancelledWalkins = 0;
                $weeklyTotalEarnings = 0;

                while ($startDate->isBefore($endDate)) {
                    $weekStartDate = $startDate->copy();
                    $weekEndDate = $startDate->addWeek()->subDay();

                    // Fetch data for bookings within this week
                    $bookingsData = Booking::whereBetween('created_at', [$weekStartDate, $weekEndDate])->get();

                    // Calculate counts, total amount, and total amount paid for this week
                    $confirmedWalkins = $bookingsData->whereIn('status', ['check-in', 'check-out'])->count();
                    $cancelledWalkins = $bookingsData->where('status', 'Canceled')->count();
                    $totalAmount = $bookingsData->whereIn('status', ['check-in', 'check-out'])->sum('total_amount');

                    // Accumulate weekly totals
                    $weeklyConfirmedWalkins += $confirmedWalkins;
                    $weeklyCancelledWalkins += $cancelledWalkins;
                    $weeklyTotalEarnings += $totalAmount;


                    // Add this week's data to the table
                    $table->addRow();
                    $table->addCell(2000)->addText('Week ' . $currentWeek);
                    $table->addCell(2500)->addText($confirmedWalkins);
                    $table->addCell(2500)->addText($cancelledWalkins);
                    $table->addCell(2000)->addText($totalAmount);

                    $currentWeek++;
                }

            break;            
    
            case 'Monthly':
                $section->addTextBreak();
                $section->addText(
                    'For a broader perspective, the monthly report presents data on a monthly basis.' . "\n" .
                    'It highlights the number of confirmed and cancelled walk-ins, for each month.'
                );
                $section->addTextBreak();

                // Create a table
                $table = $section->addTable();
                $table->addRow();
                $table->addCell(2200)->addText('Month');
                $table->addCell(2100)->addText('Confirmed Walkins');
                $table->addCell(2100)->addText('Cancelled Walkins');
                $table->addCell(1500)->addText('Total Earnings');

                $year = $specificYear;

                // Initialize variables to store monthly totals
                $monthlyConfirmedWalkins = 0;
                $monthlyCancelledWalkins = 0;
                $monthlyTotalEarnings = 0;

                for ($month = 1; $month <= 12; $month++) {
                    // Calculate the start and end dates for the current month
                    $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                    $endDate = Carbon::create($year, $month, 1)->endOfMonth();

                    // Fetch data for bookings within the current month
                    $bookingsData = Booking::whereBetween('created_at', [$startDate, $endDate])->get();

                    // Calculate counts, total amount, and total amount paid for the current month
                    $confirmedWalkins = $bookingsData->whereIn('status', ['check-in', 'check-out'])->count();
                    $cancelledWalkins = $bookingsData->where('status', 'Canceled')->count();
                    $totalAmount = $bookingsData->whereIn('status', ['check-in', 'check-out'])->sum('total_amount');

                    // Store monthly totals
                    $monthlyConfirmedWalkins += $confirmedWalkins;
                    $monthlyCancelledWalkins += $cancelledWalkins;
                    $monthlyTotalEarnings += $totalAmount;                

                    // Add this month's data to the table
                    $table->addRow();
                    $table->addCell(2200)->addText(Carbon::create()->month($month)->monthName);
                    $table->addCell(2100)->addText($confirmedWalkins);
                    $table->addCell(2100)->addText($cancelledWalkins);
                    $table->addCell(1500)->addText($totalAmount);
                }
            break;
                
    
            case 'Yearly':
                $section->addTextBreak();
                $section->addText(
                    'The yearly report summarizes booking data for entire years.' . "\n" .
                    'It provides a yearly view of confirmed walk-ins, cancelled walk-ins, and total amounts'
                );
                $section->addTextBreak();

                // Create a table
                $table = $section->addTable();
                $table->addRow();
                $table->addCell(2500)->addText('Year');
                $table->addCell(2500)->addText('Confirmed Walkins');
                $table->addCell(2500)->addText('Cancelled Walkins');
                $table->addCell(2500)->addText('Total Earnings');

                $year = $specificYear;

                // Fetch data for bookings within the specific year
                $bookingsData = Booking::whereYear('created_at', $year)->get();

                // Calculate counts, total amount, and total amount paid for the year
                $confirmedWalkins = $bookingsData->whereIn('status', ['check-in', 'check-out'])->count();
                $cancelledWalkins = $bookingsData->where('status', 'Canceled')->count();
                $totalEarnings = $bookingsData->whereIn('status', ['check-in', 'check-out'])->sum('total_amount');

                // Add the yearly data to the table
                $table->addRow();
                $table->addCell(2500)->addText($year);
                $table->addCell(2500)->addText($confirmedWalkins);
                $table->addCell(2500)->addText($cancelledWalkins);
                $table->addCell(2500)->addText($totalEarnings);
            break;
    
            default:
                // Display a toast message indicating maintenance
                return redirect()->back()->with('toast', ['type' => 'warning', 'message' => 'System is under maintenance']);
        }

        $section->addTextBreak();

        // add signature label
        $section->addText('Signature', array('bold' => true));
        
        $user = Auth::user();
        // Concatenate the first name and last name
        $fullName = $user->firstname . ' ' . $user->lastname;

        // Add the full name to the report
        $section->addText($fullName);

        // Add a footer to the section
        $footer = $section->addFooter();

        // Add content to the footer
        $footer->addText(
            'Highland Bali Villas Resort And Spa',
            array('bold' => true),
            array('alignment' => 'right'),
        );
        $footer->addText(
            'R49W+R9X, Intang, Pantabangan, Nueva Ecija',
            array('bold' => false),
            array('alignment' => 'right'),
        );

        // Save the document to the public storage directory
        $filename = '/app/public/reports/report.docx';
        $fullPath = storage_path($filename);
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($fullPath);
    
        // Generate a URL to the public storage file
        $publicUrl = Storage::url($filename);
    
        // Download the generated DOCX file
        return response()->download($fullPath)->deleteFileAfterSend(true);
    }
    

    
}
