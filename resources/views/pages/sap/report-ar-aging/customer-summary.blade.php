@extends('layouts.default')

@section('title', 'SAP Account Receivable Aging Report')
@section('custom_source_css')
<link rel="stylesheet" type="text/css" href="/css/vendor/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/js/vendor/datatables/fixedColumns.dataTables.min.css">
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

	.toolbar{
			display:flex;
			float:left;
	}

	.btn-success:focus, .btn-success.focus {
		background-color: #0ddbb9 !important;
	}
	.button-wrapper {
		/*position: absolute !important;
		top: 25px !important;
		right: 18rem !important;*/
		position: absolute !important;
    	top: 0px !important;
    	right: 16.5rem !important;
	}
	.table, .dataTables_wrapper {
		position: relative;
	}

</style>
@endsection

@section('content')
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
			<li class="breadcrumb-item"><a href="/">Home</a></li>
			<li class="breadcrumb-item"><a href="#">SAP S/4HANA</a></li>
			<li aria-current="page" class="breadcrumb-item active"><span>Account Receivable Aging Report</span></li></ol>
	</nav>

<div class="row flex-grow" id="main-header">
	<div class="col-sm-12 stretch-card" style="position: relative;">
			<div class="overlay">
				<img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
			</div>
			<div class="card">
				<div class="card-body pb-0 bg-white" id="header">
						<div class="px-0 mb-5 col-md-12 float-left">
							<h1 class="text-center">Account Receivable Aging Customer Summary</h1>
							<h3 class="text-center mt-2">Input your criteria below and review data</h3>
						</div>
						<div class="col-md-12 float-left">
							<form method="GET" action="{{ route('sap.report_ar_aging_vendor') }}" id='formRequest'>
								<div class="row justify-content-center">
									<div class="form-group col-md-4">
										<label for="">Plant</label>
										<select name="plant" id="cmbCompany" class="select2 form-control">
										@foreach ($data['list_plant'] as $list_plant)
											<option value="{{$list_plant->COMPANY_CODE}}-{{$list_plant->SAP_PLANT_ID}}"  {{ (isset($data_plant) && !empty($data_plant) && trim($data_plant->SAP_PLANT_ID) == trim($list_plant->SAP_PLANT_ID)) ? 'selected' : ''}}>{{$list_plant->SAP_PLANT_ID}} - {{$list_plant->SAP_PLANT_NAME}}</option>
										@endforeach
										</select>
									</div>
									<div class="form-group col-md-2">
										<label for="">Pick date</label>
										<input disabled type="text" class="form-control datepicker" name="date" id="datepicker" value="{{ isset($data['date_lookup']) ? date('Y-m-d', strtotime($data['date_lookup'])) : date('Y-m-d') }}">
									</div>
									<div class="form-group col-md-2">
										<label for="">Apply</label>
										<button type="submit" class="btn btn-success text-white form-control" id="btnFilter">SEARCH</button>
									</div>
								</div>
							</form>
						</div>
				</div>
				<hr class="my-0">
			</div>
	</div>
</div>

