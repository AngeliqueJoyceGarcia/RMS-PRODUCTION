<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Billing;
use Illuminate\Support\Facades\Response; 

class BackupController extends Controller
{

    public function read()
    {
        return view('back_up.read');
    }

    public function downloadBillings()
    {
        $billings = Billing::all();
        $billingsJson = $billings->toJson();
        $filename = 'billings.json';
    
        return Response::make($billingsJson, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    public function downloadBookings()
    {
        $bookings = Booking::all();
        $bookingsJson = $bookings->toJson();
        $filename = 'bookings.json';
    
        return Response::make($bookingsJson, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    

    public function storeBilling(Request $request)
    {
        try {
            //dd($request);
            // Validate the uploaded file
            $request->validate([
                'jsonFile' => 'required|file|mimes:json'
            ]);

            if ($request->file('jsonFile')->isValid()) {
                $uploadedFile = $request->file('jsonFile');
                $jsonContent = json_decode($uploadedFile->get(), true);

                if ($jsonContent) {
                    // Loop through each JSON object and create Billing records
                    foreach ($jsonContent as $data) {
                        $id = $data['id']; // Store the ID from the JSON

                        // Create the Booking record with the specific ID
                        $billing = new Billing;
                        $billing->setAttribute('id', $id); // Set the ID
                        $billing->fill($data);
                        $billing->save();
                    }

                    return redirect()->route('admin.backup.read')->with('success', 'File uploaded and records created successfully');
                } else {
                    return redirect()->route('admin.backup.read')->with('error', 'Failed to decode JSON content from the uploaded file.');
                }
            }

            return redirect()->route('admin.backup.read')->with('error', 'Please upload a valid JSON file.');
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error($e);
    
            // Redirect with an error message
            return redirect()->route('admin.backup.read')->with('error', $e);
        }
    }

    public function storeBooking(Request $request)
    {
        try {
            //dd($request);
            // Validate the uploaded file
            $request->validate([
                'jsonFile' => 'required|file|mimes:json'
            ]);

            if ($request->file('jsonFile')->isValid()) {
                $uploadedFile = $request->file('jsonFile');
                $jsonContent = json_decode($uploadedFile->get(), true);

                if ($jsonContent) {
                    // Loop through each JSON object and create Billing records
                    foreach ($jsonContent as $data) {
                        $id = $data['id']; // Store the ID from the JSON

                        // Create the Booking record with the specific ID
                        $booking = new Booking;
                        $booking->setAttribute('id', $id); // Set the ID
                        $booking->fill($data);
                        $booking->save();
                    }

                    return redirect()->route('admin.backup.read')->with('success', 'File uploaded and records created successfully');
                } else {
                    return redirect()->route('admin.backup.read')->with('error', 'Failed to decode JSON content from the uploaded file.');
                }
            }

            return redirect()->route('admin.backup.read')->with('error', 'Please upload a valid JSON file.');
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error($e);
    
            // Redirect with an error message
            return redirect()->route('admin.backup.read')->with('error', $e);
        }
    }
    
}
