<template>
  <div>
    <Loading :full="true" />
    <div class="row">
      <div class="col-md-12">
        <a href="#/" class="btn btn-primary float-right">Back</a>
      </div>
    </div>
    <br />
    <div class="row">
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">PO_NUMBER</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.PO_NUMBER"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">COMP_CODE</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.COMP_CODE"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">DOC_TYPE</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.DOC_TYPE"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">STATUS</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.STATUS"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">ITEM_INTVL</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.ITEM_INTVL"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">VENDOR</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.VENDOR"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">CURRENCY</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.CURRENCY"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">DOC_DATE</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.DOC_DATE"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">PO_REL_IND</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.PO_REL_IND"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">REL_STATUS</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.REL_STATUS"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">REASON_CANCEL</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.REASON_CANCEL"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">CREAT_DATE</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.CREAT_DATE"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">CREATED_BY</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header.CREATED_BY"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">ADDR_NO</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.ADDR_NO"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">NAME</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.NAME"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">CITY</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.CITY"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">POSTL_COD1</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.POSTL_COD1"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">STREET</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.STREET"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">COUNTRY</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.COUNTRY"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">REGION</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.REGION"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 form-group mt-1 mb-1">
        <div class="row d-flex align-items-center">
          <div class="col-md-2">
            <label for class="float-right">TEL1_NUMBR</label>
          </div>
          <div class="col-md-10">
            <input
              type="text"
              class="form-control rounded"
              readonly
              v-model="form.header2.TEL1_NUMBR"
            />
          </div>
        </div>
      </div>
    </div>
    <br />
    <hr />
    <div class="row">
      <div class="col-md-12 table-responsive">
        <h5 style="text-transform: uppercase">PO History</h5>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>PO_ITEM</th>
              <th>SERIAL_NO</th>
              <th>PROCESS_ID</th>
              <th>DOC_YEAR</th>
              <th>MAT_DOC</th>
              <th>MATDOC_ITM</th>
              <th>HIST_TYPE</th>
              <th>MOVE_TYPE</th>
              <th>PSTNG_DATE</th>
              <th>QUANTITY</th>
              <th>VAL_LOCCUR</th>
              <th>VAL_FORCUR</th>
              <th>CURRENCY</th>
              <th>VAL_TYPE</th>
              <th>MATERIAL</th>
              <th>PLANT</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(history, index) in table.po_history" v-bind:key="index">
              <td>{{ history.PO_ITEM }}</td>
              <td>{{ history.SERIAL_NO }}</td>
              <td>{{ history.PROCESS_ID }}</td>
              <td>{{ history.DOC_YEAR }}</td>
              <td>{{ history.MAT_DOC }}</td>
              <td>{{ history.MATDOC_ITM }}</td>
              <td>{{ history.HIST_TYPE }}</td>
              <td>{{ history.MOVE_TYPE }}</td>
              <td>{{ history.PSTNG_DATE }}</td>
              <td>{{ history.QUANTITY }}</td>
              <td>{{ history.VAL_LOCCUR }}</td>
              <td>{{ history.VAL_FORCUR }}</td>
              <td>{{ history.CURRENCY }}</td>
              <td>{{ history.VAL_TYPE }}</td>
              <td>{{ history.MATERIAL }}</td>
              <td>{{ history.PLANT }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br />
    <hr />
    <div class="row">
      <div class="col-md-12 table-responsive">
        <h5 style="text-transform: uppercase">PO Item</h5>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>PO_ITEM</th>
              <th>SHORT_TEXT</th>
              <th>MATERIAL</th>
              <th>PLANT</th>
              <th>TRACKINGNO</th>
              <th>MATL_GROUP</th>
              <th>INFO_REC</th>
              <th>QUANTITY</th>
              <th>PO_UNIT</th>
              <th>NET_PRICE</th>
              <th>TAX_CODE</th>
              <th>INFO_UPD</th>
              <th>VAL_TYPE</th>
              <th>PRICE_DATE</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in table.po_item" v-bind:key="index">
              <td>{{ item.PO_ITEM }}</td>
              <td>{{ item.SHORT_TEXT }}</td>
              <td>{{ item.MATERIAL }}</td>
              <td>{{ item.PLANT }}</td>
              <td>{{ item.TRACKINGNO }}</td>
              <td>{{ item.MATL_GROUP }}</td>
              <td>{{ item.INFO_REC }}</td>
              <td>{{ item.QUANTITY }}</td>
              <td>{{ item.PO_UNIT }}</td>
              <td>{{ item.NET_PRICE }}</td>
              <td>{{ item.TAX_CODE }}</td>
              <td>{{ item.INFO_UPD }}</td>
              <td>{{ item.VAL_TYPE }}</td>
              <td>{{ item.PRICE_DATE }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br />
    <hr />
    <div class="row">
      <div class="col-md-12 table-responsive">
        <h5 style="text-transform: uppercase">PO SCHEDULE</h5>
        <table class="table table-striped">
          <thead>
            <th>PO_ITEM</th>
            <th>SCHED_LINE</th>
            <th>DEL_DATCAT_EXT</th>
            <th>DELIVERY_DATE</th>
            <th>QUANTITY</th>
            <th>DELIV_TIME</th>
            <th>STAT_DATE</th>
            <th>PREQ_ITEM</th>
            <th>PO_DATE</th>
          </thead>
          <tbody>
            <tr v-for="(item, index) in table.schedule" v-bind:key="index">
              <td>{{ item.PO_ITEM }}</td>
              <td>{{ item.SCHED_LINE }}</td>
              <td>{{ item.DEL_DATCAT_EXT }}</td>
              <td>{{ item.DELIVERY_DATE }}</td>
              <td>{{ item.QUANTITY }}</td>
              <td>{{ item.DELIV_TIME }}</td>
              <td>{{ item.STAT_DATE }}</td>
              <td>{{ item.PREQ_ITEM }}</td>
              <td>{{ item.PO_DATE }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Swal from "sweetalert2";
import Loading from "@app/components/Loading";

export default {
  components: {
    Loading,
  },
  data() {
    return {
      form: {
        header: {},
        header2: {},
      },
      table: {
        po_history: [],
        po_item: [],
        schedule: [],
      },
    };
  },
  mounted() {
    let param = this.$route.params.id;
    let _this = this;
    let request = axios.get(`${window.backend_url}/v1/sap/po/${param}`);
    request.then((res) => {
      if (res.data) {
        _this.$root.$emit("loading", false);
        if (res.data.RETURN.length > 0) {
          Swal.fire({
            title: res.data.RETURN[0].MESSAGE,
            icon: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "OK",
            allowOutsideClick: false,
          }).then((result) => {
            if (result.isConfirmed) {
              _this.$router.push("/");
            }
          });
        }
        _this.form.header = res.data.POHEADER;
        _this.form.header2 = res.data.POADDRDELIVERY[0];
        _this.table.po_history = res.data.POHISTORY;
        _this.table.po_item = res.data.POITEM;
        _this.table.schedule = res.data.POSCHEDULE;
      }
    });
  },
};
</script>