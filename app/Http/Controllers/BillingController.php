<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\ExtraChargeItem;

class BillingController extends Controller
{
    public function read()
    {
        // Retrieve only the billings with a status that is not 'Canceled'
        $billings = Billing::where('status', '!=', 'Canceled')->get();
    
        // Retrieve extra charge items and paginate the billings
        $items = ExtraChargeItem::all();
        $paginatedBillings = Billing::paginate(11);
    
        return view('billing.read', [
            'billings' => $billings,
            'paginatedBillings' => $paginatedBillings,
            'items' => $items
        ]);
    }
    
}
