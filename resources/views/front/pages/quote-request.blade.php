@extends('layouts.front.app')

@section('content')
  <quote-request submit-url="{{route('voyager.quotes.store')}}" product-request="{{$product}}"></quote-request>
@endsection
