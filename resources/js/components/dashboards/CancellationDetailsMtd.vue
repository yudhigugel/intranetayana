<template>
	<div class="vld-parent">
		<!-- Title Here -->
		<loading :active="isLoading" :is-full-page="fullPage" loader="bars" height="50" width="150" color="#0D69FD" />
		<!-- End Title Here -->

		<!-- Content Render by blade -->
		<div v-html="data_cancellation_mtd"></div>
		<!-- End content render by blade -->
	</div>
</template>

<script>
	import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		data: function() {
		  return {
		    data_cancellation_mtd : this.templateBasedOnRole,
		    defaultMessageError : '<div class="card-body text-center"><h6 class="normal-line-height">Unable to load the data :( <br> Please wait for a moment and try to refresh the page...</h6></div>',
		    isLoading : false,
		    fullPage: false,
		    watch_error : false,
		  }
		},
		components: {
            Loading
        },
		props : {
			templateBasedOnRole : {
				type: String,
				default: '<div class="card-body text-center"><h6 class="loading-text-dashboard">Loading data ...</h6></div>'
			},
			resort: '',
			reportType: {
				type: String,
				default: 'CANCELLATION_MTD'
			},
			businessDate: ''
		},
		methods: {
			fetchCancellationMTD: function (event) {
			  this.isLoading = true;
			  var vm = this;
			  var baseURI = '/dashboard';
		      const params = {};

		      // Check if param is not empty
		      // Then add
		      if(this.resort)
		      	params['resort'] = this.resort;
		      if(this.reportType)
		      	params['report'] = this.reportType;
		      if(this.businessDate)
		      	params['business_date'] = this.businessDate

		      this.$http.get(baseURI, { params })
		      .then((data)=>{
		      	vm.data_cancellation_mtd = data.data;
		      	try {
		      		setTimeout(function(){
		      			if(! $.fn.DataTable.isDataTable( '.datatable-able' )){
					      	$('.datatable-able').DataTable({
						        "dom":'<"search-filter"f>rtip',
						        "scrollX":true,
						        "autoWidth":false,
						        "lengthChange":false,
						        "columnDefs": [{
						          "targets": [1],
						          render: function(data, type, row, meta) {
						            if(data){
						              if (type == 'display' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
						                 return moment(data).format('DD-MMM-YYYY');
						              }else if (type == 'filter' && data !== null && data !== '0000-00-00 00:00:00' && data !== '-') {
						                 return moment(data).format('DD-MMM-YYYY');
						              }else{
						                return "-";
						              }
						            } else { return "Not Available" }
						          }
						        }]
						    });
				      	}
			      	}, 500);
		      	} catch(error){}
		      })
		      .catch((error)=>{
		      	vm.data_cancellation_mtd = vm.defaultMessageError;
		      	// setTimeout(function(){
		      	// 	vm.$swal('Oops', 'Something went wrong with the server, please try again in a moment or reload the page', 'error');
		      	// },300)
		      })
		      .finally(()=>{
		      	vm.isLoading = false;
		      })
		    },
		},
		mounted() {
			var vm = this;
			setTimeout(function(){
				vm.fetchCancellationMTD();
			}, 1000);
		},
		watch : {},
		updated() {}
	}
</script>

<style scoped>
	.vld-overlay.is-active {
    	align-items: flex-start !important;
    	padding: 2em !important;
  	}
</style>