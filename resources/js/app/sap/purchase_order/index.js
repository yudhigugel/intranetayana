import Vue from 'vue';
import VueRouter from 'vue-router';
import PurchaseOrder from './PurchaseOrder';
import DetailPO from './DetailPO';

Vue.use(VueRouter);

const routes = [
    { path: '/', component: PurchaseOrder },
    { path: '/:id', component: DetailPO }
];

const router = new VueRouter({
    routes
});

new Vue({
    router,
    el: "#PoApp",
    components: {

    }
})