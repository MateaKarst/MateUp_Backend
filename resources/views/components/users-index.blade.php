@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4 text-center">Users List</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white divide-y divide-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 5%;">ID</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 10%;">Role</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 20%;">Email</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 10%;">Name</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 10%;">Surname</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 10%;">Phone</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 10%;">Created At</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 10%;">Updated At</th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 15%;">User Token</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                <tr>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 5%;">{{ $user->id }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 10%;">{{ $user->role }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 20%;">{{ $user->email }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 10%;">{{ $user->name }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 10%;">{{ $user->surname }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 10%;">{{ $user->phone }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 10%;">{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 10%;">{{ \Carbon\Carbon::parse($user->updated_at)->format('Y-m-d') }}</td>
                    <td class="px-4 py-4 text-left whitespace-nowrap" style="width: 15%;">{{ $user->user_token }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
