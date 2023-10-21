<li class="pl-{{ $category->depth + 2 }} position-relative mb-2 mb-sm-1 mb-md-0" data-parent-depth="{{ $category->depth + 2 }}">
	<a
		data-slug="{{ $category->slug }}" 
		data-depth="{{ $category->depth }}"
		data-id="{{ $category->id }}"
		href="{{ route('category.filter', $category) }}" 
		class="nav-item jq-category-click text-orange d-block position-relative 
			@if( is_active(route('category.filter', $category)) ) 
				font-weight-bold
			@endif
		">
		{{ $category->name }} 
	</a>
</li>

@if ( in_array($category->id, $parentCategoriesIds) )

	@php
		$childrens = $category->children()
						->withDepth()
						->orderBy('name', 'asc')
						->where('status', true)
						->remember(config('default-variables.cache_life_time'))
						->get(['name', 'id'])
	@endphp

	@if (! $childrens->isEmpty())
	    <ul class="list-unstyled">

		    @foreach($childrens as &$category)

				@php
					$category->depth += 1
				@endphp

		        @include('front.categories.category-nav', [ 
		        	'category' => $category
		        ])

		    @endforeach

	    </ul>
	@endif


@endif

