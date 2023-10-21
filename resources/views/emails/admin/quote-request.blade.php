@extends('emails.customer.master')
@section('content')
<style>
    h1, h2, h3 {
        font-weight: normal;
    }
    #email-body {
        display: flex;
        flex-direction: column;
        font-family: sans-serif;
        background-color: #fff;
        color: #333333;
        max-width: 600px;
        margin-top: 3em;
        padding: 2em 0.5em;
    }

    #email-body li{
        line-height: 1.6;
    }

    #edit-quote-button {
        padding: 20px; 
        background-color: #ad121a; 
        color: white;
        margin-top: 20px;
    }
    #more-info h2 {
        font-size: 22px;
    }

    #more-info h3 {
        font-size: 19px;
    }
    #more-info h3 a{
        color: #ad121a;
    }

    #logo-container {
       padding: 3em 0;
    }
    #more-info {
        margin: auto;
        text-align: left;
    }

</style>

<!-- Visually Hidden Preheader Text : BEGIN -->
<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; background: #fff; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
    Dear {{ ucwords($name) }}
</div>
<!-- Visually Hidden Preheader Text : END -->

<!-- Create white space after the desired preview text so email clients don’t pull other distracting text into the inbox preview. Extend as necessary. -->
<!-- Preview Text Spacing Hack : BEGIN -->
<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; background: #fff; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
    &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>
<!-- Preview Text Spacing Hack : END -->

<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background: #f5f5f5;">
    <tr>
        <td style="padding: 10px; font-family: sans-serif; font-size: 12px; line-height: 15px; text-align: center; color: #888888;">
        </td>
    </tr>
</table>

<!-- Email Body : BEGIN -->
{{-- <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background: #fff;" class="email-container">

    <!-- Hero Image, Flush : BEGIN -->
    <tr>
        <td style="padding: 30px 0 0 0; background-color: #fff; text-align: center;">



        </td>
    </tr>
</table> --}}

    <!-- Hero Image, Flush : END -->

    <!-- 1 Column Text + Button : BEGIN -->
    <div id="email-body">
        <div id="#logo-container">
            <img src="{{ asset('images/logo.png') }}" width="402" height="51" alt="{{ Voyager::setting('site.title', config('app.name')) }}" border="0" style="width: 100%; max-width: 250px; height: auto; background: #fff; font-family: sans-serif; font-size: 15px; line-height: 15px; color: #555555; margin: auto;" class="g-img">
        </div>
        <h1 style="margin: 0px 0 0 0px; font-size: 22px; line-height: 30px; color: #333333; text-align: center;">
            Quote Request from {{ $name }}
        </h1>
    
        <div style="margin: 10px 0 0 0px; padding-bottom: 1em; font-size: 19px; line-height: 30px; color: #333333; font-weight: normal; text-align: center;">
                
            <p style="padding-bottom: 1em;">You can review the quote request by clicking the link below</p>

            <p style="text-align:center;"><a id="edit-quote-button" href="{{ $link }}">Edit Quote</a></p>
        </div>
        <div id="more-info">
            <h2>Products Requested: </h2>
            <ul style="list-style: none;">
            @foreach($products_requested as $index => $product)
                <li>{{ $product }}</li>
            @endforeach
            </ul>
            @if($quote_message)
            <h2>Message:</h2>
            <p>{{ $quote_message }}</p>
            @endif
            <h2>Contact:</h2>
            <ul style="list-style: none;">
                <li>Name: {{ $name }}</li>
                <li>Email: <a href="mailto:{{ $email}}">{{ $email }}</a></li>
                <li>Phone: {{ $phone }}</li>
                <li>Address: {{$address_1}}</li>
                @isset($address_2)
                    <li>{{ $address_2 }}</li>
                @endisset
                <li>{{ $city }}, {{ $state }} {{ $zip }}</li>
            </ul>
        </div>
    </div>
    <!-- 1 Column Text + Button : END -->


    <!-- Clear Spacer : BEGIN -->
    <tr>
        <td aria-hidden="true" height="10" style="font-size: 0px; line-height: 0px; background-color: #ffffff;">
            &nbsp;
        </td>
    </tr>
    <!-- Clear Spacer : END -->


    <!-- Clear Spacer : BEGIN -->
    <tr>
        <td aria-hidden="true" height="10" style="font-size: 0px; line-height: 0px; background-color: #ffffff;">
            &nbsp;
        </td>
    </tr>
    <!-- Clear Spacer : END -->

    <!-- 1 Column Text : BEGIN -->
    <tr>
        <td style="background-color: #ffffff;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding: 0; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555; text-align: justify; text-align-last: center;">
                        <p style="margin: 0 0 10px 0;">
                            <a style="color: #555555;" href="{{ config('app.url') }}">{{ config('app.url') }}</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- 1 Column Text : END -->


    <!-- Clear Spacer : BEGIN -->
    <tr>
        <td aria-hidden="true" height="40" style="font-size: 0px; line-height: 0px; background-color: #ffffff;">
            &nbsp;
        </td>
    </tr>
    <!-- Clear Spacer : END -->

</table>
<!-- Email Body : END -->

<!-- Email Footer : BEGIN -->

<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background: #f5f5f5;">
    <tr>
        <td style="padding: 10px; font-family: sans-serif; font-size: 12px; line-height: 15px; text-align: center; color: #888888;">
            <p class="text-center" style="color: #b3b3b3; font-size: 10px; text-align: center; margin: 0px; padding: 0px;">
                &nbsp;
            </p>
        </td>
    </tr>
</table>
<!-- Email Footer : END -->
@endsection
