@if(isset($dataTypeContent->{$row->field}))
    <div data-field-name="{{ $row->field }}">
        <a href="#" class="voyager-x remove-single-image" style="position:absolute;"></a>

        @php
            $src = $dataTypeContent->{$row->field};
        @endphp

        @if( !filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL))
            @php
                $src = \Storage::disk('public')->exists('products/productImages/' . $dataTypeContent->{$row->field})
                    ? asset('storage/products/productImages/' . $dataTypeContent->{$row->field})
                    : Voyager::image($dataTypeContent->{$row->field});
            @endphp
        @endif

        <img src="{{ $src }}"
          data-file-name="{{ $dataTypeContent->{$row->field} }}" data-id="{{ $dataTypeContent->getKey() }}"
          style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
          
    </div>
@endif
<input @if($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif type="file" name="{{ $row->field }}" accept="image/*">
