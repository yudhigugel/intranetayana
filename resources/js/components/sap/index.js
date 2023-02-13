import Vue from 'vue';
import CashBalance from './CashBalance';
import RentalObject from './RentalObject';
import PaymentStatus from './PaymentStatus';

// import VueRouter from 'vue-router'
// Vue.use(VueRouter)
import axios from 'axios';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.prototype.$http = axios
Vue.config.productionTip = false;
Vue.config.silent = true;
Vue.use(VueSweetalert2);

new Vue({
    el: "#reactiveListener",
    components:{
        CashBalance,
        RentalObject,
        PaymentStatus
    }
})
