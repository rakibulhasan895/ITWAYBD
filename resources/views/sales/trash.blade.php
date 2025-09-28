<x-app-layout>
    <div class="container mt-4">
        <h3 class="mb-3">Edit Sale #{{ $sale->id }}</h3>

        <form id="saleForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Customer</label>
                <select name="user_id" class="form-control" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @if($sale->user_id == $customer->id) selected @endif>{{ $customer->name }}</option>
                    @endforeach
                </select>
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
                <tbody>
                    @foreach($sale->items as $i => $item)
                        <tr>
                            <td>
                                <select name="items[{{ $i }}][product_id]" class="form-control product-select" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" @if($item->product_id == $product->id) selected @endif>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control qty" value="{{ $item->quantity }}"></td>
                            <td><input type="number" name="items[{{ $i }}][price]" class="form-control price" value="{{ $item->price }}" readonly></td>
                            <td><input type="number" name="items[{{ $i }}][discount]" class="form-control discount" value="{{ $item->discount }}"></td>
                            <td class="subtotal">{{ ($item->quantity * $item->price - $item->discount) }}</td>
                            <td><button type="button" class="btn btn-sm btn-danger remove">X</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" class="btn btn-sm btn-primary mb-3" id="addRow">Add Product</button>
            <button type="submit" class="btn btn-success">Update Sale</button>
        </form>
    </div>

<script>
$(document).ready(function(){
    let rowCount = {{ $sale->items->count() }};

    $('#addRow').click(function(){
        let row = `<tr>
            <td>
                <select name="items[${rowCount}][product_id]" class="form-control product-select" required>
                    @foreach($products as $product)
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

    function updatePriceAndSubtotal() {
        $('#itemsTable tbody tr').each(function(){
            let price = parseFloat($(this).find('.product-select option:selected').data('price'));
            $(this).find('.price').val(price);
            let qty = parseFloat($(this).find('.qty').val());
            let discount = parseFloat($(this).find('.discount').val());
            let subtotal = (qty * price) - discount;
            $(this).find('.subtotal').text(subtotal.toFixed(2));
        });
    }

    $(document).on('change', '.product-select, .qty, .discount', updatePriceAndSubtotal);

    $(document).on('click', '.remove', function(){
        $(this).closest('tr').remove();
        updatePriceAndSubtotal();
    });

    $('#saleForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("sales.update", $sale->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                alert(resp.message);
                window.location.href = '{{ route("sales.index") }}';
            }
        });
    });

    updatePriceAndSubtotal();
});
</script>
</x-app-layout>
