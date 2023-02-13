<template>
  <div>
    <Loading :full="true" />
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h4>Primamedix Sales Order</h4>
      </div>
      <hr />
      <div>
        <button class="btn btn-primary" @click="refresh()">
          <span
            class="iconify"
            data-icon="el:refresh"
            data-inline="false"
          ></span>
        </button>
      </div>
    </div>

    <div class="row" v-if="grand_total">
      <div class="col-md-6">
        <h4>Grand Total IDR</h4>
        <div class="row">
          <div class="col-md-4">
            <label for="">Subtotal</label>
            <input
              type="text"
              name=""
              id=""
              readonly
              class="form-control text-right"
              :value="
                grand_total.idr.subtotal
                  ? new Intl.NumberFormat('id-ID').format(
                      grand_total.idr.subtotal
                    )
                  : 0
              "
            />
          </div>
          <div class="col-md-4">
            <label for="">Tax</label>
            <input
              type="text"
              name=""
              id=""
              readonly
              class="form-control text-right"
              :value="
                grand_total.idr.tax
                  ? new Intl.NumberFormat('id-ID').format(grand_total.idr.tax)
                  : 0
              "
            />
          </div>
          <div class="col-md-4">
            <label for="">Total</label>
            <input
              type="text"
              name=""
              id=""
              readonly
              class="form-control text-right"
              :value="
                grand_total.idr.total
                  ? new Intl.NumberFormat('id-ID').format(grand_total.idr.total)
                  : 0
              "
            />
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <h4>Grand Total USD</h4>
        <div class="row">
          <div class="col-md-4">
            <label for="">Subtotal</label>
            <input
              type="text"
              name=""
              id=""
              readonly
              class="form-control text-right"
              :value="
                grand_total.usd.subtotal
                  ? new Intl.NumberFormat('id-ID').format(
                      grand_total.usd.subtotal
                    )
                  : 0
              "
            />
          </div>
          <div class="col-md-4">
            <label for="">Tax</label>
            <input
              type="text"
              name=""
              id=""
              readonly
              class="form-control text-right"
              :value="
                grand_total.usd.tax
                  ? new Intl.NumberFormat('id-ID').format(grand_total.usd.tax)
                  : 0
              "
            />
          </div>
          <div class="col-md-4">
            <label for="">Total</label>
            <input
              type="text"
              name=""
              id=""
              readonly
              class="form-control text-right"
              :value="
                grand_total.usd.total
                  ? new Intl.NumberFormat('id-ID').format(grand_total.usd.total)
                  : 0
              "
            />
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <Datatable
          id_table="tablePO"
          custom_class="w-100 text-left table-striped"
          :headerTable="datatable.header"
          method="GET"
          :url="datatable.url"
          :data="datatable.data"
          :server_side="false"
          :scrollX="true"
          :scrollY="400"
          :lengthMenu="[50, 100, 200]"
          :columnDefs="datatable.columnDefs"
        />
      </div>
    </div>

    <modal name="myModal" width="50%" height="auto">
      <div class="p-4">
        <div class="row">
          <div class="col-md-12">
            <div class="d-flex justify-content-between">
              <h4 class="p-0 m-0">
                GOSEND Tracking History / GI {{ modal.gi_number }}
              </h4>
              <span style="cursor: pointer" @click="closeModal()">x</span>
            </div>
            <hr class="mt-3" />
            <div style="height: 85vh; overflow: scroll">
              <TrackingHistory
                :gi_number="modal.gi_number"
                v-if="modal.type == 'trackingHistory'"
              />
            </div>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
import Vue from "vue";
import Datatable from "@app/components/Datatable";
import mixin from "./../mixin.js";
import Swal from "sweetalert2";
import rest from "@app/services/rest";
import Loading from "@app/components/Loading";
import VModal from "vue-js-modal";
import TrackingHistory from "./pmn/TrackingHistory";
Vue.use(VModal);
import _ from "lodash";

