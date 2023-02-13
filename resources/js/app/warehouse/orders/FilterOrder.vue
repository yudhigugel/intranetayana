<template>
  <div>
    <div class="box-panel">
      <h5 class="mb-3">Group Code</h5>
      <div
        class="row"
        v-for="(ite, i) in orders.plant"
        v-bind:key="ite.group_code"
      >
        <div class="col-md-12">
          <div>
            <label class="centang"
              ><b>{{ ite.group_name }}({{ ite.group_code }})</b>
              <input
                type="checkbox"
                :name="ite.group_code"
                @change="changeGroupCode(ite.group_code, i, $event)"
              />
              <span class="checkmark"></span>
            </label>
          </div>

          <div
            class="row mb-3"
            v-for="item in plants"
            v-bind:key="item.group_code"
          >
            <div class="col-md-12" v-if="item.group_code == ite.group_code">
              <div class="row">
                <div
                  class="col-md-12"
                  v-for="item2 in item.company_code_list"
                  v-bind:key="item2.company_code"
                >
                  <p class="t-12" style="border-bottom: 1px solid #e0e0e0">
                    <b>
                      {{ item2.company_code }}
                    </b>
                  </p>
                  <div class="row">
                    <div
                      class="col-md-3"
                      v-for="(item3, i3) in item2.plant_list"
                      v-bind:key="item3.plant_id"
                    >
                      <label class="centang"
                        >{{ item3.plant_id }}
                        <input
                          type="checkbox"
                          :name="item3.plant_id"
                          @change="pickPlant(item3.plant_id, i3, $event)"
                        />
                        <span class="checkmark"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Loading />
    </div>

    <div
      class="box-panel"
      v-if="checkPlantShow('PMN') && orders.location_major"
    >
      <h5 class="mb-3">Location</h5>
      <div class="row">
        <div class="col-md-12">
          <div>
            <label
              class="centang"
              v-for="loc in orders.location_major"
              v-bind:key="loc"
              >{{ loc }}
              <input type="checkbox" />
              <span class="checkmark"></span>
            </label>
          </div>
          <div class="form-group">
            <a href="#" @click="moreLocations()">More locations...</a>
          </div>
        </div>
      </div>
    </div>

    <div class="box-panel" v-if="checkPlantShow('PMN') && orders.category">
      <h5 class="mb-3">Category</h5>
      <div class="row">
        <div class="col-md-12">
          <label
            class="centang"
            v-for="category in orders.category"
            v-bind:key="category"
            >{{ category }}
            <input type="checkbox" />
            <span class="checkmark"></span>
          </label>
        </div>
      </div>
    </div>

    <div class="box-panel" v-if="checkPlantShow('PMN') && orders.sender">
      <h5 class="mb-3">Shipping</h5>
      <div class="row">
        <div class="col-md-12">
          <label
            class="centang"
            v-for="sender in orders.sender"
            v-bind:key="sender"
            >{{ sender }}
            <input type="checkbox" />
            <span class="checkmark"></span>
          </label>
        </div>
      </div>
    </div>

    <!-- <div class="row" v-if="plant_selected.length >= 1">
      <div class="col-md-12">
        <div class="float-right">
          <button class="btn btn-danger">Reset</button>
          <button class="btn btn-primary">Apply</button>
        </div>
      </div>
    </div> -->

    <modal name="modalLocation" width="50%" height="auto">
      <div class="my-modal-container">
        <div class="my-modal-header">
          <div class="row pb-3 pt-3" style="border-bottom: 1px solid #e0e0e0">
            <div class="col-md-12">
              <div class="row d-flex align-items-center">
                <div class="col-md-1">
                  <label for="">Locations</label>
                </div>
                <div class="col-md-10">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <span
                          class="iconify"
                          data-icon="bi:search"
                          data-inline="false"
                        ></span>
                      </span>
                    </div>
                    <input type="text" class="form-control" v-model="search" />
                  </div>
                </div>
                <div class="col-md-1 text-right">
                  <a @click="hideLocation()" href="#" style="font-size: 1.2rem"
                    ><span
                      class="iconify"
                      data-icon="clarity:times-line"
                      data-inline="false"
                    ></span
                  ></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="my-modal-content">
          <div class="row">
            <div
              class="col-md-12"
              v-for="abjad in searchAbjad()"
              v-bind:key="abjad"
            >
              <h5>{{ abjad }}</h5>
              <div class="row">
                <div
                  class="col-md-4 mb-2"
                  v-for="(list, i2) in searchLocation(abjad)"
                  v-bind:key="i2"
                >
                  <label class="centang">
                    {{ list.city }}
                    <input
                      type="checkbox"
                      @change="pmnPickLocation(list.city, i2, $event)"
                      :checked="pmn_location_selected.includes(list.city)"
                    />
                    <span class="checkmark"></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="my-modal-footer">
          <div class="row">
            <div class="col-md-12">
              <div class="float-right">
                <button type="button" class="btn btn-outline-primary">
                  Reset
                </button>
                <button type="button" class="btn btn-primary">Apply</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
