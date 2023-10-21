@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> Amazon Orders
        </h1>
        
        @can('delete',app($dataType->model_name))
            @include('voyager::partials.bulk-delete')
        @endcan

        <a  class="btn btn-info" style="margin-top: 2px; border:none;" target="_blank" href="{{ route('export.orders', 'type=amazon') }}">Export</a>

        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')

    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form method="get" class="form-search">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Order Status</label>
                                    <select 
                                            data-size="6"
                                            data-width="100%"
                                            name="order_status" 
                                            class="form-control selectpicker" 
                                            data-style="btn-default"
                                            title="All"
                                        >

                                        <option {{ (request()->get('order_status', '') == 'all' && ! session()->has('amazon_order_status')) ? 'selected' : '' }} value="all">All</option>

                                        @foreach ($orderStatues as $status)
                                            <option   
                                                @if ( ( request()->get('order_status') !== 'all' && (int) request()->get('order_status', -1) === (int) $status->id) || ( session('amazon_order_status') !== 'all' && (int) session('amazon_order_status', -1) === $status->id))
                                                    selected
                                                @endif
                                                data-content="<span class='badge font-weight-bold' style='background-color: {{ $status->color  }}'>{{ $status->name }}</span> <small style='color: #000;' class='text-muted'>- {{ $status->description }}</small>"
                                                value="{{ $status->id }}">
                                                    {{ $status->name }}
                                            </option>
                                        @endforeach

                                    </select>  
                                </div>
                                <div class="col-md-2">
                                    <label for="">Display Type</label>
                                    <select 
                                            data-size="4"
                                            data-width="100%"
                                            name="order_type" 
                                            class="form-control selectpicker" 
                                            data-style="btn-default"
                                            title="All"
                                        >
                                        <option value="all">All</option>
                                        <option value="abandonments" {{ request()->get('order_type') === 'abandonments' ? 'selected' : '' }}>Abandonments</option>
                                        <option value="completed" {{ request()->get('order_type', 'completed') === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="pending" {{ request()->get('order_type') === 'pending' ? 'selected' : '' }}>Older than 48 hours with no tracking</option>
                                    </select>  
                                </div>
                                <div class="col-md-2">
                                    <label>Orders since</label>
                                    <input type="text" style="margin-top: 6px;" class="form-control date jq-datepicker" name="created_at" value="{{ request()->get('created_at') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>Query filter</label>
                                    <div id="search-input">
                                        <select id="search_key" name="key">
                                            @foreach($searchable as $key)
                                                    <option value="{{ $key }}" @if($search->key == $key){{ 'selected' }}@endif>
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                                    </option>
                                            @endforeach
                                        </select>
                                        <select id="filter" name="filter">
                                            <option value="contains" @if($search->filter == "contains"){{ 'selected' }}@endif>contains</option>
                                            <option value="equals" @if($search->filter == "equals"){{ 'selected' }}@endif>=</option>
                                        </select>
                                        <div class="input-group col-md-8">
                                            <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s" value="{{ $search->value }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-success btn-lg btn-block border-radius small-font-btn m-0" type="submit">
                                        Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <a href="{{ route('voyager.orders.index') }}" class="btn btn-default btn-lg btn-block border-radius small-font-btn m-0">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>

                                        @can('delete', app($dataType->model_name))
                                            <th>
                                                <input type="checkbox" class="select_all">
                                            </th>
                                        @endcan

                                        <th>
                                            @php
                                                $id = $dataType->browseRows->where('field', 'id')->first();
                                            @endphp

                                            <a href="{{ $id !== null ? $id->sortByUrl('id', request()->get('sort_order', 'asc')) : '' }}">

                                            Id

                                            @if ($id !== null && $id->isCurrentSortField('id'))
                                                @if (!request()->has('sort_order') || request()->sort_order == 'asc')
                                                    <i class="voyager-angle-up pull-right"></i>
                                                @else
                                                    <i class="voyager-angle-down pull-right"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th>
                                            @php
                                                $email = $dataType->browseRows->where('field', 'email')->first();
                                            @endphp

                                            <a href="{{ $email !== null ? $email->sortByUrl('email', request()->get('sort_order', 'asc')) : '' }}">
                                            Customer details

                                            @if ($email !== null && $email->isCurrentSortField('email'))
                                                @if (!request()->has('sort_order') || request()->sort_order == 'asc')
                                                    <i class="voyager-angle-up pull-right"></i>
                                                @else
                                                    <i class="voyager-angle-down pull-right"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th>
                                            @php
                                                $addresses = $dataType->browseRows->where('display_name', 'addresses')->first();
                                            @endphp

                                            <a href="{{ $addresses !== null ? $addresses->sortByUrl('order_hasmany_address_relationship', request()->get('sort_order', 'asc')) : '' }}">
                                            Billing address

                                            @if ($addresses !== null && $addresses->isCurrentSortField('order_hasmany_address_relationship'))
                                                @if (!request()->has('sort_order') || request()->sort_order == 'asc')
                                                    <i class="voyager-angle-up pull-right"></i>
                                                @else
                                                    <i class="voyager-angle-down pull-right"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th>
                                            @php
                                                $addresses = $dataType->browseRows->where('display_name', 'addresses')->first();
                                            @endphp

                                            <a href="{{ $addresses !== null ? $addresses->sortByUrl('order_hasmany_address_relationship', request()->get('sort_order', 'asc')) : '' }}">
                                            Shipping address

                                            @if ($addresses !== null && $addresses->isCurrentSortField('order_hasmany_address_relationship'))
                                                @if (!request()->has('sort_order') || request()->sort_order == 'asc')
                                                    <i class="voyager-angle-up pull-right"></i>
                                                @else
                                                    <i class="voyager-angle-down pull-right"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th>
                                            @php
                                                $total = $dataType->browseRows->where('field', 'total')->first();
                                            @endphp

                                            <a href="{{ $total !== null ? $total->sortByUrl('total_paid', request()->get('sort_order', 'asc')) : '' }}">
                                            Order

                                            @if ($total !== null && $total->isCurrentSortField('total_paid'))
                                                @if (!request()->has('sort_order') || request()->sort_order == 'asc')
                                                    <i class="voyager-angle-up pull-right"></i>
                                                @else
                                                    <i class="voyager-angle-down pull-right"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th>Type</th>
                                        <th>Status</th>

                                        <th>
                                            @php
                                                $timestamp = $dataType->browseRows->where('field', 'created_at')->first();
                                            @endphp

                                            <a href="{{ $timestamp !== null ? $timestamp->sortByUrl('created_at', request()->get('sort_order', 'asc')) : '' }}">
                                            Timestamp

                                            @if ($timestamp !==  null && $timestamp->isCurrentSortField('created_at'))
                                                @if (!request()->has('sort_order') || request()->sort_order == 'asc')
                                                    <i class="voyager-angle-up pull-right"></i>
                                                @else
                                                    <i class="voyager-angle-down pull-right"></i>
                                                @endif
                                            @endif
                                        </th>

                                        <th class="actions text-center">
                                            {{ __('voyager::generic.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($dataTypeContent as $data)

                                        <tr>
                                            @can('delete',app($dataType->model_name))
                                                <td>
                                                    <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                                </td>
                                            @endcan

                                            @foreach($dataType->browseRows as $row)

                                                    @if ( $loop->index == 0 )

                                                        <td class="text-nowrap">
                                                            <div style="margin-bottom: 3px;">
                                                                <kbd>#{{ $data->id }}</kbd>
                                                            </div>
                                                            <div>
                                                                <code>#{{ $data->amazon_order_id }}</code>
                                                            </div>
                                                        </td>

                                                    @elseif( $loop->index == 1 )

                                                        <td class="text-nowrap">
                                                            <p class="m-0">{{ $data->name }}</p>
                                                            <p class="m-0"><a href="tel:{{ $data->phone }}">{{ formatPhone($data->phone) }}</a></p>
                                                            <p class="m-0"><a href="mailto:{{ $data->email }}">{{ $data->email }}</a></p>
                                                            @if ($data->type == 'amazon')
                                                                <p class="m-0">
                                                                    <span class="badge badge-primary">Amazon order</span>
                                                                </p>
                                                            @endif
                                                        
                                                    @elseif( $loop->index == 2 )

                                                        <td class="text-nowrap">
                                                            <div class="address-col">                                                                
                                                                @if ( $data->billing_address )

                                                                    <p class="mb-0"><strong style="font-weight: 700 !important;">Billing address:</strong></p>
                                                                    <p class="mb-0">
                                                                        {{ $data->billing_address->address_1 }}
                                                                        {{ $data->billing_address->address_2 }}
                                                                    </p>
                                                                    <p>
                                                                        {{ $data->billing_address->state->name }}, {{ $data->billing_address->city }} {{ $data->billing_address->zipcode }}
                                                                    </p>

                                                                @endif
                                                            </div>
                                                        </td>

                                                    @elseif( $loop->index == 3 )

                                                        <td class="text-nowrap">
                                                            <div class="m-0 jq-address address-col">                                                            
                                                                @if ( $data->shipping_address )

                                                                    <p class="mb-0"><strong style="font-weight: 700 !important;">Shipping address:</strong></p>
                                                                    <p class="mb-0">
                                                                        {{ $data->shipping_address->address_1 }}
                                                                        {{ $data->shipping_address->address_2 }}
                                                                    </p>
                                                                    <p>
                                                                        {{ $data->shipping_address->state->name }}, {{ $data->shipping_address->city }} {{ $data->shipping_address->zipcode }}
                                                                    </p>

                                                                @endif
                                                            </div>
                                                        </td>

                                                    @elseif( $loop->index == 4 )

                                                        <td class="text-nowrap">
                                                            <p class="m-0"><strong>Shipping:</strong> ${{ number_format($data->shipping_cost, 2) }}</p>   
                                                            <p class="m-0"><strong>Subtotal:</strong> ${{ number_format($data->subtotal, 2) }}</p>   
                                                            <p class="m-0"><strong>Total paid:</strong> ${{ number_format($data->total, 2) }}</p>   

                                                            @if ( $data->confirmed == false )
                                                                <span class="badge badge-danger">Incomplete</span>
                                                            @endif
                                                        </td>

                                                    @elseif( $loop->index == 5 )

                                                        <td>

                                                            @if ( (int)$data->confirmed === 1 )

                                                                {{ $data->shipping_name }}

                                                            @else

                                                                N/A

                                                            @endif

                                                        </td>

                                                    @elseif( $loop->index == 6 )

                                                        <td class="text-nowrap">
                                                            <span class="badge d-block" style='background-color: {{ $data->orderStatus->color  }}'>
                                                                {{ $data->orderStatus->name }}
                                                            </span>
                                                        </td>
                                                        
                                                    @elseif( $loop->index == 7 )

                                                        <td class="text-nowrap">
                                                            {{ $data->created_at->format('m/d/Y h:iA') }}
                                                        </td>

                                                    @else
                                                        
                                                        @if ( ! $actionButtonsShowed )

                                                            @php
                                                                $actionButtonsShowed = true
                                                            @endphp

                                                            <td class="text-nowrap">

                                                                <div id="bread-actions">
                                                                    
                                                                    <a 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-default jq-mail-order" 
                                                                        data-order_id="{{ $data->id }}">
                                                                        <i class="voyager-mail"></i>
                                                                        <span class="hidden-xs hidden-sm">
                                                                            Send
                                                                        </span>
                                                                    </a>

                                                                    <a 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-success view" 
                                                                        data-toggle="modal" 
                                                                        href="#show-order-modal" 
                                                                        data-order_id="{{ $data->id }}">
                                                                        <i class="voyager-eye"></i>
                                                                        <span class="hidden-xs hidden-sm">
                                                                            View
                                                                        </span>
                                                                    </a>

                                                                    @foreach(Voyager::actions() as $action)

                                                                        @php $action = new $action($dataType, $data); @endphp

                                                                        @if ($action->shouldActionDisplayOnDataType())

                                                                            @if ($action->getTitle() == 'View' || $action->getTitle() == 'Delete')
                                                                                @php
                                                                                    continue
                                                                                @endphp
                                                                            @endif

                                                                            @can($action->getPolicy(), $data)
                                                                                <a href="{{ $action->getRoute($dataType->name) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                                                                                    <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm jq-remove-pull-right">{{ $action->getTitle() }}</span>
                                                                                </a>
                                                                            @endcan

                                                                        @endif

                                                                    @endforeach


                                                                    @if ( (int) $data->order_status_id !== 0)

                                                                        <a 
                                                                            type="button" 
                                                                            class="btn btn-sm btn-warning view" 
                                                                            data-toggle="modal" 
                                                                            href="#show-tracking-number" 
                                                                            data-order_id="{{ $data->id }}">
                                                                            <i class="voyager-truck"></i>
                                                                            <span class="hidden-xs hidden-sm">
                                                                                Track
                                                                            </span>
                                                                        </a>

                                                                    @endif

                                                                    @if (! $data->refunded && (int) $data->confirmed === 1 && trim($data->transaction_id) !== '' )

                                                                        @if ( (int) $data->order_status_id === 0 )

                                                                            <a 
                                                                                href="{{ route('approve-or-decline.order', [$data, 'decline']) }}"
                                                                                class="btn btn-sm btn-danger"
                                                                                onclick="return confirm('Are you sure?')"
                                                                                data-id="{{ $data->id }}"
                                                                            >
                                                                                <i class="icon voyager-trash"></i>
                                                                                <span class="hidden-xs hidden-sm">
                                                                                    Decline
                                                                                </span>
                                                                            </a>

                                                                            <a 
                                                                                href="{{ route('approve-or-decline.order', [$data, 'approve']) }}"
                                                                                class="btn btn-sm btn-success"
                                                                                onclick="return confirm('Are you sure?')"
                                                                                data-id="{{ $data->id }}"
                                                                            >
                                                                                <i class="icon voyager-check"></i>
                                                                                <span class="hidden-xs hidden-sm">
                                                                                    Approve
                                                                                </span>
                                                                            </a>

                                                                        @else

                                                                            <a 
                                                                                href="{{ route('refund.order', $data) }}"
                                                                                title="Refund"
                                                                                class="btn btn-sm btn-danger jq-refund"
                                                                                onclick="return confirm('Are you sure?')"
                                                                                data-id="{{ $data->id }}"
                                                                            >
                                                                                <i class="icon voyager-frown"></i>
                                                                                <span class="hidden-xs hidden-sm">
                                                                                    Refund
                                                                                </span>
                                                                            </a>

                                                                        @endif
                                                                        
                                                                    @endif

                                                                </div>

                                                            </td>

                                                        @endif

                                                        @if ($loop->last)

                                                            @php
                                                                $actionButtonsShowed = false
                                                            @endphp

                                                        @endif

                                                    @endif

                                            @endforeach

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pull-left">
                            <div role="status" class="show-res" aria-live="polite">{{ trans_choice(
                                'voyager::generic.showing_entries', $dataTypeContent->total(), [
                                    'from' => $dataTypeContent->firstItem(),
                                    'to' => $dataTypeContent->lastItem(),
                                    'all' => $dataTypeContent->total()
                                ]) }}
                            </div>
                        </div>

                        <div class="pull-right">
                            {{ $dataTypeContent->appends($paginationFilter)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-danger fade" tabindex="-1" id="update-order-status-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        Please confirm this update
                    </h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.update.orderStatus') }}" method="POST" class="text-left">
                        <div class="form-group">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="orderStatus" value="">
                        </div>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-danger form-edit-add" value="Yes, Confirm It!">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" tabindex="-1" id="show-tracking-number" role="dialog" data-backdrop='static'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <strong>Order id #<span class="order_id"></span></strong>
                        <span class="badge jq-orderStatus"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('voyager.create.tracking') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="">
                                <div class="panel panel-default mb-0">
                                    <div class="panel-heading p-8">New tracking number?</div>
                                    <div class="panel-body">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-3 mb-0">
                                                    <label class="mt-1">Tracking Number</label>
                                                </div>
                                                <div class="col-sm-9 mb-0">
                                                    <input name="tracking_number" type="text" value="" class="form-control" placeholder="...">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-0">
                                            <div class="row">
                                                <div class="col-sm-12 text-right mb-0">
                                                    <button class="btn btn-default jq-add-tracking-number">Add</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped border">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Carrier</th>
                                        <th class="text-nowrap">Tracking number</th>
                                        <th class="text-nowrap">Link</th>
                                        <th class="text-nowrap">Created at</th>
                                    </tr>
                                </thead>
                                <tbody id="tracking-numbers-tbody">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-right">
                        <button type="button" class="btn btn-default m-0" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info m-0 jq-btn-tracking-sendmail">Send Email</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="show-order-modal" role="dialog" data-backdrop='static'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <strong>Order id <kbd>#<span class="order_id"></span></kbd></strong>
                        <span class="badge jq-orderStatus"></span>
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-0">
                            <div class="panel panel-default panel-min-height">
                                <div class="panel-heading">Customer</div>
                                <div class="panel-body">
                                    <p>
                                        <strong class="jq-name"></strong> <br>
                                        <span class="jq-email"></span> <br>
                                        <span class="jq-phone"></span> <br>
                                        <span class="jq-address"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-0">
                            <div class="panel panel-default panel-min-height">
                                <div class="panel-heading">Payment</div>
                                <div class="panel-body">
                                    <p class="mb-0">
                                        Products cost: $<span class="jq-order-total-products"></span> <br>
                                        Shipping cost: $<span class="jq-order-shipping"></span>  <br>
                                        Tax: $<span class="jq-order-tax"></span> <strong>(<span class="jq-tax-rate"></span>%)</strong> <br>
                                        <strong class="text-danger">
                                            Total paid: $<span class="jq-order-total-paid"></span>
                                        </strong>
                                    </p>
                                </div>
                                <div class="panel-footer">
                                    <strong>
                                        <span class="jq-payment"></span>: ************<span class="jq-cc-number"></span>
                                    </strong>
                                    <span class="float-right">
                                        At: <span class="jq-order-created_at"></span>
                                    </span>
                                </div>
                            </div>  
                        </div>
                    </div>

                    <table class="table table-striped border">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price/Qty</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody id="order-products-tbody">
                            
                        </tbody>
                    </table>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script id="order-products" type="text/x-jquery-tmpl">
        <tr>
            <td>
                <p class="mb-0">
                    ID: <a target="_blank" href="${link}">${id}</a>
                </p>
                <p class="mb-0">
                    Item number: <a target="_blank" href="${link}">${vendor_code}</a>
                </p>
                <p class="mb-0">
                    UPC: ${upc}
                </p>
                <p class="mb-0">
                    Name: ${name}
                </p>
            </td>
            <td>
                <p class="mb-0">
                    Quantity: ${quantity}
                </p>   
                <p class="mb-0">
                    Price: $${price}
                </p>
            </td>
            <td class="text-right">
                <a target="_blank" href="${link}" class="btn btn-info btn-sm">View</a>
            </td>
        </tr>
    </script>

    <script id="order-tracking-numbers" type="text/x-jquery-tmpl">
        <tr>
            <td>
                <p class="mb-0">
                    ${name}    
                </p>
            </td>
            <td>
                <p class="mb-0">
                    ${number}    
                </p>
            </td>
            <td>
                <p class="mb-0">
                    <a target="_blank" href="https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${number}&cntry_code=us">${number}</a>
                </p>
            </td>
            <td>
               <p class="mb-0">
                    ${created_at}
                </p>
            </td>
        </tr>
    </script>

    <script id="order-status-template" type="text/x-jquery-tmpl">
        <tr>
            <td>
                <span class="badge" style="background-color: ${color};">${name}</span>
            </td>
            <td>
                ${pivot.note}
            </td>
            <td>
                ${pivot.formated_created_date}
            </td>
        </tr>
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
    @endif

    <style>
        #bread-actions{
            display: grid;
            grid-template-columns: repeat(3, 33%);
        }

        #bread-actions .btn{
            margin: 4px !important;
            padding: 3px 6px !important;
            font-size: 0.92rem !important;
        }
    </style>
@stop

@section('javascript')
    <!-- DataTables -->
    <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-tmpl.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {

            $('.jq-datepicker').datetimepicker({
                viewMode: 'days',
                format: 'MM/DD/YYYY'
            });

            $('#search-input select').select2({
                minimumResultsForSearch: Infinity
            });

            $('.jq-remove-pull-right').parents('.btn').removeClass('pull-right')

            $('body').on('click', '.jq-mail-order', function(event) {
                event.preventDefault();
                $('#voyager-loader').fadeIn()
                let orderId = $(this).attr('data-order_id')

                $.ajax({
                    url: @json(route('voyager.mail.order')),
                    type: 'POST',
                    dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                    data: {id: orderId, _token: $('meta[name="csrf-token"]').attr('content') },
                })
                .done(function(response) {
                    toastr.success('Invoice mailed successfully.') 
                })
                .fail(function(response) {
                    if (response.status == 200) {
                        toastr.success('Invoice mailed successfully.') 
                    } else {
                        toastr.error('Somthing went wrong.') 
                    }
                })
                .always(function() {
                    $('#voyager-loader').fadeOut()
                });
            });

            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });

            $('body').on('change', 'select.order_status', function(event) {
                event.preventDefault();
                var value = $(this).val()
                var id = $(this).data('id')
                $('#update-order-status-modal').modal('show')
                $('#update-order-status-modal').find('[name="id"]').val(id)
                $('#update-order-status-modal').find('[name="orderStatus"]').val(value)
            });

            $('#show-note-modal').on('show.bs.modal', function (event) {
                var modal = $(this)
                var button = $(event.relatedTarget)
                var note = button.attr('data-note')
                modal.find('.alert').empty().text(note)
            })


            $('#show-tracking-number').on('show.bs.modal', function (event) {

                $('#voyager-loader').fadeIn()

                var button = $(event.relatedTarget)
                var order_id = button.data('order_id')
                var address = button.parents('tr').find('.jq-address').html()
                var modal = $(this)
                modal.find('.order_id').text(order_id)
                modal.find('[name="id"]').val(order_id)
                modal.find('.jq-btn-tracking-sendmail').data('id', order_id)

                $.post( @json(route('voyager.show.tracking')), {id: order_id, _token: $('meta[name="csrf-token"]').attr('content') }, function(response) {

                    $('#voyager-loader').fadeOut()

                    $('#tracking-numbers-tbody').empty()

                    $('#order-tracking-numbers').tmpl(response.data).appendTo('#tracking-numbers-tbody')

                }, 'json')

            });

            $('.jq-btn-tracking-sendmail').on('click', function(event) {
                event.preventDefault();
                $('#voyager-loader').fadeIn()

                var order_id = $(this).data('id')

                $.ajax({
                    url: @json(route('voyager.notify.tracking')),
                    type: 'GET',
                    data: {id: order_id, _token: $('meta[name="csrf-token"]').attr('content') },
                })
                .done(function() {
                    toastr.success('Email submited successfully')
                })
                .fail(function() {
                    toastr.error('Somthing went wrong. Check if there is any tracking numbers added for this order')
                })
                .always(function() {
                    $('#voyager-loader').fadeOut()
                });
            });

            $('.jq-add-tracking-number').on('click', function(event) {
                event.preventDefault();

                $('#voyager-loader').fadeIn()

                var form = $(this).parents('form');
                var url = form.attr('action');

                var data = new FormData
                data.append('id', form.find('[name="id"]').val())
                data.append('tracking_number', form.find('[name="tracking_number"]').val())
                data.append('_token', form.find('[name="_token"]').val())
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    processData: false,
                    contentType: false,
                })
                .done(function(response) {
                    toastr.success('Tracking number successfully added')
                    $('#order-tracking-numbers').tmpl(response.data).appendTo('#tracking-numbers-tbody')
                })
                .fail(function(d) {
                    $.each(d.responseJSON.errors, function (inputName, errorMessage) {
                        toastr.error(errorMessage)
                    });
                })
                .always(function() {
                    $('#voyager-loader').fadeOut()
                });
            });

            $('body').on('click', '.jq-review-order', function(event) {
                event.preventDefault();
                
                if (confirm('Are you sure?') === false) {
                    return
                }

                let orderId = $(this).attr('data-order_id')
                let url = @json(route('order.review'));

                $('#voyager-loader').fadeIn()

                $.ajax({
                    url: url + '/' + orderId,
                    type: 'POST'
                })
                .done(function(response) {
                    toastr.success(response.message)
                })
                .fail(function(response) {
                    toastr.error(response.responseJSON.message)
                })
                .always(function() {
                    $('#voyager-loader').fadeOut()
                });
            });


            $('#show-order-modal').on('show.bs.modal', function (event) {

                $('#voyager-loader').fadeIn()

                var button = $(event.relatedTarget)
                var order_id = button.data('order_id')
                var address = button.parents('tr').find('.jq-address').html()
                var modal = $(this)
                modal.find('.order_id').text(order_id)

                $.post( @json(route('voyager.show.order')), {id: order_id, _token: $('meta[name="csrf-token"]').attr('content') }, function(response) {

                    modal.find('[name="id"]').val(response.order.id)
                    modal.find('.jq-payment').text(response.order.payment)
                    modal.find('.jq-cc-number').text(response.order.cc_number)
                    modal.find('.jq-order-created_at').text(response.order.created_at)

                    modal.find('.jq-order-total-paid').text(response.order.total_paid)
                    modal.find('.jq-order-shipping').text(response.order.shipping_cost)
                    modal.find('.jq-order-tax').text(response.order.tax)
                    modal.find('.jq-order-total-products').text(response.order.total_products)


                    modal.find('.jq-address').html('<address>'+ address +'</address>')

                    modal.find('.jq-name').text(response.order.name)

                    modal.find('.jq-email').html(
                        '<a href="mailto:' + response.order.email + '">' + response.order.email + '</a>'
                    )

                    modal.find('.jq-phone').html(
                        '<a href="tel:' + response.order.phone + '">' + response.order.phone + '</a>'
                    )

                    modal.find('.jq-tax-rate').text( response.tax_rate )

                    $('#order-products-tbody').empty()
                    $('#order-products').tmpl(response.products).appendTo('#order-products-tbody')

                    $('#voyager-loader').fadeOut()

                }, 'json');

                setTimeout(function(){
                    $('#voyager-loader').fadeOut()
                }, 5000)

            })
        });

        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });
    </script>
@stop
