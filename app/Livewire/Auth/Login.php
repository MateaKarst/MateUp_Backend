<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    // Declare the variables
    public $users, $email, $password = '';

    // Render the login page
    public function render()
    {
        // return the login page with the logout component
        return view('livewire.auth.login', [
            
            'logout' => new Logout(),
        ]);
    }

    // Reset the input fields
    private function resetInputFields()
    {
        $this->email = '';
        $this->password = '';
    }

    // Login the user
    public function login()
    {
        // Validate the input
        $validatedData = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Authorize the user
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            // Check if the user is an admin
            if (Auth::user()->role == 'admin') {
                // If admin, redirect to the homepage
                session()->flash('message', 'Logged In Successfully');
                return redirect()->route('home');
            } else {
                // If not admin, give an error
                session()->flash('error', 'You are not an admin');
                $this->resetInputFields();
            };
        } else {
            // If wrong credentials, give an error
            session()->flash('error', 'Invalid Credentials');
        }
    }
}
