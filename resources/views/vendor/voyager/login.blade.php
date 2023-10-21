<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" @if (config('voyager.multilingual.rtl')) dir="rtl" @endif>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="admin login">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-white">
<div class="container">
    <div class="row my-5">
        <div class="faded-bg animated"></div>
        <div class="hidden-xs col-sm-7 col-md-8">
            <div class="clearfix">
                <div class="col-sm-12 col-md-10 col-md-offset-2">
                    <div class="logo-title-container">
                        <div class="copy animated fadeIn">
                        </div>
                    </div> <!-- .logo-title-container -->
                </div>
            </div>
        </div>

        <div class="col-6 offset-3">

            <div class="login-container p-5 rounded-xl shadow bg-red">

                <div class="text-center">
                    <div class="d-flex py-1 justify-content-center align-items-start">
                        <div class="d-flex flex-row align-items-center text-center text-sm-left">
                            <a class="px-0 d-block" href="{{ route('home') }}">
                                <img
                                    src="{{ asset('images/circular_logo.png') }}"
                                    class="img-fluid mr-0 mr-sm-3 position-relative top-logo max-w-100px"
                                    alt="{{ config('app.name') }}"
                                >
                            </a>

                            <a class="d-flex flex-column px-0 text-white text-left text-decoration-none text-uppercase" href="{{ route('home') }}">
                                <strong style="line-height: 1.6rem;" class="font-size-2rem font-size-sm-2-5rem font-size-md-2-8rem h3 mb-0 d-block font-blooming-bold text-nowrap">rebel smuggling</strong>
                                <span class="font-size-0-7rem font-size-sm-0-9rem font-size-md-1-2rem d-block font-weight-light font-open-sans text-nowrap">galactic emporium</span>
                            </a>

                        </div>
                    </div>
                </div>

                <form action="{{ route('voyager.login') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group form-group-default" id="emailGroup">
                        <label class="text-white">{{ __('voyager::generic.email') }}</label>
                        <div class="controls">
                            <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}" class="form-control rounded" required>
                         </div>
                    </div>

                    <div class="form-group form-group-default" id="passwordGroup">
                        <label class="text-white">{{ __('voyager::generic.password') }}</label>
                        <div class="controls">
                            <input type="password" name="password" placeholder="{{ __('voyager::generic.password') }}" class="form-control rounded" required>
                        </div>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="submit" class="btn login-button btn-light">
                            <span class="signin">{{ __('voyager::generic.login') }}</span>
                        </button>
                    </div>
              </form>

              <div style="clear:both"></div>

              @if(!$errors->isEmpty())
              <div class="alert alert-danger mt-3 mb-0">
                <ul class="list-unstyled mb-0">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
              </div>
              @endif

            </div> <!-- .login-container -->

        </div> <!-- .login-sidebar -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->

<script src="{{ mix('js/app.js') }}?ver={{ time() }}"></script>
<script>
    var btn = document.querySelector('button[type="submit"]');
    var form = document.forms[0];
    var email = document.querySelector('[name="email"]');
    var password = document.querySelector('[name="password"]');
    btn.addEventListener('click', function(ev){
        if (form.checkValidity()) {
            btn.querySelector('.signingin').className = 'signingin';
            btn.querySelector('.signin').className = 'signin hidden';
        } else {
            ev.preventDefault();
        }
    });
    email.focus();
    document.getElementById('emailGroup').classList.add("focused");

    // Focus events for email and password fields
    email.addEventListener('focusin', function(e){
        document.getElementById('emailGroup').classList.add("focused");
    });
    email.addEventListener('focusout', function(e){
       document.getElementById('emailGroup').classList.remove("focused");
    });

    password.addEventListener('focusin', function(e){
        document.getElementById('passwordGroup').classList.add("focused");
    });
    password.addEventListener('focusout', function(e){
       document.getElementById('passwordGroup').classList.remove("focused");
    });
</script>
</body>
</html>
