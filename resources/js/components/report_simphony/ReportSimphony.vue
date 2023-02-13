<template>
	<div class="vld-parent">
		<!-- Title Here -->
		<loading :active="isLoading" :is-full-page="fullPage" loader="bars" height="50" width="150" color="#0D69FD" />

		<div class="card-body pb-0 bg-white" id="header">
          <div class="row">
            <div class="col-7">
                  <!-- <img src="{{ url('/image/logo_delonix.png')}}" style=""> -->
                  <h2> POS Simphony </h2>
                  <h3> Daily Transaction By Revenue Center</h3>
                  <h5> Transaction Date : {{ date_to_show }} </h5>
            </div>
            <div class="pt-3 col-5">
              <form method="GET" action="">
                <div class="form-group col-md-6 float-right">
                  <div class="mb-1">
                    <small style="color:#000;text-align: right;">Pick a date</small>
                  </div>
				  <input disabled type="text" v-model="selected_date" class="form-control datepicker" name="date" id="datepicker">
                </div>
              </form>
            </div>
          </div>
        </div>
        <hr class="my-0">
        <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
        	<div v-html="data_pos"></div>
        </div>
		<!-- End Title Here -->
	</div>
</template>

<script>
	import Loading from 'vue-loading-overlay';
	import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		data: function() {
		  return {
			data_pos : this.templateBasedOnRole,
			isLoading : false,
			selected_date : this.date_to_lookup,
			date_to_show : this.date_to_lookup,
			fullPage: false
		  }
		},
		components: {
			Loading
		},
		props : {
			templateBasedOnRole : {
				type: String,
				default: '<div class="text-center"><div class="p-3">No Data to show</div></div>'
			},
			date_to_lookup : String
		},
		computed : {},
		methods: {
			fetchPOS: function (value) {
			  this.isLoading = true;
			  var vm = this;

			  const baseURI = '/report/fnb_ayana/filter_pos_daily';
			  const value_selected = value || vm.selected_date;
			  console.log("Selected date", value_selected);
			  const params = {
				'business_date' : value_selected
			  }

			  this.$http.get(baseURI, { params })
			  .then((data)=>{
				try {
					vm.data_pos = data.data.data
					vm.date_to_show = data.data.date_to_lookup
				} catch(error){
					setTimeout(function(){
						vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
					},300)
				}
			  })
			  .catch((error)=>{
				console.log(error);
				vm.selected_date = vm.formatDate(vm.selected_date);
				setTimeout(function(){
					vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
				},300)
			  })
			  .finally(()=>{
				vm.isLoading = false;
			  })
			},
			formatDate(date) {
				try {
					var d = new Date(date),
						month = '' + (d.getMonth() + 1),
						day = '' + d.getDate(),
						year = d.getFullYear();

					if (month.length < 2) 
						month = '0' + month;
					if (day.length < 2) 
						day = '0' + day;

					return [year, month, day].join('-');
				} catch(error) {
					console.log(error);
					return date;
				}
			},
			htmlDecode(input){
		      	var e = document.createElement('textarea');
		      	e.innerHTML = input;
		      	// handle case of empty input
		      	return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
			},
			numberWithCommas(x) {
				return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			}
			
			// triggerFiscal : function(){
			//  // console.log(this.$refs.selectFiscal, this.$refs.$el);
			//  var element = this.$refs.selectFiscal;
			//  element.dispatchEvent(new Event('change'));
			// }
		},
		mounted() {
			this.selected_date = this.formatDate(this.selected_date);
			var vm = this;
			$('#datepicker').prop('disabled', false);
			$('input[name=date]').datepicker({
			  dateFormat: "yy-mm-dd",
			  showWeek: true,
			  changeYear: true,
			  showButtonPanel: true,
			  maxDate: new Date(),
			  onSelect : function(text, obj){
				vm.selected_date = vm.formatDate(text);
				vm.fetchPOS(text);
			  }
			});
		},
		updated() {},
		watch : {
			data_pos : function(val){
				var vm = this;
				try{
					$("#content-table").DataTable().destroy();
					this.$nextTick(()=>{
						var $table = $('#content-table').DataTable({
					      // dom: 'Qlfrtip',
					      // dom: 'rtip',
					      // "pageLength": 50,
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
					                  var $_unescape = vm.htmlDecode($(el)[0].val()) || '';
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
						// End Datatable
						try {
					      $('<label>Export Excel</label>').prependTo('.export-wrapper');
					      $table.buttons( 0, null ).container().appendTo($('.export-wrapper'));
					    } catch(error){}
					});
				} catch(error){}
			}
		}
	}
</script>

<style scoped>
	.vld-overlay.is-active {
		align-items: flex-start !important;
		padding: 2em !important;
	}
</style>