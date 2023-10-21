@extends('voyager::master')

@section('page_title', 'Export orders')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="icon voyager-cloud-download"></i> Export orders
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
                        <form action="{{ route('product-handler.export') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <textarea name="ids" class="form-control" placeholder="Enter order ids to export here, separated by new line" cols="30" rows="10"></textarea>
                                <small class="text-warning">separated by new line</small>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Export</button>
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
