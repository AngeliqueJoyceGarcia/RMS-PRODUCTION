<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        //return redirect()->intended(RouteServiceProvider::HOME);// redirects to dashboard
     
        $redirectRoute = $this->getRedirectRoute($request->user());

        return Redirect::intended(route($redirectRoute));
    
    }

    protected function getRedirectRoute($user)
    {
        if ($user->role_id === 1) {
            return 'admin.index'; // Named route for admin dashboard
        } elseif ($user->role_id === 2) {
            return 'reguser.index'; // Named route for user dashboard
        }

        return null; // Default Laravel behavior
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
