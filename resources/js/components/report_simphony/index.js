import Vue from 'vue';
import ReportSimphony from './ReportSimphony';
// import VueRouter from 'vue-router'
// Vue.use(VueRouter)
import axios from 'axios';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.prototype.$http = axios
Vue.config.productionTip = false;
Vue.config.silent = true;
Vue.use(VueSweetalert2);

new Vue({
    el: "#reportSimphony",
    components:{
        ReportSimphony
    }
})
