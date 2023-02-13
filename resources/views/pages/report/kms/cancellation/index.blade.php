@extends('layouts.default')

@section('title', 'Ayana Daily Cancellation')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/vendor/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

<style type="text/css">
th {
  background: white;
  position: sticky;
  position: -webkit-sticky;
  top: -0.1px; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgb(0 0 0 / 7%);
  font-size: 12px !important;

}
tr.pivot th
{
    table-layout: fixed;
    top: 34px;
}
.no-sort::after { display: none!important; }
.no-sort::before { display: none!important; }
.no-sort { pointer-events: none!important; cursor: default!important; }
#table-cancellation-summary{
  width: 100% !important;
  max-width: 100% !important;
}
.dt-buttons{
    display: inline-block !important;
    float: none !important;
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="#">Oracle Opera</a></li> 
    <li class="breadcrumb-item"><a href="/folio">Report</a></li> 
    <li aria-current="page" class="breadcrumb-item active"><span>Report Daily Cancellation Ayana Bali</span></li></ol>
</nav>

<div class="row flex-grow" id="main-header">
  <div class="col-sm-12 stretch-card" style="position: relative;">
    <div class="card"> 
      <div id="cancellationKMS">
        <kms-cancellation template-based-on-role="{{ $template }}" date_to_lookup="{{ isset($date_to_lookup) ? date('d-M-Y', strtotime($date_to_lookup)) : date('d-M-Y') }}"></kms-cancellation>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
   $(document).ready(function() {
      $('#datepicker').prop('disabled', false);
      
      $('#table-cancellation').DataTable({        
        scrollX : true,
      });
      var table_summary = $('#table-cancellation-summary').DataTable({
        dom: 'l<"button-wrapper"B>frt',
        buttons: {
          dom: {
            button: {
              tag: 'button',
              className: 'mx-2'
            }
          },
          buttons: [
          {
              extend: 'excelHtml5',
              className : 'btn btn-primary',
              text: 'Export Excel',
              action: function(e, dt, button, config) {
                var data_detail_cancellation = [];
                try {
                  var table = $('#table-cancellation').DataTable()
                  data_detail_cancellation = table.rows().data();
                } catch(error){}

                if (data_detail_cancellation.length > 0) {
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
              },
              customize: function( xlsx ) 
              {
                try 
                {
                  var data_detail_cancellation = [];
                  var titles = [];
                  var header_column = '';
                  var sheet_data = '';
                  var data_row = '';

                  try {
                    var table = $('#table-cancellation').DataTable()
                    var thead = table.table().header();
                    $(thead).find('th').each(function(){
                      titles.push($(this).text());
                    });
                    data_detail_cancellation = table.rows().data();
                  } catch(error){}

                  if(data_detail_cancellation.length == 0){
                    Swal.fire({
                      icon: 'info',
                      title: 'Oops...',
                      text: 'Data is empty, nothing to export',
                    })
                    return false;
                  }
                  else{
                    if(titles.length){
                      var kolom = '';
                      data_row += '<row r="1">'
                      for (let i = 0; i < titles.length; i++) {
                            var now_alpha = (i+10).toString(36).toUpperCase() + `1`;
                            kolom += '<col min="1" max="1" width="10" customWidth="1"/>';

                            data_row += '<c t="inlineStr" r="'+ now_alpha +'" s="2">'+
                              '<is>'+
                                '<t>'+titles[i]+'</t>'+
                              '</is>'+
                           '</c>';
                      }
                            // Tambah kolom dan row awal untuk judul 
                      data_row += '</row>';
                      header_column += '<cols>'+
                        kolom +
                        '</cols>';
                    }

                    if(data_detail_cancellation.length){
                      var start = 2;
                      for (let i = 0; i < data_detail_cancellation.length; i++) {
                        var kolom_detail = '';
                        for (let j = 0; j < titles.length; j++) {
                          var now_alpha = (j+10).toString(36).toUpperCase() + `${start}`;
                          kolom_detail += '<c t="inlineStr" r="'+ now_alpha +'" s="0">'+
                              '<is>'+
                                  '<t>'+data_detail_cancellation[i][j]+'</t>'+
                                '</is>'+
                            '</c>';
                        }

                        data_row += '<row r="'+start+'">'+
                                  kolom_detail +
                                '</row>';
                                start++;
                      }
                    }

                    sheet_data += '<sheetData>'+
                      data_row +
                    '</sheetData>';

                    //Add sheet2 to [Content_Types].xml => <Types>
                    //============================================
                    $('sheets sheet', xlsx.xl['workbook.xml'] ).attr( 'name', 'Room Nite Summary' );
                    var source = xlsx['[Content_Types].xml'].getElementsByTagName('Override')[1];
                    var clone = source.cloneNode(true);
                    clone.setAttribute('PartName','/xl/worksheets/sheet2.xml');
                    xlsx['[Content_Types].xml'].getElementsByTagName('Types')[0].appendChild(clone);
                    
                    //Add sheet relationship to xl/_rels/workbook.xml.rels => Relationships
                    //=====================================================================
                    var source = xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationship')[0];
                    var clone = source.cloneNode(true);
                    clone.setAttribute('Id','rId3');
                    clone.setAttribute('Target','worksheets/sheet2.xml');
                    xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationships')[0].appendChild(clone);
                    
                    //Add second sheet to xl/workbook.xml => <workbook><sheets>
                    //=========================================================
                    var source = xlsx.xl['workbook.xml'].getElementsByTagName('sheet')[0];
                    var clone = source.cloneNode(true);
                    clone.setAttribute('name','Detail Cancellation');
                    clone.setAttribute('sheetId','2');
                    clone.setAttribute('r:id','rId3');
                    xlsx.xl['workbook.xml'].getElementsByTagName('sheets')[0].appendChild(clone);
                    
                    //Add sheet2.xml to xl/worksheets
                    //===============================
                    // var today = new Date();
                    // var cMonth = today.getMonth()+1;
                    // var cDay = today.getDate();
                    // var dateNow = ((cMonth<10)?'0'+cMonth:cMonth)+'-'+((cDay<10)?'0'+cDay:cDay)+'-'+today.getFullYear();
                    var newSheet = '{!! $xml_version !!}'+
                      '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac" mc:Ignorable="x14ac">'+
                      header_column + sheet_data +
                    '</worksheet>';
                    xlsx.xl.worksheets['sheet2.xml'] = $.parseXML(newSheet);
                  }
                } catch(error){
                    Swal.fire({
                      icon: 'info',
                      title: 'Oops...',
                      text: "Something went wrong when trying to export, try again in a moment",
                  })
                }
              }
            }
            // End button
          ]
        }
      });
      
      table_summary.buttons( 0, null ).container().prependTo(
        $('.date-range-wrapper')
      );
    

    });
  </script>
@endsection

