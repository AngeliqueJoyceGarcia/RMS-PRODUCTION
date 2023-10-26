<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankCommission;

class BankCommissionController extends Controller
{
    public function read ()
    {
        $banks = BankCommission::all();
        return view('bank_commission.read', compact('banks'));
    }

    public function create ()
    {
        $banks = BankCommission::all();
        return view('bank_commission.create', compact('banks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bank_name' => 'required',
            'bank_commission_percentage' => 'required',
        ]);

        BankCommission::create($data);

        return redirect(route('bankcom.read'))->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        // Find the bank commission by ID
        $bank = BankCommission::find($id);
        $banks = BankCommission::all();
    
        // Check if the bank exists
        if (!$bank) {
            return redirect()->route('bankcom.read')->with('error', 'Bank not found.');
        }
    
        return view('bank_commission.edit', compact('bank', 'banks'));
    }
    

    public function update(Request $request, BankCommission $bank)
    {
        $data = $request->validate([
            'bank_name' => 'required',
            'bank_commission_percentage' => 'required',
        ]);

        $bank->update($data);

        return redirect(route('bankcom.read'))->with('success', 'Updated Successfully');
    }

    public function destroy(BankCommission $bank) {

        $bank->delete();
        return redirect(route('bankcom.read'))->with('success', 'Successfully Deleted');

    }

    public function archives(){
        $banks = BankCommission::onlyTrashed()
            ->orderBy('id', 'desc')->get();

        return view('bank_commission.archives', ['banks' => $banks]);
    }

    public function restore(BankCommission $bank, Request $request)
    {
        $bank->restore();

        return redirect(route('bankcom.archives'))->with('success', 'Successfully Restored');
    }


}
