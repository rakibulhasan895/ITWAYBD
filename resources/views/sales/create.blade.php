<x-app-layout>
    <div class="container mt-4">
        <h3 class="mb-3">Add New Sale</h3>

        <form id="saleForm">
            @csrf
            <div class="mb-3">
                <label>Customer</label>
                <select name="user_id" class="form-control" required>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Add sale notes here..."></textarea>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <button type="submit" class="btn btn-success">Save Sale</button>
                <button type="button" class="btn btn-primary" id="addRow">Add Product</button>
            </div>

            <table class="table table-bordered" id="itemsTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                        <td colspan="2" id="grandTotal">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            let rowCount = 0;

            // Add new product row
            $('#addRow').click(function() {
                let row = `<tr>
            <td>
                <select name="items[${rowCount}][product_id]" class="form-control product-select" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${rowCount}][quantity]" class="form-control qty" value="1" min="1"></td>
            <td><input type="number" name="items[${rowCount}][price]" class="form-control price" readonly></td>
            <td><input type="number" name="items[${rowCount}][discount]" class="form-control discount" value="0"></td>
            <td class="subtotal">0</td>
            <td><button type="button" class="btn btn-sm btn-danger remove">X</button></td>
        </tr>`;
                $('#itemsTable tbody').append(row);
                rowCount++;
                updatePriceAndSubtotal();
            });

            // Update price, subtotal, grand total
            function updatePriceAndSubtotal() {
                let grandTotal = 0;
                $('#itemsTable tbody tr').each(function() {
                    let price = parseFloat($(this).find('.product-select option:selected').data('price')) ||
                        0;
                    $(this).find('.price').val(price.toFixed(2));
                    let qty = parseFloat($(this).find('.qty').val()) || 0;
                    let discount = parseFloat($(this).find('.discount').val()) || 0;
                    let subtotal = (qty * price) - discount;
                    $(this).find('.subtotal').text(subtotal.toFixed(2));
                    grandTotal += subtotal;
                });
                $('#grandTotal').text(grandTotal.toFixed(2));
            }

            // Recalculate when quantity, discount, or product changes
            $(document).on('change', '.product-select, .qty, .discount', updatePriceAndSubtotal);

            // Remove row
            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove();
                updatePriceAndSubtotal();
            });

            // AJAX form submit using FormData
            $('#saleForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('sales.store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        alert(resp.message);
                        window.location.href = '{{ route('sales.index') }}';
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let msg = '';
                            for (let field in errors) {
                                msg += errors[field].join(', ') + '\n';
                            }
                            alert(msg);
                        } else {
                            alert('Something went wrong!');
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
