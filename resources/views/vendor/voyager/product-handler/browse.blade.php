@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Blacklist SKU')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-x"></i> Blacklist SKU's
        </h1>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form action="{{ route('product-handler.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <textarea name="skus" class="form-control" placeholder="Enter SKU's to ignore here" cols="30" rows="10">{{ $skus }}</textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    
    <script>
        $(document).ready(function () {
            
        });

        var deleteFormAction;

        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = ''.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });
    </script>
@stop
