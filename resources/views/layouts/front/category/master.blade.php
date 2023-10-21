@extends('layouts.front.app')

@section('content')

<div class="jq-categories-page">
	@yield('page-content')
</div>

@endsection

@if (request()->has('keyword'))
    @push('scripts')
        <script>
            try {
                window.dataLayer.push({
                    'event': 'search'
                });
            } catch (e) {
                console.log(e);
            }
        </script>
    @endpush
@endif
