@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('page_header')
    <div class="container-fluid">
        <br>
        <br>
        <br>
        <h1 class="text-center h2">This page has been <code>disabled</code> because <br> brands are controlled by outside data.</h1>
    </div>
@stop
