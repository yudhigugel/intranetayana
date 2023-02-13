import Vue from 'vue';
import SideMenu from './SideMenu';
Vue.config.productionTip = false;
Vue.config.silent = true

new Vue({
    el: "#sideMenuComponent",
    components:{
        SideMenu
    }
})
