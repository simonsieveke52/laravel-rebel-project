<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="preload" href="{{ mix('css/app.css') }}" as="style">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <title>RebelSmuggling.com - Food and Foodservice Supply Warehouse.</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="Description" content="Food and Foodservice Supply Warehouse.">
        <meta property="og:title" content="Food and Foodservice Supply Warehouse."/>
        <meta property="og:type" content=""/>
        <meta property="og:url" content="{{ request()->url() }}"/>
        <meta property="og:site_name" content="RebelSmuggling"/>
        <meta property="og:description" content="RebelSmuggling.com - Food and Foodservice Supply Warehouse - Buy in bulk for home, restaurants, and offices."/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="{{ asset('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
        <script src="{{ asset('https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
        <![endif]-->

        <script async defer>
            var resource = document.createElement('link'); 
            resource.setAttribute("rel", "stylesheet");
            resource.setAttribute('href', 'https://fonts.googleapis.com/css?family=Nunito:300,400,700&display=swap');
            document.getElementsByTagName('head')[0].appendChild(resource);
            window.categoryBrowserUrl = @json(route('category-ajax.filter'));
            @if (session()->has('coupon'))
                window.coupon = @json(session('coupon'));
            @endif
        </script>

        <link rel="icon" href="{{ asset('favicons/32.png') }}" sizes="32x32">
        <link rel="icon" href="{{ asset('favicons/76.png') }}" sizes="76x76">
        <link rel="icon" href="{{ asset('favicons/96.png') }}" sizes="96x96">
        <link rel="icon" href="{{ asset('favicons/180.png') }}" sizes="180x180">
        <link rel="icon" href="{{ asset('favicons/192.png') }}" sizes="192x192">
        <link rel="icon" href="{{ asset('favicons/196.png') }}" sizes="196x196">
        <link rel="shortcut icon" sizes="196x196" href="{{ asset('favicons/196.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicons/128.png') }}" sizes="128x128">
        <link rel="apple-touch-icon" href="{{ asset('favicons/180.png') }}" sizes="180x180">
        <link rel="apple-touch-icon" href="{{ asset('favicons/192.png') }}" sizes="192x192">
        <meta name="msapplication-TileColor" content="#ad121a">
        <meta name="msapplication-TileImage" content="{{ asset('favicons/180.png') }}">
        <meta name="msapplication-config" content="{{ asset('favicons/browserconfig.xml') }}" />
        <meta name="_token" content="{{ csrf_token() }}" />
        <!-- Google Tag Manager -->
        <script async defer>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WD7FGP3');</script>
        <!-- End Google Tag Manager -->
        @yield('javascript')
        @yield('og')
        @stack('seo')
        @stack('stylesheet')
        @yield('css')
        <style>
            .spinner-pump{width: 40px; height: 40px; position: relative;}
            .double-bounce1,.double-bounce2{width: 100%; height: 100%; border-radius: 50%; background-color: #333; opacity: 0.6; position: absolute; top: 0; left: 0; -webkit-animation: sk-bounce 2s infinite ease-in-out; animation: sk-bounce 2s infinite ease-in-out;}
        </style>
        <meta name="facebook-domain-verification" content="dte0hb241b9w6zpq3e1hg8hjjhefq7" />
    </head>
    <body class="@yield('body_class')">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WD7FGP3"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <div id="app" class="bg-white d-block position-relative">
            @include('front.shared.header')
            <div class="container" style="min-height: 500px;">
                <div class="row">
                    <div class="col-lg-3 col-md-4 d-md-block col-0">
                        @include('front.shared.navigation-primary')
                    </div>
                    <div class="col-md-8 col-lg-9 col-12">
                        <div class="row">
                            <div class="col-12 text-center mt-3">
                                @include('layouts.errors-and-messages')
                            </div>
                        </div>
                        <div class="jq-page-content">
                            @section('content')
                            
                            @show
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer bg-red">
                <div class="container">
                    <div class="row">
                        @include('front.shared.footer')
                    </div>
                </div>
                <div class="bg-med-grey w-100 bottom-footer">
                    @include('front.shared.footer-sliver')
                </div>
            </div>
        </div>
        @yield('js')
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('scripts')
        <script defer async>
            $(function () {
                $.ajax({
                    url: '{{ route('facebook.store') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        url: @json(request()->fullUrl())
                    }
                });
            });
        </script>
        <script src="https://apis.google.com/js/platform.js?onload=renderBadge" async defer></script>
        <script async defer>
          window.renderBadge = function() {
            var ratingBadgeContainer = document.createElement("div");
            document.body.appendChild(ratingBadgeContainer);
            window.gapi.load('ratingbadge', function() {
                window.gapi.ratingbadge.render(ratingBadgeContainer, {"merchant_id": 123262447});
            });
          }
        </script>
    </body>
</html>