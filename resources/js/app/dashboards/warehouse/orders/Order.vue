<template>
  <div
    :class="{
      'layout-orders': true,
      darkmode: settings.mode == 'dark',
      biznetmode: settings.mode == 'biznet',
      purplemode: settings.mode == 'purple',
      girly: settings.mode == 'girly',
    }"
  >
    <div class="title-container align-items-center">
      <div class="d-flex align-items-center">
        <div>
          <h3>
            SALES ORDER - {{ data_order.plant_name }} <span v-if="data_order.plant_name!=='ALL'"> ({{
              data_order.plant_code
            }}) </span>
          </h3>
        </div>
        <div class="ml-3">
          <LoadingSpinerBootstrap />
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between">
        <!-- <div class="mr-3">
          <a href="#" @click="refresh()">
            <span
            style="font-size:1.3rem;"
              class="iconify"
              data-icon="el:refresh"
              data-inline="false"
            ></span>
          </a>
        </div> -->
        <div>
          <ClockComponent />
        </div>
      </div>
    </div>
    <div class="order-container" v-if="data_order.detail">
      <div class="order-item first">
        <h3>OPEN ({{ countOrder("OPEN") }})</h3>
        <hr />
        <div class="order-item-container">
          <OrderItem status="OPEN" />
        </div>
      </div>
      <div class="order-item second">
        <h3>PROGRESS ({{ countOrder("PROCESS") }})</h3>
        <hr />
        <div class="order-item-container">
          <OrderItem status="PROCESS" />
        </div>
      </div>
      <div class="order-item three">
        <h3>READY PICK UP ({{ countOrder("READY") }})</h3>
        <hr />
        <div class="order-item-container">
          <OrderItem status="READY" />
        </div>
      </div>
      <div class="order-item three">
        <h3>DONE ({{ countOrder("DONE") }})</h3>
        <hr />
        <div class="order-item-container">
          <OrderItem status="DONE" />
        </div>
      </div>
    </div>
    <div class="settings">
      <a href="#" @click="showSettings()">
        <span class="iconify" data-icon="bi:tools" data-inline="false"></span>
      </a>
    </div>
    <modal name="settings" width="50%" height="auto">
      <div
        :class="{ 'modal-settings': true, darkmode: settings.mode == 'dark' }"
      >
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h5>
              <span
                class="iconify"
                data-icon="fa-solid:tools"
                data-inline="false"
              ></span>
              Settings Dashboard
            </h5>
          </div>
          <div>
            <a @click="hideSettings()" href="#" style="font-size: 1.2rem"
              ><span
                class="iconify"
                data-icon="clarity:times-line"
                data-inline="false"
              ></span
            ></a>
          </div>
        </div>
        <hr />
        <Settings />
      </div>
    </modal>
  </div>
</template>

<script>
import Vue from "vue";
import ClockComponent from "./ClockComponent";
import OrderItem from "./OrderItem";
import mixin from "./mixin";
import { mapMutations, mapState, mapGetters } from "vuex";
import Swal from "sweetalert2";
import VModal from "vue-js-modal";
import Settings from "./Settings";
import LoadingSpinerBootstrap from "@app/components/LoadingSpinerBootstrap";

Vue.use(VModal);

export default {
  props: ["plan_code"],
  mixins: [mixin],
  components: {
    ClockComponent,
    OrderItem,
    Settings,
    LoadingSpinerBootstrap,
  },
  methods: {
    ...mapMutations(["getData"]),
    countOrder(status) {
      let _this = this;
      if (this.data_order) {
        return this.data_order.detail.filter((value) => {
          return status == value.so_status;
        }).length;
      }
      return 0;
    },
    showSettings() {
      this.$modal.show("settings");
    },
    hideSettings() {
      this.$modal.hide("settings");
    },
    refresh() {
      this.getData(this.plan_code);
    },
  },
  computed: {
    ...mapState(["data_order", "settings", "loading"]),
    ...mapGetters(['refresh_time'])
  },
  watch: {
    data_order(val) {
      if (val.code == 404) {
        Swal.fire({
          icon: "error",
          title: val.message,
          allowOutsideClick: false,
          showConfirmButton: false,
        });
      }
    },
    loading(val) {
      if (val == true) {
        this.$root.$emit("loading_spiner", true);
      } else {
        this.$root.$emit("loading_spiner", false);
      }
    },
  },
  mounted() {
    let _this = this;
    this.getData(this.plan_code);
    if (this.$store.state.refresh_auto == true) {
      setInterval(function () {
        _this.getData(_this.plan_code);
      }, this.refresh_time(this.plan_code));
    }
  },
};
</script>
