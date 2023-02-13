import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);
import example from './../example';
import axios from 'axios';
import rest from "@app/services/rest";


const store = new Vuex.Store({
    state: {
        loading:false,
        refresh_auto:true,
        data_order: {
            plant_name: '',
            plant_code: '',
            detail: [],
            code: ''
        },
        settings: {
            minimize: false,
            darkmode: false,
            mode: localStorage.getItem('mode')?localStorage.getItem('mode'):'light',
            shipping: true,
            category: true,
            action_date: true,
            timer: true,
            item: true
        }
    },
    getters:{
        refresh_time:(state)=>(plant_code)=>{
            if(plant_code=='all'){
                return 120000;
            }
            return 60000;
        }
    },
    mutations: {
        getData(state, plan_code) {
            let request = rest.get(`${window.backend_url}/v1/warehouse/dashboard/${plan_code}`);
            state.loading=true;
            request.then((res) => {
                state.data_order = res.data;
                state.loading=false;
            });
        },
        setMinimize(state, data) {
            state.settings.minimize = data;
        },
        setDarkmode(state, data) {
            state.settings.darkmode = data;
        },
        setShipping(state, data) {
            state.settings.shipping = data;
        },
        setCategory(state, data) {
            state.settings.category = data;
        },
        setDate(state, data) {
            state.settings.action_date = data;
        },
        setTimer(state, data) {
            state.settings.timer = data;
        },
        setItem(state, data) {
            state.settings.item = data;
        },
        setMode(state, data) {
            state.settings.mode = data;
        },
    }
})

export default store;