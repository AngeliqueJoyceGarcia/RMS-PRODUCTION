<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtraChargeItem;
use App\Models\Booking;
use App\Models\GuestDetails;


class ExtraChargeItemController extends Controller
{
    public function view()
    {
        $extras = ExtraChargeItem::all();
        $paginatedExtras = ExtraChargeItem::paginate(11);

        return view('extra_item.view', ['extras' => $extras, 'paginatedExtras' => $paginatedExtras]);
    }

    public function read()
    {
        $extras = ExtraChargeItem::all();
        $paginatedExtras = ExtraChargeItem::paginate(11);
        return view('extra_item.read', ['extras' => $extras, 'paginatedExtras' => $paginatedExtras]);
    }

    public function create(Request $request)
    {
        $extras = ExtraChargeItem::all();
        $paginatedExtras = ExtraChargeItem::paginate(11);
        return view('extra_item.create', ['extras' => $extras, 'paginatedExtras' => $paginatedExtras]);
    }

    
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'item_name' => 'required|string',
            'price' => 'required|numeric',
        ]);
    
        // Create a new ExtraChargeItem instance and store it in the database
        ExtraChargeItem::create([
            'item_name' => $validatedData['item_name'],
            'price' => $validatedData['price'],
        ]);
    
        // Redirect to the extras.read route
        return redirect()->route('extras.read')->with('success', 'Item added successfully');
    }
    
    public function edit(ExtraChargeItem $extra)
    {
        $extras = ExtraChargeItem::all();
        $paginatedExtras = ExtraChargeItem::paginate(11);
        return view('extra_item.edit', ['extra' => $extra, 'extras' => $extras, 'paginatedExtras' => $paginatedExtras]);
    }

    public function update(Request $request, ExtraChargeItem $extra)
    {
        $validateddata = $request->validate([
            'item_name' => 'required',
            'price' => 'required|integer',
        ]);

       
        $extra->update($validateddata);

        return redirect(route('extras.read'))->with('success', 'Successfully Save');
    
    }

    public function destroy(ExtraChargeItem $extra) {

        $extra->delete();
        return redirect(route('extras.read'))->with('success', 'Successfully Deleted');

    }

    public function archives(){
        $extras = ExtraChargeItem::onlyTrashed()
            ->orderBy('id', 'desc')->get();
        $paginatedExtras = ExtraChargeItem::paginate(11);

            return view('extra_item.archives', ['extras' => $extras,'paginatedExtras' => $paginatedExtras]);
    }

    public function restore(ExtraChargeItem $extra, Request $request)
    {
        $extra->restore();

        return redirect(route('extras.archives'))->with('success', 'Successfully Restored');
    }
}
