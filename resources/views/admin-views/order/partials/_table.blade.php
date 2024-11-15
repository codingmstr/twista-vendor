@foreach($orders as $key=>$order)

<tr class="status-{{$order['order_status']}} class-all">
    <td class="">
        {{$key+1}}
    </td>
    <td class="table-column-pl-0">
        <a href="{{route($parcel_order?'admin.parcel.order.details':'admin.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
    </td>
    <td>
        <div>
            <div>
                {{date('d M Y',strtotime($order['created_at']))}}
            </div>
            <div class="d-block text-uppercase">
                {{date(config('timeformat'),strtotime($order['created_at']))}}
            </div>
        </div>
    </td>
    <td>
        @if($order->customer)
            <a class="text-body text-capitalize" href="{{route('admin.users.customer.view',[$order['user_id']])}}">
                <strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                <div>{{$order->customer['phone']}}</div>
            </a>
        @else
            <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
        @endif
    </td>
    @if ($parcel_order)

    @endif
    <td>
        @if ($parcel_order)
            <div>{{Str::limit($order->parcel_category?$order->parcel_category->name:translate('messages.not_found'),20,'...')}}</div>
        @elseif ($order->store)
            <div><a  class="text--title" href="{{route('admin.store.view', [$order->store_id,'module_id'=>$order['module_id']])}}" alt="view store">{{Str::limit($order->store?$order->store->name:translate('messages.store deleted!'),20,'...')}}</a></div>
        @else
            <div>{{Str::limit(translate('messages.not_found'),20,'...')}}</div>
        @endif
    </td>
    <td>
        <div class="text-right mw--85px">
            <div>
                {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
            </div>
            @if($order->payment_status=='paid')
            <strong class="text-success">
                {{translate('messages.paid')}}
            </strong>
            @elseif($order->payment_status=='partially_paid')
            <strong class="text-success">
                {{translate('messages.partially_paid')}}
            </strong>
            @else
            <strong class="text-danger">
                {{translate('messages.unpaid')}}
            </strong>
            @endif
        </div>
    </td>
    <td class="text-capitalize text-center">
        @if($order['order_status']=='pending')
            <span class="badge badge-soft-info">
              {{translate('messages.pending')}}
            </span>
        @elseif($order['order_status']=='confirmed')
            <span class="badge badge-soft-info">
              {{translate('messages.confirmed')}}
            </span>
        @elseif($order['order_status']=='processing')
            <span class="badge badge-soft-warning">
              {{translate('messages.processing')}}
            </span>
        @elseif($order['order_status']=='picked_up')
            <span class="badge badge-soft-warning">
              {{translate('messages.out_for_delivery')}}
            </span>
        @elseif($order['order_status']=='delivered')
            <span class="badge badge-soft-success">
              {{translate('messages.delivered')}}
            </span>
        @elseif($order['order_status']=='failed')
            <span class="badge badge-soft-danger">
              {{translate('messages.payment_failed')}}
            </span>
        @else
            <span class="badge badge-soft-danger">
              {{str_replace('_',' ',$order['order_status'])}}
            </span>
        @endif
        @if($order['order_type']=='take_away')
            <div class="text-info mt-1">
                {{translate('messages.take_away')}}
            </div>
        @else
            <div class="text-title mt-1">
              {{translate('messages.home Delivery')}}
            </div>
        @endif
    </td>
    <td>
        <div class="btn--container justify-content-center">
            <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn" href="{{route($parcel_order?'admin.parcel.order.details':'admin.order.details',['id'=>$order['id'],'module_id'=>$order['module_id']])}}">
                <i class="tio-invisible"></i>
            </a>
            <a class="ml-2 btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route($parcel_order?'admin.order.generate-invoice':'admin.order.generate-invoice',['id'=>$order['id'],'module_id'=>$order['module_id']])}}">
                <i class="tio-print"></i>
            </a>
        </div>
    </td>
</tr>

@endforeach


@if(count($orders) === 0)
<tr>
    <td colspan="12">
        <div class="empty--data">
            <img src="{{asset('assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
            <h5>
                {{translate('no_data_found')}}
            </h5>
        </div>
    </td>
</tr>
@endif
