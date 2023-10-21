@extends('layouts.front.app')

@section('content')
	
	<div class="container px-2 mt-3 terms-container">
		<div class="row py-4 px-5">

			<div class="col-12 text-center mb-5">
				<h1 class="mb-4">Contact Us</h1>
				
			</div>

			@include('front.forms.contact-form')
			
		</div>
	</div>

@endsection