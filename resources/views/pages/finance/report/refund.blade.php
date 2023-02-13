@extends('layouts.default')

@section('title', 'Report Refund')
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
      <li aria-current="page" class="breadcrumb-item active"><span>Approval</span></li></ol>
  </nav>
  <div class="row flex-grow" id="main-header">
    <div class="col-sm-12 stretch-card" style="position: relative;">
        <div class="card">
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
              <iframe height='500px' width='100%' frameborder='0' allowTransparency='true' scrolling='auto' src='https://creatorapp.zohopublic.com/david_djokopramono_midplaza/finance-form/report-embed/All_Refund_Forms/WfCGUfvqbYTaMGKEPuhBOvVPqrj9DWUN5AHwRHrCAByuRHnk85USqtU10vWOS7aZUAxhgaw9UTsgSrqbqwdXRtdFZg2qSEDqhHA2?Req_date'></iframe>
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