<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\EOD;
use Illuminate\Support\Facades\Cache;

class EODMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $now = Carbon::now();
        $eod = EOD::find(1); // Get the EOD record with id=1
    
        if (!$eod) {
            // If no EOD record is found, create a new one with default values
            $eod = new EOD([
                'start_time' => '07:00', // Default start time is 7:00 AM
                'end_time' => '19:00',   // Default end time is 7:00 PM
            ]);
            $eod->id = 1;
            $eod->save();
        }
    
        $startTime = Carbon::parse($eod->start_time);
        $endTime = Carbon::parse($eod->end_time);
    
        if ($now->lt($startTime) || $now->gte($endTime)) {
            $message = "Access denied outside of operating hours ({$startTime->format('H:i')} - {$endTime->format('H:i')})";
            session()->flash('toast_message', $message);
            
            // Determine the route based on the user's role_id
            $routeName = auth()->user()->role_id === 1 ? 'admin.index' : 'reguser.index';
            
            // Redirect to the determined route
            return redirect()->route($routeName);
        }
    
        return $next($request);
    }
    
}
