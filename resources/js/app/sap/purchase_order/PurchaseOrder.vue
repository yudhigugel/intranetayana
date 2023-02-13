<template>
  <div>
    <Loading :full="true" />
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="">Company Code</label>
          <select name="" id="" class="form-control" v-model="code">
            <option
              v-for="(cd, index) in codes"
              :value="cd.BUKRS"
              v-bind:key="index"
            >
              {{ cd.BUKRS }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="row" v-if="code">
      <div class="col-md-12">
        <DatatableIntranet
          id_table="tablePO"
          custom_class="w-100 text-left"
          :headerTable="datatable.header"
          method="GET"
          :url="dt_url"
          :data="datatable.data"
          :server_side="false"
        />
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Loading from "@app/components/Loading";
// import DatatableIntranet from '@bit/biznetrepo.intranet.datatable-intranet';

export default {
  components: {
    DatatableIntranet,
    Loading,
  },
  data() {
    return {
      codes: [],
      code: "",
      datatable: {
        load: false,
        header: [
          "Line No",
          "Po Number",
          "Doc Type",
          "Create Date",
          "Plant",
          "Vendor Number",
          "Vendor Name",
          "Req. Number",
          "DESCRIPTION",
        ],
        data: [
          {
            data: "LINE_NO",
          },
          {
            data: "EBELN",
            render(data, type, full, meta) {
              return `<a class="btn btn-link p-0 m-0" href="#/${data}">${data}</a>`;
            },
          },
          {
            data: "BSART",
          },
          {
            data: "AEDAT",
          },
          {
            data: "WERKS",
          },
          {
            data: "LIFNR",
          },
          {
            data: "NAME1",
          },
          {
            data: "BEDNR",
          },
          {
            data: "DESCRIPTION",
          },
        ],
      },
    };
  },
  computed: {
    dt_url() {
      return window.backend_url + "/v1/sap/po?company_code=" + this.code;
    },
  },
  methods: {
    getCompanyCode() {
      let _this = this;
      this.$root.$emit("loading", true);
      let request = axios.get(`${window.backend_url}/v1/sap/company-code`);
      request.then((res) => {
        _this.codes = res.data.IT_COMPANY;
        _this.$root.$emit("loading", false);
        if (_this.codes) {
          _this.code = _this.codes[0].BUKRS;
        }
      });
    },
  },
  watch: {
    code(val) {
      this.code = val;
      this.$root.$emit(
        "filter_table",
        window.backend_url + "/v1/sap/po?company_code=" + this.code
      );
    },
  },
  mounted() {
    this.getCompanyCode();
    this.$root.$emit("loading", false);
  },
};
</script>