<template>
	<div class="vld-parent">
		<!-- Title Here -->
		<loading :active="isLoading" :is-full-page="fullPage" loader="bars" height="50" width="150" color="#0D69FD" />

		<div class="card-body pb-0 bg-white" id="header">
			<div class="form-group">
				<div class="row">
				  <div class="title-header col-8">
					<h2> AYANA RESORT & SPA BALI </h2>
					<h3> Cancellation Made On </h3>
					<h5> Date : {{ date_to_show }} </h5>
				  </div>
				  <div class="pt-3 date-range-wrapper col-4 text-right">
					<form method="GET" action="" class="d-inline-block">
					  <div class="wrapper-form">
						  <div class="mb-1">
							<small style="color:#000;text-align: right;">Pick a date</small>
						  </div>
						  <input type="text" v-model="selected_date" class="form-control datepicker" name="date" id="datepicker">
					  </div>
					</form>
				  </div>
				</div>
			</div>
			<div class="form-group">
				<!-- Content Render by blade -->
				<div v-html="data_cancellation"></div>
				<!-- End content render by blade -->
			</div>
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
			data_cancellation : this.templateBasedOnRole,
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
			role : String,
			date_to_lookup : String
		},
		computed : {},
		methods: {
			fetchCancellation: function (value) {
			  this.isLoading = true;
			  var vm = this;

			  const baseURI = '/report/cancellation_daily/filter_cancellation_daily';
			  const value_selected = value || vm.selected_date;
			  console.log("Selected date", value_selected);
			  const params = {
				'business_date' : value_selected
			  }

			  this.$http.get(baseURI, { params })
			  .then((data)=>{
				try {
					vm.data_cancellation = data.data.data
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
			$('input[name=date]').datepicker({
			  dateFormat: "yy-mm-dd",
			  showWeek: true,
			  changeYear: true,
			  showButtonPanel: true,
			  maxDate: new Date(),
			  onSelect : function(text, obj){
				vm.selected_date = vm.formatDate(text);
				vm.fetchCancellation(text);
			  }
			});
		},
		updated() {},
		watch : {
			data_cancellation : function(val){
				try{
					$("#table-cancellation-summary").DataTable().destroy()					
					$("#table-cancellation").DataTable().destroy()
					this.$nextTick(()=>{
						var table_summary = $('#table-cancellation-summary').DataTable({
							dom: 'l<"button-wrapper"B>frt',
							buttons: {
					          dom: {
					            button: {
					              tag: 'button',
					              className: 'mx-2'
					            }
					          },
							  buttons: [{
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
						                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this ,e, dt, button, config);
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
						                  var newSheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'+
						                    '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac" mc:Ignorable="x14ac">'+
						                    header_column + sheet_data +
						                  '</worksheet>';
						                  xlsx.xl.worksheets['sheet2.xml'] = $.parseXML(newSheet);
						                }
						              } catch(error){
						                  Swal.fire({
						                    icon: 'info',
						                    title: 'Oops...',
						                    text: "Something went wrong when trying to export, try again in a moment or reload the page",
						                })
						              }
							        }
								}]
							}
						});
						
						$('#table-cancellation').DataTable({
							scrollX : true,
						});

						table_summary.buttons( 0, null ).container().prependTo(
					       $('.date-range-wrapper')
					    );
					});
				} catch(error) {}
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