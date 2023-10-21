@extends('voyager::master')

@section('page_title', 'Bulk Update order notes')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="icon voyager-receipt"></i> Upload csv file
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
                        <form action="{{ route('import.order') }}" method="POST" enctype="multipart/form-data">


                            @csrf
                            @method('PUT')
                            <div class="form-group">

                                <label>Fields to upload: <small class="font-weight-bold">order_id, notes</small></label>

                                <input type="file" name="file" class="form-control" style="padding: 8px;">

                                @if (! $errors->isEmpty())
                                    <span class="text-danger">{{ $errors->first() }}</span>
                                @endif

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
