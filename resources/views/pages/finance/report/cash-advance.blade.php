@extends('layouts.default')

@section('title', 'Report Cash Advance')
@section('custom_source_css')

@endsection
@section('styles')

@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li> 
      <li class="breadcrumb-item"><a href="#">Finance</a></li>
      <li class="breadcrumb-item"><a href="#">Cash Advance</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Approval</span></li></ol>
  </nav>
  <div class="row flex-grow" id="main-header">
    <div class="col-sm-12 stretch-card" style="position: relative;">
        <div class="card">
            <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
              <iframe width='100%' frameborder='0' allowTransparency='true' scrolling='auto' style="height: auto;min-height: 500px;" src='https://creatorapp.zohopublic.com/david_djokopramono_midplaza/finance-form/report-embed/All_Cash_Advances/MrvmUm5hRwVZt8PObBOqGNRKCnMAUVB6wdF3RFCA59y5EGkKZnxnWNnDmWNFZT8YJq4Z2UV2f8fmjapAM2gbJ3yOVb3E8My97PXh'></iframe>
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