<div class="row flex-grow">
	<div class="col-md-12 stretch-card">
		<div class="card">
			<div class="card-body">
				@if(isset($filtered) && $filtered)
					<div class="mb-4 float-left" style="position: relative;">
							<h2>{{ isset($data_plant->SAP_PLANT_ID) ? $data_plant->SAP_PLANT_ID : 'Unknown Plant ID' }} - 
								{{ isset($data_plant->SAP_PLANT_NAME) ? $data_plant->SAP_PLANT_NAME : 'Unknown Plant Name' }}
							</h2>
							<h4 class="">Aging Summary For All Types (IDR)</h4>
							{{--<div style="position: absolute;bottom: 0;right: 0;">
									<button class="btn btn-primary btn-export-excel">Export Excel</button>
							</div>--}}
					</div>
					<table class="table table-border table-striped" id="content-table">
							<thead>
								<tr>
									<th class="bg-secondary text-white text-center" style="width: 5%">NO.</th>
									<th class="bg-secondary text-white text-center">ACCOUNT NAME</th>
									<th class="bg-secondary text-white text-center">ACCOUNT NO.</th>
									<th class="bg-secondary text-white text-center">0-30 DAYS</th>
									<th class="bg-secondary text-white text-center">31-60 DAYS</th>
									<th class="bg-secondary text-white text-center">61-90 DAYS</th>
									<th class="bg-secondary text-white text-center">>91 DAYS</th>
									<th class="bg-secondary text-white text-center">TOTAL</th>
								</tr>
							</thead>
							{{--<tbody>
								@php
									$grand_total=0;
									$grand_total_0_30 = $grand_total_31_60 = $grand_total_61_90 = $grand_total_91 = 0;
								@endphp
								@foreach ($data_aging as $customer_id => $ar)
								@php
										
								@endphp
									<tr>
										<td class="text-center">{{ $loop->iteration }}</td>
										<td class="text-left">
												{{ isset($ar['DETAILS']['ACCOUNT_NAME']) && !empty($ar['DETAILS']['ACCOUNT_NAME']) ? strtoupper($ar['DETAILS']['ACCOUNT_NAME']) : '-' }}
										</td>
										<td>{{ !empty($customer_id) ? $customer_id : '-' }}</td>
										<td class="text-right">{{ isset($ar['AR_AGING']['day_0_30']) ? number_format($ar['AR_AGING']['day_0_30']) : '-' }}</td>
										<td class="text-right">{{ isset($ar['AR_AGING']['day_31_60']) ? number_format($ar['AR_AGING']['day_31_60']) : '-' }}</td>
										<td class="text-right">{{ isset($ar['AR_AGING']['day_61_90']) ? number_format($ar['AR_AGING']['day_61_90']) : '-' }}</td>
										<td class="text-right">{{ isset($ar['AR_AGING']['day_GT_90']) ? number_format($ar['AR_AGING']['day_GT_90']) : '-' }}</td>
										<td class="text-right">{{ isset($ar['AR_AGING']['total_amount']) ? number_format($ar['AR_AGING']['total_amount']) : '-' }}</td>
									</tr>
								@endforeach
							</tbody>--}}
							<tfoot>
								<tr class="footer-callback" hidden>
									{{--<th class="bg-secondary text-white text-center" colspan="3">TOTAL ALL AMOUNT</th>
									<th class="bg-secondary text-white text-right" >{{ isset($data['TOTAL_ALL_AGING']['day_0_30']) ? number_format($data['TOTAL_ALL_AGING']['day_0_30']) : 0 }}</th>
									<th class="bg-secondary text-white text-right">{{ isset($data['TOTAL_ALL_AGING']['day_31_60']) ? number_format($data['TOTAL_ALL_AGING']['day_31_60']) : 0 }}</th>
									<th class="bg-secondary text-white text-right">{{ isset($data['TOTAL_ALL_AGING']['day_61_90']) ? number_format($data['TOTAL_ALL_AGING']['day_61_90']) : 0 }}</th>
									<th class="bg-secondary text-white text-right">{{ isset($data['TOTAL_ALL_AGING']['day_GT_90']) ? number_format($data['TOTAL_ALL_AGING']['day_GT_90']) : 0 }}</th>
									<th class="bg-secondary text-white text-right">{{ isset($data['TOTAL_ALL_AGING']['total_amount']) ? number_format($data['TOTAL_ALL_AGING']['total_amount']) : 0 }}</th>--}}

									<th class="bg-secondary text-white text-center" colspan="3">TOTAL ALL AMOUNT</th>
									<th class="bg-secondary text-white text-right total-footer">0</th>
									<th class="bg-secondary text-white text-right total-footer">0</th>
									<th class="bg-secondary text-white text-right total-footer">0</th>
									<th class="bg-secondary text-white text-right total-footer">0</th>
									<th class="bg-secondary text-white text-right total-footer">0</th>
								</tr>
							</tfoot>
					</table>
				{{-- @else
					@if (isset($filtered) && $filtered)
							<h2 class="text-center"> Sorry, no data available </h2>
					@endif --}}
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="/template/js/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="/js/vendor/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/vendor/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/fixedColumns.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/vendor/datatables/dataTables.fixedColumns.min.js"></script>
<!-- <script src="/template/js/jquery.table2excel.min.js"></script> -->
<script type="text/javascript" src="{{ asset('template/vendors/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/select2.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script>
		//
		// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
		//
		$.fn.dataTable.pipeline = function ( opts ) {
		    // Configuration options
		    var conf = $.extend( {
		        pages: 5,     // number of pages to cache
		        url: '',      // script url
		        data: null,   // function or object with parameters to send to the server
		                      // matching how `ajax.data` works in DataTables
		        method: 'GET' // Ajax HTTP method
		    }, opts );
		 
		    // Private variables for storing the cache
		    var cacheLower = -1;
		    var cacheUpper = null;
		    var cacheLastRequest = null;
		    var cacheLastJson = null;
		 
		    return function ( request, drawCallback, settings ) {
		        var ajax          = false;
		        var requestStart  = request.start;
		        var drawStart     = request.start;
		        var requestLength = request.length;
		        var requestEnd    = requestStart + requestLength;
		         
		        if ( settings.clearCache ) {
		            // API requested that the cache be cleared
		            ajax = true;
		            settings.clearCache = false;
		        }
		        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
		            // outside cached data - need to make a request
		            ajax = true;
		        }
		        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
		                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
		                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
		        ) {
		            // properties changed (ordering, columns, searching)
		            ajax = true;
		        }
		         
		        // Store the request for checking next time around
		        cacheLastRequest = $.extend( true, {}, request );
		 
		        if ( ajax ) {
		            // Need data from the server
		            if ( requestStart < cacheLower ) {
		                requestStart = requestStart - (requestLength*(conf.pages-1));
		 
		                if ( requestStart < 0 ) {
		                    requestStart = 0;
		                }
		            }
		             
		            cacheLower = requestStart;
		            cacheUpper = requestStart + (requestLength * conf.pages);
		 
		            request.start = requestStart;
		            request.length = requestLength*conf.pages;
		 
		            // Provide the same `data` options as DataTables.
		            if ( $.isFunction ( conf.data ) ) {
		                // As a function it is executed with the data object as an arg
		                // for manipulation. If an object is returned, it is used as the
		                // data object to submit
		                var d = conf.data( request );
		                if ( d ) {
		                    $.extend( request, d );
		                }
		            }
		            else if ( $.isPlainObject( conf.data ) ) {
		                // As an object, the data given extends the default
		                $.extend( request, conf.data );
		            }
		 
		            settings.jqXHR = $.ajax( {
		                "type":     conf.method,
		                "url":      conf.url,
		                "data":     request,
		                "dataType": "json",
		                "cache":    false,
		                "success":  function ( json ) {
		                    cacheLastJson = $.extend(true, {}, json);
		 
		                    if ( cacheLower != drawStart ) {
		                        json.data.splice( 0, drawStart-cacheLower );
		                    }
		                    json.data.splice( requestLength, json.data.length );
		                     
		                    drawCallback( json );
		                }
		            } );
		        }
		        else {
		            json = $.extend( true, {}, cacheLastJson );
		            json.draw = request.draw; // Update the echo for each response
		            json.data.splice( 0, requestStart-cacheLower );
		            json.data.splice( requestLength, json.data.length );
		 
		            drawCallback(json);
		        }
		    }
		};
		 
		// Register an API method that will empty the pipelined data, forcing an Ajax
		// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
		$.fn.dataTable.Api.register( 'clearPipeline()', function () {
		    return this.iterator( 'table', function ( settings ) {
		        settings.clearCache = true;
		    } );
		});

		function numberWithCommas(x) {
		    // return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		    const options = { 
			  minimumFractionDigits: 2,
			  maximumFractionDigits: 2 
			};
		  	const formatted = Number(x).toLocaleString('en', options);
		  	return formatted;
		}

		$('#formRequest').on('submit', function(){
				$('#btnFilter').prop('disabled', true);
		})

		$(document).ready(function() {
				$('#datepicker').prop('disabled', false);
				try {
					$('#datepicker').datepicker({
						dateFormat: "yy-mm-dd",
						showWeek: true,
						changeYear: true,
						showButtonPanel: true,
						maxDate: new Date(),
						onSelect : function(text, obj){
							// console.log(text, obj);
						}
					});
				} catch(error){}

				$(".select2").select2({});
				$(".select2-vendor").select2({
						placeholder: "ALL VENDOR",
				});
				var tables =  $('#content-table').DataTable({
						// "dom": '<"toolbar">frtip',
						dom: 'l<"button-wrapper"B>frtp',
						"fixedHeader": {
							header: true,
							footer:true
						},
						"aaSorting": [],
						"searching":true,
						"paging": false,
						"autoWidth":false,
						"columnDefs": [ {
								"targets"  : 'no-sort',
								"orderable": false,
						}],
						"pagingType":"full_numbers",
						"language": {
								"zeroRecords": "Sorry, your query doesn't match any data",
								"processing": ''
						},
						"order": [[ 0, "asc" ]],
						"buttons": {
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
								footer: false,
								title : 'Account Receivable Aging Summary For All Types',
								messageTop: "Showing All Customer's Outstanding Balance",
								customize: function (xlsx) {
									var sheet = xlsx.xl.worksheets['sheet1.xml'];
									var col = $('col', sheet);							
									col.each(function (index, elem) {
										if(index == 0){
											$(this).attr('width', 8);
										}
										else if(index == 1){
											$(this).attr('width', 40);
										}
										else{
											$(this).attr('width', 20);
										}
									});

									$('row c[r^="C"]', sheet).each(function(index, elem){
										if(index != 0)
											$(elem).attr('s', '51');
									});
								},
								action: function(e, dt, button, config) {
									var data_detail_cancellation = [];
									try {
										var table = $('#content-table').DataTable()
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
								}                 
							 }
							 // End button
							]
						},
						"serverSide": true,
						"processing": true,
						"ajax": {
			                url : "/sap/customer-ar-aging-summary/get-data",
			                data : {
			                    "plant": "{{ app('request')->input('plant') }}",
			                    "date" : "{{ app('request')->input('date') }}"
			                },
			                "dataSrc": function ( json ) {
						      	return json.data;
						    },
			                pages: 10,
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
			            "columns": [
			                {"data": "NUM_ORDER"},
			                {"data": "DETAILS", 
			                    "className" : 'text-left',
			                	render: function (id, type, full, meta)
			                    {
			                        $_account_name = id.hasOwnProperty('ACCOUNT_NAME') ? id.ACCOUNT_NAME : '';
			                        return $_account_name;
			                    }
			            	},
			            	{"data": "DETAILS", 
			                    "className" : 'text-center',
			                	render: function (id, type, full, meta)
			                    {
			                        $_account_name = id.hasOwnProperty('ACCOUNT_NO') ? id.ACCOUNT_NO : '';
			                        return $_account_name;
			                    }
			            	},
			            	{"data": "AR_AGING",
			            		"className" : 'text-right',
			                	render: function (id, type, full, meta)
			                    {
			                        $_0_30 = id.hasOwnProperty('day_0_30') ? numberWithCommas(id.day_0_30) : 0;
			                        return $_0_30;
			                    }
			            	},
			            	{"data": "AR_AGING", 
			            		"className" : 'text-right',
			                	render: function (id, type, full, meta)
			                    {
			                        $_31_60 = id.hasOwnProperty('day_31_60') ? numberWithCommas(id.day_31_60) : 0;
			                        return $_31_60;
			                    }
			            	},
			            	{"data": "AR_AGING", 
			            		"className" : 'text-right',
			                	render: function (id, type, full, meta)
			                    {
			                        $_61_90 = id.hasOwnProperty('day_61_90') ? numberWithCommas(id.day_61_90) : 0;
			                        return $_61_90;
			                    }
			            	},
			            	{"data": "AR_AGING", 
			            		"className" : 'text-right',
			                	render: function (id, type, full, meta)
			                    {
			                        $_GT_90 = id.hasOwnProperty('day_GT_90') ? numberWithCommas(id.day_GT_90) : 0;
			                        return $_GT_90;
			                    }
			            	},
			            	{"data": "AR_AGING", 
			            		"className" : 'text-right',
			                	render: function (id, type, full, meta)
			                    {
			                        $_total_amount = id.hasOwnProperty('total_amount') ? numberWithCommas(id.total_amount) : 0;
			                        return $_total_amount;
			                    }
			            	}
			            ],
			            "footerCallback": function ( row, data, start, end, display ) {
			            	try {
					            var api = this.api();
					 
					            // Remove the formatting to get integer data for summation
					            var intVal = function ( i ) {
					                return typeof i === 'string' ?
					                    i.replace(/[\$,]/g, '')*1 :
					                    typeof i === 'number' ?
					                        i : 0;
					            };
					 
					            // Total over all pages
					            var total_0_30 = api
					                .column( 3 )
					                .data()
					                .reduce( function (a, b) {
					                	var $value = b.hasOwnProperty('day_0_30') ? b.day_0_30 : 0;
					                    return intVal(a) + $value;
					                }, 0 ),
					            total_31_60 = api
					                .column( 4 )
					                .data()
					                .reduce( function (a, b) {
					                    var $value = b.hasOwnProperty('day_31_60') ? b.day_31_60 : 0;
					                    return intVal(a) + $value;
					                }, 0 ),
					            total_61_90 = api
					                .column( 5 )
					                .data()
					                .reduce( function (a, b) {
					                    var $value = b.hasOwnProperty('day_61_90') ? b.day_61_90 : 0;
					                    return intVal(a) + $value;
					                }, 0 ),
					            total_GT_90 = api
					                .column( 6 )
					                .data()
					                .reduce( function (a, b) {
					                    var $value = b.hasOwnProperty('day_GT_90') ? b.day_GT_90 : 0;
					                    return intVal(a) + $value;
					                }, 0 ),
					            total_amount = api
					                .column( 7 )
					                .data()
					                .reduce( function (a, b) {
					                    var $value = b.hasOwnProperty('total_amount') ? b.total_amount : 0;
					                    return intVal(a) + $value;
					                }, 0 );
					 
					            // Update footer
					            total_0_30 = numberWithCommas(total_0_30);
					            total_31_60 = numberWithCommas(total_31_60);
					            total_61_90 = numberWithCommas(total_61_90);
					            total_GT_90 = numberWithCommas(total_GT_90);
					            total_amount = numberWithCommas(total_amount);

					            $(api.column( 3 ).footer()).html(total_0_30);
					            $(api.column( 4 ).footer()).html(total_31_60);
					            $(api.column( 5 ).footer()).html(total_61_90);
					            $(api.column( 6 ).footer()).html(total_GT_90);
					            $(api.column( 7 ).footer()).html(total_amount);

					            $('.footer-callback').prop('hidden', false);

				        	} catch(error){}
				        }

				// }).ajax.reload();
				});

				$(".btn-export-excel").click(function(e){
					var title = $(this).data('title') || 'AR Aging Summary For All Types';
					var key = $(this).data('key') || '#none';

					Swal.fire({
						title: 'Export Confirmation',
						text: `This operation will export ${title} data to Excel workbook`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						confirmButtonColor: '#3085d6',

						cancelButtonColor: '#d33',
						confirmButtonText: 'Export'
					}).then((result) => {
						if (result.isConfirmed) {
							try {
								Swal.close();
							} catch(error){}
							setTimeout(function(){
								$('#content-table').table2excel({
									// exclude CSS class
									exclude:".noExl",
									filename:title,//do not include extension
									fileext:".xls" // file extension
									// preserveColors:true
								});
							},400)
						}
					})
				});

		});

</script>
@endsection
