@if($errors->all())

    @foreach($errors->all() as $message)
        <div class="alert alert-warning alert-dismissible mb-0 border-radius-0">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endforeach

@elseif(session()->has('message'))
    @if(session()->has('email'))
    <div class="alert alert-success alert-dismissible mb-0 border-radius-0">
        {{ session()->get('message') }} <a href="mailto:{{ session()->get('email') }}">{{ session()->get('email') }}</a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    @else
    <div class="alert alert-success alert-dismissible mb-0 border-radius-0">
        {{ session()->get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    @endif
    
@elseif(session()->has('error'))
    
    <div class="alert alert-danger alert-dismissible mb-0 border-radius-0">
        {{ session()->get('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    
@endif