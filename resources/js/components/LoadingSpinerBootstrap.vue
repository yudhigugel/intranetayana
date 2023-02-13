<template>
  <div>
    <div
     v-if="showLoading==true"
      class="spinner-border text-primary"
      role="status"
      style="width: 25px; height: 25px"
    >
      <span class="sr-only">Loading...</span>
    </div>
  </div>
</template>
<script>
export default {
  props: ["show"],
  data() {
    return {
      showLoading: false
    };
  },
  mounted() {
    const _this = this;
    this.$root.$on("loading_spiner", function (param) {
      if (param == true) {
        _this.showLoading = true;
      } else {
        _this.showLoading = false;
      }
    });
  },
};
</script>