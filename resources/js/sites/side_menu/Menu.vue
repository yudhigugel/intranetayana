<template>
  <li class="nav-item" :style="styleItem(menuAll)">
    <a
      :class="checkMenu(url)+' nav-dropdown'"
      data-toggle="collapse"
      :href="`#menu_${menu_id}`"
      aria-expanded="false"
      aria-controls="ui-basic"
      v-if="subMenus"
    >
      <span class="menu-title">{{name}}</span>
      <i class="menu-arrow"></i>
    </a>

    <a :class="checkMenu(url)" :href="url" v-else :target="target==1?true:false">
      <span class="menu-title">{{name}}</span>
    </a>

    <div :class="checkCollapse(menuAll)" :id="`menu_${menu_id}`" v-if="subMenus">
      <ul class="nav flex-column sub-menu">
        <Menu
          v-for="menu in subMenus"
          :name="menu.menu_name"
          :url="menu.route"
          :status="menu.status"
          :visible_menu="menu.visible"
          :menu_id="menu.menu_id"
          :icon="menu.icon"
          :subMenus="menu['child']"
          :parent_id="menu.parent_id"
          :current_route="current_route"
          :target="menu.target_blank"
          v-bind:key="menu.menu_id"
          :menuAll="menu"
        />
      </ul>
    </div>
  </li>
</template>

<script>
import Menu from "./Menu";
import UrlPattern from "url-pattern";

export default {
  name: "Menu",
  components: {
    Menu,
  },
  props: {
    name: { type: String, required: true },
    url: { type: String, required: true },
    status: { type: Number },
    icon: { type: String },
    visible_menu: { type: String },
    menu_id: {},
    parent_id: { type: Number },
    subMenus: { required: true },
    current_route: { type: String },
    target: {},
    menuAll: {},
  },
  methods: {
    checkMenu(route) {
      let _this = this;
      const dataUrl = route;
      let splitDataUrl = dataUrl.split("*");
      // console.log(dataUrl, splitDataUrl);

      if (splitDataUrl.length > 0) {
        let dataUrlOk = splitDataUrl.filter(function (item) {
          return (
            item !== "" &&
            item !== "/" &&
            item !== "#" &&
            !item.match("http") &&
            !item.match("https")
          );
        });
        // console.log(dataUrlOk);
        if (dataUrlOk.length > 0) {
          let curentRoute = "/" + _this.current_route;
          try{
            curentRoute = curentRoute.trim()
          } catch(error){ console.log(error) }
          let urlOk = dataUrlOk[0].replace(/\/$/, "");

          var pattern = new UrlPattern(`${urlOk}/{:id}`);
          // console.log(`PATTERN FOUND ${pattern}`, `CURRENT ROUTE ${curentRoute}`, urlOk);
          // console.log(`MATCH`, typeof pattern.match(curentRoute));
          if (pattern.match(curentRoute)) {
            return "nav-link active active-menu";
          }
        }
        if (splitDataUrl.length > 1 || splitDataUrl.length == 1) {
          if (dataUrlOk.length > 0) {
            let curentRoute = "/" + _this.current_route;
            let urlOk = dataUrlOk[0].replace(/\/$/, "");

            var pattern = new UrlPattern(`${urlOk}/{:id}/{:id}`);
            if (pattern.match(curentRoute)) {
              return "nav-link active active-menu";
            }
          }
        }
        if (splitDataUrl.length > 2 || splitDataUrl.length == 2) {
          if (dataUrlOk.length > 0) {
            let curentRoute = "/" + _this.current_route;
            let urlOk = dataUrlOk[0].replace(/\/$/, "");

            var pattern = new UrlPattern(`${urlOk}/{:id}/{:id}/{:id}`);
            if (pattern.match(curentRoute)) {
              return "nav-link active active-menu";
            }
          }
        }
      }

      try {
        var current_route_normalized = _this.current_route.trim();
      } catch(error){ var current_route_normalized = _this.current_route; }
      // console.log("/"+current_route_normalized);

      var location_check = '',
      path = '',
      path_last = '',
      path_last_child1 = '',
      path_last_child2 = '';

      try {
        var location_check = current_route_normalized;
        var path = location_check.substring(0, location_check.lastIndexOf("/"));
        var path_last = path.substring(0, path.lastIndexOf("/"));
        var path_last_child1 = path_last.substring(0, path_last.lastIndexOf("/"));
        var path_last_child2 = path_last_child1.substring(0, path_last_child1.lastIndexOf("/"));
        // console.log(location_check, path, path_last, route);
      } catch(error) {};

      if ("/" + current_route_normalized == route || "/"+path == route || "/"+path_last == route || "/"+path_last_child1 == route || "/"+path_last_child2 == route) {
        return "nav-link active active-menu";
      }
      return "nav-link";
    },
    checkCollapse(menus) {
      let _this = this;

      let list_menus = [];

      let checkMenu = menus["child"].some(function (item) {
        const dataUrl = Object.keys(item).includes('route') ? item.route.trim() : '#';
        let splitDataUrl = dataUrl.split("*");
        // console.log(dataUrl, splitDataUrl);

        if (splitDataUrl.length > 0) {
          let dataUrlOk = splitDataUrl.filter(function (item) {
            return (
              item !== "" &&
              item !== "/" &&
              item !== "#" &&
              !item.match("http") &&
              !item.match("https")
            );
          });
          if (dataUrlOk.length > 0) {
            let curentRoute = "/" + _this.current_route;
            let urlOk = dataUrlOk[0].replace(/\/$/, "");

            var pattern = new UrlPattern(`${urlOk}/{:id}`);
            if (pattern.match(curentRoute)) {
              return true;
            }
          }
          if (splitDataUrl.length > 1 || splitDataUrl.length == 1) {
            if (dataUrlOk.length > 0) {
              let curentRoute = "/" + _this.current_route;
              let urlOk = dataUrlOk[0].replace(/\/$/, "");

              var pattern = new UrlPattern(`${urlOk}/{:id}/{:id}`);
              if (pattern.match(curentRoute)) {
                return true;
              }
            }
          }
          if (splitDataUrl.length > 1 || splitDataUrl.length == 2) {
            if (dataUrlOk.length > 0) {
              let curentRoute = "/" + _this.current_route;
              let urlOk = dataUrlOk[0].replace(/\/$/, "");

              var pattern = new UrlPattern(`${urlOk}/{:id}/{:id}/{:id}`);
              if (pattern.match(curentRoute)) {
                return true;
              }
            }
          }
          if (splitDataUrl.length > 2 || splitDataUrl.length == 3) {
            if (dataUrlOk.length > 0) {
              let curentRoute = "/" + _this.current_route;
              let urlOk = dataUrlOk[0].replace(/\/$/, "");

              var pattern = new UrlPattern(`${urlOk}/{:id}/{:id}/{:id}`);
              if (pattern.match(curentRoute)) {
                return true;
              }
            }
          }
        }

        var location_check = '',
        path = '',
        path_last = '',
        path_last_child1 = '',
        path_last_child2 = '';

        try {
          var location_check = "/" + _this.current_route;
          var path = location_check.substring(0, location_check.lastIndexOf("/"));
          var path_last = path.substring(0, path.lastIndexOf("/"));
          var path_last_child1 = path_last.substring(0, path_last.lastIndexOf("/"));
          var path_last_child2 = path_last_child1.substring(0, path_last_child1.lastIndexOf("/"));
          // console.log(location_check, path, path_last, route);
        } catch(error) {};

        return dataUrl == `/${_this.current_route}` ? true : dataUrl == path ? true : dataUrl == path_last ? true : dataUrl == path_last_child1 ? true : dataUrl == path_last_child2 ? true : false;
      });

      if (checkMenu) {
        return "collapse show";
      }
      return "collapse";
    },
    styleItem(menus) {
      if (menus.child_position == 4) {
        return {
          marginLeft: "-26px",
        };
      } else if (menus.child_position == 3) {
        return {
          marginLeft: "-9px",
        };
      }
      return {};
    },
  },
};
</script>

<style>
.active-menu {
  color: black !important;
  font-weight: bold !important;
}
/* .menu-title{
  margin-left: 1.2rem;
} */
</style>