<template>
  <div class="layout-container">
    <div class="left container-item">
      <FilterOrder />
    </div>
    <div class="right container-item">
      <Content />
    </div>
  </div>
</template>
<script>
import Content from "./Content";
import FilterOrder from "./FilterOrder";

export default {
  components: {
    Content,
    FilterOrder,
  },
};
</script>