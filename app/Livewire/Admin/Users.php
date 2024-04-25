<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class Users extends Component
{
    // Declare the variables
    public $search = '';

    // Render the user management page
    public function render()
    {
        // Get the users
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->get();

        return view('livewire.admin.users', ['users' => $users]);
    }
}
