<template>
  <div class="pt-2">
    <div class="row">
      <div class="col-md-12">
        <PMN v-if="checkPlantShow('PMN')"/>
      </div>
    </div>
  </div>
</template>
<script>
import mixin from "./mixin";
import { mapState } from "vuex";
import PMN from "./companies/PMN";

export default {
  mixins: [mixin],
  components: {
    PMN,
  },
  computed: {
    ...mapState(["plant_selected"]),
  },
  data() {
    return {};
  },
  watch:{
    plant_selected(val){
      
    }
  }
};
</script>