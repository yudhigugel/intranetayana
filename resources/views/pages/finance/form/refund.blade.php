@extends('layouts.default')

@section('title', 'Form Refund')
@section('custom_source_css')

@endsection
@section('styles')

@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li> 
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Refund</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Request</span></li></ol>
  </nav>
  <div class="row flex-grow" id="main-header">
    <div class="col-sm-12 stretch-card" style="position: relative;">
        <div class="card">
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
              <iframe height='700px' width='100%' frameborder='0' allowTransparency='true' scrolling='auto' src="https://creatorapp.zohopublic.com/david_djokopramono_midplaza/finance-form/form-embed/REFUND_FORM/BeHeZUA7SH6Ttwq6AJ89RNmsBU0TVWXpbCHhfedEE9jne7yFFgDtZxU6fm8Y6EOUYWv9CARNmDVOrdN2z4y0aFXXNutPUvza7QYB?Employee_ID={{$data['employee_id']}}&Company_Code={{$data['company_code']}}&Plant={{$data['plant']}}&Req_Date={{date('d-M-Y')}}"></iframe>
            </div>
        </div>
    </div>
  </div>
@endsection 
@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){

  });
  
</script>
@endsection