<template>
	<div class="vld-parent">
		<!-- Title Here -->
		<loading :active="isLoading" :is-full-page="fullPage" loader="bars" height="50" width="150" color="#0D69FD" />

		<div class="card-body pb-0 bg-white" id="header">
		  <div class="row">
		    <div class="col-7">
		          <h2> AYANA GROUP </h2>
		          <h3> BANK BALANCE & TIME DEPOSIT</h3>
		          <h5 v-if="filter_available"> Fiscal Year Balance : {{ selected_fiscal }}</h5>
		    </div>
		    <div class="pt-3 col-5" v-if="filter_available">
		        <div class="form-group float-right">
		          <div class="d-inline-block mx-1">
		          	  <button @click="triggerFiscal()" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
		          </div>
		          <div class="d-inline-block mx-1" style="min-width: 150px">
			          <div class="mb-1">
			            <small style="color:#000;text-align: right;">Pick Fiscal Year</small>
			          </div>
			          <select class="form-control" v-model="selected_fiscal" id="select-fiscal-year" ref="selectFiscal" @change="fetchCashBalance($event)">
			          	<option value="" disabled>Select Year</option>
			          	<option v-for="year in years()" :value="year">{{ year }}</option>
			          </select>
			      </div>
		        </div>
		    </div>
		  </div>
		</div>
		<!-- End Title Here -->

		<!-- Content Render by blade -->
		<div v-if="dismiss_modal" class="card-body text-center">
			<div class="d-inline-block"><h6>You have no access,</h6></div>
			<div class="d-inline-block"><a href="javascript:void(0)" @click="check_password()">&nbsp;Try again</a></div>
		</div>
		<div v-else v-html="data_cash_balance"></div>
		<!-- End content render by blade -->
	</div>
</template>

<script>
	import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		data: function() {
		  return {
		    data_cash_balance : this.templateBasedOnRole,
		    // data_cash_balance : '<div class="card-body text-center"><h6>No Data to show</h6></div>',
		    isLoading : false,
		    selected_fiscal : new Date().getFullYear().toString(),
		    fullPage: false,
		    password_cash_balance: process.env.MIX_PASSWORD_CASH_BALANCE,
		    filter_available: false,
		    watch_error : false,
		    dismiss_modal: false,
		    is_need_password : this.parent_need_password,
		  }
		},
		components: {
            Loading
        },
		props : {
			templateBasedOnRole : {
				type: String,
				default: '<div class="card-body text-center"><h6>No Data to show</h6></div>'
			},
			role : String,
			last_update_exchange : String,
			date_start_balance : String,
			date_end_balance : String,
			parent_need_password : {
				type : Number,
				default: 1
			},
			type_balance_new : {
				type : String,
				default: "false"
			}
		},
		methods: {
			years () {
		      	const year = new Date().getFullYear();
		      	return Array.from({length: year - 2019}, (value, index) => 2020 + index);
		    },
			fetchCashBalance: function (event) {
			  this.isLoading = true;
			  var vm = this;
			  var baseURI = '/sap/cash_balance/filter_cash_balance';

			  if(this.type_balance_new === "true"){
		      	baseURI = '/sap/cash_balance/new/filter_cash_balance';
			  }
		      const value_selected = event.target.value || '2021';
		      // console.log("Selected year", value_selected);
		      const params = {
				 'year' : value_selected
			  }

		      this.$http.get(baseURI, { params })
		      .then((data)=>{
		      	vm.data_cash_balance = data.data
		      })
		      .catch((error)=>{
		      	console.log(error);
		      	vm.selected_fiscal = new Date().getFullYear().toString();
		      	setTimeout(function(){
		      		vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
		      	},300)
		      })
		      .finally(()=>{
		      	vm.isLoading = false;
		      	try {
			      $("#content-table").largetable({
			        enableMaximize:true
			      });
			    } catch(error){}
		      })
		    },
		    triggerFiscal : function(){
		    	// console.log(this.$refs.selectFiscal, this.$refs.$el);
		    	var element = this.$refs.selectFiscal;
		    	element.dispatchEvent(new Event('change'));
		    },
		    check_password : async function(event){
		    	var vm = this;
				this.watch_error = false;
		    	const { value: password } = await this.$swal.fire({
				  title: 'Enter Password',
				  input: 'password',
				  inputPlaceholder: 'Enter Bank Balance Password',
				  inputAttributes: {
				    autocapitalize: 'off',
				    autocorrect: 'off'
				  },
				  showCancelButton: true,
				  cancelButtonText: "CANCEL",
				  inputValidator: (value) => {
				  	if(value && value!=vm.password_cash_balance)
				  		return 'Password incorrect!';
				    return !value && 'You need to write something!';
				  },
				  allowOutsideClick : false
				})

		    	if(typeof password == typeof undefined){
		    		vm.dismiss_modal = true
					throw new Error('Dismissed');
		    	}
		    	else{
					if (password==vm.password_cash_balance) {
						vm.filter_available = true;
						return {'status':'success'}
					}
					else{
						throw new Error('Password tidak sama');
					}
				}
		    }
		},
		mounted() {
			// var vm = this;
			// // console.log(vm.is_need_password, Boolean(Number(vm.parent_need_password)), typeof vm.is_need_password)
			// // If no permission, require password
			// if(Number(vm.is_need_password)){
			// 	vm.data_cash_balance = '<div class="card-body text-center"><h6>No Data to show</h6></div>';
			// 	var check_password = this.check_password()
			// 	.then((res) => {
			// 		vm.filter_available = true;
			// 	})
			// 	.catch((error) => {
			// 		if(vm.dismiss_modal){
			// 			// location.href = '/'
			// 			return
			// 		}
			// 		vm.watch_error = true;
			// 	});
			// }
			// else {
			this.filter_available = true;
			// }
		},
		watch : {
			watch_error: function (val) {
				var vm = this;
			  	if(val===true){
			      	this.check_password()
			      	.then((res) => {
						vm.filter_available = true;
			      	})
			      	.catch((error) => {
			      		if(vm.dismiss_modal){
							// location.href = '/'
							return
						}
						vm.watch_error = true;
					});
				}
		    },
		    filter_available : function(val){
		    	var vm = this
		    	if(val===true && Number(vm.is_need_password)){
		    		vm.dismiss_modal = false
		    		vm.data_cash_balance = '';
		    		vm.$nextTick(function(){
						vm.triggerFiscal();
					})
				}

		    }
		},
		updated() {
			try{
				$('.table-tree').simpleTreeTable({
			      opened:'all',
			    });	
			} catch(error){}
		}
	}
</script>

<style scoped>
	.vld-overlay.is-active {
    	align-items: flex-start !important;
    	padding: 2em !important;
  	}
</style>