<template>
  <div>
    <div class="row">
      <div class="col-md-12">
          <Loading :full="true"/>
        <ul class="events">
          <li class="row" v-for="(history, i) in histories" v-bind:key="i">
            <time :class="{'col-md-5':true, 'text-right':true,'not_found':history.booking_status==='no_driver','completed':history.booking_status==='delivered'}" :datetime="history.time"
              >{{ history.date }}, {{ history.time }}</time
            >
            <span class="col-md-7">
              <div>
                <div class="row">
                  <div class="col-md-12">
                    <strong>{{ history.booking_status_text }}</strong>
                  </div>
                </div>
                <div class="row" v-if="history.driver_name">
                  <div class="col-md-12">
                    Driver Name: {{history.driver_name}}
                  </div>
                </div>
                <div class="row" v-if="history.driver_name">
                  <div class="col-md-12">
                    Driver Phone: {{history.driver_phone}}
                  </div>
                </div>
                <div class="row" v-if="history.driver_name">
                  <div class="col-md-12">
                    Receiver Name: {{history.receiver_name}}
                  </div>
                </div>
              </div>
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { rest } from "@app/services/rest";
import Loading from "@app/components/Loading";

export default {
  props: ["gi_number"],
  components:{
      Loading
  },
  data() {
    return {
      histories: [],
    };
  },
  mounted() {
    let _this = this;
    this.$root.$emit("loading",true);
    let request = rest.get(
      `${window.backend_url}/v1/warehouse/gokilat/history/order/${this.gi_number}`
    );
    request.then((res) => {
      if (res.data.code == 200) {
        _this.histories = res.data.data;
      }
      this.$root.$emit("loading",false);
    });
  },
};
</script>

<style scoped>
.events li {
  display: flex;
}

.events time {
  position: relative;
  color: rgb(158, 156, 156);
  padding: 0 1.5em;
}

.events time::after {
  content: "";
  position: absolute;
  z-index: 2;
  right: 0;
  top: 0;
  transform: translateX(50%);
  border-radius: 50%;
  background: #fff;
  border: 3px #42A5F5 solid;
  width: 1.4em;
  height: 1.4em;
}

.events .completed::after {
  content: "";
  position: absolute;
  z-index: 2;
  right: 0;
  top: 0;
  transform: translateX(50%);
  border-radius: 50%;
  background: #fff;
  border: 3px #57b513 solid;
  width: 1.4em;
  height: 1.4em;
}

.events .not_found::after {
  content: "";
  position: absolute;
  z-index: 2;
  right: 0;
  top: 0;
  transform: translateX(50%);
  border-radius: 50%;
  background: #fff;
  border: 3px red solid;
  width: 1.4em;
  height: 1.4em;
}

.events span {
  padding: 0 1.5em 1.5em 1.5em;
  position: relative;
}

.events span::before {
  content: "";
  position: absolute;
  z-index: 1;
  left: 0;
  height: 100%;
  border-left: 1px #ccc solid;
}

.events strong {
  display: block;
  font-weight: bolder;
}

.events {
  margin: 1em;
  width: 50%;
}
.events,
.events *::before,
.events *::after {
  box-sizing: border-box;
  font-family: arial;
}
</style>