<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('components.users-index', compact('users'));
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['Users from UserController' => $users]);
    }

    public function confirmDelete($id)
    {
        $user = User::findOrFail($id);
        return view('components.user-delete', compact('user'));
    }

    public function create()
    {
        return view('components.user-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'name' => 'required',
            'surname' => 'required',
            'phone' => 'required',
            'role' => 'required|in:admin,member,trainer', // Ensure role is one of these values
        ]);

        // Create the user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);

        // Redirect or return a response as needed
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        print($user);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('components.user-edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed',
            'name' => 'required',
            'surname' => 'required',
            'phone' => 'required',
            'bio' => 'nullable',
            'profile_image_url' => 'nullable',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'twitter' => 'nullable',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'profile_image_url' => $request->profile_image_url,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
