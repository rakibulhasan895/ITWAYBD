<x-app-layout>

<div class="container">
    <h3>Users (Customers)</h3>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Add User</a>
    <a href="{{ route('users.trash') }}" class="btn btn-warning mb-3">🗑 Trash</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Created</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>{{ $user->address ?? '-' }}</td>
                <td>{{ $user->gender ?? '-' }}</td>
                <td>{{ $user->dob }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Move to Trash?')" class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
</x-app-layout>
