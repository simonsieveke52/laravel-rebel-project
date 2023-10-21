@extends('voyager::master')

@section('page_title', 'Add Quote')

@section('page_header')
@stop

@section('content')
    <div id="quote_form">
        <quote-form submit-url="{{ route('voyager.quotes.update', ['id' => 0]) }}" redirect-url="{{ route('voyager.quotes.index')}}"></quote-form>
    </div>
@stop
