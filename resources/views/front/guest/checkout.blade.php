@extends('layouts.front.blank')

@section('content')


<div class="container">
    <div class="row mb-4 {{ !empty($errors) ? 'mt-4' : '' }}">
        <div class="col-12">
            <h1 class="text-center text-uppercase h2 font-weight-bold text-dark">Checkout</h1>
        </div>
    </div>
</div>

<checkout-component
    action="{{ route('guest.checkout.store') }}"
    :old="{{ empty(old()) ? '{}' : json_encode(old()) }}"
    :errors="{{ isset($errors) && count($errors) > 0 ? $errors : '{}' }}"
    :free-shipping="{{ $free_shipping ? 'true' : 'false' }}"
    :session="{{ json_encode($session) }}"
    :order="{{ session('order', [0])[0] }}"
    :order-origins="{{ $orderOrigins->toJson() }}"
>
</checkout-component>

@endsection

@section('css')
	<style>
		.jp-card-container{
			margin: auto !important;
		}
		.form-control {
			border-bottom-left-radius: 5px;
		    border-top-left-radius: 5px;
		}
		.jp-card-container {
			transform: scale(1.14) !important;
		}
		.form-control {
			border: 1px solid #cacaca;
		}
	</style>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.navbar-toggler').parent().remove();
        })
    </script>
@endpush
