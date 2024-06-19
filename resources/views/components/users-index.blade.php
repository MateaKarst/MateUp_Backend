@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4 text-center">Users List</h1>

    <div class="flex justify-between items-center mb-4">
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            <a href="{{ route('users.create') }}" class="text-white">Add User</a>
        </button>
    </div>
    
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
                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left" style="width: 10%;">Actions</th>
                </tr>
            </thead>
            <tbody id="users-tbody" class="divide-y divide-gray-200">
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->surname }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>{{ $user->updated_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <a href="{{ route('users.confirmDelete', $user->id) }}" class="ml-2 text-red-600 hover:text-red-900">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function getAllUsers() {
        const fetchUsers =({
            url: '/web/users',
            method: 'GET',
            success: function(data) {
                console.log('Users from users-index.blade.php', data);
                var tbody = $('#users-tbody');
                tbody.empty(); // Clear existing rows

                var users = data.users; // Access the users property from the data object

                users.forEach(function(user) {
                    var createdAt = new Date(user.created_at).toISOString().split('T')[0];
                    var updatedAt = new Date(user.updated_at).toISOString().split('T')[0];

                    var row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.role}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.name}</td>
                            <td>${user.surname}</td>
                            <td>${user.phone}</td>
                            <td>${createdAt}</td>
                            <td>${updatedAt}</td>
                            <td>${user.user_token}</td>
                            <td>
                                <a href="/web/users/${user.id}/edit">Edit</a>
                                <a href="/web/users/${user.id}/delete" class="ml-2 text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch users:', error);
                console.error('Status:', status);
                console.error('XHR:', xhr);
                alert('Failed to fetch users.');
            }
        });
    }

    // Fetch users every 5 seconds
    setInterval(getAllUsers, 5000);
    console.log('Users from users-index.blade.php', getAllUsers);

    // Fetch users on page load
    $(document).ready(function() {
        getAllUsers();
    });
</script>
@endsection
