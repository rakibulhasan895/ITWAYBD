<x-app-layout>

    <div class="container mt-4">
        <h3>Products</h3>

        <div id="successMessage" class="alert alert-success" style="display:none;"></div>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">Add Product</button>
        <a href="{{ route('products.trash') }}" class="btn btn-warning mb-3">🗑 Trash</a>

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
                            <button class="btn btn-sm btn-warning editProduct" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}" data-price="{{ $product->price }}"
                                data-description="{{ $product->description }}">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-danger deleteProduct"
                                data-id="{{ $product->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="productForm">
                    @csrf
                    <input type="hidden" name="id" id="product_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="product_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" name="price" id="product_price" step="0.01" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" id="product_description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveBtn" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('[data-bs-target="#productModal"]').on('click', function() {
                $('#modalTitle').text('Add Product');
                $('#saveBtn').text('Save Product');
                $('#productForm')[0].reset();
                $('#product_id').val('');
            });

            $(document).on('click', '.editProduct', function() {
                $('#modalTitle').text('Edit Product');
                $('#saveBtn').text('Update Product');
                $('#product_id').val($(this).data('id'));
                $('#product_name').val($(this).data('name'));
                $('#product_price').val($(this).data('price'));
                $('#product_description').val($(this).data('description'));

                let myModal = new bootstrap.Modal(document.getElementById('productModal'));
                myModal.show();
            });

            $('#productForm').submit(function(e) {
                e.preventDefault();
                let id = $('#product_id').val();
                let formData = $(this).serialize();

                let url = id ? "/products/" + id : "{{ route('products.store') }}";
                let method = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(res) {
                        $('#successMessage').text(res.message).show().fadeOut(3000);

                        if (id) {
                            $('#row-' + id).html(`
                                <td>${res.product.id}</td>
                                <td>${res.product.name}</td>
                                <td>${res.product.price}</td>
                                <td>${res.product.description}</td>
                                <td>${res.product.user ? res.product.user.name : '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editProduct"
                                        data-id="${res.product.id}"
                                        data-name="${res.product.name}"
                                        data-price="${res.product.price}"
                                        data-description="${res.product.description}">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteProduct" data-id="${res.product.id}">Delete</button>
                                </td>
                            `);
                        } else {
                            let row = `<tr id="row-${res.product.id}">
                                <td>${res.product.id}</td>
                                <td>${res.product.name}</td>
                                <td>${res.product.price}</td>
                                <td>${res.product.description}</td>
                                <td>${res.product.user ? res.product.user.name : '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editProduct"
                                        data-id="${res.product.id}"
                                        data-name="${res.product.name}"
                                        data-price="${res.product.price}"
                                        data-description="${res.product.description}">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteProduct" data-id="${res.product.id}">Delete</button>
                                </td>
                            </tr>`;
                            $('#productTable tbody').prepend(row);
                        }

                        $('#productForm')[0].reset();
                        let modal = bootstrap.Modal.getInstance(document.getElementById(
                            'productModal'));
                        modal.hide();
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

</x-app-layout>
