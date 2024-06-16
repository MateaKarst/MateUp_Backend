@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Confirm User Deletion</h2>
        
        <p class="text-center mb-6">Are you sure you want to delete this user?</p>
        
        <div class="flex justify-center">
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="mr-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </form>
            
            <a href="{{ route('users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Cancel
            </a>
        </div>
    </div>
</div>
@endsection