import Vue from "vue";
import VModal from "vue-js-modal";
import mixin from "./mixin";
import { mapState, mapMutations } from "vuex";
import { List, Set, Map } from "immutable";
import Loading from "@app/components/Loading";

Vue.use(VModal);

export default {
  mixins: [mixin],
  components: {
    Loading,
  },
  data() {
    return {
      search: "",
      group_code: [""],
      plant_check: [],
    };
  },
  methods: {
    ...mapMutations([
      "getOrders",
      "setLocation",
      "addCompanySeleted",
      "removeCompanySeleted",
      "resetCompanySelected",
      "setPMNSelected",
      "removePMNSelected",
    ]),
    moreLocations() {
      this.$modal.show("modalLocation");
    },
    hideLocation() {
      this.$modal.hide("modalLocation");
    },
    searchAbjad() {
      let _this = this;
      let listAbjad = [];
      if (this.orders.location_all) {
        this.orders.location_all.map(function (item) {
          listAbjad.push(item.abjad);
        });
        if (_this.search) {
          return listAbjad.filter(function (item) {
            return item === _this.search.charAt(0);
          });
        }
      }

      return listAbjad;
    },
    searchLocation(abjad) {
      let _this = this;
      let search = this.search;
      let listAll = [];
      if (this.orders.location_all) {
        this.orders.location_all.map(function (item) {
          let listKota = item.list.map(function (listKota) {
            listAll.push(listKota);
          });
        });
        if (search) {
          return listAll.filter(function (item) {
            return (
              item.kota.match(new RegExp(search, "i")) &&
              item.kota.charAt(0) === abjad
            );
          });
        }
        return listAll.filter((item) => item.kota.charAt(0) == abjad);
      }
      return [];
    },
    changeGroupCode(group_code, i, e) {
      let checked = e.target.checked;
      let index = this.group_code.indexOf(i);
      if (checked == true) {
        this.group_code.push(group_code);
      } else {
        this.group_code.splice(index, 1);
        if (this.group_code.length <= 1) {
          this.resetCompanySelected();
        }
      }
    },
    pickPlant(plant_id, i, e) {
      let checked = e.target.checked;
      let index = this.plant_selected.indexOf(i);
      if (checked == true) {
        this.addCompanySeleted(plant_id);
      } else {
        this.removeCompanySeleted(index);
      }
    },
    pmnPickLocation(location, i, e) {
      let checked = e.target.checked;
      let index = this.pmn_location_selected.indexOf(i);
      if (checked == true) {
        this.setPMNSelected(location);
      } else {
        this.removePMNSelected(index);
      }
    },
  },
  computed: {
    ...mapState([
      "orders",
      "location_all",
      "plant_selected",
      "pmn_location_selected",
    ]),
    plants() {
      let _this = this;
      if (this.group_code.length > 0) {
        return this.orders.plant.filter(function (item) {
          return _this.group_code.includes(item.group_code);
        });
      }
      return [];
    },
  },
  watch: {
    search(val) {
      this.search = val.toUpperCase();
      if (val) {
        this.searchAbjad();
      }
    },
    orders(val) {
      if (val) {
        this.$root.$emit("loading", false);
      }
    },
  },
  mounted() {
    this.getOrders();
    this.$root.$emit("loading", true);
  },
};
</script>