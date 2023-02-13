<template>
  <div class="order-card">
    <div
      v-for="(item, index) in items"
      v-bind:key="index"
      :class="
        'card animate__animated animate__flash ' +
        classCategory(item.so_category_type)
      "
    >
      <div class="card-body">
        <div class="row d-flex justify-content-between align-items-center">
          <div class="col-md-8">
            <h4 class="card-txt">
              <b>{{ item.so_number }}</b>
            </h4>
          </div>
          <div class="col-md-4 text-right">
            <span
              :class="{
                badge: true,
                'badge-danger': item.so_status == 'OPEN',
                'badge-info': item.so_status == 'READY',
                'badge-success': item.so_status == 'DONE',
                'badge-warning': item.so_status == 'PROCESS',
              }"
            >
              <b>{{ item.so_status }}</b></span
            >
          </div>
        </div>
        <hr />

        <div class="row" v-if="data_order.plant_code == 'ALL'">
          <div class="col-md-6">
            <h5 class="card-txt"><b>Plant Code</b></h5>
          </div>
          <div class="col-md-6 text-right">
            <h4 class="card-txt">{{ item.plant }}</h4>
          </div>
        </div>

        <div class="row" v-if="settings.shipping == true">
          <div class="col-md-6">
            <h5 class="card-txt"><b>Order ID</b></h5>
          </div>
          <div class="col-md-6 text-right">
            <h4 class="card-txt">{{ item.po_number }}</h4>
          </div>
        </div>

        <div class="row" v-if="settings.shipping == true">
          <div class="col-md-6">
            <h5 class="card-txt"><b>Shipping Method</b></h5>
          </div>
          <div class="col-md-6 text-right">
            <h4 class="card-txt">{{ item.do_data[0]?item.do_data[0].ship_via:'-' }}</h4>
          </div>
        </div>
        <div class="row" v-if="settings.category == true">
          <div class="col-md-6">
            <h5 class="card-txt"><b>Category</b></h5>
          </div>
          <div class="col-md-6 text-right">
            <h4 class="card-txt">{{ item.so_category_type }}</h4>
          </div>
        </div>
        <div class="row" v-if="settings.action_date == true">
          <div class="col-md-4">
            <h5 class="card-txt"><b>Date</b></h5>
          </div>
          <div class="col-md-8 text-right">
            <h4 class="card-txt">{{ item.datetime }} {{ item.datetime_region }}</h4>
          </div>
        </div>
        <div class="row" v-if="settings.timer == true">
          <div class="col-md-4">
            <h5 class="card-txt">
              <b>Time</b>
              <span
                class="iconify"
                data-icon="bi:clock-history"
                data-inline="false"
              ></span>
            </h5>
          </div>
          <div class="col-md-8 text-right">
            <h4
              :class="{
                timeout: checkTimeout(diffTime(item.datetime)),
                'card-txt': true,
              }"
            >
              <span>{{ timeUp(diffTime(item.datetime)) }}</span>
            </h4>
          </div>
        </div>
        <div class="row" v-if="settings.item == true">
          <div class="col-12">
            <table class="table table-items">
              <thead>
                <tr>
                  <th class="pl-0 ml-0 mr-0 pr-0 m-0 p-0">DO No.</th>
                  <th class="text-right pl-0 ml-0 mr-0 pr-0 m-0 p-0">GI No.</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="it in item.do_data" v-bind:key="it.product_id">
                  <td class=" pl-0 ml-0 mb-0 pb-0">{{ it.do_number }}</td>
                  <td class="text-right pl-0 ml-0 mr-0 pr-0 mb-0 pb-0">{{ it.gi_number }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div
              class="row"
              v-for="(it,i) in item.item"
              v-bind:key="i"
            >
              <div class="col-md-9">
                <h5 class="item-name">{{ it.item_name }}</h5>
              </div>
              <div class="col-md-3">
                <h5 class="text-right item-name">{{ it.qty }} {{ it.unit }}</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import mixin from "./mixin";
import { mapState } from "vuex";

export default {
  mixins: [mixin],
  props: ["status"],
  computed: {
    ...mapState(["data_order", "settings"]),
    items() {
      let _this = this;
      return this.data_order.detail.filter((value) => {
        return _this.status == value.so_status;
      });
    },
  },
  methods: {
    classCategory(category) {
      if (category == "B2B") {
        return "btb";
      } else if (category == "B2C") {
        return "btc";
      }
      return "";
    },
    diffTime(datetime) {
      let d1 = new Date(datetime);
      let d2 = new Date();

      var diff = (d2.getTime() - d1.getTime()) / 1000;
      diff /= 60;
      return Math.abs(Math.round(diff)) * 60;
    },
    checkTimeout(time) {
      if (time > 3600) {
        return true;
      }
      return false;
    },
    timeUp(d) {
      d = Number(d);
      var h = Math.floor(d / 3600);
      var m = Math.floor((d % 3600) / 60);
      var s = Math.floor((d % 3600) % 60);

      var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
      var mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes ") : "";
      // var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
      return hDisplay + mDisplay;
    },
  },
  watch: {
    items(val) {},
  },
};
</script>