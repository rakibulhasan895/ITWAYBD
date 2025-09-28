@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create Product</h3>
    <form id="productForm">
        @csrf
        <div class="form-group mb-2">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter product name">
        </div>
        <div class="form-group mb-2">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#productForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('products.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                alert(res.message);
                location.href = "{{ route('products.index') }}";
            },
            error: function(err) {
                alert('Error saving product!');
            }
        });
    });
});
</script>
@endpush
