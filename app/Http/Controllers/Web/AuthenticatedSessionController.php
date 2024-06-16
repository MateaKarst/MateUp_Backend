<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    // Show the login form
    public function create()
    {
        return view('auth.login');
    }

    // Handle login form submission
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // gett the user
        $user = User::where('email', $request->email)->first();
        $userRole = $user->role;

        if (Auth::attempt($request->only('email', 'password'))) {
            if ($userRole == 'admin') {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            } else {
                return back()->withErrors(['email' => 'You are not authorized to access this page.']);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
