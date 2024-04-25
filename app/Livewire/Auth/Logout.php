<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    // Render the logout page
    public function render()
    {
        return view('livewire.auth.logout');
    }

    // Logout the user
    public function logout()
    {
        Auth::logout();
        session()->flash('message', 'Logged out successfully');
        return redirect('/');
    }
}
