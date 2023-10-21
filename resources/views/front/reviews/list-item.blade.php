<div class="card bg-light px-2 my-2 list__review" {{ isset($review) ? 'id="review-' . $review->id . '"' : '' }}>
    <div class="card-body p-2 p-md-3 row">
        <div class="col-md-4 col-12">
            <h4 class="mb-2 list__review__title card-title">{{ isset($review) ? $review->title : '' }}</h4>
            <p class="mb-1 list__review__name">{{ isset($review) ? 'reviewed by ' . $review->name : '' }}</p>
            <p class="mb-1 list__review__date">{{ isset($review) ? '(posted on ' . $review->formattedDate . ')' : '' }}</p>
            <div class="list__review__grade" {{ isset($review) ? 'id="review-' . $review->id . '-rating"' : '' }}>
                @include('front.reviews.stars',
                    ['rating' => isset($review) ? $review->grade : 0 ])
            </div>
        </div>
        <div class="col-md-8 col-12 card-text">
            <div class="mb-1 list__review__description">{{ isset($review) ? $review->description : '' }}</div>
        </div>
    </div>
</div>