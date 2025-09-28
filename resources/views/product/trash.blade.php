<x-app-layout>

    <div class="container mt-4">
        <h3>Trashed Products</h3>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">← Back to Products</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr id="trash-{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->deleted_at }}</td>
                        <td>
                            <button class="btn btn-sm btn-success restoreProduct" data-id="{{ $product->id }}">Restore</button>
                            <button class="btn btn-sm btn-danger forceDeleteProduct" data-id="{{ $product->id }}">Delete Permanently</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).on('click', '.restoreProduct', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '/products/' + id + '/restore',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    alert(res.message);
                    $('#trash-' + id).remove();
                }
            });
        });

        $(document).on('click', '.forceDeleteProduct', function() {
            if (!confirm('Are you sure to permanently delete?')) return;
            let id = $(this).data('id');
            $.ajax({
                url: '/products/' + id + '/force-delete',
                method: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    alert(res.message);
                    $('#trash-' + id).remove();
                }
            });
        });
    </script>

</x-app-layout>
