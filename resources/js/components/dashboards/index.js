import Vue from 'vue';
import FourteenDaysForecastDashboard from './14DaysForecastDashboard';
import ResortSummary from './ResortSummary';
import SevenDaysForecastBreakfast from './SevenDaysForecastBreakfast';
import TopMostSellingItem from './TopMostSellingFNBItem';
import VipGuestList from './VipGuest';
import CancellationSummary from './CancellationSummary';
import CancellationDetailsMtd from './CancellationDetailsMtd';
import AyanaCruisesRevenue from './AyanaCruisesRevenue';

// import VueRouter from 'vue-router'
// Vue.use(VueRouter)
import axios from 'axios';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.prototype.$http = axios
Vue.config.productionTip = false;
Vue.config.silent = true;
Vue.use(VueSweetalert2);

new Vue({
    el: "#reactiveDashboard",
    components:{
        FourteenDaysForecastDashboard,
        ResortSummary,
        SevenDaysForecastBreakfast,
        TopMostSellingItem,
        VipGuestList,
        CancellationSummary,
        CancellationDetailsMtd,
        AyanaCruisesRevenue
    }
})
