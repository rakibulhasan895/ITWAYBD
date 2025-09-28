<x-app-layout>

    <div class="container">
        <h3>Products</h3>

        <div id="successMessage" class="alert alert-success" style="display:none;"></div>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">Add Product</button>

        <table class="table table-bordered" id="productTable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Added By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr id="row-{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->user->name ?? '-' }}</td>
                        <td>
                            <button class="btn btn-sm btn-danger deleteProduct"
                                data-id="{{ $product->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Add Product Modal --}}
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="productForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {

        // Create Product via AJAX
        $('#productForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('products.store') }}",
                method: "POST",
                data: formData,
                success: function(res) {
                    $('#successMessage').text(res.message).show().fadeOut(3000);
                    let row = `<tr id="row-${res.product.id}">
                    <td>${res.product.id}</td>
                    <td>${res.product.name}</td>
                    <td>${res.product.price}</td>
                    <td>${res.product.description}</td>
                    <td>${res.product.user ? res.product.user.name : '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger deleteProduct" data-id="${res.product.id}">Delete</button>
                    </td>
                </tr>`;
                    $('#productTable tbody').prepend(row);

                    // Reset form and hide modal
                    $('#productForm')[0].reset();
                    $('#productModal').modal('hide');
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + "\n";
                    });
                    alert(errorMsg);
                }
            });
        });

        // Delete Product via AJAX
        $(document).on('click', '.deleteProduct', function() {
            if (!confirm('Are you sure to delete?')) return;
            let id = $(this).data('id');

            $.ajax({
                url: '/products/' + id,
                method: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    alert(res.message);
                    $('#row-' + id).remove();
                }
            });
        });

    });
</script>
