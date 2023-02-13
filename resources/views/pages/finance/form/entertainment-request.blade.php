@extends('layouts.default')

@section('title', 'Form Cash Advance')
@section('custom_source_css')

@endsection
@section('styles')

@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Entertainment Form</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Request</span></li></ol>
  </nav>
  <div class="row flex-grow" id="main-header">
    <div class="col-sm-12 stretch-card" style="position: relative;">
        <div class="card">
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">

              <iframe height='900px' width='100%' frameborder='0' allowTransparency='true' scrolling='auto' src='https://creatorapp.zohopublic.com/david_djokopramono_midplaza/request-entertainment/form-embed/Request_Form/AYNa7JmuONjXP65GF1gBDgVErpJvspGObV7fyqRJtdrBzQWPXWZ9ks1y767gdngMunvFJGCO8XmFWEVrO0YE5MhxAmjapWJnwnS0?Employee_ID={{$data['employee_id']}}&Requestor_Company={{$data['plant_name']}}&Plant={{$data['plant']}}&Date_Req={{date('d-M-Y')}}&Requestor_Name={{$data['employee_name']}}&Division={{$data['division']}}&Requestor_Department={{$data['department']}}'></iframe>
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
