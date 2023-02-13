@extends('layouts.default')

@section('title', 'Master Sidebar Menu - List')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
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
.overlay-order{
  display: none;
}
.overlay-order.in {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 9;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  background: #ffffffe3;
  flex-direction: column;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="#">Home</a></li> 
      <li class="breadcrumb-item"><a href="#">Access Management</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Master Sidebar Menu</span></li></ol>
  </nav>

<div class="row flex-grow" id="main-header">
  <div class="col-12 stretch-card" style="position: relative;">
    <div class="overlay-order">
      <div class="mb-2">
        <img style="width: 50px" src="{{ asset('image/loader.gif') }}" alt="Menu Sort Loader">
      </div>
      <div class="mb-2">
        <h6>Re-ordering Menu...</h6>
      </div>
    </div>
    <div class="overlay">
        <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
    </div>
    <div class="card"> 
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
          @if(session('message') && isset(session('message')['type']))
          <div class="alert alert-fill-{{ session('message')['type'] }} alert-dismissable p-3 mb-3" role="alert">
            @if(session('message')['type'] == 'success')
            <i class="mdi mdi-check"></i>
            @else
            <i class="mdi mdi-alert-circle"></i>
            @endif

            {{ session('message')['msg'] }}
            <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          @if(Session::get('permission_menu')->has("create_".route('mastermenu.list', array(), false)))
          <div class="form-group">
            <a href="{{ route('mastermenu.add') }}" class="btn btn-primary text-white"><i class="mdi mdi-plus"></i>&nbsp;Add New Menu</a>
          </div>
          @endif
          <div id="breakpoint" style="position: relative;">
            <div  class="mb-2 mt-1" style="position: absolute; top: 0">
              <small><i class="fa fa-info-circle text-primary"></i>&nbsp;&nbsp;Drag menu to sort / change order</small>
            </div>
            <table id="dynamic-table" class="table table-bordered" cellspacing="0" width="100%">
               <thead>
                  <tr>
                   <th class="all" style="width: 4%">NO</th>
                   <th class="all" style="width: 10%">MENU NAME</th>
                   <th class="all" style="width: 15%">ROUTE URL</th>
                   <th class="all" style="width: 10%">ICON CLASS</th>
                   <th class="all" style="width: 8%">CHILD MENU</th>
                   <th class="all" style="width: 10%">MENU STATUS</th>
                   <th class="all" style="width: 7%">MENU SORT</th>
                   <th class="all" style="width: 10%">ACTION</th>
                  </tr>
               </thead>
             </table>
          </div>
        </div>
    </div>
  </div>
</div>
{{ @csrf_field() }}
@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<!-- <script type="text/javascript" src="/template/js/jquery-sortable.js"></script> -->
<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
<script type="text/javascript">
  $(document).ready( function () {
      var table = $('#dynamic-table').DataTable(
      {
        "responsive": true,
        "dom": '<"dt-buttons"Bfli>rtp',
        "ajax": {
          "type" : "GET",
          "url" : "/access/management/master-menu/getData",
          dataSrc: function(data){
            if(data.length == 0){
              return [];
            }
            else {
              return data.data;
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            var error = jqXHR.responseJSON.message || "Cannot read data sent from server, please check and retry again";
            Swal.fire({
              title: "Oops..",
              text: error,
              icon: "error",
              showConfirmButton: true
            });
          }
        },
        createdRow: function (row, data, dataIndex) {
            var sort = data.hasOwnProperty('SEQ_ID') ? data.SEQ_ID : 0;
            $(row).attr('data-id', sort);
            $(row).addClass('row-sort');
        },
        "language": {
           "processing": ""
        },
        "paging": false,
        "autoWidth": true,
        'info':false,
        "fixedHeader": true,
        "processing": true,
        "serverSide": true,
        "order": [[0, 'asc']],
        "columns": [
           { "data": "NUM_ORDER"},
           { "data": "MENU_NAME", className: 'text-left'},
           { "data": "PATH", className: 'text-left'},
           { "data": "ICON_CLASS", className: 'text-left'},
           { "data": "HAVING_CHILD_MENU", className: 'text-right details-control'},
           { "data": "STATUS_ACTIVE", className: 'text-left'},
           { "data": "SORT", className: 'text-center'},
           { "data": "ACTION"},
        ],
        "buttons": [
           // 'colvis',
           'copyHtml5',
           'csvHtml5',
           'excelHtml5',
           'print'
        ]
      });

      $("#dynamic-table").sortable({
        items: 'tr.row-sort',
        cursor: 'move',
        delay: 200,
        opacity: 0.6,
        update: function() {
            Swal.fire({
              title: 'Re-Ordering Menu',
              text: "Are you sure want to change order of the menu? Don't worry, you can always change order next time you need it",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
              if (result.isConfirmed) {
                sendOrderToServer("#dynamic-table tbody tr");
              }
              else {
                try{
                  $('#dynamic-table').DataTable().ajax.reload();
                } catch(error){}
              }
            })
        }
      });
   // END READY FUNCTION
   });

   function sendOrderToServer($element) {
      var order = [];
      $($element).each(function(index,element) {
        order.push({
          SEQ_ID: parseInt($(this).attr('data-id')),
          MENU_SORT: parseInt(index+1)
        });
      });

      // console.log(order);
      // return;
      $('.overlay-order').addClass('in');
      $.ajax({
        type: "POST", 
        dataType: "json", 
        url: "{{ route('mastermenu.list.sort') }}",
        data: {
          order:order,
          _token: '{{csrf_token()}}'
        },
        success: function(response) {
          if (response && response.type == "success") {
            location.reload();
          } else {
            try {
              $($element.split(" ")[0]).DataTable().ajax.reload();
            } catch(error){}
            var message = response.hasOwnProperty('msg') ? response.msg : "Something went wrong, please try again";
            var type = response.hasOwnProperty('type') ? response.type : "error";

            setTimeout(function(){
              Swal.fire('Oops..', message, type);
            }, 1000)
          }
        },
        error: function(xhr){
          try {
            $($element.split(" ")[0]).DataTable().ajax.reload();
          } catch(error){}
          setTimeout(function(){
            Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
          }, 1000);
        },
        complete: function(){
          $('.overlay-order').removeClass('in');
        }
      });
   }

   function format ( d, tablename ) {
      // `d` is the original data object for the row
      var row = "<thead><th style='width:4%'>NO</th><th style='width:10%'>MENU NAME</th><th style='width:15%'>ROUTE URL</th><th style='width:10%'>ICON CLASS</th><th style='width:8%'>CHILD MENU</th><th style='width:10%'>MENU STATUS</th><th style='width:7%'>MENU SORT</th><th style='width:10%'>ACTION</th></thead>";
      var child_parent = []
      for (var key in d.PARENT_CHILD_MENU) {
        child_parent.push(d.PARENT_CHILD_MENU[key]);
      }
      // sort array of objects
      child_parent.sort(function(a,b) {
          // note that you might need to change the sort comparison function to meet your needs
          return parseInt(a.SORT) - parseInt(b.SORT);
      })
      // console.log(d);
      var next_child = [];
      $.each(child_parent, function(index, elem){
        var sequence = elem.hasOwnProperty('SEQ_ID') ? elem.SEQ_ID : 'Default';
        var is_active = elem.hasOwnProperty('IS_ACTIVE') ? elem.IS_ACTIVE : false;
        var menu_name = elem.hasOwnProperty('MENU_NAME') ? elem.MENU_NAME : 'Unknown Menu';
        var group_length = elem.hasOwnProperty('PARENT_CHILD_MENU') ? elem.PARENT_CHILD_MENU.length : 'Unknown Length';
        var group_menu = elem.hasOwnProperty('PARENT_CHILD_MENU') ? elem.PARENT_CHILD_MENU : [];
        var route_url = elem.hasOwnProperty('PATH') ? elem.PATH : 'Unknown';
        var icon_class = elem.hasOwnProperty('ICON_CLASS') ? elem.ICON_CLASS : 'Unknown';
        // var route_name = elem.hasOwnProperty('NAMED_ROUTE') ? elem.NAMED_ROUTE : 'Unknown';
        var menu_sort = elem.hasOwnProperty('SORT') ? elem.SORT : 0;
        var inside_group = group_menu.hasOwnProperty('length') ? group_menu.length : 0;

        if(is_active == '1'){
          is_active = 'Active';
          var type_button = 'btn-danger text-white';
          var icon_lock_unlock = 'mdi mdi-lock';
        }
        else{
          is_active = '<a class="text-danger">Not Active</a>';
          var type_button = 'btn-success text-white';
          var icon_lock_unlock = 'mdi mdi-lock-open'; 
        }

        var action = `<a href="/access/management/master-menu/view/${elem.SEQ_ID}" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-view"><i class="mdi mdi-eye"></i></a><a href="/access/management/master-menu/edit/${elem.SEQ_ID}" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a><a href="#" data-url-delete="/access/management/master-menu/delete/${elem.SEQ_ID}" class="btn pl-2 pr-2 ${type_button} ml-1 mr-1 btn-delete"><i class="${icon_lock_unlock}"></i></a>`;
        if(inside_group)
          var having_child_menu = `<a class="text-primary" style="cursor:pointer">${inside_group}</a> &nbsp;<i class="fa fa-caret-down"></i>`;
        else
          var having_child_menu = `-`;

        var element_child_menu = elem.hasOwnProperty('PARENT_CHILD_MENU') ? elem.PARENT_CHILD_MENU : [];

        next_child.push(
          { "SEQ_ID": sequence, "NUM_ORDER": (index + 1), "MENU_NAME": menu_name, "PATH": route_url, "ICON_CLASS": icon_class, "HAVING_CHILD_MENU": having_child_menu, "STATUS_ACTIVE": is_active, "SORT": menu_sort, "ACTION": action, "PARENT_CHILD_MENU": element_child_menu}
        )

      });
      if(tablename == 'child-parent')
        var background = 'bg-black text-white'
      else
        var background = 'bg-light'

      return [next_child, '<div class="p-2 '+background+'"><h4 class="pt-2">Child Menu Of '+d.MENU_NAME+'</h4><table id="'+ tablename +'" class="table table-bordered bg-white" cellpadding="5" cellspacing="0" border="0">'+ row +'</table></div>'];
      // return 
    }

    $(document).on('click', '#dynamic-table td.details-control', function (e) {
        try{
          var tr = $(this).closest('tr');
          var row = $('#dynamic-table').DataTable().row( tr );
          var index = row.index()

          $('#dynamic-table').DataTable().rows().every( function ( rowIdx, tableLoop, rowLoop ) {
              var data = this.data(),
              child = this.row( rowIdx ).child.isShown();
              if(child && rowIdx != index){
                this.row( rowIdx ).child.hide();
              }
          });

          if ( row.child.isShown() ) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          }
          else {
              // Open this row
              var child_data = format(row.data(), 'child-parent');
              row.child( child_data[1] ).show();
              tr.addClass('shown');

              var child_parent = []
              for (var key in child_data[0]) {
                child_parent.push(child_data[0][key]);
              }

              $("#child-parent").DataTable({
                data: child_parent,
                info: false,
                searching: false,
                lengthChange: false,
                paging: false,
                createdRow: function (row, data, dataIndex) {
                    var sort = data.hasOwnProperty('SEQ_ID') ? data.SEQ_ID : 0;
                    $(row).attr('data-id', sort);
                    $(row).addClass('sort-child');
                },
                "columns": [
                   { "data": "NUM_ORDER"},
                   { "data": "MENU_NAME", className: 'text-left'},
                   { "data": "PATH", className: 'text-left'},
                   { "data": "ICON_CLASS", className: 'text-left'},
                   { "data": "HAVING_CHILD_MENU", className: 'text-right details-control-group'},
                   { "data": "STATUS_ACTIVE", className: 'text-left'},
                   { "data": "SORT", className: 'text-center'},
                   { "data": "ACTION"},
                ],
              })

              $("#child-parent").sortable({
                items: 'tr.sort-child',
                cursor: 'move',
                delay: 200,
                opacity: 0.6,
                update: function() {
                    Swal.fire({
                      title: 'Re-Ordering Menu',
                      text: "Are you sure want to change order of the menu? Don't worry, you can always change order next time you need it",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, proceed!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        sendOrderToServer("#child-parent tbody tr");
                      }
                      else {
                        try{
                          $('#child-parent').DataTable().rows().invalidate().draw();
                        } catch(error){}
                      }
                    })
                }
              });
          }
        } catch(error) {
          console.log('Error while trying to expand data', error.message)
        }
    });

    $(document).on('click', '#child-parent td.details-control-group', function (e) {
        try{
          var tr = $(this).closest('tr');
          var row = $('#child-parent').DataTable().row( tr );
          var index = row.index()

          $('#child-parent').DataTable().rows().every( function ( rowIdx, tableLoop, rowLoop ) {
              var data = this.data(),
              child = this.row( rowIdx ).child.isShown();
              if(child && rowIdx != index){
                this.row( rowIdx ).child.hide();
              }
          });

          if ( row.child.isShown() ) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          }
          else {
              // Open this row
              // console.log(row.data());
              var child_data = format(row.data(), 'child-parent-2');
              row.child( child_data[1] ).show();
              tr.addClass('shown');

              var child_parent = []
              for (var key in child_data[0]) {
                child_parent.push(child_data[0][key]);
              }

              $("#child-parent-2").DataTable({
                data: child_parent,
                info: false,
                searching: false,
                lengthChange: false,
                paging: false,
                createdRow: function (row, data, dataIndex) {
                    var sort = data.hasOwnProperty('SEQ_ID') ? data.SEQ_ID : 0;
                    $(row).attr('data-id', sort);
                    $(row).addClass('sort-child-2');
                },
                "columns": [
                   { "data": "NUM_ORDER"},
                   { "data": "MENU_NAME", className: 'text-left'},
                   { "data": "PATH", className: 'text-left'},
                   { "data": "ICON_CLASS", className: 'text-left'},
                   { "data": "HAVING_CHILD_MENU", className: 'text-right'},
                   { "data": "STATUS_ACTIVE", className: 'text-left'},
                   { "data": "SORT", className: 'text-center'},
                   { "data": "ACTION"},
                ],
              })

              $("#child-parent-2").sortable({
                items: 'tr.sort-child-2',
                cursor: 'move',
                delay: 200,
                opacity: 0.6,
                update: function() {
                    Swal.fire({
                      title: 'Re-Ordering Menu',
                      text: "Are you sure want to change order of the menu? Don't worry, you can always change order next time you need it",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, proceed!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        sendOrderToServer("#child-parent-2 tbody tr");
                      }
                      else {
                        try{
                          $('#child-parent-2').DataTable().rows().invalidate().draw();
                        } catch(error){}
                      }
                    })
                }
              });
          }
        } catch(error) {
          console.log('Error while trying to expand data', error.message)
        }
    });

    $(document).on('click', '.btn-delete', function(){
      try {
        var url = $(this).data('url-delete');
      }catch(error){ var url = "{{ url()->current() }}" }
      Swal.fire({
        title: 'Active / Deactivate Menu',
        html: "<span style='line-height:1.5'>Are you sure want to activate / deactivate this menu ? <br>Menu will be disappear for all user access<span>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Sure'
      }).then((result) => {
        if (result.isConfirmed) {
          location.href = url;
        }
      })
    });
</script>
@endsection

