@extends('layouts.front.blank')

@section('content')

<form 
    method="POST" 
    action="{{ route('checkout.store') }}" 
    class="form mt-4 form--checkout needs-validation {{ $errors->count() > 0 ? 'was-validated' : '' }} jq-checkout-form mb-3" 
    novalidate=""
>
    @csrf
    <div class="container">

        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-center text-uppercase h2 font-weight-normal text-muted">Checkout</h1>
            </div>
        </div>

        <input type="hidden" name="payment_method" value="credit_card">

		<div class="row">

	        <div class="col-md-7 order-md-1">

	            <div class="mb-0">

	                <h4 class="d-flex justify-content-between align-items-center mb-3">
	                    <span class="text-muted">Billing address</span>
	                </h4>

	                <ul class="list-group">

	                    @foreach ($billingAddresses as $address)

	                        <address-item-component 
	                            selected-address-id="{{ session('billing_address', false) }}" 
	                            address-type="{{ $address->type }}" 
	                            :city="{{ json_encode($address->city) }}"
	                            :state="{{ json_encode($address->state) }}"
	                            :address="{{ json_encode($address) }}"
	                        >
	                        </address-item-component>

	                    @endforeach
	                    
	                </ul>

	            </div>

	            <div class="row">
	                <div class="col-12">
	                    <div class="my-2">&nbsp;</div>
	                </div>
	            </div>

	            
	            <div class="mb-0">
	                
	                <h4 class="d-flex justify-content-between align-items-center mb-3">
	                    <span class="text-muted">Shipping address</span>
	                </h4>
	                
	                @if (!$shippingAddresses->isEmpty())
	                    <ul class="list-group">

	                        @foreach ($shippingAddresses as $address)

	                            <address-item-component 
	                                selected-address-id="{{ session('billing_address', false) }}" 
	                                address-type="{{ $address->type }}" 
	                                :city="{{ json_encode($address->city) }}"
	                                :state="{{ json_encode($address->state) }}"
	                                :address="{{ json_encode($address) }}"
	                            >
	                            </address-item-component>

	                        @endforeach

	                    </ul>    
	                @else
	                    <div class="mb-4">
	                        <p class="text-highlight">You don't have any saved shipping addresses. By default, your billing address will be used as your shipping address. If you want a different shipping address, please create one.</p>
	                        <a href="{{ route('customer.address.create', Auth::user()->id) }}" class="btn btn-highlight">Add an address</a>
	                    </div>
	                @endif
	            </div>

	            <div class="row">
	                <div class="col-12">
	                    <div class="my-2">&nbsp;</div>
	                </div>
	            </div>

	            <div class="row d-none credit-card-container d-flex align-items-center">
	                {{-- payment --}}
	                <div class="col-7">
	                    <div class="payment border-secondary alert rounded bg-light mb-0">

	                        <div class="mb-0">
	                            <div class="row">
	                                <div class="col-md-12 mb-3">
	                                    <label class="text-muted" for="cc_number">Credit card number</label>
	                                    <input name="cc_number" type="text" class="form-control" id="cc_number" value="{{ old('cc_number') }}" required="">

	                                    @if ($errors->has('cc_number'))
	                                        <div class="invalid-feedback">
	                                            {{ $errors->first('cc_number') }}
	                                        </div>
	                                    @endif

	                                </div>
	                            </div>
	                            <div class="row mb-2">
	                                <div class="col-md-6">
	                                    <label class="text-muted" for="cc_name">Name on card</label>
	                                    <input name="cc_name" type="text" class="form-control" id="cc_name" value="{{ old('cc_name') }}" required="">
	                                    <small class="text-muted">Full name as displayed on card</small>

	                                    @if ($errors->has('cc_name'))
	                                        <div class="invalid-feedback">
	                                            {{ $errors->first('cc_name') }}
	                                        </div>
	                                    @endif
	                                    
	                                </div>
	                                <div class="col-8 col-md-3 mb-3">
		                                <label class="text-muted text-nowrap" for="cc_expiration">Expiration (Month/Year)</label>

		                                <div class="d-flex flex-row">
		                                	<div class="mr-2">
				                                <input name="cc_expiration_month" type="text" class="form-control" placeholder="Month" id="cc_expiration_month" value="{{ old('cc_expiration_month') }}" required="" maxlength="2">
				                            </div>
				                            <div>
				                                <input name="cc_expiration_year" type="text" class="form-control rounded" placeholder="Year" id="cc_expiration_year" value="{{ old('cc_expiration_year') }}" required="" maxlength="2">
				                            </div>
		                                </div>

		                                @if ($errors->has('cc_expiration_month'))
		                                    <div class="invalid-feedback d-block">
		                                        {{ $errors->first('cc_expiration_month') }}
		                                    </div>
		                                @endif

		                                @if ($errors->has('cc_expiration_year'))
		                                    <div class="invalid-feedback d-block">
		                                        {{ $errors->first('cc_expiration_year') }}
		                                    </div>
		                                @endif
		                            </div>
		                            <div class="col-4 col-md-3 mb-3">
		                                
		                                <label class="text-muted" for="cc_cvv">CVV</label>
		    
		                                <input name="cc_cvv" type="text" class="form-control" id="cc_cvv" value="{{ old('cc_cvv') }}" required="">
		    
		                                @if ($errors->has('cc_cvv'))
		                                    <div class="invalid-feedback d-block">
		                                        {{ $errors->first('cc_cvv') }}
		                                    </div>
		                                @endif
		                            </div>
	                            </div>
	                            <div class="row mb-2">
	                                <div class="col-12 text-right">
	                                    <button type="submit" class="btn btn-highlight py-2 px-5 jq-confirm-checkout">Confirm</button>
	                                </div>
	                            </div>
	                        </div>

	                    </div>
	                </div>
	                {{-- ./payment --}}

	                <div class="col-5">
	                	<div class="credit-card-container d-flex align-items-center justify-content-center h-100 w-100">
		                    <div class="card-wrapper d-flex align-items-center justify-content-center"></div>
		                </div>
	                </div>

	            </div>

	            <div class="row">

	                <div class="row">
	                    <div class="col-12">
	                        <div class="my-2">&nbsp;</div>
	                    </div>
	                </div>

	                <div class="col-12">
	                    <div class="d-flex flex-wrap justify-content-center">
	                        <label class="btn border-secondary rounded btn-light mr-4 min-w-100px p-3">
	                            <i class="fas fa-credit-card fa-4x"></i>
	                            <div class="form-check mt-2">
	                                <input 
	                                    class="form-check-input"
	                                    type="radio" 
	                                    name="payment_method"
	                                    id="credit-card"
	                                    value="credit_card"
	                                >
	                                <label class="form-check-label text-muted" for="credit-card">
	                                    Credit Card
	                                </label>
	                            </div>
	                        </label>
	                    </div>
	                </div>
	            </div>
	        </div>

	        <div class="col-md-4 offset-md-1 order-md-2 mb-4">

	            <div class="border-secondary rounded p-3">

	                <cart-overview-component></cart-overview-component>
	                
	                <hr class="my-5">
	                
	                <shipping-options-component 
	                    title="Your shipping method" 
	                    :selected="{{ json_encode(session('shipping', 1)) }}"
	                >
	                </shipping-options-component>
	                
	            </div>

	        </div>

	    </div>
	</div>
</form>

@endsection

@section('css')

	<style>
		.jp-card-container{
			margin: auto !important;
		}
	</style>

@endsection

@push('scripts')

	<script src="{{ mix('js/card.js') }}"></script>
	<script>
		
		$(function(){

			$('body').on('click', '.jq-confirm-checkout', function(event) {
				$.busyLoadFull('show')
			});

			$('#shipping-address').hide();

			$('[name="payment_method"]').on('change', function(event) {
				if ($(this).val() === 'credit_card') {
					$('.credit-card-container').removeClass('d-none').fadeOut(0);
					$('.credit-card-container').fadeIn(500)
				} else {
					$('.credit-card-container').fadeOut(300)
					$(this).parents('form').submit()
				}
			});

			$('[name="payment_method"]').val('credit_card').trigger('change')

			$('#credit-card').parents('.d-flex').remove()

			$('#shipping_address_different').on('change', function(event) {
				if($(this).is(':checked')) {
					$('#shipping-address').removeClass('collpase')
					$('#shipping-address').slideDown();
				} else {
					$('#shipping-address').slideUp();
				}
			});
		})

	</script>

@endpush