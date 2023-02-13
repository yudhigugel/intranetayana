<template>
	<div class="vld-parent">
		<!-- Title Here -->
		<loading :active="isLoading" :is-full-page="fullPage" loader="bars" height="50" width="150" color="#0D69FD" />
		<!-- End Title Here -->

		<!-- Content Render by blade -->
		<div v-html="data_forecast_breakfast"></div>
		<!-- End content render by blade -->
	</div>
</template>

<script>
	import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		name: 'SevenDaysForecastBreakfast',
		data: function() {
		  return {
		    data_forecast_breakfast : this.templateBasedOnRole,
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
				default: '7_DAYS_BREAKFAST_FORECAST'
			},
			businessDate: ''
		},
		methods: {
			fetchBreakfastForecast: function (event) {
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
		      	vm.data_forecast_breakfast = data.data;
		      })
		      .catch((error)=>{
		      	vm.data_forecast_breakfast = vm.defaultMessageError;
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
				vm.fetchBreakfastForecast();
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