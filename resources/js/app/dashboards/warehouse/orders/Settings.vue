<template>
  <div>
    <p>Screen Mode</p>
    <hr />
    <div class="row">
      <div class="col-md-2">
        <label class="centang"
          ><b>Light Mode</b>
          <input
            type="checkbox"
            @click="mode('light')"
            :checked="settings.mode == 'light'"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Dark Mode</b>
          <input
            type="checkbox"
            :checked="settings.mode == 'dark'"
            @click="mode('dark')"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Biznet Mode</b>
          <input
            type="checkbox"
            :checked="settings.mode == 'biznet'"
            @click="mode('biznet')"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Purple Mode</b>
          <input
            type="checkbox"
            :checked="settings.mode == 'purple'"
            @click="mode('purple')"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Girly Mode</b>
          <input
            type="checkbox"
            :checked="settings.mode == 'girly'"
            @click="mode('girly')"
          />
          <span class="checkmark"></span>
        </label>
      </div>
    </div>
    <hr />
    <p>Views</p>
    <hr />
    <div class="row">
      <div class="col-md-2">
        <label class="centang"
          ><b>Shipping Method</b>
          <input
            type="checkbox"
            @change="shipping($event)"
            :checked="settings.shipping"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Category</b>
          <input
            type="checkbox"
            @change="category($event)"
            :checked="settings.category"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Date</b>
          <input
            type="checkbox"
            @change="action_date($event)"
            :checked="settings.action_date"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Time</b>
          <input
            type="checkbox"
            @change="timer($event)"
            :checked="settings.timer"
          />
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="col-md-2">
        <label class="centang"
          ><b>Items</b>
          <input
            type="checkbox"
            @change="items($event)"
            :checked="settings.item"
          />
          <span class="checkmark"></span>
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import mixin from "./mixin";
import { mapState, mapMutations } from "vuex";
export default {
  mixins: [mixin],
  computed: {
    ...mapState(["settings"]),
  },
  methods: {
    ...mapMutations([
      "setMinimize",
      "setDarkmode",
      "setShipping",
      "setCategory",
      "setDate",
      "setTimer",
      "setItem",
      "setMode",
    ]),
    minimize(e) {
      let checked = e.target.checked;

      if (checked) {
        this.setMinimize(true);
      } else {
        this.setMinimize(false);
      }
    },
    darkmode(e) {
      let checked = e.target.checked;

      if (checked) {
        this.setDarkmode(true);
      } else {
        this.setDarkmode(false);
      }
    },
    mode(mode) {
      this.setMode(mode);
      localStorage.setItem("mode",mode);
    },
    shipping(e) {
      let checked = e.target.checked;

      if (checked) {
        this.setShipping(true);
      } else {
        this.setShipping(false);
      }
    },
    category(e) {
      let checked = e.target.checked;

      if (checked) {
        this.setCategory(true);
      } else {
        this.setCategory(false);
      }
    },
    action_date(e) {
      let checked = e.target.checked;

      if (checked) {
        this.setDate(true);
      } else {
        this.setDate(false);
      }
    },
    timer(e) {
      let checked = e.target.checked;

      if (checked) {
        this.setTimer(true);
      } else {
        this.setTimer(false);
      }
    },
    items(e) {
      let checked = e.target.checked;

      if (checked) {
        this.setItem(true);
      } else {
        this.setItem(false);
      }
    },
  },
};
</script>