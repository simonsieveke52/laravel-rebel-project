@extends('voyager::master')

@section('page_title', 'Create a Bulk Order for a customer to complete')

@section('page_header')
@stop

@section('content')
<div class="side-body padding-top">

    <form action="{{ route('quoterequest.adminStore') }}" method="post">
        @csrf
        <h1 class="page-title"><i class="voyager-credit-cards"></i> Create Bulk Order</h1>
    
        <p>
            <label for="customer_name">Customer Name</label>
            <input class="form-control" type="text" name="customer_name" id="customer_name" required>
        </p>
        <p>
            <label for="customer_email">Customer Email</label>
            <input type="text" class="form-control" name="customer_email" id="customer_email" required>
        </p>
        <p>
            <label for="customer_phone">Customer Phone Number</label>
            <input type="text" class="form-control" name="customer_phone" id="customer_phone" required>
        </p>
        {{-- find and add products to the order --}}
        <p><a href="#" class="product-add btn btn-success">Add a product</a></p>
    
        <table class="table--order-products table">
            <thead>
                <th>Product Sku</th>
                <th>Quantity</th>
                <th>% Discount</th>
                <th>Remove</th>
            </thead>
            <tbody>
                <tr class="table__row--order-product table">
                    <td><input placeholder="eg. rs22931" type="text" name="product_sku[]" class="form-control"></td>
                    <td><input type="number" name="product_quantity[]" min="1" value="1" class="form-control"></td>
                    <td><input placeholder="% discount" type="number" name="product_discount[]" class="form-control" min="0" max="100" value="0"></td>
                    <td><a href="#" class="btn btn-danger product-remove" aria-label="Remove Product from Bulk Order"><i class="voyager-trash"></i></a></td>
                </tr>
            </tbody>
        </table>
    
        <button class="btn btn-primary" type="submit">Send Order to Customer</button>
    </form>
</div>

@stop

@section('css')

@stop

@section('javascript')
    {{-- Set up the Vue Component --}}
    <script>
        var $orderProdRow = $('.table__row--order-product').clone();
        $('body').on('click','a.product-add', function(e){
            var $newRow = $orderProdRow.clone();
            var $newRowSKUInput = $newRow.children('td').first().children("input").first();
            $newRowSKUInput.val('');
            $('.table--order-products tbody').append($newRow);
        });
        
        $('body').on('click','.table--order-products a.product-remove', function(e){
            $(e.target).closest('.table__row--order-product').remove();
        });
    </script>

@stop
