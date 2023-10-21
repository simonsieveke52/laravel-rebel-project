<div class="col-12 d-block d-sm-none">
	<h1 class="h3 mt-3 font-size-1-3rem line-height-1-8rem">{{ $product->name ?? '' }}</h1>
</div>

<div class="col-lg-6">
	<div class="product--image__wrapper alert alert-light text-center d-flex align-items-center mb-2 mb-md-0 position-relative">

		@if ( (int) $product->free_shipping === 1)
			<div class="position-absolute" style="left: 1rem; top: 1rem; z-index: 3;">
	            <span class="badge badge-primary shadow text-uppercase text-white py-1 px-2">Free Shipping</span>
	        </div>
		@endif

		<product-images-component :product="{{ json_encode($product) }}"></product-images-component>
	</div>
</div>

<div class="col-lg-6">

	<h1 class="h3 d-none d-sm-block font-size-md-1-5rem line-height-md-2-1rem">{{ $product->name ?? '' }}</h1>

	<div class="form-group mt-4">
		<money-component class="text-decoration-line-through" :value="{{ $product->original_price }}"></money-component>
		<money-component class="text-highlight h3" :value="{{ $product->price }}"></money-component> <a href="#bulk-price" id="bulk-price-link">See special bulk pricing</a>
	</div>

	<div class="d-flex d-lg-none mb-3 justify-content-between">	
		<product-cart-component class="d-flex flex-row align-items-center justify-content-center" :product="{{ json_encode($product) }}"></product-cart-component>
		<add-to-favorites :product="{{ json_encode($product) }}"></add-to-favorites>
	</div>

	<div class="p-3 bg-grey-light rounded-lg mb-3">
		<div class="mb-0 product-info-table">
			<div>
				<div class="row">
					<div class="col-6 py-2 px-0 border-0">
						Product ID
					</div>
					<div class="col-6 py-2 px-0 border-0 text-right text-highlight">
						{{ $product->id }}
					</div>
				</div>
	
				<div class="row">
					<div class="col-6 py-2 px-0">
						Price each
					</div>
					<div class="col-6 py-2 px-0 text-right text-highlight">
						<money-component :value="{{ $product->price_each }}"></money-component>
					</div>
				</div>
	
				@if($product->price_size != $product->price)
					<div class="row">
						<div class="col-6 py-2 px-0">
							Price per {{ $product->size_uom }}
						</div>
						<div class="col-6 py-2 px-0 text-right text-highlight">
							<money-component :value="{{ $product->price_size }}"></money-component>
						</div>
					</div>
				@endif
	
				@isset($product->sku)
					<div class="row">
						<div class="col-6 py-2 px-0">
							SKU:
						</div>
						<div class="col-6 py-2 px-0 text-right text-highlight">
							{{ $product->sku }}
						</div>
					</div>
				@endisset
			</div>
			<div id="additional-product-info-table" class="collapse">
				@if ($product->category instanceof \App\Category)
					<div class="row">
						<div class="col-6 py-2 px-0">
							Category:
						</div>
						<div class="col-6 py-2 px-0 text-right">
							<span class="text-highlight">{{ $product->category->name ?? '' }}</span>
						</div>
					</div>
				@endif
	
				@if ($product->brand instanceof \App\Brand)
					<div class="row">
						<div class="col-6 py-2 px-0">
							Brand:
						</div>
						<div class="col-6 py-2 px-0 text-right">
							<span class="text-highlight">{{ $product->brand->name ?? '' }}</span>
						</div>
					</div>
				@endif
				<div class="row">
					<div class="col-6 py-2 px-0">
						Model Number:
					</div>
					<div class="col-6 py-2 px-0 text-right">
						<span class="text-highlight">{{ $product->mpn }}</span>
					</div>
				</div>
				<div class="row">
					<div class="col-6 py-2 px-0">
						Weight:
					</div>
					<div class="col-6 py-2 px-0 text-right">
						<span class="text-highlight">{{ $product->weight }} {{ $product->weight_uom }}</span>
					</div>
				</div>
				<div class="row">
					<div class="col-6 py-2 px-0">
						GTIN/UPC:
					</div>
					<div class="col-6 py-2 px-0 text-right">
						<span class="text-highlight">{{ $product->upc }}</span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6"></div>
				<div class="col-6 py-2 text-right">
				  <a role="button" class="additional-info-toggle font-weight-bold collapsed" data-toggle="collapse" data-target="#additional-product-info-table">
					More information
				  </a>
				</div>
			</div>
		</div>
	</div>

	<div class="d-none d-lg-flex justify-content-between">	
		<product-cart-component class="d-flex flex-row align-items-center justify-content-center" :product="{{ json_encode($product) }}"></product-cart-component>
		<add-to-favorites :product="{{ json_encode($product) }}"></add-to-favorites>
	</div>

	@isset($product->short_description)
		<div class="p-4 rounded bg-light mb-3 mt-4">
			{!! $product->short_description !!}
		</div>
	@endisset
	
	<div class="mt-3">
		<h1 class="h6 font-weight-bold" id="bulk-price">Share</h1>
		<ul class="list-inline">
			<li class="list-inline-item px-1">
				<a target="_blank" class="text-red" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->full() }}">
					<i class="fab fa-facebook-f"></i>
				</a>
			</li>
			<li class="list-inline-item px-1">
				<a target="_blank" href="https://twitter.com/intent/tweet?text={{ urlencode("Check out this " . $product->name) . '+' . url($product->id) }}" class="text-red">
					<i class="fab fa-twitter"></i>
				</a>
			</li>
			<li class="list-inline-item px-1">
				<a target="_blank"
			href="http://pinterest.com/pin/create/button/?url={{ url($product->id) }}&media={{ url($product->main_image) }}&description={{ urlencode("Check out this " . $product->name) }}"
					class="text-red">
					<i class="fab fa-pinterest-p"></i>
				</a>
			</li>
			<li class="list-inline-item px-1">
				<a target="_blank" href="mailto:?subject=Check out this {{ $product->name }}&amp;body={{ "Check out this " . $product->name . ' '. PHP_EOL . url()->full() }}" class="text-red">
					<i class="fa fa-share-alt"></i>
				</a>
			</li>
		</ul>
	</div>
	
</div>

