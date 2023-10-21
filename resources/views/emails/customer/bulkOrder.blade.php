@extends('emails.customer.master')

@section('content')
<style>
    body {
        font-family: sans-serif;
    }
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #494949;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody + tbody {
        border-top: 2px solid #dee2e6;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.3rem;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }
    .float-right {
        float: right !important;
    }
    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 5px;
    }

    .card > hr {
        margin-right: 0;
        margin-left: 0;
    }

    .card > .list-group {
        border-top: inherit;
        border-bottom: inherit;
    }

    .card > .list-group:first-child {
        border-top-width: 0;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    .card > .list-group:last-child {
        border-bottom-width: 0;
        border-bottom-right-radius: 4px;
        border-bottom-left-radius: 4px;
    }

    .card > .card-header + .list-group,
    .card > .list-group + .card-footer {
        border-top: 0;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
    }

    .card-link + .card-link {
        margin-left: 1.25rem;
    }

    .card-header {
        padding: 0.75rem;
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header:first-child {
        border-radius: 4px 4px 0 0;
    }

    .m-0 {
        margin: 0 !important;
    }
</style>

    <!-- Visually Hidden Preheader Text : BEGIN -->
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; background: #fff; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        Dear {{ ucwords($order->name) }}
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
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background: #fff;" class="email-container">

        <!-- Hero Image, Flush : BEGIN -->
        <tr>
            <td style="padding: 30px 0 0 0; background-color: #fff; text-align: center;">

                <a href="{{config('app.url')}}"><img src="{{ asset('images/logo.png') }}" width="402" height="51" alt="{{ Voyager::setting('site.title', config('app.name')) }}" border="0" style="width: 100%; max-width: 250px; height: auto; background: #fff; font-family: sans-serif; font-size: 15px; line-height: 15px; color: #555555; margin: auto;" class="g-img"></a>

            </td>
        </tr>

        <!-- Hero Image, Flush : END -->

        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td valign="middle" width="100%" style="background-color: #ffffff;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr width="100%">
                        <td  style="padding: 20px 20px 10px 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;" colspan="2">

                            <h1 style="margin: 0px 0 0 0px; font-size: 22px; line-height: 30px; color: #333333; font-weight: normal; text-align: center;">
                                {{ ucwords($order->name) }}
                            </h1>

                            <h2 style="margin: 5px 0 0 0px; font-size: 19px; line-height: 30px; color: #333333; font-weight: normal; text-align: center;">

                                <p style="padding: 20px 0;">Congratulations! The following discounts were applied to your quote. Hit the button below to checkout.</p>
                                <p style="text-align:center;"><a style="padding: 20px; background-color: green; color: white;" href="{{ $link }}">Checkout with Discount</a></p>
                                <p style="padding: 10px 0;"><small><i>This offer expires in 7 days</i></small></p>

                            </h2>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <!-- 1 Column Text + Button : END -->


        <!-- Thumbnail Left, Text Right : BEGIN -->
        <tr>
            <td dir="ltr" valign="middle" width="100%" style="padding: 0px 10px; background-color: #ffffff;" class="mobile-display">
                <div class="card">
                    <div class="card-header">
                        <h4>Products in Quote</h4>
                    </div>
                    <div class="card-body m-0">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="table table-sm">
                        @foreach( $order->products as $product )

                            <tr style="padding: 8px 0px !important;" class="product-card">
                                <td width="100%" class="stack-column-center product-cover" style="padding: 0;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td dir="ltr" style="padding: 5px; text-align: left;">
                                                <b>{{ $product->name }}</b>
                                            </td>
                                            <td style="text-align: center;">({{ $product->pivot->quantity }})</td>
                                            <td>${{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</td>
                                            <td>
                                                @if ($product->pivot->discount && $product->pivot->discount != 0 && $product->pivot->price > 0)
                                                    <span style="color:#46be8a">-${{ number_format($product->pivot->discount, 2) }} ({{round((((($product->pivot->price * $product->pivot->quantity) - $product->pivot->discount) / ($product->pivot->price * $product->pivot->quantity)) - 1) * -100)}}% off)</span>
                                                @endif
                                                @if ($product->pivot->free_shipping === 1)
                                                    <br>
                                                    <strong style="color: #46be8a">+Free Shipping</strong>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        @endforeach
                    </table>
                    </div>
                    <div class="card-footer">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="table table-sm table-borderless">
                            <tr>
                                <td>Subtotal:</td>
                                <td style="text-align:right">${{number_format($order->subtotal, 2)}}</td>
                            </tr>
                            <tr>
                                <td>Discount:</td>
                                <td style="text-align:right; color: #46be8a">${{number_format($order->appliedDiscount->discount_amount, 2)}}</td>
                            </tr>
                            <tr>
                                <td>Subtotal with discount:</td>
                                <td style="text-align:right">${{number_format($order->subtotal -$order->appliedDiscount->discount_amount, 2)}}</td>
                            </tr>
                            <tr>
                                @if ($order->shippingCostEstimate > 0)
                                    <td>Shipping Estimate:</td>
                                    <td style="text-align:right;">
                                        ${{ number_format($order->shippingCostEstimate, 2) }}
                                    </td>
                                @else
                                    <td>Shipping:</td>
                                    <td style="text-align:right;">FREE</td>
                                @endif
                            </tr>
                        </table>
                    </div>
                </div>

            </td>
        </tr>
        <!-- Thumbnail Left, Text Right : END -->


        <!-- Clear Spacer : BEGIN -->
        <tr>
            <td aria-hidden="true" height="10" style="font-size: 0px; line-height: 0px; background-color: #ffffff;">
                &nbsp;
            </td>
        </tr>
        <!-- Clear Spacer : END -->

        <!-- 1 Column Text : BEGIN -->
        <tr>
            <td style="background-color: #ffffff;" >
                <table role="presentation" width="100%">
                    <tr>
                        <td valign="middle" width="100%" style="background-color: #ffffff;">
                            <h2 style="margin: 5px; font-size: 19px; line-height: 30px; color: #333333; font-weight: normal; text-align: center;">
                                <span style="text-align:center; margin-top:10px; margin-bottom:10px"><a style="padding: 20px; background-color: green; color: white;" href="{{ $link }}">Checkout with Discount</a></span>
                            </h2>
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
@endsection
