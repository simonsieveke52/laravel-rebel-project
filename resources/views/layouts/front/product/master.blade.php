@extends('layouts.front.app')

@section('body_class', 'product')

@section('content')

	@include('front.shared.breadcrumb')

	<div class="row pb-5">
		<div class="col-12">
			@yield('page-content')
		</div>
	</div>

@endsection

