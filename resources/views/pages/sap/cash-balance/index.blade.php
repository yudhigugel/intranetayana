@extends('layouts.default')

@section('title', 'Bank Balance')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" href="/template/css/largetable.css">

@endsection
@section('styles')
<style type="text/css">
  .overlay {
    display: none;
    justify-content: center;
    align-items: flex-start;
    position: absolute;
    z-index: 2;
    opacity: 0;
    background: rgba(255, 255, 255, 0.86);
    transition: opacity 200ms ease-in-out;
    margin: -15px 0 0 0;
    top: 15px;
    left: 0;
    width:100%;
    height: 100%;
  }
  .overlay.in {
    opacity: 1;
    display: flex;
  }
  .fl-scrolls {
    bottom:0;
    height:35px;
    overflow:auto;
    position:fixed;
  }
  .fl-scrolls div {
    height:1px;
    overflow:hidden;
  }
  .fl-scrolls div:before {
    content:""; /* fixes #6 */
  }
  .fl-scrolls-hidden {
    bottom:9999px;
  }
  .sticky {
    position: fixed;
    top: 45px;
    z-index: 99;
    box-shadow: 0px 3px 7px -5px #878787;
  }
  .sticky + .main-wrapper {
    padding-top: auto;
  }
  #header{
    transform: all .7s ease-in-out;
  }

  #content-table tr th{
    text-align: center;
    /*border: 1px solid #ddd;*/
    font-size:12px !important;
  }

  #content-table tr td{
    text-align: center;
    border: 1px solid #ddd;
    font-size:11px !important;
  }

  td.parent-child{
    padding: 0px !important;
    border: none !important;
  }

  .child{
    padding: .3em;
  }

  .child > span.simple-tree-table-handler.simple-tree-table-icon{
    display: none !important;
  }
  .simple-tree-table-icon{
    margin-right: 5px !important;
    color: #000 !important;
  }
  .swal2-modal {
    min-height: 20px !important;
  }
  .largetable .largetable-maximize-btn,
  .largetable.largetable-maximized .largetable-maximize-btn {
    display: block;
  }
  .largetable.largetable-maximized .largetable-maximize-btn{
    right: 50px;
    bottom: 35px;
    top: auto;
  }
  .largetable .largetable-maximize-btn{
    right: 0px;
    bottom: auto;
    top: -50px;
  }
</style>
@endsection

@section('content')
	<nav aria-label="breadcrumb">
	<ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
	  <li class="breadcrumb-item"><a href="/">Home</a></li>
	  <li class="breadcrumb-item"><a href="#">SAP</a></li>
	  <li aria-current="page" class="breadcrumb-item active"><span>Bank Balance</span></li></ol>
	</nav>

	<div class="row flex-grow" id="main-header">
	    <div class="col-sm-12 stretch-card" style="position: relative;">
  	     <div class="card">
  				<div id="reactiveListener">
  					<cash-balance parent_need_password="{{ isset($data['IS_NEED_PASSWORD']) ? $data['IS_NEED_PASSWORD'] ? 1 : 0 : 1 }}" year_lookup="{{ isset($data['FISCAL_YEAR']) ? $data['FISCAL_YEAR'] : date('Y') }}" last_update_exchange="{{ date('d F Y') }}" date_start_contract="{{ date('m/d/Y',strtotime($data['date_start'])) }}" date_end_contract="{{ date('m/d/Y',strtotime($data['date_end'])) }}" template-based-on-role="{{ isset($data['IS_NEED_PASSWORD']) ? $data['IS_NEED_PASSWORD'] ? '' : $template : '' }}"></cash-balance>
  				</div>
  			</div>
    	</div>
	</div>

@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
<!-- <script type="text/javascript" src="/js/app/report/jquery.floatingscroll.js"></script> -->
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="/template/js/jquery-simple-tree-table.js"></script>
<script src="/template/js/largetable.js"></script>

<script>
 $(document).ready(function() {
    $('#daterange').daterangepicker(null, function(start, end, label) {
        const date_start = moment(start).format('YYYY-MM-DD');
        const date_end = moment(end).format('YYYY-MM-DD');
        window.location.href="{{url()->current()}}?date_start="+date_start+"&date_end="+date_end;
        // console.log(start.toISOString(), end.toISOString(), label);
    });

    $('#datepicker').datepicker({
      dateFormat: "yy-mm-dd",
      showWeek: true,
      changeYear: true,
      showButtonPanel: true,
      maxDate: new Date(),
      onSelect : function(text, obj){
        window.location.href="{{url()->current()}}?business_date="+text;
      }
      });
    $('#datepicker').prop('disabled', false);
    // $('#content-table').DataTable();
     
    
    $('.table-tree').simpleTreeTable({
      opened:'all',
    });

    // Collapse menu when ready
    try{
      $('body').addClass('sidebar-icon-only');
    } catch(error){}

    try {
      $("#content-table").largetable({
        enableMaximize:true
      });
    } catch(error){}
  });
</script>
@endsection

