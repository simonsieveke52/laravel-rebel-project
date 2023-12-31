<section class="list list--reviews" id="reviews">
    <div class="list--reviews__wrapper position-relative">
            <div class="position-absolute r-0 t--1-2">
                <button class="btn btn-white add-a-review text-red button-outline-red" type="button">Add a review</button>
            </div>
            <h3 class="list--reviews__headline mb-3">Customer Reviews (<span class="review-count">{{ count($reviews) }}</span>)</h3>
            <div class="list--reviews__list">
                @if (!$reviews->isEmpty())
                @foreach ($reviews->sortByDesc('created_at') as $review)
                    @include('front.reviews.list-item')
                @endforeach
                @else
                <div class="d-none">
                    @include('front.reviews.list-item')
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="modal" tabindex="-1" role="dialog" id="review-modal-component">
    <div class="modal-dialog" role="document">
        <div class="modal-content container">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    Add a review
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('front.reviews.form', ['product' => $product])
            </div>
        </div>
    </div>
</div>
