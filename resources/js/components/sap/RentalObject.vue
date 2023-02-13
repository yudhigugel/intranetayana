<template>
	<div class="vld-parent">
		<!-- Title Here -->
		<loading :active="isLoading" :is-full-page="fullPage" loader="bars" height="50" width="150" color="#0D69FD" />

		<div class="card-body pb-0 bg-white" id="header">
		  <div class="row">
		    <div class="col-9">
		        <h2> SAP RENTAL OBJECT </h2>
		        <h3> {{ company }}</h3>
		        <!-- <h5> @php echo ($data['date_start']!==$data['date_end'])? date('d F Y',strtotime($data['date_start'])).' - '.date('d F Y',strtotime($data['date_end'])) : date('d F Y',strtotime($data['date_start'])) @endphp</h5> -->
		        <h5 v-if="filter_applied">Contract date {{ date_start_contract + ' - ' + date_end_contract }}</h5>
		        <h5 v-else="filter_applied">Showing All Contract & Property</h5>

		        <div v-if="filter_applied">
	           	  <!-- <small>Filter Contract Date : <span class="badge badge-secondary">
	           		<a @click="clearFilter($event)" class="pr-2"><i class="fa fa-window-close"></i></a>{{ date_start_contract + ' - ' + date_end_contract }}</span>
	              </small> -->
	            </div>
		    </div>

		    <div class="pt-3 col-3">
		    	<div class="form-group row">
		          <div class="d-block col-12">
			         <div class="mb-1" style="position: relative;">
		              <small style="color:#000;text-align: right;">Filter By Company</small>&emsp;
		            </div>
		            <select disabled v-model="company_code" class="select2 company-select form-control" @change="fetchRentalObject($event)" ref="selectCompany" v-select="company_code">
		              <option value="" disabled>Select Company</option>
		              <option v-for="(value, key) in list_company" :value="key">{{ value }}</option>
		            </select>
			      </div>
			      <!-- <div class="d-block col-5">
		          	  <div class="mb-1">
		              	<small style="color:#000;text-align: right;">Filter Contract Date</small>
		              </div>
		              <input type="text" class="form-control" @change="fetchRentalObjectByDate($event)" @input="fetchRentalObjectByDate($event)" name="date" id="daterange" :value="date_start_contract + ' - ' + date_end_contract">
		          </div> -->
		        </div>
		    </div>

		  </div>
		</div>
		<!-- End Title Here -->

		<!-- Content Render by blade -->
		<div v-html="data_rental"></div>
		<!-- End content render by blade -->
	</div>
</template>

