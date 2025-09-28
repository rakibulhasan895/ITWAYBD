<x-app-layout>

<div class="container">
    <h3>Trashed Users</h3>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Back to Users</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Deleted At</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trashed as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>{{ $user->deleted_at->format('d M Y, h:i A') }}</td>
                <td>
                    <form method="POST" action="{{ route('users.restore', $user->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Restore</button>
                    </form>

                    <form method="POST" action="{{ route('users.forceDelete', $user->id) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure to delete permanently?')" class="btn btn-sm btn-danger">
                            Delete Permanently
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No trashed users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $trashed->links() }}
    </div>
</div>

</x-app-layout>
