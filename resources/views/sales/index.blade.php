<x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Sales List</h3>
            <a href="{{ route('sales.create') }}" class="btn btn-success">Add Sale</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Products</th>
                    <th>Total</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->user->name }}</td>
                        <td>{{ $sale->sale_date }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach ($sale->items as $item)
                                    <li>{{ $item->product->name }} ({{ $item->quantity }} ×
                                        {{ number_format($item->price, 2) }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td><strong>{{ $sale->formatted_total }}</strong></td>
                        <td>
                            @foreach ($sale->notes as $note)
                                <span class="badge bg-secondary">{{ $note->content }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this sale?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No sales found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $sales->links() }}
    </div>
</x-app-layout>
