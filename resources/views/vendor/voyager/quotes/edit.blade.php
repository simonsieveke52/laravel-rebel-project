@extends('voyager::master')

@section('page_title', 'Edit Quote')

@section('page_header')
@stop

@section('content')
    <div id="quote_form">
        <quote-form :quote-id="{{$quote}}" submit-url="{{route('voyager.quotes.update',['id' => $quote])}}" redirect-url="{{route('voyager.quotes.index')}}"></quote-form>
    </div>
{{--<div class="side-body pt-0">
    <form action="{{ route('voyager.quotes.update', ['quote' => $quote]) }}" method="post">
        @csrf
        {{ method_field('PUT') }}>
        <h1 class="page-title"><i class="voyager-credit-cards"></i> Edit Quote</h1>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="customer_name">Full Name</label>
                <input class="form-control rounded-left" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') ?? $quote->name }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="customer_email">Email</label>
                <input type="text" class="form-control rounded-left" name="customer_email" id="customer_email" value="{{ old('customer_email') ?? $quote->email }}" required>
            </div>
            <div class="form-group col-md-6">
                <label for="customer_phone">Phone Number</label>
                <input type="text" class="form-control rounded-left" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') ?? $quote->phone }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="customer_phone">Street Address</label>
                <input type="text" class="form-control rounded-left" name="customer_street_address" id="customer_street_address" value="{{$quote->street_address}}"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="customer_phone">City</label>
                <input type="text" class="form-control rounded-left" name="customer_city" id="customer_city" value="{{ old('customer_city') ?? $quote->city }}">
            </div>
            <div class="form-group col-md-4">
                <label for="customer_phone">State</label>
                <input type="text" class="form-control rounded-left" name="customer_state" id="customer_state" value="{{ old('customer_state') ?? $quote->state }}">
            </div>
            <div class="form-group col-md-4">
                <label for="customer_phone">ZIP</label>
                <input type="text" class="form-control rounded-left" name="customer_zip" id="customer_zip" value="{{ old('customer_zip') ?? $quote->zip}}" required>
            </div>
        </div>
        @isset($quote->message)
        <div class="form-row">
            <div class="form-group col-md-12 w-100">
                <label for="customer_message" class="w-100">Customer Message</label>
                <textarea name="customer_message" class="form-control rounded-left w-100" disabled> {{ $quote->message }}</textarea>

            </div>
        </div>
        @endisset
        <div class="form-row ">
            <div class="form-group col-md-8 product_box">
                <label for="add-product-input" class="w-100">Add Product to Quote</label>
                <input type="text" name="" placeholder="Search by product name or sku" class="form-control quote-typeahead typeahead w-100 rounded-left" id="add-product-input" autocomplete="off">
                <button type="button" class="btn btn-highlight mr-1 float-right" id="add-product" >Add product</button> <span id="input-feedback"></span>
            </div>
            <div class="form-group col-md-4">

            </div>
        </div>
        --}}{{-- find and add products to the order --}}{{--
        <table class="table--order-products table">
            <thead>
            <th>Product Image</th>
            <th>Product Name / SKU</th>
            <th>Original Price</th>
            <th>Quantity</th>
            <th>Discount (%/$)</th>
            <th>Discounted Price</th>
            <th>Free Shipping</th>
            <th>Remove</th>
            </thead>
            <tbody>
            @foreach($quote->products as $index => $product)
                <tr class="table__row--order-product table">
                    <td><img src="{{ $product->main_image }}" style="height: 120px; width: auto;" class="img-fluid product-image"></td>
                    <td>
                        <ul class="list-unstyled">
                            <li><strong>{{$product->name}}</strong></li>
                            <li>{{$product->sku}}</li>
                        </ul>
                    </td>
                    <td class="original-price">{{ $product->price }}</td>
                    <td><input type="number" name="product_quantity[]" style="max-width: 100px" min="1" class="form-control" value="{{$product->quote_product($quote)->quantity}}"></td>
                    <td>
                        <div class="input-group row discount">
                            <input placeholder="discount" type="number" name="product_discount[]" class="form-control discount-value" min="0" max="100" value="{{$product->quote_product($quote)->discount_amount ? $product->quote_product($quote)->get_discount_percent() : 0}}">
                            <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                            <select class="form-control custom-select discount-type" name="discount_type[]">
                                <option value="percent">%</option>
                                <option value="dollar">$</option>
                            </select>
                        </div>
                    </td>
                    <td class="discounted-price">--</td>
                    <td>
                        <input type="checkbox" value="{{$product->quote_product($quote)->quantity}}" class="free-shipping">
                    </td>
                    <td><a href="#" class="btn btn-danger product-remove" aria-label="Remove Product from Bulk Order"><i class="voyager-trash"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <button class="btn btn-primary" @if($quote->status === 'Completed')
            disabled
            @endif type="submit">
            @if ($quote->status === 'Sent')
                Resend Order to Customer
            @else
                Send Order to Customer
            @endif
        </button>
    </form>
</div>--}}
@stop
