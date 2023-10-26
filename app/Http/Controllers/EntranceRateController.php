<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EntranceRate;

class EntranceRateController extends Controller
{
    public function view()
    {
        $rates = EntranceRate::all();
        $paginatedEntrance = EntranceRate::paginate(11);
        return view('entrance_rate.view', ['rates' => $rates, 'paginatedEntrance' => $paginatedEntrance]);
    }

    // for management
    public function read()
    {
        $rates = EntranceRate::all();
        $paginatedEntrance = EntranceRate::paginate(11);
        return view('entrance_rate.read', ['rates' => $rates,  'paginatedEntrance' => $paginatedEntrance]);
    }

    public function create()
    {
        $rates = EntranceRate::all();
        $paginatedEntrance = EntranceRate::paginate(11);
        return view('entrance_rate.create', ['rates' => $rates, 'paginatedEntrance' => $paginatedEntrance]);
    }

    public function store(Request $request)
    {
        $validateddata = $request->validate([
            'rate_name' => 'required',
            'vat'  => 'required|string',
            'servicecharge'  => 'required|string',
            'baseChildPrice'  => 'required|string',
            'baseAdultPrice'  => 'required|string',
            'baseSeniorPrice'  => 'required|string',
            'vatsc_childprice'  => 'required|string',
            'vatsc_adultprice'  => 'required|string',
            'vatsc_seniorprice'  => 'required|string',
        ]);

       
        EntranceRate::create($validateddata); // Saving the data

        return redirect(route('rates.read'))->with('success', 'Successfully Save');
    
    }

    public function edit(EntranceRate $rate)
    {
        $rates = EntranceRate::all();
        $paginatedEntrance = EntranceRate::paginate(11);
        return view('entrance_rate.edit', ['rate' => $rate,'rates' => $rates, 'paginatedEntrance' => $paginatedEntrance]);
    }

    public function update(Request $request, EntranceRate $rate)
    {
        $validateddata = $request->validate([
            'rate_name' => 'required',
            'vat'  => 'required|string',
            'servicecharge'  => 'required|string',
            'baseChildPrice'  => 'required|string',
            'baseAdultPrice'  => 'required|string',
            'baseSeniorPrice'  => 'required|string',
            'vatsc_childprice'  => 'required|string',
            'vatsc_adultprice'  => 'required|string',
            'vatsc_seniorprice'  => 'required|string',
        ]);

       
        $rate->update($validateddata);

        return redirect(route('rates.read'))->with('success', 'Successfully Save');
    
    }

    public function destroy(EntranceRate $rate) {

        $rate->delete();
        return redirect(route('rates.read'))->with('success', 'Successfully Deleted');

    }

    public function archives(){
        $rates = EntranceRate::onlyTrashed()
            ->orderBy('id', 'desc')->get();
        $paginatedEntrance = EntranceRate::paginate(11);

            return view('entrance_rate.archives', ['rates' => $rates,'paginatedEntrance' => $paginatedEntrance]);
    }

    public function restore(EntranceRate $rate, Request $request)
    {
        $rate->restore();

        return redirect(route('rates.archives'))->with('success', 'Successfully Restored');
    }

}