export default {
  mixins: [mixin],
  components: {
    Datatable,
    Loading,
    TrackingHistory,
  },
  data() {
    return {
      grand_total: {
        idr: {},
        usd: {},
      },
      modal: {
        type: "",
        gi_number: "",
      },
      datatable: {
        url: `${window.backend_url}/v1/warehouse/orders`,
        load: false,
        columnDefs: [
          {
            width: "10%",
            sortable: true,
            searchable: false,
            visible: false,
            type: "num",
            targets: [0],
          },
        ],
        header: [
          "",
          "COMP ID",
          "PLANT ID",
          "SO NO",
          "SO TYPE",
          "SO DATE",
          "CUSTOMER PO NO",
          "DO NO",
          "GI NO",
          "SHIP VIA",
          "SHIP STATUS",
          "PROD ID",
          "PROD DESC",
          "QTY",
          "UNIT",
          "CURRENCY",
          "UNIT PRICE",
          "UNIT DISC",
          "SUB TOTAL",
          "TAX",
          "TOTAL",
        ],
        data: [
          {
            data: "so_status_id",
          },
          {
            data: "company_code",
          },
          {
            data: "plant",
          },
          {
            data: "so_number",
          },
          {
            data: "so_category_type",
          },
          {
            data: "datetime",
            render: (data, type, full, meta) => {
              return `${data} ${full.datetime_region}`;
            },
          },
          {
            data: "po_number",
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].do_number ? data[index].do_number : "-"
                }</td></tr>`;
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length - 1;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0"></td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].gi_number ? data[index].gi_number : "-"
                }</td></tr>`;
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length - 1;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0"></td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (
                  (data[index].ship_via ? data[index].ship_via : "-") ===
                  "GOSEND"
                ) {
                  element += `<tr">
                <td style="width:10%;" class="text-left pl-0 pr-0"><a href="#" data-gi='${
                  data[index].gi_number ? data[index].gi_number : "-"
                }' class="btn-history btn btn-success">${
                    data[index].ship_via
                  }</a></td></tr>`;
                } else {
                  element += `<tr">
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].ship_via ? data[index].ship_via : "-"
                }</td></tr>`;
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: function (data, type, full, meta) {
              if (full.so_send_status_update == true) {
                return `<span class="d-flex align-items-center">${
                  full.so_send_status
                } <button data-ship_via="${_.head(data).ship_via}" data-gi='${
                  _.head(data).gi_number
                }' class="ml-2 btn-ship-status btn btn-success"><i class="mdi mdi-check-bold"></i></button></span>`;
              }
              return full.so_send_status;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].item[index2].item_code
                    ? data[index].item[index2].item_code
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].item[index2].item_name
                    ? data[index].item[index2].item_name
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].item[index2].qty
                    ? data[index].item[index2].qty
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].item[index2].unit
                    ? data[index].item[index2].unit
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-left pl-0 pr-0">${
                  data[index].item[index2].currency
                    ? data[index].item[index2].currency
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-right pl-0 pr-0">${
                  data[index].item[index2].price_per_unit
                    ? new Intl.NumberFormat("id-ID").format(
                        data[index].item[index2].price_per_unit
                      )
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-right pl-0 pr-0">${
                  data[index].item[index2].discount
                    ? new Intl.NumberFormat("id-ID").format(
                        data[index].item[index2].discount
                      )
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-right pl-0 pr-0">${
                  data[index].item[index2].subtotal
                    ? new Intl.NumberFormat("id-ID").format(
                        data[index].item[index2].subtotal
                      )
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-right pl-0 pr-0">${
                  data[index].item[index2].tax
                    ? new Intl.NumberFormat("id-ID").format(
                        data[index].item[index2].tax
                      )
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
          {
            data: "do_data",
            render: (data, type, full, meta) => {
              var element = "<table class='table nest-table'>";
              for (let index = 0; index < data.length; index++) {
                if (data[index].item) {
                  for (
                    let index2 = 0;
                    index2 < data[index].item.length;
                    index2++
                  ) {
                    element += `<tr>
                <td style="width:10%;" class="text-right pl-0 pr-0">${
                  data[index].item[index2].total
                    ? new Intl.NumberFormat("id-ID").format(
                        data[index].item[index2].total
                      )
                    : "-"
                }</td></tr>`;
                  }
                }
              }
              element += "</table>";
              return element;
            },
          },
        ],
      },
    };
  },
  methods: {
    closeModal() {
      this.$modal.hide("myModal");
    },
    refresh() {
      this.$root.$emit("load_table");
    },
    pickup(status, gi, shipping) {
      let _this = this;

      Swal.fire({
        title: `Are you sure want update status to ${status}?`,
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.isConfirmed) {
          _this.$root.$emit("loading", true);
          let data = {
            status: status,
            gi_number: gi,
            shipping_method: shipping,
          };
          let request = rest.put(
            `${window.backend_url}/v1/warehouse/change-status`,
            data
          );
          request.then((res) => {
            if (res.code == 200) {
              Swal.fire("Success", res.message, "success");
              _this.$root.$emit("load_table");
            } else {
              Swal.fire("Error", res.message, "error");
            }
            _this.$root.$emit("loading", false);
          });
          request.catch((err) => {
            _this.$root.$emit("loading", false);
            if (err.response.status == 404) {
              Swal.fire("Error", "Request not Found", "error");
            } else if (err.response.status == 500) {
              Swal.fire("Error", "Internal Server Error", "error");
            }
          });
          // Swal.fire("Deleted!", "Your file has been deleted.", "success");
        }
      });
    },
  },
  mounted() {
    let _this = this;
    this.$root.$emit("loading", false);

    this.$root.$on("data_datatable", function (data) {
      console.log(data.grand_total);
      if (data.grand_total) {
        _this.grand_total = data.grand_total;
      }
    });

    $("#tablePO tbody").on("click", ".pickup", function () {
      let status = $(this).attr("data-status");
      let gi = $(this).attr("data-gi");
      let shipping = $(this).attr("data-shipping");
      _this.pickup(status, gi, shipping);
    });

    $("#tablePO tbody").on("click", ".btn-ship-status", function () {
      let gi = $(this).attr("data-gi");
      let ship_via = $(this).attr("data-ship_via");

      Swal.fire({
        title: `Are you sure want update status to Done?`,
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
      }).then((result) => {
        if (result.isConfirmed) {
          _this.$root.$emit("loading", true);
          let putUrl = encodeURI(
            window.backend_url +
              "/v1/warehouse/status-change/" +
              gi +
              "/" +
              ship_via
          );
          let request = rest.put(putUrl, { ss: "ss" });
          request.then((res) => {
            if (res.data.code == 200) {
              Swal.fire("Success", res.data.message, "success");
              _this.$root.$emit("load_table");
            } else {
              Swal.fire("Error", res.data.message, "error");
            }
            _this.$root.$emit("loading", false);
          });
          request.catch((err) => {
            _this.$root.$emit("loading", false);
            if (err.response.status == 404) {
              Swal.fire("Error", "Request not Found", "error");
            } else if (err.response.status == 500) {
              Swal.fire("Error", "Internal Server Error", "error");
            }
          });
        }
      });
    });

    $("#tablePO tbody").on("click", ".btn-history", function () {
      let gi = $(this).attr("data-gi");
      _this.modal.gi_number = gi;
      _this.modal.type = "trackingHistory";
      _this.$modal.show("myModal");
    });
  },
};
</script>
<style>
th {
  font-size: 10px !important;
}
#tablePO td {
  font-size: 10px !important;
  vertical-align: text-top;
}
.nest-table td {
  border: none;
  background: none !important;
}
.nest-table tr {
  background-color: none !important;
  background: none !important;
}
</style>