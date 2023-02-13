<template>
  <div v-if="showLoading==true" :id="id">
    <div :style="type_loading" v-if="show!==false">
      <div class="bar-loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["full", "show","id"],
  data() {
    return {
      showLoading: true
    };
  },
  computed: {
    type_loading() {
      if (this.full == true) {
        return {
          width: "100%",
          height: "120vh",
          zIndex: 1000,
          backgroundColor: "rgba(238, 238, 238,.4)",
          position: "fixed",
          top: 0,
          bottom: 0,
          left: 0,
          right: 0,
          display: "flex",
          alignItems: "center",
          justifyContent: "center"
        };
      }
      return {};
    }
  },
  mounted() {
    const _this = this;

    this.$root.$on("loading", function(param) {
      if (param == true) {
        _this.showLoading = true;
      } else {
        _this.showLoading = false;
      }
    });
  }
};
</script>