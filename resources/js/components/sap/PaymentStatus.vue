<template>
	<div id="payment-status">
       <i v-html="default_status"></i>
    </div>
</template>

<script>
	import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		data: function() {
		  return {
		  	default_status : '<i class="fa fa-lg fa-spin fa-spinner"></i>'
		  }
		},
		components: {
            Loading
        },
		props : {
			no_invoice : String,
	      	plant_code : String,
	      	client_code : {
	      		type : String,
	      		default : ''
	      	}
		},
		async mounted() {
			console.log('mounted');
			this.$nextTick(async function(){
				var vm = this;
				try {
			      const baseURI = '/sap/invoice/payment/status';
				  var params = {
					 'no_invoice' : this.no_invoice,
					 'plant_code' : this.plant_code,
				  }
				  if(this.client_code)
				  	params['client'] = this.client_code

			      await this.$http.get(baseURI, { params })
			      .then((data)=>{
			      	try {
						vm.default_status = '<span>'+ data.data +'</span>';
					} catch(error){
						console.log("ERROR CATCH", error);
					}
			      })
			      .catch((error)=>{
			      	console.log("ERROR AXIOS", error);
			      })
				} catch(error) {console.log('Error getting payment status')}
			})
		}
	}
</script>

<style scoped>
	.vld-overlay.is-active {
    	align-items: flex-start !important;
    	padding: 2em !important;
  	}
</style>