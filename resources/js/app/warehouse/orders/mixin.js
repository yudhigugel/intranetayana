import store from './stores/store';

const mixin = {
    store,
    methods: {
        checkPlantShow(plant_id) {
            return this.plant_selected.includes(plant_id);
        },
    }
}

export default mixin;