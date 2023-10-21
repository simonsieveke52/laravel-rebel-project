@extends('layouts.front.app')

@section('content')
  
  <div class="container mt-3">
    <div class="row py-4 pl-4">
        <div class="col-8">
            <h1 class="mb-4">Quick Order</h1>
        </div>
        @php
        $total = 0;   
       @endphp
        <div class="col-4 d-flex flex-column">
            <table class="table">
                <tr>
                    <th class="text-center no-border-top">
                        Order Summary
                    </th>
                </tr>
                @foreach ($productsQuick as $product)
                @php
                $total = $total +   $product->quantity * $product->price;
                @endphp
                <tr>
                    <th class="py-2">
                         Item Price X {{$pidQ[$product->id]}} 
                    </th>
                    <td class="py-2 text-right">
                        <h5>${{ number_format($pidQ[$product->id] *  $product->price, 2)}}</h5>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th class="py-2">
                        Subtotal:
                    </th>
                    <td class="py-2 text-right">
                        <h5 class="font-weight-bold">${{number_format($total, 2)}}</h5>
                    </td>
                </tr>
            </table>
            <button class="btn btn-highlight jq-btn-search-products" type="submit">
                <strong class="text-white">Proceed to Checkout </strong>
            </button>
        </div>



        
        

        <div class="col-12 text-center mb-5 d-flex justify-content-between">
            <div class="bg-white main-navbar-search ">
                <form method="POST" action="{{ route('quickorder.store',) }}">
                    @csrf
                    <div class="input-group m-0 collapse show" id="products-search-container main-navbar-seach">
                        <input 
                            value="{{ request()->keyword }}"
                            type="text" 
                            name="keyword" 
                            class="form-control border border-right-0 main-navbar-seach-field" 
                            placeholder="Enter SKU or Item Name" 
                            aria-label="SEARCH BY PRODUCT" 
                            aria-describedby="quick-search-button"
                        >
                        <input type="number" id="quantity" name="quantity" value="1" min="1">
                        <div class="input-group-append">
                            <button class="btn btn-highlight jq-btn-search-products" type="submit" id="quick-search-button">
                                <strong class="text-white">Add Item </strong>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div> 
        <div class="col-12 d-flex text-center mb-5 ">
            <div class="row">
            @if (!$productsQuick->isEmpty())
                    
            @foreach ($productsQuick as $product)
            @php
            $packageMove = $product->id . ",1"; 
           @endphp
            <form method="GET" action="/quickorder/{{ $packageMove}}">
                @csrf
                @method('GET')
                <button type="submit" class="btn">
                    <span class="fas fa-save text-highlight"></span>
                </button>
            </form> 

                @include('front.products.product', compact('product'))
                @php
                $packageDestroy = $product->id . ",1"; 
               @endphp
                <form method="POST" action="/quickorder/{{ $packageDestroy}}">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn">
                        <span class="far fa-times-circle text-highlight"></span>
                    </button>
                </form> 
                {{-- <quick-order
                    products='@json($products)'>
                </quick-order> --}}
                
            @endforeach

            <product-modal-component></product-modal-component>



        @else

            <div class="col-12 mt-4">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    You do not currently have any products in your list.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            
            
        </div>

        @endif
            </div>
        </div>
        @php
        $total = 0;   
       @endphp

    </div>

</div>






  <div class="container mt-3">
    <div class="row py-4 pl-4">
        <h1 class="mb-4">Save for Later</h1>
        <div class="col-12 text-center mb-5 ">
            <div class="bg-white main-navbar-search">
            </div>
        </div> 
        <div class="col-12 d-flex text-center mb-5 ">
            <div class="row">
            @if (!$productsLater->isEmpty())
                    
            @foreach ($productsLater as $product)
                @php
                    $packageMove = $product->id . ",2";
                @endphp
                <form method="GET" action="/quickorder/{{ $packageMove}}">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn">
                        <span class="fas fa-save text-highlight"></span>
                    </button>
                </form> 
                @include('front.products.product', compact('product'))
                @php
                $package = $product->id . ",2";
               @endphp
                <form method="POST" action="/quickorder/{{ $package }}">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn">
                        <span class="far fa-times-circle text-highlight"></span>
                    </button>
                </form> 
                {{-- <quick-order
                    products='@json($products)'>
                </quick-order> --}}
                
            @endforeach

            <product-modal-component></product-modal-component>

        @else

            <div class="col-12 mt-4">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    You do not currently have any products in your list.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            
            
        </div>

        @endif
            </div>
        </div>

    </div>
    

  </div>

@endsection