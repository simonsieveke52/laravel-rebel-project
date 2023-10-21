<div class="offcanvas-collapse" id="main-navbar">
	<div class="d-md-none">
		<div class="modal-header d-flex align-items-center border-bottom-0">
			<div style="width: 80%;">
                <form method="GET" action="{{ route('product.search') }}">
                    <div class="input-group m-0">
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
                            <button aria-label="search" type="submit" class="bg-white border border-red border-left-0 px-3 text-red magnify-right">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <button class="btn btn-danger text-white rounded-circle" style="padding: 0px 6.5px;" @click="toggleElement('#main-navbar')" aria-label="Close" id="navbar-toggler">
            	<span aria-hidden="true">&times;</span>
            </button>
        </div>
	</div>
	<div class="navbar--categories bg-grey-light py-3 px-2 rounded-lg mt-0 mt-md-3">
		<div class="border-0 mb-3 text-capitalize" id="categories-filter-container">
			<form class="category-filter">

				{{ csrf_field() }}

			    <ul class="navbar-nav mr-auto position-relative list-unstyled mt-md-0 mt-lg-1 position-relative">

			        @foreach($categories as $category)
						
			        	@if( isset($parentCategoriesIds) && in_array( $category->id, $parentCategoriesIds ) )

			        		<li class="mb-2 mb-sm-1 mb-md-0 position-relative @if( $loop->last ) border-bottom-0 @endif pl-{{ $category->depth }}">
								<a 
									data-slug="{{ $category->slug }}"
									data-depth="{{ $category->depth }}"
									href="{{ route('category.filter', $category) }}" 
									class="nav-item position-relative jq-category-click @if( is_active(route('category.filter', $category)) ) font-weight-bold @endif"
								>
                                {{ $category->name }}

									@if( is_active(route('category.filter', $category)) || in_array( $category->id, $parentCategoriesIds ) )
										<a class="position-absolute d-flex text-decoration-none" style="right: 0; top: 0; cursor: pointer;" href="{{ route('category.filter', $category) }}" >
											<i class="fas fa-minus"></i>
										</a>
									@endif
								</a>
							</li>

							<ul class="list-unstyled">

								@php
									$childrens = $category->children()
										->withDepth()
										->where('status', true)
										->remember(config('default-variables.cache_life_time'))
										->orderBy('name', 'asc')
										->get()
								@endphp

								@if( ! $childrens->isEmpty() )

									@foreach( $childrens as &$children )

										@php
											$children->depth += 1
										@endphp

										@include('front.categories.category-nav', [
											'parentCategoriesIds' => $parentCategoriesIds,
											'category' => $children
										])

									@endforeach

								@endif

							</ul>
							
			        	@else

				        	<li class="mb-2 mb-sm-1 mb-md-0 position-relative @if( $loop->last ) border-bottom-0 @endif pl-{{ $category->depth - 1 }}">

								<a 
									data-slug="{{ $category->slug }}"
									data-depth="{{ $category->depth }}"
									href="{{ route('category.filter', $category) }}" 
									class="nav-item jq-category-click @if( is_active(route('category.filter', $category)) ) font-weight-bold @endif"
								>
									{{ $category->name }}

									<a class="position-absolute small" style="right: 0; top: 3px; cursor: pointer;" href="{{ route('category.filter', $category) }}" >
										@if( is_active(route('category.filter', $category)) )
											<i class="fas fa-minus"></i>
										@else
											<i class="fas fa-plus"></i>
										@endif
									</a>

								</a>

							</li>	

			        	@endif
			            
			        @endforeach

			    </ul>

			</form>
		</div>
	</div>
    <div class="d-flex d-md-none justify-content-center align-items-center mx-auto pt-1">
        <div class="text-center mt-2 py-1 font-size-0-9rem">
            <div class="h4 font-size-1-35rem text-dark robto mb-0">
                Have questions? <a class="text-dark" href="{{ route('faq') }}">See FAQ</a>
            </div>
            <div class="d-flex flex-column py-1">
                <a class="text-dark h5 font-weight-bold robto mb-0" href="tel:{{ config('default-variables.phone') }}">{{ formatPhone(config('default-variables.phone')) }}</a>
                <a class= "px-0 text-dark robto mt-1">M-F 9:30am-5:30pm Eastern</a>
            </div>
        </div>
    </div>
</div>