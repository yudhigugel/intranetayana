@extends('layouts.default')

@section('title', 'Guest Folio')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<style type="text/css">
.dt-buttons .dataTables_length{
  float:left;
}
.dt-buttons .dataTables_filter{
  float:right;
}
.table,
.dataTables_wrapper{
  position: relative;
}
.dataTables_info{
  position: absolute;
  bottom: 1em;
}
#folio-header{
  position: relative;
}
.overlay {  
  display: none;
  opacity: 0;
  left: 45%;
  position: absolute;
  bottom: -2.5em;
}
.overlay.in {
  opacity: 1;
  display: flex;
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li> 
    <li class="breadcrumb-item"><a href="/folio">POS</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Guest Folio</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="card">
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
          <div class="mb-3" id="folio-header">
            <h4 class="text-muted"><i class="fa fa-address-book"></i>&nbsp;&nbsp;FOLIO FOR CONFIRMATION NO : {{ $conf_no }}</h4>
            {{ csrf_field() }}
            <div class="overlay in">
              <img style="width: 40px;" src="{{ asset('image/loader.gif') }}" alt="Tab Loader">
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">

  function createDataTable(element){
    // console.log(element);
    // if (!$.fn.dataTable.isDataTable(element)) {
    //   var table = $(element).DataTable({
    //     "columns": [
    //      { "width": "5%" },
    //      { "width": "15%" },
    //      { "width": "20%" },
    //      { "width": "25%" },
    //      { "width": "10%" },
    //      { "width": "20%" }
    //      ]
    //   });
    //   table.columns.adjust().draw();
    // }
  }

  function keyValueSearch(obj, valueToSearch){
    try{
      return obj[valueToSearch] || "";
    } catch(error){
      console.log("Error getting keys", error)
      return;
    }
  }

  function clearOverlay(){
    try{
      $('.overlay').removeClass('in');
    } catch(error){
      console.log(error)
    }
  }

  function addElementNextTo(element, callback){}

  function makeTabContent(navtabs, content){
    if(navtabs){
      try {
        if(content && typeof content == 'object'){
          var tab_content_start = '<div class="tab-content">',
          tab_pane = '',
          active = 1;
          for(key in content){
            if(content.hasOwnProperty(key)){
              var __body = '';

              content[key].forEach(function(item){
                var FOLIO_NO = keyValueSearch(item, 'FOLIO_NO'),
                CONF_NO = keyValueSearch(item, 'CONF_NO'),
                ROOM_CLASS = keyValueSearch(item, 'ROOM_CLASS'),
                ROOM_CLASS_DISPLAY = keyValueSearch(item, 'ROOM_CLASS') || "-",
                GUEST_NAME = keyValueSearch(item, 'GUEST_NAME'),
                ADDRESS = keyValueSearch(item, 'ADDRESS'),
                ROOM_CLASS = keyValueSearch(item, 'ROOM_CLASS'),
                ROOM = keyValueSearch(item, 'ROOM'),
                CASHIER = keyValueSearch(item, 'CASHIER'),
                FOLIO_AMOUNT = keyValueSearch(item, 'FOLIO_AMOUNT');
                // Prepare to append data to table body
                __body += 
                `<tr>
                  <td>${item.COUNTER}</td>
                  <td>
                    <a style='cursor:pointer;text-decoration:none;' href='/folio/get_invoice?folio_no=${FOLIO_NO}&conf_no=${CONF_NO}&room_class=${ROOM_CLASS}&window=${key}'>${FOLIO_NO}</a>
                  </td>
                  <td>${GUEST_NAME}</td>
                  <td>${ADDRESS}</td>
                  <td>${ROOM_CLASS_DISPLAY}</td>
                  <td>${ROOM}</td>
                  <td>${CASHIER}</td>
                  <td class="text-right">${FOLIO_AMOUNT}</td>
                </tr>`;
              });

              if(active == 1){
                tab_pane += 
                `<div class="tab-pane fade show active" id="window-${key}" role="tabpanel" style="width:100% !important">
                  <div>
                    <table id="window-table-${key}" class="table table-striped table-bordered">
                     <thead>
                        <tr>
                         <th class="all" width="5%">NO</th>
                         <th class="all" width="10%">FOLIO NO</th>
                         <th class="none" width="20%">GUEST NAME</th>
                         <th class="none" width="20%">ADDRESS</th>
                         <th class="none" width="10%">ROOM CLASS</th>
                         <th class="all" width="10%">ROOM</th>
                         <th class="all" width="10%">CASHIER</th>
                         <th class="all" width="10%">TOTAL AMOUNT</th>
                        </tr>
                     </thead>
                     <tbody>
                      ${__body}
                     </tbody>
                   </table>
                  </div>
                </div>`;
              }
              else {
                tab_pane += 
                `<div class="tab-pane fade" id="window-${key}" role="tabpanel" style="width:100% !important">
                  <div>
                    <table id="window-table-${key}" class="table table-striped table-bordered">
                     <thead>
                        <tr>
                         <th class="all" width="5%">NO</th>
                         <th class="all" width="10%">FOLIO NO</th>
                         <th class="none" width="20%">GUEST NAME</th>
                         <th class="none" width="20%">ADDRESS</th>
                         <th class="none" width="10%">ROOM CLASS</th>
                         <th class="all" width="10%">ROOM</th>
                         <th class="all" width="10%">CASHIER</th>
                         <th class="all" width="10%">TOTAL AMOUNT</th>
                        </tr>
                     </thead>
                     <tbody>
                      ${__body}
                     </tbody>
                   </table>
                  </div>
                </div>`;
              }
              active++;
            }
          }
          var tab_content_end = '</div>';
          var element_to_add = tab_content_start + tab_pane + tab_content_end;
          navtabs.insertAdjacentHTML('afterend', element_to_add);
          try{
            // setTimeout(function(){
            //   $('#window-table-1').DataTable({
            //     "columns": [
            //      { "width": "5%" },
            //      { "width": "15%" },
            //      { "width": "20%" },
            //      { "width": "25%" },
            //      { "width": "10%" },
            //      { "width": "20%" }
            //      ]
            //   });
            // },500)
          } catch(error){
            console.log(error);
          }
        }
      } catch(error) {
        Swal.fire('Something went Wrong', error, 'error');
      }

    // END IF NAVTABS
    }
  }

  function makeTabs(loop) {
    if(Object.keys(loop).includes("0")){
      var start_tabs = '<ul class="nav nav-tabs" role="tablist" id="nav-tabs">';
      var __tabs = '';
      var __content = {};
      var active_tabs = 1;
      for(var key in loop[0]){
        if(loop[0].hasOwnProperty(key)){
          __content[key] = loop[0][key];
          if(active_tabs == 1){
            __tabs += `<li class="nav-item"><a class="nav-link active" onclick="createDataTable('#window-table-${key}')" data-toggle="tab" href="#window-${key}" role="tab">Window ${key}</a></li>`;
          }
          else{
            __tabs += `<li class="nav-item"><a class="nav-link" onclick="createDataTable('#window-table-${key}')" data-toggle="tab" href="#window-${key}" role="tab">Window ${key}</a></li>`;
          }
          active_tabs++;
        }
      }
      var end_tabs = '</ul>';
      var beforeElementSibling = document.getElementById('folio-header') || [];
      if(beforeElementSibling){
        var element_to_add = start_tabs + __tabs + end_tabs;
        clearOverlay();
        beforeElementSibling.insertAdjacentHTML('afterend', element_to_add);
        setTimeout(function(){
          makeTabContent(document.getElementById('nav-tabs'), __content);
        }, 400)
      }
    }
  }

  $(document).ready(function(){
    $.ajax({
      'url': '/folio/folio_detail_get_data',
      'type': 'POST',
      'data' : {conf_no : "{{ app('request')->get('conf_no', 0) }}", "_token": "{{ csrf_token() }}"},
      success : function(response){
        console.log(response);
        if(Object.keys(response).includes('data') && Object.keys(response.data).includes('0') && response.data[0]){
          makeTabs(response.data);
        }else{
          var beforeElementSibling = document.getElementById('folio-header') || [];
          beforeElementSibling.insertAdjacentHTML('afterend', "<div class='text-center'><p>No Data Available</p></div>");
          clearOverlay();
        }
      },
      error : function(xhrObj, statusText){
        console.log(xhrObj);
        Swal.fire('Something went wrong', xhrObj.statusText, 'error');
        clearOverlay();
      }
    });
  });
</script>
@endsection

