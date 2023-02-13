import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);
import example from './../example';
import axios from 'axios';


const store = new Vuex.Store({
    state: {
        orders: {
            category: [],
            location_all: [],
        },
        plant_selected: [],
        location_all: [],
        pmn_location_selected: []
    },
    mutations: {
        getOrders(state, plan_code) {
            let request = axios.get(`${window.backend_url}/v1/warehouse/orders`);
            request.then((res) => {
                state.orders = res.data;
                if (res.data.location_all) {
                    state.location_all = res.data.location_all;
                }
            });
        },
        setLocation(state, data) {
            state.orders.location_all = data;
        },
        resetCompanySelected(state) {
            state.plant_selected = [];
        },
        addCompanySeleted(state, company_code) {
            state.plant_selected.push(company_code);
        },
        removeCompanySeleted(state, index) {
            state.plant_selected.splice(index, 1);
        },
        setPMNSelected(state, location) {
            state.pmn_location_selected.push(location);
        },
        removePMNSelected(state, index) {
            state.pmn_location_selected.splice(index, 1);
        }
    }
})

export default store;