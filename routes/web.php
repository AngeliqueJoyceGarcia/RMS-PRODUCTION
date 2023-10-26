<?php

use App\Http\Controllers\ExtraChargeItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegularUserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\EODController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EntranceRateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MaxPaxController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SetDefaultController;
use App\Http\Controllers\BankCommissionController;
use App\Http\Controllers\RescheduleGuestController;
use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// regular user routes

//Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout'); // logout

// admin middleware
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');
    
    Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout'); // logout
    
    // users
    Route::get('/admin/users', [UserController::class, 'read'])->name('users.read');

    //for user list
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');//{user} for passing the user to route
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');// {user} for restful route 
    Route::delete('/admin/users/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/admin/users/archives', [UserController::class, 'archives'])->name('users.archives');
    Route::post('/admin/users/{user}/restore', [UserController::class, 'restore'])->withTrashed()->name('users.restore');

    //for Guests
    Route::get('/admin/guests/view/{id}', [GuestController::class, 'view'])->name('guests.view');
    Route::get('/admin/guests', [GuestController::class, 'read'])->name('guests.read');
    Route::get('/admin/guests/edit/{id}', [GuestController::class, 'edit'])->name('guests.edit');
    Route::put('/admin/guests/update/{id}', [GuestController::class, 'update'])->name('guests.update');
    

    // for booking
    Route::get('/admin/read', [BookingController::class, 'read'])->name('booking.read');
    Route::get('/admin/read-canceled', [BookingController::class, 'read_canceled'])->name('booking.read.cancel');
    Route::get('/admin/{booking}/view-cancel-book', [BookingController::class, 'view_cancelBook'])->name('booking.cancelBook');
    Route::put('/admin/booking/{booking}/update_cancel', [BookingController::class, 'update_cancelBook'])->name('booking.update_cancel');

    Route::middleware(['auth', 'superadmin', 'eod'])->group(function () {
        Route::get('/admin/booking/create', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/admin/booking/store', [BookingController::class, 'store'])->name('booking.store');
        Route::post('/admin/booking/next-rate', [BookingController::class, 'nextRate'])->name('booking.nextRate');
        Route::get('/admin/booking/{booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
        Route::put('/admin/booking/{booking}', [BookingController::class, 'update'])->name('booking.update');
    });
  

    // for billing 
    Route::get('/admin/billing/read', [BillingController::class, 'read'])->name('billing.read');
    
    // for extra charge item
    Route::get('/admin/extras/view', [ExtraChargeItemController::class, 'view'])->name('extras.view');
    Route::get('/admin/extras/read', [ExtraChargeItemController::class, 'read'])->name('extras.read');
    Route::get('/admin/extras/create', [ExtraChargeItemController::class, 'create'])->name('extras.create');
    Route::post('/admin/extras/store', [ExtraChargeItemController::class, 'store'])->name('extras.store');
    Route::get('/admin/extras/{extra}/edit', [ExtraChargeItemController::class, 'edit'])->name('extras.edit');
    Route::put('/admin/extras/{extra}', [ExtraChargeItemController::class, 'update'])->name('extras.update');
    Route::delete('/admin/extras/{extra}/destroy', [ExtraChargeItemController::class, 'destroy'])->name('extras.destroy');
    Route::get('/admin/extras/archives', [ExtraChargeItemController::class, 'archives'])->name('extras.archives');
    Route::post('/admin/extras/{extra}/restore', [ExtraChargeItemController::class, 'restore'])->withTrashed()->name('extras.restore');


    // for entrace rates 
    Route::get('/admin/view-rates', [EntranceRateController::class, 'view'])->name('rates.view');
    Route::get('/admin/rates', [EntranceRateController::class, 'read'])->name('rates.read');
    Route::get('/admin/rates/create', [EntranceRateController::class, 'create'])->name('rates.create');
    Route::post('/admin/rates/store', [EntranceRateController::class, 'store'])->name('rates.store');
    Route::get('/admin/rates/{rate}/edit', [EntranceRateController::class, 'edit'])->name('rates.edit');
    Route::put('/admin/rates/{rate}', [EntranceRateController::class, 'update'])->name('rates.update');
    Route::delete('/admin/rates/{rate}/destroy', [EntranceRateController::class, 'destroy'])->name('rates.destroy');
    Route::get('/admin/rates/archives', [EntranceRateController::class, 'archives'])->name('rates.archives');
    Route::post('/admin/rates/{rate}/restore', [EntranceRateController::class, 'restore'])->withTrashed()->name('rates.restore');

    // for reports
    Route::get('/admin/reports/read', [ReportController::class, 'read'])->name('reports.read');
    Route::post('/admin/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    //for refund
    Route::get('/admin/refund/read', [RefundController::class, 'read'])->name('refund.read');
    Route::get('/admin/{billing}/edit', [RefundController::class, 'edit'])->name('refund.edit');
    Route::put('/admin/{billing}', [RefundController::class, 'update'])->name('refund.update');

    // for PRE BOOK check in and out
    Route::get('/admin/arrival', [BookingController::class, 'checkin_read'])->name('prebook.checkin');
    Route::get('/admin/departure', [BookingController::class, 'checkout_read'])->name('prebook.checkout');
    Route::get('/admin/arrival/{booking}/edit', [BookingController::class, 'arival_edit'])->name('prebook.arival_edit');
    Route::get('/admin/departure/{billing}/edit', [BookingController::class, 'departure_edit'])->name('prebook.departure_edit');

    //for resched
    Route::get('/admin/resched', [RescheduleGuestController::class, 'checkin_read'])->name('resched.read');
    

    Route::put('/admin/departure/{billing}/update', [BookingController::class, 'departure_update'])->name('prebook.departure_update');
   

    // for OPEN BOOK 
    Route::get('/admin/openbook', [BookingController::class, 'openBookTracker'])->name('openbook.read');
    Route::get('/admin/openbook/cehckin', [BookingController::class, 'openBookCheckIn'])->name('openbook.checkin');
    Route::get('/admin/openbook/checkout', [BookingController::class, 'openBookCheckOut'])->name('openbook.checkout');
    Route::get('/admin/openbook/{billing}/edit', [BookingController::class, 'openBookEdit'])->name('openbook.edit');
    Route::put('/admin/openbook/{billing}/update', [BookingController::class, 'openBookUpdate'])->name('openbook.update');
    Route::get('/admin/openbook/checkin/{billing}/edit', [BookingController::class, 'openBookCheckinEdit'])->name('openbook.checkin_edit');
    Route::put('/admin/openbook/checkin/{billing}/update', [BookingController::class, 'openBookCheckinUpdate'])->name('openbook.checkin_update');

    Route::get('/admin/openbook/checkout/{billing}/edit', [BookingController::class, 'openBookCheckoutEdit'])->name('openbook.checkout_edit');
    Route::put('/admin/openbook/checkout/{billing}/update', [BookingController::class, 'openBookCheckoutUpdate'])->name('openbook.checkout_update');

    // bank commission
    Route::get('/admin/bankcom/read', [BankCommissionController::class, 'read'])->name('bankcom.read');
    Route::get('/admin/bankcom/create', [BankCommissionController::class, 'create'])->name('bankcom.create');
    Route::post('/admin/bankcom/store', [BankCommissionController::class, 'store'])->name('bankcom.store');
    Route::get('/admin/bankcom/{bank}/edit', [BankCommissionController::class, 'edit'])->name('bankcom.edit');
    Route::put('/admin/bankcom/{bank}', [BankCommissionController::class, 'update'])->name('bankcom.update');
    Route::delete('/admin/bankcom/{bank}/destroy', [BankCommissionController::class, 'destroy'])->name('bankcom.destroy');
    Route::get('/admin/bankcom/archives', [BankCommissionController::class, 'archives'])->name('bankcom.archives');
    Route::post('/admin/bankcom/{bank}/restore', [BankCommissionController::class, 'restore'])->withTrashed()->name('bankcom.restore');

    // for back up
    Route::get('/admin/backup/read', [BackupController::class, 'read'])->name('admin.backup.read');
    Route::get('/admin/backup/download/billings', [BackupController::class, 'downloadBillings'])->name('admin.backup.billings');
    Route::get('/admin/backup/download/bookings', [BackupController::class, 'downloadBookings'])->name('admin.backup.bookings');
    Route::post('/admin/backup/storeBilling', [BackupController::class, 'storeBilling'])->name('admin.backup.storeBilling');
    Route::post('/admin/backup/storeBooking', [BackupController::class, 'storeBooking'])->name('admin.backup.storeBooking');



}); // end of admin middleware

// regular user middleware
Route::middleware(['auth', 'regularuser'])->group(function () {
    Route::get('/reguser/index', [RegularUserController::class, 'index'])->name('reguser.index');
    Route::post('/reguser/logout', [AuthenticatedSessionController::class, 'destroy'])->name('reguser.logout'); // logout

    // for booking
    Route::get('/reguser/read', [BookingController::class, 'read'])->name('reguserbooking.read');
    Route::get('/reguser/read-canceled', [BookingController::class, 'read_canceled'])->name('reguser.read.cancel');
    
    Route::get('/reguser/{booking}/view-cancel-book', [BookingController::class, 'view_cancelBook'])->name('reguser.cancelBook');
    Route::put('/reguser/booking/{booking}/update_cancel', [BookingController::class, 'update_cancelBook'])->name('reguser.update_cancel');

    Route::middleware(['auth', 'regularuser', 'eod'])->group(function () {
        Route::get('/reguser/booking/create', [BookingController::class, 'create'])->name('reguserbooking.create')->middleware('eod');
        Route::post('/reguser/booking/store', [BookingController::class, 'store'])->name('reguserbooking.store')->middleware('eod');
        Route::post('/reguser/booking/next-rate', [BookingController::class, 'nextRate'])->name('reguserbooking.nextRate')->middleware('eod');
        Route::get('/reguser/booking/{booking}/edit', [BookingController::class, 'edit'])->name('reguserbooking.edit')->middleware('eod');
        Route::put('/reguser/booking/{booking}', [BookingController::class, 'update'])->name('reguserbooking.update')->middleware('eod');

    });
    


    // for billing 
    Route::get('/reguser/billing/read', [BillingController::class, 'read'])->name('reguserbilling.read');

    //for Guests
    Route::get('/reguser/guests/view/{id}', [GuestController::class, 'view'])->name('reguserguests.view');
    Route::get('/reguser/guests', [GuestController::class, 'read'])->name('reguserguests.read');
    Route::get('/reguser/guests/edit/{id}', [GuestController::class, 'edit'])->name('reguserguests.edit');
    Route::put('/reguser/guests/update/{id}', [GuestController::class, 'update'])->name('reguserguests.update');


    // for extra charge item
    Route::get('/reguser/extras/view', [ExtraChargeItemController::class, 'view'])->name('reguserextras.view');
    Route::get('/reguser/extras/read', [ExtraChargeItemController::class, 'read'])->name('reguserextras.read');
    Route::get('/reguser/extras/create', [ExtraChargeItemController::class, 'create'])->name('reguserextras.create');
    Route::post('/reguser/extras/store', [ExtraChargeItemController::class, 'store'])->name('reguserextras.store');
    Route::get('/reguser/extras/{extra}/edit', [ExtraChargeItemController::class, 'edit'])->name('reguserextras.edit');
    Route::put('/reguser/extras/{extra}', [ExtraChargeItemController::class, 'update'])->name('reguserextras.update');
    Route::delete('/reguser/extras/{extra}/destroy', [ExtraChargeItemController::class, 'destroy'])->name('reguserextras.destroy');
    Route::get('/reguser/extras/archives', [ExtraChargeItemController::class, 'archives'])->name('reguserextras.archives');
    Route::post('/reguser/extras/{extra}/restore', [ExtraChargeItemController::class, 'restore'])->withTrashed()->name('reguserextras.restore');

    //for calendar
    Route::get('/reguser/calendar', [CalendarController::class, 'showCalendar'])->name('regusercalendar.read');
    Route::get('/reguser/calendar/manage', [CalendarController::class, 'showLegends'])->name('regusercalendar.manage');
    Route::post('/reguser/save-calendar-legend', [CalendarController::class,'saveLegend'])->name('regusercalendar.store');

    // for entrace rates 
    Route::get('/reguser/view-rates', [EntranceRateController::class, 'view'])->name('reguserrates.view');
    Route::get('/reguser/rates', [EntranceRateController::class, 'read'])->name('reguserrates.read');
    Route::get('/reguser/rates/create', [EntranceRateController::class, 'create'])->name('reguserrates.create');
    Route::post('/reguser/rates/store', [EntranceRateController::class, 'store'])->name('reguserrates.store');
    Route::get('/reguser/rates/{rate}/edit', [EntranceRateController::class, 'edit'])->name('reguserrates.edit');
    Route::put('/reguser/rates/{rate}', [EntranceRateController::class, 'update'])->name('reguserrates.update');
    Route::delete('/reguser/rates/{rate}/destroy', [EntranceRateController::class, 'destroy'])->name('reguserrates.destroy');
    Route::get('/reguser/rates/archives', [EntranceRateController::class, 'archives'])->name('reguserrates.archives');
    Route::post('/reguser/rates/{rate}/restore', [EntranceRateController::class, 'restore'])->withTrashed()->name('reguserrates.restore');

    //for refund
    Route::get('/reguser/refund/read', [RefundController::class, 'read'])->name('reguserrefund.read');
    Route::get('/reguser/{billing}/edit', [RefundController::class, 'edit'])->name('reguserrefund.edit');
    Route::put('/reguser/{billing}', [RefundController::class, 'update'])->name('reguserrefund.update');

    // for reports
    Route::get('/reguser/reports/read', [ReportController::class, 'read'])->name('reguserreports.read');
    Route::post('/reguser/reports/generate', [ReportController::class, 'generate'])->name('reguserreports.generate');

    // for PRE BOOK check in and out
    Route::get('/reguser/arrival', [BookingController::class, 'checkin_read'])->name('reguser.checkin');
    Route::get('/reguser/checkout', [BookingController::class, 'checkout_read'])->name('reguser.checkout');
    Route::get('/reguser/arrival/{billing}/edit', [BookingController::class, 'arival_edit'])->name('reguser.arival_edit');
    Route::get('/reguser/departure/{billing}/edit', [BookingController::class, 'departure_edit'])->name('reguser.departure_edit');
    Route::put('/reguser/arrival/{booking}/update', [BookingController::class, 'arrival_update'])->name('reguser.arival_update');
    Route::put('/reguser/departure/{billing}/update', [BookingController::class, 'departure_update'])->name('reguser.departure_update');
    Route::get('/reguser/checkin', [BookingController::class, 'checkin_read'])->name('regbooking.checkin');

    //for resched
    Route::get('/reguser/resched', [RescheduleGuestController::class, 'checkin_read'])->name('reguser.resched');

    // for OPEN BOOK
    Route::get('/reguser/openbook', [BookingController::class, 'openBookTracker'])->name('reguser.openbook');
    Route::get('/reguser/checkin', [BookingController::class, 'openBookCheckIn'])->name('reguser.openbook.checkin');
    Route::get('/reguser/departure', [BookingController::class, 'openBookCheckOut'])->name('reguser.openbook.checkout');
    Route::get('/reguser/openbook/{billing}/edit', [BookingController::class, 'openBookEdit'])->name('reguser.openbook.edit');
    Route::put('/reguser/openbook/{billing}/update', [BookingController::class, 'openBookUpdate'])->name('reguser.openbook.update');
    Route::get('/reguser/openbook/checkin/{billing}/edit', [BookingController::class, 'openBookCheckinEdit'])->name('reguser.openbook.checkin_edit');
    Route::put('/reguser/openbook/checkin/{billing}/update', [BookingController::class, 'openBookCheckinUpdate'])->name('reguser.openbook.checkin_update');

    Route::get('/reguser/openbook/checkout/{billing}/edit', [BookingController::class, 'openBookCheckoutEdit'])->name('reguser.openbook.checkout_edit');
    Route::put('/reguser/openbook/checkout/{billing}/update', [BookingController::class, 'openBookCheckoutUpdate'])->name('reguser.openbook.checkout_update');

    
});// end of regular user middleware

Route::get('/calendar', [CalendarController::class, 'showCalendar'])->name('calendar.read');
Route::get('/calendar/manage', [CalendarController::class, 'showLegends'])->name('calendar.manage');
Route::post('/save-calendar-legend', [CalendarController::class,'saveLegend'])->name('calendar.store');


Route::get('/reservationfeesetting', function (){
    return view('reservationfeesetting.rfsetting');
})->name('reservationfeesetting');




//for storing the default rate
Route::post('/setDefault', [SetDefaultController::class,'store'])->name('setDefault.store');
Route::get('/setDefault', [SetDefaultController::class, 'view'])->name('setDefault.view');

//for custom rates
Route::get('/setCustomRate', [SetDefaultController::class, 'viewCustomRate'])->name('setDefault.viewCustom');
Route::post('/create-event', [SetDefaultController::class, 'createEvent'])->name('setDefault.createEvent');



//for setting maximum pax
Route::get('/setMaxPax', [MaxPaxController::class, 'create'])->name('maxpax.create');
Route::post('/setMaxPax/store', [MaxPaxController::class, 'store'])->name('maxpax.store');


//eod
Route::get('/systemEOD', [EODController::class, 'read'])->name('eod.read');
Route::post('/systemEOD/store', [EODController::class, 'store'])->name('eod.store');

Route::post('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');



Route::get('/calendar/list', [CalendarController::class, 'showCalendarList'])->name('calendar.list');
Route::post('/checkin/update', [BookingController::class, 'updateBookingDetails'])->name('booking.updatecheckin');
Route::post('/checkout/update', [BookingController::class, 'updateCheckout'])->name('booking.updatecheckout');

Route::post('/resched/update', [RescheduleGuestController::class, 'updateCheckIn'])->name('booking.updateCheckIn');