<script>
	import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		data: function() {
		  return {
		    data_rental : this.templateBasedOnRole,
		    isLoading : false,
		    selected_fiscal : new Date().getFullYear().toString(),
		    company : this.plant,
		    company_code : this.company_code_sent,
		    fullPage: false,
		    date_start_contract: this.date_start,
		    date_end_contract : this.date_end,
		    list_company : {},
		    filter_applied : false
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
			role : String,
			date_start : String,
			date_end : String,
			plant : {
				type: String,
				default : '-'
			},
			populate_company : {
	        	type: Object,
	        	default: {}
	      	},
	      	company_code_sent : String
		},
		methods: {
			fetchRentalObjectByDate: function(){
			  this.isLoading = true;
			  var vm = this;

		      const baseURI = '/sap/rental_object_list/filter_rental';
		      const params = {
				 'company_code' : this.company_code,
				 'date_start' : this.date_start_contract,
				 'date_end' : this.date_end_contract
			  }

		      this.$http.get(baseURI, { params })
		      .then((data)=>{
		      	try {
					vm.data_rental = data.data.data
					vm.company = data.data.plant
					vm.filter_applied = true
					try{
						$(".data-t-able").DataTable().destroy();
						$('#daterange').data('daterangepicker').remove() 
					} catch(error){}

				} catch(error){
					setTimeout(function(){
						vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
					},300)
				}
		      })
		      .catch((error)=>{
		      	console.log(error);
		      	vm.company = vm.company;
		      	vm.company_code = vm.company_code
		      	setTimeout(function(){
		      		vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
		      	},300)
		      })
		      .finally(()=>{
		      	vm.isLoading = false;
		      	vm.$nextTick(()=>{
		      		try {
			      		$('.table-tree').simpleTreeTable({
					      opened:'all',
					    });

					    var element = document.getElementsByClassName('simple-tree-table-root');
					      new ResizeSensor(element, function() {
					        $(".table-tree").floatingScroll("update");
					    });

					    // $('.data-t-able').DataTable({
					    //   "dom": '<"top-wrapper"lf><"table-scroller"rt><"bottom"ip><"clear">'
					    // });
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
					     
    					$(".table-scroller").floatingScroll();

    					$('#daterange').daterangepicker({
							ranges: {
		           				'Today': [moment(), moment()],
		           				'This Month': [moment().startOf('month'), moment().endOf('month')]
		           			},
						    format: 'DD/MM/YYYY'
						}, function(start, end, label) {
						  var date = $("#daterange").val();
		        		  date = date.split('-');
						  vm.date_start_contract = date[0].toString().trim();
						  vm.date_end_contract = date[1].toString().trim();
						  vm.fetchRentalObjectByDate();
						});
					} catch(error) { console.log('ERROR', error) }
				})
		      })

			},
			fetchRentalObject: function (event) {
			  this.isLoading = true;
			  var vm = this;

		      const baseURI = '/sap/rental_object_list/filter_rental';
		      const value_selected = event.target.value || 'NONE';
		      // console.log("Selected Company", value_selected);

		      var params = {
				 'company_code' : value_selected,
			  }

			  try{
	    		// JIka filter contract sudah terapply dan mengganti company, maka filter tanggal akan tetap ada
				if(vm.filter_applied){
					var date = $("#daterange").val();
		    		date = date.split('-');
		    		var date_start = date[0]
		    		var date_end = date[1]

					params = {
						'company_code' : value_selected,
						'date_start' : date_start,
						'date_end' : date_end
					}
				}
			  } catch(error){ console.log(error) }
			  // console.log(params, vm.filter_applied)

		      this.$http.get(baseURI, { params })
		      .then((data)=>{
		      	try {
					vm.data_rental = data.data.data
					vm.company = data.data.plant
					if(data.data.date_start && data.data.date_end){
						vm.date_start_contract = data.data.date_start
				  		vm.date_end_contract = data.data.date_end
				  	}

				  	try{
						$(".data-t-able").DataTable().destroy();
						$('#daterange').data('daterangepicker').remove() 
					} catch(error){}
				} catch(error){
					setTimeout(function(){
						vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
					},300)
				}
		      })
		      .catch((error)=>{
		      	vm.company = vm.company;
		      	vm.company_code = vm.company_code
		      	vm.date_start_contract = vm.date_start_contract
				vm.date_end_contract = vm.date_end_contract
		      	setTimeout(function(){
		      		vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
		      	},300)
		      })
		      .finally(()=>{
		      	vm.isLoading = false;
		      	vm.$nextTick(()=>{
		      		try {
			      		$('.table-tree').simpleTreeTable({
					      opened:'all',
					    });

					    var element = document.getElementsByClassName('simple-tree-table-root');
					      new ResizeSensor(element, function() {
					        $(".table-tree").floatingScroll("update");
					    });

					    // $('.data-t-able').DataTable({
					    //   "dom": '<"top-wrapper"lf><"table-scroller"rt><"bottom"ip><"clear">'
					    // });
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
					     
    					$(".table-scroller").floatingScroll();

    					$('#daterange').daterangepicker({
							ranges: {
		           				'Today': [moment(), moment()],
		           				'This Month': [moment().startOf('month'), moment().endOf('month')]
		           			},
						    format: 'DD/MM/YYYY'
						}, function(start, end, label) {
						  var date = $("#daterange").val();
		        		  date = date.split('-');
						  vm.date_start_contract = date[0].toString().trim();
						  vm.date_end_contract = date[1].toString().trim();
						  vm.fetchRentalObjectByDate();
						});
					} catch(error) { console.log('ERROR', error) }
				})
		      })
		    },
		    clearFilter : function(){
		    	this.filter_applied = false
		    	var element = this.$refs.selectCompany
		    	element.dispatchEvent(new Event('change'));
		    }
		},
		directives : {
			select : {
				bind: function (el, binding, vnode) {
				    $(el).on("select2:select", (e) => {
				      // v-model looks for
				      // - an event named "change"
				      // - a value with property path "$event.target.value"
				      el.dispatchEvent(new Event('change', { target: e.target }));
				    });
				},
				componentUpdated: function(el) {
				    // update the selection if the value is changed externally
				    $(el).trigger("change");
				}
			}
		},
		mounted() {
			var vm = this;
			try {
				var company = JSON.parse(this.populate_company);
				this.list_company = company;
			} catch(error) {console.log('Error getting and parsing company list')}
			this.$nextTick(()=>{
				$('#daterange').daterangepicker({
					ranges: {
           				'Today': [moment(), moment()],
           				'This Month': [moment().startOf('month'), moment().endOf('month')]
           			},
				    format: 'DD/MM/YYYY'
				}, function(start, end, label) {
				  var date = $("#daterange").val();
        		  date = date.split('-');
				  vm.date_start_contract = date[0].toString().trim();
				  vm.date_end_contract = date[1].toString().trim();
				  vm.fetchRentalObjectByDate();
				});
			});
		}
	}
</script>

<style scoped>
	.vld-overlay.is-active {
    	align-items: flex-start !important;
    	padding: 2em !important;
  	}
</style>