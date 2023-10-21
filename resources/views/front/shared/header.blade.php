<div class="h-150px h-sm-182px">
    <div class="fixed-sm-top">
        {{-- 
        <div class="bg-light-grey py-1 text-center text-uppercase text-dark z-index-2">
            Average shipping time is 7 to 10 days
        </div> 
        --}}
        <div class="top-header bg-red">
            <header class="container">
                <div class="d-flex flex-row justify-content-between align-items-start">
                    <div class="d-flex py-1 justify-content-center align-items-start">
                        <div class="text-center text-sm-left">
                            <a class="px-0 d-block" href="{{ route('home') }}">
                                <img
                                    src="{{ asset('images/round_logo_half2.png') }}"
                                    class="img-fluid mr-0 mr-sm-3 position-relative top-logo"
                                    alt="{{ config('app.name') }}"
                                >
                            </a>
                        </div>
                        <div class="d-flex flex-column text-center text-sm-left top-text-logo">
                            <a class="d-flex flex-column px-0 text-white text-left text-decoration-none text-uppercase mt-3 mt-sm-4" href="{{ route('home') }}">
                                <strong class="font-size-2rem font-size-sm-2-5rem font-size-md-2-8rem h3 mb-0 d-block font-blooming-bold text-nowrap">rebel smuggling</strong>
                                <span class="font-size-0-7rem font-size-sm-0-9rem font-size-md-1-2rem d-block font-weight-light font-open-sans text-nowrap">galactic emporium</span>
                            </a>
                            <div class="text-secondary-6 sub-logo d-none d-md-block h5 robto plunder position-relative mt-auto">
                                FOODSERVICE PLUNDER FOR OUR REBEL FRIENDS.
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-md-flex justify-content-start align-items-end pt-1">
                        <div class="text-md-right text-center mt-2 py-1 font-size-0-9rem">
                            <div class="h4 font-size-1-35rem text-white robto mb-0">
                                Have questions? <a class="text-white" href="{{ route('faq') }}">See FAQ</a>
                            </div>
                            <div class="d-flex flex-column py-1">
                                <a class="text-white h5 font-weight-bold robto mb-0" href="tel:{{ config('default-variables.phone') }}">{{ formatPhone(config('default-variables.phone')) }}</a>
                                <a class= "px-0 text-med-grey robto mt-1">M-F 9:30am-5:30pm Eastern</a>
                            </div>
                        </div>
                    </div>
                    <nav class="navbar d-md-none d-block navbar-expand-lg navbar-dark text-light bg-red p-0">
                        <button aria-label="navbar toggler" class="navbar-toggler btn border-white mt-3 mt-sm-4 mt-md-0" type="button" @click="toggleElement('#main-navbar')">
                            <i class="fas fa-bars text-white"></i>
                        </button>
                    </nav>
                </div>
            </header>
        </div>
        <div class="bg-med-grey w-100"></div>
        <div class="bg-light-grey py-2">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="d-flex flex-row align-items-center justify-content-between justify-content-sm-end">
                            <div class="bg-light-grey main-navbar-search mr-3">
                                <form method="GET" action="{{ route('product.search') }}">
                                    <div class="input-group m-0 w-100 flex-nowrap" id="products-search-container" style="z-index: 99;">
                                        <input
                                            value="{{ request()->keyword }}"
                                            type="text"
                                            name="keyword"
                                            class="form-control border border-red border-right-0 main-navbar-seach-field"
                                            placeholder="Search"
                                            aria-label="SEARCH BY PRODUCT"
                                            aria-describedby="search-button"
                                        >
                                        <div class="input-group-append">
                                            <button aria-label="search" type="submit" class="bg-white border border-red border-left-0 px-3 text-red magnify-right" id="search-button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <ul class="list-inline mb-0 mr-1 text-nowrap">

                                @if (false)
                                    <li class="list-inline-item">
                                        @if (! auth()->guard('customer')->check())
                                            <a href="{{ route('login') }}" class="text-black h3"><i class="fas fa-user"></i></a>
                                        @else
                                            <div>
                                                <a href="#" role="button" id="customer-profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="text-dark h3">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customer-profile">
                                                    <div class="list-group">
                                                        <a href="{{ route('customer.account') }}" class="px-3 text-dark d-flex justify-content-between py-1 text-dark text-decoration-none">
                                                            My Account
                                                            <img src="{{ asset('images/account.svg') }}" class="img-fluid" alt="">
                                                        </a>
                                                        <form class="px-3" method="POST" action="{{ route('logout') }}" >
                                                            @csrf
                                                            <button class="d-flex py-1 justify-content-between btn px-0 w-100 text-dark" type="submit">
                                                                Logout
                                                                <i class="fas m-0 fa-sign-out-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endif

                                <li class="list-inline-item mr-4">
                                    <a class="d-flex text-decoration-none" href="{{ route('favorites') }}">
                                        <favorites-component>

                                        </favorites-component>
                                    </a>
                                </a>
                                </li>
                                <li class="list-inline-item">
                                    <cart-component
                                        css-classes="text-black d-flex text-decoration-none h3"
                                        checkout-url="{{ auth()->guard('customer')->check() ? route('checkout.index') :  route('guest.checkout.index') }}"
                                    >
                                    </cart-component>
                                </li>

                                <li class="list-inline-item">
                                    <a href="{{ route('faq') }}" class="text-black h3"><i class="far fa-question-circle"></i></a>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>