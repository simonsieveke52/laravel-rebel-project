@extends('layouts.front.product.master')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
@endsection

@section('page-content')

    <product-page-component 
        class="row product__buy-box py-lg-2 mb-4" 
        :parent="{{ json_encode($parent) }}"
        :product="{{ json_encode($product) }}"
        :children="{{ json_encode($children->filter()) }}"
        :bought-together="{{ json_encode($boughtTogether) }}"
    >
    </product-page-component>

    <div class="col-12 px-0 mb-4 mt-5">
        @include('front.reviews.list', ['reviews' => $reviews])
    </div>

@endsection

@push('seo')
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Product",
            "description": "{{ $product->short_description }}",
            "name": "{{ $product->name }}",
            "image": "{{ asset($product->main_image) }}",
            "offers": [
                {
                    "@type": "Offer",
                    "availability": "{{ $product->quantity > 0 ? 'http://schema.org/InStock' : 'http://schema.org/OutOfStock' }}",
                    "price": "{{ number_format($product->price, 2) }}",
                    "priceCurrency": "USD"
                }
            ]
        }
    </script>
@endpush

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <script defer>
    	$(function() {

            $('.rating').rateYo()

            $('body').on('click', '.add-a-review', function(event) {
                event.preventDefault();
                $('#review-modal-component').modal('show')
            });

    		$('body').on('submit','.form--add-review', function(e){
                e.preventDefault();
                var action = this.action;

                if ($('input[name="email_address"]').val() === '') {
                    alert('Email field is required')
                    return false;
                }

                $.ajax({
                    type: 'post',
                    url: action,
                    data: {
                        '_method': 'post',
                        'name': $('input[name="name"]').val(),
                        'title': $('input[name="title"]').val(),
                        'product_id': $('input[name="product_id').val(),
                        'email_address': $('input[name="email_address"]').val(),
                        'description': $('textarea[name="description"]').val(),
                        'grade': $('.add-review').find('.rating').rateYo('rating'),
                        'review_dont_fill': $('input[name="review_dont_fill"]').val(),
                    },
                    headers: { 'X-CSRF-Token' : $('input[name="_token"]').val() },
                    success: function(data) {
                        $('.ajax-response-message').remove();
                        $('.form--add-review').prepend('<div class="text-success h5 ajax-response-message">Thank you for contributing your review!</div>');
                        var $reviewDisplay = $($('.list__review:eq(0)').clone());
                        $('.list__review').attr('id','review-' + data.id);
                        $('.list__review__title', $reviewDisplay).html(data.title);
                        $('.list__review__name', $reviewDisplay).html('reviewed by ' + data.name);
                        $('.list__review__email_address', $reviewDisplay).html(data.email_address);
                        $('.list__review__date', $reviewDisplay).html('(posted on ' + data.formattedDate + ')');
                        $('.list__review__description', $reviewDisplay).html(data.description);
                        $('.list__review__grade', $reviewDisplay).attr('id', 'review-' + data.id + '-rating');
                        $('.rating', $reviewDisplay).attr('data-rateyo-rating', data.grade);
                        $('#review-' + data.id + '-rating', $reviewDisplay).rateYo({
                            rating: data.grade,
                            starWidth: "20px",
                            spacing: "5px",
                            halfStar: true,
                            readOnly: true,
                            ratedFill: "#bd2434"
                        });
                        $('.review-count').html(Number.parseInt($('.review-count')[0].innerText) + 1);
                        $('.list--reviews__list').prepend($reviewDisplay);
                        var timeout = window.setTimeout(function(){
                            $('[data-dismiss="modal"]').click();
                            $('input, textarea', '.form--add-review').not('[name="_token"]').val('');
                            var $rateYo = $('.form--add-review .rating').rateYo("rating", 0);
                            $('.ajax-response-message').remove();
                        }, 1000);

                        $('#review-modal-component').modal('hide')

                        toast('Thank you for your feedback')
                    },
                    error: function( data, status, error ) {
                        $('.ajax-response-message').remove();
                        $('.form--add-review').prepend('<div class="text-red h5 ajax-response-message">There was an error processing your request. ' + error + '</div>');
                        toast('<span class="text-danger">There was an error processing your request</span>')
                    }
                });
            })
    	})
    </script>
@endpush