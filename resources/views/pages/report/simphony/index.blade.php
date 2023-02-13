@extends('layouts.default')

@section('title', 'POS Simphony Report')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/css/searchBuilder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/css/jquery-ui-datepicker.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

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
  border: 1px solid #ddd;
  font-size:12px !important;
}

#content-table tr td{
  text-align: left;
  border: 1px solid #ddd;
  font-size:11px !important;
}
.dtsb-delete:first-of-type {
  display: none !important;
}
div.dt-buttons {
  float: none !important;
}
.pd-new {
  margin-top: .8em;
}
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li> 
      <li class="breadcrumb-item"><a href="#">POS</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>POS (Simphony)</span></li></ol>
  </nav>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="card">
                <div id="reportSimphony">
                  <report-simphony template-based-on-role="{{ $template }}" date_to_lookup="{{ isset($date_to_lookup) ? date('d-M-Y', strtotime($date_to_lookup)) : date('d-M-Y') }}"></report-simphony>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/moment.min.js"></script>
<script type="text/javascript" src="/js/vendor/daterangepicker.js"></script>
<!-- <script src="/template/js/jquery-ui-datepicker.js"></script> -->
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="/template/js/dataTables.searchBuilder.min.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
  function htmlDecode(input){
      var e = document.createElement('textarea');
      e.innerHTML = input;
      // handle case of empty input
      return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
  }

  function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  $(function(){
    var $table = $('#content-table').DataTable({
      // dom: 'Qlfrtip',
      // dom: 'rtip',
      dom: 'Brtip',
      buttons: {
        dom: {
          button: {
            tag: 'button',
            className: 'pd-new'
          }
        },
        buttons: 
        [{
          extend: 'excelHtml5',
          className : 'btn btn-primary',
          text: 'Export Available Data',
          messageTop: function () {
            try {
              var date = $("#datepicker").datepicker("getDate");
              date = $.datepicker.formatDate("dd-mm-yy", date);
              return `Report Date : ${date}`;
            } catch(error){}
          },
          exportOptions: {
            columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]
          },
          action: function(e, dt, button, config) {
            var data_revenue = [];
            try {
              var table = $('#content-table').DataTable()
              data_revenue = table.rows().data();
            } catch(error){}

            if(data_revenue.length > 0) {
              $.fn.dataTable.ext.buttons.excelHtml5.action.call(this,e, dt, button, config);
              return false;
            } else {
              Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: 'Data is empty, nothing to export',
              })
              return false;
            }
          }
        }]
      },
      paging : false,
      columnDefs:[{
        searchBuilderTitle: 'F&B OUTLET',
        targets: 1
      }],
      searchBuilder: {
        columns: [1,2,3,4,5,6],
        conditions:{
          string:{
              '=': {
                inputValue: function(el, that) {
                  var $_unescape = htmlDecode($(el)[0].val()) || '';
                  return [$_unescape];
                }
              }
          }
        },
        preDefined: {
          criteria:[{}]
        }
      },
      initComplete: function() {
          var text_order = 0;
          var table_obj = this.api();
          this.api().columns([3,4,1]).every( function (i) {
              var column = this;
              var text = ['Resort', 'Sub Resort', 'Outlet'];
              var col_length = 'col-5';
              if(i==1){
                var select = $(`
                  <div class="outlet-wrapper col-12 mt-3">
                    <div class="content-filter-outlet row">
                      <div class="filter-outlet col-7">
                        <div>
                          <label>Filter By ${text[text_order]}</label>
                          <select class="form-control select2 select-multiple filter-select-${i} mr-3">
                          </select>
                        </div>
                      </div>
                      <div class="export-wrapper col-5">
                      </div>
                    </div>
                  </div>`)
                  .appendTo( $('.filter-align') )
                  .on('change', function (e) {
                      if ($(e.target).select2('data')) {
                        var data = $.map( $(e.target).select2('data'), function( value, key ) {
                          return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
                                   });
                        
                        //if no data selected use ""
                        if (data.length === 0) {
                          data = [""];
                        }
                        
                        //join array into string with regex or (|)
                        var val = data.join('|');

                        //search for the option(s) selected
                        column
                              .search( val ? val : '', true, false )
                              .draw();
                        }
                  }).on('select2:unselecting', function(e) {
                      $(e.target).data('unselecting', true);
                  }).on('select2:opening', function(e) {
                      if ($(e.target).data('unselecting')) {
                          $(e.target).removeData('unselecting');
                          e.preventDefault();
                      }
                  });
              } 
              else 
              {
                if(i==3){
                  var select = $(`<div class="content-filter col-2 pt-4"><input type="checkbox" name="fb_yn" class="check-menu" id="fb-yn-checkbox" value="1"><label>&nbsp;&nbsp;Show only F&B Outlet</label></div>
                    <div class="content-filter ${col_length}"><label>Filter By ${text[text_order]}</label><div><select class="form-control select2 select-standard filter-select-${i} mr-3"><option value="">Pilih Data Disini</option></select></div></div>`)
                }
                else {
                  var select = $(`<div class="content-filter ${col_length}"><label>Filter By ${text[text_order]}</label><div><select class="form-control select2 select-standard filter-select-${i} mr-3"><option value="">Pilih Data Disini</option></select></div></div>`)
                }

                select.appendTo( $('.filter-align') )
                .on('select2:select', function (e) {
                    var value = e.params.data.id;
                    // var val = $.fn.dataTable.util.escapeRegex(
                    //     $(this).val()
                    // );
                    var val = $.fn.dataTable.util.escapeRegex(
                        value
                    );
                    // var column_search = [];
                    // try {
                    //   table_obj.columns(3).nodes().eq( 0 ).each(function (cell, i) {
                    //     if(cell.getAttribute('data-resort') == val){
                    //       var subresort = table_obj.cell(i, 3).data()
                    //       column_search.push(subresort);
                    //     }
                    //   });
                    // } catch(error){}
                    // column_search = column_search.filter((v, i, a) => a.indexOf(v) === i);
                    // console.log(column_search);

                    column
                        .search( '' )
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();

                    var target_array = [... e.target.classList];
                    if(target_array.includes('filter-select-3')){
                      var unique_search_subresort = [];
                      table_obj.column(4, { search:'applied' } ).data().each(function(value, index) {
                          if(unique_search_subresort.indexOf(value) === -1) {
                            unique_search_subresort.push(value);
                          }
                      });
                      $('.filter-select-4').select2('destroy').html('<option value="" selected disabled></option>')
                      $('.filter-select-4').select2({
                        placeholder: 'Choose data',
                        allowClear: true
                      });

                      var newOption_subresort = [];
                      $.each(unique_search_subresort, function(index, data){
                        newOption_subresort[index] = new Option(`${data}`, data, false, false);
                      });
                      $('.filter-select-4').append(newOption_subresort).trigger('change');
                      $('.filter-select-4').prop('disabled', false);
                    }
                    else if(target_array.includes('filter-select-4')){
                      var unique_search_outlet = [];
                      table_obj.column(1, { search:'applied' } ).data().each(function(value, index) {
                          if(unique_search_outlet.indexOf(value) === -1) {
                            unique_search_outlet.push(value);
                          }
                      });
                      $('.filter-select-1').select2('destroy').html('')
                      $('.filter-select-1').select2({
                        multiple: true,
                        width: '100%',
                        placeholder: "Choose data",
                        allowClear: true
                      });

                      var newOption_outlet = [];
                      $.each(unique_search_outlet, function(index, data){
                        newOption_outlet[index] = new Option(`${data}`, data, false, false);
                      });
                      $('.filter-select-1').append(newOption_outlet).trigger('change');
                      $('.filter-select-1').prop('disabled', false);
                    }

                }).on("select2:unselecting", function(e) {
                    var target_array = [... e.target.classList];
                    if(target_array.includes('filter-select-3')){
                      $('.filter-select-4').select2('destroy').html('<option value="" selected disabled></option>')
                      $('.filter-select-4').select2({
                        placeholder: 'Choose data',
                        allowClear: true
                      });
                      $('.filter-select-4').prop('disabled', true);

                      $('.filter-select-1').select2('destroy').html('')
                      $('.filter-select-1').select2({
                        multiple: true,
                        placeholder: "Choose data",
                        allowClear: true
                      });
                      $('.filter-select-1').prop('disabled', true);

                      var val = $.fn.dataTable.util.escapeRegex("");
                          table_obj
                          .columns([3])
                          .search( '' )
                          // .search( val ? '^'+val+'$' : '', true, false )
                          .draw();
                      $(this).data('state', 'unselected');

                      // var unique_search_subresort = [];
                      // var newOption_subresort = [];
                      // $.each(unique_search_subresort, function(index, data){
                      //   newOption_subresort[index] = new Option(`${data}`, data, false, false);
                      // });
                      // $('.filter-select-4').append(newOption_subresort).trigger('change');
                      // $('.filter-select-4').prop('disabled', true);
                    } 
                    else if(target_array.includes('filter-select-4')){
                      var val = $.fn.dataTable.util.escapeRegex("");
                          table_obj
                          .columns([1,4])
                          .search( '' )
                          // .search( val ? '^'+val+'$' : '', true, false )
                          .draw();
                      $(this).data('state', 'unselected');

                      var unique_search_outlet = [];
                      $('.filter-select-1').select2('destroy').html('')
                      $('.filter-select-1').select2({
                        multiple: true,
                        placeholder: "Choose data",
                        allowClear: true
                      });

                      var newOption_outlet = [];
                      $.each(unique_search_outlet, function(index, data){
                        newOption_outlet[index] = new Option(`${data}`, data, false, false);
                      });
                      $('.filter-select-1').append(newOption_outlet).trigger('change');
                      $('.filter-select-1').prop('disabled', true);
                    }
                    // else {
                    //   var val = $.fn.dataTable.util.escapeRegex("");
                    //   column
                    //       .search( '' )
                    //       .search( val ? '^'+val+'$' : '', true, false )
                    //       .draw();
                    //   $(this).data('state', 'unselected');
                    // }
                }).on("select2:open", function(e) {
                    try {
                      if ($(this).data('state') === 'unselected') {
                          $(this).removeData('state'); 

                          var self = $(this).find('.select2')[0];
                          setTimeout(function() {
                              $(self).select2('close');
                          }, 0);
                      }
                    } catch(error){}   
                });
              }

              if(i == 3){
                column.data().unique().sort().each( function ( d, j ) {
                    $(`.filter-select-${i}`).append( '<option value="'+d+'">'+d+'</option>' )
                });
              }
              else {
                $(`.filter-select-${i}`).prop('disabled', true);
              }
              text_order++;
          });

          // Initialize newly created select2
          $('.select-standard').select2({
            placeholder: 'Choose data',
            allowClear: true
          });

          $('.select-multiple').select2({
            multiple: true,
            width: '100%',
            placeholder: "Choose data",
            allowClear: true
          });
      },
      footerCallback: function ( row, data, start, end, display ) {
          var api = this.api(), data;

          // Remove the formatting to get integer data for summation
          var intVal = function ( i ) {
              // return typeof i === 'string' ?
              //     i.replace(/[\$,]/g, '')*1 :
              //     typeof i === 'number' ?
              //         i : 0;
              var data_column = typeof i === 'string' ?
              i.replace(/(<([^>]+)>)/ig, '').replaceAll(',','')*1 :
              typeof i === 'number' ? i : 0;
              return data_column;
          };

          /* Guest Today */
          // Total over all pages
          guestTodayAll = api
              .column( 5, { search: 'applied' } )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Total over this page
          guestTodayPage = api
              .column( 5, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );
          /* END Guest Today */

          /* Guest MTD */
          // Total over all pages
          guestMtdAll = api
              .column( 7, { search: 'applied' } )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Total over this page
          guestMtdPage = api
              .column( 7, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );
          /* END Guest MTD */
          guestYtdAll = api
              .column( 9, { search: 'applied' } )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Total over this page
          guestYtdPage = api
              .column( 9, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );


          /* REVENUE TODAY */
          // Total over all pages
          revenueTodayAll = api
              .column( 6, { search: 'applied' } )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // console.log("REVENUE TODAY", revenueTodayAll);

          // Total over this page
          revenueTodayPage = api
              .column( 6, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );
          /* END REVENUE TODAY */

          /* REVENUE TODAY */
          // Total over all pages
          revenueMtdAll = api
              .column( 8, { search: 'applied' } )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Total over this page
          revenueMtdPage = api
              .column( 8, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );
          /* END REVENUE TODAY */

          revenueYtdAll = api
              .column( 10, { search: 'applied' } )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Total over this page
          revenueMtdPage = api
              .column( 10, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // $('.guest-today-page').html(numberWithCommas(guestTodayPage));
          // $('.guest-mtd-page').html(numberWithCommas(guestMtdPage));
          $('.guest-today-all').html(numberWithCommas(guestTodayAll));
          $('.guest-mtd-all').html(numberWithCommas(guestMtdAll));
          $('.guest-ytd-all').html(numberWithCommas(guestYtdAll));

          // $('.revenue-today-page').html(numberWithCommas(revenueTodayPage));
          // $('.revenue-mtd-page').html(numberWithCommas(revenueMtdPage));
          $('.revenue-today-all').html(numberWithCommas(revenueTodayAll));
          $('.revenue-mtd-all').html(numberWithCommas(revenueMtdAll));
          $('.revenue-ytd-all').html(numberWithCommas(revenueYtdAll));

      }
    });
      
    try {
      $('<label>Export Excel</label>').prependTo('.export-wrapper');
      $table.buttons( 0, null ).container().appendTo($('.export-wrapper'));
    } catch(error){}

    $(document).on('change', '#fb-yn-checkbox', function(){
      if(this.checked){
        $('#content-table').DataTable().columns(2).search('YES').draw();
      }
      else{
        $('#content-table').DataTable().columns().search('').draw();
      }

      // cek jika filter outlet tidak terdisable, maka ganti data filter
      setTimeout(function(){
        var unique_search_resort = [];
        $('#content-table').DataTable().column(3, { search:'applied' } ).data().each(function(value, index) {
            if(unique_search_resort.indexOf(value) === -1) {
              unique_search_resort.push(value);
            }
        });
        $('.filter-select-3').select2('destroy').html('<option value="" selected disabled></option>')
        $('.filter-select-3').select2({
          placeholder: 'Choose data',
          allowClear: true
        });

        var newOption_resort = [];
        $.each(unique_search_resort, function(index, data){
          newOption_resort[index] = new Option(`${data}`, data, false, false);
        });
        $('.filter-select-3').append(newOption_resort).trigger('change');
        
        // if($('.filter-select-1').prop('disabled') === false){
          var unique_search_outlet = [];
          $('#content-table').DataTable().column(1, { search:'applied' } ).data().each(function(value, index) {
              if(unique_search_outlet.indexOf(value) === -1) {
                unique_search_outlet.push(value);
              }
          });
          $('.filter-select-1').select2('destroy').html('')
          $('.filter-select-1').select2({
            multiple: true,
            width: '100%',
            placeholder: "Choose data",
            allowClear: true
          });
          $('.filter-select-1').prop('disabled', true);

          // var newOption_outlet = [];
          // $.each(unique_search_outlet, function(index, data){
          //   newOption_outlet[index] = new Option(`${data}`, data, false, false);
          // });
          // $('.filter-select-1').append(newOption_outlet).trigger('change');
          // $('.filter-select-1').prop('disabled', false);
        // }
        // else if($('.filter-select-4').prop('disabled') === false){
          var unique_search_subresort = [];
          $('#content-table').DataTable().column(4, { search:'applied' } ).data().each(function(value, index) {
              if(unique_search_subresort.indexOf(value) === -1) {
                unique_search_subresort.push(value);
              }
          });
          $('.filter-select-4').select2('destroy').html('<option value="" selected disabled></option>')
          $('.filter-select-4').select2({
            placeholder: 'Choose data',
            allowClear: true
          });
          $('.filter-select-4').prop('disabled', true);

          // var newOption_subresort = [];
          // $.each(unique_search_subresort, function(index, data){
          //   newOption_subresort[index] = new Option(`${data}`, data, false, false);
          // });
          // $('.filter-select-4').append(newOption_subresort).trigger('change');
          // $('.filter-select-4').prop('disabled', false);
        // }

      }, 100)
    });

  })
</script>
@endsection