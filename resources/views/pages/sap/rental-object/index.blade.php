@extends('layouts.default')

@section('title', 'SAP RE-FX Rental Object')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/css/searchBuilder.dataTables.min.css">
@endsection
@section('styles')
<style type="text/css">
  .table-responsive{
    position: relative;
  }
  .dataTables_wrapper div > small {
    position: absolute;
    bottom: 25px;
  }
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
    background: #fff;
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
    text-align: center;
    border: 1px solid #ddd;
    font-size:11px !important;
  }

  .select2-selection.select2-selection--single{
    padding: 1.01em;
    font-size: 12px;
  }

  td.parent-child{
    padding: 0px !important;
    border: none !important;
  }

  .child{
    padding: .3em;
  }

  .child > span{
    display: none !important;
  }
  .simple-tree-table-icon{
    margin-right: 5px !important;
    color: #000 !important;
  }
  .table-scroller {
    overflow: auto;
    margin-bottom: 1em;
  }
  .top-wrapper:after{
    content: "";
    clear: both;
    display: block;
  }
  .dataTables_info{
    float: left;
    padding: 0 !important;
  }
  .data-t-able.filterable {
    min-width: 2500px !important;
  }

  .dtsb-delete:first-of-type {
    display: none !important;
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="#">SAP</a></li>
      <li class="breadcrumb-item"><a href="#">RE-FX</a></li>
      <li aria-current="page" class="breadcrumb-item active"><span>Rental Object</span></li></ol>
  </nav>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="card">
              <div id="reactiveListener">
                <rental-object populate_company="{{ json_encode(isset($data['COMPANY']) && $data['COMPANY'] ? $data['COMPANY'] : []) }}" date_start="{{ date('d/m/Y',strtotime($data['date_start'])) }}" plant="{{ isset($data['FILTER']['P_COMPANY']) && $data['FILTER']['P_COMPANY'] && isset($data['COMPANY']) ? isset($data['COMPANY'][$data['FILTER']['P_COMPANY']]) ? $data['COMPANY'][$data['FILTER']['P_COMPANY']] : '' : '' }}" date_end="{{ date('d/m/Y',strtotime($data['date_end'])) }}" company_code_sent="{{ isset($data['FILTER']['P_COMPANY']) && $data['FILTER']['P_COMPANY'] ? $data['FILTER']['P_COMPANY'] : '' }}" template-based-on-role="{{ $template }}"></rental-object>
              </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="/template/js/report/jquery.floatingscroll.js"></script>
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script src="/template/js/jquery-ui-datepicker.js"></script>
<script src="/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="/template/js/dataTables.searchBuilder.min.js"></script>
<script src="/template/js/jquery-simple-tree-table.js"></script>
<script src="/template/js/ResizeSensor.js"></script>
<script src="/template/js/ElementQueries.js"></script>

<script>
 $(window).on('load', function(){
    $(".table-scroller").floatingScroll();
 })
 $(document).ready(function() {
    $('#datepicker').prop('disabled', false);
    $('.company-select').prop('disabled', false);
    // $(".table-container-h").floatingScroll();
    // $(".scrollable").floatingScroll();
    $('.table-tree').simpleTreeTable({
      opened:'all',
    });

    var element = document.getElementsByClassName('simple-tree-table-root');
      new ResizeSensor(element, function() {
        $(".table-tree").floatingScroll("update");
    });

    function htmlDecode(input){
      var e = document.createElement('textarea');
      e.innerHTML = input;
      // handle case of empty input
      return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $('.data-t-able.no-filter').DataTable({
      "dom": '<"top-wrapper"><"table-scroller"rt><"bottom"ip><"clear">',
      "autoWidth" : false,
      "pageLength": 50
    });

    $('.data-t-able.filterable').DataTable({
      // "dom": 'Q<"top-wrapper"lf><"table-scroller"rt><"bottom"ip><"clear">',
      "dom": '<"top-wrapper"><"table-scroller"rt><"bottom"ip><"clear">',
      "autoWidth" : false,
      "pageLength": 50,
      searchBuilder: {
          columns: [3,4,6,8,10],
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
            criteria:[{}],
          }  
        },
      initComplete: function (settings, json) {
            var table = $(this[0]).parents('tr.parent-list').find('.filter-align');
            var table_wrapper = $(this[0]).parents('tr.parent-list').find('.filter-wrapper');

            var text_order = 0;
            this.api().columns([2]).every( function (i) {
                var column = this;
                var text = ['Rental Object Name'];
                var col_length = 'col-4';
                if(i==8 || i==6)
                  col_length = 'col-3'
                var select = $(`<div class="content-filter ${col_length}"><label><strong>Filter By ${text[text_order]}</strong></label><div><select class="form-control select2 filter-select-${i} mr-3"><option value="">Pilih Data Disini</option></select></div></div>`)
                    .appendTo( table )
                    .on('select2:select', function (e) {
                        var value = e.params.data.id;
                        // var val = $.fn.dataTable.util.escapeRegex(
                        //     $(this).val()
                        // );
                        var val = $.fn.dataTable.util.escapeRegex(
                            value
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    }).on("select2:unselecting", function(e) {
                        var val = $.fn.dataTable.util.escapeRegex("");
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                        $(this).data('state', 'unselected');
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
                column.data().unique().sort().each( function ( d, j ) {
                    $(table).find(`.filter-select-${i}`).append( '<option value="'+d+'">'+d+'</option>' )
                });
                text_order++;
            });
            var show_no_contract_only = $(`<div class="form-check" style="position: absolute;right: 15px;top: 20px;">
              <input type="checkbox" class="form-check-input show-empty-contract" id="exampleCheck1">
              <h6 class="" for="exampleCheck1" style="margin-top: 3px;">Show no contract only</h6>
            </div>`).appendTo(table);

            $('.select2').select2({
              placeholder: 'Choose data',
              allowClear: true
            });
        }
    });

    $(document).on('click', '.dtsb-clearAll', function(e){
      var table = $(e.target).parents('.scrollable').find('.table')[0];
      // stored = table.DataTable().searchBuilder.getDetails();         
      // console.log('click remove');
      $(table).DataTable().destroy();
      $(table).DataTable({
          // "dom": 'Q<"top-wrapper"lf><"table-scroller"rt><"bottom"ip><"clear">',
          "dom": '<"top-wrapper"lf><"table-scroller"rt><"bottom"ip><"clear">',
          "autoWidth" : false,
          "pageLength": 50,
          searchBuilder: {
              columns: [3,4,6,8,10],
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
                criteria:[{}],
              }  
            }
        });
    })

    $(document).on('click', '.detail-link', function(e){
      $_data_rental = $(this).data('rental-unit') || "";
      $_data_company = $(this).data('company-code') || "";
      $_data_plant = $(this).data('plant') || "";

      $('.contract-number').text($_data_rental);
      console.log($_data_rental,$_data_company, $_data_plant)
      if(!$_data_rental || !$_data_company || !$_data_plant){
        setTimeout(function(){
          $('#modal-detail-contract').modal('hide');
        },1000)
        setTimeout(function(){
          Swal.fire({
              title: "Oops..",
              text: 'Cannot read any Rental Unit, Company and Plant',
              icon: "error",
              showConfirmButton: true
            });
        },1500)
        return
      }

      $.ajax({
        url : `/sap/rental_object_list/contract_detail?rental_unit=${$_data_rental}&company=${$_data_company}&plant=${$_data_plant}`,
        type : 'GET',
        success : function(resp){
          setTimeout(function(){
            $('#modal-detail-contract .modal-body').html(resp);
            setTimeout(function(){
              $('.table-condition-contract').DataTable({
                "dom": '<"top-wrapper"><"table-scroller"rt><"bottom"ip><"clear">',
                "autoWidth" : false,
                "pageLength": 50
              });
              $('.modal-tree-table').simpleTreeTable({
                opened:'all',
              });
            },200)
          });
        },
        error : function(xhr){
          if(Object.keys(xhr).includes('status') && xhr.status == 419){
            $('#modal-detail-contract .modal-body').html('<div class="text-center"><h5>Page has expired, please refresh and re-login your account</h5></div>');
          } else{
            $('#modal-detail-contract .modal-body').html('<div class="text-center"><h5>Failed to get data, please check your connection and try again</h5></div>');
          }
        }
      });

      $('#modal-detail-contract').on('hidden.bs.modal', function(){
        $(this).find('.modal-body').html('<div class="overlay in align-items-center"><img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader"></div>');
      });
    });


    $(document).on('change', '.show-empty-contract', function(){
      if(this.checked){
        $(this).parents('tr.parent-list').find('.table').DataTable().column(3).search( '^$', true, false ).draw();
      } else {
        $(this).parents('tr.parent-list').find('.table').DataTable().column(3).search( '', true, false ).draw();
      }
    });
});

$('.select2').select2();
</script>
@endsection

