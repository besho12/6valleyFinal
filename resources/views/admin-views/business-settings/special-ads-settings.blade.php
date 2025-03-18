@extends('layouts.back-end.app')

@section('title', 'Special ADS Setup')

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/announcement.png')}}" alt="">
                {{translate('special_ads_setup')}}
            </h2>
        </div>
        <form action="{{ route('admin.business-settings.specialads') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="name" class="title-color">{{translate('video_title')}}</label>
                        <input type="text" name="title" class="form-control" id="video_title"
                               placeholder="{{translate('enter_video_title')}}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="title-color">{{translate('video_points')}}</label>
                        <input type="text" name="points" class="form-control" id="video_points"
                               placeholder="{{translate('enter_video_points')}}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <input type="hidden" id="id" name="id">
                        <label for="video_url" class="title-color">{{ translate('video_url')}}</label>
                        <input type="url" name="url" class="form-control" id="video_url"
                               placeholder="{{translate('enter_video_url')}}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="description" class="title-color">{{translate('description')}}</label>
                        <textarea type="text" name="description" class="form-control" id="video_description"
                               placeholder="{{translate('enter_video_description')}}" required></textarea>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" id="id">
                    </div>
                </div>
            </div>
            <div class="d-flex gap-10 justify-content-end flex-wrap">
                <button type="submit" id="actionBtn" class="btn btn--primary px-4">{{ translate('save')}}</button>
                {{-- <a href="{{ route('admin.business-settings.specialads') }}" class="btn btn-outline--primary">{{ translate('view_all')}}</a> --}}
                <a id="update" class="btn btn--primary px-4 d--none">{{ translate('update')}}</a>
            </div>
        </form>

        @php
            $orders = [];
        @endphp

        <div class="table-responsive datatable-custom">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('order_ID')}}</th>
                        <th class="text-capitalize">{{translate('order_date')}}</th>
                        <th class="text-capitalize">{{translate('customer_info')}}</th>
                        <th>{{translate('store')}}</th>
                        <th class="text-capitalize">{{translate('total_amount')}}</th>
                        @if($status == 'all')
                            <th class="text-center">{{translate('order_status')}} </th>
                        @else
                            <th class="text-capitalize">{{translate('payment_method')}} </th>
                        @endif
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($orders as $key=>$order)

                    <tr class="status-{{$order['order_status']}} class-all">
                        <td class="">
                            {{$orders->firstItem()+$key}}
                        </td>
                        <td >
                            <a class="title-color" href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}} {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                        </td>
                        <td>
                            <div>{{date('d M Y',strtotime($order['created_at']))}},</div>
                            <div>{{ date("h:i A",strtotime($order['created_at'])) }}</div>
                        </td>
                        <td>
                            @if($order->is_guest)
                                <strong class="title-name">{{translate('guest_customer')}}</strong>
                            @elseif($order->customer_id == 0)
                                <strong class="title-name">{{translate('walking_customer')}}</strong>
                            @else
                                @if($order->customer)
                                    <a class="text-body text-capitalize" href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                        <strong class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                    </a>
                                    @if($order->customer['phone'])
                                        <a class="d-block title-color" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                    @else
                                        <a class="d-block title-color" href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                    @endif
                                @else
                                    <label class="badge badge-danger fz-12">
                                        {{ translate('customer_not_found') }}
                                    </label>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if(isset($order->seller_id) && isset($order->seller_is))
                                <a href="{{$order->seller_is == 'seller' && $order->seller?->shop ? route('admin.vendors.view', ['id'=>$order->seller->shop->id]) : 'javascript:' }}" class="store-name font-weight-medium">
                                    @if($order->seller_is == 'seller')
                                        {{ isset($order->seller?->shop) ? $order->seller?->shop?->name : translate('Store_not_found') }}
                                    @elseif($order->seller_is == 'admin')
                                        {{translate('in_House')}}
                                    @endif
                                </a>
                            @else
                                {{ translate('Store_not_found') }}
                            @endif
                        </td>
                        <td>
                            <div>
                                @php($orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order))
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $orderTotalPriceSummary['totalAmount']), currencyCode: getCurrencyCode()) }}
                            </div>

                            @if($order->payment_status == 'paid')
                                <span class="badge badge-soft-success">{{translate('paid')}}</span>
                            @else
                                <span class="badge badge-soft-danger">{{translate('unpaid')}}</span>
                            @endif
                        </td>
                        @if($status == 'all')
                            <td class="text-center text-capitalize">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info fz-12">
                                        {{translate($order['order_status'])}}
                                    </span>
                                @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                    <span class="badge badge-soft-warning fz-12">
                                        {{str_replace('_',' ',$order['order_status'] == 'processing' ? translate('packaging'):translate($order['order_status']))}}
                                    </span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-success fz-12">
                                        {{translate($order['order_status'])}}
                                    </span>
                                @elseif($order['order_status']=='failed')
                                    <span class="badge badge-danger fz-12">
                                        {{translate('failed_to_deliver')}}
                                    </span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success fz-12">
                                        {{translate($order['order_status'])}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger fz-12">
                                        {{translate($order['order_status'])}}
                                    </span>
                                @endif
                            </td>
                        @else
                            <td class="text-capitalize">
                                {{str_replace('_',' ',$order['payment_method'])}}
                            </td>
                        @endif
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{translate('view')}}"
                                    href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                </a>
                                <a class="btn btn-outline-success square-btn btn-sm mr-1" target="_blank" title="{{translate('invoice')}}"
                                    href="{{route('admin.orders.generate-invoice',[$order['id']])}}">
                                    <i class="tio-download-to"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-responsive mt-4">
            <div class="d-flex justify-content-lg-end">
                {!! $orders->links() !!}
            </div>
        </div>
        @if(count($orders) == 0)
            @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
        @endif
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush
