<template>
  <div>
    <li class="nav-item nav-item-first" v-for="menu1 in sortJson" v-bind:key="menu1.menu_id">
      <div class="sidebar-menu-title">
        <i
          v-if="menu1.icon !== '#' || menu1.icon !== '-'"
          :class="menu1.icon"
          style="font-size: 1.4rem;"
        ></i> &nbsp;
        <span>{{menu1.menu_name}}</span>
      </div>

      <ul class="nav submenu-wrapper" v-if="menu1.child">
        <Menu
          v-for="menu in menu1.child"
          :name="menu.menu_name"
          :url="menu.route"
          :status="menu.status"
          :visible_menu="menu.visible"
          :menu_id="menu.menu_id"
          :icon="menu.icon"
          :subMenus="menu['child']"
          :menuAll="menu"
          :parent_id="menu.parent_id"
          :current_route="current_route"
          :target="menu.target_blank"
          v-bind:key="menu.menu_id"
        />
      </ul>
    </li>
  </div>
</template>

<script>
import Menu from "./Menu";
import '@mdi/font/css/materialdesignicons.min.css'

export default {
  props:["user_menus","current_route"],
  components: {
    Menu
  },
  data() {
    return {}
  },
  computed : {
    sortJson: function() {
      var object_array = [];
      var user_menu = this.user_menus;
      // map properties into array of objects
      for (var key in user_menu) {
          if (user_menu.hasOwnProperty(key)) {
              object_array.push(user_menu[key]);   
          }
      }
      // sort array of objects
      object_array.sort(function(a,b) {
          // note that you might need to change the sort comparison function to meet your needs
          return parseInt(a.sort) - parseInt(b.sort);
      })
      Object.values(object_array).map(function(item, index) {
          if(item.hasOwnProperty('child')){
            var object_array_2 = [];
            for (var key in item.child) {
                object_array_2.push(item.child[key]);   
            }
            object_array_2.sort(function(a,b) {
                // note that you might need to change the sort comparison function to meet your needs
                return parseInt(a.sort) - parseInt(b.sort);
            })
            item.child = object_array_2;
            Object.values(item.child).map(function(itemChild, index){
              if(itemChild.hasOwnProperty('child')){
                var object_array_3 = [];
                for (var key in itemChild.child) {
                    object_array_3.push(itemChild.child[key]);   
                }
                object_array_3.sort(function(a,b) {
                    // note that you might need to change the sort comparison function to meet your needs
                    return parseInt(a.sort) - parseInt(b.sort);
                })
                itemChild.child = object_array_3;
              }
            })
          }
      });
      return object_array;
    }
  },
  mounted(){}
};
</script>

<style>
.submenu-wrapper{
  margin-left: .6rem;
}
</style>