<template>
  <div class="d-flex">
    <h3 class="mr-2">{{ date_now }}</h3>
    <h3>-</h3>
    <h3 class="ml-2">{{ clock }}</h3>
  </div>
</template>

<script>
export default {
  data() {
    return {
      clock: "",
    };
  },
  computed: {
    date_now() {
      const days = [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
      ];
      const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      var d = new Date();
      let day = days[d.getDay()-1];
      var date = d.getDate();
      var month = d.getUTCMonth();
      var year = d.getFullYear();
      return `${day}, ${date} ${monthNames[month]} ${year}`;
    },
  },
  methods: {
    startTime() {
      var _this = this;
      setInterval(() => {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = _this.checkTime(m);
        s = _this.checkTime(s);
        _this.clock = h + ":" + m + ":" + s;
      }, 500);
    },
    checkTime(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    },
  },
  mounted() {
    this.startTime();
  },
};
</script>