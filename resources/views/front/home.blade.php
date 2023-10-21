@extends('layouts.front.app')
@section('body_class', 'home')
@section('content')

 <div class="modal fade" id="new-visit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{ asset('images/logo-modal.png') }}" class="img-fluid mx-auto d-block" alt="Rebel Smuggling logo">
                <h2 class="text-center mt-1">Instant {{ config('mailchimp.newsletter.discount') }}% Discount Coupon</h2>
                <p class="text-center">
                    Join our mailing list and we’ll send you a {{ config('mailchimp.newsletter.discount') }}% discount coupon right now.
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="row mx-lg-2">
                    @csrf
                    <div class="form-group col-lg-6">
                        <label for="first_name" class="font-weight-bold text-dark">First Name</label>
                        <input class="form-control rounded {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" placeholder="Enter your first name" value="{{ old('first_name') }}" required>
                        @if ($errors->has('first_name'))
                            <span class="invalid-feedback d-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="last_name" class="font-weight-bold text-dark">Last Name</label>
                        <input class="form-control rounded {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" placeholder="Enter your last name" value="{{ old('last_name') }}" required>
                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback d-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="email" class="font-weight-bold text-dark">Email</label>
                        <input class="form-control rounded {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" placeholder="email@example.com" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback d-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="text-dark font-weight-bold" for="origin_id">What best describes you/your industry?</label>
                        <select id="origin_id" name="origin_id" class="custom-select {{ $errors->has('origin_id') ? 'is-invalid' : '' }}" required>
                            <option value="" disabled {{ empty(old('origin_id')) ? 'selected' : '' }}>-- Select --</option>
                            @foreach($origins as $origin)
                                <option value="{{ $origin->id }}"{{ old('origin_id') == $origin->id ? 'selected' : '' }}>{{ $origin->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('origin_id'))
                            <span class="invalid-feedback d-block">
                                <strong>Select your industry</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-12 mt-2 text-right">
                        <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">Forget it</button>
                        <button type="submit" class="btn btn-highlight">Send my coupon!</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="mb-3 mt-3">

    <div>

        <div class="row row-cols-1">
            <div class="col mb-4">
                <div class="d-block d-flex position-relative">

                    <h1
                        class="hp-bulk-link text-center position-absolute text-white text-uppercase font-size-sm-3rem font-size-lg-3-5rem">
                        <a href="{{ route('quoterequest.index') }}" class="text-white d-block">Buy in bulk and save!</a>
                    </h1>

                    <a href="{{ route('quoterequest.index') }}"><img src="{{ asset( Croppa::url('storage/images/hero_spices.jpg', 925, 380, ['resize']) ) }}" class="card-img-top h-100 rounded" alt="{{ config('app.name') }}"></a>
                </div>
            </div>
        </div>



        <div class="row row-cols-1 row-cols-sm-2">
            @foreach ($categories->slice(0, 4) as $category)
            <div class="col mb-4">
                <div class="card d-flex align-items-center justify-content-center popular-bg h-100 hover-trans nounderline border-0">

                    <img
                        data-error="{{ config('default-variables.default-image') }}"
                        data-loading="{{ asset('images/px.png') }}"
                        class="card-img rounded"
                        src="{{ asset( Croppa::url($category->cover, 450, 195, ['resize']) ) }}"
                        alt="{{ $category->name }}"
                    >

                    <div class="card-img-overlay">
                        <a class="d-flex w-100 h-100 align-self-center justify-content-center text-decoration-none" href="{{ route('category.filter', $category) }}">

                            <h3 
                                class="text-white text-center d-flex flex-column align-items-center justify-content-center mb-0 h-100 font-size-only-md-1rem font-size-only-lg-1-5rem line-height-2-2rem max-w-280px mx-auto"
                            >
                                {{ trim(strtoupper($category->name)) }}
                            </h3>

                            <img 
                                src="{{ asset('images/category/learnMore.svg') }}" 
                                class="img-fluid learn-more d-none d-md-block" 
                                alt="Learn more"
                            >
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- END Popular Products --}}
    <div class="row">
        <div class="col-12">
            <div class="bg-white px-0 pt-4 pb-4">
                <div class="row">
                    <div class="col-md-6 pb-3 pb-md-0 align-items-center d-flex">
                        <div class="">
                            <h3 class="font-weight-bold mb-3">Prepare To Save</h3>
                            <p class="mb-4 w-100 m-md-0">
                                We mean business when it comes to getting you the products and customer service you need... when you need them.
                                <br><br>
                                In today's extremely aggressive marketplace, all companies face the issues of global competition and the ever increasing
                                costs of operating a business environment. We created a low-overhead business model and an extremely efficient global
                                supply chain so we can provide our customers with over 20,000 food service products at extremely low prices.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-end justify-content-end flex-row h-100 px-0">
                            <img
                                data-error="{{ config('default-variables.default-image') }}"
                                data-loading="{{ asset('images/px.png') }}"
                                src="{{ asset('images/px.png') }}"
                                v-lazy="{{ json_encode(asset( Croppa::url('storage/images/category/usp-jerky.jpg', 450, 423) )) }}"
                                class="img-fluid mr-md-0 m-auto rounded"
                                alt="Prepare To Save"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.jq-category-click').removeClass('jq-category-click')
        })
    </script>
    @if (!$visitedAlready)
    <script>
        $('#new-visit-modal').modal()
    </script>
    @endif
@endpush
