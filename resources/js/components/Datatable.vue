<template>
  <div class="table-responsive pt-3">
    <table
      :class="'table table-bordered display nowrap ' + custom_class"
      :id="idTable"
    >
      <thead>
        <tr>
          <th
            nowrap
            v-for="(column, index) in headerTable"
            v-bind:key="index"
            :class="'column' + index"
          >
            <span v-html="column"></span>
          </th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</template>
<script>
import Vue from "vue";
import $ from "jquery";
import "datatables.net";
import "datatables.net-bs4";
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import swal from "sweetalert";
import Swal2 from "sweetalert2";

export default {
  props: [
    "headerTable",
    "url",
    "headers",
    "data",
    "server_side",
    "method",
    "columnDefs",
    "order",
    "custom_class",
    "id",
    "id_table",
    "dom",
    "buttons",
    "lengthMenu",
    "select",
    "searchable",
    "rowsGroup",
    "lengthChange",
    "scrollY",
    "scrollX",
  ],
  data() {
    let _this = this;
    return {
      datatable: {
        processing: true,
        language: {
          processing:
            '<div class="bar-loader"><span></span><span></span><span></span><span></span></div>',
        },
        serverSide: _this.server_side ? _this.server_side : true,
        scrollX: true,
        ajax: {
          url: _this.url,
          method: _this.method,
          headers: _this.headers,
          dataType: "json",
          error: function (jqXHR, ajaxOptions, thrownError) {
            swal({
              title: "Error",
              text: jqXHR.responseJSON.message,
              icon: "warning",
              button: "Ok",
            });
          },
          dataSrc: function (json) {
            _this.$root.$emit("data_datatable", json);
            if (json.code == 200) {
              return json.data;
            } else if (
              json.code === 404 ||
              json.code === 400 ||
              json.success === 400
            ) {
              return [];
            } else if (json.code == 401) {
              Swal2.fire({
                title: "Error",
                text: json.message,
                allowOutsideClick: false,
                icon: "error",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "OK",
              }).then((result) => {
                if (result.value) {
                  document.location = "/";
                }
              });
            } else if (
              json.code == -5001 ||
              json.code == -5002 ||
              json.code == -5003
            ) {
              swal({
                title: "Request Error",
                text: `${json.message}`,
                icon: "warning",
                button: "Ok",
              });
            } else {
              return json.data;
            }
          },
        },
        processing: true,
        serverSide: false,
        order: _this.order,
        select: _this.select,
        rowsGroup: _this.rowsGroup ? _this.rowsGroup : [],
      },
    };
  },
  components: {},
  methods: {},
  watch: {},
  computed: {
    dt() {
      var _this = this;
      return $("#" + this.idTable).DataTable(_this.datatable);
    },
    load() {
      return stores.state.loadTable;
    },
    idTable() {
      if (this.id) {
        return id;
      } else if (this.id_table) {
        return this.id_table;
      }
      return "table";
    },
  },
  mounted() {
    var _this = this;
    if (_this.url == "" || _this.url == undefined) {
      _this.datatable.ajax = {};
    }
    if (_this.data == "" || _this.data == undefined) {
      _this.datatable.ajax = {};
    }
    if (_this.server_side == true) {
      _this.datatable.columns = _this.data;
      _this.datatable.serverSide = true;
    } else {
      _this.datatable.columns = _this.data;
    }

    if (this.dom) {
      this.datatable.dom = this.dom;
    }

    if (this.buttons) {
      this.datatable.buttons = this.buttons;
    }

    if (this.lengthMenu) {
      this.datatable.lengthMenu = this.lengthMenu;
    }

    if (this.searchable == false) {
      this.datatable.searching = false;
    }

    if (this.rowsGroup) {
      this.datatable.rowsGroup = this.rowsGroup;
    }

    if (this.scrollY) {
      this.datatable.scrollY = this.scrollY;
    }

    if (this.scrollX) {
      this.datatable.scrollX = this.scrollX;
    }

    if (this.columnDefs) {
      this.datatable.columnDefs = this.columnDefs;
    }

    $(document).ready(function () {
      var dt = _this.dt;
      $.fn.dataTable.ext.errMode = "none";
    });

    this.$root.$on("load_table", function () {
      _this.dt.ajax.reload(null, false);
    });

    this.$root.$on("filter_table", function (new_url) {
      _this.dt.ajax.url(new_url).load();
    });
  },
};
</script>