<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Billing;

class RefundController extends Controller
{
    public function read ()
    {
        $billings = Billing::all();
        $paginatedBillings = Billing::paginate(11);
        
        return view('refund.read', ['billings' => $billings, 'paginatedBillings' => $paginatedBillings]);
    }


    public function edit(Billing $billing){
        $billing = Billing::findOrFail($billing->id);
        return view('refund.edit', ['bill' => $billing]);
    }


    public function update(Request $request, Billing $billing, Booking $booking){

        try {
            $data = $request->validate([
                'returnedTowelQuantity' => 'required|integer',
                'returnedWristbandQuantity' => 'required|integer',
                'claimableRefund'=> 'required|numeric',
                'totalAmount'=> 'required|numeric',
                'totalAmountPaid' => 'required|numeric',
            ]);
    
            // Update the Billing model with the validated data
            $billing->update([
                'returnedTowelQty' => $data['returnedTowelQuantity'],
                'returnedWristBandQty' => $data['returnedWristbandQuantity'],
                'claimableRefund' => $data['claimableRefund'],
                'total_amount' => $data['totalAmount'],
                'total_amount_paid' => $data['totalAmountPaid'],
            ]);

            // Fetch the associated Booking model using the booking_id from Billing
            $associatedBooking = $billing->booking;

            // Update the associated Booking model
            if ($associatedBooking) {
                $associatedBooking->update([
                    'total_amount' => $data['totalAmount'],
                    'total_amount_paid' => $data['totalAmountPaid'],
                ]);
            }

            // Check if the reservation type is 'Walk-in' before updating the status
            if ($associatedBooking->reservation_type === 'Walk-in') {
                $associatedBooking->update([
                    'status' => 'check-out', // Update the status to 'check-out' in booking
                ]);

                // Also, update the status in Billing if it's a 'Walk-in'
                $billing->update([
                    'status' => 'check-out', // Update the status to 'check-out' in billing
                ]);
            }
    
            return redirect(auth()->user()->role_id === 1 ? route('refund.read') : route('reguserrefund.read'))->with('success', 'Updated Successfully');
    
        } catch (\Exception $e) {
            // Handle exceptions here and store a user-friendly error message in the session
            $errorMessage = 'An error occurred while saving the booking. Please try again later: ' . $e->getMessage();
    return redirect()->back()->with('error', $errorMessage);
        }
    }


    
}
