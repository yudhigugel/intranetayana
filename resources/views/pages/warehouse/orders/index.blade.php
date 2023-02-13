@extends('layouts.order')
@section('title','Purchase Order')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('widgets.breadcrumb',['data'=>['Home','Warehouse','Orders']])
    </div>
</div>
<div id="warehouseOrder">
    <warehouse-order></warehouse-order>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/app/warehouse/order.css') }}">
@endsection
@section('scripts')
<script src="{{ asset('/js/app/warehouse/orders/index.js') }}"></script>
@endsection