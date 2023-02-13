/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@mdi/font/css/materialdesignicons.min.css":
/*!****************************************************************!*\
  !*** ./node_modules/@mdi/font/css/materialdesignicons.min.css ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../css-loader??ref--9-1!../../../postcss-loader/src??ref--9-2!./materialdesignicons.min.css */ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/@mdi/font/css/materialdesignicons.min.css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.eot":
/*!**********************************************************************!*\
  !*** ./node_modules/@mdi/font/fonts/materialdesignicons-webfont.eot ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/fonts/vendor/@mdi/materialdesignicons-webfont.eot?83332a6f275d324c714c6ea40314863d";

/***/ }),

/***/ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.eot?v=5.7.55":
/*!*******************************************************************************!*\
  !*** ./node_modules/@mdi/font/fonts/materialdesignicons-webfont.eot?v=5.7.55 ***!
  \*******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/fonts/vendor/@mdi/materialdesignicons-webfont.eot?83332a6f275d324c714c6ea40314863d";

/***/ }),

/***/ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.ttf?v=5.7.55":
/*!*******************************************************************************!*\
  !*** ./node_modules/@mdi/font/fonts/materialdesignicons-webfont.ttf?v=5.7.55 ***!
  \*******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/fonts/vendor/@mdi/materialdesignicons-webfont.ttf?c1242726c7eac4eb5e843d826f78fb1b";

/***/ }),

/***/ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.woff2?v=5.7.55":
/*!*********************************************************************************!*\
  !*** ./node_modules/@mdi/font/fonts/materialdesignicons-webfont.woff2?v=5.7.55 ***!
  \*********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/fonts/vendor/@mdi/materialdesignicons-webfont.woff2?ecab20e9fda0eef864b6d2ca27b44b79";

/***/ }),

/***/ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.woff?v=5.7.55":
/*!********************************************************************************!*\
  !*** ./node_modules/@mdi/font/fonts/materialdesignicons-webfont.woff?v=5.7.55 ***!
  \********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/fonts/vendor/@mdi/materialdesignicons-webfont.woff?96d58a0792f63ec3808a897effe17faf";

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/Menu.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Menu__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Menu */ "./resources/js/sites/side_menu/Menu.vue");
/* harmony import */ var url_pattern__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! url-pattern */ "./node_modules/url-pattern/lib/url-pattern.js");
/* harmony import */ var url_pattern__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(url_pattern__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  name: "Menu",
  components: {
    Menu: _Menu__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    name: {
      type: String,
      required: true
    },
    url: {
      type: String,
      required: true
    },
    status: {
      type: Number
    },
    icon: {
      type: String
    },
    visible_menu: {
      type: String
    },
    menu_id: {},
    parent_id: {
      type: Number
    },
    subMenus: {
      required: true
    },
    current_route: {
      type: String
    },
    target: {},
    menuAll: {}
  },
  methods: {
    checkMenu: function checkMenu(route) {
      var _this = this;

      var dataUrl = route;
      var splitDataUrl = dataUrl.split("*"); // console.log(dataUrl, splitDataUrl);

      if (splitDataUrl.length > 0) {
        var dataUrlOk = splitDataUrl.filter(function (item) {
          return item !== "" && item !== "/" && item !== "#" && !item.match("http") && !item.match("https");
        }); // console.log(dataUrlOk);

        if (dataUrlOk.length > 0) {
          var curentRoute = "/" + _this.current_route;

          try {
            curentRoute = curentRoute.trim();
          } catch (error) {
            console.log(error);
          }

          var urlOk = dataUrlOk[0].replace(/\/$/, "");
          var pattern = new url_pattern__WEBPACK_IMPORTED_MODULE_1___default.a("".concat(urlOk, "/{:id}")); // console.log(`PATTERN FOUND ${pattern}`, `CURRENT ROUTE ${curentRoute}`, urlOk);
          // console.log(`MATCH`, typeof pattern.match(curentRoute));

          if (pattern.match(curentRoute)) {
            return "nav-link active active-menu";
          }
        }

        if (splitDataUrl.length > 1 || splitDataUrl.length == 1) {
          if (dataUrlOk.length > 0) {
            var _curentRoute = "/" + _this.current_route;

            var _urlOk = dataUrlOk[0].replace(/\/$/, "");

            var pattern = new url_pattern__WEBPACK_IMPORTED_MODULE_1___default.a("".concat(_urlOk, "/{:id}/{:id}"));

            if (pattern.match(_curentRoute)) {
              return "nav-link active active-menu";
            }
          }
        }

        if (splitDataUrl.length > 2 || splitDataUrl.length == 2) {
          if (dataUrlOk.length > 0) {
            var _curentRoute2 = "/" + _this.current_route;

            var _urlOk2 = dataUrlOk[0].replace(/\/$/, "");

            var pattern = new url_pattern__WEBPACK_IMPORTED_MODULE_1___default.a("".concat(_urlOk2, "/{:id}/{:id}/{:id}"));

            if (pattern.match(_curentRoute2)) {
              return "nav-link active active-menu";
            }
          }
        }
      }

      try {
        var current_route_normalized = _this.current_route.trim();
      } catch (error) {
        var current_route_normalized = _this.current_route;
      } // console.log("/"+current_route_normalized);


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
        var path_last_child2 = path_last_child1.substring(0, path_last_child1.lastIndexOf("/")); // console.log(location_check, path, path_last, route);
      } catch (error) {}

      ;

      if ("/" + current_route_normalized == route || "/" + path == route || "/" + path_last == route || "/" + path_last_child1 == route || "/" + path_last_child2 == route) {
        return "nav-link active active-menu";
      }

      return "nav-link";
    },
    checkCollapse: function checkCollapse(menus) {
      var _this = this;

      var list_menus = [];
      var checkMenu = menus["child"].some(function (item) {
        var dataUrl = Object.keys(item).includes('route') ? item.route.trim() : '#';
        var splitDataUrl = dataUrl.split("*"); // console.log(dataUrl, splitDataUrl);

        if (splitDataUrl.length > 0) {
          var dataUrlOk = splitDataUrl.filter(function (item) {
            return item !== "" && item !== "/" && item !== "#" && !item.match("http") && !item.match("https");
          });

          if (dataUrlOk.length > 0) {
            var curentRoute = "/" + _this.current_route;
            var urlOk = dataUrlOk[0].replace(/\/$/, "");
            var pattern = new url_pattern__WEBPACK_IMPORTED_MODULE_1___default.a("".concat(urlOk, "/{:id}"));

            if (pattern.match(curentRoute)) {
              return true;
            }
          }

          if (splitDataUrl.length > 1 || splitDataUrl.length == 1) {
            if (dataUrlOk.length > 0) {
              var _curentRoute3 = "/" + _this.current_route;

              var _urlOk3 = dataUrlOk[0].replace(/\/$/, "");

              var pattern = new url_pattern__WEBPACK_IMPORTED_MODULE_1___default.a("".concat(_urlOk3, "/{:id}/{:id}"));

              if (pattern.match(_curentRoute3)) {
                return true;
              }
            }
          }

          if (splitDataUrl.length > 1 || splitDataUrl.length == 2) {
            if (dataUrlOk.length > 0) {
              var _curentRoute4 = "/" + _this.current_route;

              var _urlOk4 = dataUrlOk[0].replace(/\/$/, "");

              var pattern = new url_pattern__WEBPACK_IMPORTED_MODULE_1___default.a("".concat(_urlOk4, "/{:id}/{:id}/{:id}"));

              if (pattern.match(_curentRoute4)) {
                return true;
              }
            }
          }

          if (splitDataUrl.length > 2 || splitDataUrl.length == 3) {
            if (dataUrlOk.length > 0) {
              var _curentRoute5 = "/" + _this.current_route;

              var _urlOk5 = dataUrlOk[0].replace(/\/$/, "");

              var pattern = new url_pattern__WEBPACK_IMPORTED_MODULE_1___default.a("".concat(_urlOk5, "/{:id}/{:id}/{:id}"));

              if (pattern.match(_curentRoute5)) {
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
          var path_last_child2 = path_last_child1.substring(0, path_last_child1.lastIndexOf("/")); // console.log(location_check, path, path_last, route);
        } catch (error) {}

        ;
        return dataUrl == "/".concat(_this.current_route) ? true : dataUrl == path ? true : dataUrl == path_last ? true : dataUrl == path_last_child1 ? true : dataUrl == path_last_child2 ? true : false;
      });

      if (checkMenu) {
        return "collapse show";
      }

      return "collapse";
    },
    styleItem: function styleItem(menus) {
      if (menus.child_position == 4) {
        return {
          marginLeft: "-26px"
        };
      } else if (menus.child_position == 3) {
        return {
          marginLeft: "-9px"
        };
      }

      return {};
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/SideMenu.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Menu__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Menu */ "./resources/js/sites/side_menu/Menu.vue");
/* harmony import */ var _mdi_font_css_materialdesignicons_min_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @mdi/font/css/materialdesignicons.min.css */ "./node_modules/@mdi/font/css/materialdesignicons.min.css");
/* harmony import */ var _mdi_font_css_materialdesignicons_min_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_mdi_font_css_materialdesignicons_min_css__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["user_menus", "current_route"],
  components: {
    Menu: _Menu__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {};
  },
  computed: {
    sortJson: function sortJson() {
      var object_array = [];
      var user_menu = this.user_menus; // map properties into array of objects

      for (var key in user_menu) {
        if (user_menu.hasOwnProperty(key)) {
          object_array.push(user_menu[key]);
        }
      } // sort array of objects


      object_array.sort(function (a, b) {
        // note that you might need to change the sort comparison function to meet your needs
        return parseInt(a.sort) - parseInt(b.sort);
      });
      Object.values(object_array).map(function (item, index) {
        if (item.hasOwnProperty('child')) {
          var object_array_2 = [];

          for (var key in item.child) {
            object_array_2.push(item.child[key]);
          }

          object_array_2.sort(function (a, b) {
            // note that you might need to change the sort comparison function to meet your needs
            return parseInt(a.sort) - parseInt(b.sort);
          });
          item.child = object_array_2;
          Object.values(item.child).map(function (itemChild, index) {
            if (itemChild.hasOwnProperty('child')) {
              var object_array_3 = [];

              for (var key in itemChild.child) {
                object_array_3.push(itemChild.child[key]);
              }

              object_array_3.sort(function (a, b) {
                // note that you might need to change the sort comparison function to meet your needs
                return parseInt(a.sort) - parseInt(b.sort);
              });
              itemChild.child = object_array_3;
            }
          });
        }
      });
      return object_array;
    }
  },
  mounted: function mounted() {}
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/@mdi/font/css/materialdesignicons.min.css":
/*!************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--9-1!./node_modules/postcss-loader/src??ref--9-2!./node_modules/@mdi/font/css/materialdesignicons.min.css ***!
  \************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var escape = __webpack_require__(/*! ../../../css-loader/lib/url/escape.js */ "./node_modules/css-loader/lib/url/escape.js");
exports = module.exports = __webpack_require__(/*! ../../../css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "@font-face{font-family:\"Material Design Icons\";src:url(" + escape(__webpack_require__(/*! ../fonts/materialdesignicons-webfont.eot?v=5.7.55 */ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.eot?v=5.7.55")) + ");src:url(" + escape(__webpack_require__(/*! ../fonts/materialdesignicons-webfont.eot */ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.eot")) + "?#iefix&v=5.7.55) format(\"embedded-opentype\"),url(" + escape(__webpack_require__(/*! ../fonts/materialdesignicons-webfont.woff2?v=5.7.55 */ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.woff2?v=5.7.55")) + ") format(\"woff2\"),url(" + escape(__webpack_require__(/*! ../fonts/materialdesignicons-webfont.woff?v=5.7.55 */ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.woff?v=5.7.55")) + ") format(\"woff\"),url(" + escape(__webpack_require__(/*! ../fonts/materialdesignicons-webfont.ttf?v=5.7.55 */ "./node_modules/@mdi/font/fonts/materialdesignicons-webfont.ttf?v=5.7.55")) + ") format(\"truetype\");font-weight:normal;font-style:normal}.mdi:before,.mdi-set{display:inline-block;font:normal normal normal 24px/1 \"Material Design Icons\";font-size:inherit;text-rendering:auto;line-height:inherit;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.mdi-ab-testing::before{content:\"\\F01C9\"}.mdi-abjad-arabic::before{content:\"\\F1328\"}.mdi-abjad-hebrew::before{content:\"\\F1329\"}.mdi-abugida-devanagari::before{content:\"\\F132A\"}.mdi-abugida-thai::before{content:\"\\F132B\"}.mdi-access-point::before{content:\"\\F0003\"}.mdi-access-point-check::before{content:\"\\F1538\"}.mdi-access-point-minus::before{content:\"\\F1539\"}.mdi-access-point-network::before{content:\"\\F0002\"}.mdi-access-point-network-off::before{content:\"\\F0BE1\"}.mdi-access-point-off::before{content:\"\\F1511\"}.mdi-access-point-plus::before{content:\"\\F153A\"}.mdi-access-point-remove::before{content:\"\\F153B\"}.mdi-account::before{content:\"\\F0004\"}.mdi-account-alert::before{content:\"\\F0005\"}.mdi-account-alert-outline::before{content:\"\\F0B50\"}.mdi-account-arrow-left::before{content:\"\\F0B51\"}.mdi-account-arrow-left-outline::before{content:\"\\F0B52\"}.mdi-account-arrow-right::before{content:\"\\F0B53\"}.mdi-account-arrow-right-outline::before{content:\"\\F0B54\"}.mdi-account-box::before{content:\"\\F0006\"}.mdi-account-box-multiple::before{content:\"\\F0934\"}.mdi-account-box-multiple-outline::before{content:\"\\F100A\"}.mdi-account-box-outline::before{content:\"\\F0007\"}.mdi-account-cancel::before{content:\"\\F12DF\"}.mdi-account-cancel-outline::before{content:\"\\F12E0\"}.mdi-account-cash::before{content:\"\\F1097\"}.mdi-account-cash-outline::before{content:\"\\F1098\"}.mdi-account-check::before{content:\"\\F0008\"}.mdi-account-check-outline::before{content:\"\\F0BE2\"}.mdi-account-child::before{content:\"\\F0A89\"}.mdi-account-child-circle::before{content:\"\\F0A8A\"}.mdi-account-child-outline::before{content:\"\\F10C8\"}.mdi-account-circle::before{content:\"\\F0009\"}.mdi-account-circle-outline::before{content:\"\\F0B55\"}.mdi-account-clock::before{content:\"\\F0B56\"}.mdi-account-clock-outline::before{content:\"\\F0B57\"}.mdi-account-cog::before{content:\"\\F1370\"}.mdi-account-cog-outline::before{content:\"\\F1371\"}.mdi-account-convert::before{content:\"\\F000A\"}.mdi-account-convert-outline::before{content:\"\\F1301\"}.mdi-account-cowboy-hat::before{content:\"\\F0E9B\"}.mdi-account-details::before{content:\"\\F0631\"}.mdi-account-details-outline::before{content:\"\\F1372\"}.mdi-account-edit::before{content:\"\\F06BC\"}.mdi-account-edit-outline::before{content:\"\\F0FFB\"}.mdi-account-group::before{content:\"\\F0849\"}.mdi-account-group-outline::before{content:\"\\F0B58\"}.mdi-account-hard-hat::before{content:\"\\F05B5\"}.mdi-account-heart::before{content:\"\\F0899\"}.mdi-account-heart-outline::before{content:\"\\F0BE3\"}.mdi-account-key::before{content:\"\\F000B\"}.mdi-account-key-outline::before{content:\"\\F0BE4\"}.mdi-account-lock::before{content:\"\\F115E\"}.mdi-account-lock-outline::before{content:\"\\F115F\"}.mdi-account-minus::before{content:\"\\F000D\"}.mdi-account-minus-outline::before{content:\"\\F0AEC\"}.mdi-account-multiple::before{content:\"\\F000E\"}.mdi-account-multiple-check::before{content:\"\\F08C5\"}.mdi-account-multiple-check-outline::before{content:\"\\F11FE\"}.mdi-account-multiple-minus::before{content:\"\\F05D3\"}.mdi-account-multiple-minus-outline::before{content:\"\\F0BE5\"}.mdi-account-multiple-outline::before{content:\"\\F000F\"}.mdi-account-multiple-plus::before{content:\"\\F0010\"}.mdi-account-multiple-plus-outline::before{content:\"\\F0800\"}.mdi-account-multiple-remove::before{content:\"\\F120A\"}.mdi-account-multiple-remove-outline::before{content:\"\\F120B\"}.mdi-account-music::before{content:\"\\F0803\"}.mdi-account-music-outline::before{content:\"\\F0CE9\"}.mdi-account-network::before{content:\"\\F0011\"}.mdi-account-network-outline::before{content:\"\\F0BE6\"}.mdi-account-off::before{content:\"\\F0012\"}.mdi-account-off-outline::before{content:\"\\F0BE7\"}.mdi-account-outline::before{content:\"\\F0013\"}.mdi-account-plus::before{content:\"\\F0014\"}.mdi-account-plus-outline::before{content:\"\\F0801\"}.mdi-account-question::before{content:\"\\F0B59\"}.mdi-account-question-outline::before{content:\"\\F0B5A\"}.mdi-account-reactivate::before{content:\"\\F152B\"}.mdi-account-reactivate-outline::before{content:\"\\F152C\"}.mdi-account-remove::before{content:\"\\F0015\"}.mdi-account-remove-outline::before{content:\"\\F0AED\"}.mdi-account-search::before{content:\"\\F0016\"}.mdi-account-search-outline::before{content:\"\\F0935\"}.mdi-account-settings::before{content:\"\\F0630\"}.mdi-account-settings-outline::before{content:\"\\F10C9\"}.mdi-account-star::before{content:\"\\F0017\"}.mdi-account-star-outline::before{content:\"\\F0BE8\"}.mdi-account-supervisor::before{content:\"\\F0A8B\"}.mdi-account-supervisor-circle::before{content:\"\\F0A8C\"}.mdi-account-supervisor-circle-outline::before{content:\"\\F14EC\"}.mdi-account-supervisor-outline::before{content:\"\\F112D\"}.mdi-account-switch::before{content:\"\\F0019\"}.mdi-account-switch-outline::before{content:\"\\F04CB\"}.mdi-account-tie::before{content:\"\\F0CE3\"}.mdi-account-tie-outline::before{content:\"\\F10CA\"}.mdi-account-tie-voice::before{content:\"\\F1308\"}.mdi-account-tie-voice-off::before{content:\"\\F130A\"}.mdi-account-tie-voice-off-outline::before{content:\"\\F130B\"}.mdi-account-tie-voice-outline::before{content:\"\\F1309\"}.mdi-account-voice::before{content:\"\\F05CB\"}.mdi-adjust::before{content:\"\\F001A\"}.mdi-adobe::before{content:\"\\F0936\"}.mdi-adobe-acrobat::before{content:\"\\F0F9D\"}.mdi-air-conditioner::before{content:\"\\F001B\"}.mdi-air-filter::before{content:\"\\F0D43\"}.mdi-air-horn::before{content:\"\\F0DAC\"}.mdi-air-humidifier::before{content:\"\\F1099\"}.mdi-air-humidifier-off::before{content:\"\\F1466\"}.mdi-air-purifier::before{content:\"\\F0D44\"}.mdi-airbag::before{content:\"\\F0BE9\"}.mdi-airballoon::before{content:\"\\F001C\"}.mdi-airballoon-outline::before{content:\"\\F100B\"}.mdi-airplane::before{content:\"\\F001D\"}.mdi-airplane-landing::before{content:\"\\F05D4\"}.mdi-airplane-off::before{content:\"\\F001E\"}.mdi-airplane-takeoff::before{content:\"\\F05D5\"}.mdi-airport::before{content:\"\\F084B\"}.mdi-alarm::before{content:\"\\F0020\"}.mdi-alarm-bell::before{content:\"\\F078E\"}.mdi-alarm-check::before{content:\"\\F0021\"}.mdi-alarm-light::before{content:\"\\F078F\"}.mdi-alarm-light-outline::before{content:\"\\F0BEA\"}.mdi-alarm-multiple::before{content:\"\\F0022\"}.mdi-alarm-note::before{content:\"\\F0E71\"}.mdi-alarm-note-off::before{content:\"\\F0E72\"}.mdi-alarm-off::before{content:\"\\F0023\"}.mdi-alarm-panel::before{content:\"\\F15C4\"}.mdi-alarm-panel-outline::before{content:\"\\F15C5\"}.mdi-alarm-plus::before{content:\"\\F0024\"}.mdi-alarm-snooze::before{content:\"\\F068E\"}.mdi-album::before{content:\"\\F0025\"}.mdi-alert::before{content:\"\\F0026\"}.mdi-alert-box::before{content:\"\\F0027\"}.mdi-alert-box-outline::before{content:\"\\F0CE4\"}.mdi-alert-circle::before{content:\"\\F0028\"}.mdi-alert-circle-check::before{content:\"\\F11ED\"}.mdi-alert-circle-check-outline::before{content:\"\\F11EE\"}.mdi-alert-circle-outline::before{content:\"\\F05D6\"}.mdi-alert-decagram::before{content:\"\\F06BD\"}.mdi-alert-decagram-outline::before{content:\"\\F0CE5\"}.mdi-alert-minus::before{content:\"\\F14BB\"}.mdi-alert-minus-outline::before{content:\"\\F14BE\"}.mdi-alert-octagon::before{content:\"\\F0029\"}.mdi-alert-octagon-outline::before{content:\"\\F0CE6\"}.mdi-alert-octagram::before{content:\"\\F0767\"}.mdi-alert-octagram-outline::before{content:\"\\F0CE7\"}.mdi-alert-outline::before{content:\"\\F002A\"}.mdi-alert-plus::before{content:\"\\F14BA\"}.mdi-alert-plus-outline::before{content:\"\\F14BD\"}.mdi-alert-remove::before{content:\"\\F14BC\"}.mdi-alert-remove-outline::before{content:\"\\F14BF\"}.mdi-alert-rhombus::before{content:\"\\F11CE\"}.mdi-alert-rhombus-outline::before{content:\"\\F11CF\"}.mdi-alien::before{content:\"\\F089A\"}.mdi-alien-outline::before{content:\"\\F10CB\"}.mdi-align-horizontal-center::before{content:\"\\F11C3\"}.mdi-align-horizontal-left::before{content:\"\\F11C2\"}.mdi-align-horizontal-right::before{content:\"\\F11C4\"}.mdi-align-vertical-bottom::before{content:\"\\F11C5\"}.mdi-align-vertical-center::before{content:\"\\F11C6\"}.mdi-align-vertical-top::before{content:\"\\F11C7\"}.mdi-all-inclusive::before{content:\"\\F06BE\"}.mdi-allergy::before{content:\"\\F1258\"}.mdi-alpha::before{content:\"\\F002B\"}.mdi-alpha-a::before{content:\"\\F0AEE\"}.mdi-alpha-a-box::before{content:\"\\F0B08\"}.mdi-alpha-a-box-outline::before{content:\"\\F0BEB\"}.mdi-alpha-a-circle::before{content:\"\\F0BEC\"}.mdi-alpha-a-circle-outline::before{content:\"\\F0BED\"}.mdi-alpha-b::before{content:\"\\F0AEF\"}.mdi-alpha-b-box::before{content:\"\\F0B09\"}.mdi-alpha-b-box-outline::before{content:\"\\F0BEE\"}.mdi-alpha-b-circle::before{content:\"\\F0BEF\"}.mdi-alpha-b-circle-outline::before{content:\"\\F0BF0\"}.mdi-alpha-c::before{content:\"\\F0AF0\"}.mdi-alpha-c-box::before{content:\"\\F0B0A\"}.mdi-alpha-c-box-outline::before{content:\"\\F0BF1\"}.mdi-alpha-c-circle::before{content:\"\\F0BF2\"}.mdi-alpha-c-circle-outline::before{content:\"\\F0BF3\"}.mdi-alpha-d::before{content:\"\\F0AF1\"}.mdi-alpha-d-box::before{content:\"\\F0B0B\"}.mdi-alpha-d-box-outline::before{content:\"\\F0BF4\"}.mdi-alpha-d-circle::before{content:\"\\F0BF5\"}.mdi-alpha-d-circle-outline::before{content:\"\\F0BF6\"}.mdi-alpha-e::before{content:\"\\F0AF2\"}.mdi-alpha-e-box::before{content:\"\\F0B0C\"}.mdi-alpha-e-box-outline::before{content:\"\\F0BF7\"}.mdi-alpha-e-circle::before{content:\"\\F0BF8\"}.mdi-alpha-e-circle-outline::before{content:\"\\F0BF9\"}.mdi-alpha-f::before{content:\"\\F0AF3\"}.mdi-alpha-f-box::before{content:\"\\F0B0D\"}.mdi-alpha-f-box-outline::before{content:\"\\F0BFA\"}.mdi-alpha-f-circle::before{content:\"\\F0BFB\"}.mdi-alpha-f-circle-outline::before{content:\"\\F0BFC\"}.mdi-alpha-g::before{content:\"\\F0AF4\"}.mdi-alpha-g-box::before{content:\"\\F0B0E\"}.mdi-alpha-g-box-outline::before{content:\"\\F0BFD\"}.mdi-alpha-g-circle::before{content:\"\\F0BFE\"}.mdi-alpha-g-circle-outline::before{content:\"\\F0BFF\"}.mdi-alpha-h::before{content:\"\\F0AF5\"}.mdi-alpha-h-box::before{content:\"\\F0B0F\"}.mdi-alpha-h-box-outline::before{content:\"\\F0C00\"}.mdi-alpha-h-circle::before{content:\"\\F0C01\"}.mdi-alpha-h-circle-outline::before{content:\"\\F0C02\"}.mdi-alpha-i::before{content:\"\\F0AF6\"}.mdi-alpha-i-box::before{content:\"\\F0B10\"}.mdi-alpha-i-box-outline::before{content:\"\\F0C03\"}.mdi-alpha-i-circle::before{content:\"\\F0C04\"}.mdi-alpha-i-circle-outline::before{content:\"\\F0C05\"}.mdi-alpha-j::before{content:\"\\F0AF7\"}.mdi-alpha-j-box::before{content:\"\\F0B11\"}.mdi-alpha-j-box-outline::before{content:\"\\F0C06\"}.mdi-alpha-j-circle::before{content:\"\\F0C07\"}.mdi-alpha-j-circle-outline::before{content:\"\\F0C08\"}.mdi-alpha-k::before{content:\"\\F0AF8\"}.mdi-alpha-k-box::before{content:\"\\F0B12\"}.mdi-alpha-k-box-outline::before{content:\"\\F0C09\"}.mdi-alpha-k-circle::before{content:\"\\F0C0A\"}.mdi-alpha-k-circle-outline::before{content:\"\\F0C0B\"}.mdi-alpha-l::before{content:\"\\F0AF9\"}.mdi-alpha-l-box::before{content:\"\\F0B13\"}.mdi-alpha-l-box-outline::before{content:\"\\F0C0C\"}.mdi-alpha-l-circle::before{content:\"\\F0C0D\"}.mdi-alpha-l-circle-outline::before{content:\"\\F0C0E\"}.mdi-alpha-m::before{content:\"\\F0AFA\"}.mdi-alpha-m-box::before{content:\"\\F0B14\"}.mdi-alpha-m-box-outline::before{content:\"\\F0C0F\"}.mdi-alpha-m-circle::before{content:\"\\F0C10\"}.mdi-alpha-m-circle-outline::before{content:\"\\F0C11\"}.mdi-alpha-n::before{content:\"\\F0AFB\"}.mdi-alpha-n-box::before{content:\"\\F0B15\"}.mdi-alpha-n-box-outline::before{content:\"\\F0C12\"}.mdi-alpha-n-circle::before{content:\"\\F0C13\"}.mdi-alpha-n-circle-outline::before{content:\"\\F0C14\"}.mdi-alpha-o::before{content:\"\\F0AFC\"}.mdi-alpha-o-box::before{content:\"\\F0B16\"}.mdi-alpha-o-box-outline::before{content:\"\\F0C15\"}.mdi-alpha-o-circle::before{content:\"\\F0C16\"}.mdi-alpha-o-circle-outline::before{content:\"\\F0C17\"}.mdi-alpha-p::before{content:\"\\F0AFD\"}.mdi-alpha-p-box::before{content:\"\\F0B17\"}.mdi-alpha-p-box-outline::before{content:\"\\F0C18\"}.mdi-alpha-p-circle::before{content:\"\\F0C19\"}.mdi-alpha-p-circle-outline::before{content:\"\\F0C1A\"}.mdi-alpha-q::before{content:\"\\F0AFE\"}.mdi-alpha-q-box::before{content:\"\\F0B18\"}.mdi-alpha-q-box-outline::before{content:\"\\F0C1B\"}.mdi-alpha-q-circle::before{content:\"\\F0C1C\"}.mdi-alpha-q-circle-outline::before{content:\"\\F0C1D\"}.mdi-alpha-r::before{content:\"\\F0AFF\"}.mdi-alpha-r-box::before{content:\"\\F0B19\"}.mdi-alpha-r-box-outline::before{content:\"\\F0C1E\"}.mdi-alpha-r-circle::before{content:\"\\F0C1F\"}.mdi-alpha-r-circle-outline::before{content:\"\\F0C20\"}.mdi-alpha-s::before{content:\"\\F0B00\"}.mdi-alpha-s-box::before{content:\"\\F0B1A\"}.mdi-alpha-s-box-outline::before{content:\"\\F0C21\"}.mdi-alpha-s-circle::before{content:\"\\F0C22\"}.mdi-alpha-s-circle-outline::before{content:\"\\F0C23\"}.mdi-alpha-t::before{content:\"\\F0B01\"}.mdi-alpha-t-box::before{content:\"\\F0B1B\"}.mdi-alpha-t-box-outline::before{content:\"\\F0C24\"}.mdi-alpha-t-circle::before{content:\"\\F0C25\"}.mdi-alpha-t-circle-outline::before{content:\"\\F0C26\"}.mdi-alpha-u::before{content:\"\\F0B02\"}.mdi-alpha-u-box::before{content:\"\\F0B1C\"}.mdi-alpha-u-box-outline::before{content:\"\\F0C27\"}.mdi-alpha-u-circle::before{content:\"\\F0C28\"}.mdi-alpha-u-circle-outline::before{content:\"\\F0C29\"}.mdi-alpha-v::before{content:\"\\F0B03\"}.mdi-alpha-v-box::before{content:\"\\F0B1D\"}.mdi-alpha-v-box-outline::before{content:\"\\F0C2A\"}.mdi-alpha-v-circle::before{content:\"\\F0C2B\"}.mdi-alpha-v-circle-outline::before{content:\"\\F0C2C\"}.mdi-alpha-w::before{content:\"\\F0B04\"}.mdi-alpha-w-box::before{content:\"\\F0B1E\"}.mdi-alpha-w-box-outline::before{content:\"\\F0C2D\"}.mdi-alpha-w-circle::before{content:\"\\F0C2E\"}.mdi-alpha-w-circle-outline::before{content:\"\\F0C2F\"}.mdi-alpha-x::before{content:\"\\F0B05\"}.mdi-alpha-x-box::before{content:\"\\F0B1F\"}.mdi-alpha-x-box-outline::before{content:\"\\F0C30\"}.mdi-alpha-x-circle::before{content:\"\\F0C31\"}.mdi-alpha-x-circle-outline::before{content:\"\\F0C32\"}.mdi-alpha-y::before{content:\"\\F0B06\"}.mdi-alpha-y-box::before{content:\"\\F0B20\"}.mdi-alpha-y-box-outline::before{content:\"\\F0C33\"}.mdi-alpha-y-circle::before{content:\"\\F0C34\"}.mdi-alpha-y-circle-outline::before{content:\"\\F0C35\"}.mdi-alpha-z::before{content:\"\\F0B07\"}.mdi-alpha-z-box::before{content:\"\\F0B21\"}.mdi-alpha-z-box-outline::before{content:\"\\F0C36\"}.mdi-alpha-z-circle::before{content:\"\\F0C37\"}.mdi-alpha-z-circle-outline::before{content:\"\\F0C38\"}.mdi-alphabet-aurebesh::before{content:\"\\F132C\"}.mdi-alphabet-cyrillic::before{content:\"\\F132D\"}.mdi-alphabet-greek::before{content:\"\\F132E\"}.mdi-alphabet-latin::before{content:\"\\F132F\"}.mdi-alphabet-piqad::before{content:\"\\F1330\"}.mdi-alphabet-tengwar::before{content:\"\\F1337\"}.mdi-alphabetical::before{content:\"\\F002C\"}.mdi-alphabetical-off::before{content:\"\\F100C\"}.mdi-alphabetical-variant::before{content:\"\\F100D\"}.mdi-alphabetical-variant-off::before{content:\"\\F100E\"}.mdi-altimeter::before{content:\"\\F05D7\"}.mdi-amazon::before{content:\"\\F002D\"}.mdi-amazon-alexa::before{content:\"\\F08C6\"}.mdi-ambulance::before{content:\"\\F002F\"}.mdi-ammunition::before{content:\"\\F0CE8\"}.mdi-ampersand::before{content:\"\\F0A8D\"}.mdi-amplifier::before{content:\"\\F0030\"}.mdi-amplifier-off::before{content:\"\\F11B5\"}.mdi-anchor::before{content:\"\\F0031\"}.mdi-android::before{content:\"\\F0032\"}.mdi-android-auto::before{content:\"\\F0A8E\"}.mdi-android-debug-bridge::before{content:\"\\F0033\"}.mdi-android-messages::before{content:\"\\F0D45\"}.mdi-android-studio::before{content:\"\\F0034\"}.mdi-angle-acute::before{content:\"\\F0937\"}.mdi-angle-obtuse::before{content:\"\\F0938\"}.mdi-angle-right::before{content:\"\\F0939\"}.mdi-angular::before{content:\"\\F06B2\"}.mdi-angularjs::before{content:\"\\F06BF\"}.mdi-animation::before{content:\"\\F05D8\"}.mdi-animation-outline::before{content:\"\\F0A8F\"}.mdi-animation-play::before{content:\"\\F093A\"}.mdi-animation-play-outline::before{content:\"\\F0A90\"}.mdi-ansible::before{content:\"\\F109A\"}.mdi-antenna::before{content:\"\\F1119\"}.mdi-anvil::before{content:\"\\F089B\"}.mdi-apache-kafka::before{content:\"\\F100F\"}.mdi-api::before{content:\"\\F109B\"}.mdi-api-off::before{content:\"\\F1257\"}.mdi-apple::before{content:\"\\F0035\"}.mdi-apple-airplay::before{content:\"\\F001F\"}.mdi-apple-finder::before{content:\"\\F0036\"}.mdi-apple-icloud::before{content:\"\\F0038\"}.mdi-apple-ios::before{content:\"\\F0037\"}.mdi-apple-keyboard-caps::before{content:\"\\F0632\"}.mdi-apple-keyboard-command::before{content:\"\\F0633\"}.mdi-apple-keyboard-control::before{content:\"\\F0634\"}.mdi-apple-keyboard-option::before{content:\"\\F0635\"}.mdi-apple-keyboard-shift::before{content:\"\\F0636\"}.mdi-apple-safari::before{content:\"\\F0039\"}.mdi-application::before{content:\"\\F0614\"}.mdi-application-cog::before{content:\"\\F1577\"}.mdi-application-export::before{content:\"\\F0DAD\"}.mdi-application-import::before{content:\"\\F0DAE\"}.mdi-application-settings::before{content:\"\\F1555\"}.mdi-approximately-equal::before{content:\"\\F0F9E\"}.mdi-approximately-equal-box::before{content:\"\\F0F9F\"}.mdi-apps::before{content:\"\\F003B\"}.mdi-apps-box::before{content:\"\\F0D46\"}.mdi-arch::before{content:\"\\F08C7\"}.mdi-archive::before{content:\"\\F003C\"}.mdi-archive-alert::before{content:\"\\F14FD\"}.mdi-archive-alert-outline::before{content:\"\\F14FE\"}.mdi-archive-arrow-down::before{content:\"\\F1259\"}.mdi-archive-arrow-down-outline::before{content:\"\\F125A\"}.mdi-archive-arrow-up::before{content:\"\\F125B\"}.mdi-archive-arrow-up-outline::before{content:\"\\F125C\"}.mdi-archive-outline::before{content:\"\\F120E\"}.mdi-arm-flex::before{content:\"\\F0FD7\"}.mdi-arm-flex-outline::before{content:\"\\F0FD6\"}.mdi-arrange-bring-forward::before{content:\"\\F003D\"}.mdi-arrange-bring-to-front::before{content:\"\\F003E\"}.mdi-arrange-send-backward::before{content:\"\\F003F\"}.mdi-arrange-send-to-back::before{content:\"\\F0040\"}.mdi-arrow-all::before{content:\"\\F0041\"}.mdi-arrow-bottom-left::before{content:\"\\F0042\"}.mdi-arrow-bottom-left-bold-outline::before{content:\"\\F09B7\"}.mdi-arrow-bottom-left-thick::before{content:\"\\F09B8\"}.mdi-arrow-bottom-left-thin-circle-outline::before{content:\"\\F1596\"}.mdi-arrow-bottom-right::before{content:\"\\F0043\"}.mdi-arrow-bottom-right-bold-outline::before{content:\"\\F09B9\"}.mdi-arrow-bottom-right-thick::before{content:\"\\F09BA\"}.mdi-arrow-bottom-right-thin-circle-outline::before{content:\"\\F1595\"}.mdi-arrow-collapse::before{content:\"\\F0615\"}.mdi-arrow-collapse-all::before{content:\"\\F0044\"}.mdi-arrow-collapse-down::before{content:\"\\F0792\"}.mdi-arrow-collapse-horizontal::before{content:\"\\F084C\"}.mdi-arrow-collapse-left::before{content:\"\\F0793\"}.mdi-arrow-collapse-right::before{content:\"\\F0794\"}.mdi-arrow-collapse-up::before{content:\"\\F0795\"}.mdi-arrow-collapse-vertical::before{content:\"\\F084D\"}.mdi-arrow-decision::before{content:\"\\F09BB\"}.mdi-arrow-decision-auto::before{content:\"\\F09BC\"}.mdi-arrow-decision-auto-outline::before{content:\"\\F09BD\"}.mdi-arrow-decision-outline::before{content:\"\\F09BE\"}.mdi-arrow-down::before{content:\"\\F0045\"}.mdi-arrow-down-bold::before{content:\"\\F072E\"}.mdi-arrow-down-bold-box::before{content:\"\\F072F\"}.mdi-arrow-down-bold-box-outline::before{content:\"\\F0730\"}.mdi-arrow-down-bold-circle::before{content:\"\\F0047\"}.mdi-arrow-down-bold-circle-outline::before{content:\"\\F0048\"}.mdi-arrow-down-bold-hexagon-outline::before{content:\"\\F0049\"}.mdi-arrow-down-bold-outline::before{content:\"\\F09BF\"}.mdi-arrow-down-box::before{content:\"\\F06C0\"}.mdi-arrow-down-circle::before{content:\"\\F0CDB\"}.mdi-arrow-down-circle-outline::before{content:\"\\F0CDC\"}.mdi-arrow-down-drop-circle::before{content:\"\\F004A\"}.mdi-arrow-down-drop-circle-outline::before{content:\"\\F004B\"}.mdi-arrow-down-thick::before{content:\"\\F0046\"}.mdi-arrow-down-thin-circle-outline::before{content:\"\\F1599\"}.mdi-arrow-expand::before{content:\"\\F0616\"}.mdi-arrow-expand-all::before{content:\"\\F004C\"}.mdi-arrow-expand-down::before{content:\"\\F0796\"}.mdi-arrow-expand-horizontal::before{content:\"\\F084E\"}.mdi-arrow-expand-left::before{content:\"\\F0797\"}.mdi-arrow-expand-right::before{content:\"\\F0798\"}.mdi-arrow-expand-up::before{content:\"\\F0799\"}.mdi-arrow-expand-vertical::before{content:\"\\F084F\"}.mdi-arrow-horizontal-lock::before{content:\"\\F115B\"}.mdi-arrow-left::before{content:\"\\F004D\"}.mdi-arrow-left-bold::before{content:\"\\F0731\"}.mdi-arrow-left-bold-box::before{content:\"\\F0732\"}.mdi-arrow-left-bold-box-outline::before{content:\"\\F0733\"}.mdi-arrow-left-bold-circle::before{content:\"\\F004F\"}.mdi-arrow-left-bold-circle-outline::before{content:\"\\F0050\"}.mdi-arrow-left-bold-hexagon-outline::before{content:\"\\F0051\"}.mdi-arrow-left-bold-outline::before{content:\"\\F09C0\"}.mdi-arrow-left-box::before{content:\"\\F06C1\"}.mdi-arrow-left-circle::before{content:\"\\F0CDD\"}.mdi-arrow-left-circle-outline::before{content:\"\\F0CDE\"}.mdi-arrow-left-drop-circle::before{content:\"\\F0052\"}.mdi-arrow-left-drop-circle-outline::before{content:\"\\F0053\"}.mdi-arrow-left-right::before{content:\"\\F0E73\"}.mdi-arrow-left-right-bold::before{content:\"\\F0E74\"}.mdi-arrow-left-right-bold-outline::before{content:\"\\F09C1\"}.mdi-arrow-left-thick::before{content:\"\\F004E\"}.mdi-arrow-left-thin-circle-outline::before{content:\"\\F159A\"}.mdi-arrow-right::before{content:\"\\F0054\"}.mdi-arrow-right-bold::before{content:\"\\F0734\"}.mdi-arrow-right-bold-box::before{content:\"\\F0735\"}.mdi-arrow-right-bold-box-outline::before{content:\"\\F0736\"}.mdi-arrow-right-bold-circle::before{content:\"\\F0056\"}.mdi-arrow-right-bold-circle-outline::before{content:\"\\F0057\"}.mdi-arrow-right-bold-hexagon-outline::before{content:\"\\F0058\"}.mdi-arrow-right-bold-outline::before{content:\"\\F09C2\"}.mdi-arrow-right-box::before{content:\"\\F06C2\"}.mdi-arrow-right-circle::before{content:\"\\F0CDF\"}.mdi-arrow-right-circle-outline::before{content:\"\\F0CE0\"}.mdi-arrow-right-drop-circle::before{content:\"\\F0059\"}.mdi-arrow-right-drop-circle-outline::before{content:\"\\F005A\"}.mdi-arrow-right-thick::before{content:\"\\F0055\"}.mdi-arrow-right-thin-circle-outline::before{content:\"\\F1598\"}.mdi-arrow-split-horizontal::before{content:\"\\F093B\"}.mdi-arrow-split-vertical::before{content:\"\\F093C\"}.mdi-arrow-top-left::before{content:\"\\F005B\"}.mdi-arrow-top-left-bold-outline::before{content:\"\\F09C3\"}.mdi-arrow-top-left-bottom-right::before{content:\"\\F0E75\"}.mdi-arrow-top-left-bottom-right-bold::before{content:\"\\F0E76\"}.mdi-arrow-top-left-thick::before{content:\"\\F09C4\"}.mdi-arrow-top-left-thin-circle-outline::before{content:\"\\F1593\"}.mdi-arrow-top-right::before{content:\"\\F005C\"}.mdi-arrow-top-right-bold-outline::before{content:\"\\F09C5\"}.mdi-arrow-top-right-bottom-left::before{content:\"\\F0E77\"}.mdi-arrow-top-right-bottom-left-bold::before{content:\"\\F0E78\"}.mdi-arrow-top-right-thick::before{content:\"\\F09C6\"}.mdi-arrow-top-right-thin-circle-outline::before{content:\"\\F1594\"}.mdi-arrow-up::before{content:\"\\F005D\"}.mdi-arrow-up-bold::before{content:\"\\F0737\"}.mdi-arrow-up-bold-box::before{content:\"\\F0738\"}.mdi-arrow-up-bold-box-outline::before{content:\"\\F0739\"}.mdi-arrow-up-bold-circle::before{content:\"\\F005F\"}.mdi-arrow-up-bold-circle-outline::before{content:\"\\F0060\"}.mdi-arrow-up-bold-hexagon-outline::before{content:\"\\F0061\"}.mdi-arrow-up-bold-outline::before{content:\"\\F09C7\"}.mdi-arrow-up-box::before{content:\"\\F06C3\"}.mdi-arrow-up-circle::before{content:\"\\F0CE1\"}.mdi-arrow-up-circle-outline::before{content:\"\\F0CE2\"}.mdi-arrow-up-down::before{content:\"\\F0E79\"}.mdi-arrow-up-down-bold::before{content:\"\\F0E7A\"}.mdi-arrow-up-down-bold-outline::before{content:\"\\F09C8\"}.mdi-arrow-up-drop-circle::before{content:\"\\F0062\"}.mdi-arrow-up-drop-circle-outline::before{content:\"\\F0063\"}.mdi-arrow-up-thick::before{content:\"\\F005E\"}.mdi-arrow-up-thin-circle-outline::before{content:\"\\F1597\"}.mdi-arrow-vertical-lock::before{content:\"\\F115C\"}.mdi-artstation::before{content:\"\\F0B5B\"}.mdi-aspect-ratio::before{content:\"\\F0A24\"}.mdi-assistant::before{content:\"\\F0064\"}.mdi-asterisk::before{content:\"\\F06C4\"}.mdi-at::before{content:\"\\F0065\"}.mdi-atlassian::before{content:\"\\F0804\"}.mdi-atm::before{content:\"\\F0D47\"}.mdi-atom::before{content:\"\\F0768\"}.mdi-atom-variant::before{content:\"\\F0E7B\"}.mdi-attachment::before{content:\"\\F0066\"}.mdi-audio-video::before{content:\"\\F093D\"}.mdi-audio-video-off::before{content:\"\\F11B6\"}.mdi-augmented-reality::before{content:\"\\F0850\"}.mdi-auto-download::before{content:\"\\F137E\"}.mdi-auto-fix::before{content:\"\\F0068\"}.mdi-auto-upload::before{content:\"\\F0069\"}.mdi-autorenew::before{content:\"\\F006A\"}.mdi-av-timer::before{content:\"\\F006B\"}.mdi-aws::before{content:\"\\F0E0F\"}.mdi-axe::before{content:\"\\F08C8\"}.mdi-axis::before{content:\"\\F0D48\"}.mdi-axis-arrow::before{content:\"\\F0D49\"}.mdi-axis-arrow-info::before{content:\"\\F140E\"}.mdi-axis-arrow-lock::before{content:\"\\F0D4A\"}.mdi-axis-lock::before{content:\"\\F0D4B\"}.mdi-axis-x-arrow::before{content:\"\\F0D4C\"}.mdi-axis-x-arrow-lock::before{content:\"\\F0D4D\"}.mdi-axis-x-rotate-clockwise::before{content:\"\\F0D4E\"}.mdi-axis-x-rotate-counterclockwise::before{content:\"\\F0D4F\"}.mdi-axis-x-y-arrow-lock::before{content:\"\\F0D50\"}.mdi-axis-y-arrow::before{content:\"\\F0D51\"}.mdi-axis-y-arrow-lock::before{content:\"\\F0D52\"}.mdi-axis-y-rotate-clockwise::before{content:\"\\F0D53\"}.mdi-axis-y-rotate-counterclockwise::before{content:\"\\F0D54\"}.mdi-axis-z-arrow::before{content:\"\\F0D55\"}.mdi-axis-z-arrow-lock::before{content:\"\\F0D56\"}.mdi-axis-z-rotate-clockwise::before{content:\"\\F0D57\"}.mdi-axis-z-rotate-counterclockwise::before{content:\"\\F0D58\"}.mdi-babel::before{content:\"\\F0A25\"}.mdi-baby::before{content:\"\\F006C\"}.mdi-baby-bottle::before{content:\"\\F0F39\"}.mdi-baby-bottle-outline::before{content:\"\\F0F3A\"}.mdi-baby-buggy::before{content:\"\\F13E0\"}.mdi-baby-carriage::before{content:\"\\F068F\"}.mdi-baby-carriage-off::before{content:\"\\F0FA0\"}.mdi-baby-face::before{content:\"\\F0E7C\"}.mdi-baby-face-outline::before{content:\"\\F0E7D\"}.mdi-backburger::before{content:\"\\F006D\"}.mdi-backspace::before{content:\"\\F006E\"}.mdi-backspace-outline::before{content:\"\\F0B5C\"}.mdi-backspace-reverse::before{content:\"\\F0E7E\"}.mdi-backspace-reverse-outline::before{content:\"\\F0E7F\"}.mdi-backup-restore::before{content:\"\\F006F\"}.mdi-bacteria::before{content:\"\\F0ED5\"}.mdi-bacteria-outline::before{content:\"\\F0ED6\"}.mdi-badge-account::before{content:\"\\F0DA7\"}.mdi-badge-account-alert::before{content:\"\\F0DA8\"}.mdi-badge-account-alert-outline::before{content:\"\\F0DA9\"}.mdi-badge-account-horizontal::before{content:\"\\F0E0D\"}.mdi-badge-account-horizontal-outline::before{content:\"\\F0E0E\"}.mdi-badge-account-outline::before{content:\"\\F0DAA\"}.mdi-badminton::before{content:\"\\F0851\"}.mdi-bag-carry-on::before{content:\"\\F0F3B\"}.mdi-bag-carry-on-check::before{content:\"\\F0D65\"}.mdi-bag-carry-on-off::before{content:\"\\F0F3C\"}.mdi-bag-checked::before{content:\"\\F0F3D\"}.mdi-bag-personal::before{content:\"\\F0E10\"}.mdi-bag-personal-off::before{content:\"\\F0E11\"}.mdi-bag-personal-off-outline::before{content:\"\\F0E12\"}.mdi-bag-personal-outline::before{content:\"\\F0E13\"}.mdi-bag-suitcase::before{content:\"\\F158B\"}.mdi-bag-suitcase-off::before{content:\"\\F158D\"}.mdi-bag-suitcase-off-outline::before{content:\"\\F158E\"}.mdi-bag-suitcase-outline::before{content:\"\\F158C\"}.mdi-baguette::before{content:\"\\F0F3E\"}.mdi-balloon::before{content:\"\\F0A26\"}.mdi-ballot::before{content:\"\\F09C9\"}.mdi-ballot-outline::before{content:\"\\F09CA\"}.mdi-ballot-recount::before{content:\"\\F0C39\"}.mdi-ballot-recount-outline::before{content:\"\\F0C3A\"}.mdi-bandage::before{content:\"\\F0DAF\"}.mdi-bandcamp::before{content:\"\\F0675\"}.mdi-bank::before{content:\"\\F0070\"}.mdi-bank-check::before{content:\"\\F1655\"}.mdi-bank-minus::before{content:\"\\F0DB0\"}.mdi-bank-off::before{content:\"\\F1656\"}.mdi-bank-off-outline::before{content:\"\\F1657\"}.mdi-bank-outline::before{content:\"\\F0E80\"}.mdi-bank-plus::before{content:\"\\F0DB1\"}.mdi-bank-remove::before{content:\"\\F0DB2\"}.mdi-bank-transfer::before{content:\"\\F0A27\"}.mdi-bank-transfer-in::before{content:\"\\F0A28\"}.mdi-bank-transfer-out::before{content:\"\\F0A29\"}.mdi-barcode::before{content:\"\\F0071\"}.mdi-barcode-off::before{content:\"\\F1236\"}.mdi-barcode-scan::before{content:\"\\F0072\"}.mdi-barley::before{content:\"\\F0073\"}.mdi-barley-off::before{content:\"\\F0B5D\"}.mdi-barn::before{content:\"\\F0B5E\"}.mdi-barrel::before{content:\"\\F0074\"}.mdi-baseball::before{content:\"\\F0852\"}.mdi-baseball-bat::before{content:\"\\F0853\"}.mdi-baseball-diamond::before{content:\"\\F15EC\"}.mdi-baseball-diamond-outline::before{content:\"\\F15ED\"}.mdi-bash::before{content:\"\\F1183\"}.mdi-basket::before{content:\"\\F0076\"}.mdi-basket-fill::before{content:\"\\F0077\"}.mdi-basket-minus::before{content:\"\\F1523\"}.mdi-basket-minus-outline::before{content:\"\\F1524\"}.mdi-basket-off::before{content:\"\\F1525\"}.mdi-basket-off-outline::before{content:\"\\F1526\"}.mdi-basket-outline::before{content:\"\\F1181\"}.mdi-basket-plus::before{content:\"\\F1527\"}.mdi-basket-plus-outline::before{content:\"\\F1528\"}.mdi-basket-remove::before{content:\"\\F1529\"}.mdi-basket-remove-outline::before{content:\"\\F152A\"}.mdi-basket-unfill::before{content:\"\\F0078\"}.mdi-basketball::before{content:\"\\F0806\"}.mdi-basketball-hoop::before{content:\"\\F0C3B\"}.mdi-basketball-hoop-outline::before{content:\"\\F0C3C\"}.mdi-bat::before{content:\"\\F0B5F\"}.mdi-battery::before{content:\"\\F0079\"}.mdi-battery-10::before{content:\"\\F007A\"}.mdi-battery-10-bluetooth::before{content:\"\\F093E\"}.mdi-battery-20::before{content:\"\\F007B\"}.mdi-battery-20-bluetooth::before{content:\"\\F093F\"}.mdi-battery-30::before{content:\"\\F007C\"}.mdi-battery-30-bluetooth::before{content:\"\\F0940\"}.mdi-battery-40::before{content:\"\\F007D\"}.mdi-battery-40-bluetooth::before{content:\"\\F0941\"}.mdi-battery-50::before{content:\"\\F007E\"}.mdi-battery-50-bluetooth::before{content:\"\\F0942\"}.mdi-battery-60::before{content:\"\\F007F\"}.mdi-battery-60-bluetooth::before{content:\"\\F0943\"}.mdi-battery-70::before{content:\"\\F0080\"}.mdi-battery-70-bluetooth::before{content:\"\\F0944\"}.mdi-battery-80::before{content:\"\\F0081\"}.mdi-battery-80-bluetooth::before{content:\"\\F0945\"}.mdi-battery-90::before{content:\"\\F0082\"}.mdi-battery-90-bluetooth::before{content:\"\\F0946\"}.mdi-battery-alert::before{content:\"\\F0083\"}.mdi-battery-alert-bluetooth::before{content:\"\\F0947\"}.mdi-battery-alert-variant::before{content:\"\\F10CC\"}.mdi-battery-alert-variant-outline::before{content:\"\\F10CD\"}.mdi-battery-bluetooth::before{content:\"\\F0948\"}.mdi-battery-bluetooth-variant::before{content:\"\\F0949\"}.mdi-battery-charging::before{content:\"\\F0084\"}.mdi-battery-charging-10::before{content:\"\\F089C\"}.mdi-battery-charging-100::before{content:\"\\F0085\"}.mdi-battery-charging-20::before{content:\"\\F0086\"}.mdi-battery-charging-30::before{content:\"\\F0087\"}.mdi-battery-charging-40::before{content:\"\\F0088\"}.mdi-battery-charging-50::before{content:\"\\F089D\"}.mdi-battery-charging-60::before{content:\"\\F0089\"}.mdi-battery-charging-70::before{content:\"\\F089E\"}.mdi-battery-charging-80::before{content:\"\\F008A\"}.mdi-battery-charging-90::before{content:\"\\F008B\"}.mdi-battery-charging-high::before{content:\"\\F12A6\"}.mdi-battery-charging-low::before{content:\"\\F12A4\"}.mdi-battery-charging-medium::before{content:\"\\F12A5\"}.mdi-battery-charging-outline::before{content:\"\\F089F\"}.mdi-battery-charging-wireless::before{content:\"\\F0807\"}.mdi-battery-charging-wireless-10::before{content:\"\\F0808\"}.mdi-battery-charging-wireless-20::before{content:\"\\F0809\"}.mdi-battery-charging-wireless-30::before{content:\"\\F080A\"}.mdi-battery-charging-wireless-40::before{content:\"\\F080B\"}.mdi-battery-charging-wireless-50::before{content:\"\\F080C\"}.mdi-battery-charging-wireless-60::before{content:\"\\F080D\"}.mdi-battery-charging-wireless-70::before{content:\"\\F080E\"}.mdi-battery-charging-wireless-80::before{content:\"\\F080F\"}.mdi-battery-charging-wireless-90::before{content:\"\\F0810\"}.mdi-battery-charging-wireless-alert::before{content:\"\\F0811\"}.mdi-battery-charging-wireless-outline::before{content:\"\\F0812\"}.mdi-battery-heart::before{content:\"\\F120F\"}.mdi-battery-heart-outline::before{content:\"\\F1210\"}.mdi-battery-heart-variant::before{content:\"\\F1211\"}.mdi-battery-high::before{content:\"\\F12A3\"}.mdi-battery-low::before{content:\"\\F12A1\"}.mdi-battery-medium::before{content:\"\\F12A2\"}.mdi-battery-minus::before{content:\"\\F008C\"}.mdi-battery-negative::before{content:\"\\F008D\"}.mdi-battery-off::before{content:\"\\F125D\"}.mdi-battery-off-outline::before{content:\"\\F125E\"}.mdi-battery-outline::before{content:\"\\F008E\"}.mdi-battery-plus::before{content:\"\\F008F\"}.mdi-battery-positive::before{content:\"\\F0090\"}.mdi-battery-unknown::before{content:\"\\F0091\"}.mdi-battery-unknown-bluetooth::before{content:\"\\F094A\"}.mdi-battlenet::before{content:\"\\F0B60\"}.mdi-beach::before{content:\"\\F0092\"}.mdi-beaker::before{content:\"\\F0CEA\"}.mdi-beaker-alert::before{content:\"\\F1229\"}.mdi-beaker-alert-outline::before{content:\"\\F122A\"}.mdi-beaker-check::before{content:\"\\F122B\"}.mdi-beaker-check-outline::before{content:\"\\F122C\"}.mdi-beaker-minus::before{content:\"\\F122D\"}.mdi-beaker-minus-outline::before{content:\"\\F122E\"}.mdi-beaker-outline::before{content:\"\\F0690\"}.mdi-beaker-plus::before{content:\"\\F122F\"}.mdi-beaker-plus-outline::before{content:\"\\F1230\"}.mdi-beaker-question::before{content:\"\\F1231\"}.mdi-beaker-question-outline::before{content:\"\\F1232\"}.mdi-beaker-remove::before{content:\"\\F1233\"}.mdi-beaker-remove-outline::before{content:\"\\F1234\"}.mdi-bed::before{content:\"\\F02E3\"}.mdi-bed-double::before{content:\"\\F0FD4\"}.mdi-bed-double-outline::before{content:\"\\F0FD3\"}.mdi-bed-empty::before{content:\"\\F08A0\"}.mdi-bed-king::before{content:\"\\F0FD2\"}.mdi-bed-king-outline::before{content:\"\\F0FD1\"}.mdi-bed-outline::before{content:\"\\F0099\"}.mdi-bed-queen::before{content:\"\\F0FD0\"}.mdi-bed-queen-outline::before{content:\"\\F0FDB\"}.mdi-bed-single::before{content:\"\\F106D\"}.mdi-bed-single-outline::before{content:\"\\F106E\"}.mdi-bee::before{content:\"\\F0FA1\"}.mdi-bee-flower::before{content:\"\\F0FA2\"}.mdi-beehive-off-outline::before{content:\"\\F13ED\"}.mdi-beehive-outline::before{content:\"\\F10CE\"}.mdi-beekeeper::before{content:\"\\F14E2\"}.mdi-beer::before{content:\"\\F0098\"}.mdi-beer-outline::before{content:\"\\F130C\"}.mdi-bell::before{content:\"\\F009A\"}.mdi-bell-alert::before{content:\"\\F0D59\"}.mdi-bell-alert-outline::before{content:\"\\F0E81\"}.mdi-bell-cancel::before{content:\"\\F13E7\"}.mdi-bell-cancel-outline::before{content:\"\\F13E8\"}.mdi-bell-check::before{content:\"\\F11E5\"}.mdi-bell-check-outline::before{content:\"\\F11E6\"}.mdi-bell-circle::before{content:\"\\F0D5A\"}.mdi-bell-circle-outline::before{content:\"\\F0D5B\"}.mdi-bell-minus::before{content:\"\\F13E9\"}.mdi-bell-minus-outline::before{content:\"\\F13EA\"}.mdi-bell-off::before{content:\"\\F009B\"}.mdi-bell-off-outline::before{content:\"\\F0A91\"}.mdi-bell-outline::before{content:\"\\F009C\"}.mdi-bell-plus::before{content:\"\\F009D\"}.mdi-bell-plus-outline::before{content:\"\\F0A92\"}.mdi-bell-remove::before{content:\"\\F13EB\"}.mdi-bell-remove-outline::before{content:\"\\F13EC\"}.mdi-bell-ring::before{content:\"\\F009E\"}.mdi-bell-ring-outline::before{content:\"\\F009F\"}.mdi-bell-sleep::before{content:\"\\F00A0\"}.mdi-bell-sleep-outline::before{content:\"\\F0A93\"}.mdi-beta::before{content:\"\\F00A1\"}.mdi-betamax::before{content:\"\\F09CB\"}.mdi-biathlon::before{content:\"\\F0E14\"}.mdi-bicycle::before{content:\"\\F109C\"}.mdi-bicycle-basket::before{content:\"\\F1235\"}.mdi-bicycle-electric::before{content:\"\\F15B4\"}.mdi-bicycle-penny-farthing::before{content:\"\\F15E9\"}.mdi-bike::before{content:\"\\F00A3\"}.mdi-bike-fast::before{content:\"\\F111F\"}.mdi-billboard::before{content:\"\\F1010\"}.mdi-billiards::before{content:\"\\F0B61\"}.mdi-billiards-rack::before{content:\"\\F0B62\"}.mdi-binoculars::before{content:\"\\F00A5\"}.mdi-bio::before{content:\"\\F00A6\"}.mdi-biohazard::before{content:\"\\F00A7\"}.mdi-bird::before{content:\"\\F15C6\"}.mdi-bitbucket::before{content:\"\\F00A8\"}.mdi-bitcoin::before{content:\"\\F0813\"}.mdi-black-mesa::before{content:\"\\F00A9\"}.mdi-blender::before{content:\"\\F0CEB\"}.mdi-blender-software::before{content:\"\\F00AB\"}.mdi-blinds::before{content:\"\\F00AC\"}.mdi-blinds-open::before{content:\"\\F1011\"}.mdi-block-helper::before{content:\"\\F00AD\"}.mdi-blogger::before{content:\"\\F00AE\"}.mdi-blood-bag::before{content:\"\\F0CEC\"}.mdi-bluetooth::before{content:\"\\F00AF\"}.mdi-bluetooth-audio::before{content:\"\\F00B0\"}.mdi-bluetooth-connect::before{content:\"\\F00B1\"}.mdi-bluetooth-off::before{content:\"\\F00B2\"}.mdi-bluetooth-settings::before{content:\"\\F00B3\"}.mdi-bluetooth-transfer::before{content:\"\\F00B4\"}.mdi-blur::before{content:\"\\F00B5\"}.mdi-blur-linear::before{content:\"\\F00B6\"}.mdi-blur-off::before{content:\"\\F00B7\"}.mdi-blur-radial::before{content:\"\\F00B8\"}.mdi-bolnisi-cross::before{content:\"\\F0CED\"}.mdi-bolt::before{content:\"\\F0DB3\"}.mdi-bomb::before{content:\"\\F0691\"}.mdi-bomb-off::before{content:\"\\F06C5\"}.mdi-bone::before{content:\"\\F00B9\"}.mdi-book::before{content:\"\\F00BA\"}.mdi-book-account::before{content:\"\\F13AD\"}.mdi-book-account-outline::before{content:\"\\F13AE\"}.mdi-book-alphabet::before{content:\"\\F061D\"}.mdi-book-check::before{content:\"\\F14F3\"}.mdi-book-check-outline::before{content:\"\\F14F4\"}.mdi-book-cross::before{content:\"\\F00A2\"}.mdi-book-information-variant::before{content:\"\\F106F\"}.mdi-book-lock::before{content:\"\\F079A\"}.mdi-book-lock-open::before{content:\"\\F079B\"}.mdi-book-minus::before{content:\"\\F05D9\"}.mdi-book-minus-multiple::before{content:\"\\F0A94\"}.mdi-book-minus-multiple-outline::before{content:\"\\F090B\"}.mdi-book-multiple::before{content:\"\\F00BB\"}.mdi-book-multiple-outline::before{content:\"\\F0436\"}.mdi-book-music::before{content:\"\\F0067\"}.mdi-book-open::before{content:\"\\F00BD\"}.mdi-book-open-blank-variant::before{content:\"\\F00BE\"}.mdi-book-open-outline::before{content:\"\\F0B63\"}.mdi-book-open-page-variant::before{content:\"\\F05DA\"}.mdi-book-open-page-variant-outline::before{content:\"\\F15D6\"}.mdi-book-open-variant::before{content:\"\\F14F7\"}.mdi-book-outline::before{content:\"\\F0B64\"}.mdi-book-play::before{content:\"\\F0E82\"}.mdi-book-play-outline::before{content:\"\\F0E83\"}.mdi-book-plus::before{content:\"\\F05DB\"}.mdi-book-plus-multiple::before{content:\"\\F0A95\"}.mdi-book-plus-multiple-outline::before{content:\"\\F0ADE\"}.mdi-book-remove::before{content:\"\\F0A97\"}.mdi-book-remove-multiple::before{content:\"\\F0A96\"}.mdi-book-remove-multiple-outline::before{content:\"\\F04CA\"}.mdi-book-search::before{content:\"\\F0E84\"}.mdi-book-search-outline::before{content:\"\\F0E85\"}.mdi-book-variant::before{content:\"\\F00BF\"}.mdi-book-variant-multiple::before{content:\"\\F00BC\"}.mdi-bookmark::before{content:\"\\F00C0\"}.mdi-bookmark-check::before{content:\"\\F00C1\"}.mdi-bookmark-check-outline::before{content:\"\\F137B\"}.mdi-bookmark-minus::before{content:\"\\F09CC\"}.mdi-bookmark-minus-outline::before{content:\"\\F09CD\"}.mdi-bookmark-multiple::before{content:\"\\F0E15\"}.mdi-bookmark-multiple-outline::before{content:\"\\F0E16\"}.mdi-bookmark-music::before{content:\"\\F00C2\"}.mdi-bookmark-music-outline::before{content:\"\\F1379\"}.mdi-bookmark-off::before{content:\"\\F09CE\"}.mdi-bookmark-off-outline::before{content:\"\\F09CF\"}.mdi-bookmark-outline::before{content:\"\\F00C3\"}.mdi-bookmark-plus::before{content:\"\\F00C5\"}.mdi-bookmark-plus-outline::before{content:\"\\F00C4\"}.mdi-bookmark-remove::before{content:\"\\F00C6\"}.mdi-bookmark-remove-outline::before{content:\"\\F137A\"}.mdi-bookshelf::before{content:\"\\F125F\"}.mdi-boom-gate::before{content:\"\\F0E86\"}.mdi-boom-gate-alert::before{content:\"\\F0E87\"}.mdi-boom-gate-alert-outline::before{content:\"\\F0E88\"}.mdi-boom-gate-down::before{content:\"\\F0E89\"}.mdi-boom-gate-down-outline::before{content:\"\\F0E8A\"}.mdi-boom-gate-outline::before{content:\"\\F0E8B\"}.mdi-boom-gate-up::before{content:\"\\F0E8C\"}.mdi-boom-gate-up-outline::before{content:\"\\F0E8D\"}.mdi-boombox::before{content:\"\\F05DC\"}.mdi-boomerang::before{content:\"\\F10CF\"}.mdi-bootstrap::before{content:\"\\F06C6\"}.mdi-border-all::before{content:\"\\F00C7\"}.mdi-border-all-variant::before{content:\"\\F08A1\"}.mdi-border-bottom::before{content:\"\\F00C8\"}.mdi-border-bottom-variant::before{content:\"\\F08A2\"}.mdi-border-color::before{content:\"\\F00C9\"}.mdi-border-horizontal::before{content:\"\\F00CA\"}.mdi-border-inside::before{content:\"\\F00CB\"}.mdi-border-left::before{content:\"\\F00CC\"}.mdi-border-left-variant::before{content:\"\\F08A3\"}.mdi-border-none::before{content:\"\\F00CD\"}.mdi-border-none-variant::before{content:\"\\F08A4\"}.mdi-border-outside::before{content:\"\\F00CE\"}.mdi-border-right::before{content:\"\\F00CF\"}.mdi-border-right-variant::before{content:\"\\F08A5\"}.mdi-border-style::before{content:\"\\F00D0\"}.mdi-border-top::before{content:\"\\F00D1\"}.mdi-border-top-variant::before{content:\"\\F08A6\"}.mdi-border-vertical::before{content:\"\\F00D2\"}.mdi-bottle-soda::before{content:\"\\F1070\"}.mdi-bottle-soda-classic::before{content:\"\\F1071\"}.mdi-bottle-soda-classic-outline::before{content:\"\\F1363\"}.mdi-bottle-soda-outline::before{content:\"\\F1072\"}.mdi-bottle-tonic::before{content:\"\\F112E\"}.mdi-bottle-tonic-outline::before{content:\"\\F112F\"}.mdi-bottle-tonic-plus::before{content:\"\\F1130\"}.mdi-bottle-tonic-plus-outline::before{content:\"\\F1131\"}.mdi-bottle-tonic-skull::before{content:\"\\F1132\"}.mdi-bottle-tonic-skull-outline::before{content:\"\\F1133\"}.mdi-bottle-wine::before{content:\"\\F0854\"}.mdi-bottle-wine-outline::before{content:\"\\F1310\"}.mdi-bow-tie::before{content:\"\\F0678\"}.mdi-bowl::before{content:\"\\F028E\"}.mdi-bowl-mix::before{content:\"\\F0617\"}.mdi-bowl-mix-outline::before{content:\"\\F02E4\"}.mdi-bowl-outline::before{content:\"\\F02A9\"}.mdi-bowling::before{content:\"\\F00D3\"}.mdi-box::before{content:\"\\F00D4\"}.mdi-box-cutter::before{content:\"\\F00D5\"}.mdi-box-cutter-off::before{content:\"\\F0B4A\"}.mdi-box-shadow::before{content:\"\\F0637\"}.mdi-boxing-glove::before{content:\"\\F0B65\"}.mdi-braille::before{content:\"\\F09D0\"}.mdi-brain::before{content:\"\\F09D1\"}.mdi-bread-slice::before{content:\"\\F0CEE\"}.mdi-bread-slice-outline::before{content:\"\\F0CEF\"}.mdi-bridge::before{content:\"\\F0618\"}.mdi-briefcase::before{content:\"\\F00D6\"}.mdi-briefcase-account::before{content:\"\\F0CF0\"}.mdi-briefcase-account-outline::before{content:\"\\F0CF1\"}.mdi-briefcase-check::before{content:\"\\F00D7\"}.mdi-briefcase-check-outline::before{content:\"\\F131E\"}.mdi-briefcase-clock::before{content:\"\\F10D0\"}.mdi-briefcase-clock-outline::before{content:\"\\F10D1\"}.mdi-briefcase-download::before{content:\"\\F00D8\"}.mdi-briefcase-download-outline::before{content:\"\\F0C3D\"}.mdi-briefcase-edit::before{content:\"\\F0A98\"}.mdi-briefcase-edit-outline::before{content:\"\\F0C3E\"}.mdi-briefcase-minus::before{content:\"\\F0A2A\"}.mdi-briefcase-minus-outline::before{content:\"\\F0C3F\"}.mdi-briefcase-off::before{content:\"\\F1658\"}.mdi-briefcase-off-outline::before{content:\"\\F1659\"}.mdi-briefcase-outline::before{content:\"\\F0814\"}.mdi-briefcase-plus::before{content:\"\\F0A2B\"}.mdi-briefcase-plus-outline::before{content:\"\\F0C40\"}.mdi-briefcase-remove::before{content:\"\\F0A2C\"}.mdi-briefcase-remove-outline::before{content:\"\\F0C41\"}.mdi-briefcase-search::before{content:\"\\F0A2D\"}.mdi-briefcase-search-outline::before{content:\"\\F0C42\"}.mdi-briefcase-upload::before{content:\"\\F00D9\"}.mdi-briefcase-upload-outline::before{content:\"\\F0C43\"}.mdi-briefcase-variant::before{content:\"\\F1494\"}.mdi-briefcase-variant-off::before{content:\"\\F165A\"}.mdi-briefcase-variant-off-outline::before{content:\"\\F165B\"}.mdi-briefcase-variant-outline::before{content:\"\\F1495\"}.mdi-brightness-1::before{content:\"\\F00DA\"}.mdi-brightness-2::before{content:\"\\F00DB\"}.mdi-brightness-3::before{content:\"\\F00DC\"}.mdi-brightness-4::before{content:\"\\F00DD\"}.mdi-brightness-5::before{content:\"\\F00DE\"}.mdi-brightness-6::before{content:\"\\F00DF\"}.mdi-brightness-7::before{content:\"\\F00E0\"}.mdi-brightness-auto::before{content:\"\\F00E1\"}.mdi-brightness-percent::before{content:\"\\F0CF2\"}.mdi-broom::before{content:\"\\F00E2\"}.mdi-brush::before{content:\"\\F00E3\"}.mdi-bucket::before{content:\"\\F1415\"}.mdi-bucket-outline::before{content:\"\\F1416\"}.mdi-buddhism::before{content:\"\\F094B\"}.mdi-buffer::before{content:\"\\F0619\"}.mdi-buffet::before{content:\"\\F0578\"}.mdi-bug::before{content:\"\\F00E4\"}.mdi-bug-check::before{content:\"\\F0A2E\"}.mdi-bug-check-outline::before{content:\"\\F0A2F\"}.mdi-bug-outline::before{content:\"\\F0A30\"}.mdi-bugle::before{content:\"\\F0DB4\"}.mdi-bulldozer::before{content:\"\\F0B22\"}.mdi-bullet::before{content:\"\\F0CF3\"}.mdi-bulletin-board::before{content:\"\\F00E5\"}.mdi-bullhorn::before{content:\"\\F00E6\"}.mdi-bullhorn-outline::before{content:\"\\F0B23\"}.mdi-bullseye::before{content:\"\\F05DD\"}.mdi-bullseye-arrow::before{content:\"\\F08C9\"}.mdi-bulma::before{content:\"\\F12E7\"}.mdi-bunk-bed::before{content:\"\\F1302\"}.mdi-bunk-bed-outline::before{content:\"\\F0097\"}.mdi-bus::before{content:\"\\F00E7\"}.mdi-bus-alert::before{content:\"\\F0A99\"}.mdi-bus-articulated-end::before{content:\"\\F079C\"}.mdi-bus-articulated-front::before{content:\"\\F079D\"}.mdi-bus-clock::before{content:\"\\F08CA\"}.mdi-bus-double-decker::before{content:\"\\F079E\"}.mdi-bus-marker::before{content:\"\\F1212\"}.mdi-bus-multiple::before{content:\"\\F0F3F\"}.mdi-bus-school::before{content:\"\\F079F\"}.mdi-bus-side::before{content:\"\\F07A0\"}.mdi-bus-stop::before{content:\"\\F1012\"}.mdi-bus-stop-covered::before{content:\"\\F1013\"}.mdi-bus-stop-uncovered::before{content:\"\\F1014\"}.mdi-butterfly::before{content:\"\\F1589\"}.mdi-butterfly-outline::before{content:\"\\F158A\"}.mdi-cable-data::before{content:\"\\F1394\"}.mdi-cached::before{content:\"\\F00E8\"}.mdi-cactus::before{content:\"\\F0DB5\"}.mdi-cake::before{content:\"\\F00E9\"}.mdi-cake-layered::before{content:\"\\F00EA\"}.mdi-cake-variant::before{content:\"\\F00EB\"}.mdi-calculator::before{content:\"\\F00EC\"}.mdi-calculator-variant::before{content:\"\\F0A9A\"}.mdi-calculator-variant-outline::before{content:\"\\F15A6\"}.mdi-calendar::before{content:\"\\F00ED\"}.mdi-calendar-account::before{content:\"\\F0ED7\"}.mdi-calendar-account-outline::before{content:\"\\F0ED8\"}.mdi-calendar-alert::before{content:\"\\F0A31\"}.mdi-calendar-arrow-left::before{content:\"\\F1134\"}.mdi-calendar-arrow-right::before{content:\"\\F1135\"}.mdi-calendar-blank::before{content:\"\\F00EE\"}.mdi-calendar-blank-multiple::before{content:\"\\F1073\"}.mdi-calendar-blank-outline::before{content:\"\\F0B66\"}.mdi-calendar-check::before{content:\"\\F00EF\"}.mdi-calendar-check-outline::before{content:\"\\F0C44\"}.mdi-calendar-clock::before{content:\"\\F00F0\"}.mdi-calendar-cursor::before{content:\"\\F157B\"}.mdi-calendar-edit::before{content:\"\\F08A7\"}.mdi-calendar-end::before{content:\"\\F166C\"}.mdi-calendar-export::before{content:\"\\F0B24\"}.mdi-calendar-heart::before{content:\"\\F09D2\"}.mdi-calendar-import::before{content:\"\\F0B25\"}.mdi-calendar-lock::before{content:\"\\F1641\"}.mdi-calendar-lock-outline::before{content:\"\\F1642\"}.mdi-calendar-minus::before{content:\"\\F0D5C\"}.mdi-calendar-month::before{content:\"\\F0E17\"}.mdi-calendar-month-outline::before{content:\"\\F0E18\"}.mdi-calendar-multiple::before{content:\"\\F00F1\"}.mdi-calendar-multiple-check::before{content:\"\\F00F2\"}.mdi-calendar-multiselect::before{content:\"\\F0A32\"}.mdi-calendar-outline::before{content:\"\\F0B67\"}.mdi-calendar-plus::before{content:\"\\F00F3\"}.mdi-calendar-question::before{content:\"\\F0692\"}.mdi-calendar-range::before{content:\"\\F0679\"}.mdi-calendar-range-outline::before{content:\"\\F0B68\"}.mdi-calendar-refresh::before{content:\"\\F01E1\"}.mdi-calendar-refresh-outline::before{content:\"\\F0203\"}.mdi-calendar-remove::before{content:\"\\F00F4\"}.mdi-calendar-remove-outline::before{content:\"\\F0C45\"}.mdi-calendar-search::before{content:\"\\F094C\"}.mdi-calendar-star::before{content:\"\\F09D3\"}.mdi-calendar-start::before{content:\"\\F166D\"}.mdi-calendar-sync::before{content:\"\\F0E8E\"}.mdi-calendar-sync-outline::before{content:\"\\F0E8F\"}.mdi-calendar-text::before{content:\"\\F00F5\"}.mdi-calendar-text-outline::before{content:\"\\F0C46\"}.mdi-calendar-today::before{content:\"\\F00F6\"}.mdi-calendar-week::before{content:\"\\F0A33\"}.mdi-calendar-week-begin::before{content:\"\\F0A34\"}.mdi-calendar-weekend::before{content:\"\\F0ED9\"}.mdi-calendar-weekend-outline::before{content:\"\\F0EDA\"}.mdi-call-made::before{content:\"\\F00F7\"}.mdi-call-merge::before{content:\"\\F00F8\"}.mdi-call-missed::before{content:\"\\F00F9\"}.mdi-call-received::before{content:\"\\F00FA\"}.mdi-call-split::before{content:\"\\F00FB\"}.mdi-camcorder::before{content:\"\\F00FC\"}.mdi-camcorder-off::before{content:\"\\F00FF\"}.mdi-camera::before{content:\"\\F0100\"}.mdi-camera-account::before{content:\"\\F08CB\"}.mdi-camera-burst::before{content:\"\\F0693\"}.mdi-camera-control::before{content:\"\\F0B69\"}.mdi-camera-enhance::before{content:\"\\F0101\"}.mdi-camera-enhance-outline::before{content:\"\\F0B6A\"}.mdi-camera-flip::before{content:\"\\F15D9\"}.mdi-camera-flip-outline::before{content:\"\\F15DA\"}.mdi-camera-front::before{content:\"\\F0102\"}.mdi-camera-front-variant::before{content:\"\\F0103\"}.mdi-camera-gopro::before{content:\"\\F07A1\"}.mdi-camera-image::before{content:\"\\F08CC\"}.mdi-camera-iris::before{content:\"\\F0104\"}.mdi-camera-metering-center::before{content:\"\\F07A2\"}.mdi-camera-metering-matrix::before{content:\"\\F07A3\"}.mdi-camera-metering-partial::before{content:\"\\F07A4\"}.mdi-camera-metering-spot::before{content:\"\\F07A5\"}.mdi-camera-off::before{content:\"\\F05DF\"}.mdi-camera-outline::before{content:\"\\F0D5D\"}.mdi-camera-party-mode::before{content:\"\\F0105\"}.mdi-camera-plus::before{content:\"\\F0EDB\"}.mdi-camera-plus-outline::before{content:\"\\F0EDC\"}.mdi-camera-rear::before{content:\"\\F0106\"}.mdi-camera-rear-variant::before{content:\"\\F0107\"}.mdi-camera-retake::before{content:\"\\F0E19\"}.mdi-camera-retake-outline::before{content:\"\\F0E1A\"}.mdi-camera-switch::before{content:\"\\F0108\"}.mdi-camera-switch-outline::before{content:\"\\F084A\"}.mdi-camera-timer::before{content:\"\\F0109\"}.mdi-camera-wireless::before{content:\"\\F0DB6\"}.mdi-camera-wireless-outline::before{content:\"\\F0DB7\"}.mdi-campfire::before{content:\"\\F0EDD\"}.mdi-cancel::before{content:\"\\F073A\"}.mdi-candle::before{content:\"\\F05E2\"}.mdi-candycane::before{content:\"\\F010A\"}.mdi-cannabis::before{content:\"\\F07A6\"}.mdi-cannabis-off::before{content:\"\\F166E\"}.mdi-caps-lock::before{content:\"\\F0A9B\"}.mdi-car::before{content:\"\\F010B\"}.mdi-car-2-plus::before{content:\"\\F1015\"}.mdi-car-3-plus::before{content:\"\\F1016\"}.mdi-car-arrow-left::before{content:\"\\F13B2\"}.mdi-car-arrow-right::before{content:\"\\F13B3\"}.mdi-car-back::before{content:\"\\F0E1B\"}.mdi-car-battery::before{content:\"\\F010C\"}.mdi-car-brake-abs::before{content:\"\\F0C47\"}.mdi-car-brake-alert::before{content:\"\\F0C48\"}.mdi-car-brake-hold::before{content:\"\\F0D5E\"}.mdi-car-brake-parking::before{content:\"\\F0D5F\"}.mdi-car-brake-retarder::before{content:\"\\F1017\"}.mdi-car-child-seat::before{content:\"\\F0FA3\"}.mdi-car-clutch::before{content:\"\\F1018\"}.mdi-car-cog::before{content:\"\\F13CC\"}.mdi-car-connected::before{content:\"\\F010D\"}.mdi-car-convertible::before{content:\"\\F07A7\"}.mdi-car-coolant-level::before{content:\"\\F1019\"}.mdi-car-cruise-control::before{content:\"\\F0D60\"}.mdi-car-defrost-front::before{content:\"\\F0D61\"}.mdi-car-defrost-rear::before{content:\"\\F0D62\"}.mdi-car-door::before{content:\"\\F0B6B\"}.mdi-car-door-lock::before{content:\"\\F109D\"}.mdi-car-electric::before{content:\"\\F0B6C\"}.mdi-car-electric-outline::before{content:\"\\F15B5\"}.mdi-car-emergency::before{content:\"\\F160F\"}.mdi-car-esp::before{content:\"\\F0C49\"}.mdi-car-estate::before{content:\"\\F07A8\"}.mdi-car-hatchback::before{content:\"\\F07A9\"}.mdi-car-info::before{content:\"\\F11BE\"}.mdi-car-key::before{content:\"\\F0B6D\"}.mdi-car-lifted-pickup::before{content:\"\\F152D\"}.mdi-car-light-dimmed::before{content:\"\\F0C4A\"}.mdi-car-light-fog::before{content:\"\\F0C4B\"}.mdi-car-light-high::before{content:\"\\F0C4C\"}.mdi-car-limousine::before{content:\"\\F08CD\"}.mdi-car-multiple::before{content:\"\\F0B6E\"}.mdi-car-off::before{content:\"\\F0E1C\"}.mdi-car-outline::before{content:\"\\F14ED\"}.mdi-car-parking-lights::before{content:\"\\F0D63\"}.mdi-car-pickup::before{content:\"\\F07AA\"}.mdi-car-seat::before{content:\"\\F0FA4\"}.mdi-car-seat-cooler::before{content:\"\\F0FA5\"}.mdi-car-seat-heater::before{content:\"\\F0FA6\"}.mdi-car-settings::before{content:\"\\F13CD\"}.mdi-car-shift-pattern::before{content:\"\\F0F40\"}.mdi-car-side::before{content:\"\\F07AB\"}.mdi-car-sports::before{content:\"\\F07AC\"}.mdi-car-tire-alert::before{content:\"\\F0C4D\"}.mdi-car-traction-control::before{content:\"\\F0D64\"}.mdi-car-turbocharger::before{content:\"\\F101A\"}.mdi-car-wash::before{content:\"\\F010E\"}.mdi-car-windshield::before{content:\"\\F101B\"}.mdi-car-windshield-outline::before{content:\"\\F101C\"}.mdi-carabiner::before{content:\"\\F14C0\"}.mdi-caravan::before{content:\"\\F07AD\"}.mdi-card::before{content:\"\\F0B6F\"}.mdi-card-account-details::before{content:\"\\F05D2\"}.mdi-card-account-details-outline::before{content:\"\\F0DAB\"}.mdi-card-account-details-star::before{content:\"\\F02A3\"}.mdi-card-account-details-star-outline::before{content:\"\\F06DB\"}.mdi-card-account-mail::before{content:\"\\F018E\"}.mdi-card-account-mail-outline::before{content:\"\\F0E98\"}.mdi-card-account-phone::before{content:\"\\F0E99\"}.mdi-card-account-phone-outline::before{content:\"\\F0E9A\"}.mdi-card-bulleted::before{content:\"\\F0B70\"}.mdi-card-bulleted-off::before{content:\"\\F0B71\"}.mdi-card-bulleted-off-outline::before{content:\"\\F0B72\"}.mdi-card-bulleted-outline::before{content:\"\\F0B73\"}.mdi-card-bulleted-settings::before{content:\"\\F0B74\"}.mdi-card-bulleted-settings-outline::before{content:\"\\F0B75\"}.mdi-card-minus::before{content:\"\\F1600\"}.mdi-card-minus-outline::before{content:\"\\F1601\"}.mdi-card-off::before{content:\"\\F1602\"}.mdi-card-off-outline::before{content:\"\\F1603\"}.mdi-card-outline::before{content:\"\\F0B76\"}.mdi-card-plus::before{content:\"\\F11FF\"}.mdi-card-plus-outline::before{content:\"\\F1200\"}.mdi-card-remove::before{content:\"\\F1604\"}.mdi-card-remove-outline::before{content:\"\\F1605\"}.mdi-card-search::before{content:\"\\F1074\"}.mdi-card-search-outline::before{content:\"\\F1075\"}.mdi-card-text::before{content:\"\\F0B77\"}.mdi-card-text-outline::before{content:\"\\F0B78\"}.mdi-cards::before{content:\"\\F0638\"}.mdi-cards-club::before{content:\"\\F08CE\"}.mdi-cards-diamond::before{content:\"\\F08CF\"}.mdi-cards-diamond-outline::before{content:\"\\F101D\"}.mdi-cards-heart::before{content:\"\\F08D0\"}.mdi-cards-outline::before{content:\"\\F0639\"}.mdi-cards-playing-outline::before{content:\"\\F063A\"}.mdi-cards-spade::before{content:\"\\F08D1\"}.mdi-cards-variant::before{content:\"\\F06C7\"}.mdi-carrot::before{content:\"\\F010F\"}.mdi-cart::before{content:\"\\F0110\"}.mdi-cart-arrow-down::before{content:\"\\F0D66\"}.mdi-cart-arrow-right::before{content:\"\\F0C4E\"}.mdi-cart-arrow-up::before{content:\"\\F0D67\"}.mdi-cart-check::before{content:\"\\F15EA\"}.mdi-cart-minus::before{content:\"\\F0D68\"}.mdi-cart-off::before{content:\"\\F066B\"}.mdi-cart-outline::before{content:\"\\F0111\"}.mdi-cart-plus::before{content:\"\\F0112\"}.mdi-cart-remove::before{content:\"\\F0D69\"}.mdi-cart-variant::before{content:\"\\F15EB\"}.mdi-case-sensitive-alt::before{content:\"\\F0113\"}.mdi-cash::before{content:\"\\F0114\"}.mdi-cash-100::before{content:\"\\F0115\"}.mdi-cash-check::before{content:\"\\F14EE\"}.mdi-cash-lock::before{content:\"\\F14EA\"}.mdi-cash-lock-open::before{content:\"\\F14EB\"}.mdi-cash-marker::before{content:\"\\F0DB8\"}.mdi-cash-minus::before{content:\"\\F1260\"}.mdi-cash-multiple::before{content:\"\\F0116\"}.mdi-cash-plus::before{content:\"\\F1261\"}.mdi-cash-refund::before{content:\"\\F0A9C\"}.mdi-cash-register::before{content:\"\\F0CF4\"}.mdi-cash-remove::before{content:\"\\F1262\"}.mdi-cash-usd::before{content:\"\\F1176\"}.mdi-cash-usd-outline::before{content:\"\\F0117\"}.mdi-cassette::before{content:\"\\F09D4\"}.mdi-cast::before{content:\"\\F0118\"}.mdi-cast-audio::before{content:\"\\F101E\"}.mdi-cast-connected::before{content:\"\\F0119\"}.mdi-cast-education::before{content:\"\\F0E1D\"}.mdi-cast-off::before{content:\"\\F078A\"}.mdi-castle::before{content:\"\\F011A\"}.mdi-cat::before{content:\"\\F011B\"}.mdi-cctv::before{content:\"\\F07AE\"}.mdi-ceiling-light::before{content:\"\\F0769\"}.mdi-cellphone::before{content:\"\\F011C\"}.mdi-cellphone-android::before{content:\"\\F011D\"}.mdi-cellphone-arrow-down::before{content:\"\\F09D5\"}.mdi-cellphone-basic::before{content:\"\\F011E\"}.mdi-cellphone-charging::before{content:\"\\F1397\"}.mdi-cellphone-cog::before{content:\"\\F0951\"}.mdi-cellphone-dock::before{content:\"\\F011F\"}.mdi-cellphone-erase::before{content:\"\\F094D\"}.mdi-cellphone-information::before{content:\"\\F0F41\"}.mdi-cellphone-iphone::before{content:\"\\F0120\"}.mdi-cellphone-key::before{content:\"\\F094E\"}.mdi-cellphone-link::before{content:\"\\F0121\"}.mdi-cellphone-link-off::before{content:\"\\F0122\"}.mdi-cellphone-lock::before{content:\"\\F094F\"}.mdi-cellphone-message::before{content:\"\\F08D3\"}.mdi-cellphone-message-off::before{content:\"\\F10D2\"}.mdi-cellphone-nfc::before{content:\"\\F0E90\"}.mdi-cellphone-nfc-off::before{content:\"\\F12D8\"}.mdi-cellphone-off::before{content:\"\\F0950\"}.mdi-cellphone-play::before{content:\"\\F101F\"}.mdi-cellphone-screenshot::before{content:\"\\F0A35\"}.mdi-cellphone-settings::before{content:\"\\F0123\"}.mdi-cellphone-sound::before{content:\"\\F0952\"}.mdi-cellphone-text::before{content:\"\\F08D2\"}.mdi-cellphone-wireless::before{content:\"\\F0815\"}.mdi-celtic-cross::before{content:\"\\F0CF5\"}.mdi-centos::before{content:\"\\F111A\"}.mdi-certificate::before{content:\"\\F0124\"}.mdi-certificate-outline::before{content:\"\\F1188\"}.mdi-chair-rolling::before{content:\"\\F0F48\"}.mdi-chair-school::before{content:\"\\F0125\"}.mdi-charity::before{content:\"\\F0C4F\"}.mdi-chart-arc::before{content:\"\\F0126\"}.mdi-chart-areaspline::before{content:\"\\F0127\"}.mdi-chart-areaspline-variant::before{content:\"\\F0E91\"}.mdi-chart-bar::before{content:\"\\F0128\"}.mdi-chart-bar-stacked::before{content:\"\\F076A\"}.mdi-chart-bell-curve::before{content:\"\\F0C50\"}.mdi-chart-bell-curve-cumulative::before{content:\"\\F0FA7\"}.mdi-chart-box::before{content:\"\\F154D\"}.mdi-chart-box-outline::before{content:\"\\F154E\"}.mdi-chart-box-plus-outline::before{content:\"\\F154F\"}.mdi-chart-bubble::before{content:\"\\F05E3\"}.mdi-chart-donut::before{content:\"\\F07AF\"}.mdi-chart-donut-variant::before{content:\"\\F07B0\"}.mdi-chart-gantt::before{content:\"\\F066C\"}.mdi-chart-histogram::before{content:\"\\F0129\"}.mdi-chart-line::before{content:\"\\F012A\"}.mdi-chart-line-stacked::before{content:\"\\F076B\"}.mdi-chart-line-variant::before{content:\"\\F07B1\"}.mdi-chart-multiline::before{content:\"\\F08D4\"}.mdi-chart-multiple::before{content:\"\\F1213\"}.mdi-chart-pie::before{content:\"\\F012B\"}.mdi-chart-ppf::before{content:\"\\F1380\"}.mdi-chart-sankey::before{content:\"\\F11DF\"}.mdi-chart-sankey-variant::before{content:\"\\F11E0\"}.mdi-chart-scatter-plot::before{content:\"\\F0E92\"}.mdi-chart-scatter-plot-hexbin::before{content:\"\\F066D\"}.mdi-chart-timeline::before{content:\"\\F066E\"}.mdi-chart-timeline-variant::before{content:\"\\F0E93\"}.mdi-chart-timeline-variant-shimmer::before{content:\"\\F15B6\"}.mdi-chart-tree::before{content:\"\\F0E94\"}.mdi-chat::before{content:\"\\F0B79\"}.mdi-chat-alert::before{content:\"\\F0B7A\"}.mdi-chat-alert-outline::before{content:\"\\F12C9\"}.mdi-chat-minus::before{content:\"\\F1410\"}.mdi-chat-minus-outline::before{content:\"\\F1413\"}.mdi-chat-outline::before{content:\"\\F0EDE\"}.mdi-chat-plus::before{content:\"\\F140F\"}.mdi-chat-plus-outline::before{content:\"\\F1412\"}.mdi-chat-processing::before{content:\"\\F0B7B\"}.mdi-chat-processing-outline::before{content:\"\\F12CA\"}.mdi-chat-remove::before{content:\"\\F1411\"}.mdi-chat-remove-outline::before{content:\"\\F1414\"}.mdi-chat-sleep::before{content:\"\\F12D1\"}.mdi-chat-sleep-outline::before{content:\"\\F12D2\"}.mdi-check::before{content:\"\\F012C\"}.mdi-check-all::before{content:\"\\F012D\"}.mdi-check-bold::before{content:\"\\F0E1E\"}.mdi-check-box-multiple-outline::before{content:\"\\F0C51\"}.mdi-check-box-outline::before{content:\"\\F0C52\"}.mdi-check-circle::before{content:\"\\F05E0\"}.mdi-check-circle-outline::before{content:\"\\F05E1\"}.mdi-check-decagram::before{content:\"\\F0791\"}.mdi-check-network::before{content:\"\\F0C53\"}.mdi-check-network-outline::before{content:\"\\F0C54\"}.mdi-check-outline::before{content:\"\\F0855\"}.mdi-check-underline::before{content:\"\\F0E1F\"}.mdi-check-underline-circle::before{content:\"\\F0E20\"}.mdi-check-underline-circle-outline::before{content:\"\\F0E21\"}.mdi-checkbook::before{content:\"\\F0A9D\"}.mdi-checkbox-blank::before{content:\"\\F012E\"}.mdi-checkbox-blank-circle::before{content:\"\\F012F\"}.mdi-checkbox-blank-circle-outline::before{content:\"\\F0130\"}.mdi-checkbox-blank-off::before{content:\"\\F12EC\"}.mdi-checkbox-blank-off-outline::before{content:\"\\F12ED\"}.mdi-checkbox-blank-outline::before{content:\"\\F0131\"}.mdi-checkbox-intermediate::before{content:\"\\F0856\"}.mdi-checkbox-marked::before{content:\"\\F0132\"}.mdi-checkbox-marked-circle::before{content:\"\\F0133\"}.mdi-checkbox-marked-circle-outline::before{content:\"\\F0134\"}.mdi-checkbox-marked-outline::before{content:\"\\F0135\"}.mdi-checkbox-multiple-blank::before{content:\"\\F0136\"}.mdi-checkbox-multiple-blank-circle::before{content:\"\\F063B\"}.mdi-checkbox-multiple-blank-circle-outline::before{content:\"\\F063C\"}.mdi-checkbox-multiple-blank-outline::before{content:\"\\F0137\"}.mdi-checkbox-multiple-marked::before{content:\"\\F0138\"}.mdi-checkbox-multiple-marked-circle::before{content:\"\\F063D\"}.mdi-checkbox-multiple-marked-circle-outline::before{content:\"\\F063E\"}.mdi-checkbox-multiple-marked-outline::before{content:\"\\F0139\"}.mdi-checkerboard::before{content:\"\\F013A\"}.mdi-checkerboard-minus::before{content:\"\\F1202\"}.mdi-checkerboard-plus::before{content:\"\\F1201\"}.mdi-checkerboard-remove::before{content:\"\\F1203\"}.mdi-cheese::before{content:\"\\F12B9\"}.mdi-cheese-off::before{content:\"\\F13EE\"}.mdi-chef-hat::before{content:\"\\F0B7C\"}.mdi-chemical-weapon::before{content:\"\\F013B\"}.mdi-chess-bishop::before{content:\"\\F085C\"}.mdi-chess-king::before{content:\"\\F0857\"}.mdi-chess-knight::before{content:\"\\F0858\"}.mdi-chess-pawn::before{content:\"\\F0859\"}.mdi-chess-queen::before{content:\"\\F085A\"}.mdi-chess-rook::before{content:\"\\F085B\"}.mdi-chevron-double-down::before{content:\"\\F013C\"}.mdi-chevron-double-left::before{content:\"\\F013D\"}.mdi-chevron-double-right::before{content:\"\\F013E\"}.mdi-chevron-double-up::before{content:\"\\F013F\"}.mdi-chevron-down::before{content:\"\\F0140\"}.mdi-chevron-down-box::before{content:\"\\F09D6\"}.mdi-chevron-down-box-outline::before{content:\"\\F09D7\"}.mdi-chevron-down-circle::before{content:\"\\F0B26\"}.mdi-chevron-down-circle-outline::before{content:\"\\F0B27\"}.mdi-chevron-left::before{content:\"\\F0141\"}.mdi-chevron-left-box::before{content:\"\\F09D8\"}.mdi-chevron-left-box-outline::before{content:\"\\F09D9\"}.mdi-chevron-left-circle::before{content:\"\\F0B28\"}.mdi-chevron-left-circle-outline::before{content:\"\\F0B29\"}.mdi-chevron-right::before{content:\"\\F0142\"}.mdi-chevron-right-box::before{content:\"\\F09DA\"}.mdi-chevron-right-box-outline::before{content:\"\\F09DB\"}.mdi-chevron-right-circle::before{content:\"\\F0B2A\"}.mdi-chevron-right-circle-outline::before{content:\"\\F0B2B\"}.mdi-chevron-triple-down::before{content:\"\\F0DB9\"}.mdi-chevron-triple-left::before{content:\"\\F0DBA\"}.mdi-chevron-triple-right::before{content:\"\\F0DBB\"}.mdi-chevron-triple-up::before{content:\"\\F0DBC\"}.mdi-chevron-up::before{content:\"\\F0143\"}.mdi-chevron-up-box::before{content:\"\\F09DC\"}.mdi-chevron-up-box-outline::before{content:\"\\F09DD\"}.mdi-chevron-up-circle::before{content:\"\\F0B2C\"}.mdi-chevron-up-circle-outline::before{content:\"\\F0B2D\"}.mdi-chili-hot::before{content:\"\\F07B2\"}.mdi-chili-medium::before{content:\"\\F07B3\"}.mdi-chili-mild::before{content:\"\\F07B4\"}.mdi-chili-off::before{content:\"\\F1467\"}.mdi-chip::before{content:\"\\F061A\"}.mdi-christianity::before{content:\"\\F0953\"}.mdi-christianity-outline::before{content:\"\\F0CF6\"}.mdi-church::before{content:\"\\F0144\"}.mdi-cigar::before{content:\"\\F1189\"}.mdi-cigar-off::before{content:\"\\F141B\"}.mdi-circle::before{content:\"\\F0765\"}.mdi-circle-box::before{content:\"\\F15DC\"}.mdi-circle-box-outline::before{content:\"\\F15DD\"}.mdi-circle-double::before{content:\"\\F0E95\"}.mdi-circle-edit-outline::before{content:\"\\F08D5\"}.mdi-circle-expand::before{content:\"\\F0E96\"}.mdi-circle-half::before{content:\"\\F1395\"}.mdi-circle-half-full::before{content:\"\\F1396\"}.mdi-circle-medium::before{content:\"\\F09DE\"}.mdi-circle-multiple::before{content:\"\\F0B38\"}.mdi-circle-multiple-outline::before{content:\"\\F0695\"}.mdi-circle-off-outline::before{content:\"\\F10D3\"}.mdi-circle-outline::before{content:\"\\F0766\"}.mdi-circle-slice-1::before{content:\"\\F0A9E\"}.mdi-circle-slice-2::before{content:\"\\F0A9F\"}.mdi-circle-slice-3::before{content:\"\\F0AA0\"}.mdi-circle-slice-4::before{content:\"\\F0AA1\"}.mdi-circle-slice-5::before{content:\"\\F0AA2\"}.mdi-circle-slice-6::before{content:\"\\F0AA3\"}.mdi-circle-slice-7::before{content:\"\\F0AA4\"}.mdi-circle-slice-8::before{content:\"\\F0AA5\"}.mdi-circle-small::before{content:\"\\F09DF\"}.mdi-circular-saw::before{content:\"\\F0E22\"}.mdi-city::before{content:\"\\F0146\"}.mdi-city-variant::before{content:\"\\F0A36\"}.mdi-city-variant-outline::before{content:\"\\F0A37\"}.mdi-clipboard::before{content:\"\\F0147\"}.mdi-clipboard-account::before{content:\"\\F0148\"}.mdi-clipboard-account-outline::before{content:\"\\F0C55\"}.mdi-clipboard-alert::before{content:\"\\F0149\"}.mdi-clipboard-alert-outline::before{content:\"\\F0CF7\"}.mdi-clipboard-arrow-down::before{content:\"\\F014A\"}.mdi-clipboard-arrow-down-outline::before{content:\"\\F0C56\"}.mdi-clipboard-arrow-left::before{content:\"\\F014B\"}.mdi-clipboard-arrow-left-outline::before{content:\"\\F0CF8\"}.mdi-clipboard-arrow-right::before{content:\"\\F0CF9\"}.mdi-clipboard-arrow-right-outline::before{content:\"\\F0CFA\"}.mdi-clipboard-arrow-up::before{content:\"\\F0C57\"}.mdi-clipboard-arrow-up-outline::before{content:\"\\F0C58\"}.mdi-clipboard-check::before{content:\"\\F014E\"}.mdi-clipboard-check-multiple::before{content:\"\\F1263\"}.mdi-clipboard-check-multiple-outline::before{content:\"\\F1264\"}.mdi-clipboard-check-outline::before{content:\"\\F08A8\"}.mdi-clipboard-edit::before{content:\"\\F14E5\"}.mdi-clipboard-edit-outline::before{content:\"\\F14E6\"}.mdi-clipboard-file::before{content:\"\\F1265\"}.mdi-clipboard-file-outline::before{content:\"\\F1266\"}.mdi-clipboard-flow::before{content:\"\\F06C8\"}.mdi-clipboard-flow-outline::before{content:\"\\F1117\"}.mdi-clipboard-list::before{content:\"\\F10D4\"}.mdi-clipboard-list-outline::before{content:\"\\F10D5\"}.mdi-clipboard-minus::before{content:\"\\F1618\"}.mdi-clipboard-minus-outline::before{content:\"\\F1619\"}.mdi-clipboard-multiple::before{content:\"\\F1267\"}.mdi-clipboard-multiple-outline::before{content:\"\\F1268\"}.mdi-clipboard-off::before{content:\"\\F161A\"}.mdi-clipboard-off-outline::before{content:\"\\F161B\"}.mdi-clipboard-outline::before{content:\"\\F014C\"}.mdi-clipboard-play::before{content:\"\\F0C59\"}.mdi-clipboard-play-multiple::before{content:\"\\F1269\"}.mdi-clipboard-play-multiple-outline::before{content:\"\\F126A\"}.mdi-clipboard-play-outline::before{content:\"\\F0C5A\"}.mdi-clipboard-plus::before{content:\"\\F0751\"}.mdi-clipboard-plus-outline::before{content:\"\\F131F\"}.mdi-clipboard-pulse::before{content:\"\\F085D\"}.mdi-clipboard-pulse-outline::before{content:\"\\F085E\"}.mdi-clipboard-remove::before{content:\"\\F161C\"}.mdi-clipboard-remove-outline::before{content:\"\\F161D\"}.mdi-clipboard-search::before{content:\"\\F161E\"}.mdi-clipboard-search-outline::before{content:\"\\F161F\"}.mdi-clipboard-text::before{content:\"\\F014D\"}.mdi-clipboard-text-multiple::before{content:\"\\F126B\"}.mdi-clipboard-text-multiple-outline::before{content:\"\\F126C\"}.mdi-clipboard-text-off::before{content:\"\\F1620\"}.mdi-clipboard-text-off-outline::before{content:\"\\F1621\"}.mdi-clipboard-text-outline::before{content:\"\\F0A38\"}.mdi-clipboard-text-play::before{content:\"\\F0C5B\"}.mdi-clipboard-text-play-outline::before{content:\"\\F0C5C\"}.mdi-clipboard-text-search::before{content:\"\\F1622\"}.mdi-clipboard-text-search-outline::before{content:\"\\F1623\"}.mdi-clippy::before{content:\"\\F014F\"}.mdi-clock::before{content:\"\\F0954\"}.mdi-clock-alert::before{content:\"\\F0955\"}.mdi-clock-alert-outline::before{content:\"\\F05CE\"}.mdi-clock-check::before{content:\"\\F0FA8\"}.mdi-clock-check-outline::before{content:\"\\F0FA9\"}.mdi-clock-digital::before{content:\"\\F0E97\"}.mdi-clock-end::before{content:\"\\F0151\"}.mdi-clock-fast::before{content:\"\\F0152\"}.mdi-clock-in::before{content:\"\\F0153\"}.mdi-clock-out::before{content:\"\\F0154\"}.mdi-clock-outline::before{content:\"\\F0150\"}.mdi-clock-start::before{content:\"\\F0155\"}.mdi-clock-time-eight::before{content:\"\\F1446\"}.mdi-clock-time-eight-outline::before{content:\"\\F1452\"}.mdi-clock-time-eleven::before{content:\"\\F1449\"}.mdi-clock-time-eleven-outline::before{content:\"\\F1455\"}.mdi-clock-time-five::before{content:\"\\F1443\"}.mdi-clock-time-five-outline::before{content:\"\\F144F\"}.mdi-clock-time-four::before{content:\"\\F1442\"}.mdi-clock-time-four-outline::before{content:\"\\F144E\"}.mdi-clock-time-nine::before{content:\"\\F1447\"}.mdi-clock-time-nine-outline::before{content:\"\\F1453\"}.mdi-clock-time-one::before{content:\"\\F143F\"}.mdi-clock-time-one-outline::before{content:\"\\F144B\"}.mdi-clock-time-seven::before{content:\"\\F1445\"}.mdi-clock-time-seven-outline::before{content:\"\\F1451\"}.mdi-clock-time-six::before{content:\"\\F1444\"}.mdi-clock-time-six-outline::before{content:\"\\F1450\"}.mdi-clock-time-ten::before{content:\"\\F1448\"}.mdi-clock-time-ten-outline::before{content:\"\\F1454\"}.mdi-clock-time-three::before{content:\"\\F1441\"}.mdi-clock-time-three-outline::before{content:\"\\F144D\"}.mdi-clock-time-twelve::before{content:\"\\F144A\"}.mdi-clock-time-twelve-outline::before{content:\"\\F1456\"}.mdi-clock-time-two::before{content:\"\\F1440\"}.mdi-clock-time-two-outline::before{content:\"\\F144C\"}.mdi-close::before{content:\"\\F0156\"}.mdi-close-box::before{content:\"\\F0157\"}.mdi-close-box-multiple::before{content:\"\\F0C5D\"}.mdi-close-box-multiple-outline::before{content:\"\\F0C5E\"}.mdi-close-box-outline::before{content:\"\\F0158\"}.mdi-close-circle::before{content:\"\\F0159\"}.mdi-close-circle-multiple::before{content:\"\\F062A\"}.mdi-close-circle-multiple-outline::before{content:\"\\F0883\"}.mdi-close-circle-outline::before{content:\"\\F015A\"}.mdi-close-network::before{content:\"\\F015B\"}.mdi-close-network-outline::before{content:\"\\F0C5F\"}.mdi-close-octagon::before{content:\"\\F015C\"}.mdi-close-octagon-outline::before{content:\"\\F015D\"}.mdi-close-outline::before{content:\"\\F06C9\"}.mdi-close-thick::before{content:\"\\F1398\"}.mdi-closed-caption::before{content:\"\\F015E\"}.mdi-closed-caption-outline::before{content:\"\\F0DBD\"}.mdi-cloud::before{content:\"\\F015F\"}.mdi-cloud-alert::before{content:\"\\F09E0\"}.mdi-cloud-braces::before{content:\"\\F07B5\"}.mdi-cloud-check::before{content:\"\\F0160\"}.mdi-cloud-check-outline::before{content:\"\\F12CC\"}.mdi-cloud-circle::before{content:\"\\F0161\"}.mdi-cloud-download::before{content:\"\\F0162\"}.mdi-cloud-download-outline::before{content:\"\\F0B7D\"}.mdi-cloud-lock::before{content:\"\\F11F1\"}.mdi-cloud-lock-outline::before{content:\"\\F11F2\"}.mdi-cloud-off-outline::before{content:\"\\F0164\"}.mdi-cloud-outline::before{content:\"\\F0163\"}.mdi-cloud-print::before{content:\"\\F0165\"}.mdi-cloud-print-outline::before{content:\"\\F0166\"}.mdi-cloud-question::before{content:\"\\F0A39\"}.mdi-cloud-refresh::before{content:\"\\F052A\"}.mdi-cloud-search::before{content:\"\\F0956\"}.mdi-cloud-search-outline::before{content:\"\\F0957\"}.mdi-cloud-sync::before{content:\"\\F063F\"}.mdi-cloud-sync-outline::before{content:\"\\F12D6\"}.mdi-cloud-tags::before{content:\"\\F07B6\"}.mdi-cloud-upload::before{content:\"\\F0167\"}.mdi-cloud-upload-outline::before{content:\"\\F0B7E\"}.mdi-clover::before{content:\"\\F0816\"}.mdi-coach-lamp::before{content:\"\\F1020\"}.mdi-coat-rack::before{content:\"\\F109E\"}.mdi-code-array::before{content:\"\\F0168\"}.mdi-code-braces::before{content:\"\\F0169\"}.mdi-code-braces-box::before{content:\"\\F10D6\"}.mdi-code-brackets::before{content:\"\\F016A\"}.mdi-code-equal::before{content:\"\\F016B\"}.mdi-code-greater-than::before{content:\"\\F016C\"}.mdi-code-greater-than-or-equal::before{content:\"\\F016D\"}.mdi-code-json::before{content:\"\\F0626\"}.mdi-code-less-than::before{content:\"\\F016E\"}.mdi-code-less-than-or-equal::before{content:\"\\F016F\"}.mdi-code-not-equal::before{content:\"\\F0170\"}.mdi-code-not-equal-variant::before{content:\"\\F0171\"}.mdi-code-parentheses::before{content:\"\\F0172\"}.mdi-code-parentheses-box::before{content:\"\\F10D7\"}.mdi-code-string::before{content:\"\\F0173\"}.mdi-code-tags::before{content:\"\\F0174\"}.mdi-code-tags-check::before{content:\"\\F0694\"}.mdi-codepen::before{content:\"\\F0175\"}.mdi-coffee::before{content:\"\\F0176\"}.mdi-coffee-maker::before{content:\"\\F109F\"}.mdi-coffee-off::before{content:\"\\F0FAA\"}.mdi-coffee-off-outline::before{content:\"\\F0FAB\"}.mdi-coffee-outline::before{content:\"\\F06CA\"}.mdi-coffee-to-go::before{content:\"\\F0177\"}.mdi-coffee-to-go-outline::before{content:\"\\F130E\"}.mdi-coffin::before{content:\"\\F0B7F\"}.mdi-cog::before{content:\"\\F0493\"}.mdi-cog-box::before{content:\"\\F0494\"}.mdi-cog-clockwise::before{content:\"\\F11DD\"}.mdi-cog-counterclockwise::before{content:\"\\F11DE\"}.mdi-cog-off::before{content:\"\\F13CE\"}.mdi-cog-off-outline::before{content:\"\\F13CF\"}.mdi-cog-outline::before{content:\"\\F08BB\"}.mdi-cog-refresh::before{content:\"\\F145E\"}.mdi-cog-refresh-outline::before{content:\"\\F145F\"}.mdi-cog-sync::before{content:\"\\F1460\"}.mdi-cog-sync-outline::before{content:\"\\F1461\"}.mdi-cog-transfer::before{content:\"\\F105B\"}.mdi-cog-transfer-outline::before{content:\"\\F105C\"}.mdi-cogs::before{content:\"\\F08D6\"}.mdi-collage::before{content:\"\\F0640\"}.mdi-collapse-all::before{content:\"\\F0AA6\"}.mdi-collapse-all-outline::before{content:\"\\F0AA7\"}.mdi-color-helper::before{content:\"\\F0179\"}.mdi-comma::before{content:\"\\F0E23\"}.mdi-comma-box::before{content:\"\\F0E2B\"}.mdi-comma-box-outline::before{content:\"\\F0E24\"}.mdi-comma-circle::before{content:\"\\F0E25\"}.mdi-comma-circle-outline::before{content:\"\\F0E26\"}.mdi-comment::before{content:\"\\F017A\"}.mdi-comment-account::before{content:\"\\F017B\"}.mdi-comment-account-outline::before{content:\"\\F017C\"}.mdi-comment-alert::before{content:\"\\F017D\"}.mdi-comment-alert-outline::before{content:\"\\F017E\"}.mdi-comment-arrow-left::before{content:\"\\F09E1\"}.mdi-comment-arrow-left-outline::before{content:\"\\F09E2\"}.mdi-comment-arrow-right::before{content:\"\\F09E3\"}.mdi-comment-arrow-right-outline::before{content:\"\\F09E4\"}.mdi-comment-bookmark::before{content:\"\\F15AE\"}.mdi-comment-bookmark-outline::before{content:\"\\F15AF\"}.mdi-comment-check::before{content:\"\\F017F\"}.mdi-comment-check-outline::before{content:\"\\F0180\"}.mdi-comment-edit::before{content:\"\\F11BF\"}.mdi-comment-edit-outline::before{content:\"\\F12C4\"}.mdi-comment-eye::before{content:\"\\F0A3A\"}.mdi-comment-eye-outline::before{content:\"\\F0A3B\"}.mdi-comment-flash::before{content:\"\\F15B0\"}.mdi-comment-flash-outline::before{content:\"\\F15B1\"}.mdi-comment-minus::before{content:\"\\F15DF\"}.mdi-comment-minus-outline::before{content:\"\\F15E0\"}.mdi-comment-multiple::before{content:\"\\F085F\"}.mdi-comment-multiple-outline::before{content:\"\\F0181\"}.mdi-comment-off::before{content:\"\\F15E1\"}.mdi-comment-off-outline::before{content:\"\\F15E2\"}.mdi-comment-outline::before{content:\"\\F0182\"}.mdi-comment-plus::before{content:\"\\F09E5\"}.mdi-comment-plus-outline::before{content:\"\\F0183\"}.mdi-comment-processing::before{content:\"\\F0184\"}.mdi-comment-processing-outline::before{content:\"\\F0185\"}.mdi-comment-question::before{content:\"\\F0817\"}.mdi-comment-question-outline::before{content:\"\\F0186\"}.mdi-comment-quote::before{content:\"\\F1021\"}.mdi-comment-quote-outline::before{content:\"\\F1022\"}.mdi-comment-remove::before{content:\"\\F05DE\"}.mdi-comment-remove-outline::before{content:\"\\F0187\"}.mdi-comment-search::before{content:\"\\F0A3C\"}.mdi-comment-search-outline::before{content:\"\\F0A3D\"}.mdi-comment-text::before{content:\"\\F0188\"}.mdi-comment-text-multiple::before{content:\"\\F0860\"}.mdi-comment-text-multiple-outline::before{content:\"\\F0861\"}.mdi-comment-text-outline::before{content:\"\\F0189\"}.mdi-compare::before{content:\"\\F018A\"}.mdi-compare-horizontal::before{content:\"\\F1492\"}.mdi-compare-vertical::before{content:\"\\F1493\"}.mdi-compass::before{content:\"\\F018B\"}.mdi-compass-off::before{content:\"\\F0B80\"}.mdi-compass-off-outline::before{content:\"\\F0B81\"}.mdi-compass-outline::before{content:\"\\F018C\"}.mdi-compass-rose::before{content:\"\\F1382\"}.mdi-concourse-ci::before{content:\"\\F10A0\"}.mdi-connection::before{content:\"\\F1616\"}.mdi-console::before{content:\"\\F018D\"}.mdi-console-line::before{content:\"\\F07B7\"}.mdi-console-network::before{content:\"\\F08A9\"}.mdi-console-network-outline::before{content:\"\\F0C60\"}.mdi-consolidate::before{content:\"\\F10D8\"}.mdi-contactless-payment::before{content:\"\\F0D6A\"}.mdi-contactless-payment-circle::before{content:\"\\F0321\"}.mdi-contactless-payment-circle-outline::before{content:\"\\F0408\"}.mdi-contacts::before{content:\"\\F06CB\"}.mdi-contacts-outline::before{content:\"\\F05B8\"}.mdi-contain::before{content:\"\\F0A3E\"}.mdi-contain-end::before{content:\"\\F0A3F\"}.mdi-contain-start::before{content:\"\\F0A40\"}.mdi-content-copy::before{content:\"\\F018F\"}.mdi-content-cut::before{content:\"\\F0190\"}.mdi-content-duplicate::before{content:\"\\F0191\"}.mdi-content-paste::before{content:\"\\F0192\"}.mdi-content-save::before{content:\"\\F0193\"}.mdi-content-save-alert::before{content:\"\\F0F42\"}.mdi-content-save-alert-outline::before{content:\"\\F0F43\"}.mdi-content-save-all::before{content:\"\\F0194\"}.mdi-content-save-all-outline::before{content:\"\\F0F44\"}.mdi-content-save-cog::before{content:\"\\F145B\"}.mdi-content-save-cog-outline::before{content:\"\\F145C\"}.mdi-content-save-edit::before{content:\"\\F0CFB\"}.mdi-content-save-edit-outline::before{content:\"\\F0CFC\"}.mdi-content-save-move::before{content:\"\\F0E27\"}.mdi-content-save-move-outline::before{content:\"\\F0E28\"}.mdi-content-save-off::before{content:\"\\F1643\"}.mdi-content-save-off-outline::before{content:\"\\F1644\"}.mdi-content-save-outline::before{content:\"\\F0818\"}.mdi-content-save-settings::before{content:\"\\F061B\"}.mdi-content-save-settings-outline::before{content:\"\\F0B2E\"}.mdi-contrast::before{content:\"\\F0195\"}.mdi-contrast-box::before{content:\"\\F0196\"}.mdi-contrast-circle::before{content:\"\\F0197\"}.mdi-controller-classic::before{content:\"\\F0B82\"}.mdi-controller-classic-outline::before{content:\"\\F0B83\"}.mdi-cookie::before{content:\"\\F0198\"}.mdi-coolant-temperature::before{content:\"\\F03C8\"}.mdi-copyright::before{content:\"\\F05E6\"}.mdi-cordova::before{content:\"\\F0958\"}.mdi-corn::before{content:\"\\F07B8\"}.mdi-corn-off::before{content:\"\\F13EF\"}.mdi-cosine-wave::before{content:\"\\F1479\"}.mdi-counter::before{content:\"\\F0199\"}.mdi-cow::before{content:\"\\F019A\"}.mdi-cpu-32-bit::before{content:\"\\F0EDF\"}.mdi-cpu-64-bit::before{content:\"\\F0EE0\"}.mdi-crane::before{content:\"\\F0862\"}.mdi-creation::before{content:\"\\F0674\"}.mdi-creative-commons::before{content:\"\\F0D6B\"}.mdi-credit-card::before{content:\"\\F0FEF\"}.mdi-credit-card-check::before{content:\"\\F13D0\"}.mdi-credit-card-check-outline::before{content:\"\\F13D1\"}.mdi-credit-card-clock::before{content:\"\\F0EE1\"}.mdi-credit-card-clock-outline::before{content:\"\\F0EE2\"}.mdi-credit-card-marker::before{content:\"\\F06A8\"}.mdi-credit-card-marker-outline::before{content:\"\\F0DBE\"}.mdi-credit-card-minus::before{content:\"\\F0FAC\"}.mdi-credit-card-minus-outline::before{content:\"\\F0FAD\"}.mdi-credit-card-multiple::before{content:\"\\F0FF0\"}.mdi-credit-card-multiple-outline::before{content:\"\\F019C\"}.mdi-credit-card-off::before{content:\"\\F0FF1\"}.mdi-credit-card-off-outline::before{content:\"\\F05E4\"}.mdi-credit-card-outline::before{content:\"\\F019B\"}.mdi-credit-card-plus::before{content:\"\\F0FF2\"}.mdi-credit-card-plus-outline::before{content:\"\\F0676\"}.mdi-credit-card-refresh::before{content:\"\\F1645\"}.mdi-credit-card-refresh-outline::before{content:\"\\F1646\"}.mdi-credit-card-refund::before{content:\"\\F0FF3\"}.mdi-credit-card-refund-outline::before{content:\"\\F0AA8\"}.mdi-credit-card-remove::before{content:\"\\F0FAE\"}.mdi-credit-card-remove-outline::before{content:\"\\F0FAF\"}.mdi-credit-card-scan::before{content:\"\\F0FF4\"}.mdi-credit-card-scan-outline::before{content:\"\\F019D\"}.mdi-credit-card-search::before{content:\"\\F1647\"}.mdi-credit-card-search-outline::before{content:\"\\F1648\"}.mdi-credit-card-settings::before{content:\"\\F0FF5\"}.mdi-credit-card-settings-outline::before{content:\"\\F08D7\"}.mdi-credit-card-sync::before{content:\"\\F1649\"}.mdi-credit-card-sync-outline::before{content:\"\\F164A\"}.mdi-credit-card-wireless::before{content:\"\\F0802\"}.mdi-credit-card-wireless-off::before{content:\"\\F057A\"}.mdi-credit-card-wireless-off-outline::before{content:\"\\F057B\"}.mdi-credit-card-wireless-outline::before{content:\"\\F0D6C\"}.mdi-cricket::before{content:\"\\F0D6D\"}.mdi-crop::before{content:\"\\F019E\"}.mdi-crop-free::before{content:\"\\F019F\"}.mdi-crop-landscape::before{content:\"\\F01A0\"}.mdi-crop-portrait::before{content:\"\\F01A1\"}.mdi-crop-rotate::before{content:\"\\F0696\"}.mdi-crop-square::before{content:\"\\F01A2\"}.mdi-crosshairs::before{content:\"\\F01A3\"}.mdi-crosshairs-gps::before{content:\"\\F01A4\"}.mdi-crosshairs-off::before{content:\"\\F0F45\"}.mdi-crosshairs-question::before{content:\"\\F1136\"}.mdi-crown::before{content:\"\\F01A5\"}.mdi-crown-outline::before{content:\"\\F11D0\"}.mdi-cryengine::before{content:\"\\F0959\"}.mdi-crystal-ball::before{content:\"\\F0B2F\"}.mdi-cube::before{content:\"\\F01A6\"}.mdi-cube-off::before{content:\"\\F141C\"}.mdi-cube-off-outline::before{content:\"\\F141D\"}.mdi-cube-outline::before{content:\"\\F01A7\"}.mdi-cube-scan::before{content:\"\\F0B84\"}.mdi-cube-send::before{content:\"\\F01A8\"}.mdi-cube-unfolded::before{content:\"\\F01A9\"}.mdi-cup::before{content:\"\\F01AA\"}.mdi-cup-off::before{content:\"\\F05E5\"}.mdi-cup-off-outline::before{content:\"\\F137D\"}.mdi-cup-outline::before{content:\"\\F130F\"}.mdi-cup-water::before{content:\"\\F01AB\"}.mdi-cupboard::before{content:\"\\F0F46\"}.mdi-cupboard-outline::before{content:\"\\F0F47\"}.mdi-cupcake::before{content:\"\\F095A\"}.mdi-curling::before{content:\"\\F0863\"}.mdi-currency-bdt::before{content:\"\\F0864\"}.mdi-currency-brl::before{content:\"\\F0B85\"}.mdi-currency-btc::before{content:\"\\F01AC\"}.mdi-currency-cny::before{content:\"\\F07BA\"}.mdi-currency-eth::before{content:\"\\F07BB\"}.mdi-currency-eur::before{content:\"\\F01AD\"}.mdi-currency-eur-off::before{content:\"\\F1315\"}.mdi-currency-gbp::before{content:\"\\F01AE\"}.mdi-currency-ils::before{content:\"\\F0C61\"}.mdi-currency-inr::before{content:\"\\F01AF\"}.mdi-currency-jpy::before{content:\"\\F07BC\"}.mdi-currency-krw::before{content:\"\\F07BD\"}.mdi-currency-kzt::before{content:\"\\F0865\"}.mdi-currency-mnt::before{content:\"\\F1512\"}.mdi-currency-ngn::before{content:\"\\F01B0\"}.mdi-currency-php::before{content:\"\\F09E6\"}.mdi-currency-rial::before{content:\"\\F0E9C\"}.mdi-currency-rub::before{content:\"\\F01B1\"}.mdi-currency-sign::before{content:\"\\F07BE\"}.mdi-currency-try::before{content:\"\\F01B2\"}.mdi-currency-twd::before{content:\"\\F07BF\"}.mdi-currency-usd::before{content:\"\\F01C1\"}.mdi-currency-usd-circle::before{content:\"\\F116B\"}.mdi-currency-usd-circle-outline::before{content:\"\\F0178\"}.mdi-currency-usd-off::before{content:\"\\F067A\"}.mdi-current-ac::before{content:\"\\F1480\"}.mdi-current-dc::before{content:\"\\F095C\"}.mdi-cursor-default::before{content:\"\\F01C0\"}.mdi-cursor-default-click::before{content:\"\\F0CFD\"}.mdi-cursor-default-click-outline::before{content:\"\\F0CFE\"}.mdi-cursor-default-gesture::before{content:\"\\F1127\"}.mdi-cursor-default-gesture-outline::before{content:\"\\F1128\"}.mdi-cursor-default-outline::before{content:\"\\F01BF\"}.mdi-cursor-move::before{content:\"\\F01BE\"}.mdi-cursor-pointer::before{content:\"\\F01BD\"}.mdi-cursor-text::before{content:\"\\F05E7\"}.mdi-dance-ballroom::before{content:\"\\F15FB\"}.mdi-dance-pole::before{content:\"\\F1578\"}.mdi-data-matrix::before{content:\"\\F153C\"}.mdi-data-matrix-edit::before{content:\"\\F153D\"}.mdi-data-matrix-minus::before{content:\"\\F153E\"}.mdi-data-matrix-plus::before{content:\"\\F153F\"}.mdi-data-matrix-remove::before{content:\"\\F1540\"}.mdi-data-matrix-scan::before{content:\"\\F1541\"}.mdi-database::before{content:\"\\F01BC\"}.mdi-database-alert::before{content:\"\\F163A\"}.mdi-database-alert-outline::before{content:\"\\F1624\"}.mdi-database-arrow-down::before{content:\"\\F163B\"}.mdi-database-arrow-down-outline::before{content:\"\\F1625\"}.mdi-database-arrow-left::before{content:\"\\F163C\"}.mdi-database-arrow-left-outline::before{content:\"\\F1626\"}.mdi-database-arrow-right::before{content:\"\\F163D\"}.mdi-database-arrow-right-outline::before{content:\"\\F1627\"}.mdi-database-arrow-up::before{content:\"\\F163E\"}.mdi-database-arrow-up-outline::before{content:\"\\F1628\"}.mdi-database-check::before{content:\"\\F0AA9\"}.mdi-database-check-outline::before{content:\"\\F1629\"}.mdi-database-clock::before{content:\"\\F163F\"}.mdi-database-clock-outline::before{content:\"\\F162A\"}.mdi-database-cog::before{content:\"\\F164B\"}.mdi-database-cog-outline::before{content:\"\\F164C\"}.mdi-database-edit::before{content:\"\\F0B86\"}.mdi-database-edit-outline::before{content:\"\\F162B\"}.mdi-database-export::before{content:\"\\F095E\"}.mdi-database-export-outline::before{content:\"\\F162C\"}.mdi-database-import::before{content:\"\\F095D\"}.mdi-database-import-outline::before{content:\"\\F162D\"}.mdi-database-lock::before{content:\"\\F0AAA\"}.mdi-database-lock-outline::before{content:\"\\F162E\"}.mdi-database-marker::before{content:\"\\F12F6\"}.mdi-database-marker-outline::before{content:\"\\F162F\"}.mdi-database-minus::before{content:\"\\F01BB\"}.mdi-database-minus-outline::before{content:\"\\F1630\"}.mdi-database-off::before{content:\"\\F1640\"}.mdi-database-off-outline::before{content:\"\\F1631\"}.mdi-database-outline::before{content:\"\\F1632\"}.mdi-database-plus::before{content:\"\\F01BA\"}.mdi-database-plus-outline::before{content:\"\\F1633\"}.mdi-database-refresh::before{content:\"\\F05C2\"}.mdi-database-refresh-outline::before{content:\"\\F1634\"}.mdi-database-remove::before{content:\"\\F0D00\"}.mdi-database-remove-outline::before{content:\"\\F1635\"}.mdi-database-search::before{content:\"\\F0866\"}.mdi-database-search-outline::before{content:\"\\F1636\"}.mdi-database-settings::before{content:\"\\F0D01\"}.mdi-database-settings-outline::before{content:\"\\F1637\"}.mdi-database-sync::before{content:\"\\F0CFF\"}.mdi-database-sync-outline::before{content:\"\\F1638\"}.mdi-death-star::before{content:\"\\F08D8\"}.mdi-death-star-variant::before{content:\"\\F08D9\"}.mdi-deathly-hallows::before{content:\"\\F0B87\"}.mdi-debian::before{content:\"\\F08DA\"}.mdi-debug-step-into::before{content:\"\\F01B9\"}.mdi-debug-step-out::before{content:\"\\F01B8\"}.mdi-debug-step-over::before{content:\"\\F01B7\"}.mdi-decagram::before{content:\"\\F076C\"}.mdi-decagram-outline::before{content:\"\\F076D\"}.mdi-decimal::before{content:\"\\F10A1\"}.mdi-decimal-comma::before{content:\"\\F10A2\"}.mdi-decimal-comma-decrease::before{content:\"\\F10A3\"}.mdi-decimal-comma-increase::before{content:\"\\F10A4\"}.mdi-decimal-decrease::before{content:\"\\F01B6\"}.mdi-decimal-increase::before{content:\"\\F01B5\"}.mdi-delete::before{content:\"\\F01B4\"}.mdi-delete-alert::before{content:\"\\F10A5\"}.mdi-delete-alert-outline::before{content:\"\\F10A6\"}.mdi-delete-circle::before{content:\"\\F0683\"}.mdi-delete-circle-outline::before{content:\"\\F0B88\"}.mdi-delete-clock::before{content:\"\\F1556\"}.mdi-delete-clock-outline::before{content:\"\\F1557\"}.mdi-delete-empty::before{content:\"\\F06CC\"}.mdi-delete-empty-outline::before{content:\"\\F0E9D\"}.mdi-delete-forever::before{content:\"\\F05E8\"}.mdi-delete-forever-outline::before{content:\"\\F0B89\"}.mdi-delete-off::before{content:\"\\F10A7\"}.mdi-delete-off-outline::before{content:\"\\F10A8\"}.mdi-delete-outline::before{content:\"\\F09E7\"}.mdi-delete-restore::before{content:\"\\F0819\"}.mdi-delete-sweep::before{content:\"\\F05E9\"}.mdi-delete-sweep-outline::before{content:\"\\F0C62\"}.mdi-delete-variant::before{content:\"\\F01B3\"}.mdi-delta::before{content:\"\\F01C2\"}.mdi-desk::before{content:\"\\F1239\"}.mdi-desk-lamp::before{content:\"\\F095F\"}.mdi-deskphone::before{content:\"\\F01C3\"}.mdi-desktop-classic::before{content:\"\\F07C0\"}.mdi-desktop-mac::before{content:\"\\F01C4\"}.mdi-desktop-mac-dashboard::before{content:\"\\F09E8\"}.mdi-desktop-tower::before{content:\"\\F01C5\"}.mdi-desktop-tower-monitor::before{content:\"\\F0AAB\"}.mdi-details::before{content:\"\\F01C6\"}.mdi-dev-to::before{content:\"\\F0D6E\"}.mdi-developer-board::before{content:\"\\F0697\"}.mdi-deviantart::before{content:\"\\F01C7\"}.mdi-devices::before{content:\"\\F0FB0\"}.mdi-diabetes::before{content:\"\\F1126\"}.mdi-dialpad::before{content:\"\\F061C\"}.mdi-diameter::before{content:\"\\F0C63\"}.mdi-diameter-outline::before{content:\"\\F0C64\"}.mdi-diameter-variant::before{content:\"\\F0C65\"}.mdi-diamond::before{content:\"\\F0B8A\"}.mdi-diamond-outline::before{content:\"\\F0B8B\"}.mdi-diamond-stone::before{content:\"\\F01C8\"}.mdi-dice-1::before{content:\"\\F01CA\"}.mdi-dice-1-outline::before{content:\"\\F114A\"}.mdi-dice-2::before{content:\"\\F01CB\"}.mdi-dice-2-outline::before{content:\"\\F114B\"}.mdi-dice-3::before{content:\"\\F01CC\"}.mdi-dice-3-outline::before{content:\"\\F114C\"}.mdi-dice-4::before{content:\"\\F01CD\"}.mdi-dice-4-outline::before{content:\"\\F114D\"}.mdi-dice-5::before{content:\"\\F01CE\"}.mdi-dice-5-outline::before{content:\"\\F114E\"}.mdi-dice-6::before{content:\"\\F01CF\"}.mdi-dice-6-outline::before{content:\"\\F114F\"}.mdi-dice-d10::before{content:\"\\F1153\"}.mdi-dice-d10-outline::before{content:\"\\F076F\"}.mdi-dice-d12::before{content:\"\\F1154\"}.mdi-dice-d12-outline::before{content:\"\\F0867\"}.mdi-dice-d20::before{content:\"\\F1155\"}.mdi-dice-d20-outline::before{content:\"\\F05EA\"}.mdi-dice-d4::before{content:\"\\F1150\"}.mdi-dice-d4-outline::before{content:\"\\F05EB\"}.mdi-dice-d6::before{content:\"\\F1151\"}.mdi-dice-d6-outline::before{content:\"\\F05ED\"}.mdi-dice-d8::before{content:\"\\F1152\"}.mdi-dice-d8-outline::before{content:\"\\F05EC\"}.mdi-dice-multiple::before{content:\"\\F076E\"}.mdi-dice-multiple-outline::before{content:\"\\F1156\"}.mdi-digital-ocean::before{content:\"\\F1237\"}.mdi-dip-switch::before{content:\"\\F07C1\"}.mdi-directions::before{content:\"\\F01D0\"}.mdi-directions-fork::before{content:\"\\F0641\"}.mdi-disc::before{content:\"\\F05EE\"}.mdi-disc-alert::before{content:\"\\F01D1\"}.mdi-disc-player::before{content:\"\\F0960\"}.mdi-discord::before{content:\"\\F066F\"}.mdi-dishwasher::before{content:\"\\F0AAC\"}.mdi-dishwasher-alert::before{content:\"\\F11B8\"}.mdi-dishwasher-off::before{content:\"\\F11B9\"}.mdi-disqus::before{content:\"\\F01D2\"}.mdi-distribute-horizontal-center::before{content:\"\\F11C9\"}.mdi-distribute-horizontal-left::before{content:\"\\F11C8\"}.mdi-distribute-horizontal-right::before{content:\"\\F11CA\"}.mdi-distribute-vertical-bottom::before{content:\"\\F11CB\"}.mdi-distribute-vertical-center::before{content:\"\\F11CC\"}.mdi-distribute-vertical-top::before{content:\"\\F11CD\"}.mdi-diving-flippers::before{content:\"\\F0DBF\"}.mdi-diving-helmet::before{content:\"\\F0DC0\"}.mdi-diving-scuba::before{content:\"\\F0DC1\"}.mdi-diving-scuba-flag::before{content:\"\\F0DC2\"}.mdi-diving-scuba-tank::before{content:\"\\F0DC3\"}.mdi-diving-scuba-tank-multiple::before{content:\"\\F0DC4\"}.mdi-diving-snorkel::before{content:\"\\F0DC5\"}.mdi-division::before{content:\"\\F01D4\"}.mdi-division-box::before{content:\"\\F01D5\"}.mdi-dlna::before{content:\"\\F0A41\"}.mdi-dna::before{content:\"\\F0684\"}.mdi-dns::before{content:\"\\F01D6\"}.mdi-dns-outline::before{content:\"\\F0B8C\"}.mdi-do-not-disturb::before{content:\"\\F0698\"}.mdi-do-not-disturb-off::before{content:\"\\F0699\"}.mdi-dock-bottom::before{content:\"\\F10A9\"}.mdi-dock-left::before{content:\"\\F10AA\"}.mdi-dock-right::before{content:\"\\F10AB\"}.mdi-dock-top::before{content:\"\\F1513\"}.mdi-dock-window::before{content:\"\\F10AC\"}.mdi-docker::before{content:\"\\F0868\"}.mdi-doctor::before{content:\"\\F0A42\"}.mdi-dog::before{content:\"\\F0A43\"}.mdi-dog-service::before{content:\"\\F0AAD\"}.mdi-dog-side::before{content:\"\\F0A44\"}.mdi-dolby::before{content:\"\\F06B3\"}.mdi-dolly::before{content:\"\\F0E9E\"}.mdi-domain::before{content:\"\\F01D7\"}.mdi-domain-off::before{content:\"\\F0D6F\"}.mdi-domain-plus::before{content:\"\\F10AD\"}.mdi-domain-remove::before{content:\"\\F10AE\"}.mdi-dome-light::before{content:\"\\F141E\"}.mdi-domino-mask::before{content:\"\\F1023\"}.mdi-donkey::before{content:\"\\F07C2\"}.mdi-door::before{content:\"\\F081A\"}.mdi-door-closed::before{content:\"\\F081B\"}.mdi-door-closed-lock::before{content:\"\\F10AF\"}.mdi-door-open::before{content:\"\\F081C\"}.mdi-doorbell::before{content:\"\\F12E6\"}.mdi-doorbell-video::before{content:\"\\F0869\"}.mdi-dot-net::before{content:\"\\F0AAE\"}.mdi-dots-grid::before{content:\"\\F15FC\"}.mdi-dots-hexagon::before{content:\"\\F15FF\"}.mdi-dots-horizontal::before{content:\"\\F01D8\"}.mdi-dots-horizontal-circle::before{content:\"\\F07C3\"}.mdi-dots-horizontal-circle-outline::before{content:\"\\F0B8D\"}.mdi-dots-square::before{content:\"\\F15FD\"}.mdi-dots-triangle::before{content:\"\\F15FE\"}.mdi-dots-vertical::before{content:\"\\F01D9\"}.mdi-dots-vertical-circle::before{content:\"\\F07C4\"}.mdi-dots-vertical-circle-outline::before{content:\"\\F0B8E\"}.mdi-douban::before{content:\"\\F069A\"}.mdi-download::before{content:\"\\F01DA\"}.mdi-download-box::before{content:\"\\F1462\"}.mdi-download-box-outline::before{content:\"\\F1463\"}.mdi-download-circle::before{content:\"\\F1464\"}.mdi-download-circle-outline::before{content:\"\\F1465\"}.mdi-download-lock::before{content:\"\\F1320\"}.mdi-download-lock-outline::before{content:\"\\F1321\"}.mdi-download-multiple::before{content:\"\\F09E9\"}.mdi-download-network::before{content:\"\\F06F4\"}.mdi-download-network-outline::before{content:\"\\F0C66\"}.mdi-download-off::before{content:\"\\F10B0\"}.mdi-download-off-outline::before{content:\"\\F10B1\"}.mdi-download-outline::before{content:\"\\F0B8F\"}.mdi-drag::before{content:\"\\F01DB\"}.mdi-drag-horizontal::before{content:\"\\F01DC\"}.mdi-drag-horizontal-variant::before{content:\"\\F12F0\"}.mdi-drag-variant::before{content:\"\\F0B90\"}.mdi-drag-vertical::before{content:\"\\F01DD\"}.mdi-drag-vertical-variant::before{content:\"\\F12F1\"}.mdi-drama-masks::before{content:\"\\F0D02\"}.mdi-draw::before{content:\"\\F0F49\"}.mdi-drawing::before{content:\"\\F01DE\"}.mdi-drawing-box::before{content:\"\\F01DF\"}.mdi-dresser::before{content:\"\\F0F4A\"}.mdi-dresser-outline::before{content:\"\\F0F4B\"}.mdi-drone::before{content:\"\\F01E2\"}.mdi-dropbox::before{content:\"\\F01E3\"}.mdi-drupal::before{content:\"\\F01E4\"}.mdi-duck::before{content:\"\\F01E5\"}.mdi-dumbbell::before{content:\"\\F01E6\"}.mdi-dump-truck::before{content:\"\\F0C67\"}.mdi-ear-hearing::before{content:\"\\F07C5\"}.mdi-ear-hearing-off::before{content:\"\\F0A45\"}.mdi-earth::before{content:\"\\F01E7\"}.mdi-earth-arrow-right::before{content:\"\\F1311\"}.mdi-earth-box::before{content:\"\\F06CD\"}.mdi-earth-box-minus::before{content:\"\\F1407\"}.mdi-earth-box-off::before{content:\"\\F06CE\"}.mdi-earth-box-plus::before{content:\"\\F1406\"}.mdi-earth-box-remove::before{content:\"\\F1408\"}.mdi-earth-minus::before{content:\"\\F1404\"}.mdi-earth-off::before{content:\"\\F01E8\"}.mdi-earth-plus::before{content:\"\\F1403\"}.mdi-earth-remove::before{content:\"\\F1405\"}.mdi-egg::before{content:\"\\F0AAF\"}.mdi-egg-easter::before{content:\"\\F0AB0\"}.mdi-egg-off::before{content:\"\\F13F0\"}.mdi-egg-off-outline::before{content:\"\\F13F1\"}.mdi-egg-outline::before{content:\"\\F13F2\"}.mdi-eiffel-tower::before{content:\"\\F156B\"}.mdi-eight-track::before{content:\"\\F09EA\"}.mdi-eject::before{content:\"\\F01EA\"}.mdi-eject-outline::before{content:\"\\F0B91\"}.mdi-electric-switch::before{content:\"\\F0E9F\"}.mdi-electric-switch-closed::before{content:\"\\F10D9\"}.mdi-electron-framework::before{content:\"\\F1024\"}.mdi-elephant::before{content:\"\\F07C6\"}.mdi-elevation-decline::before{content:\"\\F01EB\"}.mdi-elevation-rise::before{content:\"\\F01EC\"}.mdi-elevator::before{content:\"\\F01ED\"}.mdi-elevator-down::before{content:\"\\F12C2\"}.mdi-elevator-passenger::before{content:\"\\F1381\"}.mdi-elevator-up::before{content:\"\\F12C1\"}.mdi-ellipse::before{content:\"\\F0EA0\"}.mdi-ellipse-outline::before{content:\"\\F0EA1\"}.mdi-email::before{content:\"\\F01EE\"}.mdi-email-alert::before{content:\"\\F06CF\"}.mdi-email-alert-outline::before{content:\"\\F0D42\"}.mdi-email-box::before{content:\"\\F0D03\"}.mdi-email-check::before{content:\"\\F0AB1\"}.mdi-email-check-outline::before{content:\"\\F0AB2\"}.mdi-email-edit::before{content:\"\\F0EE3\"}.mdi-email-edit-outline::before{content:\"\\F0EE4\"}.mdi-email-lock::before{content:\"\\F01F1\"}.mdi-email-mark-as-unread::before{content:\"\\F0B92\"}.mdi-email-minus::before{content:\"\\F0EE5\"}.mdi-email-minus-outline::before{content:\"\\F0EE6\"}.mdi-email-multiple::before{content:\"\\F0EE7\"}.mdi-email-multiple-outline::before{content:\"\\F0EE8\"}.mdi-email-newsletter::before{content:\"\\F0FB1\"}.mdi-email-off::before{content:\"\\F13E3\"}.mdi-email-off-outline::before{content:\"\\F13E4\"}.mdi-email-open::before{content:\"\\F01EF\"}.mdi-email-open-multiple::before{content:\"\\F0EE9\"}.mdi-email-open-multiple-outline::before{content:\"\\F0EEA\"}.mdi-email-open-outline::before{content:\"\\F05EF\"}.mdi-email-outline::before{content:\"\\F01F0\"}.mdi-email-plus::before{content:\"\\F09EB\"}.mdi-email-plus-outline::before{content:\"\\F09EC\"}.mdi-email-receive::before{content:\"\\F10DA\"}.mdi-email-receive-outline::before{content:\"\\F10DB\"}.mdi-email-remove::before{content:\"\\F1661\"}.mdi-email-remove-outline::before{content:\"\\F1662\"}.mdi-email-search::before{content:\"\\F0961\"}.mdi-email-search-outline::before{content:\"\\F0962\"}.mdi-email-send::before{content:\"\\F10DC\"}.mdi-email-send-outline::before{content:\"\\F10DD\"}.mdi-email-sync::before{content:\"\\F12C7\"}.mdi-email-sync-outline::before{content:\"\\F12C8\"}.mdi-email-variant::before{content:\"\\F05F0\"}.mdi-ember::before{content:\"\\F0B30\"}.mdi-emby::before{content:\"\\F06B4\"}.mdi-emoticon::before{content:\"\\F0C68\"}.mdi-emoticon-angry::before{content:\"\\F0C69\"}.mdi-emoticon-angry-outline::before{content:\"\\F0C6A\"}.mdi-emoticon-confused::before{content:\"\\F10DE\"}.mdi-emoticon-confused-outline::before{content:\"\\F10DF\"}.mdi-emoticon-cool::before{content:\"\\F0C6B\"}.mdi-emoticon-cool-outline::before{content:\"\\F01F3\"}.mdi-emoticon-cry::before{content:\"\\F0C6C\"}.mdi-emoticon-cry-outline::before{content:\"\\F0C6D\"}.mdi-emoticon-dead::before{content:\"\\F0C6E\"}.mdi-emoticon-dead-outline::before{content:\"\\F069B\"}.mdi-emoticon-devil::before{content:\"\\F0C6F\"}.mdi-emoticon-devil-outline::before{content:\"\\F01F4\"}.mdi-emoticon-excited::before{content:\"\\F0C70\"}.mdi-emoticon-excited-outline::before{content:\"\\F069C\"}.mdi-emoticon-frown::before{content:\"\\F0F4C\"}.mdi-emoticon-frown-outline::before{content:\"\\F0F4D\"}.mdi-emoticon-happy::before{content:\"\\F0C71\"}.mdi-emoticon-happy-outline::before{content:\"\\F01F5\"}.mdi-emoticon-kiss::before{content:\"\\F0C72\"}.mdi-emoticon-kiss-outline::before{content:\"\\F0C73\"}.mdi-emoticon-lol::before{content:\"\\F1214\"}.mdi-emoticon-lol-outline::before{content:\"\\F1215\"}.mdi-emoticon-neutral::before{content:\"\\F0C74\"}.mdi-emoticon-neutral-outline::before{content:\"\\F01F6\"}.mdi-emoticon-outline::before{content:\"\\F01F2\"}.mdi-emoticon-poop::before{content:\"\\F01F7\"}.mdi-emoticon-poop-outline::before{content:\"\\F0C75\"}.mdi-emoticon-sad::before{content:\"\\F0C76\"}.mdi-emoticon-sad-outline::before{content:\"\\F01F8\"}.mdi-emoticon-sick::before{content:\"\\F157C\"}.mdi-emoticon-sick-outline::before{content:\"\\F157D\"}.mdi-emoticon-tongue::before{content:\"\\F01F9\"}.mdi-emoticon-tongue-outline::before{content:\"\\F0C77\"}.mdi-emoticon-wink::before{content:\"\\F0C78\"}.mdi-emoticon-wink-outline::before{content:\"\\F0C79\"}.mdi-engine::before{content:\"\\F01FA\"}.mdi-engine-off::before{content:\"\\F0A46\"}.mdi-engine-off-outline::before{content:\"\\F0A47\"}.mdi-engine-outline::before{content:\"\\F01FB\"}.mdi-epsilon::before{content:\"\\F10E0\"}.mdi-equal::before{content:\"\\F01FC\"}.mdi-equal-box::before{content:\"\\F01FD\"}.mdi-equalizer::before{content:\"\\F0EA2\"}.mdi-equalizer-outline::before{content:\"\\F0EA3\"}.mdi-eraser::before{content:\"\\F01FE\"}.mdi-eraser-variant::before{content:\"\\F0642\"}.mdi-escalator::before{content:\"\\F01FF\"}.mdi-escalator-box::before{content:\"\\F1399\"}.mdi-escalator-down::before{content:\"\\F12C0\"}.mdi-escalator-up::before{content:\"\\F12BF\"}.mdi-eslint::before{content:\"\\F0C7A\"}.mdi-et::before{content:\"\\F0AB3\"}.mdi-ethereum::before{content:\"\\F086A\"}.mdi-ethernet::before{content:\"\\F0200\"}.mdi-ethernet-cable::before{content:\"\\F0201\"}.mdi-ethernet-cable-off::before{content:\"\\F0202\"}.mdi-ev-plug-ccs1::before{content:\"\\F1519\"}.mdi-ev-plug-ccs2::before{content:\"\\F151A\"}.mdi-ev-plug-chademo::before{content:\"\\F151B\"}.mdi-ev-plug-tesla::before{content:\"\\F151C\"}.mdi-ev-plug-type1::before{content:\"\\F151D\"}.mdi-ev-plug-type2::before{content:\"\\F151E\"}.mdi-ev-station::before{content:\"\\F05F1\"}.mdi-evernote::before{content:\"\\F0204\"}.mdi-excavator::before{content:\"\\F1025\"}.mdi-exclamation::before{content:\"\\F0205\"}.mdi-exclamation-thick::before{content:\"\\F1238\"}.mdi-exit-run::before{content:\"\\F0A48\"}.mdi-exit-to-app::before{content:\"\\F0206\"}.mdi-expand-all::before{content:\"\\F0AB4\"}.mdi-expand-all-outline::before{content:\"\\F0AB5\"}.mdi-expansion-card::before{content:\"\\F08AE\"}.mdi-expansion-card-variant::before{content:\"\\F0FB2\"}.mdi-exponent::before{content:\"\\F0963\"}.mdi-exponent-box::before{content:\"\\F0964\"}.mdi-export::before{content:\"\\F0207\"}.mdi-export-variant::before{content:\"\\F0B93\"}.mdi-eye::before{content:\"\\F0208\"}.mdi-eye-check::before{content:\"\\F0D04\"}.mdi-eye-check-outline::before{content:\"\\F0D05\"}.mdi-eye-circle::before{content:\"\\F0B94\"}.mdi-eye-circle-outline::before{content:\"\\F0B95\"}.mdi-eye-minus::before{content:\"\\F1026\"}.mdi-eye-minus-outline::before{content:\"\\F1027\"}.mdi-eye-off::before{content:\"\\F0209\"}.mdi-eye-off-outline::before{content:\"\\F06D1\"}.mdi-eye-outline::before{content:\"\\F06D0\"}.mdi-eye-plus::before{content:\"\\F086B\"}.mdi-eye-plus-outline::before{content:\"\\F086C\"}.mdi-eye-remove::before{content:\"\\F15E3\"}.mdi-eye-remove-outline::before{content:\"\\F15E4\"}.mdi-eye-settings::before{content:\"\\F086D\"}.mdi-eye-settings-outline::before{content:\"\\F086E\"}.mdi-eyedropper::before{content:\"\\F020A\"}.mdi-eyedropper-minus::before{content:\"\\F13DD\"}.mdi-eyedropper-off::before{content:\"\\F13DF\"}.mdi-eyedropper-plus::before{content:\"\\F13DC\"}.mdi-eyedropper-remove::before{content:\"\\F13DE\"}.mdi-eyedropper-variant::before{content:\"\\F020B\"}.mdi-face::before{content:\"\\F0643\"}.mdi-face-agent::before{content:\"\\F0D70\"}.mdi-face-mask::before{content:\"\\F1586\"}.mdi-face-mask-outline::before{content:\"\\F1587\"}.mdi-face-outline::before{content:\"\\F0B96\"}.mdi-face-profile::before{content:\"\\F0644\"}.mdi-face-profile-woman::before{content:\"\\F1076\"}.mdi-face-recognition::before{content:\"\\F0C7B\"}.mdi-face-shimmer::before{content:\"\\F15CC\"}.mdi-face-shimmer-outline::before{content:\"\\F15CD\"}.mdi-face-woman::before{content:\"\\F1077\"}.mdi-face-woman-outline::before{content:\"\\F1078\"}.mdi-face-woman-shimmer::before{content:\"\\F15CE\"}.mdi-face-woman-shimmer-outline::before{content:\"\\F15CF\"}.mdi-facebook::before{content:\"\\F020C\"}.mdi-facebook-gaming::before{content:\"\\F07DD\"}.mdi-facebook-messenger::before{content:\"\\F020E\"}.mdi-facebook-workplace::before{content:\"\\F0B31\"}.mdi-factory::before{content:\"\\F020F\"}.mdi-family-tree::before{content:\"\\F160E\"}.mdi-fan::before{content:\"\\F0210\"}.mdi-fan-alert::before{content:\"\\F146C\"}.mdi-fan-chevron-down::before{content:\"\\F146D\"}.mdi-fan-chevron-up::before{content:\"\\F146E\"}.mdi-fan-minus::before{content:\"\\F1470\"}.mdi-fan-off::before{content:\"\\F081D\"}.mdi-fan-plus::before{content:\"\\F146F\"}.mdi-fan-remove::before{content:\"\\F1471\"}.mdi-fan-speed-1::before{content:\"\\F1472\"}.mdi-fan-speed-2::before{content:\"\\F1473\"}.mdi-fan-speed-3::before{content:\"\\F1474\"}.mdi-fast-forward::before{content:\"\\F0211\"}.mdi-fast-forward-10::before{content:\"\\F0D71\"}.mdi-fast-forward-30::before{content:\"\\F0D06\"}.mdi-fast-forward-5::before{content:\"\\F11F8\"}.mdi-fast-forward-60::before{content:\"\\F160B\"}.mdi-fast-forward-outline::before{content:\"\\F06D2\"}.mdi-fax::before{content:\"\\F0212\"}.mdi-feather::before{content:\"\\F06D3\"}.mdi-feature-search::before{content:\"\\F0A49\"}.mdi-feature-search-outline::before{content:\"\\F0A4A\"}.mdi-fedora::before{content:\"\\F08DB\"}.mdi-fencing::before{content:\"\\F14C1\"}.mdi-ferris-wheel::before{content:\"\\F0EA4\"}.mdi-ferry::before{content:\"\\F0213\"}.mdi-file::before{content:\"\\F0214\"}.mdi-file-account::before{content:\"\\F073B\"}.mdi-file-account-outline::before{content:\"\\F1028\"}.mdi-file-alert::before{content:\"\\F0A4B\"}.mdi-file-alert-outline::before{content:\"\\F0A4C\"}.mdi-file-cabinet::before{content:\"\\F0AB6\"}.mdi-file-cad::before{content:\"\\F0EEB\"}.mdi-file-cad-box::before{content:\"\\F0EEC\"}.mdi-file-cancel::before{content:\"\\F0DC6\"}.mdi-file-cancel-outline::before{content:\"\\F0DC7\"}.mdi-file-certificate::before{content:\"\\F1186\"}.mdi-file-certificate-outline::before{content:\"\\F1187\"}.mdi-file-chart::before{content:\"\\F0215\"}.mdi-file-chart-outline::before{content:\"\\F1029\"}.mdi-file-check::before{content:\"\\F0216\"}.mdi-file-check-outline::before{content:\"\\F0E29\"}.mdi-file-clock::before{content:\"\\F12E1\"}.mdi-file-clock-outline::before{content:\"\\F12E2\"}.mdi-file-cloud::before{content:\"\\F0217\"}.mdi-file-cloud-outline::before{content:\"\\F102A\"}.mdi-file-code::before{content:\"\\F022E\"}.mdi-file-code-outline::before{content:\"\\F102B\"}.mdi-file-cog::before{content:\"\\F107B\"}.mdi-file-cog-outline::before{content:\"\\F107C\"}.mdi-file-compare::before{content:\"\\F08AA\"}.mdi-file-delimited::before{content:\"\\F0218\"}.mdi-file-delimited-outline::before{content:\"\\F0EA5\"}.mdi-file-document::before{content:\"\\F0219\"}.mdi-file-document-edit::before{content:\"\\F0DC8\"}.mdi-file-document-edit-outline::before{content:\"\\F0DC9\"}.mdi-file-document-multiple::before{content:\"\\F1517\"}.mdi-file-document-multiple-outline::before{content:\"\\F1518\"}.mdi-file-document-outline::before{content:\"\\F09EE\"}.mdi-file-download::before{content:\"\\F0965\"}.mdi-file-download-outline::before{content:\"\\F0966\"}.mdi-file-edit::before{content:\"\\F11E7\"}.mdi-file-edit-outline::before{content:\"\\F11E8\"}.mdi-file-excel::before{content:\"\\F021B\"}.mdi-file-excel-box::before{content:\"\\F021C\"}.mdi-file-excel-box-outline::before{content:\"\\F102C\"}.mdi-file-excel-outline::before{content:\"\\F102D\"}.mdi-file-export::before{content:\"\\F021D\"}.mdi-file-export-outline::before{content:\"\\F102E\"}.mdi-file-eye::before{content:\"\\F0DCA\"}.mdi-file-eye-outline::before{content:\"\\F0DCB\"}.mdi-file-find::before{content:\"\\F021E\"}.mdi-file-find-outline::before{content:\"\\F0B97\"}.mdi-file-hidden::before{content:\"\\F0613\"}.mdi-file-image::before{content:\"\\F021F\"}.mdi-file-image-outline::before{content:\"\\F0EB0\"}.mdi-file-import::before{content:\"\\F0220\"}.mdi-file-import-outline::before{content:\"\\F102F\"}.mdi-file-key::before{content:\"\\F1184\"}.mdi-file-key-outline::before{content:\"\\F1185\"}.mdi-file-link::before{content:\"\\F1177\"}.mdi-file-link-outline::before{content:\"\\F1178\"}.mdi-file-lock::before{content:\"\\F0221\"}.mdi-file-lock-outline::before{content:\"\\F1030\"}.mdi-file-move::before{content:\"\\F0AB9\"}.mdi-file-move-outline::before{content:\"\\F1031\"}.mdi-file-multiple::before{content:\"\\F0222\"}.mdi-file-multiple-outline::before{content:\"\\F1032\"}.mdi-file-music::before{content:\"\\F0223\"}.mdi-file-music-outline::before{content:\"\\F0E2A\"}.mdi-file-outline::before{content:\"\\F0224\"}.mdi-file-pdf::before{content:\"\\F0225\"}.mdi-file-pdf-box::before{content:\"\\F0226\"}.mdi-file-pdf-box-outline::before{content:\"\\F0FB3\"}.mdi-file-pdf-outline::before{content:\"\\F0E2D\"}.mdi-file-percent::before{content:\"\\F081E\"}.mdi-file-percent-outline::before{content:\"\\F1033\"}.mdi-file-phone::before{content:\"\\F1179\"}.mdi-file-phone-outline::before{content:\"\\F117A\"}.mdi-file-plus::before{content:\"\\F0752\"}.mdi-file-plus-outline::before{content:\"\\F0EED\"}.mdi-file-powerpoint::before{content:\"\\F0227\"}.mdi-file-powerpoint-box::before{content:\"\\F0228\"}.mdi-file-powerpoint-box-outline::before{content:\"\\F1034\"}.mdi-file-powerpoint-outline::before{content:\"\\F1035\"}.mdi-file-presentation-box::before{content:\"\\F0229\"}.mdi-file-question::before{content:\"\\F086F\"}.mdi-file-question-outline::before{content:\"\\F1036\"}.mdi-file-refresh::before{content:\"\\F0918\"}.mdi-file-refresh-outline::before{content:\"\\F0541\"}.mdi-file-remove::before{content:\"\\F0B98\"}.mdi-file-remove-outline::before{content:\"\\F1037\"}.mdi-file-replace::before{content:\"\\F0B32\"}.mdi-file-replace-outline::before{content:\"\\F0B33\"}.mdi-file-restore::before{content:\"\\F0670\"}.mdi-file-restore-outline::before{content:\"\\F1038\"}.mdi-file-search::before{content:\"\\F0C7C\"}.mdi-file-search-outline::before{content:\"\\F0C7D\"}.mdi-file-send::before{content:\"\\F022A\"}.mdi-file-send-outline::before{content:\"\\F1039\"}.mdi-file-settings::before{content:\"\\F1079\"}.mdi-file-settings-outline::before{content:\"\\F107A\"}.mdi-file-star::before{content:\"\\F103A\"}.mdi-file-star-outline::before{content:\"\\F103B\"}.mdi-file-swap::before{content:\"\\F0FB4\"}.mdi-file-swap-outline::before{content:\"\\F0FB5\"}.mdi-file-sync::before{content:\"\\F1216\"}.mdi-file-sync-outline::before{content:\"\\F1217\"}.mdi-file-table::before{content:\"\\F0C7E\"}.mdi-file-table-box::before{content:\"\\F10E1\"}.mdi-file-table-box-multiple::before{content:\"\\F10E2\"}.mdi-file-table-box-multiple-outline::before{content:\"\\F10E3\"}.mdi-file-table-box-outline::before{content:\"\\F10E4\"}.mdi-file-table-outline::before{content:\"\\F0C7F\"}.mdi-file-tree::before{content:\"\\F0645\"}.mdi-file-tree-outline::before{content:\"\\F13D2\"}.mdi-file-undo::before{content:\"\\F08DC\"}.mdi-file-undo-outline::before{content:\"\\F103C\"}.mdi-file-upload::before{content:\"\\F0A4D\"}.mdi-file-upload-outline::before{content:\"\\F0A4E\"}.mdi-file-video::before{content:\"\\F022B\"}.mdi-file-video-outline::before{content:\"\\F0E2C\"}.mdi-file-word::before{content:\"\\F022C\"}.mdi-file-word-box::before{content:\"\\F022D\"}.mdi-file-word-box-outline::before{content:\"\\F103D\"}.mdi-file-word-outline::before{content:\"\\F103E\"}.mdi-film::before{content:\"\\F022F\"}.mdi-filmstrip::before{content:\"\\F0230\"}.mdi-filmstrip-box::before{content:\"\\F0332\"}.mdi-filmstrip-box-multiple::before{content:\"\\F0D18\"}.mdi-filmstrip-off::before{content:\"\\F0231\"}.mdi-filter::before{content:\"\\F0232\"}.mdi-filter-menu::before{content:\"\\F10E5\"}.mdi-filter-menu-outline::before{content:\"\\F10E6\"}.mdi-filter-minus::before{content:\"\\F0EEE\"}.mdi-filter-minus-outline::before{content:\"\\F0EEF\"}.mdi-filter-off::before{content:\"\\F14EF\"}.mdi-filter-off-outline::before{content:\"\\F14F0\"}.mdi-filter-outline::before{content:\"\\F0233\"}.mdi-filter-plus::before{content:\"\\F0EF0\"}.mdi-filter-plus-outline::before{content:\"\\F0EF1\"}.mdi-filter-remove::before{content:\"\\F0234\"}.mdi-filter-remove-outline::before{content:\"\\F0235\"}.mdi-filter-variant::before{content:\"\\F0236\"}.mdi-filter-variant-minus::before{content:\"\\F1112\"}.mdi-filter-variant-plus::before{content:\"\\F1113\"}.mdi-filter-variant-remove::before{content:\"\\F103F\"}.mdi-finance::before{content:\"\\F081F\"}.mdi-find-replace::before{content:\"\\F06D4\"}.mdi-fingerprint::before{content:\"\\F0237\"}.mdi-fingerprint-off::before{content:\"\\F0EB1\"}.mdi-fire::before{content:\"\\F0238\"}.mdi-fire-alert::before{content:\"\\F15D7\"}.mdi-fire-extinguisher::before{content:\"\\F0EF2\"}.mdi-fire-hydrant::before{content:\"\\F1137\"}.mdi-fire-hydrant-alert::before{content:\"\\F1138\"}.mdi-fire-hydrant-off::before{content:\"\\F1139\"}.mdi-fire-truck::before{content:\"\\F08AB\"}.mdi-firebase::before{content:\"\\F0967\"}.mdi-firefox::before{content:\"\\F0239\"}.mdi-fireplace::before{content:\"\\F0E2E\"}.mdi-fireplace-off::before{content:\"\\F0E2F\"}.mdi-firework::before{content:\"\\F0E30\"}.mdi-fish::before{content:\"\\F023A\"}.mdi-fish-off::before{content:\"\\F13F3\"}.mdi-fishbowl::before{content:\"\\F0EF3\"}.mdi-fishbowl-outline::before{content:\"\\F0EF4\"}.mdi-fit-to-page::before{content:\"\\F0EF5\"}.mdi-fit-to-page-outline::before{content:\"\\F0EF6\"}.mdi-flag::before{content:\"\\F023B\"}.mdi-flag-checkered::before{content:\"\\F023C\"}.mdi-flag-minus::before{content:\"\\F0B99\"}.mdi-flag-minus-outline::before{content:\"\\F10B2\"}.mdi-flag-outline::before{content:\"\\F023D\"}.mdi-flag-plus::before{content:\"\\F0B9A\"}.mdi-flag-plus-outline::before{content:\"\\F10B3\"}.mdi-flag-remove::before{content:\"\\F0B9B\"}.mdi-flag-remove-outline::before{content:\"\\F10B4\"}.mdi-flag-triangle::before{content:\"\\F023F\"}.mdi-flag-variant::before{content:\"\\F0240\"}.mdi-flag-variant-outline::before{content:\"\\F023E\"}.mdi-flare::before{content:\"\\F0D72\"}.mdi-flash::before{content:\"\\F0241\"}.mdi-flash-alert::before{content:\"\\F0EF7\"}.mdi-flash-alert-outline::before{content:\"\\F0EF8\"}.mdi-flash-auto::before{content:\"\\F0242\"}.mdi-flash-circle::before{content:\"\\F0820\"}.mdi-flash-off::before{content:\"\\F0243\"}.mdi-flash-outline::before{content:\"\\F06D5\"}.mdi-flash-red-eye::before{content:\"\\F067B\"}.mdi-flashlight::before{content:\"\\F0244\"}.mdi-flashlight-off::before{content:\"\\F0245\"}.mdi-flask::before{content:\"\\F0093\"}.mdi-flask-empty::before{content:\"\\F0094\"}.mdi-flask-empty-minus::before{content:\"\\F123A\"}.mdi-flask-empty-minus-outline::before{content:\"\\F123B\"}.mdi-flask-empty-off::before{content:\"\\F13F4\"}.mdi-flask-empty-off-outline::before{content:\"\\F13F5\"}.mdi-flask-empty-outline::before{content:\"\\F0095\"}.mdi-flask-empty-plus::before{content:\"\\F123C\"}.mdi-flask-empty-plus-outline::before{content:\"\\F123D\"}.mdi-flask-empty-remove::before{content:\"\\F123E\"}.mdi-flask-empty-remove-outline::before{content:\"\\F123F\"}.mdi-flask-minus::before{content:\"\\F1240\"}.mdi-flask-minus-outline::before{content:\"\\F1241\"}.mdi-flask-off::before{content:\"\\F13F6\"}.mdi-flask-off-outline::before{content:\"\\F13F7\"}.mdi-flask-outline::before{content:\"\\F0096\"}.mdi-flask-plus::before{content:\"\\F1242\"}.mdi-flask-plus-outline::before{content:\"\\F1243\"}.mdi-flask-remove::before{content:\"\\F1244\"}.mdi-flask-remove-outline::before{content:\"\\F1245\"}.mdi-flask-round-bottom::before{content:\"\\F124B\"}.mdi-flask-round-bottom-empty::before{content:\"\\F124C\"}.mdi-flask-round-bottom-empty-outline::before{content:\"\\F124D\"}.mdi-flask-round-bottom-outline::before{content:\"\\F124E\"}.mdi-fleur-de-lis::before{content:\"\\F1303\"}.mdi-flip-horizontal::before{content:\"\\F10E7\"}.mdi-flip-to-back::before{content:\"\\F0247\"}.mdi-flip-to-front::before{content:\"\\F0248\"}.mdi-flip-vertical::before{content:\"\\F10E8\"}.mdi-floor-lamp::before{content:\"\\F08DD\"}.mdi-floor-lamp-dual::before{content:\"\\F1040\"}.mdi-floor-lamp-variant::before{content:\"\\F1041\"}.mdi-floor-plan::before{content:\"\\F0821\"}.mdi-floppy::before{content:\"\\F0249\"}.mdi-floppy-variant::before{content:\"\\F09EF\"}.mdi-flower::before{content:\"\\F024A\"}.mdi-flower-outline::before{content:\"\\F09F0\"}.mdi-flower-poppy::before{content:\"\\F0D08\"}.mdi-flower-tulip::before{content:\"\\F09F1\"}.mdi-flower-tulip-outline::before{content:\"\\F09F2\"}.mdi-focus-auto::before{content:\"\\F0F4E\"}.mdi-focus-field::before{content:\"\\F0F4F\"}.mdi-focus-field-horizontal::before{content:\"\\F0F50\"}.mdi-focus-field-vertical::before{content:\"\\F0F51\"}.mdi-folder::before{content:\"\\F024B\"}.mdi-folder-account::before{content:\"\\F024C\"}.mdi-folder-account-outline::before{content:\"\\F0B9C\"}.mdi-folder-alert::before{content:\"\\F0DCC\"}.mdi-folder-alert-outline::before{content:\"\\F0DCD\"}.mdi-folder-clock::before{content:\"\\F0ABA\"}.mdi-folder-clock-outline::before{content:\"\\F0ABB\"}.mdi-folder-cog::before{content:\"\\F107F\"}.mdi-folder-cog-outline::before{content:\"\\F1080\"}.mdi-folder-download::before{content:\"\\F024D\"}.mdi-folder-download-outline::before{content:\"\\F10E9\"}.mdi-folder-edit::before{content:\"\\F08DE\"}.mdi-folder-edit-outline::before{content:\"\\F0DCE\"}.mdi-folder-google-drive::before{content:\"\\F024E\"}.mdi-folder-heart::before{content:\"\\F10EA\"}.mdi-folder-heart-outline::before{content:\"\\F10EB\"}.mdi-folder-home::before{content:\"\\F10B5\"}.mdi-folder-home-outline::before{content:\"\\F10B6\"}.mdi-folder-image::before{content:\"\\F024F\"}.mdi-folder-information::before{content:\"\\F10B7\"}.mdi-folder-information-outline::before{content:\"\\F10B8\"}.mdi-folder-key::before{content:\"\\F08AC\"}.mdi-folder-key-network::before{content:\"\\F08AD\"}.mdi-folder-key-network-outline::before{content:\"\\F0C80\"}.mdi-folder-key-outline::before{content:\"\\F10EC\"}.mdi-folder-lock::before{content:\"\\F0250\"}.mdi-folder-lock-open::before{content:\"\\F0251\"}.mdi-folder-marker::before{content:\"\\F126D\"}.mdi-folder-marker-outline::before{content:\"\\F126E\"}.mdi-folder-move::before{content:\"\\F0252\"}.mdi-folder-move-outline::before{content:\"\\F1246\"}.mdi-folder-multiple::before{content:\"\\F0253\"}.mdi-folder-multiple-image::before{content:\"\\F0254\"}.mdi-folder-multiple-outline::before{content:\"\\F0255\"}.mdi-folder-multiple-plus::before{content:\"\\F147E\"}.mdi-folder-multiple-plus-outline::before{content:\"\\F147F\"}.mdi-folder-music::before{content:\"\\F1359\"}.mdi-folder-music-outline::before{content:\"\\F135A\"}.mdi-folder-network::before{content:\"\\F0870\"}.mdi-folder-network-outline::before{content:\"\\F0C81\"}.mdi-folder-open::before{content:\"\\F0770\"}.mdi-folder-open-outline::before{content:\"\\F0DCF\"}.mdi-folder-outline::before{content:\"\\F0256\"}.mdi-folder-plus::before{content:\"\\F0257\"}.mdi-folder-plus-outline::before{content:\"\\F0B9D\"}.mdi-folder-pound::before{content:\"\\F0D09\"}.mdi-folder-pound-outline::before{content:\"\\F0D0A\"}.mdi-folder-refresh::before{content:\"\\F0749\"}.mdi-folder-refresh-outline::before{content:\"\\F0542\"}.mdi-folder-remove::before{content:\"\\F0258\"}.mdi-folder-remove-outline::before{content:\"\\F0B9E\"}.mdi-folder-search::before{content:\"\\F0968\"}.mdi-folder-search-outline::before{content:\"\\F0969\"}.mdi-folder-settings::before{content:\"\\F107D\"}.mdi-folder-settings-outline::before{content:\"\\F107E\"}.mdi-folder-star::before{content:\"\\F069D\"}.mdi-folder-star-multiple::before{content:\"\\F13D3\"}.mdi-folder-star-multiple-outline::before{content:\"\\F13D4\"}.mdi-folder-star-outline::before{content:\"\\F0B9F\"}.mdi-folder-swap::before{content:\"\\F0FB6\"}.mdi-folder-swap-outline::before{content:\"\\F0FB7\"}.mdi-folder-sync::before{content:\"\\F0D0B\"}.mdi-folder-sync-outline::before{content:\"\\F0D0C\"}.mdi-folder-table::before{content:\"\\F12E3\"}.mdi-folder-table-outline::before{content:\"\\F12E4\"}.mdi-folder-text::before{content:\"\\F0C82\"}.mdi-folder-text-outline::before{content:\"\\F0C83\"}.mdi-folder-upload::before{content:\"\\F0259\"}.mdi-folder-upload-outline::before{content:\"\\F10ED\"}.mdi-folder-zip::before{content:\"\\F06EB\"}.mdi-folder-zip-outline::before{content:\"\\F07B9\"}.mdi-font-awesome::before{content:\"\\F003A\"}.mdi-food::before{content:\"\\F025A\"}.mdi-food-apple::before{content:\"\\F025B\"}.mdi-food-apple-outline::before{content:\"\\F0C84\"}.mdi-food-croissant::before{content:\"\\F07C8\"}.mdi-food-drumstick::before{content:\"\\F141F\"}.mdi-food-drumstick-off::before{content:\"\\F1468\"}.mdi-food-drumstick-off-outline::before{content:\"\\F1469\"}.mdi-food-drumstick-outline::before{content:\"\\F1420\"}.mdi-food-fork-drink::before{content:\"\\F05F2\"}.mdi-food-halal::before{content:\"\\F1572\"}.mdi-food-kosher::before{content:\"\\F1573\"}.mdi-food-off::before{content:\"\\F05F3\"}.mdi-food-steak::before{content:\"\\F146A\"}.mdi-food-steak-off::before{content:\"\\F146B\"}.mdi-food-variant::before{content:\"\\F025C\"}.mdi-food-variant-off::before{content:\"\\F13E5\"}.mdi-foot-print::before{content:\"\\F0F52\"}.mdi-football::before{content:\"\\F025D\"}.mdi-football-australian::before{content:\"\\F025E\"}.mdi-football-helmet::before{content:\"\\F025F\"}.mdi-forklift::before{content:\"\\F07C9\"}.mdi-form-dropdown::before{content:\"\\F1400\"}.mdi-form-select::before{content:\"\\F1401\"}.mdi-form-textarea::before{content:\"\\F1095\"}.mdi-form-textbox::before{content:\"\\F060E\"}.mdi-form-textbox-lock::before{content:\"\\F135D\"}.mdi-form-textbox-password::before{content:\"\\F07F5\"}.mdi-format-align-bottom::before{content:\"\\F0753\"}.mdi-format-align-center::before{content:\"\\F0260\"}.mdi-format-align-justify::before{content:\"\\F0261\"}.mdi-format-align-left::before{content:\"\\F0262\"}.mdi-format-align-middle::before{content:\"\\F0754\"}.mdi-format-align-right::before{content:\"\\F0263\"}.mdi-format-align-top::before{content:\"\\F0755\"}.mdi-format-annotation-minus::before{content:\"\\F0ABC\"}.mdi-format-annotation-plus::before{content:\"\\F0646\"}.mdi-format-bold::before{content:\"\\F0264\"}.mdi-format-clear::before{content:\"\\F0265\"}.mdi-format-color-fill::before{content:\"\\F0266\"}.mdi-format-color-highlight::before{content:\"\\F0E31\"}.mdi-format-color-marker-cancel::before{content:\"\\F1313\"}.mdi-format-color-text::before{content:\"\\F069E\"}.mdi-format-columns::before{content:\"\\F08DF\"}.mdi-format-float-center::before{content:\"\\F0267\"}.mdi-format-float-left::before{content:\"\\F0268\"}.mdi-format-float-none::before{content:\"\\F0269\"}.mdi-format-float-right::before{content:\"\\F026A\"}.mdi-format-font::before{content:\"\\F06D6\"}.mdi-format-font-size-decrease::before{content:\"\\F09F3\"}.mdi-format-font-size-increase::before{content:\"\\F09F4\"}.mdi-format-header-1::before{content:\"\\F026B\"}.mdi-format-header-2::before{content:\"\\F026C\"}.mdi-format-header-3::before{content:\"\\F026D\"}.mdi-format-header-4::before{content:\"\\F026E\"}.mdi-format-header-5::before{content:\"\\F026F\"}.mdi-format-header-6::before{content:\"\\F0270\"}.mdi-format-header-decrease::before{content:\"\\F0271\"}.mdi-format-header-equal::before{content:\"\\F0272\"}.mdi-format-header-increase::before{content:\"\\F0273\"}.mdi-format-header-pound::before{content:\"\\F0274\"}.mdi-format-horizontal-align-center::before{content:\"\\F061E\"}.mdi-format-horizontal-align-left::before{content:\"\\F061F\"}.mdi-format-horizontal-align-right::before{content:\"\\F0620\"}.mdi-format-indent-decrease::before{content:\"\\F0275\"}.mdi-format-indent-increase::before{content:\"\\F0276\"}.mdi-format-italic::before{content:\"\\F0277\"}.mdi-format-letter-case::before{content:\"\\F0B34\"}.mdi-format-letter-case-lower::before{content:\"\\F0B35\"}.mdi-format-letter-case-upper::before{content:\"\\F0B36\"}.mdi-format-letter-ends-with::before{content:\"\\F0FB8\"}.mdi-format-letter-matches::before{content:\"\\F0FB9\"}.mdi-format-letter-starts-with::before{content:\"\\F0FBA\"}.mdi-format-line-spacing::before{content:\"\\F0278\"}.mdi-format-line-style::before{content:\"\\F05C8\"}.mdi-format-line-weight::before{content:\"\\F05C9\"}.mdi-format-list-bulleted::before{content:\"\\F0279\"}.mdi-format-list-bulleted-square::before{content:\"\\F0DD0\"}.mdi-format-list-bulleted-triangle::before{content:\"\\F0EB2\"}.mdi-format-list-bulleted-type::before{content:\"\\F027A\"}.mdi-format-list-checkbox::before{content:\"\\F096A\"}.mdi-format-list-checks::before{content:\"\\F0756\"}.mdi-format-list-numbered::before{content:\"\\F027B\"}.mdi-format-list-numbered-rtl::before{content:\"\\F0D0D\"}.mdi-format-list-text::before{content:\"\\F126F\"}.mdi-format-overline::before{content:\"\\F0EB3\"}.mdi-format-page-break::before{content:\"\\F06D7\"}.mdi-format-paint::before{content:\"\\F027C\"}.mdi-format-paragraph::before{content:\"\\F027D\"}.mdi-format-pilcrow::before{content:\"\\F06D8\"}.mdi-format-quote-close::before{content:\"\\F027E\"}.mdi-format-quote-close-outline::before{content:\"\\F11A8\"}.mdi-format-quote-open::before{content:\"\\F0757\"}.mdi-format-quote-open-outline::before{content:\"\\F11A7\"}.mdi-format-rotate-90::before{content:\"\\F06AA\"}.mdi-format-section::before{content:\"\\F069F\"}.mdi-format-size::before{content:\"\\F027F\"}.mdi-format-strikethrough::before{content:\"\\F0280\"}.mdi-format-strikethrough-variant::before{content:\"\\F0281\"}.mdi-format-subscript::before{content:\"\\F0282\"}.mdi-format-superscript::before{content:\"\\F0283\"}.mdi-format-text::before{content:\"\\F0284\"}.mdi-format-text-rotation-angle-down::before{content:\"\\F0FBB\"}.mdi-format-text-rotation-angle-up::before{content:\"\\F0FBC\"}.mdi-format-text-rotation-down::before{content:\"\\F0D73\"}.mdi-format-text-rotation-down-vertical::before{content:\"\\F0FBD\"}.mdi-format-text-rotation-none::before{content:\"\\F0D74\"}.mdi-format-text-rotation-up::before{content:\"\\F0FBE\"}.mdi-format-text-rotation-vertical::before{content:\"\\F0FBF\"}.mdi-format-text-variant::before{content:\"\\F0E32\"}.mdi-format-text-variant-outline::before{content:\"\\F150F\"}.mdi-format-text-wrapping-clip::before{content:\"\\F0D0E\"}.mdi-format-text-wrapping-overflow::before{content:\"\\F0D0F\"}.mdi-format-text-wrapping-wrap::before{content:\"\\F0D10\"}.mdi-format-textbox::before{content:\"\\F0D11\"}.mdi-format-textdirection-l-to-r::before{content:\"\\F0285\"}.mdi-format-textdirection-r-to-l::before{content:\"\\F0286\"}.mdi-format-title::before{content:\"\\F05F4\"}.mdi-format-underline::before{content:\"\\F0287\"}.mdi-format-vertical-align-bottom::before{content:\"\\F0621\"}.mdi-format-vertical-align-center::before{content:\"\\F0622\"}.mdi-format-vertical-align-top::before{content:\"\\F0623\"}.mdi-format-wrap-inline::before{content:\"\\F0288\"}.mdi-format-wrap-square::before{content:\"\\F0289\"}.mdi-format-wrap-tight::before{content:\"\\F028A\"}.mdi-format-wrap-top-bottom::before{content:\"\\F028B\"}.mdi-forum::before{content:\"\\F028C\"}.mdi-forum-outline::before{content:\"\\F0822\"}.mdi-forward::before{content:\"\\F028D\"}.mdi-forwardburger::before{content:\"\\F0D75\"}.mdi-fountain::before{content:\"\\F096B\"}.mdi-fountain-pen::before{content:\"\\F0D12\"}.mdi-fountain-pen-tip::before{content:\"\\F0D13\"}.mdi-freebsd::before{content:\"\\F08E0\"}.mdi-frequently-asked-questions::before{content:\"\\F0EB4\"}.mdi-fridge::before{content:\"\\F0290\"}.mdi-fridge-alert::before{content:\"\\F11B1\"}.mdi-fridge-alert-outline::before{content:\"\\F11B2\"}.mdi-fridge-bottom::before{content:\"\\F0292\"}.mdi-fridge-industrial::before{content:\"\\F15EE\"}.mdi-fridge-industrial-alert::before{content:\"\\F15EF\"}.mdi-fridge-industrial-alert-outline::before{content:\"\\F15F0\"}.mdi-fridge-industrial-off::before{content:\"\\F15F1\"}.mdi-fridge-industrial-off-outline::before{content:\"\\F15F2\"}.mdi-fridge-industrial-outline::before{content:\"\\F15F3\"}.mdi-fridge-off::before{content:\"\\F11AF\"}.mdi-fridge-off-outline::before{content:\"\\F11B0\"}.mdi-fridge-outline::before{content:\"\\F028F\"}.mdi-fridge-top::before{content:\"\\F0291\"}.mdi-fridge-variant::before{content:\"\\F15F4\"}.mdi-fridge-variant-alert::before{content:\"\\F15F5\"}.mdi-fridge-variant-alert-outline::before{content:\"\\F15F6\"}.mdi-fridge-variant-off::before{content:\"\\F15F7\"}.mdi-fridge-variant-off-outline::before{content:\"\\F15F8\"}.mdi-fridge-variant-outline::before{content:\"\\F15F9\"}.mdi-fruit-cherries::before{content:\"\\F1042\"}.mdi-fruit-cherries-off::before{content:\"\\F13F8\"}.mdi-fruit-citrus::before{content:\"\\F1043\"}.mdi-fruit-citrus-off::before{content:\"\\F13F9\"}.mdi-fruit-grapes::before{content:\"\\F1044\"}.mdi-fruit-grapes-outline::before{content:\"\\F1045\"}.mdi-fruit-pineapple::before{content:\"\\F1046\"}.mdi-fruit-watermelon::before{content:\"\\F1047\"}.mdi-fuel::before{content:\"\\F07CA\"}.mdi-fullscreen::before{content:\"\\F0293\"}.mdi-fullscreen-exit::before{content:\"\\F0294\"}.mdi-function::before{content:\"\\F0295\"}.mdi-function-variant::before{content:\"\\F0871\"}.mdi-furigana-horizontal::before{content:\"\\F1081\"}.mdi-furigana-vertical::before{content:\"\\F1082\"}.mdi-fuse::before{content:\"\\F0C85\"}.mdi-fuse-alert::before{content:\"\\F142D\"}.mdi-fuse-blade::before{content:\"\\F0C86\"}.mdi-fuse-off::before{content:\"\\F142C\"}.mdi-gamepad::before{content:\"\\F0296\"}.mdi-gamepad-circle::before{content:\"\\F0E33\"}.mdi-gamepad-circle-down::before{content:\"\\F0E34\"}.mdi-gamepad-circle-left::before{content:\"\\F0E35\"}.mdi-gamepad-circle-outline::before{content:\"\\F0E36\"}.mdi-gamepad-circle-right::before{content:\"\\F0E37\"}.mdi-gamepad-circle-up::before{content:\"\\F0E38\"}.mdi-gamepad-down::before{content:\"\\F0E39\"}.mdi-gamepad-left::before{content:\"\\F0E3A\"}.mdi-gamepad-right::before{content:\"\\F0E3B\"}.mdi-gamepad-round::before{content:\"\\F0E3C\"}.mdi-gamepad-round-down::before{content:\"\\F0E3D\"}.mdi-gamepad-round-left::before{content:\"\\F0E3E\"}.mdi-gamepad-round-outline::before{content:\"\\F0E3F\"}.mdi-gamepad-round-right::before{content:\"\\F0E40\"}.mdi-gamepad-round-up::before{content:\"\\F0E41\"}.mdi-gamepad-square::before{content:\"\\F0EB5\"}.mdi-gamepad-square-outline::before{content:\"\\F0EB6\"}.mdi-gamepad-up::before{content:\"\\F0E42\"}.mdi-gamepad-variant::before{content:\"\\F0297\"}.mdi-gamepad-variant-outline::before{content:\"\\F0EB7\"}.mdi-gamma::before{content:\"\\F10EE\"}.mdi-gantry-crane::before{content:\"\\F0DD1\"}.mdi-garage::before{content:\"\\F06D9\"}.mdi-garage-alert::before{content:\"\\F0872\"}.mdi-garage-alert-variant::before{content:\"\\F12D5\"}.mdi-garage-open::before{content:\"\\F06DA\"}.mdi-garage-open-variant::before{content:\"\\F12D4\"}.mdi-garage-variant::before{content:\"\\F12D3\"}.mdi-gas-cylinder::before{content:\"\\F0647\"}.mdi-gas-station::before{content:\"\\F0298\"}.mdi-gas-station-off::before{content:\"\\F1409\"}.mdi-gas-station-off-outline::before{content:\"\\F140A\"}.mdi-gas-station-outline::before{content:\"\\F0EB8\"}.mdi-gate::before{content:\"\\F0299\"}.mdi-gate-and::before{content:\"\\F08E1\"}.mdi-gate-arrow-right::before{content:\"\\F1169\"}.mdi-gate-nand::before{content:\"\\F08E2\"}.mdi-gate-nor::before{content:\"\\F08E3\"}.mdi-gate-not::before{content:\"\\F08E4\"}.mdi-gate-open::before{content:\"\\F116A\"}.mdi-gate-or::before{content:\"\\F08E5\"}.mdi-gate-xnor::before{content:\"\\F08E6\"}.mdi-gate-xor::before{content:\"\\F08E7\"}.mdi-gatsby::before{content:\"\\F0E43\"}.mdi-gauge::before{content:\"\\F029A\"}.mdi-gauge-empty::before{content:\"\\F0873\"}.mdi-gauge-full::before{content:\"\\F0874\"}.mdi-gauge-low::before{content:\"\\F0875\"}.mdi-gavel::before{content:\"\\F029B\"}.mdi-gender-female::before{content:\"\\F029C\"}.mdi-gender-male::before{content:\"\\F029D\"}.mdi-gender-male-female::before{content:\"\\F029E\"}.mdi-gender-male-female-variant::before{content:\"\\F113F\"}.mdi-gender-non-binary::before{content:\"\\F1140\"}.mdi-gender-transgender::before{content:\"\\F029F\"}.mdi-gentoo::before{content:\"\\F08E8\"}.mdi-gesture::before{content:\"\\F07CB\"}.mdi-gesture-double-tap::before{content:\"\\F073C\"}.mdi-gesture-pinch::before{content:\"\\F0ABD\"}.mdi-gesture-spread::before{content:\"\\F0ABE\"}.mdi-gesture-swipe::before{content:\"\\F0D76\"}.mdi-gesture-swipe-down::before{content:\"\\F073D\"}.mdi-gesture-swipe-horizontal::before{content:\"\\F0ABF\"}.mdi-gesture-swipe-left::before{content:\"\\F073E\"}.mdi-gesture-swipe-right::before{content:\"\\F073F\"}.mdi-gesture-swipe-up::before{content:\"\\F0740\"}.mdi-gesture-swipe-vertical::before{content:\"\\F0AC0\"}.mdi-gesture-tap::before{content:\"\\F0741\"}.mdi-gesture-tap-box::before{content:\"\\F12A9\"}.mdi-gesture-tap-button::before{content:\"\\F12A8\"}.mdi-gesture-tap-hold::before{content:\"\\F0D77\"}.mdi-gesture-two-double-tap::before{content:\"\\F0742\"}.mdi-gesture-two-tap::before{content:\"\\F0743\"}.mdi-ghost::before{content:\"\\F02A0\"}.mdi-ghost-off::before{content:\"\\F09F5\"}.mdi-ghost-off-outline::before{content:\"\\F165C\"}.mdi-ghost-outline::before{content:\"\\F165D\"}.mdi-gif::before{content:\"\\F0D78\"}.mdi-gift::before{content:\"\\F0E44\"}.mdi-gift-outline::before{content:\"\\F02A1\"}.mdi-git::before{content:\"\\F02A2\"}.mdi-github::before{content:\"\\F02A4\"}.mdi-gitlab::before{content:\"\\F0BA0\"}.mdi-glass-cocktail::before{content:\"\\F0356\"}.mdi-glass-cocktail-off::before{content:\"\\F15E6\"}.mdi-glass-flute::before{content:\"\\F02A5\"}.mdi-glass-mug::before{content:\"\\F02A6\"}.mdi-glass-mug-off::before{content:\"\\F15E7\"}.mdi-glass-mug-variant::before{content:\"\\F1116\"}.mdi-glass-mug-variant-off::before{content:\"\\F15E8\"}.mdi-glass-pint-outline::before{content:\"\\F130D\"}.mdi-glass-stange::before{content:\"\\F02A7\"}.mdi-glass-tulip::before{content:\"\\F02A8\"}.mdi-glass-wine::before{content:\"\\F0876\"}.mdi-glasses::before{content:\"\\F02AA\"}.mdi-globe-light::before{content:\"\\F12D7\"}.mdi-globe-model::before{content:\"\\F08E9\"}.mdi-gmail::before{content:\"\\F02AB\"}.mdi-gnome::before{content:\"\\F02AC\"}.mdi-go-kart::before{content:\"\\F0D79\"}.mdi-go-kart-track::before{content:\"\\F0D7A\"}.mdi-gog::before{content:\"\\F0BA1\"}.mdi-gold::before{content:\"\\F124F\"}.mdi-golf::before{content:\"\\F0823\"}.mdi-golf-cart::before{content:\"\\F11A4\"}.mdi-golf-tee::before{content:\"\\F1083\"}.mdi-gondola::before{content:\"\\F0686\"}.mdi-goodreads::before{content:\"\\F0D7B\"}.mdi-google::before{content:\"\\F02AD\"}.mdi-google-ads::before{content:\"\\F0C87\"}.mdi-google-analytics::before{content:\"\\F07CC\"}.mdi-google-assistant::before{content:\"\\F07CD\"}.mdi-google-cardboard::before{content:\"\\F02AE\"}.mdi-google-chrome::before{content:\"\\F02AF\"}.mdi-google-circles::before{content:\"\\F02B0\"}.mdi-google-circles-communities::before{content:\"\\F02B1\"}.mdi-google-circles-extended::before{content:\"\\F02B2\"}.mdi-google-circles-group::before{content:\"\\F02B3\"}.mdi-google-classroom::before{content:\"\\F02C0\"}.mdi-google-cloud::before{content:\"\\F11F6\"}.mdi-google-controller::before{content:\"\\F02B4\"}.mdi-google-controller-off::before{content:\"\\F02B5\"}.mdi-google-downasaur::before{content:\"\\F1362\"}.mdi-google-drive::before{content:\"\\F02B6\"}.mdi-google-earth::before{content:\"\\F02B7\"}.mdi-google-fit::before{content:\"\\F096C\"}.mdi-google-glass::before{content:\"\\F02B8\"}.mdi-google-hangouts::before{content:\"\\F02C9\"}.mdi-google-home::before{content:\"\\F0824\"}.mdi-google-keep::before{content:\"\\F06DC\"}.mdi-google-lens::before{content:\"\\F09F6\"}.mdi-google-maps::before{content:\"\\F05F5\"}.mdi-google-my-business::before{content:\"\\F1048\"}.mdi-google-nearby::before{content:\"\\F02B9\"}.mdi-google-photos::before{content:\"\\F06DD\"}.mdi-google-play::before{content:\"\\F02BC\"}.mdi-google-plus::before{content:\"\\F02BD\"}.mdi-google-podcast::before{content:\"\\F0EB9\"}.mdi-google-spreadsheet::before{content:\"\\F09F7\"}.mdi-google-street-view::before{content:\"\\F0C88\"}.mdi-google-translate::before{content:\"\\F02BF\"}.mdi-gradient::before{content:\"\\F06A0\"}.mdi-grain::before{content:\"\\F0D7C\"}.mdi-graph::before{content:\"\\F1049\"}.mdi-graph-outline::before{content:\"\\F104A\"}.mdi-graphql::before{content:\"\\F0877\"}.mdi-grass::before{content:\"\\F1510\"}.mdi-grave-stone::before{content:\"\\F0BA2\"}.mdi-grease-pencil::before{content:\"\\F0648\"}.mdi-greater-than::before{content:\"\\F096D\"}.mdi-greater-than-or-equal::before{content:\"\\F096E\"}.mdi-grid::before{content:\"\\F02C1\"}.mdi-grid-large::before{content:\"\\F0758\"}.mdi-grid-off::before{content:\"\\F02C2\"}.mdi-grill::before{content:\"\\F0E45\"}.mdi-grill-outline::before{content:\"\\F118A\"}.mdi-group::before{content:\"\\F02C3\"}.mdi-guitar-acoustic::before{content:\"\\F0771\"}.mdi-guitar-electric::before{content:\"\\F02C4\"}.mdi-guitar-pick::before{content:\"\\F02C5\"}.mdi-guitar-pick-outline::before{content:\"\\F02C6\"}.mdi-guy-fawkes-mask::before{content:\"\\F0825\"}.mdi-hail::before{content:\"\\F0AC1\"}.mdi-hair-dryer::before{content:\"\\F10EF\"}.mdi-hair-dryer-outline::before{content:\"\\F10F0\"}.mdi-halloween::before{content:\"\\F0BA3\"}.mdi-hamburger::before{content:\"\\F0685\"}.mdi-hammer::before{content:\"\\F08EA\"}.mdi-hammer-screwdriver::before{content:\"\\F1322\"}.mdi-hammer-wrench::before{content:\"\\F1323\"}.mdi-hand::before{content:\"\\F0A4F\"}.mdi-hand-heart::before{content:\"\\F10F1\"}.mdi-hand-heart-outline::before{content:\"\\F157E\"}.mdi-hand-left::before{content:\"\\F0E46\"}.mdi-hand-okay::before{content:\"\\F0A50\"}.mdi-hand-peace::before{content:\"\\F0A51\"}.mdi-hand-peace-variant::before{content:\"\\F0A52\"}.mdi-hand-pointing-down::before{content:\"\\F0A53\"}.mdi-hand-pointing-left::before{content:\"\\F0A54\"}.mdi-hand-pointing-right::before{content:\"\\F02C7\"}.mdi-hand-pointing-up::before{content:\"\\F0A55\"}.mdi-hand-right::before{content:\"\\F0E47\"}.mdi-hand-saw::before{content:\"\\F0E48\"}.mdi-hand-wash::before{content:\"\\F157F\"}.mdi-hand-wash-outline::before{content:\"\\F1580\"}.mdi-hand-water::before{content:\"\\F139F\"}.mdi-handball::before{content:\"\\F0F53\"}.mdi-handcuffs::before{content:\"\\F113E\"}.mdi-handshake::before{content:\"\\F1218\"}.mdi-handshake-outline::before{content:\"\\F15A1\"}.mdi-hanger::before{content:\"\\F02C8\"}.mdi-hard-hat::before{content:\"\\F096F\"}.mdi-harddisk::before{content:\"\\F02CA\"}.mdi-harddisk-plus::before{content:\"\\F104B\"}.mdi-harddisk-remove::before{content:\"\\F104C\"}.mdi-hat-fedora::before{content:\"\\F0BA4\"}.mdi-hazard-lights::before{content:\"\\F0C89\"}.mdi-hdr::before{content:\"\\F0D7D\"}.mdi-hdr-off::before{content:\"\\F0D7E\"}.mdi-head::before{content:\"\\F135E\"}.mdi-head-alert::before{content:\"\\F1338\"}.mdi-head-alert-outline::before{content:\"\\F1339\"}.mdi-head-check::before{content:\"\\F133A\"}.mdi-head-check-outline::before{content:\"\\F133B\"}.mdi-head-cog::before{content:\"\\F133C\"}.mdi-head-cog-outline::before{content:\"\\F133D\"}.mdi-head-dots-horizontal::before{content:\"\\F133E\"}.mdi-head-dots-horizontal-outline::before{content:\"\\F133F\"}.mdi-head-flash::before{content:\"\\F1340\"}.mdi-head-flash-outline::before{content:\"\\F1341\"}.mdi-head-heart::before{content:\"\\F1342\"}.mdi-head-heart-outline::before{content:\"\\F1343\"}.mdi-head-lightbulb::before{content:\"\\F1344\"}.mdi-head-lightbulb-outline::before{content:\"\\F1345\"}.mdi-head-minus::before{content:\"\\F1346\"}.mdi-head-minus-outline::before{content:\"\\F1347\"}.mdi-head-outline::before{content:\"\\F135F\"}.mdi-head-plus::before{content:\"\\F1348\"}.mdi-head-plus-outline::before{content:\"\\F1349\"}.mdi-head-question::before{content:\"\\F134A\"}.mdi-head-question-outline::before{content:\"\\F134B\"}.mdi-head-remove::before{content:\"\\F134C\"}.mdi-head-remove-outline::before{content:\"\\F134D\"}.mdi-head-snowflake::before{content:\"\\F134E\"}.mdi-head-snowflake-outline::before{content:\"\\F134F\"}.mdi-head-sync::before{content:\"\\F1350\"}.mdi-head-sync-outline::before{content:\"\\F1351\"}.mdi-headphones::before{content:\"\\F02CB\"}.mdi-headphones-bluetooth::before{content:\"\\F0970\"}.mdi-headphones-box::before{content:\"\\F02CC\"}.mdi-headphones-off::before{content:\"\\F07CE\"}.mdi-headphones-settings::before{content:\"\\F02CD\"}.mdi-headset::before{content:\"\\F02CE\"}.mdi-headset-dock::before{content:\"\\F02CF\"}.mdi-headset-off::before{content:\"\\F02D0\"}.mdi-heart::before{content:\"\\F02D1\"}.mdi-heart-box::before{content:\"\\F02D2\"}.mdi-heart-box-outline::before{content:\"\\F02D3\"}.mdi-heart-broken::before{content:\"\\F02D4\"}.mdi-heart-broken-outline::before{content:\"\\F0D14\"}.mdi-heart-circle::before{content:\"\\F0971\"}.mdi-heart-circle-outline::before{content:\"\\F0972\"}.mdi-heart-cog::before{content:\"\\F1663\"}.mdi-heart-cog-outline::before{content:\"\\F1664\"}.mdi-heart-flash::before{content:\"\\F0EF9\"}.mdi-heart-half::before{content:\"\\F06DF\"}.mdi-heart-half-full::before{content:\"\\F06DE\"}.mdi-heart-half-outline::before{content:\"\\F06E0\"}.mdi-heart-minus::before{content:\"\\F142F\"}.mdi-heart-minus-outline::before{content:\"\\F1432\"}.mdi-heart-multiple::before{content:\"\\F0A56\"}.mdi-heart-multiple-outline::before{content:\"\\F0A57\"}.mdi-heart-off::before{content:\"\\F0759\"}.mdi-heart-off-outline::before{content:\"\\F1434\"}.mdi-heart-outline::before{content:\"\\F02D5\"}.mdi-heart-plus::before{content:\"\\F142E\"}.mdi-heart-plus-outline::before{content:\"\\F1431\"}.mdi-heart-pulse::before{content:\"\\F05F6\"}.mdi-heart-remove::before{content:\"\\F1430\"}.mdi-heart-remove-outline::before{content:\"\\F1433\"}.mdi-heart-settings::before{content:\"\\F1665\"}.mdi-heart-settings-outline::before{content:\"\\F1666\"}.mdi-helicopter::before{content:\"\\F0AC2\"}.mdi-help::before{content:\"\\F02D6\"}.mdi-help-box::before{content:\"\\F078B\"}.mdi-help-circle::before{content:\"\\F02D7\"}.mdi-help-circle-outline::before{content:\"\\F0625\"}.mdi-help-network::before{content:\"\\F06F5\"}.mdi-help-network-outline::before{content:\"\\F0C8A\"}.mdi-help-rhombus::before{content:\"\\F0BA5\"}.mdi-help-rhombus-outline::before{content:\"\\F0BA6\"}.mdi-hexadecimal::before{content:\"\\F12A7\"}.mdi-hexagon::before{content:\"\\F02D8\"}.mdi-hexagon-multiple::before{content:\"\\F06E1\"}.mdi-hexagon-multiple-outline::before{content:\"\\F10F2\"}.mdi-hexagon-outline::before{content:\"\\F02D9\"}.mdi-hexagon-slice-1::before{content:\"\\F0AC3\"}.mdi-hexagon-slice-2::before{content:\"\\F0AC4\"}.mdi-hexagon-slice-3::before{content:\"\\F0AC5\"}.mdi-hexagon-slice-4::before{content:\"\\F0AC6\"}.mdi-hexagon-slice-5::before{content:\"\\F0AC7\"}.mdi-hexagon-slice-6::before{content:\"\\F0AC8\"}.mdi-hexagram::before{content:\"\\F0AC9\"}.mdi-hexagram-outline::before{content:\"\\F0ACA\"}.mdi-high-definition::before{content:\"\\F07CF\"}.mdi-high-definition-box::before{content:\"\\F0878\"}.mdi-highway::before{content:\"\\F05F7\"}.mdi-hiking::before{content:\"\\F0D7F\"}.mdi-hinduism::before{content:\"\\F0973\"}.mdi-history::before{content:\"\\F02DA\"}.mdi-hockey-puck::before{content:\"\\F0879\"}.mdi-hockey-sticks::before{content:\"\\F087A\"}.mdi-hololens::before{content:\"\\F02DB\"}.mdi-home::before{content:\"\\F02DC\"}.mdi-home-account::before{content:\"\\F0826\"}.mdi-home-alert::before{content:\"\\F087B\"}.mdi-home-alert-outline::before{content:\"\\F15D0\"}.mdi-home-analytics::before{content:\"\\F0EBA\"}.mdi-home-assistant::before{content:\"\\F07D0\"}.mdi-home-automation::before{content:\"\\F07D1\"}.mdi-home-circle::before{content:\"\\F07D2\"}.mdi-home-circle-outline::before{content:\"\\F104D\"}.mdi-home-city::before{content:\"\\F0D15\"}.mdi-home-city-outline::before{content:\"\\F0D16\"}.mdi-home-currency-usd::before{content:\"\\F08AF\"}.mdi-home-edit::before{content:\"\\F1159\"}.mdi-home-edit-outline::before{content:\"\\F115A\"}.mdi-home-export-outline::before{content:\"\\F0F9B\"}.mdi-home-flood::before{content:\"\\F0EFA\"}.mdi-home-floor-0::before{content:\"\\F0DD2\"}.mdi-home-floor-1::before{content:\"\\F0D80\"}.mdi-home-floor-2::before{content:\"\\F0D81\"}.mdi-home-floor-3::before{content:\"\\F0D82\"}.mdi-home-floor-a::before{content:\"\\F0D83\"}.mdi-home-floor-b::before{content:\"\\F0D84\"}.mdi-home-floor-g::before{content:\"\\F0D85\"}.mdi-home-floor-l::before{content:\"\\F0D86\"}.mdi-home-floor-negative-1::before{content:\"\\F0DD3\"}.mdi-home-group::before{content:\"\\F0DD4\"}.mdi-home-heart::before{content:\"\\F0827\"}.mdi-home-import-outline::before{content:\"\\F0F9C\"}.mdi-home-lightbulb::before{content:\"\\F1251\"}.mdi-home-lightbulb-outline::before{content:\"\\F1252\"}.mdi-home-lock::before{content:\"\\F08EB\"}.mdi-home-lock-open::before{content:\"\\F08EC\"}.mdi-home-map-marker::before{content:\"\\F05F8\"}.mdi-home-minus::before{content:\"\\F0974\"}.mdi-home-minus-outline::before{content:\"\\F13D5\"}.mdi-home-modern::before{content:\"\\F02DD\"}.mdi-home-outline::before{content:\"\\F06A1\"}.mdi-home-plus::before{content:\"\\F0975\"}.mdi-home-plus-outline::before{content:\"\\F13D6\"}.mdi-home-remove::before{content:\"\\F1247\"}.mdi-home-remove-outline::before{content:\"\\F13D7\"}.mdi-home-roof::before{content:\"\\F112B\"}.mdi-home-search::before{content:\"\\F13B0\"}.mdi-home-search-outline::before{content:\"\\F13B1\"}.mdi-home-thermometer::before{content:\"\\F0F54\"}.mdi-home-thermometer-outline::before{content:\"\\F0F55\"}.mdi-home-variant::before{content:\"\\F02DE\"}.mdi-home-variant-outline::before{content:\"\\F0BA7\"}.mdi-hook::before{content:\"\\F06E2\"}.mdi-hook-off::before{content:\"\\F06E3\"}.mdi-hops::before{content:\"\\F02DF\"}.mdi-horizontal-rotate-clockwise::before{content:\"\\F10F3\"}.mdi-horizontal-rotate-counterclockwise::before{content:\"\\F10F4\"}.mdi-horse::before{content:\"\\F15BF\"}.mdi-horse-human::before{content:\"\\F15C0\"}.mdi-horse-variant::before{content:\"\\F15C1\"}.mdi-horseshoe::before{content:\"\\F0A58\"}.mdi-hospital::before{content:\"\\F0FF6\"}.mdi-hospital-box::before{content:\"\\F02E0\"}.mdi-hospital-box-outline::before{content:\"\\F0FF7\"}.mdi-hospital-building::before{content:\"\\F02E1\"}.mdi-hospital-marker::before{content:\"\\F02E2\"}.mdi-hot-tub::before{content:\"\\F0828\"}.mdi-hours-24::before{content:\"\\F1478\"}.mdi-hubspot::before{content:\"\\F0D17\"}.mdi-hulu::before{content:\"\\F0829\"}.mdi-human::before{content:\"\\F02E6\"}.mdi-human-baby-changing-table::before{content:\"\\F138B\"}.mdi-human-cane::before{content:\"\\F1581\"}.mdi-human-capacity-decrease::before{content:\"\\F159B\"}.mdi-human-capacity-increase::before{content:\"\\F159C\"}.mdi-human-child::before{content:\"\\F02E7\"}.mdi-human-edit::before{content:\"\\F14E8\"}.mdi-human-female::before{content:\"\\F0649\"}.mdi-human-female-boy::before{content:\"\\F0A59\"}.mdi-human-female-dance::before{content:\"\\F15C9\"}.mdi-human-female-female::before{content:\"\\F0A5A\"}.mdi-human-female-girl::before{content:\"\\F0A5B\"}.mdi-human-greeting::before{content:\"\\F064A\"}.mdi-human-greeting-proximity::before{content:\"\\F159D\"}.mdi-human-handsdown::before{content:\"\\F064B\"}.mdi-human-handsup::before{content:\"\\F064C\"}.mdi-human-male::before{content:\"\\F064D\"}.mdi-human-male-boy::before{content:\"\\F0A5C\"}.mdi-human-male-child::before{content:\"\\F138C\"}.mdi-human-male-female::before{content:\"\\F02E8\"}.mdi-human-male-girl::before{content:\"\\F0A5D\"}.mdi-human-male-height::before{content:\"\\F0EFB\"}.mdi-human-male-height-variant::before{content:\"\\F0EFC\"}.mdi-human-male-male::before{content:\"\\F0A5E\"}.mdi-human-pregnant::before{content:\"\\F05CF\"}.mdi-human-queue::before{content:\"\\F1571\"}.mdi-human-scooter::before{content:\"\\F11E9\"}.mdi-human-wheelchair::before{content:\"\\F138D\"}.mdi-humble-bundle::before{content:\"\\F0744\"}.mdi-hvac::before{content:\"\\F1352\"}.mdi-hvac-off::before{content:\"\\F159E\"}.mdi-hydraulic-oil-level::before{content:\"\\F1324\"}.mdi-hydraulic-oil-temperature::before{content:\"\\F1325\"}.mdi-hydro-power::before{content:\"\\F12E5\"}.mdi-ice-cream::before{content:\"\\F082A\"}.mdi-ice-cream-off::before{content:\"\\F0E52\"}.mdi-ice-pop::before{content:\"\\F0EFD\"}.mdi-id-card::before{content:\"\\F0FC0\"}.mdi-identifier::before{content:\"\\F0EFE\"}.mdi-ideogram-cjk::before{content:\"\\F1331\"}.mdi-ideogram-cjk-variant::before{content:\"\\F1332\"}.mdi-iframe::before{content:\"\\F0C8B\"}.mdi-iframe-array::before{content:\"\\F10F5\"}.mdi-iframe-array-outline::before{content:\"\\F10F6\"}.mdi-iframe-braces::before{content:\"\\F10F7\"}.mdi-iframe-braces-outline::before{content:\"\\F10F8\"}.mdi-iframe-outline::before{content:\"\\F0C8C\"}.mdi-iframe-parentheses::before{content:\"\\F10F9\"}.mdi-iframe-parentheses-outline::before{content:\"\\F10FA\"}.mdi-iframe-variable::before{content:\"\\F10FB\"}.mdi-iframe-variable-outline::before{content:\"\\F10FC\"}.mdi-image::before{content:\"\\F02E9\"}.mdi-image-album::before{content:\"\\F02EA\"}.mdi-image-area::before{content:\"\\F02EB\"}.mdi-image-area-close::before{content:\"\\F02EC\"}.mdi-image-auto-adjust::before{content:\"\\F0FC1\"}.mdi-image-broken::before{content:\"\\F02ED\"}.mdi-image-broken-variant::before{content:\"\\F02EE\"}.mdi-image-edit::before{content:\"\\F11E3\"}.mdi-image-edit-outline::before{content:\"\\F11E4\"}.mdi-image-filter-black-white::before{content:\"\\F02F0\"}.mdi-image-filter-center-focus::before{content:\"\\F02F1\"}.mdi-image-filter-center-focus-strong::before{content:\"\\F0EFF\"}.mdi-image-filter-center-focus-strong-outline::before{content:\"\\F0F00\"}.mdi-image-filter-center-focus-weak::before{content:\"\\F02F2\"}.mdi-image-filter-drama::before{content:\"\\F02F3\"}.mdi-image-filter-frames::before{content:\"\\F02F4\"}.mdi-image-filter-hdr::before{content:\"\\F02F5\"}.mdi-image-filter-none::before{content:\"\\F02F6\"}.mdi-image-filter-tilt-shift::before{content:\"\\F02F7\"}.mdi-image-filter-vintage::before{content:\"\\F02F8\"}.mdi-image-frame::before{content:\"\\F0E49\"}.mdi-image-minus::before{content:\"\\F1419\"}.mdi-image-move::before{content:\"\\F09F8\"}.mdi-image-multiple::before{content:\"\\F02F9\"}.mdi-image-multiple-outline::before{content:\"\\F02EF\"}.mdi-image-off::before{content:\"\\F082B\"}.mdi-image-off-outline::before{content:\"\\F11D1\"}.mdi-image-outline::before{content:\"\\F0976\"}.mdi-image-plus::before{content:\"\\F087C\"}.mdi-image-remove::before{content:\"\\F1418\"}.mdi-image-search::before{content:\"\\F0977\"}.mdi-image-search-outline::before{content:\"\\F0978\"}.mdi-image-size-select-actual::before{content:\"\\F0C8D\"}.mdi-image-size-select-large::before{content:\"\\F0C8E\"}.mdi-image-size-select-small::before{content:\"\\F0C8F\"}.mdi-image-text::before{content:\"\\F160D\"}.mdi-import::before{content:\"\\F02FA\"}.mdi-inbox::before{content:\"\\F0687\"}.mdi-inbox-arrow-down::before{content:\"\\F02FB\"}.mdi-inbox-arrow-down-outline::before{content:\"\\F1270\"}.mdi-inbox-arrow-up::before{content:\"\\F03D1\"}.mdi-inbox-arrow-up-outline::before{content:\"\\F1271\"}.mdi-inbox-full::before{content:\"\\F1272\"}.mdi-inbox-full-outline::before{content:\"\\F1273\"}.mdi-inbox-multiple::before{content:\"\\F08B0\"}.mdi-inbox-multiple-outline::before{content:\"\\F0BA8\"}.mdi-inbox-outline::before{content:\"\\F1274\"}.mdi-inbox-remove::before{content:\"\\F159F\"}.mdi-inbox-remove-outline::before{content:\"\\F15A0\"}.mdi-incognito::before{content:\"\\F05F9\"}.mdi-incognito-circle::before{content:\"\\F1421\"}.mdi-incognito-circle-off::before{content:\"\\F1422\"}.mdi-incognito-off::before{content:\"\\F0075\"}.mdi-infinity::before{content:\"\\F06E4\"}.mdi-information::before{content:\"\\F02FC\"}.mdi-information-outline::before{content:\"\\F02FD\"}.mdi-information-variant::before{content:\"\\F064E\"}.mdi-instagram::before{content:\"\\F02FE\"}.mdi-instrument-triangle::before{content:\"\\F104E\"}.mdi-invert-colors::before{content:\"\\F0301\"}.mdi-invert-colors-off::before{content:\"\\F0E4A\"}.mdi-iobroker::before{content:\"\\F12E8\"}.mdi-ip::before{content:\"\\F0A5F\"}.mdi-ip-network::before{content:\"\\F0A60\"}.mdi-ip-network-outline::before{content:\"\\F0C90\"}.mdi-ipod::before{content:\"\\F0C91\"}.mdi-islam::before{content:\"\\F0979\"}.mdi-island::before{content:\"\\F104F\"}.mdi-iv-bag::before{content:\"\\F10B9\"}.mdi-jabber::before{content:\"\\F0DD5\"}.mdi-jeepney::before{content:\"\\F0302\"}.mdi-jellyfish::before{content:\"\\F0F01\"}.mdi-jellyfish-outline::before{content:\"\\F0F02\"}.mdi-jira::before{content:\"\\F0303\"}.mdi-jquery::before{content:\"\\F087D\"}.mdi-jsfiddle::before{content:\"\\F0304\"}.mdi-judaism::before{content:\"\\F097A\"}.mdi-jump-rope::before{content:\"\\F12FF\"}.mdi-kabaddi::before{content:\"\\F0D87\"}.mdi-kangaroo::before{content:\"\\F1558\"}.mdi-karate::before{content:\"\\F082C\"}.mdi-keg::before{content:\"\\F0305\"}.mdi-kettle::before{content:\"\\F05FA\"}.mdi-kettle-alert::before{content:\"\\F1317\"}.mdi-kettle-alert-outline::before{content:\"\\F1318\"}.mdi-kettle-off::before{content:\"\\F131B\"}.mdi-kettle-off-outline::before{content:\"\\F131C\"}.mdi-kettle-outline::before{content:\"\\F0F56\"}.mdi-kettle-steam::before{content:\"\\F1319\"}.mdi-kettle-steam-outline::before{content:\"\\F131A\"}.mdi-kettlebell::before{content:\"\\F1300\"}.mdi-key::before{content:\"\\F0306\"}.mdi-key-arrow-right::before{content:\"\\F1312\"}.mdi-key-chain::before{content:\"\\F1574\"}.mdi-key-chain-variant::before{content:\"\\F1575\"}.mdi-key-change::before{content:\"\\F0307\"}.mdi-key-link::before{content:\"\\F119F\"}.mdi-key-minus::before{content:\"\\F0308\"}.mdi-key-outline::before{content:\"\\F0DD6\"}.mdi-key-plus::before{content:\"\\F0309\"}.mdi-key-remove::before{content:\"\\F030A\"}.mdi-key-star::before{content:\"\\F119E\"}.mdi-key-variant::before{content:\"\\F030B\"}.mdi-key-wireless::before{content:\"\\F0FC2\"}.mdi-keyboard::before{content:\"\\F030C\"}.mdi-keyboard-backspace::before{content:\"\\F030D\"}.mdi-keyboard-caps::before{content:\"\\F030E\"}.mdi-keyboard-close::before{content:\"\\F030F\"}.mdi-keyboard-esc::before{content:\"\\F12B7\"}.mdi-keyboard-f1::before{content:\"\\F12AB\"}.mdi-keyboard-f10::before{content:\"\\F12B4\"}.mdi-keyboard-f11::before{content:\"\\F12B5\"}.mdi-keyboard-f12::before{content:\"\\F12B6\"}.mdi-keyboard-f2::before{content:\"\\F12AC\"}.mdi-keyboard-f3::before{content:\"\\F12AD\"}.mdi-keyboard-f4::before{content:\"\\F12AE\"}.mdi-keyboard-f5::before{content:\"\\F12AF\"}.mdi-keyboard-f6::before{content:\"\\F12B0\"}.mdi-keyboard-f7::before{content:\"\\F12B1\"}.mdi-keyboard-f8::before{content:\"\\F12B2\"}.mdi-keyboard-f9::before{content:\"\\F12B3\"}.mdi-keyboard-off::before{content:\"\\F0310\"}.mdi-keyboard-off-outline::before{content:\"\\F0E4B\"}.mdi-keyboard-outline::before{content:\"\\F097B\"}.mdi-keyboard-return::before{content:\"\\F0311\"}.mdi-keyboard-settings::before{content:\"\\F09F9\"}.mdi-keyboard-settings-outline::before{content:\"\\F09FA\"}.mdi-keyboard-space::before{content:\"\\F1050\"}.mdi-keyboard-tab::before{content:\"\\F0312\"}.mdi-keyboard-variant::before{content:\"\\F0313\"}.mdi-khanda::before{content:\"\\F10FD\"}.mdi-kickstarter::before{content:\"\\F0745\"}.mdi-klingon::before{content:\"\\F135B\"}.mdi-knife::before{content:\"\\F09FB\"}.mdi-knife-military::before{content:\"\\F09FC\"}.mdi-kodi::before{content:\"\\F0314\"}.mdi-kubernetes::before{content:\"\\F10FE\"}.mdi-label::before{content:\"\\F0315\"}.mdi-label-multiple::before{content:\"\\F1375\"}.mdi-label-multiple-outline::before{content:\"\\F1376\"}.mdi-label-off::before{content:\"\\F0ACB\"}.mdi-label-off-outline::before{content:\"\\F0ACC\"}.mdi-label-outline::before{content:\"\\F0316\"}.mdi-label-percent::before{content:\"\\F12EA\"}.mdi-label-percent-outline::before{content:\"\\F12EB\"}.mdi-label-variant::before{content:\"\\F0ACD\"}.mdi-label-variant-outline::before{content:\"\\F0ACE\"}.mdi-ladder::before{content:\"\\F15A2\"}.mdi-ladybug::before{content:\"\\F082D\"}.mdi-lambda::before{content:\"\\F0627\"}.mdi-lamp::before{content:\"\\F06B5\"}.mdi-lamps::before{content:\"\\F1576\"}.mdi-lan::before{content:\"\\F0317\"}.mdi-lan-check::before{content:\"\\F12AA\"}.mdi-lan-connect::before{content:\"\\F0318\"}.mdi-lan-disconnect::before{content:\"\\F0319\"}.mdi-lan-pending::before{content:\"\\F031A\"}.mdi-language-c::before{content:\"\\F0671\"}.mdi-language-cpp::before{content:\"\\F0672\"}.mdi-language-csharp::before{content:\"\\F031B\"}.mdi-language-css3::before{content:\"\\F031C\"}.mdi-language-fortran::before{content:\"\\F121A\"}.mdi-language-go::before{content:\"\\F07D3\"}.mdi-language-haskell::before{content:\"\\F0C92\"}.mdi-language-html5::before{content:\"\\F031D\"}.mdi-language-java::before{content:\"\\F0B37\"}.mdi-language-javascript::before{content:\"\\F031E\"}.mdi-language-kotlin::before{content:\"\\F1219\"}.mdi-language-lua::before{content:\"\\F08B1\"}.mdi-language-markdown::before{content:\"\\F0354\"}.mdi-language-markdown-outline::before{content:\"\\F0F5B\"}.mdi-language-php::before{content:\"\\F031F\"}.mdi-language-python::before{content:\"\\F0320\"}.mdi-language-r::before{content:\"\\F07D4\"}.mdi-language-ruby::before{content:\"\\F0D2D\"}.mdi-language-ruby-on-rails::before{content:\"\\F0ACF\"}.mdi-language-rust::before{content:\"\\F1617\"}.mdi-language-swift::before{content:\"\\F06E5\"}.mdi-language-typescript::before{content:\"\\F06E6\"}.mdi-language-xaml::before{content:\"\\F0673\"}.mdi-laptop::before{content:\"\\F0322\"}.mdi-laptop-chromebook::before{content:\"\\F0323\"}.mdi-laptop-mac::before{content:\"\\F0324\"}.mdi-laptop-off::before{content:\"\\F06E7\"}.mdi-laptop-windows::before{content:\"\\F0325\"}.mdi-laravel::before{content:\"\\F0AD0\"}.mdi-laser-pointer::before{content:\"\\F1484\"}.mdi-lasso::before{content:\"\\F0F03\"}.mdi-lastpass::before{content:\"\\F0446\"}.mdi-latitude::before{content:\"\\F0F57\"}.mdi-launch::before{content:\"\\F0327\"}.mdi-lava-lamp::before{content:\"\\F07D5\"}.mdi-layers::before{content:\"\\F0328\"}.mdi-layers-minus::before{content:\"\\F0E4C\"}.mdi-layers-off::before{content:\"\\F0329\"}.mdi-layers-off-outline::before{content:\"\\F09FD\"}.mdi-layers-outline::before{content:\"\\F09FE\"}.mdi-layers-plus::before{content:\"\\F0E4D\"}.mdi-layers-remove::before{content:\"\\F0E4E\"}.mdi-layers-search::before{content:\"\\F1206\"}.mdi-layers-search-outline::before{content:\"\\F1207\"}.mdi-layers-triple::before{content:\"\\F0F58\"}.mdi-layers-triple-outline::before{content:\"\\F0F59\"}.mdi-lead-pencil::before{content:\"\\F064F\"}.mdi-leaf::before{content:\"\\F032A\"}.mdi-leaf-maple::before{content:\"\\F0C93\"}.mdi-leaf-maple-off::before{content:\"\\F12DA\"}.mdi-leaf-off::before{content:\"\\F12D9\"}.mdi-leak::before{content:\"\\F0DD7\"}.mdi-leak-off::before{content:\"\\F0DD8\"}.mdi-led-off::before{content:\"\\F032B\"}.mdi-led-on::before{content:\"\\F032C\"}.mdi-led-outline::before{content:\"\\F032D\"}.mdi-led-strip::before{content:\"\\F07D6\"}.mdi-led-strip-variant::before{content:\"\\F1051\"}.mdi-led-variant-off::before{content:\"\\F032E\"}.mdi-led-variant-on::before{content:\"\\F032F\"}.mdi-led-variant-outline::before{content:\"\\F0330\"}.mdi-leek::before{content:\"\\F117D\"}.mdi-less-than::before{content:\"\\F097C\"}.mdi-less-than-or-equal::before{content:\"\\F097D\"}.mdi-library::before{content:\"\\F0331\"}.mdi-library-shelves::before{content:\"\\F0BA9\"}.mdi-license::before{content:\"\\F0FC3\"}.mdi-lifebuoy::before{content:\"\\F087E\"}.mdi-light-switch::before{content:\"\\F097E\"}.mdi-lightbulb::before{content:\"\\F0335\"}.mdi-lightbulb-cfl::before{content:\"\\F1208\"}.mdi-lightbulb-cfl-off::before{content:\"\\F1209\"}.mdi-lightbulb-cfl-spiral::before{content:\"\\F1275\"}.mdi-lightbulb-cfl-spiral-off::before{content:\"\\F12C3\"}.mdi-lightbulb-group::before{content:\"\\F1253\"}.mdi-lightbulb-group-off::before{content:\"\\F12CD\"}.mdi-lightbulb-group-off-outline::before{content:\"\\F12CE\"}.mdi-lightbulb-group-outline::before{content:\"\\F1254\"}.mdi-lightbulb-multiple::before{content:\"\\F1255\"}.mdi-lightbulb-multiple-off::before{content:\"\\F12CF\"}.mdi-lightbulb-multiple-off-outline::before{content:\"\\F12D0\"}.mdi-lightbulb-multiple-outline::before{content:\"\\F1256\"}.mdi-lightbulb-off::before{content:\"\\F0E4F\"}.mdi-lightbulb-off-outline::before{content:\"\\F0E50\"}.mdi-lightbulb-on::before{content:\"\\F06E8\"}.mdi-lightbulb-on-outline::before{content:\"\\F06E9\"}.mdi-lightbulb-outline::before{content:\"\\F0336\"}.mdi-lighthouse::before{content:\"\\F09FF\"}.mdi-lighthouse-on::before{content:\"\\F0A00\"}.mdi-lightning-bolt::before{content:\"\\F140B\"}.mdi-lightning-bolt-outline::before{content:\"\\F140C\"}.mdi-lingerie::before{content:\"\\F1476\"}.mdi-link::before{content:\"\\F0337\"}.mdi-link-box::before{content:\"\\F0D1A\"}.mdi-link-box-outline::before{content:\"\\F0D1B\"}.mdi-link-box-variant::before{content:\"\\F0D1C\"}.mdi-link-box-variant-outline::before{content:\"\\F0D1D\"}.mdi-link-lock::before{content:\"\\F10BA\"}.mdi-link-off::before{content:\"\\F0338\"}.mdi-link-plus::before{content:\"\\F0C94\"}.mdi-link-variant::before{content:\"\\F0339\"}.mdi-link-variant-minus::before{content:\"\\F10FF\"}.mdi-link-variant-off::before{content:\"\\F033A\"}.mdi-link-variant-plus::before{content:\"\\F1100\"}.mdi-link-variant-remove::before{content:\"\\F1101\"}.mdi-linkedin::before{content:\"\\F033B\"}.mdi-linux::before{content:\"\\F033D\"}.mdi-linux-mint::before{content:\"\\F08ED\"}.mdi-lipstick::before{content:\"\\F13B5\"}.mdi-list-status::before{content:\"\\F15AB\"}.mdi-litecoin::before{content:\"\\F0A61\"}.mdi-loading::before{content:\"\\F0772\"}.mdi-location-enter::before{content:\"\\F0FC4\"}.mdi-location-exit::before{content:\"\\F0FC5\"}.mdi-lock::before{content:\"\\F033E\"}.mdi-lock-alert::before{content:\"\\F08EE\"}.mdi-lock-alert-outline::before{content:\"\\F15D1\"}.mdi-lock-check::before{content:\"\\F139A\"}.mdi-lock-clock::before{content:\"\\F097F\"}.mdi-lock-off::before{content:\"\\F1671\"}.mdi-lock-off-outline::before{content:\"\\F1672\"}.mdi-lock-open::before{content:\"\\F033F\"}.mdi-lock-open-alert::before{content:\"\\F139B\"}.mdi-lock-open-alert-outline::before{content:\"\\F15D2\"}.mdi-lock-open-check::before{content:\"\\F139C\"}.mdi-lock-open-outline::before{content:\"\\F0340\"}.mdi-lock-open-variant::before{content:\"\\F0FC6\"}.mdi-lock-open-variant-outline::before{content:\"\\F0FC7\"}.mdi-lock-outline::before{content:\"\\F0341\"}.mdi-lock-pattern::before{content:\"\\F06EA\"}.mdi-lock-plus::before{content:\"\\F05FB\"}.mdi-lock-question::before{content:\"\\F08EF\"}.mdi-lock-reset::before{content:\"\\F0773\"}.mdi-lock-smart::before{content:\"\\F08B2\"}.mdi-locker::before{content:\"\\F07D7\"}.mdi-locker-multiple::before{content:\"\\F07D8\"}.mdi-login::before{content:\"\\F0342\"}.mdi-login-variant::before{content:\"\\F05FC\"}.mdi-logout::before{content:\"\\F0343\"}.mdi-logout-variant::before{content:\"\\F05FD\"}.mdi-longitude::before{content:\"\\F0F5A\"}.mdi-looks::before{content:\"\\F0344\"}.mdi-lotion::before{content:\"\\F1582\"}.mdi-lotion-outline::before{content:\"\\F1583\"}.mdi-lotion-plus::before{content:\"\\F1584\"}.mdi-lotion-plus-outline::before{content:\"\\F1585\"}.mdi-loupe::before{content:\"\\F0345\"}.mdi-lumx::before{content:\"\\F0346\"}.mdi-lungs::before{content:\"\\F1084\"}.mdi-magnet::before{content:\"\\F0347\"}.mdi-magnet-on::before{content:\"\\F0348\"}.mdi-magnify::before{content:\"\\F0349\"}.mdi-magnify-close::before{content:\"\\F0980\"}.mdi-magnify-minus::before{content:\"\\F034A\"}.mdi-magnify-minus-cursor::before{content:\"\\F0A62\"}.mdi-magnify-minus-outline::before{content:\"\\F06EC\"}.mdi-magnify-plus::before{content:\"\\F034B\"}.mdi-magnify-plus-cursor::before{content:\"\\F0A63\"}.mdi-magnify-plus-outline::before{content:\"\\F06ED\"}.mdi-magnify-remove-cursor::before{content:\"\\F120C\"}.mdi-magnify-remove-outline::before{content:\"\\F120D\"}.mdi-magnify-scan::before{content:\"\\F1276\"}.mdi-mail::before{content:\"\\F0EBB\"}.mdi-mailbox::before{content:\"\\F06EE\"}.mdi-mailbox-open::before{content:\"\\F0D88\"}.mdi-mailbox-open-outline::before{content:\"\\F0D89\"}.mdi-mailbox-open-up::before{content:\"\\F0D8A\"}.mdi-mailbox-open-up-outline::before{content:\"\\F0D8B\"}.mdi-mailbox-outline::before{content:\"\\F0D8C\"}.mdi-mailbox-up::before{content:\"\\F0D8D\"}.mdi-mailbox-up-outline::before{content:\"\\F0D8E\"}.mdi-manjaro::before{content:\"\\F160A\"}.mdi-map::before{content:\"\\F034D\"}.mdi-map-check::before{content:\"\\F0EBC\"}.mdi-map-check-outline::before{content:\"\\F0EBD\"}.mdi-map-clock::before{content:\"\\F0D1E\"}.mdi-map-clock-outline::before{content:\"\\F0D1F\"}.mdi-map-legend::before{content:\"\\F0A01\"}.mdi-map-marker::before{content:\"\\F034E\"}.mdi-map-marker-alert::before{content:\"\\F0F05\"}.mdi-map-marker-alert-outline::before{content:\"\\F0F06\"}.mdi-map-marker-check::before{content:\"\\F0C95\"}.mdi-map-marker-check-outline::before{content:\"\\F12FB\"}.mdi-map-marker-circle::before{content:\"\\F034F\"}.mdi-map-marker-distance::before{content:\"\\F08F0\"}.mdi-map-marker-down::before{content:\"\\F1102\"}.mdi-map-marker-left::before{content:\"\\F12DB\"}.mdi-map-marker-left-outline::before{content:\"\\F12DD\"}.mdi-map-marker-minus::before{content:\"\\F0650\"}.mdi-map-marker-minus-outline::before{content:\"\\F12F9\"}.mdi-map-marker-multiple::before{content:\"\\F0350\"}.mdi-map-marker-multiple-outline::before{content:\"\\F1277\"}.mdi-map-marker-off::before{content:\"\\F0351\"}.mdi-map-marker-off-outline::before{content:\"\\F12FD\"}.mdi-map-marker-outline::before{content:\"\\F07D9\"}.mdi-map-marker-path::before{content:\"\\F0D20\"}.mdi-map-marker-plus::before{content:\"\\F0651\"}.mdi-map-marker-plus-outline::before{content:\"\\F12F8\"}.mdi-map-marker-question::before{content:\"\\F0F07\"}.mdi-map-marker-question-outline::before{content:\"\\F0F08\"}.mdi-map-marker-radius::before{content:\"\\F0352\"}.mdi-map-marker-radius-outline::before{content:\"\\F12FC\"}.mdi-map-marker-remove::before{content:\"\\F0F09\"}.mdi-map-marker-remove-outline::before{content:\"\\F12FA\"}.mdi-map-marker-remove-variant::before{content:\"\\F0F0A\"}.mdi-map-marker-right::before{content:\"\\F12DC\"}.mdi-map-marker-right-outline::before{content:\"\\F12DE\"}.mdi-map-marker-star::before{content:\"\\F1608\"}.mdi-map-marker-star-outline::before{content:\"\\F1609\"}.mdi-map-marker-up::before{content:\"\\F1103\"}.mdi-map-minus::before{content:\"\\F0981\"}.mdi-map-outline::before{content:\"\\F0982\"}.mdi-map-plus::before{content:\"\\F0983\"}.mdi-map-search::before{content:\"\\F0984\"}.mdi-map-search-outline::before{content:\"\\F0985\"}.mdi-mapbox::before{content:\"\\F0BAA\"}.mdi-margin::before{content:\"\\F0353\"}.mdi-marker::before{content:\"\\F0652\"}.mdi-marker-cancel::before{content:\"\\F0DD9\"}.mdi-marker-check::before{content:\"\\F0355\"}.mdi-mastodon::before{content:\"\\F0AD1\"}.mdi-material-design::before{content:\"\\F0986\"}.mdi-material-ui::before{content:\"\\F0357\"}.mdi-math-compass::before{content:\"\\F0358\"}.mdi-math-cos::before{content:\"\\F0C96\"}.mdi-math-integral::before{content:\"\\F0FC8\"}.mdi-math-integral-box::before{content:\"\\F0FC9\"}.mdi-math-log::before{content:\"\\F1085\"}.mdi-math-norm::before{content:\"\\F0FCA\"}.mdi-math-norm-box::before{content:\"\\F0FCB\"}.mdi-math-sin::before{content:\"\\F0C97\"}.mdi-math-tan::before{content:\"\\F0C98\"}.mdi-matrix::before{content:\"\\F0628\"}.mdi-medal::before{content:\"\\F0987\"}.mdi-medal-outline::before{content:\"\\F1326\"}.mdi-medical-bag::before{content:\"\\F06EF\"}.mdi-meditation::before{content:\"\\F117B\"}.mdi-memory::before{content:\"\\F035B\"}.mdi-menu::before{content:\"\\F035C\"}.mdi-menu-down::before{content:\"\\F035D\"}.mdi-menu-down-outline::before{content:\"\\F06B6\"}.mdi-menu-left::before{content:\"\\F035E\"}.mdi-menu-left-outline::before{content:\"\\F0A02\"}.mdi-menu-open::before{content:\"\\F0BAB\"}.mdi-menu-right::before{content:\"\\F035F\"}.mdi-menu-right-outline::before{content:\"\\F0A03\"}.mdi-menu-swap::before{content:\"\\F0A64\"}.mdi-menu-swap-outline::before{content:\"\\F0A65\"}.mdi-menu-up::before{content:\"\\F0360\"}.mdi-menu-up-outline::before{content:\"\\F06B7\"}.mdi-merge::before{content:\"\\F0F5C\"}.mdi-message::before{content:\"\\F0361\"}.mdi-message-alert::before{content:\"\\F0362\"}.mdi-message-alert-outline::before{content:\"\\F0A04\"}.mdi-message-arrow-left::before{content:\"\\F12F2\"}.mdi-message-arrow-left-outline::before{content:\"\\F12F3\"}.mdi-message-arrow-right::before{content:\"\\F12F4\"}.mdi-message-arrow-right-outline::before{content:\"\\F12F5\"}.mdi-message-bookmark::before{content:\"\\F15AC\"}.mdi-message-bookmark-outline::before{content:\"\\F15AD\"}.mdi-message-bulleted::before{content:\"\\F06A2\"}.mdi-message-bulleted-off::before{content:\"\\F06A3\"}.mdi-message-cog::before{content:\"\\F06F1\"}.mdi-message-cog-outline::before{content:\"\\F1172\"}.mdi-message-draw::before{content:\"\\F0363\"}.mdi-message-flash::before{content:\"\\F15A9\"}.mdi-message-flash-outline::before{content:\"\\F15AA\"}.mdi-message-image::before{content:\"\\F0364\"}.mdi-message-image-outline::before{content:\"\\F116C\"}.mdi-message-lock::before{content:\"\\F0FCC\"}.mdi-message-lock-outline::before{content:\"\\F116D\"}.mdi-message-minus::before{content:\"\\F116E\"}.mdi-message-minus-outline::before{content:\"\\F116F\"}.mdi-message-off::before{content:\"\\F164D\"}.mdi-message-off-outline::before{content:\"\\F164E\"}.mdi-message-outline::before{content:\"\\F0365\"}.mdi-message-plus::before{content:\"\\F0653\"}.mdi-message-plus-outline::before{content:\"\\F10BB\"}.mdi-message-processing::before{content:\"\\F0366\"}.mdi-message-processing-outline::before{content:\"\\F1170\"}.mdi-message-reply::before{content:\"\\F0367\"}.mdi-message-reply-text::before{content:\"\\F0368\"}.mdi-message-settings::before{content:\"\\F06F0\"}.mdi-message-settings-outline::before{content:\"\\F1171\"}.mdi-message-text::before{content:\"\\F0369\"}.mdi-message-text-clock::before{content:\"\\F1173\"}.mdi-message-text-clock-outline::before{content:\"\\F1174\"}.mdi-message-text-lock::before{content:\"\\F0FCD\"}.mdi-message-text-lock-outline::before{content:\"\\F1175\"}.mdi-message-text-outline::before{content:\"\\F036A\"}.mdi-message-video::before{content:\"\\F036B\"}.mdi-meteor::before{content:\"\\F0629\"}.mdi-metronome::before{content:\"\\F07DA\"}.mdi-metronome-tick::before{content:\"\\F07DB\"}.mdi-micro-sd::before{content:\"\\F07DC\"}.mdi-microphone::before{content:\"\\F036C\"}.mdi-microphone-minus::before{content:\"\\F08B3\"}.mdi-microphone-off::before{content:\"\\F036D\"}.mdi-microphone-outline::before{content:\"\\F036E\"}.mdi-microphone-plus::before{content:\"\\F08B4\"}.mdi-microphone-settings::before{content:\"\\F036F\"}.mdi-microphone-variant::before{content:\"\\F0370\"}.mdi-microphone-variant-off::before{content:\"\\F0371\"}.mdi-microscope::before{content:\"\\F0654\"}.mdi-microsoft::before{content:\"\\F0372\"}.mdi-microsoft-access::before{content:\"\\F138E\"}.mdi-microsoft-azure::before{content:\"\\F0805\"}.mdi-microsoft-azure-devops::before{content:\"\\F0FD5\"}.mdi-microsoft-bing::before{content:\"\\F00A4\"}.mdi-microsoft-dynamics-365::before{content:\"\\F0988\"}.mdi-microsoft-edge::before{content:\"\\F01E9\"}.mdi-microsoft-edge-legacy::before{content:\"\\F1250\"}.mdi-microsoft-excel::before{content:\"\\F138F\"}.mdi-microsoft-internet-explorer::before{content:\"\\F0300\"}.mdi-microsoft-office::before{content:\"\\F03C6\"}.mdi-microsoft-onedrive::before{content:\"\\F03CA\"}.mdi-microsoft-onenote::before{content:\"\\F0747\"}.mdi-microsoft-outlook::before{content:\"\\F0D22\"}.mdi-microsoft-powerpoint::before{content:\"\\F1390\"}.mdi-microsoft-sharepoint::before{content:\"\\F1391\"}.mdi-microsoft-teams::before{content:\"\\F02BB\"}.mdi-microsoft-visual-studio::before{content:\"\\F0610\"}.mdi-microsoft-visual-studio-code::before{content:\"\\F0A1E\"}.mdi-microsoft-windows::before{content:\"\\F05B3\"}.mdi-microsoft-windows-classic::before{content:\"\\F0A21\"}.mdi-microsoft-word::before{content:\"\\F1392\"}.mdi-microsoft-xbox::before{content:\"\\F05B9\"}.mdi-microsoft-xbox-controller::before{content:\"\\F05BA\"}.mdi-microsoft-xbox-controller-battery-alert::before{content:\"\\F074B\"}.mdi-microsoft-xbox-controller-battery-charging::before{content:\"\\F0A22\"}.mdi-microsoft-xbox-controller-battery-empty::before{content:\"\\F074C\"}.mdi-microsoft-xbox-controller-battery-full::before{content:\"\\F074D\"}.mdi-microsoft-xbox-controller-battery-low::before{content:\"\\F074E\"}.mdi-microsoft-xbox-controller-battery-medium::before{content:\"\\F074F\"}.mdi-microsoft-xbox-controller-battery-unknown::before{content:\"\\F0750\"}.mdi-microsoft-xbox-controller-menu::before{content:\"\\F0E6F\"}.mdi-microsoft-xbox-controller-off::before{content:\"\\F05BB\"}.mdi-microsoft-xbox-controller-view::before{content:\"\\F0E70\"}.mdi-microsoft-yammer::before{content:\"\\F0789\"}.mdi-microwave::before{content:\"\\F0C99\"}.mdi-microwave-off::before{content:\"\\F1423\"}.mdi-middleware::before{content:\"\\F0F5D\"}.mdi-middleware-outline::before{content:\"\\F0F5E\"}.mdi-midi::before{content:\"\\F08F1\"}.mdi-midi-port::before{content:\"\\F08F2\"}.mdi-mine::before{content:\"\\F0DDA\"}.mdi-minecraft::before{content:\"\\F0373\"}.mdi-mini-sd::before{content:\"\\F0A05\"}.mdi-minidisc::before{content:\"\\F0A06\"}.mdi-minus::before{content:\"\\F0374\"}.mdi-minus-box::before{content:\"\\F0375\"}.mdi-minus-box-multiple::before{content:\"\\F1141\"}.mdi-minus-box-multiple-outline::before{content:\"\\F1142\"}.mdi-minus-box-outline::before{content:\"\\F06F2\"}.mdi-minus-circle::before{content:\"\\F0376\"}.mdi-minus-circle-multiple::before{content:\"\\F035A\"}.mdi-minus-circle-multiple-outline::before{content:\"\\F0AD3\"}.mdi-minus-circle-off::before{content:\"\\F1459\"}.mdi-minus-circle-off-outline::before{content:\"\\F145A\"}.mdi-minus-circle-outline::before{content:\"\\F0377\"}.mdi-minus-network::before{content:\"\\F0378\"}.mdi-minus-network-outline::before{content:\"\\F0C9A\"}.mdi-minus-thick::before{content:\"\\F1639\"}.mdi-mirror::before{content:\"\\F11FD\"}.mdi-mixed-martial-arts::before{content:\"\\F0D8F\"}.mdi-mixed-reality::before{content:\"\\F087F\"}.mdi-molecule::before{content:\"\\F0BAC\"}.mdi-molecule-co::before{content:\"\\F12FE\"}.mdi-molecule-co2::before{content:\"\\F07E4\"}.mdi-monitor::before{content:\"\\F0379\"}.mdi-monitor-cellphone::before{content:\"\\F0989\"}.mdi-monitor-cellphone-star::before{content:\"\\F098A\"}.mdi-monitor-clean::before{content:\"\\F1104\"}.mdi-monitor-dashboard::before{content:\"\\F0A07\"}.mdi-monitor-edit::before{content:\"\\F12C6\"}.mdi-monitor-eye::before{content:\"\\F13B4\"}.mdi-monitor-lock::before{content:\"\\F0DDB\"}.mdi-monitor-multiple::before{content:\"\\F037A\"}.mdi-monitor-off::before{content:\"\\F0D90\"}.mdi-monitor-screenshot::before{content:\"\\F0E51\"}.mdi-monitor-share::before{content:\"\\F1483\"}.mdi-monitor-speaker::before{content:\"\\F0F5F\"}.mdi-monitor-speaker-off::before{content:\"\\F0F60\"}.mdi-monitor-star::before{content:\"\\F0DDC\"}.mdi-moon-first-quarter::before{content:\"\\F0F61\"}.mdi-moon-full::before{content:\"\\F0F62\"}.mdi-moon-last-quarter::before{content:\"\\F0F63\"}.mdi-moon-new::before{content:\"\\F0F64\"}.mdi-moon-waning-crescent::before{content:\"\\F0F65\"}.mdi-moon-waning-gibbous::before{content:\"\\F0F66\"}.mdi-moon-waxing-crescent::before{content:\"\\F0F67\"}.mdi-moon-waxing-gibbous::before{content:\"\\F0F68\"}.mdi-moped::before{content:\"\\F1086\"}.mdi-moped-electric::before{content:\"\\F15B7\"}.mdi-moped-electric-outline::before{content:\"\\F15B8\"}.mdi-moped-outline::before{content:\"\\F15B9\"}.mdi-more::before{content:\"\\F037B\"}.mdi-mother-heart::before{content:\"\\F1314\"}.mdi-mother-nurse::before{content:\"\\F0D21\"}.mdi-motion::before{content:\"\\F15B2\"}.mdi-motion-outline::before{content:\"\\F15B3\"}.mdi-motion-pause::before{content:\"\\F1590\"}.mdi-motion-pause-outline::before{content:\"\\F1592\"}.mdi-motion-play::before{content:\"\\F158F\"}.mdi-motion-play-outline::before{content:\"\\F1591\"}.mdi-motion-sensor::before{content:\"\\F0D91\"}.mdi-motion-sensor-off::before{content:\"\\F1435\"}.mdi-motorbike::before{content:\"\\F037C\"}.mdi-motorbike-electric::before{content:\"\\F15BA\"}.mdi-mouse::before{content:\"\\F037D\"}.mdi-mouse-bluetooth::before{content:\"\\F098B\"}.mdi-mouse-move-down::before{content:\"\\F1550\"}.mdi-mouse-move-up::before{content:\"\\F1551\"}.mdi-mouse-move-vertical::before{content:\"\\F1552\"}.mdi-mouse-off::before{content:\"\\F037E\"}.mdi-mouse-variant::before{content:\"\\F037F\"}.mdi-mouse-variant-off::before{content:\"\\F0380\"}.mdi-move-resize::before{content:\"\\F0655\"}.mdi-move-resize-variant::before{content:\"\\F0656\"}.mdi-movie::before{content:\"\\F0381\"}.mdi-movie-edit::before{content:\"\\F1122\"}.mdi-movie-edit-outline::before{content:\"\\F1123\"}.mdi-movie-filter::before{content:\"\\F1124\"}.mdi-movie-filter-outline::before{content:\"\\F1125\"}.mdi-movie-open::before{content:\"\\F0FCE\"}.mdi-movie-open-outline::before{content:\"\\F0FCF\"}.mdi-movie-outline::before{content:\"\\F0DDD\"}.mdi-movie-roll::before{content:\"\\F07DE\"}.mdi-movie-search::before{content:\"\\F11D2\"}.mdi-movie-search-outline::before{content:\"\\F11D3\"}.mdi-mower::before{content:\"\\F166F\"}.mdi-mower-bag::before{content:\"\\F1670\"}.mdi-muffin::before{content:\"\\F098C\"}.mdi-multiplication::before{content:\"\\F0382\"}.mdi-multiplication-box::before{content:\"\\F0383\"}.mdi-mushroom::before{content:\"\\F07DF\"}.mdi-mushroom-off::before{content:\"\\F13FA\"}.mdi-mushroom-off-outline::before{content:\"\\F13FB\"}.mdi-mushroom-outline::before{content:\"\\F07E0\"}.mdi-music::before{content:\"\\F075A\"}.mdi-music-accidental-double-flat::before{content:\"\\F0F69\"}.mdi-music-accidental-double-sharp::before{content:\"\\F0F6A\"}.mdi-music-accidental-flat::before{content:\"\\F0F6B\"}.mdi-music-accidental-natural::before{content:\"\\F0F6C\"}.mdi-music-accidental-sharp::before{content:\"\\F0F6D\"}.mdi-music-box::before{content:\"\\F0384\"}.mdi-music-box-multiple::before{content:\"\\F0333\"}.mdi-music-box-multiple-outline::before{content:\"\\F0F04\"}.mdi-music-box-outline::before{content:\"\\F0385\"}.mdi-music-circle::before{content:\"\\F0386\"}.mdi-music-circle-outline::before{content:\"\\F0AD4\"}.mdi-music-clef-alto::before{content:\"\\F0F6E\"}.mdi-music-clef-bass::before{content:\"\\F0F6F\"}.mdi-music-clef-treble::before{content:\"\\F0F70\"}.mdi-music-note::before{content:\"\\F0387\"}.mdi-music-note-bluetooth::before{content:\"\\F05FE\"}.mdi-music-note-bluetooth-off::before{content:\"\\F05FF\"}.mdi-music-note-eighth::before{content:\"\\F0388\"}.mdi-music-note-eighth-dotted::before{content:\"\\F0F71\"}.mdi-music-note-half::before{content:\"\\F0389\"}.mdi-music-note-half-dotted::before{content:\"\\F0F72\"}.mdi-music-note-off::before{content:\"\\F038A\"}.mdi-music-note-off-outline::before{content:\"\\F0F73\"}.mdi-music-note-outline::before{content:\"\\F0F74\"}.mdi-music-note-plus::before{content:\"\\F0DDE\"}.mdi-music-note-quarter::before{content:\"\\F038B\"}.mdi-music-note-quarter-dotted::before{content:\"\\F0F75\"}.mdi-music-note-sixteenth::before{content:\"\\F038C\"}.mdi-music-note-sixteenth-dotted::before{content:\"\\F0F76\"}.mdi-music-note-whole::before{content:\"\\F038D\"}.mdi-music-note-whole-dotted::before{content:\"\\F0F77\"}.mdi-music-off::before{content:\"\\F075B\"}.mdi-music-rest-eighth::before{content:\"\\F0F78\"}.mdi-music-rest-half::before{content:\"\\F0F79\"}.mdi-music-rest-quarter::before{content:\"\\F0F7A\"}.mdi-music-rest-sixteenth::before{content:\"\\F0F7B\"}.mdi-music-rest-whole::before{content:\"\\F0F7C\"}.mdi-mustache::before{content:\"\\F15DE\"}.mdi-nail::before{content:\"\\F0DDF\"}.mdi-nas::before{content:\"\\F08F3\"}.mdi-nativescript::before{content:\"\\F0880\"}.mdi-nature::before{content:\"\\F038E\"}.mdi-nature-people::before{content:\"\\F038F\"}.mdi-navigation::before{content:\"\\F0390\"}.mdi-navigation-outline::before{content:\"\\F1607\"}.mdi-near-me::before{content:\"\\F05CD\"}.mdi-necklace::before{content:\"\\F0F0B\"}.mdi-needle::before{content:\"\\F0391\"}.mdi-netflix::before{content:\"\\F0746\"}.mdi-network::before{content:\"\\F06F3\"}.mdi-network-off::before{content:\"\\F0C9B\"}.mdi-network-off-outline::before{content:\"\\F0C9C\"}.mdi-network-outline::before{content:\"\\F0C9D\"}.mdi-network-strength-1::before{content:\"\\F08F4\"}.mdi-network-strength-1-alert::before{content:\"\\F08F5\"}.mdi-network-strength-2::before{content:\"\\F08F6\"}.mdi-network-strength-2-alert::before{content:\"\\F08F7\"}.mdi-network-strength-3::before{content:\"\\F08F8\"}.mdi-network-strength-3-alert::before{content:\"\\F08F9\"}.mdi-network-strength-4::before{content:\"\\F08FA\"}.mdi-network-strength-4-alert::before{content:\"\\F08FB\"}.mdi-network-strength-off::before{content:\"\\F08FC\"}.mdi-network-strength-off-outline::before{content:\"\\F08FD\"}.mdi-network-strength-outline::before{content:\"\\F08FE\"}.mdi-new-box::before{content:\"\\F0394\"}.mdi-newspaper::before{content:\"\\F0395\"}.mdi-newspaper-minus::before{content:\"\\F0F0C\"}.mdi-newspaper-plus::before{content:\"\\F0F0D\"}.mdi-newspaper-variant::before{content:\"\\F1001\"}.mdi-newspaper-variant-multiple::before{content:\"\\F1002\"}.mdi-newspaper-variant-multiple-outline::before{content:\"\\F1003\"}.mdi-newspaper-variant-outline::before{content:\"\\F1004\"}.mdi-nfc::before{content:\"\\F0396\"}.mdi-nfc-search-variant::before{content:\"\\F0E53\"}.mdi-nfc-tap::before{content:\"\\F0397\"}.mdi-nfc-variant::before{content:\"\\F0398\"}.mdi-nfc-variant-off::before{content:\"\\F0E54\"}.mdi-ninja::before{content:\"\\F0774\"}.mdi-nintendo-game-boy::before{content:\"\\F1393\"}.mdi-nintendo-switch::before{content:\"\\F07E1\"}.mdi-nintendo-wii::before{content:\"\\F05AB\"}.mdi-nintendo-wiiu::before{content:\"\\F072D\"}.mdi-nix::before{content:\"\\F1105\"}.mdi-nodejs::before{content:\"\\F0399\"}.mdi-noodles::before{content:\"\\F117E\"}.mdi-not-equal::before{content:\"\\F098D\"}.mdi-not-equal-variant::before{content:\"\\F098E\"}.mdi-note::before{content:\"\\F039A\"}.mdi-note-minus::before{content:\"\\F164F\"}.mdi-note-minus-outline::before{content:\"\\F1650\"}.mdi-note-multiple::before{content:\"\\F06B8\"}.mdi-note-multiple-outline::before{content:\"\\F06B9\"}.mdi-note-outline::before{content:\"\\F039B\"}.mdi-note-plus::before{content:\"\\F039C\"}.mdi-note-plus-outline::before{content:\"\\F039D\"}.mdi-note-remove::before{content:\"\\F1651\"}.mdi-note-remove-outline::before{content:\"\\F1652\"}.mdi-note-search::before{content:\"\\F1653\"}.mdi-note-search-outline::before{content:\"\\F1654\"}.mdi-note-text::before{content:\"\\F039E\"}.mdi-note-text-outline::before{content:\"\\F11D7\"}.mdi-notebook::before{content:\"\\F082E\"}.mdi-notebook-check::before{content:\"\\F14F5\"}.mdi-notebook-check-outline::before{content:\"\\F14F6\"}.mdi-notebook-edit::before{content:\"\\F14E7\"}.mdi-notebook-edit-outline::before{content:\"\\F14E9\"}.mdi-notebook-minus::before{content:\"\\F1610\"}.mdi-notebook-minus-outline::before{content:\"\\F1611\"}.mdi-notebook-multiple::before{content:\"\\F0E55\"}.mdi-notebook-outline::before{content:\"\\F0EBF\"}.mdi-notebook-plus::before{content:\"\\F1612\"}.mdi-notebook-plus-outline::before{content:\"\\F1613\"}.mdi-notebook-remove::before{content:\"\\F1614\"}.mdi-notebook-remove-outline::before{content:\"\\F1615\"}.mdi-notification-clear-all::before{content:\"\\F039F\"}.mdi-npm::before{content:\"\\F06F7\"}.mdi-nuke::before{content:\"\\F06A4\"}.mdi-null::before{content:\"\\F07E2\"}.mdi-numeric::before{content:\"\\F03A0\"}.mdi-numeric-0::before{content:\"\\F0B39\"}.mdi-numeric-0-box::before{content:\"\\F03A1\"}.mdi-numeric-0-box-multiple::before{content:\"\\F0F0E\"}.mdi-numeric-0-box-multiple-outline::before{content:\"\\F03A2\"}.mdi-numeric-0-box-outline::before{content:\"\\F03A3\"}.mdi-numeric-0-circle::before{content:\"\\F0C9E\"}.mdi-numeric-0-circle-outline::before{content:\"\\F0C9F\"}.mdi-numeric-1::before{content:\"\\F0B3A\"}.mdi-numeric-1-box::before{content:\"\\F03A4\"}.mdi-numeric-1-box-multiple::before{content:\"\\F0F0F\"}.mdi-numeric-1-box-multiple-outline::before{content:\"\\F03A5\"}.mdi-numeric-1-box-outline::before{content:\"\\F03A6\"}.mdi-numeric-1-circle::before{content:\"\\F0CA0\"}.mdi-numeric-1-circle-outline::before{content:\"\\F0CA1\"}.mdi-numeric-10::before{content:\"\\F0FE9\"}.mdi-numeric-10-box::before{content:\"\\F0F7D\"}.mdi-numeric-10-box-multiple::before{content:\"\\F0FEA\"}.mdi-numeric-10-box-multiple-outline::before{content:\"\\F0FEB\"}.mdi-numeric-10-box-outline::before{content:\"\\F0F7E\"}.mdi-numeric-10-circle::before{content:\"\\F0FEC\"}.mdi-numeric-10-circle-outline::before{content:\"\\F0FED\"}.mdi-numeric-2::before{content:\"\\F0B3B\"}.mdi-numeric-2-box::before{content:\"\\F03A7\"}.mdi-numeric-2-box-multiple::before{content:\"\\F0F10\"}.mdi-numeric-2-box-multiple-outline::before{content:\"\\F03A8\"}.mdi-numeric-2-box-outline::before{content:\"\\F03A9\"}.mdi-numeric-2-circle::before{content:\"\\F0CA2\"}.mdi-numeric-2-circle-outline::before{content:\"\\F0CA3\"}.mdi-numeric-3::before{content:\"\\F0B3C\"}.mdi-numeric-3-box::before{content:\"\\F03AA\"}.mdi-numeric-3-box-multiple::before{content:\"\\F0F11\"}.mdi-numeric-3-box-multiple-outline::before{content:\"\\F03AB\"}.mdi-numeric-3-box-outline::before{content:\"\\F03AC\"}.mdi-numeric-3-circle::before{content:\"\\F0CA4\"}.mdi-numeric-3-circle-outline::before{content:\"\\F0CA5\"}.mdi-numeric-4::before{content:\"\\F0B3D\"}.mdi-numeric-4-box::before{content:\"\\F03AD\"}.mdi-numeric-4-box-multiple::before{content:\"\\F0F12\"}.mdi-numeric-4-box-multiple-outline::before{content:\"\\F03B2\"}.mdi-numeric-4-box-outline::before{content:\"\\F03AE\"}.mdi-numeric-4-circle::before{content:\"\\F0CA6\"}.mdi-numeric-4-circle-outline::before{content:\"\\F0CA7\"}.mdi-numeric-5::before{content:\"\\F0B3E\"}.mdi-numeric-5-box::before{content:\"\\F03B1\"}.mdi-numeric-5-box-multiple::before{content:\"\\F0F13\"}.mdi-numeric-5-box-multiple-outline::before{content:\"\\F03AF\"}.mdi-numeric-5-box-outline::before{content:\"\\F03B0\"}.mdi-numeric-5-circle::before{content:\"\\F0CA8\"}.mdi-numeric-5-circle-outline::before{content:\"\\F0CA9\"}.mdi-numeric-6::before{content:\"\\F0B3F\"}.mdi-numeric-6-box::before{content:\"\\F03B3\"}.mdi-numeric-6-box-multiple::before{content:\"\\F0F14\"}.mdi-numeric-6-box-multiple-outline::before{content:\"\\F03B4\"}.mdi-numeric-6-box-outline::before{content:\"\\F03B5\"}.mdi-numeric-6-circle::before{content:\"\\F0CAA\"}.mdi-numeric-6-circle-outline::before{content:\"\\F0CAB\"}.mdi-numeric-7::before{content:\"\\F0B40\"}.mdi-numeric-7-box::before{content:\"\\F03B6\"}.mdi-numeric-7-box-multiple::before{content:\"\\F0F15\"}.mdi-numeric-7-box-multiple-outline::before{content:\"\\F03B7\"}.mdi-numeric-7-box-outline::before{content:\"\\F03B8\"}.mdi-numeric-7-circle::before{content:\"\\F0CAC\"}.mdi-numeric-7-circle-outline::before{content:\"\\F0CAD\"}.mdi-numeric-8::before{content:\"\\F0B41\"}.mdi-numeric-8-box::before{content:\"\\F03B9\"}.mdi-numeric-8-box-multiple::before{content:\"\\F0F16\"}.mdi-numeric-8-box-multiple-outline::before{content:\"\\F03BA\"}.mdi-numeric-8-box-outline::before{content:\"\\F03BB\"}.mdi-numeric-8-circle::before{content:\"\\F0CAE\"}.mdi-numeric-8-circle-outline::before{content:\"\\F0CAF\"}.mdi-numeric-9::before{content:\"\\F0B42\"}.mdi-numeric-9-box::before{content:\"\\F03BC\"}.mdi-numeric-9-box-multiple::before{content:\"\\F0F17\"}.mdi-numeric-9-box-multiple-outline::before{content:\"\\F03BD\"}.mdi-numeric-9-box-outline::before{content:\"\\F03BE\"}.mdi-numeric-9-circle::before{content:\"\\F0CB0\"}.mdi-numeric-9-circle-outline::before{content:\"\\F0CB1\"}.mdi-numeric-9-plus::before{content:\"\\F0FEE\"}.mdi-numeric-9-plus-box::before{content:\"\\F03BF\"}.mdi-numeric-9-plus-box-multiple::before{content:\"\\F0F18\"}.mdi-numeric-9-plus-box-multiple-outline::before{content:\"\\F03C0\"}.mdi-numeric-9-plus-box-outline::before{content:\"\\F03C1\"}.mdi-numeric-9-plus-circle::before{content:\"\\F0CB2\"}.mdi-numeric-9-plus-circle-outline::before{content:\"\\F0CB3\"}.mdi-numeric-negative-1::before{content:\"\\F1052\"}.mdi-numeric-positive-1::before{content:\"\\F15CB\"}.mdi-nut::before{content:\"\\F06F8\"}.mdi-nutrition::before{content:\"\\F03C2\"}.mdi-nuxt::before{content:\"\\F1106\"}.mdi-oar::before{content:\"\\F067C\"}.mdi-ocarina::before{content:\"\\F0DE0\"}.mdi-oci::before{content:\"\\F12E9\"}.mdi-ocr::before{content:\"\\F113A\"}.mdi-octagon::before{content:\"\\F03C3\"}.mdi-octagon-outline::before{content:\"\\F03C4\"}.mdi-octagram::before{content:\"\\F06F9\"}.mdi-octagram-outline::before{content:\"\\F0775\"}.mdi-odnoklassniki::before{content:\"\\F03C5\"}.mdi-offer::before{content:\"\\F121B\"}.mdi-office-building::before{content:\"\\F0991\"}.mdi-office-building-marker::before{content:\"\\F1520\"}.mdi-office-building-marker-outline::before{content:\"\\F1521\"}.mdi-office-building-outline::before{content:\"\\F151F\"}.mdi-oil::before{content:\"\\F03C7\"}.mdi-oil-lamp::before{content:\"\\F0F19\"}.mdi-oil-level::before{content:\"\\F1053\"}.mdi-oil-temperature::before{content:\"\\F0FF8\"}.mdi-omega::before{content:\"\\F03C9\"}.mdi-one-up::before{content:\"\\F0BAD\"}.mdi-onepassword::before{content:\"\\F0881\"}.mdi-opacity::before{content:\"\\F05CC\"}.mdi-open-in-app::before{content:\"\\F03CB\"}.mdi-open-in-new::before{content:\"\\F03CC\"}.mdi-open-source-initiative::before{content:\"\\F0BAE\"}.mdi-openid::before{content:\"\\F03CD\"}.mdi-opera::before{content:\"\\F03CE\"}.mdi-orbit::before{content:\"\\F0018\"}.mdi-orbit-variant::before{content:\"\\F15DB\"}.mdi-order-alphabetical-ascending::before{content:\"\\F020D\"}.mdi-order-alphabetical-descending::before{content:\"\\F0D07\"}.mdi-order-bool-ascending::before{content:\"\\F02BE\"}.mdi-order-bool-ascending-variant::before{content:\"\\F098F\"}.mdi-order-bool-descending::before{content:\"\\F1384\"}.mdi-order-bool-descending-variant::before{content:\"\\F0990\"}.mdi-order-numeric-ascending::before{content:\"\\F0545\"}.mdi-order-numeric-descending::before{content:\"\\F0546\"}.mdi-origin::before{content:\"\\F0B43\"}.mdi-ornament::before{content:\"\\F03CF\"}.mdi-ornament-variant::before{content:\"\\F03D0\"}.mdi-outdoor-lamp::before{content:\"\\F1054\"}.mdi-overscan::before{content:\"\\F1005\"}.mdi-owl::before{content:\"\\F03D2\"}.mdi-pac-man::before{content:\"\\F0BAF\"}.mdi-package::before{content:\"\\F03D3\"}.mdi-package-down::before{content:\"\\F03D4\"}.mdi-package-up::before{content:\"\\F03D5\"}.mdi-package-variant::before{content:\"\\F03D6\"}.mdi-package-variant-closed::before{content:\"\\F03D7\"}.mdi-page-first::before{content:\"\\F0600\"}.mdi-page-last::before{content:\"\\F0601\"}.mdi-page-layout-body::before{content:\"\\F06FA\"}.mdi-page-layout-footer::before{content:\"\\F06FB\"}.mdi-page-layout-header::before{content:\"\\F06FC\"}.mdi-page-layout-header-footer::before{content:\"\\F0F7F\"}.mdi-page-layout-sidebar-left::before{content:\"\\F06FD\"}.mdi-page-layout-sidebar-right::before{content:\"\\F06FE\"}.mdi-page-next::before{content:\"\\F0BB0\"}.mdi-page-next-outline::before{content:\"\\F0BB1\"}.mdi-page-previous::before{content:\"\\F0BB2\"}.mdi-page-previous-outline::before{content:\"\\F0BB3\"}.mdi-pail::before{content:\"\\F1417\"}.mdi-pail-minus::before{content:\"\\F1437\"}.mdi-pail-minus-outline::before{content:\"\\F143C\"}.mdi-pail-off::before{content:\"\\F1439\"}.mdi-pail-off-outline::before{content:\"\\F143E\"}.mdi-pail-outline::before{content:\"\\F143A\"}.mdi-pail-plus::before{content:\"\\F1436\"}.mdi-pail-plus-outline::before{content:\"\\F143B\"}.mdi-pail-remove::before{content:\"\\F1438\"}.mdi-pail-remove-outline::before{content:\"\\F143D\"}.mdi-palette::before{content:\"\\F03D8\"}.mdi-palette-advanced::before{content:\"\\F03D9\"}.mdi-palette-outline::before{content:\"\\F0E0C\"}.mdi-palette-swatch::before{content:\"\\F08B5\"}.mdi-palette-swatch-outline::before{content:\"\\F135C\"}.mdi-palm-tree::before{content:\"\\F1055\"}.mdi-pan::before{content:\"\\F0BB4\"}.mdi-pan-bottom-left::before{content:\"\\F0BB5\"}.mdi-pan-bottom-right::before{content:\"\\F0BB6\"}.mdi-pan-down::before{content:\"\\F0BB7\"}.mdi-pan-horizontal::before{content:\"\\F0BB8\"}.mdi-pan-left::before{content:\"\\F0BB9\"}.mdi-pan-right::before{content:\"\\F0BBA\"}.mdi-pan-top-left::before{content:\"\\F0BBB\"}.mdi-pan-top-right::before{content:\"\\F0BBC\"}.mdi-pan-up::before{content:\"\\F0BBD\"}.mdi-pan-vertical::before{content:\"\\F0BBE\"}.mdi-panda::before{content:\"\\F03DA\"}.mdi-pandora::before{content:\"\\F03DB\"}.mdi-panorama::before{content:\"\\F03DC\"}.mdi-panorama-fisheye::before{content:\"\\F03DD\"}.mdi-panorama-horizontal::before{content:\"\\F03DE\"}.mdi-panorama-vertical::before{content:\"\\F03DF\"}.mdi-panorama-wide-angle::before{content:\"\\F03E0\"}.mdi-paper-cut-vertical::before{content:\"\\F03E1\"}.mdi-paper-roll::before{content:\"\\F1157\"}.mdi-paper-roll-outline::before{content:\"\\F1158\"}.mdi-paperclip::before{content:\"\\F03E2\"}.mdi-parachute::before{content:\"\\F0CB4\"}.mdi-parachute-outline::before{content:\"\\F0CB5\"}.mdi-parking::before{content:\"\\F03E3\"}.mdi-party-popper::before{content:\"\\F1056\"}.mdi-passport::before{content:\"\\F07E3\"}.mdi-passport-biometric::before{content:\"\\F0DE1\"}.mdi-pasta::before{content:\"\\F1160\"}.mdi-patio-heater::before{content:\"\\F0F80\"}.mdi-patreon::before{content:\"\\F0882\"}.mdi-pause::before{content:\"\\F03E4\"}.mdi-pause-circle::before{content:\"\\F03E5\"}.mdi-pause-circle-outline::before{content:\"\\F03E6\"}.mdi-pause-octagon::before{content:\"\\F03E7\"}.mdi-pause-octagon-outline::before{content:\"\\F03E8\"}.mdi-paw::before{content:\"\\F03E9\"}.mdi-paw-off::before{content:\"\\F0657\"}.mdi-paw-off-outline::before{content:\"\\F1676\"}.mdi-paw-outline::before{content:\"\\F1675\"}.mdi-pdf-box::before{content:\"\\F0E56\"}.mdi-peace::before{content:\"\\F0884\"}.mdi-peanut::before{content:\"\\F0FFC\"}.mdi-peanut-off::before{content:\"\\F0FFD\"}.mdi-peanut-off-outline::before{content:\"\\F0FFF\"}.mdi-peanut-outline::before{content:\"\\F0FFE\"}.mdi-pen::before{content:\"\\F03EA\"}.mdi-pen-lock::before{content:\"\\F0DE2\"}.mdi-pen-minus::before{content:\"\\F0DE3\"}.mdi-pen-off::before{content:\"\\F0DE4\"}.mdi-pen-plus::before{content:\"\\F0DE5\"}.mdi-pen-remove::before{content:\"\\F0DE6\"}.mdi-pencil::before{content:\"\\F03EB\"}.mdi-pencil-box::before{content:\"\\F03EC\"}.mdi-pencil-box-multiple::before{content:\"\\F1144\"}.mdi-pencil-box-multiple-outline::before{content:\"\\F1145\"}.mdi-pencil-box-outline::before{content:\"\\F03ED\"}.mdi-pencil-circle::before{content:\"\\F06FF\"}.mdi-pencil-circle-outline::before{content:\"\\F0776\"}.mdi-pencil-lock::before{content:\"\\F03EE\"}.mdi-pencil-lock-outline::before{content:\"\\F0DE7\"}.mdi-pencil-minus::before{content:\"\\F0DE8\"}.mdi-pencil-minus-outline::before{content:\"\\F0DE9\"}.mdi-pencil-off::before{content:\"\\F03EF\"}.mdi-pencil-off-outline::before{content:\"\\F0DEA\"}.mdi-pencil-outline::before{content:\"\\F0CB6\"}.mdi-pencil-plus::before{content:\"\\F0DEB\"}.mdi-pencil-plus-outline::before{content:\"\\F0DEC\"}.mdi-pencil-remove::before{content:\"\\F0DED\"}.mdi-pencil-remove-outline::before{content:\"\\F0DEE\"}.mdi-pencil-ruler::before{content:\"\\F1353\"}.mdi-penguin::before{content:\"\\F0EC0\"}.mdi-pentagon::before{content:\"\\F0701\"}.mdi-pentagon-outline::before{content:\"\\F0700\"}.mdi-pentagram::before{content:\"\\F1667\"}.mdi-percent::before{content:\"\\F03F0\"}.mdi-percent-outline::before{content:\"\\F1278\"}.mdi-periodic-table::before{content:\"\\F08B6\"}.mdi-perspective-less::before{content:\"\\F0D23\"}.mdi-perspective-more::before{content:\"\\F0D24\"}.mdi-pharmacy::before{content:\"\\F03F1\"}.mdi-phone::before{content:\"\\F03F2\"}.mdi-phone-alert::before{content:\"\\F0F1A\"}.mdi-phone-alert-outline::before{content:\"\\F118E\"}.mdi-phone-bluetooth::before{content:\"\\F03F3\"}.mdi-phone-bluetooth-outline::before{content:\"\\F118F\"}.mdi-phone-cancel::before{content:\"\\F10BC\"}.mdi-phone-cancel-outline::before{content:\"\\F1190\"}.mdi-phone-check::before{content:\"\\F11A9\"}.mdi-phone-check-outline::before{content:\"\\F11AA\"}.mdi-phone-classic::before{content:\"\\F0602\"}.mdi-phone-classic-off::before{content:\"\\F1279\"}.mdi-phone-dial::before{content:\"\\F1559\"}.mdi-phone-dial-outline::before{content:\"\\F155A\"}.mdi-phone-forward::before{content:\"\\F03F4\"}.mdi-phone-forward-outline::before{content:\"\\F1191\"}.mdi-phone-hangup::before{content:\"\\F03F5\"}.mdi-phone-hangup-outline::before{content:\"\\F1192\"}.mdi-phone-in-talk::before{content:\"\\F03F6\"}.mdi-phone-in-talk-outline::before{content:\"\\F1182\"}.mdi-phone-incoming::before{content:\"\\F03F7\"}.mdi-phone-incoming-outline::before{content:\"\\F1193\"}.mdi-phone-lock::before{content:\"\\F03F8\"}.mdi-phone-lock-outline::before{content:\"\\F1194\"}.mdi-phone-log::before{content:\"\\F03F9\"}.mdi-phone-log-outline::before{content:\"\\F1195\"}.mdi-phone-message::before{content:\"\\F1196\"}.mdi-phone-message-outline::before{content:\"\\F1197\"}.mdi-phone-minus::before{content:\"\\F0658\"}.mdi-phone-minus-outline::before{content:\"\\F1198\"}.mdi-phone-missed::before{content:\"\\F03FA\"}.mdi-phone-missed-outline::before{content:\"\\F11A5\"}.mdi-phone-off::before{content:\"\\F0DEF\"}.mdi-phone-off-outline::before{content:\"\\F11A6\"}.mdi-phone-outgoing::before{content:\"\\F03FB\"}.mdi-phone-outgoing-outline::before{content:\"\\F1199\"}.mdi-phone-outline::before{content:\"\\F0DF0\"}.mdi-phone-paused::before{content:\"\\F03FC\"}.mdi-phone-paused-outline::before{content:\"\\F119A\"}.mdi-phone-plus::before{content:\"\\F0659\"}.mdi-phone-plus-outline::before{content:\"\\F119B\"}.mdi-phone-remove::before{content:\"\\F152F\"}.mdi-phone-remove-outline::before{content:\"\\F1530\"}.mdi-phone-return::before{content:\"\\F082F\"}.mdi-phone-return-outline::before{content:\"\\F119C\"}.mdi-phone-ring::before{content:\"\\F11AB\"}.mdi-phone-ring-outline::before{content:\"\\F11AC\"}.mdi-phone-rotate-landscape::before{content:\"\\F0885\"}.mdi-phone-rotate-portrait::before{content:\"\\F0886\"}.mdi-phone-settings::before{content:\"\\F03FD\"}.mdi-phone-settings-outline::before{content:\"\\F119D\"}.mdi-phone-voip::before{content:\"\\F03FE\"}.mdi-pi::before{content:\"\\F03FF\"}.mdi-pi-box::before{content:\"\\F0400\"}.mdi-pi-hole::before{content:\"\\F0DF1\"}.mdi-piano::before{content:\"\\F067D\"}.mdi-pickaxe::before{content:\"\\F08B7\"}.mdi-picture-in-picture-bottom-right::before{content:\"\\F0E57\"}.mdi-picture-in-picture-bottom-right-outline::before{content:\"\\F0E58\"}.mdi-picture-in-picture-top-right::before{content:\"\\F0E59\"}.mdi-picture-in-picture-top-right-outline::before{content:\"\\F0E5A\"}.mdi-pier::before{content:\"\\F0887\"}.mdi-pier-crane::before{content:\"\\F0888\"}.mdi-pig::before{content:\"\\F0401\"}.mdi-pig-variant::before{content:\"\\F1006\"}.mdi-pig-variant-outline::before{content:\"\\F1678\"}.mdi-piggy-bank::before{content:\"\\F1007\"}.mdi-piggy-bank-outline::before{content:\"\\F1679\"}.mdi-pill::before{content:\"\\F0402\"}.mdi-pillar::before{content:\"\\F0702\"}.mdi-pin::before{content:\"\\F0403\"}.mdi-pin-off::before{content:\"\\F0404\"}.mdi-pin-off-outline::before{content:\"\\F0930\"}.mdi-pin-outline::before{content:\"\\F0931\"}.mdi-pine-tree::before{content:\"\\F0405\"}.mdi-pine-tree-box::before{content:\"\\F0406\"}.mdi-pine-tree-fire::before{content:\"\\F141A\"}.mdi-pinterest::before{content:\"\\F0407\"}.mdi-pinwheel::before{content:\"\\F0AD5\"}.mdi-pinwheel-outline::before{content:\"\\F0AD6\"}.mdi-pipe::before{content:\"\\F07E5\"}.mdi-pipe-disconnected::before{content:\"\\F07E6\"}.mdi-pipe-leak::before{content:\"\\F0889\"}.mdi-pipe-wrench::before{content:\"\\F1354\"}.mdi-pirate::before{content:\"\\F0A08\"}.mdi-pistol::before{content:\"\\F0703\"}.mdi-piston::before{content:\"\\F088A\"}.mdi-pitchfork::before{content:\"\\F1553\"}.mdi-pizza::before{content:\"\\F0409\"}.mdi-play::before{content:\"\\F040A\"}.mdi-play-box::before{content:\"\\F127A\"}.mdi-play-box-multiple::before{content:\"\\F0D19\"}.mdi-play-box-multiple-outline::before{content:\"\\F13E6\"}.mdi-play-box-outline::before{content:\"\\F040B\"}.mdi-play-circle::before{content:\"\\F040C\"}.mdi-play-circle-outline::before{content:\"\\F040D\"}.mdi-play-network::before{content:\"\\F088B\"}.mdi-play-network-outline::before{content:\"\\F0CB7\"}.mdi-play-outline::before{content:\"\\F0F1B\"}.mdi-play-pause::before{content:\"\\F040E\"}.mdi-play-protected-content::before{content:\"\\F040F\"}.mdi-play-speed::before{content:\"\\F08FF\"}.mdi-playlist-check::before{content:\"\\F05C7\"}.mdi-playlist-edit::before{content:\"\\F0900\"}.mdi-playlist-minus::before{content:\"\\F0410\"}.mdi-playlist-music::before{content:\"\\F0CB8\"}.mdi-playlist-music-outline::before{content:\"\\F0CB9\"}.mdi-playlist-play::before{content:\"\\F0411\"}.mdi-playlist-plus::before{content:\"\\F0412\"}.mdi-playlist-remove::before{content:\"\\F0413\"}.mdi-playlist-star::before{content:\"\\F0DF2\"}.mdi-plex::before{content:\"\\F06BA\"}.mdi-plus::before{content:\"\\F0415\"}.mdi-plus-box::before{content:\"\\F0416\"}.mdi-plus-box-multiple::before{content:\"\\F0334\"}.mdi-plus-box-multiple-outline::before{content:\"\\F1143\"}.mdi-plus-box-outline::before{content:\"\\F0704\"}.mdi-plus-circle::before{content:\"\\F0417\"}.mdi-plus-circle-multiple::before{content:\"\\F034C\"}.mdi-plus-circle-multiple-outline::before{content:\"\\F0418\"}.mdi-plus-circle-outline::before{content:\"\\F0419\"}.mdi-plus-minus::before{content:\"\\F0992\"}.mdi-plus-minus-box::before{content:\"\\F0993\"}.mdi-plus-minus-variant::before{content:\"\\F14C9\"}.mdi-plus-network::before{content:\"\\F041A\"}.mdi-plus-network-outline::before{content:\"\\F0CBA\"}.mdi-plus-one::before{content:\"\\F041B\"}.mdi-plus-outline::before{content:\"\\F0705\"}.mdi-plus-thick::before{content:\"\\F11EC\"}.mdi-podcast::before{content:\"\\F0994\"}.mdi-podium::before{content:\"\\F0D25\"}.mdi-podium-bronze::before{content:\"\\F0D26\"}.mdi-podium-gold::before{content:\"\\F0D27\"}.mdi-podium-silver::before{content:\"\\F0D28\"}.mdi-point-of-sale::before{content:\"\\F0D92\"}.mdi-pokeball::before{content:\"\\F041D\"}.mdi-pokemon-go::before{content:\"\\F0A09\"}.mdi-poker-chip::before{content:\"\\F0830\"}.mdi-polaroid::before{content:\"\\F041E\"}.mdi-police-badge::before{content:\"\\F1167\"}.mdi-police-badge-outline::before{content:\"\\F1168\"}.mdi-poll::before{content:\"\\F041F\"}.mdi-poll-box::before{content:\"\\F0420\"}.mdi-poll-box-outline::before{content:\"\\F127B\"}.mdi-polo::before{content:\"\\F14C3\"}.mdi-polymer::before{content:\"\\F0421\"}.mdi-pool::before{content:\"\\F0606\"}.mdi-popcorn::before{content:\"\\F0422\"}.mdi-post::before{content:\"\\F1008\"}.mdi-post-outline::before{content:\"\\F1009\"}.mdi-postage-stamp::before{content:\"\\F0CBB\"}.mdi-pot::before{content:\"\\F02E5\"}.mdi-pot-mix::before{content:\"\\F065B\"}.mdi-pot-mix-outline::before{content:\"\\F0677\"}.mdi-pot-outline::before{content:\"\\F02FF\"}.mdi-pot-steam::before{content:\"\\F065A\"}.mdi-pot-steam-outline::before{content:\"\\F0326\"}.mdi-pound::before{content:\"\\F0423\"}.mdi-pound-box::before{content:\"\\F0424\"}.mdi-pound-box-outline::before{content:\"\\F117F\"}.mdi-power::before{content:\"\\F0425\"}.mdi-power-cycle::before{content:\"\\F0901\"}.mdi-power-off::before{content:\"\\F0902\"}.mdi-power-on::before{content:\"\\F0903\"}.mdi-power-plug::before{content:\"\\F06A5\"}.mdi-power-plug-off::before{content:\"\\F06A6\"}.mdi-power-plug-off-outline::before{content:\"\\F1424\"}.mdi-power-plug-outline::before{content:\"\\F1425\"}.mdi-power-settings::before{content:\"\\F0426\"}.mdi-power-sleep::before{content:\"\\F0904\"}.mdi-power-socket::before{content:\"\\F0427\"}.mdi-power-socket-au::before{content:\"\\F0905\"}.mdi-power-socket-de::before{content:\"\\F1107\"}.mdi-power-socket-eu::before{content:\"\\F07E7\"}.mdi-power-socket-fr::before{content:\"\\F1108\"}.mdi-power-socket-it::before{content:\"\\F14FF\"}.mdi-power-socket-jp::before{content:\"\\F1109\"}.mdi-power-socket-uk::before{content:\"\\F07E8\"}.mdi-power-socket-us::before{content:\"\\F07E9\"}.mdi-power-standby::before{content:\"\\F0906\"}.mdi-powershell::before{content:\"\\F0A0A\"}.mdi-prescription::before{content:\"\\F0706\"}.mdi-presentation::before{content:\"\\F0428\"}.mdi-presentation-play::before{content:\"\\F0429\"}.mdi-pretzel::before{content:\"\\F1562\"}.mdi-printer::before{content:\"\\F042A\"}.mdi-printer-3d::before{content:\"\\F042B\"}.mdi-printer-3d-nozzle::before{content:\"\\F0E5B\"}.mdi-printer-3d-nozzle-alert::before{content:\"\\F11C0\"}.mdi-printer-3d-nozzle-alert-outline::before{content:\"\\F11C1\"}.mdi-printer-3d-nozzle-outline::before{content:\"\\F0E5C\"}.mdi-printer-alert::before{content:\"\\F042C\"}.mdi-printer-check::before{content:\"\\F1146\"}.mdi-printer-eye::before{content:\"\\F1458\"}.mdi-printer-off::before{content:\"\\F0E5D\"}.mdi-printer-pos::before{content:\"\\F1057\"}.mdi-printer-search::before{content:\"\\F1457\"}.mdi-printer-settings::before{content:\"\\F0707\"}.mdi-printer-wireless::before{content:\"\\F0A0B\"}.mdi-priority-high::before{content:\"\\F0603\"}.mdi-priority-low::before{content:\"\\F0604\"}.mdi-professional-hexagon::before{content:\"\\F042D\"}.mdi-progress-alert::before{content:\"\\F0CBC\"}.mdi-progress-check::before{content:\"\\F0995\"}.mdi-progress-clock::before{content:\"\\F0996\"}.mdi-progress-close::before{content:\"\\F110A\"}.mdi-progress-download::before{content:\"\\F0997\"}.mdi-progress-question::before{content:\"\\F1522\"}.mdi-progress-upload::before{content:\"\\F0998\"}.mdi-progress-wrench::before{content:\"\\F0CBD\"}.mdi-projector::before{content:\"\\F042E\"}.mdi-projector-screen::before{content:\"\\F042F\"}.mdi-propane-tank::before{content:\"\\F1357\"}.mdi-propane-tank-outline::before{content:\"\\F1358\"}.mdi-protocol::before{content:\"\\F0FD8\"}.mdi-publish::before{content:\"\\F06A7\"}.mdi-pulse::before{content:\"\\F0430\"}.mdi-pump::before{content:\"\\F1402\"}.mdi-pumpkin::before{content:\"\\F0BBF\"}.mdi-purse::before{content:\"\\F0F1C\"}.mdi-purse-outline::before{content:\"\\F0F1D\"}.mdi-puzzle::before{content:\"\\F0431\"}.mdi-puzzle-check::before{content:\"\\F1426\"}.mdi-puzzle-check-outline::before{content:\"\\F1427\"}.mdi-puzzle-edit::before{content:\"\\F14D3\"}.mdi-puzzle-edit-outline::before{content:\"\\F14D9\"}.mdi-puzzle-heart::before{content:\"\\F14D4\"}.mdi-puzzle-heart-outline::before{content:\"\\F14DA\"}.mdi-puzzle-minus::before{content:\"\\F14D1\"}.mdi-puzzle-minus-outline::before{content:\"\\F14D7\"}.mdi-puzzle-outline::before{content:\"\\F0A66\"}.mdi-puzzle-plus::before{content:\"\\F14D0\"}.mdi-puzzle-plus-outline::before{content:\"\\F14D6\"}.mdi-puzzle-remove::before{content:\"\\F14D2\"}.mdi-puzzle-remove-outline::before{content:\"\\F14D8\"}.mdi-puzzle-star::before{content:\"\\F14D5\"}.mdi-puzzle-star-outline::before{content:\"\\F14DB\"}.mdi-qi::before{content:\"\\F0999\"}.mdi-qqchat::before{content:\"\\F0605\"}.mdi-qrcode::before{content:\"\\F0432\"}.mdi-qrcode-edit::before{content:\"\\F08B8\"}.mdi-qrcode-minus::before{content:\"\\F118C\"}.mdi-qrcode-plus::before{content:\"\\F118B\"}.mdi-qrcode-remove::before{content:\"\\F118D\"}.mdi-qrcode-scan::before{content:\"\\F0433\"}.mdi-quadcopter::before{content:\"\\F0434\"}.mdi-quality-high::before{content:\"\\F0435\"}.mdi-quality-low::before{content:\"\\F0A0C\"}.mdi-quality-medium::before{content:\"\\F0A0D\"}.mdi-quora::before{content:\"\\F0D29\"}.mdi-rabbit::before{content:\"\\F0907\"}.mdi-racing-helmet::before{content:\"\\F0D93\"}.mdi-racquetball::before{content:\"\\F0D94\"}.mdi-radar::before{content:\"\\F0437\"}.mdi-radiator::before{content:\"\\F0438\"}.mdi-radiator-disabled::before{content:\"\\F0AD7\"}.mdi-radiator-off::before{content:\"\\F0AD8\"}.mdi-radio::before{content:\"\\F0439\"}.mdi-radio-am::before{content:\"\\F0CBE\"}.mdi-radio-fm::before{content:\"\\F0CBF\"}.mdi-radio-handheld::before{content:\"\\F043A\"}.mdi-radio-off::before{content:\"\\F121C\"}.mdi-radio-tower::before{content:\"\\F043B\"}.mdi-radioactive::before{content:\"\\F043C\"}.mdi-radioactive-off::before{content:\"\\F0EC1\"}.mdi-radiobox-blank::before{content:\"\\F043D\"}.mdi-radiobox-marked::before{content:\"\\F043E\"}.mdi-radiology-box::before{content:\"\\F14C5\"}.mdi-radiology-box-outline::before{content:\"\\F14C6\"}.mdi-radius::before{content:\"\\F0CC0\"}.mdi-radius-outline::before{content:\"\\F0CC1\"}.mdi-railroad-light::before{content:\"\\F0F1E\"}.mdi-rake::before{content:\"\\F1544\"}.mdi-raspberry-pi::before{content:\"\\F043F\"}.mdi-ray-end::before{content:\"\\F0440\"}.mdi-ray-end-arrow::before{content:\"\\F0441\"}.mdi-ray-start::before{content:\"\\F0442\"}.mdi-ray-start-arrow::before{content:\"\\F0443\"}.mdi-ray-start-end::before{content:\"\\F0444\"}.mdi-ray-start-vertex-end::before{content:\"\\F15D8\"}.mdi-ray-vertex::before{content:\"\\F0445\"}.mdi-react::before{content:\"\\F0708\"}.mdi-read::before{content:\"\\F0447\"}.mdi-receipt::before{content:\"\\F0449\"}.mdi-record::before{content:\"\\F044A\"}.mdi-record-circle::before{content:\"\\F0EC2\"}.mdi-record-circle-outline::before{content:\"\\F0EC3\"}.mdi-record-player::before{content:\"\\F099A\"}.mdi-record-rec::before{content:\"\\F044B\"}.mdi-rectangle::before{content:\"\\F0E5E\"}.mdi-rectangle-outline::before{content:\"\\F0E5F\"}.mdi-recycle::before{content:\"\\F044C\"}.mdi-recycle-variant::before{content:\"\\F139D\"}.mdi-reddit::before{content:\"\\F044D\"}.mdi-redhat::before{content:\"\\F111B\"}.mdi-redo::before{content:\"\\F044E\"}.mdi-redo-variant::before{content:\"\\F044F\"}.mdi-reflect-horizontal::before{content:\"\\F0A0E\"}.mdi-reflect-vertical::before{content:\"\\F0A0F\"}.mdi-refresh::before{content:\"\\F0450\"}.mdi-refresh-circle::before{content:\"\\F1377\"}.mdi-regex::before{content:\"\\F0451\"}.mdi-registered-trademark::before{content:\"\\F0A67\"}.mdi-reiterate::before{content:\"\\F1588\"}.mdi-relation-many-to-many::before{content:\"\\F1496\"}.mdi-relation-many-to-one::before{content:\"\\F1497\"}.mdi-relation-many-to-one-or-many::before{content:\"\\F1498\"}.mdi-relation-many-to-only-one::before{content:\"\\F1499\"}.mdi-relation-many-to-zero-or-many::before{content:\"\\F149A\"}.mdi-relation-many-to-zero-or-one::before{content:\"\\F149B\"}.mdi-relation-one-or-many-to-many::before{content:\"\\F149C\"}.mdi-relation-one-or-many-to-one::before{content:\"\\F149D\"}.mdi-relation-one-or-many-to-one-or-many::before{content:\"\\F149E\"}.mdi-relation-one-or-many-to-only-one::before{content:\"\\F149F\"}.mdi-relation-one-or-many-to-zero-or-many::before{content:\"\\F14A0\"}.mdi-relation-one-or-many-to-zero-or-one::before{content:\"\\F14A1\"}.mdi-relation-one-to-many::before{content:\"\\F14A2\"}.mdi-relation-one-to-one::before{content:\"\\F14A3\"}.mdi-relation-one-to-one-or-many::before{content:\"\\F14A4\"}.mdi-relation-one-to-only-one::before{content:\"\\F14A5\"}.mdi-relation-one-to-zero-or-many::before{content:\"\\F14A6\"}.mdi-relation-one-to-zero-or-one::before{content:\"\\F14A7\"}.mdi-relation-only-one-to-many::before{content:\"\\F14A8\"}.mdi-relation-only-one-to-one::before{content:\"\\F14A9\"}.mdi-relation-only-one-to-one-or-many::before{content:\"\\F14AA\"}.mdi-relation-only-one-to-only-one::before{content:\"\\F14AB\"}.mdi-relation-only-one-to-zero-or-many::before{content:\"\\F14AC\"}.mdi-relation-only-one-to-zero-or-one::before{content:\"\\F14AD\"}.mdi-relation-zero-or-many-to-many::before{content:\"\\F14AE\"}.mdi-relation-zero-or-many-to-one::before{content:\"\\F14AF\"}.mdi-relation-zero-or-many-to-one-or-many::before{content:\"\\F14B0\"}.mdi-relation-zero-or-many-to-only-one::before{content:\"\\F14B1\"}.mdi-relation-zero-or-many-to-zero-or-many::before{content:\"\\F14B2\"}.mdi-relation-zero-or-many-to-zero-or-one::before{content:\"\\F14B3\"}.mdi-relation-zero-or-one-to-many::before{content:\"\\F14B4\"}.mdi-relation-zero-or-one-to-one::before{content:\"\\F14B5\"}.mdi-relation-zero-or-one-to-one-or-many::before{content:\"\\F14B6\"}.mdi-relation-zero-or-one-to-only-one::before{content:\"\\F14B7\"}.mdi-relation-zero-or-one-to-zero-or-many::before{content:\"\\F14B8\"}.mdi-relation-zero-or-one-to-zero-or-one::before{content:\"\\F14B9\"}.mdi-relative-scale::before{content:\"\\F0452\"}.mdi-reload::before{content:\"\\F0453\"}.mdi-reload-alert::before{content:\"\\F110B\"}.mdi-reminder::before{content:\"\\F088C\"}.mdi-remote::before{content:\"\\F0454\"}.mdi-remote-desktop::before{content:\"\\F08B9\"}.mdi-remote-off::before{content:\"\\F0EC4\"}.mdi-remote-tv::before{content:\"\\F0EC5\"}.mdi-remote-tv-off::before{content:\"\\F0EC6\"}.mdi-rename-box::before{content:\"\\F0455\"}.mdi-reorder-horizontal::before{content:\"\\F0688\"}.mdi-reorder-vertical::before{content:\"\\F0689\"}.mdi-repeat::before{content:\"\\F0456\"}.mdi-repeat-off::before{content:\"\\F0457\"}.mdi-repeat-once::before{content:\"\\F0458\"}.mdi-replay::before{content:\"\\F0459\"}.mdi-reply::before{content:\"\\F045A\"}.mdi-reply-all::before{content:\"\\F045B\"}.mdi-reply-all-outline::before{content:\"\\F0F1F\"}.mdi-reply-circle::before{content:\"\\F11AE\"}.mdi-reply-outline::before{content:\"\\F0F20\"}.mdi-reproduction::before{content:\"\\F045C\"}.mdi-resistor::before{content:\"\\F0B44\"}.mdi-resistor-nodes::before{content:\"\\F0B45\"}.mdi-resize::before{content:\"\\F0A68\"}.mdi-resize-bottom-right::before{content:\"\\F045D\"}.mdi-responsive::before{content:\"\\F045E\"}.mdi-restart::before{content:\"\\F0709\"}.mdi-restart-alert::before{content:\"\\F110C\"}.mdi-restart-off::before{content:\"\\F0D95\"}.mdi-restore::before{content:\"\\F099B\"}.mdi-restore-alert::before{content:\"\\F110D\"}.mdi-rewind::before{content:\"\\F045F\"}.mdi-rewind-10::before{content:\"\\F0D2A\"}.mdi-rewind-30::before{content:\"\\F0D96\"}.mdi-rewind-5::before{content:\"\\F11F9\"}.mdi-rewind-60::before{content:\"\\F160C\"}.mdi-rewind-outline::before{content:\"\\F070A\"}.mdi-rhombus::before{content:\"\\F070B\"}.mdi-rhombus-medium::before{content:\"\\F0A10\"}.mdi-rhombus-medium-outline::before{content:\"\\F14DC\"}.mdi-rhombus-outline::before{content:\"\\F070C\"}.mdi-rhombus-split::before{content:\"\\F0A11\"}.mdi-rhombus-split-outline::before{content:\"\\F14DD\"}.mdi-ribbon::before{content:\"\\F0460\"}.mdi-rice::before{content:\"\\F07EA\"}.mdi-rickshaw::before{content:\"\\F15BB\"}.mdi-rickshaw-electric::before{content:\"\\F15BC\"}.mdi-ring::before{content:\"\\F07EB\"}.mdi-rivet::before{content:\"\\F0E60\"}.mdi-road::before{content:\"\\F0461\"}.mdi-road-variant::before{content:\"\\F0462\"}.mdi-robber::before{content:\"\\F1058\"}.mdi-robot::before{content:\"\\F06A9\"}.mdi-robot-industrial::before{content:\"\\F0B46\"}.mdi-robot-mower::before{content:\"\\F11F7\"}.mdi-robot-mower-outline::before{content:\"\\F11F3\"}.mdi-robot-off-outline::before{content:\"\\F167B\"}.mdi-robot-outline::before{content:\"\\F167A\"}.mdi-robot-vacuum::before{content:\"\\F070D\"}.mdi-robot-vacuum-variant::before{content:\"\\F0908\"}.mdi-rocket::before{content:\"\\F0463\"}.mdi-rocket-launch::before{content:\"\\F14DE\"}.mdi-rocket-launch-outline::before{content:\"\\F14DF\"}.mdi-rocket-outline::before{content:\"\\F13AF\"}.mdi-rodent::before{content:\"\\F1327\"}.mdi-roller-skate::before{content:\"\\F0D2B\"}.mdi-roller-skate-off::before{content:\"\\F0145\"}.mdi-rollerblade::before{content:\"\\F0D2C\"}.mdi-rollerblade-off::before{content:\"\\F002E\"}.mdi-rollupjs::before{content:\"\\F0BC0\"}.mdi-roman-numeral-1::before{content:\"\\F1088\"}.mdi-roman-numeral-10::before{content:\"\\F1091\"}.mdi-roman-numeral-2::before{content:\"\\F1089\"}.mdi-roman-numeral-3::before{content:\"\\F108A\"}.mdi-roman-numeral-4::before{content:\"\\F108B\"}.mdi-roman-numeral-5::before{content:\"\\F108C\"}.mdi-roman-numeral-6::before{content:\"\\F108D\"}.mdi-roman-numeral-7::before{content:\"\\F108E\"}.mdi-roman-numeral-8::before{content:\"\\F108F\"}.mdi-roman-numeral-9::before{content:\"\\F1090\"}.mdi-room-service::before{content:\"\\F088D\"}.mdi-room-service-outline::before{content:\"\\F0D97\"}.mdi-rotate-3d::before{content:\"\\F0EC7\"}.mdi-rotate-3d-variant::before{content:\"\\F0464\"}.mdi-rotate-left::before{content:\"\\F0465\"}.mdi-rotate-left-variant::before{content:\"\\F0466\"}.mdi-rotate-orbit::before{content:\"\\F0D98\"}.mdi-rotate-right::before{content:\"\\F0467\"}.mdi-rotate-right-variant::before{content:\"\\F0468\"}.mdi-rounded-corner::before{content:\"\\F0607\"}.mdi-router::before{content:\"\\F11E2\"}.mdi-router-network::before{content:\"\\F1087\"}.mdi-router-wireless::before{content:\"\\F0469\"}.mdi-router-wireless-off::before{content:\"\\F15A3\"}.mdi-router-wireless-settings::before{content:\"\\F0A69\"}.mdi-routes::before{content:\"\\F046A\"}.mdi-routes-clock::before{content:\"\\F1059\"}.mdi-rowing::before{content:\"\\F0608\"}.mdi-rss::before{content:\"\\F046B\"}.mdi-rss-box::before{content:\"\\F046C\"}.mdi-rss-off::before{content:\"\\F0F21\"}.mdi-rug::before{content:\"\\F1475\"}.mdi-rugby::before{content:\"\\F0D99\"}.mdi-ruler::before{content:\"\\F046D\"}.mdi-ruler-square::before{content:\"\\F0CC2\"}.mdi-ruler-square-compass::before{content:\"\\F0EBE\"}.mdi-run::before{content:\"\\F070E\"}.mdi-run-fast::before{content:\"\\F046E\"}.mdi-rv-truck::before{content:\"\\F11D4\"}.mdi-sack::before{content:\"\\F0D2E\"}.mdi-sack-percent::before{content:\"\\F0D2F\"}.mdi-safe::before{content:\"\\F0A6A\"}.mdi-safe-square::before{content:\"\\F127C\"}.mdi-safe-square-outline::before{content:\"\\F127D\"}.mdi-safety-goggles::before{content:\"\\F0D30\"}.mdi-sail-boat::before{content:\"\\F0EC8\"}.mdi-sale::before{content:\"\\F046F\"}.mdi-salesforce::before{content:\"\\F088E\"}.mdi-sass::before{content:\"\\F07EC\"}.mdi-satellite::before{content:\"\\F0470\"}.mdi-satellite-uplink::before{content:\"\\F0909\"}.mdi-satellite-variant::before{content:\"\\F0471\"}.mdi-sausage::before{content:\"\\F08BA\"}.mdi-saw-blade::before{content:\"\\F0E61\"}.mdi-sawtooth-wave::before{content:\"\\F147A\"}.mdi-saxophone::before{content:\"\\F0609\"}.mdi-scale::before{content:\"\\F0472\"}.mdi-scale-balance::before{content:\"\\F05D1\"}.mdi-scale-bathroom::before{content:\"\\F0473\"}.mdi-scale-off::before{content:\"\\F105A\"}.mdi-scan-helper::before{content:\"\\F13D8\"}.mdi-scanner::before{content:\"\\F06AB\"}.mdi-scanner-off::before{content:\"\\F090A\"}.mdi-scatter-plot::before{content:\"\\F0EC9\"}.mdi-scatter-plot-outline::before{content:\"\\F0ECA\"}.mdi-school::before{content:\"\\F0474\"}.mdi-school-outline::before{content:\"\\F1180\"}.mdi-scissors-cutting::before{content:\"\\F0A6B\"}.mdi-scooter::before{content:\"\\F15BD\"}.mdi-scooter-electric::before{content:\"\\F15BE\"}.mdi-scoreboard::before{content:\"\\F127E\"}.mdi-scoreboard-outline::before{content:\"\\F127F\"}.mdi-screen-rotation::before{content:\"\\F0475\"}.mdi-screen-rotation-lock::before{content:\"\\F0478\"}.mdi-screw-flat-top::before{content:\"\\F0DF3\"}.mdi-screw-lag::before{content:\"\\F0DF4\"}.mdi-screw-machine-flat-top::before{content:\"\\F0DF5\"}.mdi-screw-machine-round-top::before{content:\"\\F0DF6\"}.mdi-screw-round-top::before{content:\"\\F0DF7\"}.mdi-screwdriver::before{content:\"\\F0476\"}.mdi-script::before{content:\"\\F0BC1\"}.mdi-script-outline::before{content:\"\\F0477\"}.mdi-script-text::before{content:\"\\F0BC2\"}.mdi-script-text-outline::before{content:\"\\F0BC3\"}.mdi-sd::before{content:\"\\F0479\"}.mdi-seal::before{content:\"\\F047A\"}.mdi-seal-variant::before{content:\"\\F0FD9\"}.mdi-search-web::before{content:\"\\F070F\"}.mdi-seat::before{content:\"\\F0CC3\"}.mdi-seat-flat::before{content:\"\\F047B\"}.mdi-seat-flat-angled::before{content:\"\\F047C\"}.mdi-seat-individual-suite::before{content:\"\\F047D\"}.mdi-seat-legroom-extra::before{content:\"\\F047E\"}.mdi-seat-legroom-normal::before{content:\"\\F047F\"}.mdi-seat-legroom-reduced::before{content:\"\\F0480\"}.mdi-seat-outline::before{content:\"\\F0CC4\"}.mdi-seat-passenger::before{content:\"\\F1249\"}.mdi-seat-recline-extra::before{content:\"\\F0481\"}.mdi-seat-recline-normal::before{content:\"\\F0482\"}.mdi-seatbelt::before{content:\"\\F0CC5\"}.mdi-security::before{content:\"\\F0483\"}.mdi-security-network::before{content:\"\\F0484\"}.mdi-seed::before{content:\"\\F0E62\"}.mdi-seed-off::before{content:\"\\F13FD\"}.mdi-seed-off-outline::before{content:\"\\F13FE\"}.mdi-seed-outline::before{content:\"\\F0E63\"}.mdi-seesaw::before{content:\"\\F15A4\"}.mdi-segment::before{content:\"\\F0ECB\"}.mdi-select::before{content:\"\\F0485\"}.mdi-select-all::before{content:\"\\F0486\"}.mdi-select-color::before{content:\"\\F0D31\"}.mdi-select-compare::before{content:\"\\F0AD9\"}.mdi-select-drag::before{content:\"\\F0A6C\"}.mdi-select-group::before{content:\"\\F0F82\"}.mdi-select-inverse::before{content:\"\\F0487\"}.mdi-select-marker::before{content:\"\\F1280\"}.mdi-select-multiple::before{content:\"\\F1281\"}.mdi-select-multiple-marker::before{content:\"\\F1282\"}.mdi-select-off::before{content:\"\\F0488\"}.mdi-select-place::before{content:\"\\F0FDA\"}.mdi-select-search::before{content:\"\\F1204\"}.mdi-selection::before{content:\"\\F0489\"}.mdi-selection-drag::before{content:\"\\F0A6D\"}.mdi-selection-ellipse::before{content:\"\\F0D32\"}.mdi-selection-ellipse-arrow-inside::before{content:\"\\F0F22\"}.mdi-selection-marker::before{content:\"\\F1283\"}.mdi-selection-multiple::before{content:\"\\F1285\"}.mdi-selection-multiple-marker::before{content:\"\\F1284\"}.mdi-selection-off::before{content:\"\\F0777\"}.mdi-selection-search::before{content:\"\\F1205\"}.mdi-semantic-web::before{content:\"\\F1316\"}.mdi-send::before{content:\"\\F048A\"}.mdi-send-check::before{content:\"\\F1161\"}.mdi-send-check-outline::before{content:\"\\F1162\"}.mdi-send-circle::before{content:\"\\F0DF8\"}.mdi-send-circle-outline::before{content:\"\\F0DF9\"}.mdi-send-clock::before{content:\"\\F1163\"}.mdi-send-clock-outline::before{content:\"\\F1164\"}.mdi-send-lock::before{content:\"\\F07ED\"}.mdi-send-lock-outline::before{content:\"\\F1166\"}.mdi-send-outline::before{content:\"\\F1165\"}.mdi-serial-port::before{content:\"\\F065C\"}.mdi-server::before{content:\"\\F048B\"}.mdi-server-minus::before{content:\"\\F048C\"}.mdi-server-network::before{content:\"\\F048D\"}.mdi-server-network-off::before{content:\"\\F048E\"}.mdi-server-off::before{content:\"\\F048F\"}.mdi-server-plus::before{content:\"\\F0490\"}.mdi-server-remove::before{content:\"\\F0491\"}.mdi-server-security::before{content:\"\\F0492\"}.mdi-set-all::before{content:\"\\F0778\"}.mdi-set-center::before{content:\"\\F0779\"}.mdi-set-center-right::before{content:\"\\F077A\"}.mdi-set-left::before{content:\"\\F077B\"}.mdi-set-left-center::before{content:\"\\F077C\"}.mdi-set-left-right::before{content:\"\\F077D\"}.mdi-set-merge::before{content:\"\\F14E0\"}.mdi-set-none::before{content:\"\\F077E\"}.mdi-set-right::before{content:\"\\F077F\"}.mdi-set-split::before{content:\"\\F14E1\"}.mdi-set-square::before{content:\"\\F145D\"}.mdi-set-top-box::before{content:\"\\F099F\"}.mdi-settings-helper::before{content:\"\\F0A6E\"}.mdi-shaker::before{content:\"\\F110E\"}.mdi-shaker-outline::before{content:\"\\F110F\"}.mdi-shape::before{content:\"\\F0831\"}.mdi-shape-circle-plus::before{content:\"\\F065D\"}.mdi-shape-outline::before{content:\"\\F0832\"}.mdi-shape-oval-plus::before{content:\"\\F11FA\"}.mdi-shape-plus::before{content:\"\\F0495\"}.mdi-shape-polygon-plus::before{content:\"\\F065E\"}.mdi-shape-rectangle-plus::before{content:\"\\F065F\"}.mdi-shape-square-plus::before{content:\"\\F0660\"}.mdi-shape-square-rounded-plus::before{content:\"\\F14FA\"}.mdi-share::before{content:\"\\F0496\"}.mdi-share-all::before{content:\"\\F11F4\"}.mdi-share-all-outline::before{content:\"\\F11F5\"}.mdi-share-circle::before{content:\"\\F11AD\"}.mdi-share-off::before{content:\"\\F0F23\"}.mdi-share-off-outline::before{content:\"\\F0F24\"}.mdi-share-outline::before{content:\"\\F0932\"}.mdi-share-variant::before{content:\"\\F0497\"}.mdi-share-variant-outline::before{content:\"\\F1514\"}.mdi-shark-fin::before{content:\"\\F1673\"}.mdi-shark-fin-outline::before{content:\"\\F1674\"}.mdi-sheep::before{content:\"\\F0CC6\"}.mdi-shield::before{content:\"\\F0498\"}.mdi-shield-account::before{content:\"\\F088F\"}.mdi-shield-account-outline::before{content:\"\\F0A12\"}.mdi-shield-account-variant::before{content:\"\\F15A7\"}.mdi-shield-account-variant-outline::before{content:\"\\F15A8\"}.mdi-shield-airplane::before{content:\"\\F06BB\"}.mdi-shield-airplane-outline::before{content:\"\\F0CC7\"}.mdi-shield-alert::before{content:\"\\F0ECC\"}.mdi-shield-alert-outline::before{content:\"\\F0ECD\"}.mdi-shield-bug::before{content:\"\\F13DA\"}.mdi-shield-bug-outline::before{content:\"\\F13DB\"}.mdi-shield-car::before{content:\"\\F0F83\"}.mdi-shield-check::before{content:\"\\F0565\"}.mdi-shield-check-outline::before{content:\"\\F0CC8\"}.mdi-shield-cross::before{content:\"\\F0CC9\"}.mdi-shield-cross-outline::before{content:\"\\F0CCA\"}.mdi-shield-edit::before{content:\"\\F11A0\"}.mdi-shield-edit-outline::before{content:\"\\F11A1\"}.mdi-shield-half::before{content:\"\\F1360\"}.mdi-shield-half-full::before{content:\"\\F0780\"}.mdi-shield-home::before{content:\"\\F068A\"}.mdi-shield-home-outline::before{content:\"\\F0CCB\"}.mdi-shield-key::before{content:\"\\F0BC4\"}.mdi-shield-key-outline::before{content:\"\\F0BC5\"}.mdi-shield-link-variant::before{content:\"\\F0D33\"}.mdi-shield-link-variant-outline::before{content:\"\\F0D34\"}.mdi-shield-lock::before{content:\"\\F099D\"}.mdi-shield-lock-outline::before{content:\"\\F0CCC\"}.mdi-shield-off::before{content:\"\\F099E\"}.mdi-shield-off-outline::before{content:\"\\F099C\"}.mdi-shield-outline::before{content:\"\\F0499\"}.mdi-shield-plus::before{content:\"\\F0ADA\"}.mdi-shield-plus-outline::before{content:\"\\F0ADB\"}.mdi-shield-refresh::before{content:\"\\F00AA\"}.mdi-shield-refresh-outline::before{content:\"\\F01E0\"}.mdi-shield-remove::before{content:\"\\F0ADC\"}.mdi-shield-remove-outline::before{content:\"\\F0ADD\"}.mdi-shield-search::before{content:\"\\F0D9A\"}.mdi-shield-star::before{content:\"\\F113B\"}.mdi-shield-star-outline::before{content:\"\\F113C\"}.mdi-shield-sun::before{content:\"\\F105D\"}.mdi-shield-sun-outline::before{content:\"\\F105E\"}.mdi-shield-sync::before{content:\"\\F11A2\"}.mdi-shield-sync-outline::before{content:\"\\F11A3\"}.mdi-ship-wheel::before{content:\"\\F0833\"}.mdi-shoe-ballet::before{content:\"\\F15CA\"}.mdi-shoe-cleat::before{content:\"\\F15C7\"}.mdi-shoe-formal::before{content:\"\\F0B47\"}.mdi-shoe-heel::before{content:\"\\F0B48\"}.mdi-shoe-print::before{content:\"\\F0DFA\"}.mdi-shoe-sneaker::before{content:\"\\F15C8\"}.mdi-shopping::before{content:\"\\F049A\"}.mdi-shopping-music::before{content:\"\\F049B\"}.mdi-shopping-outline::before{content:\"\\F11D5\"}.mdi-shopping-search::before{content:\"\\F0F84\"}.mdi-shore::before{content:\"\\F14F9\"}.mdi-shovel::before{content:\"\\F0710\"}.mdi-shovel-off::before{content:\"\\F0711\"}.mdi-shower::before{content:\"\\F09A0\"}.mdi-shower-head::before{content:\"\\F09A1\"}.mdi-shredder::before{content:\"\\F049C\"}.mdi-shuffle::before{content:\"\\F049D\"}.mdi-shuffle-disabled::before{content:\"\\F049E\"}.mdi-shuffle-variant::before{content:\"\\F049F\"}.mdi-shuriken::before{content:\"\\F137F\"}.mdi-sigma::before{content:\"\\F04A0\"}.mdi-sigma-lower::before{content:\"\\F062B\"}.mdi-sign-caution::before{content:\"\\F04A1\"}.mdi-sign-direction::before{content:\"\\F0781\"}.mdi-sign-direction-minus::before{content:\"\\F1000\"}.mdi-sign-direction-plus::before{content:\"\\F0FDC\"}.mdi-sign-direction-remove::before{content:\"\\F0FDD\"}.mdi-sign-pole::before{content:\"\\F14F8\"}.mdi-sign-real-estate::before{content:\"\\F1118\"}.mdi-sign-text::before{content:\"\\F0782\"}.mdi-signal::before{content:\"\\F04A2\"}.mdi-signal-2g::before{content:\"\\F0712\"}.mdi-signal-3g::before{content:\"\\F0713\"}.mdi-signal-4g::before{content:\"\\F0714\"}.mdi-signal-5g::before{content:\"\\F0A6F\"}.mdi-signal-cellular-1::before{content:\"\\F08BC\"}.mdi-signal-cellular-2::before{content:\"\\F08BD\"}.mdi-signal-cellular-3::before{content:\"\\F08BE\"}.mdi-signal-cellular-outline::before{content:\"\\F08BF\"}.mdi-signal-distance-variant::before{content:\"\\F0E64\"}.mdi-signal-hspa::before{content:\"\\F0715\"}.mdi-signal-hspa-plus::before{content:\"\\F0716\"}.mdi-signal-off::before{content:\"\\F0783\"}.mdi-signal-variant::before{content:\"\\F060A\"}.mdi-signature::before{content:\"\\F0DFB\"}.mdi-signature-freehand::before{content:\"\\F0DFC\"}.mdi-signature-image::before{content:\"\\F0DFD\"}.mdi-signature-text::before{content:\"\\F0DFE\"}.mdi-silo::before{content:\"\\F0B49\"}.mdi-silverware::before{content:\"\\F04A3\"}.mdi-silverware-clean::before{content:\"\\F0FDE\"}.mdi-silverware-fork::before{content:\"\\F04A4\"}.mdi-silverware-fork-knife::before{content:\"\\F0A70\"}.mdi-silverware-spoon::before{content:\"\\F04A5\"}.mdi-silverware-variant::before{content:\"\\F04A6\"}.mdi-sim::before{content:\"\\F04A7\"}.mdi-sim-alert::before{content:\"\\F04A8\"}.mdi-sim-alert-outline::before{content:\"\\F15D3\"}.mdi-sim-off::before{content:\"\\F04A9\"}.mdi-sim-off-outline::before{content:\"\\F15D4\"}.mdi-sim-outline::before{content:\"\\F15D5\"}.mdi-simple-icons::before{content:\"\\F131D\"}.mdi-sina-weibo::before{content:\"\\F0ADF\"}.mdi-sine-wave::before{content:\"\\F095B\"}.mdi-sitemap::before{content:\"\\F04AA\"}.mdi-size-l::before{content:\"\\F13A6\"}.mdi-size-m::before{content:\"\\F13A5\"}.mdi-size-s::before{content:\"\\F13A4\"}.mdi-size-xl::before{content:\"\\F13A7\"}.mdi-size-xs::before{content:\"\\F13A3\"}.mdi-size-xxl::before{content:\"\\F13A8\"}.mdi-size-xxs::before{content:\"\\F13A2\"}.mdi-size-xxxl::before{content:\"\\F13A9\"}.mdi-skate::before{content:\"\\F0D35\"}.mdi-skateboard::before{content:\"\\F14C2\"}.mdi-skew-less::before{content:\"\\F0D36\"}.mdi-skew-more::before{content:\"\\F0D37\"}.mdi-ski::before{content:\"\\F1304\"}.mdi-ski-cross-country::before{content:\"\\F1305\"}.mdi-ski-water::before{content:\"\\F1306\"}.mdi-skip-backward::before{content:\"\\F04AB\"}.mdi-skip-backward-outline::before{content:\"\\F0F25\"}.mdi-skip-forward::before{content:\"\\F04AC\"}.mdi-skip-forward-outline::before{content:\"\\F0F26\"}.mdi-skip-next::before{content:\"\\F04AD\"}.mdi-skip-next-circle::before{content:\"\\F0661\"}.mdi-skip-next-circle-outline::before{content:\"\\F0662\"}.mdi-skip-next-outline::before{content:\"\\F0F27\"}.mdi-skip-previous::before{content:\"\\F04AE\"}.mdi-skip-previous-circle::before{content:\"\\F0663\"}.mdi-skip-previous-circle-outline::before{content:\"\\F0664\"}.mdi-skip-previous-outline::before{content:\"\\F0F28\"}.mdi-skull::before{content:\"\\F068C\"}.mdi-skull-crossbones::before{content:\"\\F0BC6\"}.mdi-skull-crossbones-outline::before{content:\"\\F0BC7\"}.mdi-skull-outline::before{content:\"\\F0BC8\"}.mdi-skull-scan::before{content:\"\\F14C7\"}.mdi-skull-scan-outline::before{content:\"\\F14C8\"}.mdi-skype::before{content:\"\\F04AF\"}.mdi-skype-business::before{content:\"\\F04B0\"}.mdi-slack::before{content:\"\\F04B1\"}.mdi-slash-forward::before{content:\"\\F0FDF\"}.mdi-slash-forward-box::before{content:\"\\F0FE0\"}.mdi-sleep::before{content:\"\\F04B2\"}.mdi-sleep-off::before{content:\"\\F04B3\"}.mdi-slide::before{content:\"\\F15A5\"}.mdi-slope-downhill::before{content:\"\\F0DFF\"}.mdi-slope-uphill::before{content:\"\\F0E00\"}.mdi-slot-machine::before{content:\"\\F1114\"}.mdi-slot-machine-outline::before{content:\"\\F1115\"}.mdi-smart-card::before{content:\"\\F10BD\"}.mdi-smart-card-outline::before{content:\"\\F10BE\"}.mdi-smart-card-reader::before{content:\"\\F10BF\"}.mdi-smart-card-reader-outline::before{content:\"\\F10C0\"}.mdi-smog::before{content:\"\\F0A71\"}.mdi-smoke-detector::before{content:\"\\F0392\"}.mdi-smoking::before{content:\"\\F04B4\"}.mdi-smoking-off::before{content:\"\\F04B5\"}.mdi-smoking-pipe::before{content:\"\\F140D\"}.mdi-smoking-pipe-off::before{content:\"\\F1428\"}.mdi-snail::before{content:\"\\F1677\"}.mdi-snake::before{content:\"\\F150E\"}.mdi-snapchat::before{content:\"\\F04B6\"}.mdi-snowboard::before{content:\"\\F1307\"}.mdi-snowflake::before{content:\"\\F0717\"}.mdi-snowflake-alert::before{content:\"\\F0F29\"}.mdi-snowflake-melt::before{content:\"\\F12CB\"}.mdi-snowflake-off::before{content:\"\\F14E3\"}.mdi-snowflake-variant::before{content:\"\\F0F2A\"}.mdi-snowman::before{content:\"\\F04B7\"}.mdi-soccer::before{content:\"\\F04B8\"}.mdi-soccer-field::before{content:\"\\F0834\"}.mdi-social-distance-2-meters::before{content:\"\\F1579\"}.mdi-social-distance-6-feet::before{content:\"\\F157A\"}.mdi-sofa::before{content:\"\\F04B9\"}.mdi-sofa-outline::before{content:\"\\F156D\"}.mdi-sofa-single::before{content:\"\\F156E\"}.mdi-sofa-single-outline::before{content:\"\\F156F\"}.mdi-solar-panel::before{content:\"\\F0D9B\"}.mdi-solar-panel-large::before{content:\"\\F0D9C\"}.mdi-solar-power::before{content:\"\\F0A72\"}.mdi-soldering-iron::before{content:\"\\F1092\"}.mdi-solid::before{content:\"\\F068D\"}.mdi-sony-playstation::before{content:\"\\F0414\"}.mdi-sort::before{content:\"\\F04BA\"}.mdi-sort-alphabetical-ascending::before{content:\"\\F05BD\"}.mdi-sort-alphabetical-ascending-variant::before{content:\"\\F1148\"}.mdi-sort-alphabetical-descending::before{content:\"\\F05BF\"}.mdi-sort-alphabetical-descending-variant::before{content:\"\\F1149\"}.mdi-sort-alphabetical-variant::before{content:\"\\F04BB\"}.mdi-sort-ascending::before{content:\"\\F04BC\"}.mdi-sort-bool-ascending::before{content:\"\\F1385\"}.mdi-sort-bool-ascending-variant::before{content:\"\\F1386\"}.mdi-sort-bool-descending::before{content:\"\\F1387\"}.mdi-sort-bool-descending-variant::before{content:\"\\F1388\"}.mdi-sort-calendar-ascending::before{content:\"\\F1547\"}.mdi-sort-calendar-descending::before{content:\"\\F1548\"}.mdi-sort-clock-ascending::before{content:\"\\F1549\"}.mdi-sort-clock-ascending-outline::before{content:\"\\F154A\"}.mdi-sort-clock-descending::before{content:\"\\F154B\"}.mdi-sort-clock-descending-outline::before{content:\"\\F154C\"}.mdi-sort-descending::before{content:\"\\F04BD\"}.mdi-sort-numeric-ascending::before{content:\"\\F1389\"}.mdi-sort-numeric-ascending-variant::before{content:\"\\F090D\"}.mdi-sort-numeric-descending::before{content:\"\\F138A\"}.mdi-sort-numeric-descending-variant::before{content:\"\\F0AD2\"}.mdi-sort-numeric-variant::before{content:\"\\F04BE\"}.mdi-sort-reverse-variant::before{content:\"\\F033C\"}.mdi-sort-variant::before{content:\"\\F04BF\"}.mdi-sort-variant-lock::before{content:\"\\F0CCD\"}.mdi-sort-variant-lock-open::before{content:\"\\F0CCE\"}.mdi-sort-variant-remove::before{content:\"\\F1147\"}.mdi-soundcloud::before{content:\"\\F04C0\"}.mdi-source-branch::before{content:\"\\F062C\"}.mdi-source-branch-check::before{content:\"\\F14CF\"}.mdi-source-branch-minus::before{content:\"\\F14CB\"}.mdi-source-branch-plus::before{content:\"\\F14CA\"}.mdi-source-branch-refresh::before{content:\"\\F14CD\"}.mdi-source-branch-remove::before{content:\"\\F14CC\"}.mdi-source-branch-sync::before{content:\"\\F14CE\"}.mdi-source-commit::before{content:\"\\F0718\"}.mdi-source-commit-end::before{content:\"\\F0719\"}.mdi-source-commit-end-local::before{content:\"\\F071A\"}.mdi-source-commit-local::before{content:\"\\F071B\"}.mdi-source-commit-next-local::before{content:\"\\F071C\"}.mdi-source-commit-start::before{content:\"\\F071D\"}.mdi-source-commit-start-next-local::before{content:\"\\F071E\"}.mdi-source-fork::before{content:\"\\F04C1\"}.mdi-source-merge::before{content:\"\\F062D\"}.mdi-source-pull::before{content:\"\\F04C2\"}.mdi-source-repository::before{content:\"\\F0CCF\"}.mdi-source-repository-multiple::before{content:\"\\F0CD0\"}.mdi-soy-sauce::before{content:\"\\F07EE\"}.mdi-soy-sauce-off::before{content:\"\\F13FC\"}.mdi-spa::before{content:\"\\F0CD1\"}.mdi-spa-outline::before{content:\"\\F0CD2\"}.mdi-space-invaders::before{content:\"\\F0BC9\"}.mdi-space-station::before{content:\"\\F1383\"}.mdi-spade::before{content:\"\\F0E65\"}.mdi-sparkles::before{content:\"\\F1545\"}.mdi-speaker::before{content:\"\\F04C3\"}.mdi-speaker-bluetooth::before{content:\"\\F09A2\"}.mdi-speaker-multiple::before{content:\"\\F0D38\"}.mdi-speaker-off::before{content:\"\\F04C4\"}.mdi-speaker-wireless::before{content:\"\\F071F\"}.mdi-speedometer::before{content:\"\\F04C5\"}.mdi-speedometer-medium::before{content:\"\\F0F85\"}.mdi-speedometer-slow::before{content:\"\\F0F86\"}.mdi-spellcheck::before{content:\"\\F04C6\"}.mdi-spider::before{content:\"\\F11EA\"}.mdi-spider-thread::before{content:\"\\F11EB\"}.mdi-spider-web::before{content:\"\\F0BCA\"}.mdi-spirit-level::before{content:\"\\F14F1\"}.mdi-spoon-sugar::before{content:\"\\F1429\"}.mdi-spotify::before{content:\"\\F04C7\"}.mdi-spotlight::before{content:\"\\F04C8\"}.mdi-spotlight-beam::before{content:\"\\F04C9\"}.mdi-spray::before{content:\"\\F0665\"}.mdi-spray-bottle::before{content:\"\\F0AE0\"}.mdi-sprinkler::before{content:\"\\F105F\"}.mdi-sprinkler-variant::before{content:\"\\F1060\"}.mdi-sprout::before{content:\"\\F0E66\"}.mdi-sprout-outline::before{content:\"\\F0E67\"}.mdi-square::before{content:\"\\F0764\"}.mdi-square-circle::before{content:\"\\F1500\"}.mdi-square-edit-outline::before{content:\"\\F090C\"}.mdi-square-medium::before{content:\"\\F0A13\"}.mdi-square-medium-outline::before{content:\"\\F0A14\"}.mdi-square-off::before{content:\"\\F12EE\"}.mdi-square-off-outline::before{content:\"\\F12EF\"}.mdi-square-outline::before{content:\"\\F0763\"}.mdi-square-root::before{content:\"\\F0784\"}.mdi-square-root-box::before{content:\"\\F09A3\"}.mdi-square-rounded::before{content:\"\\F14FB\"}.mdi-square-rounded-outline::before{content:\"\\F14FC\"}.mdi-square-small::before{content:\"\\F0A15\"}.mdi-square-wave::before{content:\"\\F147B\"}.mdi-squeegee::before{content:\"\\F0AE1\"}.mdi-ssh::before{content:\"\\F08C0\"}.mdi-stack-exchange::before{content:\"\\F060B\"}.mdi-stack-overflow::before{content:\"\\F04CC\"}.mdi-stackpath::before{content:\"\\F0359\"}.mdi-stadium::before{content:\"\\F0FF9\"}.mdi-stadium-variant::before{content:\"\\F0720\"}.mdi-stairs::before{content:\"\\F04CD\"}.mdi-stairs-box::before{content:\"\\F139E\"}.mdi-stairs-down::before{content:\"\\F12BE\"}.mdi-stairs-up::before{content:\"\\F12BD\"}.mdi-stamper::before{content:\"\\F0D39\"}.mdi-standard-definition::before{content:\"\\F07EF\"}.mdi-star::before{content:\"\\F04CE\"}.mdi-star-box::before{content:\"\\F0A73\"}.mdi-star-box-multiple::before{content:\"\\F1286\"}.mdi-star-box-multiple-outline::before{content:\"\\F1287\"}.mdi-star-box-outline::before{content:\"\\F0A74\"}.mdi-star-check::before{content:\"\\F1566\"}.mdi-star-check-outline::before{content:\"\\F156A\"}.mdi-star-circle::before{content:\"\\F04CF\"}.mdi-star-circle-outline::before{content:\"\\F09A4\"}.mdi-star-cog::before{content:\"\\F1668\"}.mdi-star-cog-outline::before{content:\"\\F1669\"}.mdi-star-face::before{content:\"\\F09A5\"}.mdi-star-four-points::before{content:\"\\F0AE2\"}.mdi-star-four-points-outline::before{content:\"\\F0AE3\"}.mdi-star-half::before{content:\"\\F0246\"}.mdi-star-half-full::before{content:\"\\F04D0\"}.mdi-star-minus::before{content:\"\\F1564\"}.mdi-star-minus-outline::before{content:\"\\F1568\"}.mdi-star-off::before{content:\"\\F04D1\"}.mdi-star-off-outline::before{content:\"\\F155B\"}.mdi-star-outline::before{content:\"\\F04D2\"}.mdi-star-plus::before{content:\"\\F1563\"}.mdi-star-plus-outline::before{content:\"\\F1567\"}.mdi-star-remove::before{content:\"\\F1565\"}.mdi-star-remove-outline::before{content:\"\\F1569\"}.mdi-star-settings::before{content:\"\\F166A\"}.mdi-star-settings-outline::before{content:\"\\F166B\"}.mdi-star-three-points::before{content:\"\\F0AE4\"}.mdi-star-three-points-outline::before{content:\"\\F0AE5\"}.mdi-state-machine::before{content:\"\\F11EF\"}.mdi-steam::before{content:\"\\F04D3\"}.mdi-steering::before{content:\"\\F04D4\"}.mdi-steering-off::before{content:\"\\F090E\"}.mdi-step-backward::before{content:\"\\F04D5\"}.mdi-step-backward-2::before{content:\"\\F04D6\"}.mdi-step-forward::before{content:\"\\F04D7\"}.mdi-step-forward-2::before{content:\"\\F04D8\"}.mdi-stethoscope::before{content:\"\\F04D9\"}.mdi-sticker::before{content:\"\\F1364\"}.mdi-sticker-alert::before{content:\"\\F1365\"}.mdi-sticker-alert-outline::before{content:\"\\F1366\"}.mdi-sticker-check::before{content:\"\\F1367\"}.mdi-sticker-check-outline::before{content:\"\\F1368\"}.mdi-sticker-circle-outline::before{content:\"\\F05D0\"}.mdi-sticker-emoji::before{content:\"\\F0785\"}.mdi-sticker-minus::before{content:\"\\F1369\"}.mdi-sticker-minus-outline::before{content:\"\\F136A\"}.mdi-sticker-outline::before{content:\"\\F136B\"}.mdi-sticker-plus::before{content:\"\\F136C\"}.mdi-sticker-plus-outline::before{content:\"\\F136D\"}.mdi-sticker-remove::before{content:\"\\F136E\"}.mdi-sticker-remove-outline::before{content:\"\\F136F\"}.mdi-stocking::before{content:\"\\F04DA\"}.mdi-stomach::before{content:\"\\F1093\"}.mdi-stop::before{content:\"\\F04DB\"}.mdi-stop-circle::before{content:\"\\F0666\"}.mdi-stop-circle-outline::before{content:\"\\F0667\"}.mdi-store::before{content:\"\\F04DC\"}.mdi-store-24-hour::before{content:\"\\F04DD\"}.mdi-store-minus::before{content:\"\\F165E\"}.mdi-store-outline::before{content:\"\\F1361\"}.mdi-store-plus::before{content:\"\\F165F\"}.mdi-store-remove::before{content:\"\\F1660\"}.mdi-storefront::before{content:\"\\F07C7\"}.mdi-storefront-outline::before{content:\"\\F10C1\"}.mdi-stove::before{content:\"\\F04DE\"}.mdi-strategy::before{content:\"\\F11D6\"}.mdi-stretch-to-page::before{content:\"\\F0F2B\"}.mdi-stretch-to-page-outline::before{content:\"\\F0F2C\"}.mdi-string-lights::before{content:\"\\F12BA\"}.mdi-string-lights-off::before{content:\"\\F12BB\"}.mdi-subdirectory-arrow-left::before{content:\"\\F060C\"}.mdi-subdirectory-arrow-right::before{content:\"\\F060D\"}.mdi-submarine::before{content:\"\\F156C\"}.mdi-subtitles::before{content:\"\\F0A16\"}.mdi-subtitles-outline::before{content:\"\\F0A17\"}.mdi-subway::before{content:\"\\F06AC\"}.mdi-subway-alert-variant::before{content:\"\\F0D9D\"}.mdi-subway-variant::before{content:\"\\F04DF\"}.mdi-summit::before{content:\"\\F0786\"}.mdi-sunglasses::before{content:\"\\F04E0\"}.mdi-surround-sound::before{content:\"\\F05C5\"}.mdi-surround-sound-2-0::before{content:\"\\F07F0\"}.mdi-surround-sound-3-1::before{content:\"\\F07F1\"}.mdi-surround-sound-5-1::before{content:\"\\F07F2\"}.mdi-surround-sound-7-1::before{content:\"\\F07F3\"}.mdi-svg::before{content:\"\\F0721\"}.mdi-swap-horizontal::before{content:\"\\F04E1\"}.mdi-swap-horizontal-bold::before{content:\"\\F0BCD\"}.mdi-swap-horizontal-circle::before{content:\"\\F0FE1\"}.mdi-swap-horizontal-circle-outline::before{content:\"\\F0FE2\"}.mdi-swap-horizontal-variant::before{content:\"\\F08C1\"}.mdi-swap-vertical::before{content:\"\\F04E2\"}.mdi-swap-vertical-bold::before{content:\"\\F0BCE\"}.mdi-swap-vertical-circle::before{content:\"\\F0FE3\"}.mdi-swap-vertical-circle-outline::before{content:\"\\F0FE4\"}.mdi-swap-vertical-variant::before{content:\"\\F08C2\"}.mdi-swim::before{content:\"\\F04E3\"}.mdi-switch::before{content:\"\\F04E4\"}.mdi-sword::before{content:\"\\F04E5\"}.mdi-sword-cross::before{content:\"\\F0787\"}.mdi-syllabary-hangul::before{content:\"\\F1333\"}.mdi-syllabary-hiragana::before{content:\"\\F1334\"}.mdi-syllabary-katakana::before{content:\"\\F1335\"}.mdi-syllabary-katakana-halfwidth::before{content:\"\\F1336\"}.mdi-symbol::before{content:\"\\F1501\"}.mdi-symfony::before{content:\"\\F0AE6\"}.mdi-sync::before{content:\"\\F04E6\"}.mdi-sync-alert::before{content:\"\\F04E7\"}.mdi-sync-circle::before{content:\"\\F1378\"}.mdi-sync-off::before{content:\"\\F04E8\"}.mdi-tab::before{content:\"\\F04E9\"}.mdi-tab-minus::before{content:\"\\F0B4B\"}.mdi-tab-plus::before{content:\"\\F075C\"}.mdi-tab-remove::before{content:\"\\F0B4C\"}.mdi-tab-unselected::before{content:\"\\F04EA\"}.mdi-table::before{content:\"\\F04EB\"}.mdi-table-account::before{content:\"\\F13B9\"}.mdi-table-alert::before{content:\"\\F13BA\"}.mdi-table-arrow-down::before{content:\"\\F13BB\"}.mdi-table-arrow-left::before{content:\"\\F13BC\"}.mdi-table-arrow-right::before{content:\"\\F13BD\"}.mdi-table-arrow-up::before{content:\"\\F13BE\"}.mdi-table-border::before{content:\"\\F0A18\"}.mdi-table-cancel::before{content:\"\\F13BF\"}.mdi-table-chair::before{content:\"\\F1061\"}.mdi-table-check::before{content:\"\\F13C0\"}.mdi-table-clock::before{content:\"\\F13C1\"}.mdi-table-cog::before{content:\"\\F13C2\"}.mdi-table-column::before{content:\"\\F0835\"}.mdi-table-column-plus-after::before{content:\"\\F04EC\"}.mdi-table-column-plus-before::before{content:\"\\F04ED\"}.mdi-table-column-remove::before{content:\"\\F04EE\"}.mdi-table-column-width::before{content:\"\\F04EF\"}.mdi-table-edit::before{content:\"\\F04F0\"}.mdi-table-eye::before{content:\"\\F1094\"}.mdi-table-eye-off::before{content:\"\\F13C3\"}.mdi-table-furniture::before{content:\"\\F05BC\"}.mdi-table-headers-eye::before{content:\"\\F121D\"}.mdi-table-headers-eye-off::before{content:\"\\F121E\"}.mdi-table-heart::before{content:\"\\F13C4\"}.mdi-table-key::before{content:\"\\F13C5\"}.mdi-table-large::before{content:\"\\F04F1\"}.mdi-table-large-plus::before{content:\"\\F0F87\"}.mdi-table-large-remove::before{content:\"\\F0F88\"}.mdi-table-lock::before{content:\"\\F13C6\"}.mdi-table-merge-cells::before{content:\"\\F09A6\"}.mdi-table-minus::before{content:\"\\F13C7\"}.mdi-table-multiple::before{content:\"\\F13C8\"}.mdi-table-network::before{content:\"\\F13C9\"}.mdi-table-of-contents::before{content:\"\\F0836\"}.mdi-table-off::before{content:\"\\F13CA\"}.mdi-table-plus::before{content:\"\\F0A75\"}.mdi-table-refresh::before{content:\"\\F13A0\"}.mdi-table-remove::before{content:\"\\F0A76\"}.mdi-table-row::before{content:\"\\F0837\"}.mdi-table-row-height::before{content:\"\\F04F2\"}.mdi-table-row-plus-after::before{content:\"\\F04F3\"}.mdi-table-row-plus-before::before{content:\"\\F04F4\"}.mdi-table-row-remove::before{content:\"\\F04F5\"}.mdi-table-search::before{content:\"\\F090F\"}.mdi-table-settings::before{content:\"\\F0838\"}.mdi-table-split-cell::before{content:\"\\F142A\"}.mdi-table-star::before{content:\"\\F13CB\"}.mdi-table-sync::before{content:\"\\F13A1\"}.mdi-table-tennis::before{content:\"\\F0E68\"}.mdi-tablet::before{content:\"\\F04F6\"}.mdi-tablet-android::before{content:\"\\F04F7\"}.mdi-tablet-cellphone::before{content:\"\\F09A7\"}.mdi-tablet-dashboard::before{content:\"\\F0ECE\"}.mdi-tablet-ipad::before{content:\"\\F04F8\"}.mdi-taco::before{content:\"\\F0762\"}.mdi-tag::before{content:\"\\F04F9\"}.mdi-tag-faces::before{content:\"\\F04FA\"}.mdi-tag-heart::before{content:\"\\F068B\"}.mdi-tag-heart-outline::before{content:\"\\F0BCF\"}.mdi-tag-minus::before{content:\"\\F0910\"}.mdi-tag-minus-outline::before{content:\"\\F121F\"}.mdi-tag-multiple::before{content:\"\\F04FB\"}.mdi-tag-multiple-outline::before{content:\"\\F12F7\"}.mdi-tag-off::before{content:\"\\F1220\"}.mdi-tag-off-outline::before{content:\"\\F1221\"}.mdi-tag-outline::before{content:\"\\F04FC\"}.mdi-tag-plus::before{content:\"\\F0722\"}.mdi-tag-plus-outline::before{content:\"\\F1222\"}.mdi-tag-remove::before{content:\"\\F0723\"}.mdi-tag-remove-outline::before{content:\"\\F1223\"}.mdi-tag-text::before{content:\"\\F1224\"}.mdi-tag-text-outline::before{content:\"\\F04FD\"}.mdi-tailwind::before{content:\"\\F13FF\"}.mdi-tank::before{content:\"\\F0D3A\"}.mdi-tanker-truck::before{content:\"\\F0FE5\"}.mdi-tape-measure::before{content:\"\\F0B4D\"}.mdi-target::before{content:\"\\F04FE\"}.mdi-target-account::before{content:\"\\F0BD0\"}.mdi-target-variant::before{content:\"\\F0A77\"}.mdi-taxi::before{content:\"\\F04FF\"}.mdi-tea::before{content:\"\\F0D9E\"}.mdi-tea-outline::before{content:\"\\F0D9F\"}.mdi-teach::before{content:\"\\F0890\"}.mdi-teamviewer::before{content:\"\\F0500\"}.mdi-telegram::before{content:\"\\F0501\"}.mdi-telescope::before{content:\"\\F0B4E\"}.mdi-television::before{content:\"\\F0502\"}.mdi-television-ambient-light::before{content:\"\\F1356\"}.mdi-television-box::before{content:\"\\F0839\"}.mdi-television-classic::before{content:\"\\F07F4\"}.mdi-television-classic-off::before{content:\"\\F083A\"}.mdi-television-clean::before{content:\"\\F1110\"}.mdi-television-guide::before{content:\"\\F0503\"}.mdi-television-off::before{content:\"\\F083B\"}.mdi-television-pause::before{content:\"\\F0F89\"}.mdi-television-play::before{content:\"\\F0ECF\"}.mdi-television-stop::before{content:\"\\F0F8A\"}.mdi-temperature-celsius::before{content:\"\\F0504\"}.mdi-temperature-fahrenheit::before{content:\"\\F0505\"}.mdi-temperature-kelvin::before{content:\"\\F0506\"}.mdi-tennis::before{content:\"\\F0DA0\"}.mdi-tennis-ball::before{content:\"\\F0507\"}.mdi-tent::before{content:\"\\F0508\"}.mdi-terraform::before{content:\"\\F1062\"}.mdi-terrain::before{content:\"\\F0509\"}.mdi-test-tube::before{content:\"\\F0668\"}.mdi-test-tube-empty::before{content:\"\\F0911\"}.mdi-test-tube-off::before{content:\"\\F0912\"}.mdi-text::before{content:\"\\F09A8\"}.mdi-text-account::before{content:\"\\F1570\"}.mdi-text-box::before{content:\"\\F021A\"}.mdi-text-box-check::before{content:\"\\F0EA6\"}.mdi-text-box-check-outline::before{content:\"\\F0EA7\"}.mdi-text-box-minus::before{content:\"\\F0EA8\"}.mdi-text-box-minus-outline::before{content:\"\\F0EA9\"}.mdi-text-box-multiple::before{content:\"\\F0AB7\"}.mdi-text-box-multiple-outline::before{content:\"\\F0AB8\"}.mdi-text-box-outline::before{content:\"\\F09ED\"}.mdi-text-box-plus::before{content:\"\\F0EAA\"}.mdi-text-box-plus-outline::before{content:\"\\F0EAB\"}.mdi-text-box-remove::before{content:\"\\F0EAC\"}.mdi-text-box-remove-outline::before{content:\"\\F0EAD\"}.mdi-text-box-search::before{content:\"\\F0EAE\"}.mdi-text-box-search-outline::before{content:\"\\F0EAF\"}.mdi-text-recognition::before{content:\"\\F113D\"}.mdi-text-search::before{content:\"\\F13B8\"}.mdi-text-shadow::before{content:\"\\F0669\"}.mdi-text-short::before{content:\"\\F09A9\"}.mdi-text-subject::before{content:\"\\F09AA\"}.mdi-text-to-speech::before{content:\"\\F050A\"}.mdi-text-to-speech-off::before{content:\"\\F050B\"}.mdi-texture::before{content:\"\\F050C\"}.mdi-texture-box::before{content:\"\\F0FE6\"}.mdi-theater::before{content:\"\\F050D\"}.mdi-theme-light-dark::before{content:\"\\F050E\"}.mdi-thermometer::before{content:\"\\F050F\"}.mdi-thermometer-alert::before{content:\"\\F0E01\"}.mdi-thermometer-chevron-down::before{content:\"\\F0E02\"}.mdi-thermometer-chevron-up::before{content:\"\\F0E03\"}.mdi-thermometer-high::before{content:\"\\F10C2\"}.mdi-thermometer-lines::before{content:\"\\F0510\"}.mdi-thermometer-low::before{content:\"\\F10C3\"}.mdi-thermometer-minus::before{content:\"\\F0E04\"}.mdi-thermometer-off::before{content:\"\\F1531\"}.mdi-thermometer-plus::before{content:\"\\F0E05\"}.mdi-thermostat::before{content:\"\\F0393\"}.mdi-thermostat-box::before{content:\"\\F0891\"}.mdi-thought-bubble::before{content:\"\\F07F6\"}.mdi-thought-bubble-outline::before{content:\"\\F07F7\"}.mdi-thumb-down::before{content:\"\\F0511\"}.mdi-thumb-down-outline::before{content:\"\\F0512\"}.mdi-thumb-up::before{content:\"\\F0513\"}.mdi-thumb-up-outline::before{content:\"\\F0514\"}.mdi-thumbs-up-down::before{content:\"\\F0515\"}.mdi-ticket::before{content:\"\\F0516\"}.mdi-ticket-account::before{content:\"\\F0517\"}.mdi-ticket-confirmation::before{content:\"\\F0518\"}.mdi-ticket-confirmation-outline::before{content:\"\\F13AA\"}.mdi-ticket-outline::before{content:\"\\F0913\"}.mdi-ticket-percent::before{content:\"\\F0724\"}.mdi-ticket-percent-outline::before{content:\"\\F142B\"}.mdi-tie::before{content:\"\\F0519\"}.mdi-tilde::before{content:\"\\F0725\"}.mdi-timelapse::before{content:\"\\F051A\"}.mdi-timeline::before{content:\"\\F0BD1\"}.mdi-timeline-alert::before{content:\"\\F0F95\"}.mdi-timeline-alert-outline::before{content:\"\\F0F98\"}.mdi-timeline-check::before{content:\"\\F1532\"}.mdi-timeline-check-outline::before{content:\"\\F1533\"}.mdi-timeline-clock::before{content:\"\\F11FB\"}.mdi-timeline-clock-outline::before{content:\"\\F11FC\"}.mdi-timeline-help::before{content:\"\\F0F99\"}.mdi-timeline-help-outline::before{content:\"\\F0F9A\"}.mdi-timeline-minus::before{content:\"\\F1534\"}.mdi-timeline-minus-outline::before{content:\"\\F1535\"}.mdi-timeline-outline::before{content:\"\\F0BD2\"}.mdi-timeline-plus::before{content:\"\\F0F96\"}.mdi-timeline-plus-outline::before{content:\"\\F0F97\"}.mdi-timeline-remove::before{content:\"\\F1536\"}.mdi-timeline-remove-outline::before{content:\"\\F1537\"}.mdi-timeline-text::before{content:\"\\F0BD3\"}.mdi-timeline-text-outline::before{content:\"\\F0BD4\"}.mdi-timer::before{content:\"\\F13AB\"}.mdi-timer-10::before{content:\"\\F051C\"}.mdi-timer-3::before{content:\"\\F051D\"}.mdi-timer-off::before{content:\"\\F13AC\"}.mdi-timer-off-outline::before{content:\"\\F051E\"}.mdi-timer-outline::before{content:\"\\F051B\"}.mdi-timer-sand::before{content:\"\\F051F\"}.mdi-timer-sand-empty::before{content:\"\\F06AD\"}.mdi-timer-sand-full::before{content:\"\\F078C\"}.mdi-timetable::before{content:\"\\F0520\"}.mdi-toaster::before{content:\"\\F1063\"}.mdi-toaster-off::before{content:\"\\F11B7\"}.mdi-toaster-oven::before{content:\"\\F0CD3\"}.mdi-toggle-switch::before{content:\"\\F0521\"}.mdi-toggle-switch-off::before{content:\"\\F0522\"}.mdi-toggle-switch-off-outline::before{content:\"\\F0A19\"}.mdi-toggle-switch-outline::before{content:\"\\F0A1A\"}.mdi-toilet::before{content:\"\\F09AB\"}.mdi-toolbox::before{content:\"\\F09AC\"}.mdi-toolbox-outline::before{content:\"\\F09AD\"}.mdi-tools::before{content:\"\\F1064\"}.mdi-tooltip::before{content:\"\\F0523\"}.mdi-tooltip-account::before{content:\"\\F000C\"}.mdi-tooltip-check::before{content:\"\\F155C\"}.mdi-tooltip-check-outline::before{content:\"\\F155D\"}.mdi-tooltip-edit::before{content:\"\\F0524\"}.mdi-tooltip-edit-outline::before{content:\"\\F12C5\"}.mdi-tooltip-image::before{content:\"\\F0525\"}.mdi-tooltip-image-outline::before{content:\"\\F0BD5\"}.mdi-tooltip-minus::before{content:\"\\F155E\"}.mdi-tooltip-minus-outline::before{content:\"\\F155F\"}.mdi-tooltip-outline::before{content:\"\\F0526\"}.mdi-tooltip-plus::before{content:\"\\F0BD6\"}.mdi-tooltip-plus-outline::before{content:\"\\F0527\"}.mdi-tooltip-remove::before{content:\"\\F1560\"}.mdi-tooltip-remove-outline::before{content:\"\\F1561\"}.mdi-tooltip-text::before{content:\"\\F0528\"}.mdi-tooltip-text-outline::before{content:\"\\F0BD7\"}.mdi-tooth::before{content:\"\\F08C3\"}.mdi-tooth-outline::before{content:\"\\F0529\"}.mdi-toothbrush::before{content:\"\\F1129\"}.mdi-toothbrush-electric::before{content:\"\\F112C\"}.mdi-toothbrush-paste::before{content:\"\\F112A\"}.mdi-torch::before{content:\"\\F1606\"}.mdi-tortoise::before{content:\"\\F0D3B\"}.mdi-toslink::before{content:\"\\F12B8\"}.mdi-tournament::before{content:\"\\F09AE\"}.mdi-tow-truck::before{content:\"\\F083C\"}.mdi-tower-beach::before{content:\"\\F0681\"}.mdi-tower-fire::before{content:\"\\F0682\"}.mdi-toy-brick::before{content:\"\\F1288\"}.mdi-toy-brick-marker::before{content:\"\\F1289\"}.mdi-toy-brick-marker-outline::before{content:\"\\F128A\"}.mdi-toy-brick-minus::before{content:\"\\F128B\"}.mdi-toy-brick-minus-outline::before{content:\"\\F128C\"}.mdi-toy-brick-outline::before{content:\"\\F128D\"}.mdi-toy-brick-plus::before{content:\"\\F128E\"}.mdi-toy-brick-plus-outline::before{content:\"\\F128F\"}.mdi-toy-brick-remove::before{content:\"\\F1290\"}.mdi-toy-brick-remove-outline::before{content:\"\\F1291\"}.mdi-toy-brick-search::before{content:\"\\F1292\"}.mdi-toy-brick-search-outline::before{content:\"\\F1293\"}.mdi-track-light::before{content:\"\\F0914\"}.mdi-trackpad::before{content:\"\\F07F8\"}.mdi-trackpad-lock::before{content:\"\\F0933\"}.mdi-tractor::before{content:\"\\F0892\"}.mdi-tractor-variant::before{content:\"\\F14C4\"}.mdi-trademark::before{content:\"\\F0A78\"}.mdi-traffic-cone::before{content:\"\\F137C\"}.mdi-traffic-light::before{content:\"\\F052B\"}.mdi-train::before{content:\"\\F052C\"}.mdi-train-car::before{content:\"\\F0BD8\"}.mdi-train-variant::before{content:\"\\F08C4\"}.mdi-tram::before{content:\"\\F052D\"}.mdi-tram-side::before{content:\"\\F0FE7\"}.mdi-transcribe::before{content:\"\\F052E\"}.mdi-transcribe-close::before{content:\"\\F052F\"}.mdi-transfer::before{content:\"\\F1065\"}.mdi-transfer-down::before{content:\"\\F0DA1\"}.mdi-transfer-left::before{content:\"\\F0DA2\"}.mdi-transfer-right::before{content:\"\\F0530\"}.mdi-transfer-up::before{content:\"\\F0DA3\"}.mdi-transit-connection::before{content:\"\\F0D3C\"}.mdi-transit-connection-horizontal::before{content:\"\\F1546\"}.mdi-transit-connection-variant::before{content:\"\\F0D3D\"}.mdi-transit-detour::before{content:\"\\F0F8B\"}.mdi-transit-skip::before{content:\"\\F1515\"}.mdi-transit-transfer::before{content:\"\\F06AE\"}.mdi-transition::before{content:\"\\F0915\"}.mdi-transition-masked::before{content:\"\\F0916\"}.mdi-translate::before{content:\"\\F05CA\"}.mdi-translate-off::before{content:\"\\F0E06\"}.mdi-transmission-tower::before{content:\"\\F0D3E\"}.mdi-trash-can::before{content:\"\\F0A79\"}.mdi-trash-can-outline::before{content:\"\\F0A7A\"}.mdi-tray::before{content:\"\\F1294\"}.mdi-tray-alert::before{content:\"\\F1295\"}.mdi-tray-full::before{content:\"\\F1296\"}.mdi-tray-minus::before{content:\"\\F1297\"}.mdi-tray-plus::before{content:\"\\F1298\"}.mdi-tray-remove::before{content:\"\\F1299\"}.mdi-treasure-chest::before{content:\"\\F0726\"}.mdi-tree::before{content:\"\\F0531\"}.mdi-tree-outline::before{content:\"\\F0E69\"}.mdi-trello::before{content:\"\\F0532\"}.mdi-trending-down::before{content:\"\\F0533\"}.mdi-trending-neutral::before{content:\"\\F0534\"}.mdi-trending-up::before{content:\"\\F0535\"}.mdi-triangle::before{content:\"\\F0536\"}.mdi-triangle-outline::before{content:\"\\F0537\"}.mdi-triangle-wave::before{content:\"\\F147C\"}.mdi-triforce::before{content:\"\\F0BD9\"}.mdi-trophy::before{content:\"\\F0538\"}.mdi-trophy-award::before{content:\"\\F0539\"}.mdi-trophy-broken::before{content:\"\\F0DA4\"}.mdi-trophy-outline::before{content:\"\\F053A\"}.mdi-trophy-variant::before{content:\"\\F053B\"}.mdi-trophy-variant-outline::before{content:\"\\F053C\"}.mdi-truck::before{content:\"\\F053D\"}.mdi-truck-check::before{content:\"\\F0CD4\"}.mdi-truck-check-outline::before{content:\"\\F129A\"}.mdi-truck-delivery::before{content:\"\\F053E\"}.mdi-truck-delivery-outline::before{content:\"\\F129B\"}.mdi-truck-fast::before{content:\"\\F0788\"}.mdi-truck-fast-outline::before{content:\"\\F129C\"}.mdi-truck-outline::before{content:\"\\F129D\"}.mdi-truck-trailer::before{content:\"\\F0727\"}.mdi-trumpet::before{content:\"\\F1096\"}.mdi-tshirt-crew::before{content:\"\\F0A7B\"}.mdi-tshirt-crew-outline::before{content:\"\\F053F\"}.mdi-tshirt-v::before{content:\"\\F0A7C\"}.mdi-tshirt-v-outline::before{content:\"\\F0540\"}.mdi-tumble-dryer::before{content:\"\\F0917\"}.mdi-tumble-dryer-alert::before{content:\"\\F11BA\"}.mdi-tumble-dryer-off::before{content:\"\\F11BB\"}.mdi-tune::before{content:\"\\F062E\"}.mdi-tune-variant::before{content:\"\\F1542\"}.mdi-tune-vertical::before{content:\"\\F066A\"}.mdi-tune-vertical-variant::before{content:\"\\F1543\"}.mdi-turnstile::before{content:\"\\F0CD5\"}.mdi-turnstile-outline::before{content:\"\\F0CD6\"}.mdi-turtle::before{content:\"\\F0CD7\"}.mdi-twitch::before{content:\"\\F0543\"}.mdi-twitter::before{content:\"\\F0544\"}.mdi-twitter-retweet::before{content:\"\\F0547\"}.mdi-two-factor-authentication::before{content:\"\\F09AF\"}.mdi-typewriter::before{content:\"\\F0F2D\"}.mdi-ubisoft::before{content:\"\\F0BDA\"}.mdi-ubuntu::before{content:\"\\F0548\"}.mdi-ufo::before{content:\"\\F10C4\"}.mdi-ufo-outline::before{content:\"\\F10C5\"}.mdi-ultra-high-definition::before{content:\"\\F07F9\"}.mdi-umbraco::before{content:\"\\F0549\"}.mdi-umbrella::before{content:\"\\F054A\"}.mdi-umbrella-closed::before{content:\"\\F09B0\"}.mdi-umbrella-closed-outline::before{content:\"\\F13E2\"}.mdi-umbrella-closed-variant::before{content:\"\\F13E1\"}.mdi-umbrella-outline::before{content:\"\\F054B\"}.mdi-undo::before{content:\"\\F054C\"}.mdi-undo-variant::before{content:\"\\F054D\"}.mdi-unfold-less-horizontal::before{content:\"\\F054E\"}.mdi-unfold-less-vertical::before{content:\"\\F0760\"}.mdi-unfold-more-horizontal::before{content:\"\\F054F\"}.mdi-unfold-more-vertical::before{content:\"\\F0761\"}.mdi-ungroup::before{content:\"\\F0550\"}.mdi-unicode::before{content:\"\\F0ED0\"}.mdi-unicorn::before{content:\"\\F15C2\"}.mdi-unicorn-variant::before{content:\"\\F15C3\"}.mdi-unicycle::before{content:\"\\F15E5\"}.mdi-unity::before{content:\"\\F06AF\"}.mdi-unreal::before{content:\"\\F09B1\"}.mdi-untappd::before{content:\"\\F0551\"}.mdi-update::before{content:\"\\F06B0\"}.mdi-upload::before{content:\"\\F0552\"}.mdi-upload-lock::before{content:\"\\F1373\"}.mdi-upload-lock-outline::before{content:\"\\F1374\"}.mdi-upload-multiple::before{content:\"\\F083D\"}.mdi-upload-network::before{content:\"\\F06F6\"}.mdi-upload-network-outline::before{content:\"\\F0CD8\"}.mdi-upload-off::before{content:\"\\F10C6\"}.mdi-upload-off-outline::before{content:\"\\F10C7\"}.mdi-upload-outline::before{content:\"\\F0E07\"}.mdi-usb::before{content:\"\\F0553\"}.mdi-usb-flash-drive::before{content:\"\\F129E\"}.mdi-usb-flash-drive-outline::before{content:\"\\F129F\"}.mdi-usb-port::before{content:\"\\F11F0\"}.mdi-valve::before{content:\"\\F1066\"}.mdi-valve-closed::before{content:\"\\F1067\"}.mdi-valve-open::before{content:\"\\F1068\"}.mdi-van-passenger::before{content:\"\\F07FA\"}.mdi-van-utility::before{content:\"\\F07FB\"}.mdi-vanish::before{content:\"\\F07FC\"}.mdi-vanish-quarter::before{content:\"\\F1554\"}.mdi-vanity-light::before{content:\"\\F11E1\"}.mdi-variable::before{content:\"\\F0AE7\"}.mdi-variable-box::before{content:\"\\F1111\"}.mdi-vector-arrange-above::before{content:\"\\F0554\"}.mdi-vector-arrange-below::before{content:\"\\F0555\"}.mdi-vector-bezier::before{content:\"\\F0AE8\"}.mdi-vector-circle::before{content:\"\\F0556\"}.mdi-vector-circle-variant::before{content:\"\\F0557\"}.mdi-vector-combine::before{content:\"\\F0558\"}.mdi-vector-curve::before{content:\"\\F0559\"}.mdi-vector-difference::before{content:\"\\F055A\"}.mdi-vector-difference-ab::before{content:\"\\F055B\"}.mdi-vector-difference-ba::before{content:\"\\F055C\"}.mdi-vector-ellipse::before{content:\"\\F0893\"}.mdi-vector-intersection::before{content:\"\\F055D\"}.mdi-vector-line::before{content:\"\\F055E\"}.mdi-vector-link::before{content:\"\\F0FE8\"}.mdi-vector-point::before{content:\"\\F055F\"}.mdi-vector-polygon::before{content:\"\\F0560\"}.mdi-vector-polyline::before{content:\"\\F0561\"}.mdi-vector-polyline-edit::before{content:\"\\F1225\"}.mdi-vector-polyline-minus::before{content:\"\\F1226\"}.mdi-vector-polyline-plus::before{content:\"\\F1227\"}.mdi-vector-polyline-remove::before{content:\"\\F1228\"}.mdi-vector-radius::before{content:\"\\F074A\"}.mdi-vector-rectangle::before{content:\"\\F05C6\"}.mdi-vector-selection::before{content:\"\\F0562\"}.mdi-vector-square::before{content:\"\\F0001\"}.mdi-vector-triangle::before{content:\"\\F0563\"}.mdi-vector-union::before{content:\"\\F0564\"}.mdi-vhs::before{content:\"\\F0A1B\"}.mdi-vibrate::before{content:\"\\F0566\"}.mdi-vibrate-off::before{content:\"\\F0CD9\"}.mdi-video::before{content:\"\\F0567\"}.mdi-video-3d::before{content:\"\\F07FD\"}.mdi-video-3d-off::before{content:\"\\F13D9\"}.mdi-video-3d-variant::before{content:\"\\F0ED1\"}.mdi-video-4k-box::before{content:\"\\F083E\"}.mdi-video-account::before{content:\"\\F0919\"}.mdi-video-box::before{content:\"\\F00FD\"}.mdi-video-box-off::before{content:\"\\F00FE\"}.mdi-video-check::before{content:\"\\F1069\"}.mdi-video-check-outline::before{content:\"\\F106A\"}.mdi-video-high-definition::before{content:\"\\F152E\"}.mdi-video-image::before{content:\"\\F091A\"}.mdi-video-input-antenna::before{content:\"\\F083F\"}.mdi-video-input-component::before{content:\"\\F0840\"}.mdi-video-input-hdmi::before{content:\"\\F0841\"}.mdi-video-input-scart::before{content:\"\\F0F8C\"}.mdi-video-input-svideo::before{content:\"\\F0842\"}.mdi-video-minus::before{content:\"\\F09B2\"}.mdi-video-minus-outline::before{content:\"\\F02BA\"}.mdi-video-off::before{content:\"\\F0568\"}.mdi-video-off-outline::before{content:\"\\F0BDB\"}.mdi-video-outline::before{content:\"\\F0BDC\"}.mdi-video-plus::before{content:\"\\F09B3\"}.mdi-video-plus-outline::before{content:\"\\F01D3\"}.mdi-video-stabilization::before{content:\"\\F091B\"}.mdi-video-switch::before{content:\"\\F0569\"}.mdi-video-switch-outline::before{content:\"\\F0790\"}.mdi-video-vintage::before{content:\"\\F0A1C\"}.mdi-video-wireless::before{content:\"\\F0ED2\"}.mdi-video-wireless-outline::before{content:\"\\F0ED3\"}.mdi-view-agenda::before{content:\"\\F056A\"}.mdi-view-agenda-outline::before{content:\"\\F11D8\"}.mdi-view-array::before{content:\"\\F056B\"}.mdi-view-array-outline::before{content:\"\\F1485\"}.mdi-view-carousel::before{content:\"\\F056C\"}.mdi-view-carousel-outline::before{content:\"\\F1486\"}.mdi-view-column::before{content:\"\\F056D\"}.mdi-view-column-outline::before{content:\"\\F1487\"}.mdi-view-comfy::before{content:\"\\F0E6A\"}.mdi-view-comfy-outline::before{content:\"\\F1488\"}.mdi-view-compact::before{content:\"\\F0E6B\"}.mdi-view-compact-outline::before{content:\"\\F0E6C\"}.mdi-view-dashboard::before{content:\"\\F056E\"}.mdi-view-dashboard-outline::before{content:\"\\F0A1D\"}.mdi-view-dashboard-variant::before{content:\"\\F0843\"}.mdi-view-dashboard-variant-outline::before{content:\"\\F1489\"}.mdi-view-day::before{content:\"\\F056F\"}.mdi-view-day-outline::before{content:\"\\F148A\"}.mdi-view-grid::before{content:\"\\F0570\"}.mdi-view-grid-outline::before{content:\"\\F11D9\"}.mdi-view-grid-plus::before{content:\"\\F0F8D\"}.mdi-view-grid-plus-outline::before{content:\"\\F11DA\"}.mdi-view-headline::before{content:\"\\F0571\"}.mdi-view-list::before{content:\"\\F0572\"}.mdi-view-list-outline::before{content:\"\\F148B\"}.mdi-view-module::before{content:\"\\F0573\"}.mdi-view-module-outline::before{content:\"\\F148C\"}.mdi-view-parallel::before{content:\"\\F0728\"}.mdi-view-parallel-outline::before{content:\"\\F148D\"}.mdi-view-quilt::before{content:\"\\F0574\"}.mdi-view-quilt-outline::before{content:\"\\F148E\"}.mdi-view-sequential::before{content:\"\\F0729\"}.mdi-view-sequential-outline::before{content:\"\\F148F\"}.mdi-view-split-horizontal::before{content:\"\\F0BCB\"}.mdi-view-split-vertical::before{content:\"\\F0BCC\"}.mdi-view-stream::before{content:\"\\F0575\"}.mdi-view-stream-outline::before{content:\"\\F1490\"}.mdi-view-week::before{content:\"\\F0576\"}.mdi-view-week-outline::before{content:\"\\F1491\"}.mdi-vimeo::before{content:\"\\F0577\"}.mdi-violin::before{content:\"\\F060F\"}.mdi-virtual-reality::before{content:\"\\F0894\"}.mdi-virus::before{content:\"\\F13B6\"}.mdi-virus-outline::before{content:\"\\F13B7\"}.mdi-vk::before{content:\"\\F0579\"}.mdi-vlc::before{content:\"\\F057C\"}.mdi-voice-off::before{content:\"\\F0ED4\"}.mdi-voicemail::before{content:\"\\F057D\"}.mdi-volleyball::before{content:\"\\F09B4\"}.mdi-volume-high::before{content:\"\\F057E\"}.mdi-volume-low::before{content:\"\\F057F\"}.mdi-volume-medium::before{content:\"\\F0580\"}.mdi-volume-minus::before{content:\"\\F075E\"}.mdi-volume-mute::before{content:\"\\F075F\"}.mdi-volume-off::before{content:\"\\F0581\"}.mdi-volume-plus::before{content:\"\\F075D\"}.mdi-volume-source::before{content:\"\\F1120\"}.mdi-volume-variant-off::before{content:\"\\F0E08\"}.mdi-volume-vibrate::before{content:\"\\F1121\"}.mdi-vote::before{content:\"\\F0A1F\"}.mdi-vote-outline::before{content:\"\\F0A20\"}.mdi-vpn::before{content:\"\\F0582\"}.mdi-vuejs::before{content:\"\\F0844\"}.mdi-vuetify::before{content:\"\\F0E6D\"}.mdi-walk::before{content:\"\\F0583\"}.mdi-wall::before{content:\"\\F07FE\"}.mdi-wall-sconce::before{content:\"\\F091C\"}.mdi-wall-sconce-flat::before{content:\"\\F091D\"}.mdi-wall-sconce-flat-variant::before{content:\"\\F041C\"}.mdi-wall-sconce-round::before{content:\"\\F0748\"}.mdi-wall-sconce-round-variant::before{content:\"\\F091E\"}.mdi-wallet::before{content:\"\\F0584\"}.mdi-wallet-giftcard::before{content:\"\\F0585\"}.mdi-wallet-membership::before{content:\"\\F0586\"}.mdi-wallet-outline::before{content:\"\\F0BDD\"}.mdi-wallet-plus::before{content:\"\\F0F8E\"}.mdi-wallet-plus-outline::before{content:\"\\F0F8F\"}.mdi-wallet-travel::before{content:\"\\F0587\"}.mdi-wallpaper::before{content:\"\\F0E09\"}.mdi-wan::before{content:\"\\F0588\"}.mdi-wardrobe::before{content:\"\\F0F90\"}.mdi-wardrobe-outline::before{content:\"\\F0F91\"}.mdi-warehouse::before{content:\"\\F0F81\"}.mdi-washing-machine::before{content:\"\\F072A\"}.mdi-washing-machine-alert::before{content:\"\\F11BC\"}.mdi-washing-machine-off::before{content:\"\\F11BD\"}.mdi-watch::before{content:\"\\F0589\"}.mdi-watch-export::before{content:\"\\F058A\"}.mdi-watch-export-variant::before{content:\"\\F0895\"}.mdi-watch-import::before{content:\"\\F058B\"}.mdi-watch-import-variant::before{content:\"\\F0896\"}.mdi-watch-variant::before{content:\"\\F0897\"}.mdi-watch-vibrate::before{content:\"\\F06B1\"}.mdi-watch-vibrate-off::before{content:\"\\F0CDA\"}.mdi-water::before{content:\"\\F058C\"}.mdi-water-alert::before{content:\"\\F1502\"}.mdi-water-alert-outline::before{content:\"\\F1503\"}.mdi-water-boiler::before{content:\"\\F0F92\"}.mdi-water-boiler-alert::before{content:\"\\F11B3\"}.mdi-water-boiler-off::before{content:\"\\F11B4\"}.mdi-water-check::before{content:\"\\F1504\"}.mdi-water-check-outline::before{content:\"\\F1505\"}.mdi-water-minus::before{content:\"\\F1506\"}.mdi-water-minus-outline::before{content:\"\\F1507\"}.mdi-water-off::before{content:\"\\F058D\"}.mdi-water-off-outline::before{content:\"\\F1508\"}.mdi-water-outline::before{content:\"\\F0E0A\"}.mdi-water-percent::before{content:\"\\F058E\"}.mdi-water-percent-alert::before{content:\"\\F1509\"}.mdi-water-plus::before{content:\"\\F150A\"}.mdi-water-plus-outline::before{content:\"\\F150B\"}.mdi-water-polo::before{content:\"\\F12A0\"}.mdi-water-pump::before{content:\"\\F058F\"}.mdi-water-pump-off::before{content:\"\\F0F93\"}.mdi-water-remove::before{content:\"\\F150C\"}.mdi-water-remove-outline::before{content:\"\\F150D\"}.mdi-water-well::before{content:\"\\F106B\"}.mdi-water-well-outline::before{content:\"\\F106C\"}.mdi-watering-can::before{content:\"\\F1481\"}.mdi-watering-can-outline::before{content:\"\\F1482\"}.mdi-watermark::before{content:\"\\F0612\"}.mdi-wave::before{content:\"\\F0F2E\"}.mdi-waveform::before{content:\"\\F147D\"}.mdi-waves::before{content:\"\\F078D\"}.mdi-waze::before{content:\"\\F0BDE\"}.mdi-weather-cloudy::before{content:\"\\F0590\"}.mdi-weather-cloudy-alert::before{content:\"\\F0F2F\"}.mdi-weather-cloudy-arrow-right::before{content:\"\\F0E6E\"}.mdi-weather-fog::before{content:\"\\F0591\"}.mdi-weather-hail::before{content:\"\\F0592\"}.mdi-weather-hazy::before{content:\"\\F0F30\"}.mdi-weather-hurricane::before{content:\"\\F0898\"}.mdi-weather-lightning::before{content:\"\\F0593\"}.mdi-weather-lightning-rainy::before{content:\"\\F067E\"}.mdi-weather-night::before{content:\"\\F0594\"}.mdi-weather-night-partly-cloudy::before{content:\"\\F0F31\"}.mdi-weather-partly-cloudy::before{content:\"\\F0595\"}.mdi-weather-partly-lightning::before{content:\"\\F0F32\"}.mdi-weather-partly-rainy::before{content:\"\\F0F33\"}.mdi-weather-partly-snowy::before{content:\"\\F0F34\"}.mdi-weather-partly-snowy-rainy::before{content:\"\\F0F35\"}.mdi-weather-pouring::before{content:\"\\F0596\"}.mdi-weather-rainy::before{content:\"\\F0597\"}.mdi-weather-snowy::before{content:\"\\F0598\"}.mdi-weather-snowy-heavy::before{content:\"\\F0F36\"}.mdi-weather-snowy-rainy::before{content:\"\\F067F\"}.mdi-weather-sunny::before{content:\"\\F0599\"}.mdi-weather-sunny-alert::before{content:\"\\F0F37\"}.mdi-weather-sunny-off::before{content:\"\\F14E4\"}.mdi-weather-sunset::before{content:\"\\F059A\"}.mdi-weather-sunset-down::before{content:\"\\F059B\"}.mdi-weather-sunset-up::before{content:\"\\F059C\"}.mdi-weather-tornado::before{content:\"\\F0F38\"}.mdi-weather-windy::before{content:\"\\F059D\"}.mdi-weather-windy-variant::before{content:\"\\F059E\"}.mdi-web::before{content:\"\\F059F\"}.mdi-web-box::before{content:\"\\F0F94\"}.mdi-web-clock::before{content:\"\\F124A\"}.mdi-webcam::before{content:\"\\F05A0\"}.mdi-webhook::before{content:\"\\F062F\"}.mdi-webpack::before{content:\"\\F072B\"}.mdi-webrtc::before{content:\"\\F1248\"}.mdi-wechat::before{content:\"\\F0611\"}.mdi-weight::before{content:\"\\F05A1\"}.mdi-weight-gram::before{content:\"\\F0D3F\"}.mdi-weight-kilogram::before{content:\"\\F05A2\"}.mdi-weight-lifter::before{content:\"\\F115D\"}.mdi-weight-pound::before{content:\"\\F09B5\"}.mdi-whatsapp::before{content:\"\\F05A3\"}.mdi-wheel-barrow::before{content:\"\\F14F2\"}.mdi-wheelchair-accessibility::before{content:\"\\F05A4\"}.mdi-whistle::before{content:\"\\F09B6\"}.mdi-whistle-outline::before{content:\"\\F12BC\"}.mdi-white-balance-auto::before{content:\"\\F05A5\"}.mdi-white-balance-incandescent::before{content:\"\\F05A6\"}.mdi-white-balance-iridescent::before{content:\"\\F05A7\"}.mdi-white-balance-sunny::before{content:\"\\F05A8\"}.mdi-widgets::before{content:\"\\F072C\"}.mdi-widgets-outline::before{content:\"\\F1355\"}.mdi-wifi::before{content:\"\\F05A9\"}.mdi-wifi-off::before{content:\"\\F05AA\"}.mdi-wifi-star::before{content:\"\\F0E0B\"}.mdi-wifi-strength-1::before{content:\"\\F091F\"}.mdi-wifi-strength-1-alert::before{content:\"\\F0920\"}.mdi-wifi-strength-1-lock::before{content:\"\\F0921\"}.mdi-wifi-strength-2::before{content:\"\\F0922\"}.mdi-wifi-strength-2-alert::before{content:\"\\F0923\"}.mdi-wifi-strength-2-lock::before{content:\"\\F0924\"}.mdi-wifi-strength-3::before{content:\"\\F0925\"}.mdi-wifi-strength-3-alert::before{content:\"\\F0926\"}.mdi-wifi-strength-3-lock::before{content:\"\\F0927\"}.mdi-wifi-strength-4::before{content:\"\\F0928\"}.mdi-wifi-strength-4-alert::before{content:\"\\F0929\"}.mdi-wifi-strength-4-lock::before{content:\"\\F092A\"}.mdi-wifi-strength-alert-outline::before{content:\"\\F092B\"}.mdi-wifi-strength-lock-outline::before{content:\"\\F092C\"}.mdi-wifi-strength-off::before{content:\"\\F092D\"}.mdi-wifi-strength-off-outline::before{content:\"\\F092E\"}.mdi-wifi-strength-outline::before{content:\"\\F092F\"}.mdi-wikipedia::before{content:\"\\F05AC\"}.mdi-wind-turbine::before{content:\"\\F0DA5\"}.mdi-window-close::before{content:\"\\F05AD\"}.mdi-window-closed::before{content:\"\\F05AE\"}.mdi-window-closed-variant::before{content:\"\\F11DB\"}.mdi-window-maximize::before{content:\"\\F05AF\"}.mdi-window-minimize::before{content:\"\\F05B0\"}.mdi-window-open::before{content:\"\\F05B1\"}.mdi-window-open-variant::before{content:\"\\F11DC\"}.mdi-window-restore::before{content:\"\\F05B2\"}.mdi-window-shutter::before{content:\"\\F111C\"}.mdi-window-shutter-alert::before{content:\"\\F111D\"}.mdi-window-shutter-open::before{content:\"\\F111E\"}.mdi-windsock::before{content:\"\\F15FA\"}.mdi-wiper::before{content:\"\\F0AE9\"}.mdi-wiper-wash::before{content:\"\\F0DA6\"}.mdi-wizard-hat::before{content:\"\\F1477\"}.mdi-wordpress::before{content:\"\\F05B4\"}.mdi-wrap::before{content:\"\\F05B6\"}.mdi-wrap-disabled::before{content:\"\\F0BDF\"}.mdi-wrench::before{content:\"\\F05B7\"}.mdi-wrench-outline::before{content:\"\\F0BE0\"}.mdi-xamarin::before{content:\"\\F0845\"}.mdi-xamarin-outline::before{content:\"\\F0846\"}.mdi-xing::before{content:\"\\F05BE\"}.mdi-xml::before{content:\"\\F05C0\"}.mdi-xmpp::before{content:\"\\F07FF\"}.mdi-y-combinator::before{content:\"\\F0624\"}.mdi-yahoo::before{content:\"\\F0B4F\"}.mdi-yeast::before{content:\"\\F05C1\"}.mdi-yin-yang::before{content:\"\\F0680\"}.mdi-yoga::before{content:\"\\F117C\"}.mdi-youtube::before{content:\"\\F05C3\"}.mdi-youtube-gaming::before{content:\"\\F0848\"}.mdi-youtube-studio::before{content:\"\\F0847\"}.mdi-youtube-subscription::before{content:\"\\F0D40\"}.mdi-youtube-tv::before{content:\"\\F0448\"}.mdi-yurt::before{content:\"\\F1516\"}.mdi-z-wave::before{content:\"\\F0AEA\"}.mdi-zend::before{content:\"\\F0AEB\"}.mdi-zigbee::before{content:\"\\F0D41\"}.mdi-zip-box::before{content:\"\\F05C4\"}.mdi-zip-box-outline::before{content:\"\\F0FFA\"}.mdi-zip-disk::before{content:\"\\F0A23\"}.mdi-zodiac-aquarius::before{content:\"\\F0A7D\"}.mdi-zodiac-aries::before{content:\"\\F0A7E\"}.mdi-zodiac-cancer::before{content:\"\\F0A7F\"}.mdi-zodiac-capricorn::before{content:\"\\F0A80\"}.mdi-zodiac-gemini::before{content:\"\\F0A81\"}.mdi-zodiac-leo::before{content:\"\\F0A82\"}.mdi-zodiac-libra::before{content:\"\\F0A83\"}.mdi-zodiac-pisces::before{content:\"\\F0A84\"}.mdi-zodiac-sagittarius::before{content:\"\\F0A85\"}.mdi-zodiac-scorpio::before{content:\"\\F0A86\"}.mdi-zodiac-taurus::before{content:\"\\F0A87\"}.mdi-zodiac-virgo::before{content:\"\\F0A88\"}.mdi-blank::before{content:\"\\F68C\";visibility:hidden}.mdi-18px.mdi-set,.mdi-18px.mdi:before{font-size:18px}.mdi-24px.mdi-set,.mdi-24px.mdi:before{font-size:24px}.mdi-36px.mdi-set,.mdi-36px.mdi:before{font-size:36px}.mdi-48px.mdi-set,.mdi-48px.mdi:before{font-size:48px}.mdi-dark:before{color:rgba(0,0,0,0.54)}.mdi-dark.mdi-inactive:before{color:rgba(0,0,0,0.26)}.mdi-light:before{color:#fff}.mdi-light.mdi-inactive:before{color:rgba(255,255,255,0.3)}.mdi-rotate-45:before{-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg)}.mdi-rotate-90:before{-webkit-transform:rotate(90deg);-ms-transform:rotate(90deg);transform:rotate(90deg)}.mdi-rotate-135:before{-webkit-transform:rotate(135deg);-ms-transform:rotate(135deg);transform:rotate(135deg)}.mdi-rotate-180:before{-webkit-transform:rotate(180deg);-ms-transform:rotate(180deg);transform:rotate(180deg)}.mdi-rotate-225:before{-webkit-transform:rotate(225deg);-ms-transform:rotate(225deg);transform:rotate(225deg)}.mdi-rotate-270:before{-webkit-transform:rotate(270deg);-ms-transform:rotate(270deg);transform:rotate(270deg)}.mdi-rotate-315:before{-webkit-transform:rotate(315deg);-ms-transform:rotate(315deg);transform:rotate(315deg)}.mdi-flip-h:before{-webkit-transform:scaleX(-1);transform:scaleX(-1);filter:FlipH;-ms-filter:\"FlipH\"}.mdi-flip-v:before{-webkit-transform:scaleY(-1);transform:scaleY(-1);filter:FlipV;-ms-filter:\"FlipV\"}.mdi-spin:before{-webkit-animation:mdi-spin 2s infinite linear;animation:mdi-spin 2s infinite linear}@-webkit-keyframes mdi-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}@keyframes mdi-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--9-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.active-menu {\r\n  color: black !important;\r\n  font-weight: bold !important;\n}\r\n/* .menu-title{\r\n  margin-left: 1.2rem;\r\n} */\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--9-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.submenu-wrapper{\r\n  margin-left: .6rem;\n}\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/lib/css-base.js":
/*!*************************************************!*\
  !*** ./node_modules/css-loader/lib/css-base.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),

/***/ "./node_modules/css-loader/lib/url/escape.js":
/*!***************************************************!*\
  !*** ./node_modules/css-loader/lib/url/escape.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function escape(url) {
    if (typeof url !== 'string') {
        return url
    }
    // If url is already wrapped in quotes, remove them
    if (/^['"].*['"]$/.test(url)) {
        url = url.slice(1, -1);
    }
    // Should url be wrapped?
    // See https://drafts.csswg.org/css-values-3/#urls
    if (/["'() \t\n]/.test(url)) {
        return '"' + url.replace(/"/g, '\\"').replace(/\n/g, '\\n') + '"'
    }

    return url
}


/***/ }),

/***/ "./node_modules/process/browser.js":
/*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/setimmediate/setImmediate.js":
/*!***************************************************!*\
  !*** ./node_modules/setimmediate/setImmediate.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global, process) {(function (global, undefined) {
    "use strict";

    if (global.setImmediate) {
        return;
    }

    var nextHandle = 1; // Spec says greater than zero
    var tasksByHandle = {};
    var currentlyRunningATask = false;
    var doc = global.document;
    var registerImmediate;

    function setImmediate(callback) {
      // Callback can either be a function or a string
      if (typeof callback !== "function") {
        callback = new Function("" + callback);
      }
      // Copy function arguments
      var args = new Array(arguments.length - 1);
      for (var i = 0; i < args.length; i++) {
          args[i] = arguments[i + 1];
      }
      // Store and register the task
      var task = { callback: callback, args: args };
      tasksByHandle[nextHandle] = task;
      registerImmediate(nextHandle);
      return nextHandle++;
    }

    function clearImmediate(handle) {
        delete tasksByHandle[handle];
    }

    function run(task) {
        var callback = task.callback;
        var args = task.args;
        switch (args.length) {
        case 0:
            callback();
            break;
        case 1:
            callback(args[0]);
            break;
        case 2:
            callback(args[0], args[1]);
            break;
        case 3:
            callback(args[0], args[1], args[2]);
            break;
        default:
            callback.apply(undefined, args);
            break;
        }
    }

    function runIfPresent(handle) {
        // From the spec: "Wait until any invocations of this algorithm started before this one have completed."
        // So if we're currently running a task, we'll need to delay this invocation.
        if (currentlyRunningATask) {
            // Delay by doing a setTimeout. setImmediate was tried instead, but in Firefox 7 it generated a
            // "too much recursion" error.
            setTimeout(runIfPresent, 0, handle);
        } else {
            var task = tasksByHandle[handle];
            if (task) {
                currentlyRunningATask = true;
                try {
                    run(task);
                } finally {
                    clearImmediate(handle);
                    currentlyRunningATask = false;
                }
            }
        }
    }

    function installNextTickImplementation() {
        registerImmediate = function(handle) {
            process.nextTick(function () { runIfPresent(handle); });
        };
    }

    function canUsePostMessage() {
        // The test against `importScripts` prevents this implementation from being installed inside a web worker,
        // where `global.postMessage` means something completely different and can't be used for this purpose.
        if (global.postMessage && !global.importScripts) {
            var postMessageIsAsynchronous = true;
            var oldOnMessage = global.onmessage;
            global.onmessage = function() {
                postMessageIsAsynchronous = false;
            };
            global.postMessage("", "*");
            global.onmessage = oldOnMessage;
            return postMessageIsAsynchronous;
        }
    }

    function installPostMessageImplementation() {
        // Installs an event handler on `global` for the `message` event: see
        // * https://developer.mozilla.org/en/DOM/window.postMessage
        // * http://www.whatwg.org/specs/web-apps/current-work/multipage/comms.html#crossDocumentMessages

        var messagePrefix = "setImmediate$" + Math.random() + "$";
        var onGlobalMessage = function(event) {
            if (event.source === global &&
                typeof event.data === "string" &&
                event.data.indexOf(messagePrefix) === 0) {
                runIfPresent(+event.data.slice(messagePrefix.length));
            }
        };

        if (global.addEventListener) {
            global.addEventListener("message", onGlobalMessage, false);
        } else {
            global.attachEvent("onmessage", onGlobalMessage);
        }

        registerImmediate = function(handle) {
            global.postMessage(messagePrefix + handle, "*");
        };
    }

    function installMessageChannelImplementation() {
        var channel = new MessageChannel();
        channel.port1.onmessage = function(event) {
            var handle = event.data;
            runIfPresent(handle);
        };

        registerImmediate = function(handle) {
            channel.port2.postMessage(handle);
        };
    }

    function installReadyStateChangeImplementation() {
        var html = doc.documentElement;
        registerImmediate = function(handle) {
            // Create a <script> element; its readystatechange event will be fired asynchronously once it is inserted
            // into the document. Do so, thus queuing up the task. Remember to clean up once it's been called.
            var script = doc.createElement("script");
            script.onreadystatechange = function () {
                runIfPresent(handle);
                script.onreadystatechange = null;
                html.removeChild(script);
                script = null;
            };
            html.appendChild(script);
        };
    }

    function installSetTimeoutImplementation() {
        registerImmediate = function(handle) {
            setTimeout(runIfPresent, 0, handle);
        };
    }

    // If supported, we should attach to the prototype of global, since that is where setTimeout et al. live.
    var attachTo = Object.getPrototypeOf && Object.getPrototypeOf(global);
    attachTo = attachTo && attachTo.setTimeout ? attachTo : global;

    // Don't get fooled by e.g. browserify environments.
    if ({}.toString.call(global.process) === "[object process]") {
        // For Node.js before 0.9
        installNextTickImplementation();

    } else if (canUsePostMessage()) {
        // For non-IE10 modern browsers
        installPostMessageImplementation();

    } else if (global.MessageChannel) {
        // For web workers, where supported
        installMessageChannelImplementation();

    } else if (doc && "onreadystatechange" in doc.createElement("script")) {
        // For IE 6–8
        installReadyStateChangeImplementation();

    } else {
        // For older browsers
        installSetTimeoutImplementation();
    }

    attachTo.setImmediate = setImmediate;
    attachTo.clearImmediate = clearImmediate;
}(typeof self === "undefined" ? typeof global === "undefined" ? this : global : self));

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js"), __webpack_require__(/*! ./../process/browser.js */ "./node_modules/process/browser.js")))

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--9-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--9-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--9-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./Menu.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--9-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--9-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--9-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--9-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./SideMenu.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/lib/addStyles.js":
/*!****************************************************!*\
  !*** ./node_modules/style-loader/lib/addStyles.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/

var stylesInDom = {};

var	memoize = function (fn) {
	var memo;

	return function () {
		if (typeof memo === "undefined") memo = fn.apply(this, arguments);
		return memo;
	};
};

var isOldIE = memoize(function () {
	// Test for IE <= 9 as proposed by Browserhacks
	// @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
	// Tests for existence of standard globals is to allow style-loader
	// to operate correctly into non-standard environments
	// @see https://github.com/webpack-contrib/style-loader/issues/177
	return window && document && document.all && !window.atob;
});

var getTarget = function (target, parent) {
  if (parent){
    return parent.querySelector(target);
  }
  return document.querySelector(target);
};

var getElement = (function (fn) {
	var memo = {};

	return function(target, parent) {
                // If passing function in options, then use it for resolve "head" element.
                // Useful for Shadow Root style i.e
                // {
                //   insertInto: function () { return document.querySelector("#foo").shadowRoot }
                // }
                if (typeof target === 'function') {
                        return target();
                }
                if (typeof memo[target] === "undefined") {
			var styleTarget = getTarget.call(this, target, parent);
			// Special case to return head of iframe instead of iframe itself
			if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
				try {
					// This will throw an exception if access to iframe is blocked
					// due to cross-origin restrictions
					styleTarget = styleTarget.contentDocument.head;
				} catch(e) {
					styleTarget = null;
				}
			}
			memo[target] = styleTarget;
		}
		return memo[target]
	};
})();

var singleton = null;
var	singletonCounter = 0;
var	stylesInsertedAtTop = [];

var	fixUrls = __webpack_require__(/*! ./urls */ "./node_modules/style-loader/lib/urls.js");

module.exports = function(list, options) {
	if (typeof DEBUG !== "undefined" && DEBUG) {
		if (typeof document !== "object") throw new Error("The style-loader cannot be used in a non-browser environment");
	}

	options = options || {};

	options.attrs = typeof options.attrs === "object" ? options.attrs : {};

	// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
	// tags it will allow on a page
	if (!options.singleton && typeof options.singleton !== "boolean") options.singleton = isOldIE();

	// By default, add <style> tags to the <head> element
        if (!options.insertInto) options.insertInto = "head";

	// By default, add <style> tags to the bottom of the target
	if (!options.insertAt) options.insertAt = "bottom";

	var styles = listToStyles(list, options);

	addStylesToDom(styles, options);

	return function update (newList) {
		var mayRemove = [];

		for (var i = 0; i < styles.length; i++) {
			var item = styles[i];
			var domStyle = stylesInDom[item.id];

			domStyle.refs--;
			mayRemove.push(domStyle);
		}

		if(newList) {
			var newStyles = listToStyles(newList, options);
			addStylesToDom(newStyles, options);
		}

		for (var i = 0; i < mayRemove.length; i++) {
			var domStyle = mayRemove[i];

			if(domStyle.refs === 0) {
				for (var j = 0; j < domStyle.parts.length; j++) domStyle.parts[j]();

				delete stylesInDom[domStyle.id];
			}
		}
	};
};

function addStylesToDom (styles, options) {
	for (var i = 0; i < styles.length; i++) {
		var item = styles[i];
		var domStyle = stylesInDom[item.id];

		if(domStyle) {
			domStyle.refs++;

			for(var j = 0; j < domStyle.parts.length; j++) {
				domStyle.parts[j](item.parts[j]);
			}

			for(; j < item.parts.length; j++) {
				domStyle.parts.push(addStyle(item.parts[j], options));
			}
		} else {
			var parts = [];

			for(var j = 0; j < item.parts.length; j++) {
				parts.push(addStyle(item.parts[j], options));
			}

			stylesInDom[item.id] = {id: item.id, refs: 1, parts: parts};
		}
	}
}

function listToStyles (list, options) {
	var styles = [];
	var newStyles = {};

	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		var id = options.base ? item[0] + options.base : item[0];
		var css = item[1];
		var media = item[2];
		var sourceMap = item[3];
		var part = {css: css, media: media, sourceMap: sourceMap};

		if(!newStyles[id]) styles.push(newStyles[id] = {id: id, parts: [part]});
		else newStyles[id].parts.push(part);
	}

	return styles;
}

function insertStyleElement (options, style) {
	var target = getElement(options.insertInto)

	if (!target) {
		throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
	}

	var lastStyleElementInsertedAtTop = stylesInsertedAtTop[stylesInsertedAtTop.length - 1];

	if (options.insertAt === "top") {
		if (!lastStyleElementInsertedAtTop) {
			target.insertBefore(style, target.firstChild);
		} else if (lastStyleElementInsertedAtTop.nextSibling) {
			target.insertBefore(style, lastStyleElementInsertedAtTop.nextSibling);
		} else {
			target.appendChild(style);
		}
		stylesInsertedAtTop.push(style);
	} else if (options.insertAt === "bottom") {
		target.appendChild(style);
	} else if (typeof options.insertAt === "object" && options.insertAt.before) {
		var nextSibling = getElement(options.insertAt.before, target);
		target.insertBefore(style, nextSibling);
	} else {
		throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
	}
}

function removeStyleElement (style) {
	if (style.parentNode === null) return false;
	style.parentNode.removeChild(style);

	var idx = stylesInsertedAtTop.indexOf(style);
	if(idx >= 0) {
		stylesInsertedAtTop.splice(idx, 1);
	}
}

function createStyleElement (options) {
	var style = document.createElement("style");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}

	if(options.attrs.nonce === undefined) {
		var nonce = getNonce();
		if (nonce) {
			options.attrs.nonce = nonce;
		}
	}

	addAttrs(style, options.attrs);
	insertStyleElement(options, style);

	return style;
}

function createLinkElement (options) {
	var link = document.createElement("link");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}
	options.attrs.rel = "stylesheet";

	addAttrs(link, options.attrs);
	insertStyleElement(options, link);

	return link;
}

function addAttrs (el, attrs) {
	Object.keys(attrs).forEach(function (key) {
		el.setAttribute(key, attrs[key]);
	});
}

function getNonce() {
	if (false) {}

	return __webpack_require__.nc;
}

function addStyle (obj, options) {
	var style, update, remove, result;

	// If a transform function was defined, run it on the css
	if (options.transform && obj.css) {
	    result = typeof options.transform === 'function'
		 ? options.transform(obj.css) 
		 : options.transform.default(obj.css);

	    if (result) {
	    	// If transform returns a value, use that instead of the original css.
	    	// This allows running runtime transformations on the css.
	    	obj.css = result;
	    } else {
	    	// If the transform function returns a falsy value, don't add this css.
	    	// This allows conditional loading of css
	    	return function() {
	    		// noop
	    	};
	    }
	}

	if (options.singleton) {
		var styleIndex = singletonCounter++;

		style = singleton || (singleton = createStyleElement(options));

		update = applyToSingletonTag.bind(null, style, styleIndex, false);
		remove = applyToSingletonTag.bind(null, style, styleIndex, true);

	} else if (
		obj.sourceMap &&
		typeof URL === "function" &&
		typeof URL.createObjectURL === "function" &&
		typeof URL.revokeObjectURL === "function" &&
		typeof Blob === "function" &&
		typeof btoa === "function"
	) {
		style = createLinkElement(options);
		update = updateLink.bind(null, style, options);
		remove = function () {
			removeStyleElement(style);

			if(style.href) URL.revokeObjectURL(style.href);
		};
	} else {
		style = createStyleElement(options);
		update = applyToTag.bind(null, style);
		remove = function () {
			removeStyleElement(style);
		};
	}

	update(obj);

	return function updateStyle (newObj) {
		if (newObj) {
			if (
				newObj.css === obj.css &&
				newObj.media === obj.media &&
				newObj.sourceMap === obj.sourceMap
			) {
				return;
			}

			update(obj = newObj);
		} else {
			remove();
		}
	};
}

var replaceText = (function () {
	var textStore = [];

	return function (index, replacement) {
		textStore[index] = replacement;

		return textStore.filter(Boolean).join('\n');
	};
})();

function applyToSingletonTag (style, index, remove, obj) {
	var css = remove ? "" : obj.css;

	if (style.styleSheet) {
		style.styleSheet.cssText = replaceText(index, css);
	} else {
		var cssNode = document.createTextNode(css);
		var childNodes = style.childNodes;

		if (childNodes[index]) style.removeChild(childNodes[index]);

		if (childNodes.length) {
			style.insertBefore(cssNode, childNodes[index]);
		} else {
			style.appendChild(cssNode);
		}
	}
}

function applyToTag (style, obj) {
	var css = obj.css;
	var media = obj.media;

	if(media) {
		style.setAttribute("media", media)
	}

	if(style.styleSheet) {
		style.styleSheet.cssText = css;
	} else {
		while(style.firstChild) {
			style.removeChild(style.firstChild);
		}

		style.appendChild(document.createTextNode(css));
	}
}

function updateLink (link, options, obj) {
	var css = obj.css;
	var sourceMap = obj.sourceMap;

	/*
		If convertToAbsoluteUrls isn't defined, but sourcemaps are enabled
		and there is no publicPath defined then lets turn convertToAbsoluteUrls
		on by default.  Otherwise default to the convertToAbsoluteUrls option
		directly
	*/
	var autoFixUrls = options.convertToAbsoluteUrls === undefined && sourceMap;

	if (options.convertToAbsoluteUrls || autoFixUrls) {
		css = fixUrls(css);
	}

	if (sourceMap) {
		// http://stackoverflow.com/a/26603875
		css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
	}

	var blob = new Blob([css], { type: "text/css" });

	var oldSrc = link.href;

	link.href = URL.createObjectURL(blob);

	if(oldSrc) URL.revokeObjectURL(oldSrc);
}


/***/ }),

/***/ "./node_modules/style-loader/lib/urls.js":
/*!***********************************************!*\
  !*** ./node_modules/style-loader/lib/urls.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {


/**
 * When source maps are enabled, `style-loader` uses a link element with a data-uri to
 * embed the css on the page. This breaks all relative urls because now they are relative to a
 * bundle instead of the current page.
 *
 * One solution is to only use full urls, but that may be impossible.
 *
 * Instead, this function "fixes" the relative urls to be absolute according to the current page location.
 *
 * A rudimentary test suite is located at `test/fixUrls.js` and can be run via the `npm test` command.
 *
 */

module.exports = function (css) {
  // get current location
  var location = typeof window !== "undefined" && window.location;

  if (!location) {
    throw new Error("fixUrls requires window.location");
  }

	// blank or null?
	if (!css || typeof css !== "string") {
	  return css;
  }

  var baseUrl = location.protocol + "//" + location.host;
  var currentDir = baseUrl + location.pathname.replace(/\/[^\/]*$/, "/");

	// convert each url(...)
	/*
	This regular expression is just a way to recursively match brackets within
	a string.

	 /url\s*\(  = Match on the word "url" with any whitespace after it and then a parens
	   (  = Start a capturing group
	     (?:  = Start a non-capturing group
	         [^)(]  = Match anything that isn't a parentheses
	         |  = OR
	         \(  = Match a start parentheses
	             (?:  = Start another non-capturing groups
	                 [^)(]+  = Match anything that isn't a parentheses
	                 |  = OR
	                 \(  = Match a start parentheses
	                     [^)(]*  = Match anything that isn't a parentheses
	                 \)  = Match a end parentheses
	             )  = End Group
              *\) = Match anything and then a close parens
          )  = Close non-capturing group
          *  = Match anything
       )  = Close capturing group
	 \)  = Match a close parens

	 /gi  = Get all matches, not the first.  Be case insensitive.
	 */
	var fixedCss = css.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function(fullMatch, origUrl) {
		// strip quotes (if they exist)
		var unquotedOrigUrl = origUrl
			.trim()
			.replace(/^"(.*)"$/, function(o, $1){ return $1; })
			.replace(/^'(.*)'$/, function(o, $1){ return $1; });

		// already a full url? no change
		if (/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(unquotedOrigUrl)) {
		  return fullMatch;
		}

		// convert the url to a full url
		var newUrl;

		if (unquotedOrigUrl.indexOf("//") === 0) {
		  	//TODO: should we add protocol?
			newUrl = unquotedOrigUrl;
		} else if (unquotedOrigUrl.indexOf("/") === 0) {
			// path should be relative to the base url
			newUrl = baseUrl + unquotedOrigUrl; // already starts with '/'
		} else {
			// path should be relative to current directory
			newUrl = currentDir + unquotedOrigUrl.replace(/^\.\//, ""); // Strip leading './'
		}

		// send back the fixed url(...)
		return "url(" + JSON.stringify(newUrl) + ")";
	});

	// send back the fixed css
	return fixedCss;
};


/***/ }),

/***/ "./node_modules/timers-browserify/main.js":
/*!************************************************!*\
  !*** ./node_modules/timers-browserify/main.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var scope = (typeof global !== "undefined" && global) ||
            (typeof self !== "undefined" && self) ||
            window;
var apply = Function.prototype.apply;

// DOM APIs, for completeness

exports.setTimeout = function() {
  return new Timeout(apply.call(setTimeout, scope, arguments), clearTimeout);
};
exports.setInterval = function() {
  return new Timeout(apply.call(setInterval, scope, arguments), clearInterval);
};
exports.clearTimeout =
exports.clearInterval = function(timeout) {
  if (timeout) {
    timeout.close();
  }
};

function Timeout(id, clearFn) {
  this._id = id;
  this._clearFn = clearFn;
}
Timeout.prototype.unref = Timeout.prototype.ref = function() {};
Timeout.prototype.close = function() {
  this._clearFn.call(scope, this._id);
};

// Does not start the time, just sets up the members needed.
exports.enroll = function(item, msecs) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = msecs;
};

exports.unenroll = function(item) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = -1;
};

exports._unrefActive = exports.active = function(item) {
  clearTimeout(item._idleTimeoutId);

  var msecs = item._idleTimeout;
  if (msecs >= 0) {
    item._idleTimeoutId = setTimeout(function onTimeout() {
      if (item._onTimeout)
        item._onTimeout();
    }, msecs);
  }
};

// setimmediate attaches itself to the global object
__webpack_require__(/*! setimmediate */ "./node_modules/setimmediate/setImmediate.js");
// On some exotic environments, it's not clear which object `setimmediate` was
// able to install onto.  Search each possibility in the same order as the
// `setimmediate` library.
exports.setImmediate = (typeof self !== "undefined" && self.setImmediate) ||
                       (typeof global !== "undefined" && global.setImmediate) ||
                       (this && this.setImmediate);
exports.clearImmediate = (typeof self !== "undefined" && self.clearImmediate) ||
                         (typeof global !== "undefined" && global.clearImmediate) ||
                         (this && this.clearImmediate);

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/url-pattern/lib/url-pattern.js":
/*!*****************************************************!*\
  !*** ./node_modules/url-pattern/lib/url-pattern.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;// Generated by CoffeeScript 1.10.0
var slice = [].slice;

(function(root, factory) {
  if (( true) && (__webpack_require__(/*! !webpack amd options */ "./node_modules/webpack/buildin/amd-options.js") != null)) {
    return !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else if ( true && exports !== null) {
    return module.exports = factory();
  } else {
    return root.UrlPattern = factory();
  }
})(this, function() {
  var P, UrlPattern, astNodeContainsSegmentsForProvidedParams, astNodeToNames, astNodeToRegexString, baseAstNodeToRegexString, concatMap, defaultOptions, escapeForRegex, getParam, keysAndValuesToObject, newParser, regexGroupCount, stringConcatMap, stringify;
  escapeForRegex = function(string) {
    return string.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
  };
  concatMap = function(array, f) {
    var i, length, results;
    results = [];
    i = -1;
    length = array.length;
    while (++i < length) {
      results = results.concat(f(array[i]));
    }
    return results;
  };
  stringConcatMap = function(array, f) {
    var i, length, result;
    result = '';
    i = -1;
    length = array.length;
    while (++i < length) {
      result += f(array[i]);
    }
    return result;
  };
  regexGroupCount = function(regex) {
    return (new RegExp(regex.toString() + '|')).exec('').length - 1;
  };
  keysAndValuesToObject = function(keys, values) {
    var i, key, length, object, value;
    object = {};
    i = -1;
    length = keys.length;
    while (++i < length) {
      key = keys[i];
      value = values[i];
      if (value == null) {
        continue;
      }
      if (object[key] != null) {
        if (!Array.isArray(object[key])) {
          object[key] = [object[key]];
        }
        object[key].push(value);
      } else {
        object[key] = value;
      }
    }
    return object;
  };
  P = {};
  P.Result = function(value, rest) {
    this.value = value;
    this.rest = rest;
  };
  P.Tagged = function(tag, value) {
    this.tag = tag;
    this.value = value;
  };
  P.tag = function(tag, parser) {
    return function(input) {
      var result, tagged;
      result = parser(input);
      if (result == null) {
        return;
      }
      tagged = new P.Tagged(tag, result.value);
      return new P.Result(tagged, result.rest);
    };
  };
  P.regex = function(regex) {
    return function(input) {
      var matches, result;
      matches = regex.exec(input);
      if (matches == null) {
        return;
      }
      result = matches[0];
      return new P.Result(result, input.slice(result.length));
    };
  };
  P.sequence = function() {
    var parsers;
    parsers = 1 <= arguments.length ? slice.call(arguments, 0) : [];
    return function(input) {
      var i, length, parser, rest, result, values;
      i = -1;
      length = parsers.length;
      values = [];
      rest = input;
      while (++i < length) {
        parser = parsers[i];
        result = parser(rest);
        if (result == null) {
          return;
        }
        values.push(result.value);
        rest = result.rest;
      }
      return new P.Result(values, rest);
    };
  };
  P.pick = function() {
    var indexes, parsers;
    indexes = arguments[0], parsers = 2 <= arguments.length ? slice.call(arguments, 1) : [];
    return function(input) {
      var array, result;
      result = P.sequence.apply(P, parsers)(input);
      if (result == null) {
        return;
      }
      array = result.value;
      result.value = array[indexes];
      return result;
    };
  };
  P.string = function(string) {
    var length;
    length = string.length;
    return function(input) {
      if (input.slice(0, length) === string) {
        return new P.Result(string, input.slice(length));
      }
    };
  };
  P.lazy = function(fn) {
    var cached;
    cached = null;
    return function(input) {
      if (cached == null) {
        cached = fn();
      }
      return cached(input);
    };
  };
  P.baseMany = function(parser, end, stringResult, atLeastOneResultRequired, input) {
    var endResult, parserResult, rest, results;
    rest = input;
    results = stringResult ? '' : [];
    while (true) {
      if (end != null) {
        endResult = end(rest);
        if (endResult != null) {
          break;
        }
      }
      parserResult = parser(rest);
      if (parserResult == null) {
        break;
      }
      if (stringResult) {
        results += parserResult.value;
      } else {
        results.push(parserResult.value);
      }
      rest = parserResult.rest;
    }
    if (atLeastOneResultRequired && results.length === 0) {
      return;
    }
    return new P.Result(results, rest);
  };
  P.many1 = function(parser) {
    return function(input) {
      return P.baseMany(parser, null, false, true, input);
    };
  };
  P.concatMany1Till = function(parser, end) {
    return function(input) {
      return P.baseMany(parser, end, true, true, input);
    };
  };
  P.firstChoice = function() {
    var parsers;
    parsers = 1 <= arguments.length ? slice.call(arguments, 0) : [];
    return function(input) {
      var i, length, parser, result;
      i = -1;
      length = parsers.length;
      while (++i < length) {
        parser = parsers[i];
        result = parser(input);
        if (result != null) {
          return result;
        }
      }
    };
  };
  newParser = function(options) {
    var U;
    U = {};
    U.wildcard = P.tag('wildcard', P.string(options.wildcardChar));
    U.optional = P.tag('optional', P.pick(1, P.string(options.optionalSegmentStartChar), P.lazy(function() {
      return U.pattern;
    }), P.string(options.optionalSegmentEndChar)));
    U.name = P.regex(new RegExp("^[" + options.segmentNameCharset + "]+"));
    U.named = P.tag('named', P.pick(1, P.string(options.segmentNameStartChar), P.lazy(function() {
      return U.name;
    })));
    U.escapedChar = P.pick(1, P.string(options.escapeChar), P.regex(/^./));
    U["static"] = P.tag('static', P.concatMany1Till(P.firstChoice(P.lazy(function() {
      return U.escapedChar;
    }), P.regex(/^./)), P.firstChoice(P.string(options.segmentNameStartChar), P.string(options.optionalSegmentStartChar), P.string(options.optionalSegmentEndChar), U.wildcard)));
    U.token = P.lazy(function() {
      return P.firstChoice(U.wildcard, U.optional, U.named, U["static"]);
    });
    U.pattern = P.many1(P.lazy(function() {
      return U.token;
    }));
    return U;
  };
  defaultOptions = {
    escapeChar: '\\',
    segmentNameStartChar: ':',
    segmentValueCharset: 'a-zA-Z0-9-_~ %',
    segmentNameCharset: 'a-zA-Z0-9',
    optionalSegmentStartChar: '(',
    optionalSegmentEndChar: ')',
    wildcardChar: '*'
  };
  baseAstNodeToRegexString = function(astNode, segmentValueCharset) {
    if (Array.isArray(astNode)) {
      return stringConcatMap(astNode, function(node) {
        return baseAstNodeToRegexString(node, segmentValueCharset);
      });
    }
    switch (astNode.tag) {
      case 'wildcard':
        return '(.*?)';
      case 'named':
        return "([" + segmentValueCharset + "]+)";
      case 'static':
        return escapeForRegex(astNode.value);
      case 'optional':
        return '(?:' + baseAstNodeToRegexString(astNode.value, segmentValueCharset) + ')?';
    }
  };
  astNodeToRegexString = function(astNode, segmentValueCharset) {
    if (segmentValueCharset == null) {
      segmentValueCharset = defaultOptions.segmentValueCharset;
    }
    return '^' + baseAstNodeToRegexString(astNode, segmentValueCharset) + '$';
  };
  astNodeToNames = function(astNode) {
    if (Array.isArray(astNode)) {
      return concatMap(astNode, astNodeToNames);
    }
    switch (astNode.tag) {
      case 'wildcard':
        return ['_'];
      case 'named':
        return [astNode.value];
      case 'static':
        return [];
      case 'optional':
        return astNodeToNames(astNode.value);
    }
  };
  getParam = function(params, key, nextIndexes, sideEffects) {
    var index, maxIndex, result, value;
    if (sideEffects == null) {
      sideEffects = false;
    }
    value = params[key];
    if (value == null) {
      if (sideEffects) {
        throw new Error("no values provided for key `" + key + "`");
      } else {
        return;
      }
    }
    index = nextIndexes[key] || 0;
    maxIndex = Array.isArray(value) ? value.length - 1 : 0;
    if (index > maxIndex) {
      if (sideEffects) {
        throw new Error("too few values provided for key `" + key + "`");
      } else {
        return;
      }
    }
    result = Array.isArray(value) ? value[index] : value;
    if (sideEffects) {
      nextIndexes[key] = index + 1;
    }
    return result;
  };
  astNodeContainsSegmentsForProvidedParams = function(astNode, params, nextIndexes) {
    var i, length;
    if (Array.isArray(astNode)) {
      i = -1;
      length = astNode.length;
      while (++i < length) {
        if (astNodeContainsSegmentsForProvidedParams(astNode[i], params, nextIndexes)) {
          return true;
        }
      }
      return false;
    }
    switch (astNode.tag) {
      case 'wildcard':
        return getParam(params, '_', nextIndexes, false) != null;
      case 'named':
        return getParam(params, astNode.value, nextIndexes, false) != null;
      case 'static':
        return false;
      case 'optional':
        return astNodeContainsSegmentsForProvidedParams(astNode.value, params, nextIndexes);
    }
  };
  stringify = function(astNode, params, nextIndexes) {
    if (Array.isArray(astNode)) {
      return stringConcatMap(astNode, function(node) {
        return stringify(node, params, nextIndexes);
      });
    }
    switch (astNode.tag) {
      case 'wildcard':
        return getParam(params, '_', nextIndexes, true);
      case 'named':
        return getParam(params, astNode.value, nextIndexes, true);
      case 'static':
        return astNode.value;
      case 'optional':
        if (astNodeContainsSegmentsForProvidedParams(astNode.value, params, nextIndexes)) {
          return stringify(astNode.value, params, nextIndexes);
        } else {
          return '';
        }
    }
  };
  UrlPattern = function(arg1, arg2) {
    var groupCount, options, parsed, parser, withoutWhitespace;
    if (arg1 instanceof UrlPattern) {
      this.isRegex = arg1.isRegex;
      this.regex = arg1.regex;
      this.ast = arg1.ast;
      this.names = arg1.names;
      return;
    }
    this.isRegex = arg1 instanceof RegExp;
    if (!(('string' === typeof arg1) || this.isRegex)) {
      throw new TypeError('argument must be a regex or a string');
    }
    if (this.isRegex) {
      this.regex = arg1;
      if (arg2 != null) {
        if (!Array.isArray(arg2)) {
          throw new Error('if first argument is a regex the second argument may be an array of group names but you provided something else');
        }
        groupCount = regexGroupCount(this.regex);
        if (arg2.length !== groupCount) {
          throw new Error("regex contains " + groupCount + " groups but array of group names contains " + arg2.length);
        }
        this.names = arg2;
      }
      return;
    }
    if (arg1 === '') {
      throw new Error('argument must not be the empty string');
    }
    withoutWhitespace = arg1.replace(/\s+/g, '');
    if (withoutWhitespace !== arg1) {
      throw new Error('argument must not contain whitespace');
    }
    options = {
      escapeChar: (arg2 != null ? arg2.escapeChar : void 0) || defaultOptions.escapeChar,
      segmentNameStartChar: (arg2 != null ? arg2.segmentNameStartChar : void 0) || defaultOptions.segmentNameStartChar,
      segmentNameCharset: (arg2 != null ? arg2.segmentNameCharset : void 0) || defaultOptions.segmentNameCharset,
      segmentValueCharset: (arg2 != null ? arg2.segmentValueCharset : void 0) || defaultOptions.segmentValueCharset,
      optionalSegmentStartChar: (arg2 != null ? arg2.optionalSegmentStartChar : void 0) || defaultOptions.optionalSegmentStartChar,
      optionalSegmentEndChar: (arg2 != null ? arg2.optionalSegmentEndChar : void 0) || defaultOptions.optionalSegmentEndChar,
      wildcardChar: (arg2 != null ? arg2.wildcardChar : void 0) || defaultOptions.wildcardChar
    };
    parser = newParser(options);
    parsed = parser.pattern(arg1);
    if (parsed == null) {
      throw new Error("couldn't parse pattern");
    }
    if (parsed.rest !== '') {
      throw new Error("could only partially parse pattern");
    }
    this.ast = parsed.value;
    this.regex = new RegExp(astNodeToRegexString(this.ast, options.segmentValueCharset));
    this.names = astNodeToNames(this.ast);
  };
  UrlPattern.prototype.match = function(url) {
    var groups, match;
    match = this.regex.exec(url);
    if (match == null) {
      return null;
    }
    groups = match.slice(1);
    if (this.names) {
      return keysAndValuesToObject(this.names, groups);
    } else {
      return groups;
    }
  };
  UrlPattern.prototype.stringify = function(params) {
    if (params == null) {
      params = {};
    }
    if (this.isRegex) {
      throw new Error("can't stringify patterns generated from a regex");
    }
    if (params !== Object(params)) {
      throw new Error("argument must be an object or undefined");
    }
    return stringify(this.ast, params, {});
  };
  UrlPattern.escapeForRegex = escapeForRegex;
  UrlPattern.concatMap = concatMap;
  UrlPattern.stringConcatMap = stringConcatMap;
  UrlPattern.regexGroupCount = regexGroupCount;
  UrlPattern.keysAndValuesToObject = keysAndValuesToObject;
  UrlPattern.P = P;
  UrlPattern.newParser = newParser;
  UrlPattern.defaultOptions = defaultOptions;
  UrlPattern.astNodeToRegexString = astNodeToRegexString;
  UrlPattern.astNodeToNames = astNodeToNames;
  UrlPattern.getParam = getParam;
  UrlPattern.astNodeContainsSegmentsForProvidedParams = astNodeContainsSegmentsForProvidedParams;
  UrlPattern.stringify = stringify;
  return UrlPattern;
});


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=template&id=10032bb0&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/Menu.vue?vue&type=template&id=10032bb0& ***!
  \************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "li",
    { staticClass: "nav-item", style: _vm.styleItem(_vm.menuAll) },
    [
      _vm.subMenus
        ? _c(
            "a",
            {
              class: _vm.checkMenu(_vm.url) + " nav-dropdown",
              attrs: {
                "data-toggle": "collapse",
                href: "#menu_" + _vm.menu_id,
                "aria-expanded": "false",
                "aria-controls": "ui-basic"
              }
            },
            [
              _c("span", { staticClass: "menu-title" }, [
                _vm._v(_vm._s(_vm.name))
              ]),
              _vm._v(" "),
              _c("i", { staticClass: "menu-arrow" })
            ]
          )
        : _c(
            "a",
            {
              class: _vm.checkMenu(_vm.url),
              attrs: { href: _vm.url, target: _vm.target == 1 ? true : false }
            },
            [
              _c("span", { staticClass: "menu-title" }, [
                _vm._v(_vm._s(_vm.name))
              ])
            ]
          ),
      _vm._v(" "),
      _vm.subMenus
        ? _c(
            "div",
            {
              class: _vm.checkCollapse(_vm.menuAll),
              attrs: { id: "menu_" + _vm.menu_id }
            },
            [
              _c(
                "ul",
                { staticClass: "nav flex-column sub-menu" },
                _vm._l(_vm.subMenus, function(menu) {
                  return _c("Menu", {
                    key: menu.menu_id,
                    attrs: {
                      name: menu.menu_name,
                      url: menu.route,
                      status: menu.status,
                      visible_menu: menu.visible,
                      menu_id: menu.menu_id,
                      icon: menu.icon,
                      subMenus: menu["child"],
                      parent_id: menu.parent_id,
                      current_route: _vm.current_route,
                      target: menu.target_blank,
                      menuAll: menu
                    }
                  })
                }),
                1
              )
            ]
          )
        : _vm._e()
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=template&id=5985241f&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/sites/side_menu/SideMenu.vue?vue&type=template&id=5985241f& ***!
  \****************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    _vm._l(_vm.sortJson, function(menu1) {
      return _c(
        "li",
        { key: menu1.menu_id, staticClass: "nav-item nav-item-first" },
        [
          _c("div", { staticClass: "sidebar-menu-title" }, [
            menu1.icon !== "#" || menu1.icon !== "-"
              ? _c("i", {
                  class: menu1.icon,
                  staticStyle: { "font-size": "1.4rem" }
                })
              : _vm._e(),
            _vm._v("  \n      "),
            _c("span", [_vm._v(_vm._s(menu1.menu_name))])
          ]),
          _vm._v(" "),
          menu1.child
            ? _c(
                "ul",
                { staticClass: "nav submenu-wrapper" },
                _vm._l(menu1.child, function(menu) {
                  return _c("Menu", {
                    key: menu.menu_id,
                    attrs: {
                      name: menu.menu_name,
                      url: menu.route,
                      status: menu.status,
                      visible_menu: menu.visible,
                      menu_id: menu.menu_id,
                      icon: menu.icon,
                      subMenus: menu["child"],
                      menuAll: menu,
                      parent_id: menu.parent_id,
                      current_route: _vm.current_route,
                      target: menu.target_blank
                    }
                  })
                }),
                1
              )
            : _vm._e()
        ]
      )
    }),
    0
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./node_modules/vue/dist/vue.common.dev.js":
/*!*************************************************!*\
  !*** ./node_modules/vue/dist/vue.common.dev.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(global, setImmediate) {/*!
 * Vue.js v2.6.12
 * (c) 2014-2020 Evan You
 * Released under the MIT License.
 */


/*  */

var emptyObject = Object.freeze({});

// These helpers produce better VM code in JS engines due to their
// explicitness and function inlining.
function isUndef (v) {
  return v === undefined || v === null
}

function isDef (v) {
  return v !== undefined && v !== null
}

function isTrue (v) {
  return v === true
}

function isFalse (v) {
  return v === false
}

/**
 * Check if value is primitive.
 */
function isPrimitive (value) {
  return (
    typeof value === 'string' ||
    typeof value === 'number' ||
    // $flow-disable-line
    typeof value === 'symbol' ||
    typeof value === 'boolean'
  )
}

/**
 * Quick object check - this is primarily used to tell
 * Objects from primitive values when we know the value
 * is a JSON-compliant type.
 */
function isObject (obj) {
  return obj !== null && typeof obj === 'object'
}

/**
 * Get the raw type string of a value, e.g., [object Object].
 */
var _toString = Object.prototype.toString;

function toRawType (value) {
  return _toString.call(value).slice(8, -1)
}

/**
 * Strict object type check. Only returns true
 * for plain JavaScript objects.
 */
function isPlainObject (obj) {
  return _toString.call(obj) === '[object Object]'
}

function isRegExp (v) {
  return _toString.call(v) === '[object RegExp]'
}

/**
 * Check if val is a valid array index.
 */
function isValidArrayIndex (val) {
  var n = parseFloat(String(val));
  return n >= 0 && Math.floor(n) === n && isFinite(val)
}

function isPromise (val) {
  return (
    isDef(val) &&
    typeof val.then === 'function' &&
    typeof val.catch === 'function'
  )
}

/**
 * Convert a value to a string that is actually rendered.
 */
function toString (val) {
  return val == null
    ? ''
    : Array.isArray(val) || (isPlainObject(val) && val.toString === _toString)
      ? JSON.stringify(val, null, 2)
      : String(val)
}

/**
 * Convert an input value to a number for persistence.
 * If the conversion fails, return original string.
 */
function toNumber (val) {
  var n = parseFloat(val);
  return isNaN(n) ? val : n
}

/**
 * Make a map and return a function for checking if a key
 * is in that map.
 */
function makeMap (
  str,
  expectsLowerCase
) {
  var map = Object.create(null);
  var list = str.split(',');
  for (var i = 0; i < list.length; i++) {
    map[list[i]] = true;
  }
  return expectsLowerCase
    ? function (val) { return map[val.toLowerCase()]; }
    : function (val) { return map[val]; }
}

/**
 * Check if a tag is a built-in tag.
 */
var isBuiltInTag = makeMap('slot,component', true);

/**
 * Check if an attribute is a reserved attribute.
 */
var isReservedAttribute = makeMap('key,ref,slot,slot-scope,is');

/**
 * Remove an item from an array.
 */
function remove (arr, item) {
  if (arr.length) {
    var index = arr.indexOf(item);
    if (index > -1) {
      return arr.splice(index, 1)
    }
  }
}

/**
 * Check whether an object has the property.
 */
var hasOwnProperty = Object.prototype.hasOwnProperty;
function hasOwn (obj, key) {
  return hasOwnProperty.call(obj, key)
}

/**
 * Create a cached version of a pure function.
 */
function cached (fn) {
  var cache = Object.create(null);
  return (function cachedFn (str) {
    var hit = cache[str];
    return hit || (cache[str] = fn(str))
  })
}

/**
 * Camelize a hyphen-delimited string.
 */
var camelizeRE = /-(\w)/g;
var camelize = cached(function (str) {
  return str.replace(camelizeRE, function (_, c) { return c ? c.toUpperCase() : ''; })
});

/**
 * Capitalize a string.
 */
var capitalize = cached(function (str) {
  return str.charAt(0).toUpperCase() + str.slice(1)
});

/**
 * Hyphenate a camelCase string.
 */
var hyphenateRE = /\B([A-Z])/g;
var hyphenate = cached(function (str) {
  return str.replace(hyphenateRE, '-$1').toLowerCase()
});

/**
 * Simple bind polyfill for environments that do not support it,
 * e.g., PhantomJS 1.x. Technically, we don't need this anymore
 * since native bind is now performant enough in most browsers.
 * But removing it would mean breaking code that was able to run in
 * PhantomJS 1.x, so this must be kept for backward compatibility.
 */

/* istanbul ignore next */
function polyfillBind (fn, ctx) {
  function boundFn (a) {
    var l = arguments.length;
    return l
      ? l > 1
        ? fn.apply(ctx, arguments)
        : fn.call(ctx, a)
      : fn.call(ctx)
  }

  boundFn._length = fn.length;
  return boundFn
}

function nativeBind (fn, ctx) {
  return fn.bind(ctx)
}

var bind = Function.prototype.bind
  ? nativeBind
  : polyfillBind;

/**
 * Convert an Array-like object to a real Array.
 */
function toArray (list, start) {
  start = start || 0;
  var i = list.length - start;
  var ret = new Array(i);
  while (i--) {
    ret[i] = list[i + start];
  }
  return ret
}

/**
 * Mix properties into target object.
 */
function extend (to, _from) {
  for (var key in _from) {
    to[key] = _from[key];
  }
  return to
}

/**
 * Merge an Array of Objects into a single Object.
 */
function toObject (arr) {
  var res = {};
  for (var i = 0; i < arr.length; i++) {
    if (arr[i]) {
      extend(res, arr[i]);
    }
  }
  return res
}

/* eslint-disable no-unused-vars */

/**
 * Perform no operation.
 * Stubbing args to make Flow happy without leaving useless transpiled code
 * with ...rest (https://flow.org/blog/2017/05/07/Strict-Function-Call-Arity/).
 */
function noop (a, b, c) {}

/**
 * Always return false.
 */
var no = function (a, b, c) { return false; };

/* eslint-enable no-unused-vars */

/**
 * Return the same value.
 */
var identity = function (_) { return _; };

/**
 * Generate a string containing static keys from compiler modules.
 */
function genStaticKeys (modules) {
  return modules.reduce(function (keys, m) {
    return keys.concat(m.staticKeys || [])
  }, []).join(',')
}

/**
 * Check if two values are loosely equal - that is,
 * if they are plain objects, do they have the same shape?
 */
function looseEqual (a, b) {
  if (a === b) { return true }
  var isObjectA = isObject(a);
  var isObjectB = isObject(b);
  if (isObjectA && isObjectB) {
    try {
      var isArrayA = Array.isArray(a);
      var isArrayB = Array.isArray(b);
      if (isArrayA && isArrayB) {
        return a.length === b.length && a.every(function (e, i) {
          return looseEqual(e, b[i])
        })
      } else if (a instanceof Date && b instanceof Date) {
        return a.getTime() === b.getTime()
      } else if (!isArrayA && !isArrayB) {
        var keysA = Object.keys(a);
        var keysB = Object.keys(b);
        return keysA.length === keysB.length && keysA.every(function (key) {
          return looseEqual(a[key], b[key])
        })
      } else {
        /* istanbul ignore next */
        return false
      }
    } catch (e) {
      /* istanbul ignore next */
      return false
    }
  } else if (!isObjectA && !isObjectB) {
    return String(a) === String(b)
  } else {
    return false
  }
}

/**
 * Return the first index at which a loosely equal value can be
 * found in the array (if value is a plain object, the array must
 * contain an object of the same shape), or -1 if it is not present.
 */
function looseIndexOf (arr, val) {
  for (var i = 0; i < arr.length; i++) {
    if (looseEqual(arr[i], val)) { return i }
  }
  return -1
}

/**
 * Ensure a function is called only once.
 */
function once (fn) {
  var called = false;
  return function () {
    if (!called) {
      called = true;
      fn.apply(this, arguments);
    }
  }
}

var SSR_ATTR = 'data-server-rendered';

var ASSET_TYPES = [
  'component',
  'directive',
  'filter'
];

var LIFECYCLE_HOOKS = [
  'beforeCreate',
  'created',
  'beforeMount',
  'mounted',
  'beforeUpdate',
  'updated',
  'beforeDestroy',
  'destroyed',
  'activated',
  'deactivated',
  'errorCaptured',
  'serverPrefetch'
];

/*  */



var config = ({
  /**
   * Option merge strategies (used in core/util/options)
   */
  // $flow-disable-line
  optionMergeStrategies: Object.create(null),

  /**
   * Whether to suppress warnings.
   */
  silent: false,

  /**
   * Show production mode tip message on boot?
   */
  productionTip: "development" !== 'production',

  /**
   * Whether to enable devtools
   */
  devtools: "development" !== 'production',

  /**
   * Whether to record perf
   */
  performance: false,

  /**
   * Error handler for watcher errors
   */
  errorHandler: null,

  /**
   * Warn handler for watcher warns
   */
  warnHandler: null,

  /**
   * Ignore certain custom elements
   */
  ignoredElements: [],

  /**
   * Custom user key aliases for v-on
   */
  // $flow-disable-line
  keyCodes: Object.create(null),

  /**
   * Check if a tag is reserved so that it cannot be registered as a
   * component. This is platform-dependent and may be overwritten.
   */
  isReservedTag: no,

  /**
   * Check if an attribute is reserved so that it cannot be used as a component
   * prop. This is platform-dependent and may be overwritten.
   */
  isReservedAttr: no,

  /**
   * Check if a tag is an unknown element.
   * Platform-dependent.
   */
  isUnknownElement: no,

  /**
   * Get the namespace of an element
   */
  getTagNamespace: noop,

  /**
   * Parse the real tag name for the specific platform.
   */
  parsePlatformTagName: identity,

  /**
   * Check if an attribute must be bound using property, e.g. value
   * Platform-dependent.
   */
  mustUseProp: no,

  /**
   * Perform updates asynchronously. Intended to be used by Vue Test Utils
   * This will significantly reduce performance if set to false.
   */
  async: true,

  /**
   * Exposed for legacy reasons
   */
  _lifecycleHooks: LIFECYCLE_HOOKS
});

/*  */

/**
 * unicode letters used for parsing html tags, component names and property paths.
 * using https://www.w3.org/TR/html53/semantics-scripting.html#potentialcustomelementname
 * skipping \u10000-\uEFFFF due to it freezing up PhantomJS
 */
var unicodeRegExp = /a-zA-Z\u00B7\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u037D\u037F-\u1FFF\u200C-\u200D\u203F-\u2040\u2070-\u218F\u2C00-\u2FEF\u3001-\uD7FF\uF900-\uFDCF\uFDF0-\uFFFD/;

/**
 * Check if a string starts with $ or _
 */
function isReserved (str) {
  var c = (str + '').charCodeAt(0);
  return c === 0x24 || c === 0x5F
}

/**
 * Define a property.
 */
function def (obj, key, val, enumerable) {
  Object.defineProperty(obj, key, {
    value: val,
    enumerable: !!enumerable,
    writable: true,
    configurable: true
  });
}

/**
 * Parse simple path.
 */
var bailRE = new RegExp(("[^" + (unicodeRegExp.source) + ".$_\\d]"));
function parsePath (path) {
  if (bailRE.test(path)) {
    return
  }
  var segments = path.split('.');
  return function (obj) {
    for (var i = 0; i < segments.length; i++) {
      if (!obj) { return }
      obj = obj[segments[i]];
    }
    return obj
  }
}

/*  */

// can we use __proto__?
var hasProto = '__proto__' in {};

// Browser environment sniffing
var inBrowser = typeof window !== 'undefined';
var inWeex = typeof WXEnvironment !== 'undefined' && !!WXEnvironment.platform;
var weexPlatform = inWeex && WXEnvironment.platform.toLowerCase();
var UA = inBrowser && window.navigator.userAgent.toLowerCase();
var isIE = UA && /msie|trident/.test(UA);
var isIE9 = UA && UA.indexOf('msie 9.0') > 0;
var isEdge = UA && UA.indexOf('edge/') > 0;
var isAndroid = (UA && UA.indexOf('android') > 0) || (weexPlatform === 'android');
var isIOS = (UA && /iphone|ipad|ipod|ios/.test(UA)) || (weexPlatform === 'ios');
var isChrome = UA && /chrome\/\d+/.test(UA) && !isEdge;
var isPhantomJS = UA && /phantomjs/.test(UA);
var isFF = UA && UA.match(/firefox\/(\d+)/);

// Firefox has a "watch" function on Object.prototype...
var nativeWatch = ({}).watch;

var supportsPassive = false;
if (inBrowser) {
  try {
    var opts = {};
    Object.defineProperty(opts, 'passive', ({
      get: function get () {
        /* istanbul ignore next */
        supportsPassive = true;
      }
    })); // https://github.com/facebook/flow/issues/285
    window.addEventListener('test-passive', null, opts);
  } catch (e) {}
}

// this needs to be lazy-evaled because vue may be required before
// vue-server-renderer can set VUE_ENV
var _isServer;
var isServerRendering = function () {
  if (_isServer === undefined) {
    /* istanbul ignore if */
    if (!inBrowser && !inWeex && typeof global !== 'undefined') {
      // detect presence of vue-server-renderer and avoid
      // Webpack shimming the process
      _isServer = global['process'] && global['process'].env.VUE_ENV === 'server';
    } else {
      _isServer = false;
    }
  }
  return _isServer
};

// detect devtools
var devtools = inBrowser && window.__VUE_DEVTOOLS_GLOBAL_HOOK__;

/* istanbul ignore next */
function isNative (Ctor) {
  return typeof Ctor === 'function' && /native code/.test(Ctor.toString())
}

var hasSymbol =
  typeof Symbol !== 'undefined' && isNative(Symbol) &&
  typeof Reflect !== 'undefined' && isNative(Reflect.ownKeys);

var _Set;
/* istanbul ignore if */ // $flow-disable-line
if (typeof Set !== 'undefined' && isNative(Set)) {
  // use native Set when available.
  _Set = Set;
} else {
  // a non-standard Set polyfill that only works with primitive keys.
  _Set = /*@__PURE__*/(function () {
    function Set () {
      this.set = Object.create(null);
    }
    Set.prototype.has = function has (key) {
      return this.set[key] === true
    };
    Set.prototype.add = function add (key) {
      this.set[key] = true;
    };
    Set.prototype.clear = function clear () {
      this.set = Object.create(null);
    };

    return Set;
  }());
}

/*  */

var warn = noop;
var tip = noop;
var generateComponentTrace = (noop); // work around flow check
var formatComponentName = (noop);

{
  var hasConsole = typeof console !== 'undefined';
  var classifyRE = /(?:^|[-_])(\w)/g;
  var classify = function (str) { return str
    .replace(classifyRE, function (c) { return c.toUpperCase(); })
    .replace(/[-_]/g, ''); };

  warn = function (msg, vm) {
    var trace = vm ? generateComponentTrace(vm) : '';

    if (config.warnHandler) {
      config.warnHandler.call(null, msg, vm, trace);
    } else if (hasConsole && (!config.silent)) {
      console.error(("[Vue warn]: " + msg + trace));
    }
  };

  tip = function (msg, vm) {
    if (hasConsole && (!config.silent)) {
      console.warn("[Vue tip]: " + msg + (
        vm ? generateComponentTrace(vm) : ''
      ));
    }
  };

  formatComponentName = function (vm, includeFile) {
    if (vm.$root === vm) {
      return '<Root>'
    }
    var options = typeof vm === 'function' && vm.cid != null
      ? vm.options
      : vm._isVue
        ? vm.$options || vm.constructor.options
        : vm;
    var name = options.name || options._componentTag;
    var file = options.__file;
    if (!name && file) {
      var match = file.match(/([^/\\]+)\.vue$/);
      name = match && match[1];
    }

    return (
      (name ? ("<" + (classify(name)) + ">") : "<Anonymous>") +
      (file && includeFile !== false ? (" at " + file) : '')
    )
  };

  var repeat = function (str, n) {
    var res = '';
    while (n) {
      if (n % 2 === 1) { res += str; }
      if (n > 1) { str += str; }
      n >>= 1;
    }
    return res
  };

  generateComponentTrace = function (vm) {
    if (vm._isVue && vm.$parent) {
      var tree = [];
      var currentRecursiveSequence = 0;
      while (vm) {
        if (tree.length > 0) {
          var last = tree[tree.length - 1];
          if (last.constructor === vm.constructor) {
            currentRecursiveSequence++;
            vm = vm.$parent;
            continue
          } else if (currentRecursiveSequence > 0) {
            tree[tree.length - 1] = [last, currentRecursiveSequence];
            currentRecursiveSequence = 0;
          }
        }
        tree.push(vm);
        vm = vm.$parent;
      }
      return '\n\nfound in\n\n' + tree
        .map(function (vm, i) { return ("" + (i === 0 ? '---> ' : repeat(' ', 5 + i * 2)) + (Array.isArray(vm)
            ? ((formatComponentName(vm[0])) + "... (" + (vm[1]) + " recursive calls)")
            : formatComponentName(vm))); })
        .join('\n')
    } else {
      return ("\n\n(found in " + (formatComponentName(vm)) + ")")
    }
  };
}

/*  */

var uid = 0;

/**
 * A dep is an observable that can have multiple
 * directives subscribing to it.
 */
var Dep = function Dep () {
  this.id = uid++;
  this.subs = [];
};

Dep.prototype.addSub = function addSub (sub) {
  this.subs.push(sub);
};

Dep.prototype.removeSub = function removeSub (sub) {
  remove(this.subs, sub);
};

Dep.prototype.depend = function depend () {
  if (Dep.target) {
    Dep.target.addDep(this);
  }
};

Dep.prototype.notify = function notify () {
  // stabilize the subscriber list first
  var subs = this.subs.slice();
  if (!config.async) {
    // subs aren't sorted in scheduler if not running async
    // we need to sort them now to make sure they fire in correct
    // order
    subs.sort(function (a, b) { return a.id - b.id; });
  }
  for (var i = 0, l = subs.length; i < l; i++) {
    subs[i].update();
  }
};

// The current target watcher being evaluated.
// This is globally unique because only one watcher
// can be evaluated at a time.
Dep.target = null;
var targetStack = [];

function pushTarget (target) {
  targetStack.push(target);
  Dep.target = target;
}

function popTarget () {
  targetStack.pop();
  Dep.target = targetStack[targetStack.length - 1];
}

/*  */

var VNode = function VNode (
  tag,
  data,
  children,
  text,
  elm,
  context,
  componentOptions,
  asyncFactory
) {
  this.tag = tag;
  this.data = data;
  this.children = children;
  this.text = text;
  this.elm = elm;
  this.ns = undefined;
  this.context = context;
  this.fnContext = undefined;
  this.fnOptions = undefined;
  this.fnScopeId = undefined;
  this.key = data && data.key;
  this.componentOptions = componentOptions;
  this.componentInstance = undefined;
  this.parent = undefined;
  this.raw = false;
  this.isStatic = false;
  this.isRootInsert = true;
  this.isComment = false;
  this.isCloned = false;
  this.isOnce = false;
  this.asyncFactory = asyncFactory;
  this.asyncMeta = undefined;
  this.isAsyncPlaceholder = false;
};

var prototypeAccessors = { child: { configurable: true } };

// DEPRECATED: alias for componentInstance for backwards compat.
/* istanbul ignore next */
prototypeAccessors.child.get = function () {
  return this.componentInstance
};

Object.defineProperties( VNode.prototype, prototypeAccessors );

var createEmptyVNode = function (text) {
  if ( text === void 0 ) text = '';

  var node = new VNode();
  node.text = text;
  node.isComment = true;
  return node
};

function createTextVNode (val) {
  return new VNode(undefined, undefined, undefined, String(val))
}

// optimized shallow clone
// used for static nodes and slot nodes because they may be reused across
// multiple renders, cloning them avoids errors when DOM manipulations rely
// on their elm reference.
function cloneVNode (vnode) {
  var cloned = new VNode(
    vnode.tag,
    vnode.data,
    // #7975
    // clone children array to avoid mutating original in case of cloning
    // a child.
    vnode.children && vnode.children.slice(),
    vnode.text,
    vnode.elm,
    vnode.context,
    vnode.componentOptions,
    vnode.asyncFactory
  );
  cloned.ns = vnode.ns;
  cloned.isStatic = vnode.isStatic;
  cloned.key = vnode.key;
  cloned.isComment = vnode.isComment;
  cloned.fnContext = vnode.fnContext;
  cloned.fnOptions = vnode.fnOptions;
  cloned.fnScopeId = vnode.fnScopeId;
  cloned.asyncMeta = vnode.asyncMeta;
  cloned.isCloned = true;
  return cloned
}

/*
 * not type checking this file because flow doesn't play well with
 * dynamically accessing methods on Array prototype
 */

var arrayProto = Array.prototype;
var arrayMethods = Object.create(arrayProto);

var methodsToPatch = [
  'push',
  'pop',
  'shift',
  'unshift',
  'splice',
  'sort',
  'reverse'
];

/**
 * Intercept mutating methods and emit events
 */
methodsToPatch.forEach(function (method) {
  // cache original method
  var original = arrayProto[method];
  def(arrayMethods, method, function mutator () {
    var args = [], len = arguments.length;
    while ( len-- ) args[ len ] = arguments[ len ];

    var result = original.apply(this, args);
    var ob = this.__ob__;
    var inserted;
    switch (method) {
      case 'push':
      case 'unshift':
        inserted = args;
        break
      case 'splice':
        inserted = args.slice(2);
        break
    }
    if (inserted) { ob.observeArray(inserted); }
    // notify change
    ob.dep.notify();
    return result
  });
});

/*  */

var arrayKeys = Object.getOwnPropertyNames(arrayMethods);

/**
 * In some cases we may want to disable observation inside a component's
 * update computation.
 */
var shouldObserve = true;

function toggleObserving (value) {
  shouldObserve = value;
}

/**
 * Observer class that is attached to each observed
 * object. Once attached, the observer converts the target
 * object's property keys into getter/setters that
 * collect dependencies and dispatch updates.
 */
var Observer = function Observer (value) {
  this.value = value;
  this.dep = new Dep();
  this.vmCount = 0;
  def(value, '__ob__', this);
  if (Array.isArray(value)) {
    if (hasProto) {
      protoAugment(value, arrayMethods);
    } else {
      copyAugment(value, arrayMethods, arrayKeys);
    }
    this.observeArray(value);
  } else {
    this.walk(value);
  }
};

/**
 * Walk through all properties and convert them into
 * getter/setters. This method should only be called when
 * value type is Object.
 */
Observer.prototype.walk = function walk (obj) {
  var keys = Object.keys(obj);
  for (var i = 0; i < keys.length; i++) {
    defineReactive$$1(obj, keys[i]);
  }
};

/**
 * Observe a list of Array items.
 */
Observer.prototype.observeArray = function observeArray (items) {
  for (var i = 0, l = items.length; i < l; i++) {
    observe(items[i]);
  }
};

// helpers

/**
 * Augment a target Object or Array by intercepting
 * the prototype chain using __proto__
 */
function protoAugment (target, src) {
  /* eslint-disable no-proto */
  target.__proto__ = src;
  /* eslint-enable no-proto */
}

/**
 * Augment a target Object or Array by defining
 * hidden properties.
 */
/* istanbul ignore next */
function copyAugment (target, src, keys) {
  for (var i = 0, l = keys.length; i < l; i++) {
    var key = keys[i];
    def(target, key, src[key]);
  }
}

/**
 * Attempt to create an observer instance for a value,
 * returns the new observer if successfully observed,
 * or the existing observer if the value already has one.
 */
function observe (value, asRootData) {
  if (!isObject(value) || value instanceof VNode) {
    return
  }
  var ob;
  if (hasOwn(value, '__ob__') && value.__ob__ instanceof Observer) {
    ob = value.__ob__;
  } else if (
    shouldObserve &&
    !isServerRendering() &&
    (Array.isArray(value) || isPlainObject(value)) &&
    Object.isExtensible(value) &&
    !value._isVue
  ) {
    ob = new Observer(value);
  }
  if (asRootData && ob) {
    ob.vmCount++;
  }
  return ob
}

/**
 * Define a reactive property on an Object.
 */
function defineReactive$$1 (
  obj,
  key,
  val,
  customSetter,
  shallow
) {
  var dep = new Dep();

  var property = Object.getOwnPropertyDescriptor(obj, key);
  if (property && property.configurable === false) {
    return
  }

  // cater for pre-defined getter/setters
  var getter = property && property.get;
  var setter = property && property.set;
  if ((!getter || setter) && arguments.length === 2) {
    val = obj[key];
  }

  var childOb = !shallow && observe(val);
  Object.defineProperty(obj, key, {
    enumerable: true,
    configurable: true,
    get: function reactiveGetter () {
      var value = getter ? getter.call(obj) : val;
      if (Dep.target) {
        dep.depend();
        if (childOb) {
          childOb.dep.depend();
          if (Array.isArray(value)) {
            dependArray(value);
          }
        }
      }
      return value
    },
    set: function reactiveSetter (newVal) {
      var value = getter ? getter.call(obj) : val;
      /* eslint-disable no-self-compare */
      if (newVal === value || (newVal !== newVal && value !== value)) {
        return
      }
      /* eslint-enable no-self-compare */
      if (customSetter) {
        customSetter();
      }
      // #7981: for accessor properties without setter
      if (getter && !setter) { return }
      if (setter) {
        setter.call(obj, newVal);
      } else {
        val = newVal;
      }
      childOb = !shallow && observe(newVal);
      dep.notify();
    }
  });
}

/**
 * Set a property on an object. Adds the new property and
 * triggers change notification if the property doesn't
 * already exist.
 */
function set (target, key, val) {
  if (isUndef(target) || isPrimitive(target)
  ) {
    warn(("Cannot set reactive property on undefined, null, or primitive value: " + ((target))));
  }
  if (Array.isArray(target) && isValidArrayIndex(key)) {
    target.length = Math.max(target.length, key);
    target.splice(key, 1, val);
    return val
  }
  if (key in target && !(key in Object.prototype)) {
    target[key] = val;
    return val
  }
  var ob = (target).__ob__;
  if (target._isVue || (ob && ob.vmCount)) {
    warn(
      'Avoid adding reactive properties to a Vue instance or its root $data ' +
      'at runtime - declare it upfront in the data option.'
    );
    return val
  }
  if (!ob) {
    target[key] = val;
    return val
  }
  defineReactive$$1(ob.value, key, val);
  ob.dep.notify();
  return val
}

/**
 * Delete a property and trigger change if necessary.
 */
function del (target, key) {
  if (isUndef(target) || isPrimitive(target)
  ) {
    warn(("Cannot delete reactive property on undefined, null, or primitive value: " + ((target))));
  }
  if (Array.isArray(target) && isValidArrayIndex(key)) {
    target.splice(key, 1);
    return
  }
  var ob = (target).__ob__;
  if (target._isVue || (ob && ob.vmCount)) {
    warn(
      'Avoid deleting properties on a Vue instance or its root $data ' +
      '- just set it to null.'
    );
    return
  }
  if (!hasOwn(target, key)) {
    return
  }
  delete target[key];
  if (!ob) {
    return
  }
  ob.dep.notify();
}

/**
 * Collect dependencies on array elements when the array is touched, since
 * we cannot intercept array element access like property getters.
 */
function dependArray (value) {
  for (var e = (void 0), i = 0, l = value.length; i < l; i++) {
    e = value[i];
    e && e.__ob__ && e.__ob__.dep.depend();
    if (Array.isArray(e)) {
      dependArray(e);
    }
  }
}

/*  */

/**
 * Option overwriting strategies are functions that handle
 * how to merge a parent option value and a child option
 * value into the final value.
 */
var strats = config.optionMergeStrategies;

/**
 * Options with restrictions
 */
{
  strats.el = strats.propsData = function (parent, child, vm, key) {
    if (!vm) {
      warn(
        "option \"" + key + "\" can only be used during instance " +
        'creation with the `new` keyword.'
      );
    }
    return defaultStrat(parent, child)
  };
}

/**
 * Helper that recursively merges two data objects together.
 */
function mergeData (to, from) {
  if (!from) { return to }
  var key, toVal, fromVal;

  var keys = hasSymbol
    ? Reflect.ownKeys(from)
    : Object.keys(from);

  for (var i = 0; i < keys.length; i++) {
    key = keys[i];
    // in case the object is already observed...
    if (key === '__ob__') { continue }
    toVal = to[key];
    fromVal = from[key];
    if (!hasOwn(to, key)) {
      set(to, key, fromVal);
    } else if (
      toVal !== fromVal &&
      isPlainObject(toVal) &&
      isPlainObject(fromVal)
    ) {
      mergeData(toVal, fromVal);
    }
  }
  return to
}

/**
 * Data
 */
function mergeDataOrFn (
  parentVal,
  childVal,
  vm
) {
  if (!vm) {
    // in a Vue.extend merge, both should be functions
    if (!childVal) {
      return parentVal
    }
    if (!parentVal) {
      return childVal
    }
    // when parentVal & childVal are both present,
    // we need to return a function that returns the
    // merged result of both functions... no need to
    // check if parentVal is a function here because
    // it has to be a function to pass previous merges.
    return function mergedDataFn () {
      return mergeData(
        typeof childVal === 'function' ? childVal.call(this, this) : childVal,
        typeof parentVal === 'function' ? parentVal.call(this, this) : parentVal
      )
    }
  } else {
    return function mergedInstanceDataFn () {
      // instance merge
      var instanceData = typeof childVal === 'function'
        ? childVal.call(vm, vm)
        : childVal;
      var defaultData = typeof parentVal === 'function'
        ? parentVal.call(vm, vm)
        : parentVal;
      if (instanceData) {
        return mergeData(instanceData, defaultData)
      } else {
        return defaultData
      }
    }
  }
}

strats.data = function (
  parentVal,
  childVal,
  vm
) {
  if (!vm) {
    if (childVal && typeof childVal !== 'function') {
      warn(
        'The "data" option should be a function ' +
        'that returns a per-instance value in component ' +
        'definitions.',
        vm
      );

      return parentVal
    }
    return mergeDataOrFn(parentVal, childVal)
  }

  return mergeDataOrFn(parentVal, childVal, vm)
};

/**
 * Hooks and props are merged as arrays.
 */
function mergeHook (
  parentVal,
  childVal
) {
  var res = childVal
    ? parentVal
      ? parentVal.concat(childVal)
      : Array.isArray(childVal)
        ? childVal
        : [childVal]
    : parentVal;
  return res
    ? dedupeHooks(res)
    : res
}

function dedupeHooks (hooks) {
  var res = [];
  for (var i = 0; i < hooks.length; i++) {
    if (res.indexOf(hooks[i]) === -1) {
      res.push(hooks[i]);
    }
  }
  return res
}

LIFECYCLE_HOOKS.forEach(function (hook) {
  strats[hook] = mergeHook;
});

/**
 * Assets
 *
 * When a vm is present (instance creation), we need to do
 * a three-way merge between constructor options, instance
 * options and parent options.
 */
function mergeAssets (
  parentVal,
  childVal,
  vm,
  key
) {
  var res = Object.create(parentVal || null);
  if (childVal) {
    assertObjectType(key, childVal, vm);
    return extend(res, childVal)
  } else {
    return res
  }
}

ASSET_TYPES.forEach(function (type) {
  strats[type + 's'] = mergeAssets;
});

/**
 * Watchers.
 *
 * Watchers hashes should not overwrite one
 * another, so we merge them as arrays.
 */
strats.watch = function (
  parentVal,
  childVal,
  vm,
  key
) {
  // work around Firefox's Object.prototype.watch...
  if (parentVal === nativeWatch) { parentVal = undefined; }
  if (childVal === nativeWatch) { childVal = undefined; }
  /* istanbul ignore if */
  if (!childVal) { return Object.create(parentVal || null) }
  {
    assertObjectType(key, childVal, vm);
  }
  if (!parentVal) { return childVal }
  var ret = {};
  extend(ret, parentVal);
  for (var key$1 in childVal) {
    var parent = ret[key$1];
    var child = childVal[key$1];
    if (parent && !Array.isArray(parent)) {
      parent = [parent];
    }
    ret[key$1] = parent
      ? parent.concat(child)
      : Array.isArray(child) ? child : [child];
  }
  return ret
};

/**
 * Other object hashes.
 */
strats.props =
strats.methods =
strats.inject =
strats.computed = function (
  parentVal,
  childVal,
  vm,
  key
) {
  if (childVal && "development" !== 'production') {
    assertObjectType(key, childVal, vm);
  }
  if (!parentVal) { return childVal }
  var ret = Object.create(null);
  extend(ret, parentVal);
  if (childVal) { extend(ret, childVal); }
  return ret
};
strats.provide = mergeDataOrFn;

/**
 * Default strategy.
 */
var defaultStrat = function (parentVal, childVal) {
  return childVal === undefined
    ? parentVal
    : childVal
};

/**
 * Validate component names
 */
function checkComponents (options) {
  for (var key in options.components) {
    validateComponentName(key);
  }
}

function validateComponentName (name) {
  if (!new RegExp(("^[a-zA-Z][\\-\\.0-9_" + (unicodeRegExp.source) + "]*$")).test(name)) {
    warn(
      'Invalid component name: "' + name + '". Component names ' +
      'should conform to valid custom element name in html5 specification.'
    );
  }
  if (isBuiltInTag(name) || config.isReservedTag(name)) {
    warn(
      'Do not use built-in or reserved HTML elements as component ' +
      'id: ' + name
    );
  }
}

/**
 * Ensure all props option syntax are normalized into the
 * Object-based format.
 */
function normalizeProps (options, vm) {
  var props = options.props;
  if (!props) { return }
  var res = {};
  var i, val, name;
  if (Array.isArray(props)) {
    i = props.length;
    while (i--) {
      val = props[i];
      if (typeof val === 'string') {
        name = camelize(val);
        res[name] = { type: null };
      } else {
        warn('props must be strings when using array syntax.');
      }
    }
  } else if (isPlainObject(props)) {
    for (var key in props) {
      val = props[key];
      name = camelize(key);
      res[name] = isPlainObject(val)
        ? val
        : { type: val };
    }
  } else {
    warn(
      "Invalid value for option \"props\": expected an Array or an Object, " +
      "but got " + (toRawType(props)) + ".",
      vm
    );
  }
  options.props = res;
}

/**
 * Normalize all injections into Object-based format
 */
function normalizeInject (options, vm) {
  var inject = options.inject;
  if (!inject) { return }
  var normalized = options.inject = {};
  if (Array.isArray(inject)) {
    for (var i = 0; i < inject.length; i++) {
      normalized[inject[i]] = { from: inject[i] };
    }
  } else if (isPlainObject(inject)) {
    for (var key in inject) {
      var val = inject[key];
      normalized[key] = isPlainObject(val)
        ? extend({ from: key }, val)
        : { from: val };
    }
  } else {
    warn(
      "Invalid value for option \"inject\": expected an Array or an Object, " +
      "but got " + (toRawType(inject)) + ".",
      vm
    );
  }
}

/**
 * Normalize raw function directives into object format.
 */
function normalizeDirectives (options) {
  var dirs = options.directives;
  if (dirs) {
    for (var key in dirs) {
      var def$$1 = dirs[key];
      if (typeof def$$1 === 'function') {
        dirs[key] = { bind: def$$1, update: def$$1 };
      }
    }
  }
}

function assertObjectType (name, value, vm) {
  if (!isPlainObject(value)) {
    warn(
      "Invalid value for option \"" + name + "\": expected an Object, " +
      "but got " + (toRawType(value)) + ".",
      vm
    );
  }
}

/**
 * Merge two option objects into a new one.
 * Core utility used in both instantiation and inheritance.
 */
function mergeOptions (
  parent,
  child,
  vm
) {
  {
    checkComponents(child);
  }

  if (typeof child === 'function') {
    child = child.options;
  }

  normalizeProps(child, vm);
  normalizeInject(child, vm);
  normalizeDirectives(child);

  // Apply extends and mixins on the child options,
  // but only if it is a raw options object that isn't
  // the result of another mergeOptions call.
  // Only merged options has the _base property.
  if (!child._base) {
    if (child.extends) {
      parent = mergeOptions(parent, child.extends, vm);
    }
    if (child.mixins) {
      for (var i = 0, l = child.mixins.length; i < l; i++) {
        parent = mergeOptions(parent, child.mixins[i], vm);
      }
    }
  }

  var options = {};
  var key;
  for (key in parent) {
    mergeField(key);
  }
  for (key in child) {
    if (!hasOwn(parent, key)) {
      mergeField(key);
    }
  }
  function mergeField (key) {
    var strat = strats[key] || defaultStrat;
    options[key] = strat(parent[key], child[key], vm, key);
  }
  return options
}

/**
 * Resolve an asset.
 * This function is used because child instances need access
 * to assets defined in its ancestor chain.
 */
function resolveAsset (
  options,
  type,
  id,
  warnMissing
) {
  /* istanbul ignore if */
  if (typeof id !== 'string') {
    return
  }
  var assets = options[type];
  // check local registration variations first
  if (hasOwn(assets, id)) { return assets[id] }
  var camelizedId = camelize(id);
  if (hasOwn(assets, camelizedId)) { return assets[camelizedId] }
  var PascalCaseId = capitalize(camelizedId);
  if (hasOwn(assets, PascalCaseId)) { return assets[PascalCaseId] }
  // fallback to prototype chain
  var res = assets[id] || assets[camelizedId] || assets[PascalCaseId];
  if (warnMissing && !res) {
    warn(
      'Failed to resolve ' + type.slice(0, -1) + ': ' + id,
      options
    );
  }
  return res
}

/*  */



function validateProp (
  key,
  propOptions,
  propsData,
  vm
) {
  var prop = propOptions[key];
  var absent = !hasOwn(propsData, key);
  var value = propsData[key];
  // boolean casting
  var booleanIndex = getTypeIndex(Boolean, prop.type);
  if (booleanIndex > -1) {
    if (absent && !hasOwn(prop, 'default')) {
      value = false;
    } else if (value === '' || value === hyphenate(key)) {
      // only cast empty string / same name to boolean if
      // boolean has higher priority
      var stringIndex = getTypeIndex(String, prop.type);
      if (stringIndex < 0 || booleanIndex < stringIndex) {
        value = true;
      }
    }
  }
  // check default value
  if (value === undefined) {
    value = getPropDefaultValue(vm, prop, key);
    // since the default value is a fresh copy,
    // make sure to observe it.
    var prevShouldObserve = shouldObserve;
    toggleObserving(true);
    observe(value);
    toggleObserving(prevShouldObserve);
  }
  {
    assertProp(prop, key, value, vm, absent);
  }
  return value
}

/**
 * Get the default value of a prop.
 */
function getPropDefaultValue (vm, prop, key) {
  // no default, return undefined
  if (!hasOwn(prop, 'default')) {
    return undefined
  }
  var def = prop.default;
  // warn against non-factory defaults for Object & Array
  if (isObject(def)) {
    warn(
      'Invalid default value for prop "' + key + '": ' +
      'Props with type Object/Array must use a factory function ' +
      'to return the default value.',
      vm
    );
  }
  // the raw prop value was also undefined from previous render,
  // return previous default value to avoid unnecessary watcher trigger
  if (vm && vm.$options.propsData &&
    vm.$options.propsData[key] === undefined &&
    vm._props[key] !== undefined
  ) {
    return vm._props[key]
  }
  // call factory function for non-Function types
  // a value is Function if its prototype is function even across different execution context
  return typeof def === 'function' && getType(prop.type) !== 'Function'
    ? def.call(vm)
    : def
}

/**
 * Assert whether a prop is valid.
 */
function assertProp (
  prop,
  name,
  value,
  vm,
  absent
) {
  if (prop.required && absent) {
    warn(
      'Missing required prop: "' + name + '"',
      vm
    );
    return
  }
  if (value == null && !prop.required) {
    return
  }
  var type = prop.type;
  var valid = !type || type === true;
  var expectedTypes = [];
  if (type) {
    if (!Array.isArray(type)) {
      type = [type];
    }
    for (var i = 0; i < type.length && !valid; i++) {
      var assertedType = assertType(value, type[i]);
      expectedTypes.push(assertedType.expectedType || '');
      valid = assertedType.valid;
    }
  }

  if (!valid) {
    warn(
      getInvalidTypeMessage(name, value, expectedTypes),
      vm
    );
    return
  }
  var validator = prop.validator;
  if (validator) {
    if (!validator(value)) {
      warn(
        'Invalid prop: custom validator check failed for prop "' + name + '".',
        vm
      );
    }
  }
}

var simpleCheckRE = /^(String|Number|Boolean|Function|Symbol)$/;

function assertType (value, type) {
  var valid;
  var expectedType = getType(type);
  if (simpleCheckRE.test(expectedType)) {
    var t = typeof value;
    valid = t === expectedType.toLowerCase();
    // for primitive wrapper objects
    if (!valid && t === 'object') {
      valid = value instanceof type;
    }
  } else if (expectedType === 'Object') {
    valid = isPlainObject(value);
  } else if (expectedType === 'Array') {
    valid = Array.isArray(value);
  } else {
    valid = value instanceof type;
  }
  return {
    valid: valid,
    expectedType: expectedType
  }
}

/**
 * Use function string name to check built-in types,
 * because a simple equality check will fail when running
 * across different vms / iframes.
 */
function getType (fn) {
  var match = fn && fn.toString().match(/^\s*function (\w+)/);
  return match ? match[1] : ''
}

function isSameType (a, b) {
  return getType(a) === getType(b)
}

function getTypeIndex (type, expectedTypes) {
  if (!Array.isArray(expectedTypes)) {
    return isSameType(expectedTypes, type) ? 0 : -1
  }
  for (var i = 0, len = expectedTypes.length; i < len; i++) {
    if (isSameType(expectedTypes[i], type)) {
      return i
    }
  }
  return -1
}

function getInvalidTypeMessage (name, value, expectedTypes) {
  var message = "Invalid prop: type check failed for prop \"" + name + "\"." +
    " Expected " + (expectedTypes.map(capitalize).join(', '));
  var expectedType = expectedTypes[0];
  var receivedType = toRawType(value);
  var expectedValue = styleValue(value, expectedType);
  var receivedValue = styleValue(value, receivedType);
  // check if we need to specify expected value
  if (expectedTypes.length === 1 &&
      isExplicable(expectedType) &&
      !isBoolean(expectedType, receivedType)) {
    message += " with value " + expectedValue;
  }
  message += ", got " + receivedType + " ";
  // check if we need to specify received value
  if (isExplicable(receivedType)) {
    message += "with value " + receivedValue + ".";
  }
  return message
}

function styleValue (value, type) {
  if (type === 'String') {
    return ("\"" + value + "\"")
  } else if (type === 'Number') {
    return ("" + (Number(value)))
  } else {
    return ("" + value)
  }
}

function isExplicable (value) {
  var explicitTypes = ['string', 'number', 'boolean'];
  return explicitTypes.some(function (elem) { return value.toLowerCase() === elem; })
}

function isBoolean () {
  var args = [], len = arguments.length;
  while ( len-- ) args[ len ] = arguments[ len ];

  return args.some(function (elem) { return elem.toLowerCase() === 'boolean'; })
}

/*  */

function handleError (err, vm, info) {
  // Deactivate deps tracking while processing error handler to avoid possible infinite rendering.
  // See: https://github.com/vuejs/vuex/issues/1505
  pushTarget();
  try {
    if (vm) {
      var cur = vm;
      while ((cur = cur.$parent)) {
        var hooks = cur.$options.errorCaptured;
        if (hooks) {
          for (var i = 0; i < hooks.length; i++) {
            try {
              var capture = hooks[i].call(cur, err, vm, info) === false;
              if (capture) { return }
            } catch (e) {
              globalHandleError(e, cur, 'errorCaptured hook');
            }
          }
        }
      }
    }
    globalHandleError(err, vm, info);
  } finally {
    popTarget();
  }
}

function invokeWithErrorHandling (
  handler,
  context,
  args,
  vm,
  info
) {
  var res;
  try {
    res = args ? handler.apply(context, args) : handler.call(context);
    if (res && !res._isVue && isPromise(res) && !res._handled) {
      res.catch(function (e) { return handleError(e, vm, info + " (Promise/async)"); });
      // issue #9511
      // avoid catch triggering multiple times when nested calls
      res._handled = true;
    }
  } catch (e) {
    handleError(e, vm, info);
  }
  return res
}

function globalHandleError (err, vm, info) {
  if (config.errorHandler) {
    try {
      return config.errorHandler.call(null, err, vm, info)
    } catch (e) {
      // if the user intentionally throws the original error in the handler,
      // do not log it twice
      if (e !== err) {
        logError(e, null, 'config.errorHandler');
      }
    }
  }
  logError(err, vm, info);
}

function logError (err, vm, info) {
  {
    warn(("Error in " + info + ": \"" + (err.toString()) + "\""), vm);
  }
  /* istanbul ignore else */
  if ((inBrowser || inWeex) && typeof console !== 'undefined') {
    console.error(err);
  } else {
    throw err
  }
}

/*  */

var isUsingMicroTask = false;

var callbacks = [];
var pending = false;

function flushCallbacks () {
  pending = false;
  var copies = callbacks.slice(0);
  callbacks.length = 0;
  for (var i = 0; i < copies.length; i++) {
    copies[i]();
  }
}

// Here we have async deferring wrappers using microtasks.
// In 2.5 we used (macro) tasks (in combination with microtasks).
// However, it has subtle problems when state is changed right before repaint
// (e.g. #6813, out-in transitions).
// Also, using (macro) tasks in event handler would cause some weird behaviors
// that cannot be circumvented (e.g. #7109, #7153, #7546, #7834, #8109).
// So we now use microtasks everywhere, again.
// A major drawback of this tradeoff is that there are some scenarios
// where microtasks have too high a priority and fire in between supposedly
// sequential events (e.g. #4521, #6690, which have workarounds)
// or even between bubbling of the same event (#6566).
var timerFunc;

// The nextTick behavior leverages the microtask queue, which can be accessed
// via either native Promise.then or MutationObserver.
// MutationObserver has wider support, however it is seriously bugged in
// UIWebView in iOS >= 9.3.3 when triggered in touch event handlers. It
// completely stops working after triggering a few times... so, if native
// Promise is available, we will use it:
/* istanbul ignore next, $flow-disable-line */
if (typeof Promise !== 'undefined' && isNative(Promise)) {
  var p = Promise.resolve();
  timerFunc = function () {
    p.then(flushCallbacks);
    // In problematic UIWebViews, Promise.then doesn't completely break, but
    // it can get stuck in a weird state where callbacks are pushed into the
    // microtask queue but the queue isn't being flushed, until the browser
    // needs to do some other work, e.g. handle a timer. Therefore we can
    // "force" the microtask queue to be flushed by adding an empty timer.
    if (isIOS) { setTimeout(noop); }
  };
  isUsingMicroTask = true;
} else if (!isIE && typeof MutationObserver !== 'undefined' && (
  isNative(MutationObserver) ||
  // PhantomJS and iOS 7.x
  MutationObserver.toString() === '[object MutationObserverConstructor]'
)) {
  // Use MutationObserver where native Promise is not available,
  // e.g. PhantomJS, iOS7, Android 4.4
  // (#6466 MutationObserver is unreliable in IE11)
  var counter = 1;
  var observer = new MutationObserver(flushCallbacks);
  var textNode = document.createTextNode(String(counter));
  observer.observe(textNode, {
    characterData: true
  });
  timerFunc = function () {
    counter = (counter + 1) % 2;
    textNode.data = String(counter);
  };
  isUsingMicroTask = true;
} else if (typeof setImmediate !== 'undefined' && isNative(setImmediate)) {
  // Fallback to setImmediate.
  // Technically it leverages the (macro) task queue,
  // but it is still a better choice than setTimeout.
  timerFunc = function () {
    setImmediate(flushCallbacks);
  };
} else {
  // Fallback to setTimeout.
  timerFunc = function () {
    setTimeout(flushCallbacks, 0);
  };
}

function nextTick (cb, ctx) {
  var _resolve;
  callbacks.push(function () {
    if (cb) {
      try {
        cb.call(ctx);
      } catch (e) {
        handleError(e, ctx, 'nextTick');
      }
    } else if (_resolve) {
      _resolve(ctx);
    }
  });
  if (!pending) {
    pending = true;
    timerFunc();
  }
  // $flow-disable-line
  if (!cb && typeof Promise !== 'undefined') {
    return new Promise(function (resolve) {
      _resolve = resolve;
    })
  }
}

/*  */

var mark;
var measure;

{
  var perf = inBrowser && window.performance;
  /* istanbul ignore if */
  if (
    perf &&
    perf.mark &&
    perf.measure &&
    perf.clearMarks &&
    perf.clearMeasures
  ) {
    mark = function (tag) { return perf.mark(tag); };
    measure = function (name, startTag, endTag) {
      perf.measure(name, startTag, endTag);
      perf.clearMarks(startTag);
      perf.clearMarks(endTag);
      // perf.clearMeasures(name)
    };
  }
}

/* not type checking this file because flow doesn't play well with Proxy */

var initProxy;

{
  var allowedGlobals = makeMap(
    'Infinity,undefined,NaN,isFinite,isNaN,' +
    'parseFloat,parseInt,decodeURI,decodeURIComponent,encodeURI,encodeURIComponent,' +
    'Math,Number,Date,Array,Object,Boolean,String,RegExp,Map,Set,JSON,Intl,' +
    'require' // for Webpack/Browserify
  );

  var warnNonPresent = function (target, key) {
    warn(
      "Property or method \"" + key + "\" is not defined on the instance but " +
      'referenced during render. Make sure that this property is reactive, ' +
      'either in the data option, or for class-based components, by ' +
      'initializing the property. ' +
      'See: https://vuejs.org/v2/guide/reactivity.html#Declaring-Reactive-Properties.',
      target
    );
  };

  var warnReservedPrefix = function (target, key) {
    warn(
      "Property \"" + key + "\" must be accessed with \"$data." + key + "\" because " +
      'properties starting with "$" or "_" are not proxied in the Vue instance to ' +
      'prevent conflicts with Vue internals. ' +
      'See: https://vuejs.org/v2/api/#data',
      target
    );
  };

  var hasProxy =
    typeof Proxy !== 'undefined' && isNative(Proxy);

  if (hasProxy) {
    var isBuiltInModifier = makeMap('stop,prevent,self,ctrl,shift,alt,meta,exact');
    config.keyCodes = new Proxy(config.keyCodes, {
      set: function set (target, key, value) {
        if (isBuiltInModifier(key)) {
          warn(("Avoid overwriting built-in modifier in config.keyCodes: ." + key));
          return false
        } else {
          target[key] = value;
          return true
        }
      }
    });
  }

  var hasHandler = {
    has: function has (target, key) {
      var has = key in target;
      var isAllowed = allowedGlobals(key) ||
        (typeof key === 'string' && key.charAt(0) === '_' && !(key in target.$data));
      if (!has && !isAllowed) {
        if (key in target.$data) { warnReservedPrefix(target, key); }
        else { warnNonPresent(target, key); }
      }
      return has || !isAllowed
    }
  };

  var getHandler = {
    get: function get (target, key) {
      if (typeof key === 'string' && !(key in target)) {
        if (key in target.$data) { warnReservedPrefix(target, key); }
        else { warnNonPresent(target, key); }
      }
      return target[key]
    }
  };

  initProxy = function initProxy (vm) {
    if (hasProxy) {
      // determine which proxy handler to use
      var options = vm.$options;
      var handlers = options.render && options.render._withStripped
        ? getHandler
        : hasHandler;
      vm._renderProxy = new Proxy(vm, handlers);
    } else {
      vm._renderProxy = vm;
    }
  };
}

/*  */

var seenObjects = new _Set();

/**
 * Recursively traverse an object to evoke all converted
 * getters, so that every nested property inside the object
 * is collected as a "deep" dependency.
 */
function traverse (val) {
  _traverse(val, seenObjects);
  seenObjects.clear();
}

function _traverse (val, seen) {
  var i, keys;
  var isA = Array.isArray(val);
  if ((!isA && !isObject(val)) || Object.isFrozen(val) || val instanceof VNode) {
    return
  }
  if (val.__ob__) {
    var depId = val.__ob__.dep.id;
    if (seen.has(depId)) {
      return
    }
    seen.add(depId);
  }
  if (isA) {
    i = val.length;
    while (i--) { _traverse(val[i], seen); }
  } else {
    keys = Object.keys(val);
    i = keys.length;
    while (i--) { _traverse(val[keys[i]], seen); }
  }
}

/*  */

var normalizeEvent = cached(function (name) {
  var passive = name.charAt(0) === '&';
  name = passive ? name.slice(1) : name;
  var once$$1 = name.charAt(0) === '~'; // Prefixed last, checked first
  name = once$$1 ? name.slice(1) : name;
  var capture = name.charAt(0) === '!';
  name = capture ? name.slice(1) : name;
  return {
    name: name,
    once: once$$1,
    capture: capture,
    passive: passive
  }
});

function createFnInvoker (fns, vm) {
  function invoker () {
    var arguments$1 = arguments;

    var fns = invoker.fns;
    if (Array.isArray(fns)) {
      var cloned = fns.slice();
      for (var i = 0; i < cloned.length; i++) {
        invokeWithErrorHandling(cloned[i], null, arguments$1, vm, "v-on handler");
      }
    } else {
      // return handler return value for single handlers
      return invokeWithErrorHandling(fns, null, arguments, vm, "v-on handler")
    }
  }
  invoker.fns = fns;
  return invoker
}

function updateListeners (
  on,
  oldOn,
  add,
  remove$$1,
  createOnceHandler,
  vm
) {
  var name, def$$1, cur, old, event;
  for (name in on) {
    def$$1 = cur = on[name];
    old = oldOn[name];
    event = normalizeEvent(name);
    if (isUndef(cur)) {
      warn(
        "Invalid handler for event \"" + (event.name) + "\": got " + String(cur),
        vm
      );
    } else if (isUndef(old)) {
      if (isUndef(cur.fns)) {
        cur = on[name] = createFnInvoker(cur, vm);
      }
      if (isTrue(event.once)) {
        cur = on[name] = createOnceHandler(event.name, cur, event.capture);
      }
      add(event.name, cur, event.capture, event.passive, event.params);
    } else if (cur !== old) {
      old.fns = cur;
      on[name] = old;
    }
  }
  for (name in oldOn) {
    if (isUndef(on[name])) {
      event = normalizeEvent(name);
      remove$$1(event.name, oldOn[name], event.capture);
    }
  }
}

/*  */

function mergeVNodeHook (def, hookKey, hook) {
  if (def instanceof VNode) {
    def = def.data.hook || (def.data.hook = {});
  }
  var invoker;
  var oldHook = def[hookKey];

  function wrappedHook () {
    hook.apply(this, arguments);
    // important: remove merged hook to ensure it's called only once
    // and prevent memory leak
    remove(invoker.fns, wrappedHook);
  }

  if (isUndef(oldHook)) {
    // no existing hook
    invoker = createFnInvoker([wrappedHook]);
  } else {
    /* istanbul ignore if */
    if (isDef(oldHook.fns) && isTrue(oldHook.merged)) {
      // already a merged invoker
      invoker = oldHook;
      invoker.fns.push(wrappedHook);
    } else {
      // existing plain hook
      invoker = createFnInvoker([oldHook, wrappedHook]);
    }
  }

  invoker.merged = true;
  def[hookKey] = invoker;
}

/*  */

function extractPropsFromVNodeData (
  data,
  Ctor,
  tag
) {
  // we are only extracting raw values here.
  // validation and default values are handled in the child
  // component itself.
  var propOptions = Ctor.options.props;
  if (isUndef(propOptions)) {
    return
  }
  var res = {};
  var attrs = data.attrs;
  var props = data.props;
  if (isDef(attrs) || isDef(props)) {
    for (var key in propOptions) {
      var altKey = hyphenate(key);
      {
        var keyInLowerCase = key.toLowerCase();
        if (
          key !== keyInLowerCase &&
          attrs && hasOwn(attrs, keyInLowerCase)
        ) {
          tip(
            "Prop \"" + keyInLowerCase + "\" is passed to component " +
            (formatComponentName(tag || Ctor)) + ", but the declared prop name is" +
            " \"" + key + "\". " +
            "Note that HTML attributes are case-insensitive and camelCased " +
            "props need to use their kebab-case equivalents when using in-DOM " +
            "templates. You should probably use \"" + altKey + "\" instead of \"" + key + "\"."
          );
        }
      }
      checkProp(res, props, key, altKey, true) ||
      checkProp(res, attrs, key, altKey, false);
    }
  }
  return res
}

function checkProp (
  res,
  hash,
  key,
  altKey,
  preserve
) {
  if (isDef(hash)) {
    if (hasOwn(hash, key)) {
      res[key] = hash[key];
      if (!preserve) {
        delete hash[key];
      }
      return true
    } else if (hasOwn(hash, altKey)) {
      res[key] = hash[altKey];
      if (!preserve) {
        delete hash[altKey];
      }
      return true
    }
  }
  return false
}

/*  */

// The template compiler attempts to minimize the need for normalization by
// statically analyzing the template at compile time.
//
// For plain HTML markup, normalization can be completely skipped because the
// generated render function is guaranteed to return Array<VNode>. There are
// two cases where extra normalization is needed:

// 1. When the children contains components - because a functional component
// may return an Array instead of a single root. In this case, just a simple
// normalization is needed - if any child is an Array, we flatten the whole
// thing with Array.prototype.concat. It is guaranteed to be only 1-level deep
// because functional components already normalize their own children.
function simpleNormalizeChildren (children) {
  for (var i = 0; i < children.length; i++) {
    if (Array.isArray(children[i])) {
      return Array.prototype.concat.apply([], children)
    }
  }
  return children
}

// 2. When the children contains constructs that always generated nested Arrays,
// e.g. <template>, <slot>, v-for, or when the children is provided by user
// with hand-written render functions / JSX. In such cases a full normalization
// is needed to cater to all possible types of children values.
function normalizeChildren (children) {
  return isPrimitive(children)
    ? [createTextVNode(children)]
    : Array.isArray(children)
      ? normalizeArrayChildren(children)
      : undefined
}

function isTextNode (node) {
  return isDef(node) && isDef(node.text) && isFalse(node.isComment)
}

function normalizeArrayChildren (children, nestedIndex) {
  var res = [];
  var i, c, lastIndex, last;
  for (i = 0; i < children.length; i++) {
    c = children[i];
    if (isUndef(c) || typeof c === 'boolean') { continue }
    lastIndex = res.length - 1;
    last = res[lastIndex];
    //  nested
    if (Array.isArray(c)) {
      if (c.length > 0) {
        c = normalizeArrayChildren(c, ((nestedIndex || '') + "_" + i));
        // merge adjacent text nodes
        if (isTextNode(c[0]) && isTextNode(last)) {
          res[lastIndex] = createTextVNode(last.text + (c[0]).text);
          c.shift();
        }
        res.push.apply(res, c);
      }
    } else if (isPrimitive(c)) {
      if (isTextNode(last)) {
        // merge adjacent text nodes
        // this is necessary for SSR hydration because text nodes are
        // essentially merged when rendered to HTML strings
        res[lastIndex] = createTextVNode(last.text + c);
      } else if (c !== '') {
        // convert primitive to vnode
        res.push(createTextVNode(c));
      }
    } else {
      if (isTextNode(c) && isTextNode(last)) {
        // merge adjacent text nodes
        res[lastIndex] = createTextVNode(last.text + c.text);
      } else {
        // default key for nested array children (likely generated by v-for)
        if (isTrue(children._isVList) &&
          isDef(c.tag) &&
          isUndef(c.key) &&
          isDef(nestedIndex)) {
          c.key = "__vlist" + nestedIndex + "_" + i + "__";
        }
        res.push(c);
      }
    }
  }
  return res
}

/*  */

function initProvide (vm) {
  var provide = vm.$options.provide;
  if (provide) {
    vm._provided = typeof provide === 'function'
      ? provide.call(vm)
      : provide;
  }
}

function initInjections (vm) {
  var result = resolveInject(vm.$options.inject, vm);
  if (result) {
    toggleObserving(false);
    Object.keys(result).forEach(function (key) {
      /* istanbul ignore else */
      {
        defineReactive$$1(vm, key, result[key], function () {
          warn(
            "Avoid mutating an injected value directly since the changes will be " +
            "overwritten whenever the provided component re-renders. " +
            "injection being mutated: \"" + key + "\"",
            vm
          );
        });
      }
    });
    toggleObserving(true);
  }
}

function resolveInject (inject, vm) {
  if (inject) {
    // inject is :any because flow is not smart enough to figure out cached
    var result = Object.create(null);
    var keys = hasSymbol
      ? Reflect.ownKeys(inject)
      : Object.keys(inject);

    for (var i = 0; i < keys.length; i++) {
      var key = keys[i];
      // #6574 in case the inject object is observed...
      if (key === '__ob__') { continue }
      var provideKey = inject[key].from;
      var source = vm;
      while (source) {
        if (source._provided && hasOwn(source._provided, provideKey)) {
          result[key] = source._provided[provideKey];
          break
        }
        source = source.$parent;
      }
      if (!source) {
        if ('default' in inject[key]) {
          var provideDefault = inject[key].default;
          result[key] = typeof provideDefault === 'function'
            ? provideDefault.call(vm)
            : provideDefault;
        } else {
          warn(("Injection \"" + key + "\" not found"), vm);
        }
      }
    }
    return result
  }
}

/*  */



/**
 * Runtime helper for resolving raw children VNodes into a slot object.
 */
function resolveSlots (
  children,
  context
) {
  if (!children || !children.length) {
    return {}
  }
  var slots = {};
  for (var i = 0, l = children.length; i < l; i++) {
    var child = children[i];
    var data = child.data;
    // remove slot attribute if the node is resolved as a Vue slot node
    if (data && data.attrs && data.attrs.slot) {
      delete data.attrs.slot;
    }
    // named slots should only be respected if the vnode was rendered in the
    // same context.
    if ((child.context === context || child.fnContext === context) &&
      data && data.slot != null
    ) {
      var name = data.slot;
      var slot = (slots[name] || (slots[name] = []));
      if (child.tag === 'template') {
        slot.push.apply(slot, child.children || []);
      } else {
        slot.push(child);
      }
    } else {
      (slots.default || (slots.default = [])).push(child);
    }
  }
  // ignore slots that contains only whitespace
  for (var name$1 in slots) {
    if (slots[name$1].every(isWhitespace)) {
      delete slots[name$1];
    }
  }
  return slots
}

function isWhitespace (node) {
  return (node.isComment && !node.asyncFactory) || node.text === ' '
}

/*  */

function normalizeScopedSlots (
  slots,
  normalSlots,
  prevSlots
) {
  var res;
  var hasNormalSlots = Object.keys(normalSlots).length > 0;
  var isStable = slots ? !!slots.$stable : !hasNormalSlots;
  var key = slots && slots.$key;
  if (!slots) {
    res = {};
  } else if (slots._normalized) {
    // fast path 1: child component re-render only, parent did not change
    return slots._normalized
  } else if (
    isStable &&
    prevSlots &&
    prevSlots !== emptyObject &&
    key === prevSlots.$key &&
    !hasNormalSlots &&
    !prevSlots.$hasNormal
  ) {
    // fast path 2: stable scoped slots w/ no normal slots to proxy,
    // only need to normalize once
    return prevSlots
  } else {
    res = {};
    for (var key$1 in slots) {
      if (slots[key$1] && key$1[0] !== '$') {
        res[key$1] = normalizeScopedSlot(normalSlots, key$1, slots[key$1]);
      }
    }
  }
  // expose normal slots on scopedSlots
  for (var key$2 in normalSlots) {
    if (!(key$2 in res)) {
      res[key$2] = proxyNormalSlot(normalSlots, key$2);
    }
  }
  // avoriaz seems to mock a non-extensible $scopedSlots object
  // and when that is passed down this would cause an error
  if (slots && Object.isExtensible(slots)) {
    (slots)._normalized = res;
  }
  def(res, '$stable', isStable);
  def(res, '$key', key);
  def(res, '$hasNormal', hasNormalSlots);
  return res
}

function normalizeScopedSlot(normalSlots, key, fn) {
  var normalized = function () {
    var res = arguments.length ? fn.apply(null, arguments) : fn({});
    res = res && typeof res === 'object' && !Array.isArray(res)
      ? [res] // single vnode
      : normalizeChildren(res);
    return res && (
      res.length === 0 ||
      (res.length === 1 && res[0].isComment) // #9658
    ) ? undefined
      : res
  };
  // this is a slot using the new v-slot syntax without scope. although it is
  // compiled as a scoped slot, render fn users would expect it to be present
  // on this.$slots because the usage is semantically a normal slot.
  if (fn.proxy) {
    Object.defineProperty(normalSlots, key, {
      get: normalized,
      enumerable: true,
      configurable: true
    });
  }
  return normalized
}

function proxyNormalSlot(slots, key) {
  return function () { return slots[key]; }
}

/*  */

/**
 * Runtime helper for rendering v-for lists.
 */
function renderList (
  val,
  render
) {
  var ret, i, l, keys, key;
  if (Array.isArray(val) || typeof val === 'string') {
    ret = new Array(val.length);
    for (i = 0, l = val.length; i < l; i++) {
      ret[i] = render(val[i], i);
    }
  } else if (typeof val === 'number') {
    ret = new Array(val);
    for (i = 0; i < val; i++) {
      ret[i] = render(i + 1, i);
    }
  } else if (isObject(val)) {
    if (hasSymbol && val[Symbol.iterator]) {
      ret = [];
      var iterator = val[Symbol.iterator]();
      var result = iterator.next();
      while (!result.done) {
        ret.push(render(result.value, ret.length));
        result = iterator.next();
      }
    } else {
      keys = Object.keys(val);
      ret = new Array(keys.length);
      for (i = 0, l = keys.length; i < l; i++) {
        key = keys[i];
        ret[i] = render(val[key], key, i);
      }
    }
  }
  if (!isDef(ret)) {
    ret = [];
  }
  (ret)._isVList = true;
  return ret
}

/*  */

/**
 * Runtime helper for rendering <slot>
 */
function renderSlot (
  name,
  fallback,
  props,
  bindObject
) {
  var scopedSlotFn = this.$scopedSlots[name];
  var nodes;
  if (scopedSlotFn) { // scoped slot
    props = props || {};
    if (bindObject) {
      if (!isObject(bindObject)) {
        warn(
          'slot v-bind without argument expects an Object',
          this
        );
      }
      props = extend(extend({}, bindObject), props);
    }
    nodes = scopedSlotFn(props) || fallback;
  } else {
    nodes = this.$slots[name] || fallback;
  }

  var target = props && props.slot;
  if (target) {
    return this.$createElement('template', { slot: target }, nodes)
  } else {
    return nodes
  }
}

/*  */

/**
 * Runtime helper for resolving filters
 */
function resolveFilter (id) {
  return resolveAsset(this.$options, 'filters', id, true) || identity
}

/*  */

function isKeyNotMatch (expect, actual) {
  if (Array.isArray(expect)) {
    return expect.indexOf(actual) === -1
  } else {
    return expect !== actual
  }
}

/**
 * Runtime helper for checking keyCodes from config.
 * exposed as Vue.prototype._k
 * passing in eventKeyName as last argument separately for backwards compat
 */
function checkKeyCodes (
  eventKeyCode,
  key,
  builtInKeyCode,
  eventKeyName,
  builtInKeyName
) {
  var mappedKeyCode = config.keyCodes[key] || builtInKeyCode;
  if (builtInKeyName && eventKeyName && !config.keyCodes[key]) {
    return isKeyNotMatch(builtInKeyName, eventKeyName)
  } else if (mappedKeyCode) {
    return isKeyNotMatch(mappedKeyCode, eventKeyCode)
  } else if (eventKeyName) {
    return hyphenate(eventKeyName) !== key
  }
}

/*  */

/**
 * Runtime helper for merging v-bind="object" into a VNode's data.
 */
function bindObjectProps (
  data,
  tag,
  value,
  asProp,
  isSync
) {
  if (value) {
    if (!isObject(value)) {
      warn(
        'v-bind without argument expects an Object or Array value',
        this
      );
    } else {
      if (Array.isArray(value)) {
        value = toObject(value);
      }
      var hash;
      var loop = function ( key ) {
        if (
          key === 'class' ||
          key === 'style' ||
          isReservedAttribute(key)
        ) {
          hash = data;
        } else {
          var type = data.attrs && data.attrs.type;
          hash = asProp || config.mustUseProp(tag, type, key)
            ? data.domProps || (data.domProps = {})
            : data.attrs || (data.attrs = {});
        }
        var camelizedKey = camelize(key);
        var hyphenatedKey = hyphenate(key);
        if (!(camelizedKey in hash) && !(hyphenatedKey in hash)) {
          hash[key] = value[key];

          if (isSync) {
            var on = data.on || (data.on = {});
            on[("update:" + key)] = function ($event) {
              value[key] = $event;
            };
          }
        }
      };

      for (var key in value) loop( key );
    }
  }
  return data
}

/*  */

/**
 * Runtime helper for rendering static trees.
 */
function renderStatic (
  index,
  isInFor
) {
  var cached = this._staticTrees || (this._staticTrees = []);
  var tree = cached[index];
  // if has already-rendered static tree and not inside v-for,
  // we can reuse the same tree.
  if (tree && !isInFor) {
    return tree
  }
  // otherwise, render a fresh tree.
  tree = cached[index] = this.$options.staticRenderFns[index].call(
    this._renderProxy,
    null,
    this // for render fns generated for functional component templates
  );
  markStatic(tree, ("__static__" + index), false);
  return tree
}

/**
 * Runtime helper for v-once.
 * Effectively it means marking the node as static with a unique key.
 */
function markOnce (
  tree,
  index,
  key
) {
  markStatic(tree, ("__once__" + index + (key ? ("_" + key) : "")), true);
  return tree
}

function markStatic (
  tree,
  key,
  isOnce
) {
  if (Array.isArray(tree)) {
    for (var i = 0; i < tree.length; i++) {
      if (tree[i] && typeof tree[i] !== 'string') {
        markStaticNode(tree[i], (key + "_" + i), isOnce);
      }
    }
  } else {
    markStaticNode(tree, key, isOnce);
  }
}

function markStaticNode (node, key, isOnce) {
  node.isStatic = true;
  node.key = key;
  node.isOnce = isOnce;
}

/*  */

function bindObjectListeners (data, value) {
  if (value) {
    if (!isPlainObject(value)) {
      warn(
        'v-on without argument expects an Object value',
        this
      );
    } else {
      var on = data.on = data.on ? extend({}, data.on) : {};
      for (var key in value) {
        var existing = on[key];
        var ours = value[key];
        on[key] = existing ? [].concat(existing, ours) : ours;
      }
    }
  }
  return data
}

/*  */

function resolveScopedSlots (
  fns, // see flow/vnode
  res,
  // the following are added in 2.6
  hasDynamicKeys,
  contentHashKey
) {
  res = res || { $stable: !hasDynamicKeys };
  for (var i = 0; i < fns.length; i++) {
    var slot = fns[i];
    if (Array.isArray(slot)) {
      resolveScopedSlots(slot, res, hasDynamicKeys);
    } else if (slot) {
      // marker for reverse proxying v-slot without scope on this.$slots
      if (slot.proxy) {
        slot.fn.proxy = true;
      }
      res[slot.key] = slot.fn;
    }
  }
  if (contentHashKey) {
    (res).$key = contentHashKey;
  }
  return res
}

/*  */

function bindDynamicKeys (baseObj, values) {
  for (var i = 0; i < values.length; i += 2) {
    var key = values[i];
    if (typeof key === 'string' && key) {
      baseObj[values[i]] = values[i + 1];
    } else if (key !== '' && key !== null) {
      // null is a special value for explicitly removing a binding
      warn(
        ("Invalid value for dynamic directive argument (expected string or null): " + key),
        this
      );
    }
  }
  return baseObj
}

// helper to dynamically append modifier runtime markers to event names.
// ensure only append when value is already string, otherwise it will be cast
// to string and cause the type check to miss.
function prependModifier (value, symbol) {
  return typeof value === 'string' ? symbol + value : value
}

/*  */

function installRenderHelpers (target) {
  target._o = markOnce;
  target._n = toNumber;
  target._s = toString;
  target._l = renderList;
  target._t = renderSlot;
  target._q = looseEqual;
  target._i = looseIndexOf;
  target._m = renderStatic;
  target._f = resolveFilter;
  target._k = checkKeyCodes;
  target._b = bindObjectProps;
  target._v = createTextVNode;
  target._e = createEmptyVNode;
  target._u = resolveScopedSlots;
  target._g = bindObjectListeners;
  target._d = bindDynamicKeys;
  target._p = prependModifier;
}

/*  */

function FunctionalRenderContext (
  data,
  props,
  children,
  parent,
  Ctor
) {
  var this$1 = this;

  var options = Ctor.options;
  // ensure the createElement function in functional components
  // gets a unique context - this is necessary for correct named slot check
  var contextVm;
  if (hasOwn(parent, '_uid')) {
    contextVm = Object.create(parent);
    // $flow-disable-line
    contextVm._original = parent;
  } else {
    // the context vm passed in is a functional context as well.
    // in this case we want to make sure we are able to get a hold to the
    // real context instance.
    contextVm = parent;
    // $flow-disable-line
    parent = parent._original;
  }
  var isCompiled = isTrue(options._compiled);
  var needNormalization = !isCompiled;

  this.data = data;
  this.props = props;
  this.children = children;
  this.parent = parent;
  this.listeners = data.on || emptyObject;
  this.injections = resolveInject(options.inject, parent);
  this.slots = function () {
    if (!this$1.$slots) {
      normalizeScopedSlots(
        data.scopedSlots,
        this$1.$slots = resolveSlots(children, parent)
      );
    }
    return this$1.$slots
  };

  Object.defineProperty(this, 'scopedSlots', ({
    enumerable: true,
    get: function get () {
      return normalizeScopedSlots(data.scopedSlots, this.slots())
    }
  }));

  // support for compiled functional template
  if (isCompiled) {
    // exposing $options for renderStatic()
    this.$options = options;
    // pre-resolve slots for renderSlot()
    this.$slots = this.slots();
    this.$scopedSlots = normalizeScopedSlots(data.scopedSlots, this.$slots);
  }

  if (options._scopeId) {
    this._c = function (a, b, c, d) {
      var vnode = createElement(contextVm, a, b, c, d, needNormalization);
      if (vnode && !Array.isArray(vnode)) {
        vnode.fnScopeId = options._scopeId;
        vnode.fnContext = parent;
      }
      return vnode
    };
  } else {
    this._c = function (a, b, c, d) { return createElement(contextVm, a, b, c, d, needNormalization); };
  }
}

installRenderHelpers(FunctionalRenderContext.prototype);

function createFunctionalComponent (
  Ctor,
  propsData,
  data,
  contextVm,
  children
) {
  var options = Ctor.options;
  var props = {};
  var propOptions = options.props;
  if (isDef(propOptions)) {
    for (var key in propOptions) {
      props[key] = validateProp(key, propOptions, propsData || emptyObject);
    }
  } else {
    if (isDef(data.attrs)) { mergeProps(props, data.attrs); }
    if (isDef(data.props)) { mergeProps(props, data.props); }
  }

  var renderContext = new FunctionalRenderContext(
    data,
    props,
    children,
    contextVm,
    Ctor
  );

  var vnode = options.render.call(null, renderContext._c, renderContext);

  if (vnode instanceof VNode) {
    return cloneAndMarkFunctionalResult(vnode, data, renderContext.parent, options, renderContext)
  } else if (Array.isArray(vnode)) {
    var vnodes = normalizeChildren(vnode) || [];
    var res = new Array(vnodes.length);
    for (var i = 0; i < vnodes.length; i++) {
      res[i] = cloneAndMarkFunctionalResult(vnodes[i], data, renderContext.parent, options, renderContext);
    }
    return res
  }
}

function cloneAndMarkFunctionalResult (vnode, data, contextVm, options, renderContext) {
  // #7817 clone node before setting fnContext, otherwise if the node is reused
  // (e.g. it was from a cached normal slot) the fnContext causes named slots
  // that should not be matched to match.
  var clone = cloneVNode(vnode);
  clone.fnContext = contextVm;
  clone.fnOptions = options;
  {
    (clone.devtoolsMeta = clone.devtoolsMeta || {}).renderContext = renderContext;
  }
  if (data.slot) {
    (clone.data || (clone.data = {})).slot = data.slot;
  }
  return clone
}

function mergeProps (to, from) {
  for (var key in from) {
    to[camelize(key)] = from[key];
  }
}

/*  */

/*  */

/*  */

/*  */

// inline hooks to be invoked on component VNodes during patch
var componentVNodeHooks = {
  init: function init (vnode, hydrating) {
    if (
      vnode.componentInstance &&
      !vnode.componentInstance._isDestroyed &&
      vnode.data.keepAlive
    ) {
      // kept-alive components, treat as a patch
      var mountedNode = vnode; // work around flow
      componentVNodeHooks.prepatch(mountedNode, mountedNode);
    } else {
      var child = vnode.componentInstance = createComponentInstanceForVnode(
        vnode,
        activeInstance
      );
      child.$mount(hydrating ? vnode.elm : undefined, hydrating);
    }
  },

  prepatch: function prepatch (oldVnode, vnode) {
    var options = vnode.componentOptions;
    var child = vnode.componentInstance = oldVnode.componentInstance;
    updateChildComponent(
      child,
      options.propsData, // updated props
      options.listeners, // updated listeners
      vnode, // new parent vnode
      options.children // new children
    );
  },

  insert: function insert (vnode) {
    var context = vnode.context;
    var componentInstance = vnode.componentInstance;
    if (!componentInstance._isMounted) {
      componentInstance._isMounted = true;
      callHook(componentInstance, 'mounted');
    }
    if (vnode.data.keepAlive) {
      if (context._isMounted) {
        // vue-router#1212
        // During updates, a kept-alive component's child components may
        // change, so directly walking the tree here may call activated hooks
        // on incorrect children. Instead we push them into a queue which will
        // be processed after the whole patch process ended.
        queueActivatedComponent(componentInstance);
      } else {
        activateChildComponent(componentInstance, true /* direct */);
      }
    }
  },

  destroy: function destroy (vnode) {
    var componentInstance = vnode.componentInstance;
    if (!componentInstance._isDestroyed) {
      if (!vnode.data.keepAlive) {
        componentInstance.$destroy();
      } else {
        deactivateChildComponent(componentInstance, true /* direct */);
      }
    }
  }
};

var hooksToMerge = Object.keys(componentVNodeHooks);

function createComponent (
  Ctor,
  data,
  context,
  children,
  tag
) {
  if (isUndef(Ctor)) {
    return
  }

  var baseCtor = context.$options._base;

  // plain options object: turn it into a constructor
  if (isObject(Ctor)) {
    Ctor = baseCtor.extend(Ctor);
  }

  // if at this stage it's not a constructor or an async component factory,
  // reject.
  if (typeof Ctor !== 'function') {
    {
      warn(("Invalid Component definition: " + (String(Ctor))), context);
    }
    return
  }

  // async component
  var asyncFactory;
  if (isUndef(Ctor.cid)) {
    asyncFactory = Ctor;
    Ctor = resolveAsyncComponent(asyncFactory, baseCtor);
    if (Ctor === undefined) {
      // return a placeholder node for async component, which is rendered
      // as a comment node but preserves all the raw information for the node.
      // the information will be used for async server-rendering and hydration.
      return createAsyncPlaceholder(
        asyncFactory,
        data,
        context,
        children,
        tag
      )
    }
  }

  data = data || {};

  // resolve constructor options in case global mixins are applied after
  // component constructor creation
  resolveConstructorOptions(Ctor);

  // transform component v-model data into props & events
  if (isDef(data.model)) {
    transformModel(Ctor.options, data);
  }

  // extract props
  var propsData = extractPropsFromVNodeData(data, Ctor, tag);

  // functional component
  if (isTrue(Ctor.options.functional)) {
    return createFunctionalComponent(Ctor, propsData, data, context, children)
  }

  // extract listeners, since these needs to be treated as
  // child component listeners instead of DOM listeners
  var listeners = data.on;
  // replace with listeners with .native modifier
  // so it gets processed during parent component patch.
  data.on = data.nativeOn;

  if (isTrue(Ctor.options.abstract)) {
    // abstract components do not keep anything
    // other than props & listeners & slot

    // work around flow
    var slot = data.slot;
    data = {};
    if (slot) {
      data.slot = slot;
    }
  }

  // install component management hooks onto the placeholder node
  installComponentHooks(data);

  // return a placeholder vnode
  var name = Ctor.options.name || tag;
  var vnode = new VNode(
    ("vue-component-" + (Ctor.cid) + (name ? ("-" + name) : '')),
    data, undefined, undefined, undefined, context,
    { Ctor: Ctor, propsData: propsData, listeners: listeners, tag: tag, children: children },
    asyncFactory
  );

  return vnode
}

function createComponentInstanceForVnode (
  vnode, // we know it's MountedComponentVNode but flow doesn't
  parent // activeInstance in lifecycle state
) {
  var options = {
    _isComponent: true,
    _parentVnode: vnode,
    parent: parent
  };
  // check inline-template render functions
  var inlineTemplate = vnode.data.inlineTemplate;
  if (isDef(inlineTemplate)) {
    options.render = inlineTemplate.render;
    options.staticRenderFns = inlineTemplate.staticRenderFns;
  }
  return new vnode.componentOptions.Ctor(options)
}

function installComponentHooks (data) {
  var hooks = data.hook || (data.hook = {});
  for (var i = 0; i < hooksToMerge.length; i++) {
    var key = hooksToMerge[i];
    var existing = hooks[key];
    var toMerge = componentVNodeHooks[key];
    if (existing !== toMerge && !(existing && existing._merged)) {
      hooks[key] = existing ? mergeHook$1(toMerge, existing) : toMerge;
    }
  }
}

function mergeHook$1 (f1, f2) {
  var merged = function (a, b) {
    // flow complains about extra args which is why we use any
    f1(a, b);
    f2(a, b);
  };
  merged._merged = true;
  return merged
}

// transform component v-model info (value and callback) into
// prop and event handler respectively.
function transformModel (options, data) {
  var prop = (options.model && options.model.prop) || 'value';
  var event = (options.model && options.model.event) || 'input'
  ;(data.attrs || (data.attrs = {}))[prop] = data.model.value;
  var on = data.on || (data.on = {});
  var existing = on[event];
  var callback = data.model.callback;
  if (isDef(existing)) {
    if (
      Array.isArray(existing)
        ? existing.indexOf(callback) === -1
        : existing !== callback
    ) {
      on[event] = [callback].concat(existing);
    }
  } else {
    on[event] = callback;
  }
}

/*  */

var SIMPLE_NORMALIZE = 1;
var ALWAYS_NORMALIZE = 2;

// wrapper function for providing a more flexible interface
// without getting yelled at by flow
function createElement (
  context,
  tag,
  data,
  children,
  normalizationType,
  alwaysNormalize
) {
  if (Array.isArray(data) || isPrimitive(data)) {
    normalizationType = children;
    children = data;
    data = undefined;
  }
  if (isTrue(alwaysNormalize)) {
    normalizationType = ALWAYS_NORMALIZE;
  }
  return _createElement(context, tag, data, children, normalizationType)
}

function _createElement (
  context,
  tag,
  data,
  children,
  normalizationType
) {
  if (isDef(data) && isDef((data).__ob__)) {
    warn(
      "Avoid using observed data object as vnode data: " + (JSON.stringify(data)) + "\n" +
      'Always create fresh vnode data objects in each render!',
      context
    );
    return createEmptyVNode()
  }
  // object syntax in v-bind
  if (isDef(data) && isDef(data.is)) {
    tag = data.is;
  }
  if (!tag) {
    // in case of component :is set to falsy value
    return createEmptyVNode()
  }
  // warn against non-primitive key
  if (isDef(data) && isDef(data.key) && !isPrimitive(data.key)
  ) {
    {
      warn(
        'Avoid using non-primitive value as key, ' +
        'use string/number value instead.',
        context
      );
    }
  }
  // support single function children as default scoped slot
  if (Array.isArray(children) &&
    typeof children[0] === 'function'
  ) {
    data = data || {};
    data.scopedSlots = { default: children[0] };
    children.length = 0;
  }
  if (normalizationType === ALWAYS_NORMALIZE) {
    children = normalizeChildren(children);
  } else if (normalizationType === SIMPLE_NORMALIZE) {
    children = simpleNormalizeChildren(children);
  }
  var vnode, ns;
  if (typeof tag === 'string') {
    var Ctor;
    ns = (context.$vnode && context.$vnode.ns) || config.getTagNamespace(tag);
    if (config.isReservedTag(tag)) {
      // platform built-in elements
      if (isDef(data) && isDef(data.nativeOn)) {
        warn(
          ("The .native modifier for v-on is only valid on components but it was used on <" + tag + ">."),
          context
        );
      }
      vnode = new VNode(
        config.parsePlatformTagName(tag), data, children,
        undefined, undefined, context
      );
    } else if ((!data || !data.pre) && isDef(Ctor = resolveAsset(context.$options, 'components', tag))) {
      // component
      vnode = createComponent(Ctor, data, context, children, tag);
    } else {
      // unknown or unlisted namespaced elements
      // check at runtime because it may get assigned a namespace when its
      // parent normalizes children
      vnode = new VNode(
        tag, data, children,
        undefined, undefined, context
      );
    }
  } else {
    // direct component options / constructor
    vnode = createComponent(tag, data, context, children);
  }
  if (Array.isArray(vnode)) {
    return vnode
  } else if (isDef(vnode)) {
    if (isDef(ns)) { applyNS(vnode, ns); }
    if (isDef(data)) { registerDeepBindings(data); }
    return vnode
  } else {
    return createEmptyVNode()
  }
}

function applyNS (vnode, ns, force) {
  vnode.ns = ns;
  if (vnode.tag === 'foreignObject') {
    // use default namespace inside foreignObject
    ns = undefined;
    force = true;
  }
  if (isDef(vnode.children)) {
    for (var i = 0, l = vnode.children.length; i < l; i++) {
      var child = vnode.children[i];
      if (isDef(child.tag) && (
        isUndef(child.ns) || (isTrue(force) && child.tag !== 'svg'))) {
        applyNS(child, ns, force);
      }
    }
  }
}

// ref #5318
// necessary to ensure parent re-render when deep bindings like :style and
// :class are used on slot nodes
function registerDeepBindings (data) {
  if (isObject(data.style)) {
    traverse(data.style);
  }
  if (isObject(data.class)) {
    traverse(data.class);
  }
}

/*  */

function initRender (vm) {
  vm._vnode = null; // the root of the child tree
  vm._staticTrees = null; // v-once cached trees
  var options = vm.$options;
  var parentVnode = vm.$vnode = options._parentVnode; // the placeholder node in parent tree
  var renderContext = parentVnode && parentVnode.context;
  vm.$slots = resolveSlots(options._renderChildren, renderContext);
  vm.$scopedSlots = emptyObject;
  // bind the createElement fn to this instance
  // so that we get proper render context inside it.
  // args order: tag, data, children, normalizationType, alwaysNormalize
  // internal version is used by render functions compiled from templates
  vm._c = function (a, b, c, d) { return createElement(vm, a, b, c, d, false); };
  // normalization is always applied for the public version, used in
  // user-written render functions.
  vm.$createElement = function (a, b, c, d) { return createElement(vm, a, b, c, d, true); };

  // $attrs & $listeners are exposed for easier HOC creation.
  // they need to be reactive so that HOCs using them are always updated
  var parentData = parentVnode && parentVnode.data;

  /* istanbul ignore else */
  {
    defineReactive$$1(vm, '$attrs', parentData && parentData.attrs || emptyObject, function () {
      !isUpdatingChildComponent && warn("$attrs is readonly.", vm);
    }, true);
    defineReactive$$1(vm, '$listeners', options._parentListeners || emptyObject, function () {
      !isUpdatingChildComponent && warn("$listeners is readonly.", vm);
    }, true);
  }
}

var currentRenderingInstance = null;

function renderMixin (Vue) {
  // install runtime convenience helpers
  installRenderHelpers(Vue.prototype);

  Vue.prototype.$nextTick = function (fn) {
    return nextTick(fn, this)
  };

  Vue.prototype._render = function () {
    var vm = this;
    var ref = vm.$options;
    var render = ref.render;
    var _parentVnode = ref._parentVnode;

    if (_parentVnode) {
      vm.$scopedSlots = normalizeScopedSlots(
        _parentVnode.data.scopedSlots,
        vm.$slots,
        vm.$scopedSlots
      );
    }

    // set parent vnode. this allows render functions to have access
    // to the data on the placeholder node.
    vm.$vnode = _parentVnode;
    // render self
    var vnode;
    try {
      // There's no need to maintain a stack because all render fns are called
      // separately from one another. Nested component's render fns are called
      // when parent component is patched.
      currentRenderingInstance = vm;
      vnode = render.call(vm._renderProxy, vm.$createElement);
    } catch (e) {
      handleError(e, vm, "render");
      // return error render result,
      // or previous vnode to prevent render error causing blank component
      /* istanbul ignore else */
      if (vm.$options.renderError) {
        try {
          vnode = vm.$options.renderError.call(vm._renderProxy, vm.$createElement, e);
        } catch (e) {
          handleError(e, vm, "renderError");
          vnode = vm._vnode;
        }
      } else {
        vnode = vm._vnode;
      }
    } finally {
      currentRenderingInstance = null;
    }
    // if the returned array contains only a single node, allow it
    if (Array.isArray(vnode) && vnode.length === 1) {
      vnode = vnode[0];
    }
    // return empty vnode in case the render function errored out
    if (!(vnode instanceof VNode)) {
      if (Array.isArray(vnode)) {
        warn(
          'Multiple root nodes returned from render function. Render function ' +
          'should return a single root node.',
          vm
        );
      }
      vnode = createEmptyVNode();
    }
    // set parent
    vnode.parent = _parentVnode;
    return vnode
  };
}

/*  */

function ensureCtor (comp, base) {
  if (
    comp.__esModule ||
    (hasSymbol && comp[Symbol.toStringTag] === 'Module')
  ) {
    comp = comp.default;
  }
  return isObject(comp)
    ? base.extend(comp)
    : comp
}

function createAsyncPlaceholder (
  factory,
  data,
  context,
  children,
  tag
) {
  var node = createEmptyVNode();
  node.asyncFactory = factory;
  node.asyncMeta = { data: data, context: context, children: children, tag: tag };
  return node
}

function resolveAsyncComponent (
  factory,
  baseCtor
) {
  if (isTrue(factory.error) && isDef(factory.errorComp)) {
    return factory.errorComp
  }

  if (isDef(factory.resolved)) {
    return factory.resolved
  }

  var owner = currentRenderingInstance;
  if (owner && isDef(factory.owners) && factory.owners.indexOf(owner) === -1) {
    // already pending
    factory.owners.push(owner);
  }

  if (isTrue(factory.loading) && isDef(factory.loadingComp)) {
    return factory.loadingComp
  }

  if (owner && !isDef(factory.owners)) {
    var owners = factory.owners = [owner];
    var sync = true;
    var timerLoading = null;
    var timerTimeout = null

    ;(owner).$on('hook:destroyed', function () { return remove(owners, owner); });

    var forceRender = function (renderCompleted) {
      for (var i = 0, l = owners.length; i < l; i++) {
        (owners[i]).$forceUpdate();
      }

      if (renderCompleted) {
        owners.length = 0;
        if (timerLoading !== null) {
          clearTimeout(timerLoading);
          timerLoading = null;
        }
        if (timerTimeout !== null) {
          clearTimeout(timerTimeout);
          timerTimeout = null;
        }
      }
    };

    var resolve = once(function (res) {
      // cache resolved
      factory.resolved = ensureCtor(res, baseCtor);
      // invoke callbacks only if this is not a synchronous resolve
      // (async resolves are shimmed as synchronous during SSR)
      if (!sync) {
        forceRender(true);
      } else {
        owners.length = 0;
      }
    });

    var reject = once(function (reason) {
      warn(
        "Failed to resolve async component: " + (String(factory)) +
        (reason ? ("\nReason: " + reason) : '')
      );
      if (isDef(factory.errorComp)) {
        factory.error = true;
        forceRender(true);
      }
    });

    var res = factory(resolve, reject);

    if (isObject(res)) {
      if (isPromise(res)) {
        // () => Promise
        if (isUndef(factory.resolved)) {
          res.then(resolve, reject);
        }
      } else if (isPromise(res.component)) {
        res.component.then(resolve, reject);

        if (isDef(res.error)) {
          factory.errorComp = ensureCtor(res.error, baseCtor);
        }

        if (isDef(res.loading)) {
          factory.loadingComp = ensureCtor(res.loading, baseCtor);
          if (res.delay === 0) {
            factory.loading = true;
          } else {
            timerLoading = setTimeout(function () {
              timerLoading = null;
              if (isUndef(factory.resolved) && isUndef(factory.error)) {
                factory.loading = true;
                forceRender(false);
              }
            }, res.delay || 200);
          }
        }

        if (isDef(res.timeout)) {
          timerTimeout = setTimeout(function () {
            timerTimeout = null;
            if (isUndef(factory.resolved)) {
              reject(
                "timeout (" + (res.timeout) + "ms)"
              );
            }
          }, res.timeout);
        }
      }
    }

    sync = false;
    // return in case resolved synchronously
    return factory.loading
      ? factory.loadingComp
      : factory.resolved
  }
}

/*  */

function isAsyncPlaceholder (node) {
  return node.isComment && node.asyncFactory
}

/*  */

function getFirstComponentChild (children) {
  if (Array.isArray(children)) {
    for (var i = 0; i < children.length; i++) {
      var c = children[i];
      if (isDef(c) && (isDef(c.componentOptions) || isAsyncPlaceholder(c))) {
        return c
      }
    }
  }
}

/*  */

/*  */

function initEvents (vm) {
  vm._events = Object.create(null);
  vm._hasHookEvent = false;
  // init parent attached events
  var listeners = vm.$options._parentListeners;
  if (listeners) {
    updateComponentListeners(vm, listeners);
  }
}

var target;

function add (event, fn) {
  target.$on(event, fn);
}

function remove$1 (event, fn) {
  target.$off(event, fn);
}

function createOnceHandler (event, fn) {
  var _target = target;
  return function onceHandler () {
    var res = fn.apply(null, arguments);
    if (res !== null) {
      _target.$off(event, onceHandler);
    }
  }
}

function updateComponentListeners (
  vm,
  listeners,
  oldListeners
) {
  target = vm;
  updateListeners(listeners, oldListeners || {}, add, remove$1, createOnceHandler, vm);
  target = undefined;
}

function eventsMixin (Vue) {
  var hookRE = /^hook:/;
  Vue.prototype.$on = function (event, fn) {
    var vm = this;
    if (Array.isArray(event)) {
      for (var i = 0, l = event.length; i < l; i++) {
        vm.$on(event[i], fn);
      }
    } else {
      (vm._events[event] || (vm._events[event] = [])).push(fn);
      // optimize hook:event cost by using a boolean flag marked at registration
      // instead of a hash lookup
      if (hookRE.test(event)) {
        vm._hasHookEvent = true;
      }
    }
    return vm
  };

  Vue.prototype.$once = function (event, fn) {
    var vm = this;
    function on () {
      vm.$off(event, on);
      fn.apply(vm, arguments);
    }
    on.fn = fn;
    vm.$on(event, on);
    return vm
  };

  Vue.prototype.$off = function (event, fn) {
    var vm = this;
    // all
    if (!arguments.length) {
      vm._events = Object.create(null);
      return vm
    }
    // array of events
    if (Array.isArray(event)) {
      for (var i$1 = 0, l = event.length; i$1 < l; i$1++) {
        vm.$off(event[i$1], fn);
      }
      return vm
    }
    // specific event
    var cbs = vm._events[event];
    if (!cbs) {
      return vm
    }
    if (!fn) {
      vm._events[event] = null;
      return vm
    }
    // specific handler
    var cb;
    var i = cbs.length;
    while (i--) {
      cb = cbs[i];
      if (cb === fn || cb.fn === fn) {
        cbs.splice(i, 1);
        break
      }
    }
    return vm
  };

  Vue.prototype.$emit = function (event) {
    var vm = this;
    {
      var lowerCaseEvent = event.toLowerCase();
      if (lowerCaseEvent !== event && vm._events[lowerCaseEvent]) {
        tip(
          "Event \"" + lowerCaseEvent + "\" is emitted in component " +
          (formatComponentName(vm)) + " but the handler is registered for \"" + event + "\". " +
          "Note that HTML attributes are case-insensitive and you cannot use " +
          "v-on to listen to camelCase events when using in-DOM templates. " +
          "You should probably use \"" + (hyphenate(event)) + "\" instead of \"" + event + "\"."
        );
      }
    }
    var cbs = vm._events[event];
    if (cbs) {
      cbs = cbs.length > 1 ? toArray(cbs) : cbs;
      var args = toArray(arguments, 1);
      var info = "event handler for \"" + event + "\"";
      for (var i = 0, l = cbs.length; i < l; i++) {
        invokeWithErrorHandling(cbs[i], vm, args, vm, info);
      }
    }
    return vm
  };
}

/*  */

var activeInstance = null;
var isUpdatingChildComponent = false;

function setActiveInstance(vm) {
  var prevActiveInstance = activeInstance;
  activeInstance = vm;
  return function () {
    activeInstance = prevActiveInstance;
  }
}

function initLifecycle (vm) {
  var options = vm.$options;

  // locate first non-abstract parent
  var parent = options.parent;
  if (parent && !options.abstract) {
    while (parent.$options.abstract && parent.$parent) {
      parent = parent.$parent;
    }
    parent.$children.push(vm);
  }

  vm.$parent = parent;
  vm.$root = parent ? parent.$root : vm;

  vm.$children = [];
  vm.$refs = {};

  vm._watcher = null;
  vm._inactive = null;
  vm._directInactive = false;
  vm._isMounted = false;
  vm._isDestroyed = false;
  vm._isBeingDestroyed = false;
}

function lifecycleMixin (Vue) {
  Vue.prototype._update = function (vnode, hydrating) {
    var vm = this;
    var prevEl = vm.$el;
    var prevVnode = vm._vnode;
    var restoreActiveInstance = setActiveInstance(vm);
    vm._vnode = vnode;
    // Vue.prototype.__patch__ is injected in entry points
    // based on the rendering backend used.
    if (!prevVnode) {
      // initial render
      vm.$el = vm.__patch__(vm.$el, vnode, hydrating, false /* removeOnly */);
    } else {
      // updates
      vm.$el = vm.__patch__(prevVnode, vnode);
    }
    restoreActiveInstance();
    // update __vue__ reference
    if (prevEl) {
      prevEl.__vue__ = null;
    }
    if (vm.$el) {
      vm.$el.__vue__ = vm;
    }
    // if parent is an HOC, update its $el as well
    if (vm.$vnode && vm.$parent && vm.$vnode === vm.$parent._vnode) {
      vm.$parent.$el = vm.$el;
    }
    // updated hook is called by the scheduler to ensure that children are
    // updated in a parent's updated hook.
  };

  Vue.prototype.$forceUpdate = function () {
    var vm = this;
    if (vm._watcher) {
      vm._watcher.update();
    }
  };

  Vue.prototype.$destroy = function () {
    var vm = this;
    if (vm._isBeingDestroyed) {
      return
    }
    callHook(vm, 'beforeDestroy');
    vm._isBeingDestroyed = true;
    // remove self from parent
    var parent = vm.$parent;
    if (parent && !parent._isBeingDestroyed && !vm.$options.abstract) {
      remove(parent.$children, vm);
    }
    // teardown watchers
    if (vm._watcher) {
      vm._watcher.teardown();
    }
    var i = vm._watchers.length;
    while (i--) {
      vm._watchers[i].teardown();
    }
    // remove reference from data ob
    // frozen object may not have observer.
    if (vm._data.__ob__) {
      vm._data.__ob__.vmCount--;
    }
    // call the last hook...
    vm._isDestroyed = true;
    // invoke destroy hooks on current rendered tree
    vm.__patch__(vm._vnode, null);
    // fire destroyed hook
    callHook(vm, 'destroyed');
    // turn off all instance listeners.
    vm.$off();
    // remove __vue__ reference
    if (vm.$el) {
      vm.$el.__vue__ = null;
    }
    // release circular reference (#6759)
    if (vm.$vnode) {
      vm.$vnode.parent = null;
    }
  };
}

function mountComponent (
  vm,
  el,
  hydrating
) {
  vm.$el = el;
  if (!vm.$options.render) {
    vm.$options.render = createEmptyVNode;
    {
      /* istanbul ignore if */
      if ((vm.$options.template && vm.$options.template.charAt(0) !== '#') ||
        vm.$options.el || el) {
        warn(
          'You are using the runtime-only build of Vue where the template ' +
          'compiler is not available. Either pre-compile the templates into ' +
          'render functions, or use the compiler-included build.',
          vm
        );
      } else {
        warn(
          'Failed to mount component: template or render function not defined.',
          vm
        );
      }
    }
  }
  callHook(vm, 'beforeMount');

  var updateComponent;
  /* istanbul ignore if */
  if (config.performance && mark) {
    updateComponent = function () {
      var name = vm._name;
      var id = vm._uid;
      var startTag = "vue-perf-start:" + id;
      var endTag = "vue-perf-end:" + id;

      mark(startTag);
      var vnode = vm._render();
      mark(endTag);
      measure(("vue " + name + " render"), startTag, endTag);

      mark(startTag);
      vm._update(vnode, hydrating);
      mark(endTag);
      measure(("vue " + name + " patch"), startTag, endTag);
    };
  } else {
    updateComponent = function () {
      vm._update(vm._render(), hydrating);
    };
  }

  // we set this to vm._watcher inside the watcher's constructor
  // since the watcher's initial patch may call $forceUpdate (e.g. inside child
  // component's mounted hook), which relies on vm._watcher being already defined
  new Watcher(vm, updateComponent, noop, {
    before: function before () {
      if (vm._isMounted && !vm._isDestroyed) {
        callHook(vm, 'beforeUpdate');
      }
    }
  }, true /* isRenderWatcher */);
  hydrating = false;

  // manually mounted instance, call mounted on self
  // mounted is called for render-created child components in its inserted hook
  if (vm.$vnode == null) {
    vm._isMounted = true;
    callHook(vm, 'mounted');
  }
  return vm
}

function updateChildComponent (
  vm,
  propsData,
  listeners,
  parentVnode,
  renderChildren
) {
  {
    isUpdatingChildComponent = true;
  }

  // determine whether component has slot children
  // we need to do this before overwriting $options._renderChildren.

  // check if there are dynamic scopedSlots (hand-written or compiled but with
  // dynamic slot names). Static scoped slots compiled from template has the
  // "$stable" marker.
  var newScopedSlots = parentVnode.data.scopedSlots;
  var oldScopedSlots = vm.$scopedSlots;
  var hasDynamicScopedSlot = !!(
    (newScopedSlots && !newScopedSlots.$stable) ||
    (oldScopedSlots !== emptyObject && !oldScopedSlots.$stable) ||
    (newScopedSlots && vm.$scopedSlots.$key !== newScopedSlots.$key)
  );

  // Any static slot children from the parent may have changed during parent's
  // update. Dynamic scoped slots may also have changed. In such cases, a forced
  // update is necessary to ensure correctness.
  var needsForceUpdate = !!(
    renderChildren ||               // has new static slots
    vm.$options._renderChildren ||  // has old static slots
    hasDynamicScopedSlot
  );

  vm.$options._parentVnode = parentVnode;
  vm.$vnode = parentVnode; // update vm's placeholder node without re-render

  if (vm._vnode) { // update child tree's parent
    vm._vnode.parent = parentVnode;
  }
  vm.$options._renderChildren = renderChildren;

  // update $attrs and $listeners hash
  // these are also reactive so they may trigger child update if the child
  // used them during render
  vm.$attrs = parentVnode.data.attrs || emptyObject;
  vm.$listeners = listeners || emptyObject;

  // update props
  if (propsData && vm.$options.props) {
    toggleObserving(false);
    var props = vm._props;
    var propKeys = vm.$options._propKeys || [];
    for (var i = 0; i < propKeys.length; i++) {
      var key = propKeys[i];
      var propOptions = vm.$options.props; // wtf flow?
      props[key] = validateProp(key, propOptions, propsData, vm);
    }
    toggleObserving(true);
    // keep a copy of raw propsData
    vm.$options.propsData = propsData;
  }

  // update listeners
  listeners = listeners || emptyObject;
  var oldListeners = vm.$options._parentListeners;
  vm.$options._parentListeners = listeners;
  updateComponentListeners(vm, listeners, oldListeners);

  // resolve slots + force update if has children
  if (needsForceUpdate) {
    vm.$slots = resolveSlots(renderChildren, parentVnode.context);
    vm.$forceUpdate();
  }

  {
    isUpdatingChildComponent = false;
  }
}

function isInInactiveTree (vm) {
  while (vm && (vm = vm.$parent)) {
    if (vm._inactive) { return true }
  }
  return false
}

function activateChildComponent (vm, direct) {
  if (direct) {
    vm._directInactive = false;
    if (isInInactiveTree(vm)) {
      return
    }
  } else if (vm._directInactive) {
    return
  }
  if (vm._inactive || vm._inactive === null) {
    vm._inactive = false;
    for (var i = 0; i < vm.$children.length; i++) {
      activateChildComponent(vm.$children[i]);
    }
    callHook(vm, 'activated');
  }
}

function deactivateChildComponent (vm, direct) {
  if (direct) {
    vm._directInactive = true;
    if (isInInactiveTree(vm)) {
      return
    }
  }
  if (!vm._inactive) {
    vm._inactive = true;
    for (var i = 0; i < vm.$children.length; i++) {
      deactivateChildComponent(vm.$children[i]);
    }
    callHook(vm, 'deactivated');
  }
}

function callHook (vm, hook) {
  // #7573 disable dep collection when invoking lifecycle hooks
  pushTarget();
  var handlers = vm.$options[hook];
  var info = hook + " hook";
  if (handlers) {
    for (var i = 0, j = handlers.length; i < j; i++) {
      invokeWithErrorHandling(handlers[i], vm, null, vm, info);
    }
  }
  if (vm._hasHookEvent) {
    vm.$emit('hook:' + hook);
  }
  popTarget();
}

/*  */

var MAX_UPDATE_COUNT = 100;

var queue = [];
var activatedChildren = [];
var has = {};
var circular = {};
var waiting = false;
var flushing = false;
var index = 0;

/**
 * Reset the scheduler's state.
 */
function resetSchedulerState () {
  index = queue.length = activatedChildren.length = 0;
  has = {};
  {
    circular = {};
  }
  waiting = flushing = false;
}

// Async edge case #6566 requires saving the timestamp when event listeners are
// attached. However, calling performance.now() has a perf overhead especially
// if the page has thousands of event listeners. Instead, we take a timestamp
// every time the scheduler flushes and use that for all event listeners
// attached during that flush.
var currentFlushTimestamp = 0;

// Async edge case fix requires storing an event listener's attach timestamp.
var getNow = Date.now;

// Determine what event timestamp the browser is using. Annoyingly, the
// timestamp can either be hi-res (relative to page load) or low-res
// (relative to UNIX epoch), so in order to compare time we have to use the
// same timestamp type when saving the flush timestamp.
// All IE versions use low-res event timestamps, and have problematic clock
// implementations (#9632)
if (inBrowser && !isIE) {
  var performance = window.performance;
  if (
    performance &&
    typeof performance.now === 'function' &&
    getNow() > document.createEvent('Event').timeStamp
  ) {
    // if the event timestamp, although evaluated AFTER the Date.now(), is
    // smaller than it, it means the event is using a hi-res timestamp,
    // and we need to use the hi-res version for event listener timestamps as
    // well.
    getNow = function () { return performance.now(); };
  }
}

/**
 * Flush both queues and run the watchers.
 */
function flushSchedulerQueue () {
  currentFlushTimestamp = getNow();
  flushing = true;
  var watcher, id;

  // Sort queue before flush.
  // This ensures that:
  // 1. Components are updated from parent to child. (because parent is always
  //    created before the child)
  // 2. A component's user watchers are run before its render watcher (because
  //    user watchers are created before the render watcher)
  // 3. If a component is destroyed during a parent component's watcher run,
  //    its watchers can be skipped.
  queue.sort(function (a, b) { return a.id - b.id; });

  // do not cache length because more watchers might be pushed
  // as we run existing watchers
  for (index = 0; index < queue.length; index++) {
    watcher = queue[index];
    if (watcher.before) {
      watcher.before();
    }
    id = watcher.id;
    has[id] = null;
    watcher.run();
    // in dev build, check and stop circular updates.
    if (has[id] != null) {
      circular[id] = (circular[id] || 0) + 1;
      if (circular[id] > MAX_UPDATE_COUNT) {
        warn(
          'You may have an infinite update loop ' + (
            watcher.user
              ? ("in watcher with expression \"" + (watcher.expression) + "\"")
              : "in a component render function."
          ),
          watcher.vm
        );
        break
      }
    }
  }

  // keep copies of post queues before resetting state
  var activatedQueue = activatedChildren.slice();
  var updatedQueue = queue.slice();

  resetSchedulerState();

  // call component updated and activated hooks
  callActivatedHooks(activatedQueue);
  callUpdatedHooks(updatedQueue);

  // devtool hook
  /* istanbul ignore if */
  if (devtools && config.devtools) {
    devtools.emit('flush');
  }
}

function callUpdatedHooks (queue) {
  var i = queue.length;
  while (i--) {
    var watcher = queue[i];
    var vm = watcher.vm;
    if (vm._watcher === watcher && vm._isMounted && !vm._isDestroyed) {
      callHook(vm, 'updated');
    }
  }
}

/**
 * Queue a kept-alive component that was activated during patch.
 * The queue will be processed after the entire tree has been patched.
 */
function queueActivatedComponent (vm) {
  // setting _inactive to false here so that a render function can
  // rely on checking whether it's in an inactive tree (e.g. router-view)
  vm._inactive = false;
  activatedChildren.push(vm);
}

function callActivatedHooks (queue) {
  for (var i = 0; i < queue.length; i++) {
    queue[i]._inactive = true;
    activateChildComponent(queue[i], true /* true */);
  }
}

/**
 * Push a watcher into the watcher queue.
 * Jobs with duplicate IDs will be skipped unless it's
 * pushed when the queue is being flushed.
 */
function queueWatcher (watcher) {
  var id = watcher.id;
  if (has[id] == null) {
    has[id] = true;
    if (!flushing) {
      queue.push(watcher);
    } else {
      // if already flushing, splice the watcher based on its id
      // if already past its id, it will be run next immediately.
      var i = queue.length - 1;
      while (i > index && queue[i].id > watcher.id) {
        i--;
      }
      queue.splice(i + 1, 0, watcher);
    }
    // queue the flush
    if (!waiting) {
      waiting = true;

      if (!config.async) {
        flushSchedulerQueue();
        return
      }
      nextTick(flushSchedulerQueue);
    }
  }
}

/*  */



var uid$2 = 0;

/**
 * A watcher parses an expression, collects dependencies,
 * and fires callback when the expression value changes.
 * This is used for both the $watch() api and directives.
 */
var Watcher = function Watcher (
  vm,
  expOrFn,
  cb,
  options,
  isRenderWatcher
) {
  this.vm = vm;
  if (isRenderWatcher) {
    vm._watcher = this;
  }
  vm._watchers.push(this);
  // options
  if (options) {
    this.deep = !!options.deep;
    this.user = !!options.user;
    this.lazy = !!options.lazy;
    this.sync = !!options.sync;
    this.before = options.before;
  } else {
    this.deep = this.user = this.lazy = this.sync = false;
  }
  this.cb = cb;
  this.id = ++uid$2; // uid for batching
  this.active = true;
  this.dirty = this.lazy; // for lazy watchers
  this.deps = [];
  this.newDeps = [];
  this.depIds = new _Set();
  this.newDepIds = new _Set();
  this.expression = expOrFn.toString();
  // parse expression for getter
  if (typeof expOrFn === 'function') {
    this.getter = expOrFn;
  } else {
    this.getter = parsePath(expOrFn);
    if (!this.getter) {
      this.getter = noop;
      warn(
        "Failed watching path: \"" + expOrFn + "\" " +
        'Watcher only accepts simple dot-delimited paths. ' +
        'For full control, use a function instead.',
        vm
      );
    }
  }
  this.value = this.lazy
    ? undefined
    : this.get();
};

/**
 * Evaluate the getter, and re-collect dependencies.
 */
Watcher.prototype.get = function get () {
  pushTarget(this);
  var value;
  var vm = this.vm;
  try {
    value = this.getter.call(vm, vm);
  } catch (e) {
    if (this.user) {
      handleError(e, vm, ("getter for watcher \"" + (this.expression) + "\""));
    } else {
      throw e
    }
  } finally {
    // "touch" every property so they are all tracked as
    // dependencies for deep watching
    if (this.deep) {
      traverse(value);
    }
    popTarget();
    this.cleanupDeps();
  }
  return value
};

/**
 * Add a dependency to this directive.
 */
Watcher.prototype.addDep = function addDep (dep) {
  var id = dep.id;
  if (!this.newDepIds.has(id)) {
    this.newDepIds.add(id);
    this.newDeps.push(dep);
    if (!this.depIds.has(id)) {
      dep.addSub(this);
    }
  }
};

/**
 * Clean up for dependency collection.
 */
Watcher.prototype.cleanupDeps = function cleanupDeps () {
  var i = this.deps.length;
  while (i--) {
    var dep = this.deps[i];
    if (!this.newDepIds.has(dep.id)) {
      dep.removeSub(this);
    }
  }
  var tmp = this.depIds;
  this.depIds = this.newDepIds;
  this.newDepIds = tmp;
  this.newDepIds.clear();
  tmp = this.deps;
  this.deps = this.newDeps;
  this.newDeps = tmp;
  this.newDeps.length = 0;
};

/**
 * Subscriber interface.
 * Will be called when a dependency changes.
 */
Watcher.prototype.update = function update () {
  /* istanbul ignore else */
  if (this.lazy) {
    this.dirty = true;
  } else if (this.sync) {
    this.run();
  } else {
    queueWatcher(this);
  }
};

/**
 * Scheduler job interface.
 * Will be called by the scheduler.
 */
Watcher.prototype.run = function run () {
  if (this.active) {
    var value = this.get();
    if (
      value !== this.value ||
      // Deep watchers and watchers on Object/Arrays should fire even
      // when the value is the same, because the value may
      // have mutated.
      isObject(value) ||
      this.deep
    ) {
      // set new value
      var oldValue = this.value;
      this.value = value;
      if (this.user) {
        try {
          this.cb.call(this.vm, value, oldValue);
        } catch (e) {
          handleError(e, this.vm, ("callback for watcher \"" + (this.expression) + "\""));
        }
      } else {
        this.cb.call(this.vm, value, oldValue);
      }
    }
  }
};

/**
 * Evaluate the value of the watcher.
 * This only gets called for lazy watchers.
 */
Watcher.prototype.evaluate = function evaluate () {
  this.value = this.get();
  this.dirty = false;
};

/**
 * Depend on all deps collected by this watcher.
 */
Watcher.prototype.depend = function depend () {
  var i = this.deps.length;
  while (i--) {
    this.deps[i].depend();
  }
};

/**
 * Remove self from all dependencies' subscriber list.
 */
Watcher.prototype.teardown = function teardown () {
  if (this.active) {
    // remove self from vm's watcher list
    // this is a somewhat expensive operation so we skip it
    // if the vm is being destroyed.
    if (!this.vm._isBeingDestroyed) {
      remove(this.vm._watchers, this);
    }
    var i = this.deps.length;
    while (i--) {
      this.deps[i].removeSub(this);
    }
    this.active = false;
  }
};

/*  */

var sharedPropertyDefinition = {
  enumerable: true,
  configurable: true,
  get: noop,
  set: noop
};

function proxy (target, sourceKey, key) {
  sharedPropertyDefinition.get = function proxyGetter () {
    return this[sourceKey][key]
  };
  sharedPropertyDefinition.set = function proxySetter (val) {
    this[sourceKey][key] = val;
  };
  Object.defineProperty(target, key, sharedPropertyDefinition);
}

function initState (vm) {
  vm._watchers = [];
  var opts = vm.$options;
  if (opts.props) { initProps(vm, opts.props); }
  if (opts.methods) { initMethods(vm, opts.methods); }
  if (opts.data) {
    initData(vm);
  } else {
    observe(vm._data = {}, true /* asRootData */);
  }
  if (opts.computed) { initComputed(vm, opts.computed); }
  if (opts.watch && opts.watch !== nativeWatch) {
    initWatch(vm, opts.watch);
  }
}

function initProps (vm, propsOptions) {
  var propsData = vm.$options.propsData || {};
  var props = vm._props = {};
  // cache prop keys so that future props updates can iterate using Array
  // instead of dynamic object key enumeration.
  var keys = vm.$options._propKeys = [];
  var isRoot = !vm.$parent;
  // root instance props should be converted
  if (!isRoot) {
    toggleObserving(false);
  }
  var loop = function ( key ) {
    keys.push(key);
    var value = validateProp(key, propsOptions, propsData, vm);
    /* istanbul ignore else */
    {
      var hyphenatedKey = hyphenate(key);
      if (isReservedAttribute(hyphenatedKey) ||
          config.isReservedAttr(hyphenatedKey)) {
        warn(
          ("\"" + hyphenatedKey + "\" is a reserved attribute and cannot be used as component prop."),
          vm
        );
      }
      defineReactive$$1(props, key, value, function () {
        if (!isRoot && !isUpdatingChildComponent) {
          warn(
            "Avoid mutating a prop directly since the value will be " +
            "overwritten whenever the parent component re-renders. " +
            "Instead, use a data or computed property based on the prop's " +
            "value. Prop being mutated: \"" + key + "\"",
            vm
          );
        }
      });
    }
    // static props are already proxied on the component's prototype
    // during Vue.extend(). We only need to proxy props defined at
    // instantiation here.
    if (!(key in vm)) {
      proxy(vm, "_props", key);
    }
  };

  for (var key in propsOptions) loop( key );
  toggleObserving(true);
}

function initData (vm) {
  var data = vm.$options.data;
  data = vm._data = typeof data === 'function'
    ? getData(data, vm)
    : data || {};
  if (!isPlainObject(data)) {
    data = {};
    warn(
      'data functions should return an object:\n' +
      'https://vuejs.org/v2/guide/components.html#data-Must-Be-a-Function',
      vm
    );
  }
  // proxy data on instance
  var keys = Object.keys(data);
  var props = vm.$options.props;
  var methods = vm.$options.methods;
  var i = keys.length;
  while (i--) {
    var key = keys[i];
    {
      if (methods && hasOwn(methods, key)) {
        warn(
          ("Method \"" + key + "\" has already been defined as a data property."),
          vm
        );
      }
    }
    if (props && hasOwn(props, key)) {
      warn(
        "The data property \"" + key + "\" is already declared as a prop. " +
        "Use prop default value instead.",
        vm
      );
    } else if (!isReserved(key)) {
      proxy(vm, "_data", key);
    }
  }
  // observe data
  observe(data, true /* asRootData */);
}

function getData (data, vm) {
  // #7573 disable dep collection when invoking data getters
  pushTarget();
  try {
    return data.call(vm, vm)
  } catch (e) {
    handleError(e, vm, "data()");
    return {}
  } finally {
    popTarget();
  }
}

var computedWatcherOptions = { lazy: true };

function initComputed (vm, computed) {
  // $flow-disable-line
  var watchers = vm._computedWatchers = Object.create(null);
  // computed properties are just getters during SSR
  var isSSR = isServerRendering();

  for (var key in computed) {
    var userDef = computed[key];
    var getter = typeof userDef === 'function' ? userDef : userDef.get;
    if (getter == null) {
      warn(
        ("Getter is missing for computed property \"" + key + "\"."),
        vm
      );
    }

    if (!isSSR) {
      // create internal watcher for the computed property.
      watchers[key] = new Watcher(
        vm,
        getter || noop,
        noop,
        computedWatcherOptions
      );
    }

    // component-defined computed properties are already defined on the
    // component prototype. We only need to define computed properties defined
    // at instantiation here.
    if (!(key in vm)) {
      defineComputed(vm, key, userDef);
    } else {
      if (key in vm.$data) {
        warn(("The computed property \"" + key + "\" is already defined in data."), vm);
      } else if (vm.$options.props && key in vm.$options.props) {
        warn(("The computed property \"" + key + "\" is already defined as a prop."), vm);
      }
    }
  }
}

function defineComputed (
  target,
  key,
  userDef
) {
  var shouldCache = !isServerRendering();
  if (typeof userDef === 'function') {
    sharedPropertyDefinition.get = shouldCache
      ? createComputedGetter(key)
      : createGetterInvoker(userDef);
    sharedPropertyDefinition.set = noop;
  } else {
    sharedPropertyDefinition.get = userDef.get
      ? shouldCache && userDef.cache !== false
        ? createComputedGetter(key)
        : createGetterInvoker(userDef.get)
      : noop;
    sharedPropertyDefinition.set = userDef.set || noop;
  }
  if (sharedPropertyDefinition.set === noop) {
    sharedPropertyDefinition.set = function () {
      warn(
        ("Computed property \"" + key + "\" was assigned to but it has no setter."),
        this
      );
    };
  }
  Object.defineProperty(target, key, sharedPropertyDefinition);
}

function createComputedGetter (key) {
  return function computedGetter () {
    var watcher = this._computedWatchers && this._computedWatchers[key];
    if (watcher) {
      if (watcher.dirty) {
        watcher.evaluate();
      }
      if (Dep.target) {
        watcher.depend();
      }
      return watcher.value
    }
  }
}

function createGetterInvoker(fn) {
  return function computedGetter () {
    return fn.call(this, this)
  }
}

function initMethods (vm, methods) {
  var props = vm.$options.props;
  for (var key in methods) {
    {
      if (typeof methods[key] !== 'function') {
        warn(
          "Method \"" + key + "\" has type \"" + (typeof methods[key]) + "\" in the component definition. " +
          "Did you reference the function correctly?",
          vm
        );
      }
      if (props && hasOwn(props, key)) {
        warn(
          ("Method \"" + key + "\" has already been defined as a prop."),
          vm
        );
      }
      if ((key in vm) && isReserved(key)) {
        warn(
          "Method \"" + key + "\" conflicts with an existing Vue instance method. " +
          "Avoid defining component methods that start with _ or $."
        );
      }
    }
    vm[key] = typeof methods[key] !== 'function' ? noop : bind(methods[key], vm);
  }
}

function initWatch (vm, watch) {
  for (var key in watch) {
    var handler = watch[key];
    if (Array.isArray(handler)) {
      for (var i = 0; i < handler.length; i++) {
        createWatcher(vm, key, handler[i]);
      }
    } else {
      createWatcher(vm, key, handler);
    }
  }
}

function createWatcher (
  vm,
  expOrFn,
  handler,
  options
) {
  if (isPlainObject(handler)) {
    options = handler;
    handler = handler.handler;
  }
  if (typeof handler === 'string') {
    handler = vm[handler];
  }
  return vm.$watch(expOrFn, handler, options)
}

function stateMixin (Vue) {
  // flow somehow has problems with directly declared definition object
  // when using Object.defineProperty, so we have to procedurally build up
  // the object here.
  var dataDef = {};
  dataDef.get = function () { return this._data };
  var propsDef = {};
  propsDef.get = function () { return this._props };
  {
    dataDef.set = function () {
      warn(
        'Avoid replacing instance root $data. ' +
        'Use nested data properties instead.',
        this
      );
    };
    propsDef.set = function () {
      warn("$props is readonly.", this);
    };
  }
  Object.defineProperty(Vue.prototype, '$data', dataDef);
  Object.defineProperty(Vue.prototype, '$props', propsDef);

  Vue.prototype.$set = set;
  Vue.prototype.$delete = del;

  Vue.prototype.$watch = function (
    expOrFn,
    cb,
    options
  ) {
    var vm = this;
    if (isPlainObject(cb)) {
      return createWatcher(vm, expOrFn, cb, options)
    }
    options = options || {};
    options.user = true;
    var watcher = new Watcher(vm, expOrFn, cb, options);
    if (options.immediate) {
      try {
        cb.call(vm, watcher.value);
      } catch (error) {
        handleError(error, vm, ("callback for immediate watcher \"" + (watcher.expression) + "\""));
      }
    }
    return function unwatchFn () {
      watcher.teardown();
    }
  };
}

/*  */

var uid$3 = 0;

function initMixin (Vue) {
  Vue.prototype._init = function (options) {
    var vm = this;
    // a uid
    vm._uid = uid$3++;

    var startTag, endTag;
    /* istanbul ignore if */
    if (config.performance && mark) {
      startTag = "vue-perf-start:" + (vm._uid);
      endTag = "vue-perf-end:" + (vm._uid);
      mark(startTag);
    }

    // a flag to avoid this being observed
    vm._isVue = true;
    // merge options
    if (options && options._isComponent) {
      // optimize internal component instantiation
      // since dynamic options merging is pretty slow, and none of the
      // internal component options needs special treatment.
      initInternalComponent(vm, options);
    } else {
      vm.$options = mergeOptions(
        resolveConstructorOptions(vm.constructor),
        options || {},
        vm
      );
    }
    /* istanbul ignore else */
    {
      initProxy(vm);
    }
    // expose real self
    vm._self = vm;
    initLifecycle(vm);
    initEvents(vm);
    initRender(vm);
    callHook(vm, 'beforeCreate');
    initInjections(vm); // resolve injections before data/props
    initState(vm);
    initProvide(vm); // resolve provide after data/props
    callHook(vm, 'created');

    /* istanbul ignore if */
    if (config.performance && mark) {
      vm._name = formatComponentName(vm, false);
      mark(endTag);
      measure(("vue " + (vm._name) + " init"), startTag, endTag);
    }

    if (vm.$options.el) {
      vm.$mount(vm.$options.el);
    }
  };
}

function initInternalComponent (vm, options) {
  var opts = vm.$options = Object.create(vm.constructor.options);
  // doing this because it's faster than dynamic enumeration.
  var parentVnode = options._parentVnode;
  opts.parent = options.parent;
  opts._parentVnode = parentVnode;

  var vnodeComponentOptions = parentVnode.componentOptions;
  opts.propsData = vnodeComponentOptions.propsData;
  opts._parentListeners = vnodeComponentOptions.listeners;
  opts._renderChildren = vnodeComponentOptions.children;
  opts._componentTag = vnodeComponentOptions.tag;

  if (options.render) {
    opts.render = options.render;
    opts.staticRenderFns = options.staticRenderFns;
  }
}

function resolveConstructorOptions (Ctor) {
  var options = Ctor.options;
  if (Ctor.super) {
    var superOptions = resolveConstructorOptions(Ctor.super);
    var cachedSuperOptions = Ctor.superOptions;
    if (superOptions !== cachedSuperOptions) {
      // super option changed,
      // need to resolve new options.
      Ctor.superOptions = superOptions;
      // check if there are any late-modified/attached options (#4976)
      var modifiedOptions = resolveModifiedOptions(Ctor);
      // update base extend options
      if (modifiedOptions) {
        extend(Ctor.extendOptions, modifiedOptions);
      }
      options = Ctor.options = mergeOptions(superOptions, Ctor.extendOptions);
      if (options.name) {
        options.components[options.name] = Ctor;
      }
    }
  }
  return options
}

function resolveModifiedOptions (Ctor) {
  var modified;
  var latest = Ctor.options;
  var sealed = Ctor.sealedOptions;
  for (var key in latest) {
    if (latest[key] !== sealed[key]) {
      if (!modified) { modified = {}; }
      modified[key] = latest[key];
    }
  }
  return modified
}

function Vue (options) {
  if (!(this instanceof Vue)
  ) {
    warn('Vue is a constructor and should be called with the `new` keyword');
  }
  this._init(options);
}

initMixin(Vue);
stateMixin(Vue);
eventsMixin(Vue);
lifecycleMixin(Vue);
renderMixin(Vue);

/*  */

function initUse (Vue) {
  Vue.use = function (plugin) {
    var installedPlugins = (this._installedPlugins || (this._installedPlugins = []));
    if (installedPlugins.indexOf(plugin) > -1) {
      return this
    }

    // additional parameters
    var args = toArray(arguments, 1);
    args.unshift(this);
    if (typeof plugin.install === 'function') {
      plugin.install.apply(plugin, args);
    } else if (typeof plugin === 'function') {
      plugin.apply(null, args);
    }
    installedPlugins.push(plugin);
    return this
  };
}

/*  */

function initMixin$1 (Vue) {
  Vue.mixin = function (mixin) {
    this.options = mergeOptions(this.options, mixin);
    return this
  };
}

/*  */

function initExtend (Vue) {
  /**
   * Each instance constructor, including Vue, has a unique
   * cid. This enables us to create wrapped "child
   * constructors" for prototypal inheritance and cache them.
   */
  Vue.cid = 0;
  var cid = 1;

  /**
   * Class inheritance
   */
  Vue.extend = function (extendOptions) {
    extendOptions = extendOptions || {};
    var Super = this;
    var SuperId = Super.cid;
    var cachedCtors = extendOptions._Ctor || (extendOptions._Ctor = {});
    if (cachedCtors[SuperId]) {
      return cachedCtors[SuperId]
    }

    var name = extendOptions.name || Super.options.name;
    if (name) {
      validateComponentName(name);
    }

    var Sub = function VueComponent (options) {
      this._init(options);
    };
    Sub.prototype = Object.create(Super.prototype);
    Sub.prototype.constructor = Sub;
    Sub.cid = cid++;
    Sub.options = mergeOptions(
      Super.options,
      extendOptions
    );
    Sub['super'] = Super;

    // For props and computed properties, we define the proxy getters on
    // the Vue instances at extension time, on the extended prototype. This
    // avoids Object.defineProperty calls for each instance created.
    if (Sub.options.props) {
      initProps$1(Sub);
    }
    if (Sub.options.computed) {
      initComputed$1(Sub);
    }

    // allow further extension/mixin/plugin usage
    Sub.extend = Super.extend;
    Sub.mixin = Super.mixin;
    Sub.use = Super.use;

    // create asset registers, so extended classes
    // can have their private assets too.
    ASSET_TYPES.forEach(function (type) {
      Sub[type] = Super[type];
    });
    // enable recursive self-lookup
    if (name) {
      Sub.options.components[name] = Sub;
    }

    // keep a reference to the super options at extension time.
    // later at instantiation we can check if Super's options have
    // been updated.
    Sub.superOptions = Super.options;
    Sub.extendOptions = extendOptions;
    Sub.sealedOptions = extend({}, Sub.options);

    // cache constructor
    cachedCtors[SuperId] = Sub;
    return Sub
  };
}

function initProps$1 (Comp) {
  var props = Comp.options.props;
  for (var key in props) {
    proxy(Comp.prototype, "_props", key);
  }
}

function initComputed$1 (Comp) {
  var computed = Comp.options.computed;
  for (var key in computed) {
    defineComputed(Comp.prototype, key, computed[key]);
  }
}

/*  */

function initAssetRegisters (Vue) {
  /**
   * Create asset registration methods.
   */
  ASSET_TYPES.forEach(function (type) {
    Vue[type] = function (
      id,
      definition
    ) {
      if (!definition) {
        return this.options[type + 's'][id]
      } else {
        /* istanbul ignore if */
        if (type === 'component') {
          validateComponentName(id);
        }
        if (type === 'component' && isPlainObject(definition)) {
          definition.name = definition.name || id;
          definition = this.options._base.extend(definition);
        }
        if (type === 'directive' && typeof definition === 'function') {
          definition = { bind: definition, update: definition };
        }
        this.options[type + 's'][id] = definition;
        return definition
      }
    };
  });
}

/*  */



function getComponentName (opts) {
  return opts && (opts.Ctor.options.name || opts.tag)
}

function matches (pattern, name) {
  if (Array.isArray(pattern)) {
    return pattern.indexOf(name) > -1
  } else if (typeof pattern === 'string') {
    return pattern.split(',').indexOf(name) > -1
  } else if (isRegExp(pattern)) {
    return pattern.test(name)
  }
  /* istanbul ignore next */
  return false
}

function pruneCache (keepAliveInstance, filter) {
  var cache = keepAliveInstance.cache;
  var keys = keepAliveInstance.keys;
  var _vnode = keepAliveInstance._vnode;
  for (var key in cache) {
    var cachedNode = cache[key];
    if (cachedNode) {
      var name = getComponentName(cachedNode.componentOptions);
      if (name && !filter(name)) {
        pruneCacheEntry(cache, key, keys, _vnode);
      }
    }
  }
}

function pruneCacheEntry (
  cache,
  key,
  keys,
  current
) {
  var cached$$1 = cache[key];
  if (cached$$1 && (!current || cached$$1.tag !== current.tag)) {
    cached$$1.componentInstance.$destroy();
  }
  cache[key] = null;
  remove(keys, key);
}

var patternTypes = [String, RegExp, Array];

var KeepAlive = {
  name: 'keep-alive',
  abstract: true,

  props: {
    include: patternTypes,
    exclude: patternTypes,
    max: [String, Number]
  },

  created: function created () {
    this.cache = Object.create(null);
    this.keys = [];
  },

  destroyed: function destroyed () {
    for (var key in this.cache) {
      pruneCacheEntry(this.cache, key, this.keys);
    }
  },

  mounted: function mounted () {
    var this$1 = this;

    this.$watch('include', function (val) {
      pruneCache(this$1, function (name) { return matches(val, name); });
    });
    this.$watch('exclude', function (val) {
      pruneCache(this$1, function (name) { return !matches(val, name); });
    });
  },

  render: function render () {
    var slot = this.$slots.default;
    var vnode = getFirstComponentChild(slot);
    var componentOptions = vnode && vnode.componentOptions;
    if (componentOptions) {
      // check pattern
      var name = getComponentName(componentOptions);
      var ref = this;
      var include = ref.include;
      var exclude = ref.exclude;
      if (
        // not included
        (include && (!name || !matches(include, name))) ||
        // excluded
        (exclude && name && matches(exclude, name))
      ) {
        return vnode
      }

      var ref$1 = this;
      var cache = ref$1.cache;
      var keys = ref$1.keys;
      var key = vnode.key == null
        // same constructor may get registered as different local components
        // so cid alone is not enough (#3269)
        ? componentOptions.Ctor.cid + (componentOptions.tag ? ("::" + (componentOptions.tag)) : '')
        : vnode.key;
      if (cache[key]) {
        vnode.componentInstance = cache[key].componentInstance;
        // make current key freshest
        remove(keys, key);
        keys.push(key);
      } else {
        cache[key] = vnode;
        keys.push(key);
        // prune oldest entry
        if (this.max && keys.length > parseInt(this.max)) {
          pruneCacheEntry(cache, keys[0], keys, this._vnode);
        }
      }

      vnode.data.keepAlive = true;
    }
    return vnode || (slot && slot[0])
  }
};

var builtInComponents = {
  KeepAlive: KeepAlive
};

/*  */

function initGlobalAPI (Vue) {
  // config
  var configDef = {};
  configDef.get = function () { return config; };
  {
    configDef.set = function () {
      warn(
        'Do not replace the Vue.config object, set individual fields instead.'
      );
    };
  }
  Object.defineProperty(Vue, 'config', configDef);

  // exposed util methods.
  // NOTE: these are not considered part of the public API - avoid relying on
  // them unless you are aware of the risk.
  Vue.util = {
    warn: warn,
    extend: extend,
    mergeOptions: mergeOptions,
    defineReactive: defineReactive$$1
  };

  Vue.set = set;
  Vue.delete = del;
  Vue.nextTick = nextTick;

  // 2.6 explicit observable API
  Vue.observable = function (obj) {
    observe(obj);
    return obj
  };

  Vue.options = Object.create(null);
  ASSET_TYPES.forEach(function (type) {
    Vue.options[type + 's'] = Object.create(null);
  });

  // this is used to identify the "base" constructor to extend all plain-object
  // components with in Weex's multi-instance scenarios.
  Vue.options._base = Vue;

  extend(Vue.options.components, builtInComponents);

  initUse(Vue);
  initMixin$1(Vue);
  initExtend(Vue);
  initAssetRegisters(Vue);
}

initGlobalAPI(Vue);

Object.defineProperty(Vue.prototype, '$isServer', {
  get: isServerRendering
});

Object.defineProperty(Vue.prototype, '$ssrContext', {
  get: function get () {
    /* istanbul ignore next */
    return this.$vnode && this.$vnode.ssrContext
  }
});

// expose FunctionalRenderContext for ssr runtime helper installation
Object.defineProperty(Vue, 'FunctionalRenderContext', {
  value: FunctionalRenderContext
});

Vue.version = '2.6.12';

/*  */

// these are reserved for web because they are directly compiled away
// during template compilation
var isReservedAttr = makeMap('style,class');

// attributes that should be using props for binding
var acceptValue = makeMap('input,textarea,option,select,progress');
var mustUseProp = function (tag, type, attr) {
  return (
    (attr === 'value' && acceptValue(tag)) && type !== 'button' ||
    (attr === 'selected' && tag === 'option') ||
    (attr === 'checked' && tag === 'input') ||
    (attr === 'muted' && tag === 'video')
  )
};

var isEnumeratedAttr = makeMap('contenteditable,draggable,spellcheck');

var isValidContentEditableValue = makeMap('events,caret,typing,plaintext-only');

var convertEnumeratedValue = function (key, value) {
  return isFalsyAttrValue(value) || value === 'false'
    ? 'false'
    // allow arbitrary string value for contenteditable
    : key === 'contenteditable' && isValidContentEditableValue(value)
      ? value
      : 'true'
};

var isBooleanAttr = makeMap(
  'allowfullscreen,async,autofocus,autoplay,checked,compact,controls,declare,' +
  'default,defaultchecked,defaultmuted,defaultselected,defer,disabled,' +
  'enabled,formnovalidate,hidden,indeterminate,inert,ismap,itemscope,loop,multiple,' +
  'muted,nohref,noresize,noshade,novalidate,nowrap,open,pauseonexit,readonly,' +
  'required,reversed,scoped,seamless,selected,sortable,translate,' +
  'truespeed,typemustmatch,visible'
);

var xlinkNS = 'http://www.w3.org/1999/xlink';

var isXlink = function (name) {
  return name.charAt(5) === ':' && name.slice(0, 5) === 'xlink'
};

var getXlinkProp = function (name) {
  return isXlink(name) ? name.slice(6, name.length) : ''
};

var isFalsyAttrValue = function (val) {
  return val == null || val === false
};

/*  */

function genClassForVnode (vnode) {
  var data = vnode.data;
  var parentNode = vnode;
  var childNode = vnode;
  while (isDef(childNode.componentInstance)) {
    childNode = childNode.componentInstance._vnode;
    if (childNode && childNode.data) {
      data = mergeClassData(childNode.data, data);
    }
  }
  while (isDef(parentNode = parentNode.parent)) {
    if (parentNode && parentNode.data) {
      data = mergeClassData(data, parentNode.data);
    }
  }
  return renderClass(data.staticClass, data.class)
}

function mergeClassData (child, parent) {
  return {
    staticClass: concat(child.staticClass, parent.staticClass),
    class: isDef(child.class)
      ? [child.class, parent.class]
      : parent.class
  }
}

function renderClass (
  staticClass,
  dynamicClass
) {
  if (isDef(staticClass) || isDef(dynamicClass)) {
    return concat(staticClass, stringifyClass(dynamicClass))
  }
  /* istanbul ignore next */
  return ''
}

function concat (a, b) {
  return a ? b ? (a + ' ' + b) : a : (b || '')
}

function stringifyClass (value) {
  if (Array.isArray(value)) {
    return stringifyArray(value)
  }
  if (isObject(value)) {
    return stringifyObject(value)
  }
  if (typeof value === 'string') {
    return value
  }
  /* istanbul ignore next */
  return ''
}

function stringifyArray (value) {
  var res = '';
  var stringified;
  for (var i = 0, l = value.length; i < l; i++) {
    if (isDef(stringified = stringifyClass(value[i])) && stringified !== '') {
      if (res) { res += ' '; }
      res += stringified;
    }
  }
  return res
}

function stringifyObject (value) {
  var res = '';
  for (var key in value) {
    if (value[key]) {
      if (res) { res += ' '; }
      res += key;
    }
  }
  return res
}

/*  */

var namespaceMap = {
  svg: 'http://www.w3.org/2000/svg',
  math: 'http://www.w3.org/1998/Math/MathML'
};

var isHTMLTag = makeMap(
  'html,body,base,head,link,meta,style,title,' +
  'address,article,aside,footer,header,h1,h2,h3,h4,h5,h6,hgroup,nav,section,' +
  'div,dd,dl,dt,figcaption,figure,picture,hr,img,li,main,ol,p,pre,ul,' +
  'a,b,abbr,bdi,bdo,br,cite,code,data,dfn,em,i,kbd,mark,q,rp,rt,rtc,ruby,' +
  's,samp,small,span,strong,sub,sup,time,u,var,wbr,area,audio,map,track,video,' +
  'embed,object,param,source,canvas,script,noscript,del,ins,' +
  'caption,col,colgroup,table,thead,tbody,td,th,tr,' +
  'button,datalist,fieldset,form,input,label,legend,meter,optgroup,option,' +
  'output,progress,select,textarea,' +
  'details,dialog,menu,menuitem,summary,' +
  'content,element,shadow,template,blockquote,iframe,tfoot'
);

// this map is intentionally selective, only covering SVG elements that may
// contain child elements.
var isSVG = makeMap(
  'svg,animate,circle,clippath,cursor,defs,desc,ellipse,filter,font-face,' +
  'foreignObject,g,glyph,image,line,marker,mask,missing-glyph,path,pattern,' +
  'polygon,polyline,rect,switch,symbol,text,textpath,tspan,use,view',
  true
);

var isPreTag = function (tag) { return tag === 'pre'; };

var isReservedTag = function (tag) {
  return isHTMLTag(tag) || isSVG(tag)
};

function getTagNamespace (tag) {
  if (isSVG(tag)) {
    return 'svg'
  }
  // basic support for MathML
  // note it doesn't support other MathML elements being component roots
  if (tag === 'math') {
    return 'math'
  }
}

var unknownElementCache = Object.create(null);
function isUnknownElement (tag) {
  /* istanbul ignore if */
  if (!inBrowser) {
    return true
  }
  if (isReservedTag(tag)) {
    return false
  }
  tag = tag.toLowerCase();
  /* istanbul ignore if */
  if (unknownElementCache[tag] != null) {
    return unknownElementCache[tag]
  }
  var el = document.createElement(tag);
  if (tag.indexOf('-') > -1) {
    // http://stackoverflow.com/a/28210364/1070244
    return (unknownElementCache[tag] = (
      el.constructor === window.HTMLUnknownElement ||
      el.constructor === window.HTMLElement
    ))
  } else {
    return (unknownElementCache[tag] = /HTMLUnknownElement/.test(el.toString()))
  }
}

var isTextInputType = makeMap('text,number,password,search,email,tel,url');

/*  */

/**
 * Query an element selector if it's not an element already.
 */
function query (el) {
  if (typeof el === 'string') {
    var selected = document.querySelector(el);
    if (!selected) {
      warn(
        'Cannot find element: ' + el
      );
      return document.createElement('div')
    }
    return selected
  } else {
    return el
  }
}

/*  */

function createElement$1 (tagName, vnode) {
  var elm = document.createElement(tagName);
  if (tagName !== 'select') {
    return elm
  }
  // false or null will remove the attribute but undefined will not
  if (vnode.data && vnode.data.attrs && vnode.data.attrs.multiple !== undefined) {
    elm.setAttribute('multiple', 'multiple');
  }
  return elm
}

function createElementNS (namespace, tagName) {
  return document.createElementNS(namespaceMap[namespace], tagName)
}

function createTextNode (text) {
  return document.createTextNode(text)
}

function createComment (text) {
  return document.createComment(text)
}

function insertBefore (parentNode, newNode, referenceNode) {
  parentNode.insertBefore(newNode, referenceNode);
}

function removeChild (node, child) {
  node.removeChild(child);
}

function appendChild (node, child) {
  node.appendChild(child);
}

function parentNode (node) {
  return node.parentNode
}

function nextSibling (node) {
  return node.nextSibling
}

function tagName (node) {
  return node.tagName
}

function setTextContent (node, text) {
  node.textContent = text;
}

function setStyleScope (node, scopeId) {
  node.setAttribute(scopeId, '');
}

var nodeOps = /*#__PURE__*/Object.freeze({
  createElement: createElement$1,
  createElementNS: createElementNS,
  createTextNode: createTextNode,
  createComment: createComment,
  insertBefore: insertBefore,
  removeChild: removeChild,
  appendChild: appendChild,
  parentNode: parentNode,
  nextSibling: nextSibling,
  tagName: tagName,
  setTextContent: setTextContent,
  setStyleScope: setStyleScope
});

/*  */

var ref = {
  create: function create (_, vnode) {
    registerRef(vnode);
  },
  update: function update (oldVnode, vnode) {
    if (oldVnode.data.ref !== vnode.data.ref) {
      registerRef(oldVnode, true);
      registerRef(vnode);
    }
  },
  destroy: function destroy (vnode) {
    registerRef(vnode, true);
  }
};

function registerRef (vnode, isRemoval) {
  var key = vnode.data.ref;
  if (!isDef(key)) { return }

  var vm = vnode.context;
  var ref = vnode.componentInstance || vnode.elm;
  var refs = vm.$refs;
  if (isRemoval) {
    if (Array.isArray(refs[key])) {
      remove(refs[key], ref);
    } else if (refs[key] === ref) {
      refs[key] = undefined;
    }
  } else {
    if (vnode.data.refInFor) {
      if (!Array.isArray(refs[key])) {
        refs[key] = [ref];
      } else if (refs[key].indexOf(ref) < 0) {
        // $flow-disable-line
        refs[key].push(ref);
      }
    } else {
      refs[key] = ref;
    }
  }
}

/**
 * Virtual DOM patching algorithm based on Snabbdom by
 * Simon Friis Vindum (@paldepind)
 * Licensed under the MIT License
 * https://github.com/paldepind/snabbdom/blob/master/LICENSE
 *
 * modified by Evan You (@yyx990803)
 *
 * Not type-checking this because this file is perf-critical and the cost
 * of making flow understand it is not worth it.
 */

var emptyNode = new VNode('', {}, []);

var hooks = ['create', 'activate', 'update', 'remove', 'destroy'];

function sameVnode (a, b) {
  return (
    a.key === b.key && (
      (
        a.tag === b.tag &&
        a.isComment === b.isComment &&
        isDef(a.data) === isDef(b.data) &&
        sameInputType(a, b)
      ) || (
        isTrue(a.isAsyncPlaceholder) &&
        a.asyncFactory === b.asyncFactory &&
        isUndef(b.asyncFactory.error)
      )
    )
  )
}

function sameInputType (a, b) {
  if (a.tag !== 'input') { return true }
  var i;
  var typeA = isDef(i = a.data) && isDef(i = i.attrs) && i.type;
  var typeB = isDef(i = b.data) && isDef(i = i.attrs) && i.type;
  return typeA === typeB || isTextInputType(typeA) && isTextInputType(typeB)
}

function createKeyToOldIdx (children, beginIdx, endIdx) {
  var i, key;
  var map = {};
  for (i = beginIdx; i <= endIdx; ++i) {
    key = children[i].key;
    if (isDef(key)) { map[key] = i; }
  }
  return map
}

function createPatchFunction (backend) {
  var i, j;
  var cbs = {};

  var modules = backend.modules;
  var nodeOps = backend.nodeOps;

  for (i = 0; i < hooks.length; ++i) {
    cbs[hooks[i]] = [];
    for (j = 0; j < modules.length; ++j) {
      if (isDef(modules[j][hooks[i]])) {
        cbs[hooks[i]].push(modules[j][hooks[i]]);
      }
    }
  }

  function emptyNodeAt (elm) {
    return new VNode(nodeOps.tagName(elm).toLowerCase(), {}, [], undefined, elm)
  }

  function createRmCb (childElm, listeners) {
    function remove$$1 () {
      if (--remove$$1.listeners === 0) {
        removeNode(childElm);
      }
    }
    remove$$1.listeners = listeners;
    return remove$$1
  }

  function removeNode (el) {
    var parent = nodeOps.parentNode(el);
    // element may have already been removed due to v-html / v-text
    if (isDef(parent)) {
      nodeOps.removeChild(parent, el);
    }
  }

  function isUnknownElement$$1 (vnode, inVPre) {
    return (
      !inVPre &&
      !vnode.ns &&
      !(
        config.ignoredElements.length &&
        config.ignoredElements.some(function (ignore) {
          return isRegExp(ignore)
            ? ignore.test(vnode.tag)
            : ignore === vnode.tag
        })
      ) &&
      config.isUnknownElement(vnode.tag)
    )
  }

  var creatingElmInVPre = 0;

  function createElm (
    vnode,
    insertedVnodeQueue,
    parentElm,
    refElm,
    nested,
    ownerArray,
    index
  ) {
    if (isDef(vnode.elm) && isDef(ownerArray)) {
      // This vnode was used in a previous render!
      // now it's used as a new node, overwriting its elm would cause
      // potential patch errors down the road when it's used as an insertion
      // reference node. Instead, we clone the node on-demand before creating
      // associated DOM element for it.
      vnode = ownerArray[index] = cloneVNode(vnode);
    }

    vnode.isRootInsert = !nested; // for transition enter check
    if (createComponent(vnode, insertedVnodeQueue, parentElm, refElm)) {
      return
    }

    var data = vnode.data;
    var children = vnode.children;
    var tag = vnode.tag;
    if (isDef(tag)) {
      {
        if (data && data.pre) {
          creatingElmInVPre++;
        }
        if (isUnknownElement$$1(vnode, creatingElmInVPre)) {
          warn(
            'Unknown custom element: <' + tag + '> - did you ' +
            'register the component correctly? For recursive components, ' +
            'make sure to provide the "name" option.',
            vnode.context
          );
        }
      }

      vnode.elm = vnode.ns
        ? nodeOps.createElementNS(vnode.ns, tag)
        : nodeOps.createElement(tag, vnode);
      setScope(vnode);

      /* istanbul ignore if */
      {
        createChildren(vnode, children, insertedVnodeQueue);
        if (isDef(data)) {
          invokeCreateHooks(vnode, insertedVnodeQueue);
        }
        insert(parentElm, vnode.elm, refElm);
      }

      if (data && data.pre) {
        creatingElmInVPre--;
      }
    } else if (isTrue(vnode.isComment)) {
      vnode.elm = nodeOps.createComment(vnode.text);
      insert(parentElm, vnode.elm, refElm);
    } else {
      vnode.elm = nodeOps.createTextNode(vnode.text);
      insert(parentElm, vnode.elm, refElm);
    }
  }

  function createComponent (vnode, insertedVnodeQueue, parentElm, refElm) {
    var i = vnode.data;
    if (isDef(i)) {
      var isReactivated = isDef(vnode.componentInstance) && i.keepAlive;
      if (isDef(i = i.hook) && isDef(i = i.init)) {
        i(vnode, false /* hydrating */);
      }
      // after calling the init hook, if the vnode is a child component
      // it should've created a child instance and mounted it. the child
      // component also has set the placeholder vnode's elm.
      // in that case we can just return the element and be done.
      if (isDef(vnode.componentInstance)) {
        initComponent(vnode, insertedVnodeQueue);
        insert(parentElm, vnode.elm, refElm);
        if (isTrue(isReactivated)) {
          reactivateComponent(vnode, insertedVnodeQueue, parentElm, refElm);
        }
        return true
      }
    }
  }

  function initComponent (vnode, insertedVnodeQueue) {
    if (isDef(vnode.data.pendingInsert)) {
      insertedVnodeQueue.push.apply(insertedVnodeQueue, vnode.data.pendingInsert);
      vnode.data.pendingInsert = null;
    }
    vnode.elm = vnode.componentInstance.$el;
    if (isPatchable(vnode)) {
      invokeCreateHooks(vnode, insertedVnodeQueue);
      setScope(vnode);
    } else {
      // empty component root.
      // skip all element-related modules except for ref (#3455)
      registerRef(vnode);
      // make sure to invoke the insert hook
      insertedVnodeQueue.push(vnode);
    }
  }

  function reactivateComponent (vnode, insertedVnodeQueue, parentElm, refElm) {
    var i;
    // hack for #4339: a reactivated component with inner transition
    // does not trigger because the inner node's created hooks are not called
    // again. It's not ideal to involve module-specific logic in here but
    // there doesn't seem to be a better way to do it.
    var innerNode = vnode;
    while (innerNode.componentInstance) {
      innerNode = innerNode.componentInstance._vnode;
      if (isDef(i = innerNode.data) && isDef(i = i.transition)) {
        for (i = 0; i < cbs.activate.length; ++i) {
          cbs.activate[i](emptyNode, innerNode);
        }
        insertedVnodeQueue.push(innerNode);
        break
      }
    }
    // unlike a newly created component,
    // a reactivated keep-alive component doesn't insert itself
    insert(parentElm, vnode.elm, refElm);
  }

  function insert (parent, elm, ref$$1) {
    if (isDef(parent)) {
      if (isDef(ref$$1)) {
        if (nodeOps.parentNode(ref$$1) === parent) {
          nodeOps.insertBefore(parent, elm, ref$$1);
        }
      } else {
        nodeOps.appendChild(parent, elm);
      }
    }
  }

  function createChildren (vnode, children, insertedVnodeQueue) {
    if (Array.isArray(children)) {
      {
        checkDuplicateKeys(children);
      }
      for (var i = 0; i < children.length; ++i) {
        createElm(children[i], insertedVnodeQueue, vnode.elm, null, true, children, i);
      }
    } else if (isPrimitive(vnode.text)) {
      nodeOps.appendChild(vnode.elm, nodeOps.createTextNode(String(vnode.text)));
    }
  }

  function isPatchable (vnode) {
    while (vnode.componentInstance) {
      vnode = vnode.componentInstance._vnode;
    }
    return isDef(vnode.tag)
  }

  function invokeCreateHooks (vnode, insertedVnodeQueue) {
    for (var i$1 = 0; i$1 < cbs.create.length; ++i$1) {
      cbs.create[i$1](emptyNode, vnode);
    }
    i = vnode.data.hook; // Reuse variable
    if (isDef(i)) {
      if (isDef(i.create)) { i.create(emptyNode, vnode); }
      if (isDef(i.insert)) { insertedVnodeQueue.push(vnode); }
    }
  }

  // set scope id attribute for scoped CSS.
  // this is implemented as a special case to avoid the overhead
  // of going through the normal attribute patching process.
  function setScope (vnode) {
    var i;
    if (isDef(i = vnode.fnScopeId)) {
      nodeOps.setStyleScope(vnode.elm, i);
    } else {
      var ancestor = vnode;
      while (ancestor) {
        if (isDef(i = ancestor.context) && isDef(i = i.$options._scopeId)) {
          nodeOps.setStyleScope(vnode.elm, i);
        }
        ancestor = ancestor.parent;
      }
    }
    // for slot content they should also get the scopeId from the host instance.
    if (isDef(i = activeInstance) &&
      i !== vnode.context &&
      i !== vnode.fnContext &&
      isDef(i = i.$options._scopeId)
    ) {
      nodeOps.setStyleScope(vnode.elm, i);
    }
  }

  function addVnodes (parentElm, refElm, vnodes, startIdx, endIdx, insertedVnodeQueue) {
    for (; startIdx <= endIdx; ++startIdx) {
      createElm(vnodes[startIdx], insertedVnodeQueue, parentElm, refElm, false, vnodes, startIdx);
    }
  }

  function invokeDestroyHook (vnode) {
    var i, j;
    var data = vnode.data;
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.destroy)) { i(vnode); }
      for (i = 0; i < cbs.destroy.length; ++i) { cbs.destroy[i](vnode); }
    }
    if (isDef(i = vnode.children)) {
      for (j = 0; j < vnode.children.length; ++j) {
        invokeDestroyHook(vnode.children[j]);
      }
    }
  }

  function removeVnodes (vnodes, startIdx, endIdx) {
    for (; startIdx <= endIdx; ++startIdx) {
      var ch = vnodes[startIdx];
      if (isDef(ch)) {
        if (isDef(ch.tag)) {
          removeAndInvokeRemoveHook(ch);
          invokeDestroyHook(ch);
        } else { // Text node
          removeNode(ch.elm);
        }
      }
    }
  }

  function removeAndInvokeRemoveHook (vnode, rm) {
    if (isDef(rm) || isDef(vnode.data)) {
      var i;
      var listeners = cbs.remove.length + 1;
      if (isDef(rm)) {
        // we have a recursively passed down rm callback
        // increase the listeners count
        rm.listeners += listeners;
      } else {
        // directly removing
        rm = createRmCb(vnode.elm, listeners);
      }
      // recursively invoke hooks on child component root node
      if (isDef(i = vnode.componentInstance) && isDef(i = i._vnode) && isDef(i.data)) {
        removeAndInvokeRemoveHook(i, rm);
      }
      for (i = 0; i < cbs.remove.length; ++i) {
        cbs.remove[i](vnode, rm);
      }
      if (isDef(i = vnode.data.hook) && isDef(i = i.remove)) {
        i(vnode, rm);
      } else {
        rm();
      }
    } else {
      removeNode(vnode.elm);
    }
  }

  function updateChildren (parentElm, oldCh, newCh, insertedVnodeQueue, removeOnly) {
    var oldStartIdx = 0;
    var newStartIdx = 0;
    var oldEndIdx = oldCh.length - 1;
    var oldStartVnode = oldCh[0];
    var oldEndVnode = oldCh[oldEndIdx];
    var newEndIdx = newCh.length - 1;
    var newStartVnode = newCh[0];
    var newEndVnode = newCh[newEndIdx];
    var oldKeyToIdx, idxInOld, vnodeToMove, refElm;

    // removeOnly is a special flag used only by <transition-group>
    // to ensure removed elements stay in correct relative positions
    // during leaving transitions
    var canMove = !removeOnly;

    {
      checkDuplicateKeys(newCh);
    }

    while (oldStartIdx <= oldEndIdx && newStartIdx <= newEndIdx) {
      if (isUndef(oldStartVnode)) {
        oldStartVnode = oldCh[++oldStartIdx]; // Vnode has been moved left
      } else if (isUndef(oldEndVnode)) {
        oldEndVnode = oldCh[--oldEndIdx];
      } else if (sameVnode(oldStartVnode, newStartVnode)) {
        patchVnode(oldStartVnode, newStartVnode, insertedVnodeQueue, newCh, newStartIdx);
        oldStartVnode = oldCh[++oldStartIdx];
        newStartVnode = newCh[++newStartIdx];
      } else if (sameVnode(oldEndVnode, newEndVnode)) {
        patchVnode(oldEndVnode, newEndVnode, insertedVnodeQueue, newCh, newEndIdx);
        oldEndVnode = oldCh[--oldEndIdx];
        newEndVnode = newCh[--newEndIdx];
      } else if (sameVnode(oldStartVnode, newEndVnode)) { // Vnode moved right
        patchVnode(oldStartVnode, newEndVnode, insertedVnodeQueue, newCh, newEndIdx);
        canMove && nodeOps.insertBefore(parentElm, oldStartVnode.elm, nodeOps.nextSibling(oldEndVnode.elm));
        oldStartVnode = oldCh[++oldStartIdx];
        newEndVnode = newCh[--newEndIdx];
      } else if (sameVnode(oldEndVnode, newStartVnode)) { // Vnode moved left
        patchVnode(oldEndVnode, newStartVnode, insertedVnodeQueue, newCh, newStartIdx);
        canMove && nodeOps.insertBefore(parentElm, oldEndVnode.elm, oldStartVnode.elm);
        oldEndVnode = oldCh[--oldEndIdx];
        newStartVnode = newCh[++newStartIdx];
      } else {
        if (isUndef(oldKeyToIdx)) { oldKeyToIdx = createKeyToOldIdx(oldCh, oldStartIdx, oldEndIdx); }
        idxInOld = isDef(newStartVnode.key)
          ? oldKeyToIdx[newStartVnode.key]
          : findIdxInOld(newStartVnode, oldCh, oldStartIdx, oldEndIdx);
        if (isUndef(idxInOld)) { // New element
          createElm(newStartVnode, insertedVnodeQueue, parentElm, oldStartVnode.elm, false, newCh, newStartIdx);
        } else {
          vnodeToMove = oldCh[idxInOld];
          if (sameVnode(vnodeToMove, newStartVnode)) {
            patchVnode(vnodeToMove, newStartVnode, insertedVnodeQueue, newCh, newStartIdx);
            oldCh[idxInOld] = undefined;
            canMove && nodeOps.insertBefore(parentElm, vnodeToMove.elm, oldStartVnode.elm);
          } else {
            // same key but different element. treat as new element
            createElm(newStartVnode, insertedVnodeQueue, parentElm, oldStartVnode.elm, false, newCh, newStartIdx);
          }
        }
        newStartVnode = newCh[++newStartIdx];
      }
    }
    if (oldStartIdx > oldEndIdx) {
      refElm = isUndef(newCh[newEndIdx + 1]) ? null : newCh[newEndIdx + 1].elm;
      addVnodes(parentElm, refElm, newCh, newStartIdx, newEndIdx, insertedVnodeQueue);
    } else if (newStartIdx > newEndIdx) {
      removeVnodes(oldCh, oldStartIdx, oldEndIdx);
    }
  }

  function checkDuplicateKeys (children) {
    var seenKeys = {};
    for (var i = 0; i < children.length; i++) {
      var vnode = children[i];
      var key = vnode.key;
      if (isDef(key)) {
        if (seenKeys[key]) {
          warn(
            ("Duplicate keys detected: '" + key + "'. This may cause an update error."),
            vnode.context
          );
        } else {
          seenKeys[key] = true;
        }
      }
    }
  }

  function findIdxInOld (node, oldCh, start, end) {
    for (var i = start; i < end; i++) {
      var c = oldCh[i];
      if (isDef(c) && sameVnode(node, c)) { return i }
    }
  }

  function patchVnode (
    oldVnode,
    vnode,
    insertedVnodeQueue,
    ownerArray,
    index,
    removeOnly
  ) {
    if (oldVnode === vnode) {
      return
    }

    if (isDef(vnode.elm) && isDef(ownerArray)) {
      // clone reused vnode
      vnode = ownerArray[index] = cloneVNode(vnode);
    }

    var elm = vnode.elm = oldVnode.elm;

    if (isTrue(oldVnode.isAsyncPlaceholder)) {
      if (isDef(vnode.asyncFactory.resolved)) {
        hydrate(oldVnode.elm, vnode, insertedVnodeQueue);
      } else {
        vnode.isAsyncPlaceholder = true;
      }
      return
    }

    // reuse element for static trees.
    // note we only do this if the vnode is cloned -
    // if the new node is not cloned it means the render functions have been
    // reset by the hot-reload-api and we need to do a proper re-render.
    if (isTrue(vnode.isStatic) &&
      isTrue(oldVnode.isStatic) &&
      vnode.key === oldVnode.key &&
      (isTrue(vnode.isCloned) || isTrue(vnode.isOnce))
    ) {
      vnode.componentInstance = oldVnode.componentInstance;
      return
    }

    var i;
    var data = vnode.data;
    if (isDef(data) && isDef(i = data.hook) && isDef(i = i.prepatch)) {
      i(oldVnode, vnode);
    }

    var oldCh = oldVnode.children;
    var ch = vnode.children;
    if (isDef(data) && isPatchable(vnode)) {
      for (i = 0; i < cbs.update.length; ++i) { cbs.update[i](oldVnode, vnode); }
      if (isDef(i = data.hook) && isDef(i = i.update)) { i(oldVnode, vnode); }
    }
    if (isUndef(vnode.text)) {
      if (isDef(oldCh) && isDef(ch)) {
        if (oldCh !== ch) { updateChildren(elm, oldCh, ch, insertedVnodeQueue, removeOnly); }
      } else if (isDef(ch)) {
        {
          checkDuplicateKeys(ch);
        }
        if (isDef(oldVnode.text)) { nodeOps.setTextContent(elm, ''); }
        addVnodes(elm, null, ch, 0, ch.length - 1, insertedVnodeQueue);
      } else if (isDef(oldCh)) {
        removeVnodes(oldCh, 0, oldCh.length - 1);
      } else if (isDef(oldVnode.text)) {
        nodeOps.setTextContent(elm, '');
      }
    } else if (oldVnode.text !== vnode.text) {
      nodeOps.setTextContent(elm, vnode.text);
    }
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.postpatch)) { i(oldVnode, vnode); }
    }
  }

  function invokeInsertHook (vnode, queue, initial) {
    // delay insert hooks for component root nodes, invoke them after the
    // element is really inserted
    if (isTrue(initial) && isDef(vnode.parent)) {
      vnode.parent.data.pendingInsert = queue;
    } else {
      for (var i = 0; i < queue.length; ++i) {
        queue[i].data.hook.insert(queue[i]);
      }
    }
  }

  var hydrationBailed = false;
  // list of modules that can skip create hook during hydration because they
  // are already rendered on the client or has no need for initialization
  // Note: style is excluded because it relies on initial clone for future
  // deep updates (#7063).
  var isRenderedModule = makeMap('attrs,class,staticClass,staticStyle,key');

  // Note: this is a browser-only function so we can assume elms are DOM nodes.
  function hydrate (elm, vnode, insertedVnodeQueue, inVPre) {
    var i;
    var tag = vnode.tag;
    var data = vnode.data;
    var children = vnode.children;
    inVPre = inVPre || (data && data.pre);
    vnode.elm = elm;

    if (isTrue(vnode.isComment) && isDef(vnode.asyncFactory)) {
      vnode.isAsyncPlaceholder = true;
      return true
    }
    // assert node match
    {
      if (!assertNodeMatch(elm, vnode, inVPre)) {
        return false
      }
    }
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.init)) { i(vnode, true /* hydrating */); }
      if (isDef(i = vnode.componentInstance)) {
        // child component. it should have hydrated its own tree.
        initComponent(vnode, insertedVnodeQueue);
        return true
      }
    }
    if (isDef(tag)) {
      if (isDef(children)) {
        // empty element, allow client to pick up and populate children
        if (!elm.hasChildNodes()) {
          createChildren(vnode, children, insertedVnodeQueue);
        } else {
          // v-html and domProps: innerHTML
          if (isDef(i = data) && isDef(i = i.domProps) && isDef(i = i.innerHTML)) {
            if (i !== elm.innerHTML) {
              /* istanbul ignore if */
              if (typeof console !== 'undefined' &&
                !hydrationBailed
              ) {
                hydrationBailed = true;
                console.warn('Parent: ', elm);
                console.warn('server innerHTML: ', i);
                console.warn('client innerHTML: ', elm.innerHTML);
              }
              return false
            }
          } else {
            // iterate and compare children lists
            var childrenMatch = true;
            var childNode = elm.firstChild;
            for (var i$1 = 0; i$1 < children.length; i$1++) {
              if (!childNode || !hydrate(childNode, children[i$1], insertedVnodeQueue, inVPre)) {
                childrenMatch = false;
                break
              }
              childNode = childNode.nextSibling;
            }
            // if childNode is not null, it means the actual childNodes list is
            // longer than the virtual children list.
            if (!childrenMatch || childNode) {
              /* istanbul ignore if */
              if (typeof console !== 'undefined' &&
                !hydrationBailed
              ) {
                hydrationBailed = true;
                console.warn('Parent: ', elm);
                console.warn('Mismatching childNodes vs. VNodes: ', elm.childNodes, children);
              }
              return false
            }
          }
        }
      }
      if (isDef(data)) {
        var fullInvoke = false;
        for (var key in data) {
          if (!isRenderedModule(key)) {
            fullInvoke = true;
            invokeCreateHooks(vnode, insertedVnodeQueue);
            break
          }
        }
        if (!fullInvoke && data['class']) {
          // ensure collecting deps for deep class bindings for future updates
          traverse(data['class']);
        }
      }
    } else if (elm.data !== vnode.text) {
      elm.data = vnode.text;
    }
    return true
  }

  function assertNodeMatch (node, vnode, inVPre) {
    if (isDef(vnode.tag)) {
      return vnode.tag.indexOf('vue-component') === 0 || (
        !isUnknownElement$$1(vnode, inVPre) &&
        vnode.tag.toLowerCase() === (node.tagName && node.tagName.toLowerCase())
      )
    } else {
      return node.nodeType === (vnode.isComment ? 8 : 3)
    }
  }

  return function patch (oldVnode, vnode, hydrating, removeOnly) {
    if (isUndef(vnode)) {
      if (isDef(oldVnode)) { invokeDestroyHook(oldVnode); }
      return
    }

    var isInitialPatch = false;
    var insertedVnodeQueue = [];

    if (isUndef(oldVnode)) {
      // empty mount (likely as component), create new root element
      isInitialPatch = true;
      createElm(vnode, insertedVnodeQueue);
    } else {
      var isRealElement = isDef(oldVnode.nodeType);
      if (!isRealElement && sameVnode(oldVnode, vnode)) {
        // patch existing root node
        patchVnode(oldVnode, vnode, insertedVnodeQueue, null, null, removeOnly);
      } else {
        if (isRealElement) {
          // mounting to a real element
          // check if this is server-rendered content and if we can perform
          // a successful hydration.
          if (oldVnode.nodeType === 1 && oldVnode.hasAttribute(SSR_ATTR)) {
            oldVnode.removeAttribute(SSR_ATTR);
            hydrating = true;
          }
          if (isTrue(hydrating)) {
            if (hydrate(oldVnode, vnode, insertedVnodeQueue)) {
              invokeInsertHook(vnode, insertedVnodeQueue, true);
              return oldVnode
            } else {
              warn(
                'The client-side rendered virtual DOM tree is not matching ' +
                'server-rendered content. This is likely caused by incorrect ' +
                'HTML markup, for example nesting block-level elements inside ' +
                '<p>, or missing <tbody>. Bailing hydration and performing ' +
                'full client-side render.'
              );
            }
          }
          // either not server-rendered, or hydration failed.
          // create an empty node and replace it
          oldVnode = emptyNodeAt(oldVnode);
        }

        // replacing existing element
        var oldElm = oldVnode.elm;
        var parentElm = nodeOps.parentNode(oldElm);

        // create new node
        createElm(
          vnode,
          insertedVnodeQueue,
          // extremely rare edge case: do not insert if old element is in a
          // leaving transition. Only happens when combining transition +
          // keep-alive + HOCs. (#4590)
          oldElm._leaveCb ? null : parentElm,
          nodeOps.nextSibling(oldElm)
        );

        // update parent placeholder node element, recursively
        if (isDef(vnode.parent)) {
          var ancestor = vnode.parent;
          var patchable = isPatchable(vnode);
          while (ancestor) {
            for (var i = 0; i < cbs.destroy.length; ++i) {
              cbs.destroy[i](ancestor);
            }
            ancestor.elm = vnode.elm;
            if (patchable) {
              for (var i$1 = 0; i$1 < cbs.create.length; ++i$1) {
                cbs.create[i$1](emptyNode, ancestor);
              }
              // #6513
              // invoke insert hooks that may have been merged by create hooks.
              // e.g. for directives that uses the "inserted" hook.
              var insert = ancestor.data.hook.insert;
              if (insert.merged) {
                // start at index 1 to avoid re-invoking component mounted hook
                for (var i$2 = 1; i$2 < insert.fns.length; i$2++) {
                  insert.fns[i$2]();
                }
              }
            } else {
              registerRef(ancestor);
            }
            ancestor = ancestor.parent;
          }
        }

        // destroy old node
        if (isDef(parentElm)) {
          removeVnodes([oldVnode], 0, 0);
        } else if (isDef(oldVnode.tag)) {
          invokeDestroyHook(oldVnode);
        }
      }
    }

    invokeInsertHook(vnode, insertedVnodeQueue, isInitialPatch);
    return vnode.elm
  }
}

/*  */

var directives = {
  create: updateDirectives,
  update: updateDirectives,
  destroy: function unbindDirectives (vnode) {
    updateDirectives(vnode, emptyNode);
  }
};

function updateDirectives (oldVnode, vnode) {
  if (oldVnode.data.directives || vnode.data.directives) {
    _update(oldVnode, vnode);
  }
}

function _update (oldVnode, vnode) {
  var isCreate = oldVnode === emptyNode;
  var isDestroy = vnode === emptyNode;
  var oldDirs = normalizeDirectives$1(oldVnode.data.directives, oldVnode.context);
  var newDirs = normalizeDirectives$1(vnode.data.directives, vnode.context);

  var dirsWithInsert = [];
  var dirsWithPostpatch = [];

  var key, oldDir, dir;
  for (key in newDirs) {
    oldDir = oldDirs[key];
    dir = newDirs[key];
    if (!oldDir) {
      // new directive, bind
      callHook$1(dir, 'bind', vnode, oldVnode);
      if (dir.def && dir.def.inserted) {
        dirsWithInsert.push(dir);
      }
    } else {
      // existing directive, update
      dir.oldValue = oldDir.value;
      dir.oldArg = oldDir.arg;
      callHook$1(dir, 'update', vnode, oldVnode);
      if (dir.def && dir.def.componentUpdated) {
        dirsWithPostpatch.push(dir);
      }
    }
  }

  if (dirsWithInsert.length) {
    var callInsert = function () {
      for (var i = 0; i < dirsWithInsert.length; i++) {
        callHook$1(dirsWithInsert[i], 'inserted', vnode, oldVnode);
      }
    };
    if (isCreate) {
      mergeVNodeHook(vnode, 'insert', callInsert);
    } else {
      callInsert();
    }
  }

  if (dirsWithPostpatch.length) {
    mergeVNodeHook(vnode, 'postpatch', function () {
      for (var i = 0; i < dirsWithPostpatch.length; i++) {
        callHook$1(dirsWithPostpatch[i], 'componentUpdated', vnode, oldVnode);
      }
    });
  }

  if (!isCreate) {
    for (key in oldDirs) {
      if (!newDirs[key]) {
        // no longer present, unbind
        callHook$1(oldDirs[key], 'unbind', oldVnode, oldVnode, isDestroy);
      }
    }
  }
}

var emptyModifiers = Object.create(null);

function normalizeDirectives$1 (
  dirs,
  vm
) {
  var res = Object.create(null);
  if (!dirs) {
    // $flow-disable-line
    return res
  }
  var i, dir;
  for (i = 0; i < dirs.length; i++) {
    dir = dirs[i];
    if (!dir.modifiers) {
      // $flow-disable-line
      dir.modifiers = emptyModifiers;
    }
    res[getRawDirName(dir)] = dir;
    dir.def = resolveAsset(vm.$options, 'directives', dir.name, true);
  }
  // $flow-disable-line
  return res
}

function getRawDirName (dir) {
  return dir.rawName || ((dir.name) + "." + (Object.keys(dir.modifiers || {}).join('.')))
}

function callHook$1 (dir, hook, vnode, oldVnode, isDestroy) {
  var fn = dir.def && dir.def[hook];
  if (fn) {
    try {
      fn(vnode.elm, dir, vnode, oldVnode, isDestroy);
    } catch (e) {
      handleError(e, vnode.context, ("directive " + (dir.name) + " " + hook + " hook"));
    }
  }
}

var baseModules = [
  ref,
  directives
];

/*  */

function updateAttrs (oldVnode, vnode) {
  var opts = vnode.componentOptions;
  if (isDef(opts) && opts.Ctor.options.inheritAttrs === false) {
    return
  }
  if (isUndef(oldVnode.data.attrs) && isUndef(vnode.data.attrs)) {
    return
  }
  var key, cur, old;
  var elm = vnode.elm;
  var oldAttrs = oldVnode.data.attrs || {};
  var attrs = vnode.data.attrs || {};
  // clone observed objects, as the user probably wants to mutate it
  if (isDef(attrs.__ob__)) {
    attrs = vnode.data.attrs = extend({}, attrs);
  }

  for (key in attrs) {
    cur = attrs[key];
    old = oldAttrs[key];
    if (old !== cur) {
      setAttr(elm, key, cur);
    }
  }
  // #4391: in IE9, setting type can reset value for input[type=radio]
  // #6666: IE/Edge forces progress value down to 1 before setting a max
  /* istanbul ignore if */
  if ((isIE || isEdge) && attrs.value !== oldAttrs.value) {
    setAttr(elm, 'value', attrs.value);
  }
  for (key in oldAttrs) {
    if (isUndef(attrs[key])) {
      if (isXlink(key)) {
        elm.removeAttributeNS(xlinkNS, getXlinkProp(key));
      } else if (!isEnumeratedAttr(key)) {
        elm.removeAttribute(key);
      }
    }
  }
}

function setAttr (el, key, value) {
  if (el.tagName.indexOf('-') > -1) {
    baseSetAttr(el, key, value);
  } else if (isBooleanAttr(key)) {
    // set attribute for blank value
    // e.g. <option disabled>Select one</option>
    if (isFalsyAttrValue(value)) {
      el.removeAttribute(key);
    } else {
      // technically allowfullscreen is a boolean attribute for <iframe>,
      // but Flash expects a value of "true" when used on <embed> tag
      value = key === 'allowfullscreen' && el.tagName === 'EMBED'
        ? 'true'
        : key;
      el.setAttribute(key, value);
    }
  } else if (isEnumeratedAttr(key)) {
    el.setAttribute(key, convertEnumeratedValue(key, value));
  } else if (isXlink(key)) {
    if (isFalsyAttrValue(value)) {
      el.removeAttributeNS(xlinkNS, getXlinkProp(key));
    } else {
      el.setAttributeNS(xlinkNS, key, value);
    }
  } else {
    baseSetAttr(el, key, value);
  }
}

function baseSetAttr (el, key, value) {
  if (isFalsyAttrValue(value)) {
    el.removeAttribute(key);
  } else {
    // #7138: IE10 & 11 fires input event when setting placeholder on
    // <textarea>... block the first input event and remove the blocker
    // immediately.
    /* istanbul ignore if */
    if (
      isIE && !isIE9 &&
      el.tagName === 'TEXTAREA' &&
      key === 'placeholder' && value !== '' && !el.__ieph
    ) {
      var blocker = function (e) {
        e.stopImmediatePropagation();
        el.removeEventListener('input', blocker);
      };
      el.addEventListener('input', blocker);
      // $flow-disable-line
      el.__ieph = true; /* IE placeholder patched */
    }
    el.setAttribute(key, value);
  }
}

var attrs = {
  create: updateAttrs,
  update: updateAttrs
};

/*  */

function updateClass (oldVnode, vnode) {
  var el = vnode.elm;
  var data = vnode.data;
  var oldData = oldVnode.data;
  if (
    isUndef(data.staticClass) &&
    isUndef(data.class) && (
      isUndef(oldData) || (
        isUndef(oldData.staticClass) &&
        isUndef(oldData.class)
      )
    )
  ) {
    return
  }

  var cls = genClassForVnode(vnode);

  // handle transition classes
  var transitionClass = el._transitionClasses;
  if (isDef(transitionClass)) {
    cls = concat(cls, stringifyClass(transitionClass));
  }

  // set the class
  if (cls !== el._prevClass) {
    el.setAttribute('class', cls);
    el._prevClass = cls;
  }
}

var klass = {
  create: updateClass,
  update: updateClass
};

/*  */

var validDivisionCharRE = /[\w).+\-_$\]]/;

function parseFilters (exp) {
  var inSingle = false;
  var inDouble = false;
  var inTemplateString = false;
  var inRegex = false;
  var curly = 0;
  var square = 0;
  var paren = 0;
  var lastFilterIndex = 0;
  var c, prev, i, expression, filters;

  for (i = 0; i < exp.length; i++) {
    prev = c;
    c = exp.charCodeAt(i);
    if (inSingle) {
      if (c === 0x27 && prev !== 0x5C) { inSingle = false; }
    } else if (inDouble) {
      if (c === 0x22 && prev !== 0x5C) { inDouble = false; }
    } else if (inTemplateString) {
      if (c === 0x60 && prev !== 0x5C) { inTemplateString = false; }
    } else if (inRegex) {
      if (c === 0x2f && prev !== 0x5C) { inRegex = false; }
    } else if (
      c === 0x7C && // pipe
      exp.charCodeAt(i + 1) !== 0x7C &&
      exp.charCodeAt(i - 1) !== 0x7C &&
      !curly && !square && !paren
    ) {
      if (expression === undefined) {
        // first filter, end of expression
        lastFilterIndex = i + 1;
        expression = exp.slice(0, i).trim();
      } else {
        pushFilter();
      }
    } else {
      switch (c) {
        case 0x22: inDouble = true; break         // "
        case 0x27: inSingle = true; break         // '
        case 0x60: inTemplateString = true; break // `
        case 0x28: paren++; break                 // (
        case 0x29: paren--; break                 // )
        case 0x5B: square++; break                // [
        case 0x5D: square--; break                // ]
        case 0x7B: curly++; break                 // {
        case 0x7D: curly--; break                 // }
      }
      if (c === 0x2f) { // /
        var j = i - 1;
        var p = (void 0);
        // find first non-whitespace prev char
        for (; j >= 0; j--) {
          p = exp.charAt(j);
          if (p !== ' ') { break }
        }
        if (!p || !validDivisionCharRE.test(p)) {
          inRegex = true;
        }
      }
    }
  }

  if (expression === undefined) {
    expression = exp.slice(0, i).trim();
  } else if (lastFilterIndex !== 0) {
    pushFilter();
  }

  function pushFilter () {
    (filters || (filters = [])).push(exp.slice(lastFilterIndex, i).trim());
    lastFilterIndex = i + 1;
  }

  if (filters) {
    for (i = 0; i < filters.length; i++) {
      expression = wrapFilter(expression, filters[i]);
    }
  }

  return expression
}

function wrapFilter (exp, filter) {
  var i = filter.indexOf('(');
  if (i < 0) {
    // _f: resolveFilter
    return ("_f(\"" + filter + "\")(" + exp + ")")
  } else {
    var name = filter.slice(0, i);
    var args = filter.slice(i + 1);
    return ("_f(\"" + name + "\")(" + exp + (args !== ')' ? ',' + args : args))
  }
}

/*  */



/* eslint-disable no-unused-vars */
function baseWarn (msg, range) {
  console.error(("[Vue compiler]: " + msg));
}
/* eslint-enable no-unused-vars */

function pluckModuleFunction (
  modules,
  key
) {
  return modules
    ? modules.map(function (m) { return m[key]; }).filter(function (_) { return _; })
    : []
}

function addProp (el, name, value, range, dynamic) {
  (el.props || (el.props = [])).push(rangeSetItem({ name: name, value: value, dynamic: dynamic }, range));
  el.plain = false;
}

function addAttr (el, name, value, range, dynamic) {
  var attrs = dynamic
    ? (el.dynamicAttrs || (el.dynamicAttrs = []))
    : (el.attrs || (el.attrs = []));
  attrs.push(rangeSetItem({ name: name, value: value, dynamic: dynamic }, range));
  el.plain = false;
}

// add a raw attr (use this in preTransforms)
function addRawAttr (el, name, value, range) {
  el.attrsMap[name] = value;
  el.attrsList.push(rangeSetItem({ name: name, value: value }, range));
}

function addDirective (
  el,
  name,
  rawName,
  value,
  arg,
  isDynamicArg,
  modifiers,
  range
) {
  (el.directives || (el.directives = [])).push(rangeSetItem({
    name: name,
    rawName: rawName,
    value: value,
    arg: arg,
    isDynamicArg: isDynamicArg,
    modifiers: modifiers
  }, range));
  el.plain = false;
}

function prependModifierMarker (symbol, name, dynamic) {
  return dynamic
    ? ("_p(" + name + ",\"" + symbol + "\")")
    : symbol + name // mark the event as captured
}

function addHandler (
  el,
  name,
  value,
  modifiers,
  important,
  warn,
  range,
  dynamic
) {
  modifiers = modifiers || emptyObject;
  // warn prevent and passive modifier
  /* istanbul ignore if */
  if (
    warn &&
    modifiers.prevent && modifiers.passive
  ) {
    warn(
      'passive and prevent can\'t be used together. ' +
      'Passive handler can\'t prevent default event.',
      range
    );
  }

  // normalize click.right and click.middle since they don't actually fire
  // this is technically browser-specific, but at least for now browsers are
  // the only target envs that have right/middle clicks.
  if (modifiers.right) {
    if (dynamic) {
      name = "(" + name + ")==='click'?'contextmenu':(" + name + ")";
    } else if (name === 'click') {
      name = 'contextmenu';
      delete modifiers.right;
    }
  } else if (modifiers.middle) {
    if (dynamic) {
      name = "(" + name + ")==='click'?'mouseup':(" + name + ")";
    } else if (name === 'click') {
      name = 'mouseup';
    }
  }

  // check capture modifier
  if (modifiers.capture) {
    delete modifiers.capture;
    name = prependModifierMarker('!', name, dynamic);
  }
  if (modifiers.once) {
    delete modifiers.once;
    name = prependModifierMarker('~', name, dynamic);
  }
  /* istanbul ignore if */
  if (modifiers.passive) {
    delete modifiers.passive;
    name = prependModifierMarker('&', name, dynamic);
  }

  var events;
  if (modifiers.native) {
    delete modifiers.native;
    events = el.nativeEvents || (el.nativeEvents = {});
  } else {
    events = el.events || (el.events = {});
  }

  var newHandler = rangeSetItem({ value: value.trim(), dynamic: dynamic }, range);
  if (modifiers !== emptyObject) {
    newHandler.modifiers = modifiers;
  }

  var handlers = events[name];
  /* istanbul ignore if */
  if (Array.isArray(handlers)) {
    important ? handlers.unshift(newHandler) : handlers.push(newHandler);
  } else if (handlers) {
    events[name] = important ? [newHandler, handlers] : [handlers, newHandler];
  } else {
    events[name] = newHandler;
  }

  el.plain = false;
}

function getRawBindingAttr (
  el,
  name
) {
  return el.rawAttrsMap[':' + name] ||
    el.rawAttrsMap['v-bind:' + name] ||
    el.rawAttrsMap[name]
}

function getBindingAttr (
  el,
  name,
  getStatic
) {
  var dynamicValue =
    getAndRemoveAttr(el, ':' + name) ||
    getAndRemoveAttr(el, 'v-bind:' + name);
  if (dynamicValue != null) {
    return parseFilters(dynamicValue)
  } else if (getStatic !== false) {
    var staticValue = getAndRemoveAttr(el, name);
    if (staticValue != null) {
      return JSON.stringify(staticValue)
    }
  }
}

// note: this only removes the attr from the Array (attrsList) so that it
// doesn't get processed by processAttrs.
// By default it does NOT remove it from the map (attrsMap) because the map is
// needed during codegen.
function getAndRemoveAttr (
  el,
  name,
  removeFromMap
) {
  var val;
  if ((val = el.attrsMap[name]) != null) {
    var list = el.attrsList;
    for (var i = 0, l = list.length; i < l; i++) {
      if (list[i].name === name) {
        list.splice(i, 1);
        break
      }
    }
  }
  if (removeFromMap) {
    delete el.attrsMap[name];
  }
  return val
}

function getAndRemoveAttrByRegex (
  el,
  name
) {
  var list = el.attrsList;
  for (var i = 0, l = list.length; i < l; i++) {
    var attr = list[i];
    if (name.test(attr.name)) {
      list.splice(i, 1);
      return attr
    }
  }
}

function rangeSetItem (
  item,
  range
) {
  if (range) {
    if (range.start != null) {
      item.start = range.start;
    }
    if (range.end != null) {
      item.end = range.end;
    }
  }
  return item
}

/*  */

/**
 * Cross-platform code generation for component v-model
 */
function genComponentModel (
  el,
  value,
  modifiers
) {
  var ref = modifiers || {};
  var number = ref.number;
  var trim = ref.trim;

  var baseValueExpression = '$$v';
  var valueExpression = baseValueExpression;
  if (trim) {
    valueExpression =
      "(typeof " + baseValueExpression + " === 'string'" +
      "? " + baseValueExpression + ".trim()" +
      ": " + baseValueExpression + ")";
  }
  if (number) {
    valueExpression = "_n(" + valueExpression + ")";
  }
  var assignment = genAssignmentCode(value, valueExpression);

  el.model = {
    value: ("(" + value + ")"),
    expression: JSON.stringify(value),
    callback: ("function (" + baseValueExpression + ") {" + assignment + "}")
  };
}

/**
 * Cross-platform codegen helper for generating v-model value assignment code.
 */
function genAssignmentCode (
  value,
  assignment
) {
  var res = parseModel(value);
  if (res.key === null) {
    return (value + "=" + assignment)
  } else {
    return ("$set(" + (res.exp) + ", " + (res.key) + ", " + assignment + ")")
  }
}

/**
 * Parse a v-model expression into a base path and a final key segment.
 * Handles both dot-path and possible square brackets.
 *
 * Possible cases:
 *
 * - test
 * - test[key]
 * - test[test1[key]]
 * - test["a"][key]
 * - xxx.test[a[a].test1[key]]
 * - test.xxx.a["asa"][test1[key]]
 *
 */

var len, str, chr, index$1, expressionPos, expressionEndPos;



function parseModel (val) {
  // Fix https://github.com/vuejs/vue/pull/7730
  // allow v-model="obj.val " (trailing whitespace)
  val = val.trim();
  len = val.length;

  if (val.indexOf('[') < 0 || val.lastIndexOf(']') < len - 1) {
    index$1 = val.lastIndexOf('.');
    if (index$1 > -1) {
      return {
        exp: val.slice(0, index$1),
        key: '"' + val.slice(index$1 + 1) + '"'
      }
    } else {
      return {
        exp: val,
        key: null
      }
    }
  }

  str = val;
  index$1 = expressionPos = expressionEndPos = 0;

  while (!eof()) {
    chr = next();
    /* istanbul ignore if */
    if (isStringStart(chr)) {
      parseString(chr);
    } else if (chr === 0x5B) {
      parseBracket(chr);
    }
  }

  return {
    exp: val.slice(0, expressionPos),
    key: val.slice(expressionPos + 1, expressionEndPos)
  }
}

function next () {
  return str.charCodeAt(++index$1)
}

function eof () {
  return index$1 >= len
}

function isStringStart (chr) {
  return chr === 0x22 || chr === 0x27
}

function parseBracket (chr) {
  var inBracket = 1;
  expressionPos = index$1;
  while (!eof()) {
    chr = next();
    if (isStringStart(chr)) {
      parseString(chr);
      continue
    }
    if (chr === 0x5B) { inBracket++; }
    if (chr === 0x5D) { inBracket--; }
    if (inBracket === 0) {
      expressionEndPos = index$1;
      break
    }
  }
}

function parseString (chr) {
  var stringQuote = chr;
  while (!eof()) {
    chr = next();
    if (chr === stringQuote) {
      break
    }
  }
}

/*  */

var warn$1;

// in some cases, the event used has to be determined at runtime
// so we used some reserved tokens during compile.
var RANGE_TOKEN = '__r';
var CHECKBOX_RADIO_TOKEN = '__c';

function model (
  el,
  dir,
  _warn
) {
  warn$1 = _warn;
  var value = dir.value;
  var modifiers = dir.modifiers;
  var tag = el.tag;
  var type = el.attrsMap.type;

  {
    // inputs with type="file" are read only and setting the input's
    // value will throw an error.
    if (tag === 'input' && type === 'file') {
      warn$1(
        "<" + (el.tag) + " v-model=\"" + value + "\" type=\"file\">:\n" +
        "File inputs are read only. Use a v-on:change listener instead.",
        el.rawAttrsMap['v-model']
      );
    }
  }

  if (el.component) {
    genComponentModel(el, value, modifiers);
    // component v-model doesn't need extra runtime
    return false
  } else if (tag === 'select') {
    genSelect(el, value, modifiers);
  } else if (tag === 'input' && type === 'checkbox') {
    genCheckboxModel(el, value, modifiers);
  } else if (tag === 'input' && type === 'radio') {
    genRadioModel(el, value, modifiers);
  } else if (tag === 'input' || tag === 'textarea') {
    genDefaultModel(el, value, modifiers);
  } else if (!config.isReservedTag(tag)) {
    genComponentModel(el, value, modifiers);
    // component v-model doesn't need extra runtime
    return false
  } else {
    warn$1(
      "<" + (el.tag) + " v-model=\"" + value + "\">: " +
      "v-model is not supported on this element type. " +
      'If you are working with contenteditable, it\'s recommended to ' +
      'wrap a library dedicated for that purpose inside a custom component.',
      el.rawAttrsMap['v-model']
    );
  }

  // ensure runtime directive metadata
  return true
}

function genCheckboxModel (
  el,
  value,
  modifiers
) {
  var number = modifiers && modifiers.number;
  var valueBinding = getBindingAttr(el, 'value') || 'null';
  var trueValueBinding = getBindingAttr(el, 'true-value') || 'true';
  var falseValueBinding = getBindingAttr(el, 'false-value') || 'false';
  addProp(el, 'checked',
    "Array.isArray(" + value + ")" +
    "?_i(" + value + "," + valueBinding + ")>-1" + (
      trueValueBinding === 'true'
        ? (":(" + value + ")")
        : (":_q(" + value + "," + trueValueBinding + ")")
    )
  );
  addHandler(el, 'change',
    "var $$a=" + value + "," +
        '$$el=$event.target,' +
        "$$c=$$el.checked?(" + trueValueBinding + "):(" + falseValueBinding + ");" +
    'if(Array.isArray($$a)){' +
      "var $$v=" + (number ? '_n(' + valueBinding + ')' : valueBinding) + "," +
          '$$i=_i($$a,$$v);' +
      "if($$el.checked){$$i<0&&(" + (genAssignmentCode(value, '$$a.concat([$$v])')) + ")}" +
      "else{$$i>-1&&(" + (genAssignmentCode(value, '$$a.slice(0,$$i).concat($$a.slice($$i+1))')) + ")}" +
    "}else{" + (genAssignmentCode(value, '$$c')) + "}",
    null, true
  );
}

function genRadioModel (
  el,
  value,
  modifiers
) {
  var number = modifiers && modifiers.number;
  var valueBinding = getBindingAttr(el, 'value') || 'null';
  valueBinding = number ? ("_n(" + valueBinding + ")") : valueBinding;
  addProp(el, 'checked', ("_q(" + value + "," + valueBinding + ")"));
  addHandler(el, 'change', genAssignmentCode(value, valueBinding), null, true);
}

function genSelect (
  el,
  value,
  modifiers
) {
  var number = modifiers && modifiers.number;
  var selectedVal = "Array.prototype.filter" +
    ".call($event.target.options,function(o){return o.selected})" +
    ".map(function(o){var val = \"_value\" in o ? o._value : o.value;" +
    "return " + (number ? '_n(val)' : 'val') + "})";

  var assignment = '$event.target.multiple ? $$selectedVal : $$selectedVal[0]';
  var code = "var $$selectedVal = " + selectedVal + ";";
  code = code + " " + (genAssignmentCode(value, assignment));
  addHandler(el, 'change', code, null, true);
}

function genDefaultModel (
  el,
  value,
  modifiers
) {
  var type = el.attrsMap.type;

  // warn if v-bind:value conflicts with v-model
  // except for inputs with v-bind:type
  {
    var value$1 = el.attrsMap['v-bind:value'] || el.attrsMap[':value'];
    var typeBinding = el.attrsMap['v-bind:type'] || el.attrsMap[':type'];
    if (value$1 && !typeBinding) {
      var binding = el.attrsMap['v-bind:value'] ? 'v-bind:value' : ':value';
      warn$1(
        binding + "=\"" + value$1 + "\" conflicts with v-model on the same element " +
        'because the latter already expands to a value binding internally',
        el.rawAttrsMap[binding]
      );
    }
  }

  var ref = modifiers || {};
  var lazy = ref.lazy;
  var number = ref.number;
  var trim = ref.trim;
  var needCompositionGuard = !lazy && type !== 'range';
  var event = lazy
    ? 'change'
    : type === 'range'
      ? RANGE_TOKEN
      : 'input';

  var valueExpression = '$event.target.value';
  if (trim) {
    valueExpression = "$event.target.value.trim()";
  }
  if (number) {
    valueExpression = "_n(" + valueExpression + ")";
  }

  var code = genAssignmentCode(value, valueExpression);
  if (needCompositionGuard) {
    code = "if($event.target.composing)return;" + code;
  }

  addProp(el, 'value', ("(" + value + ")"));
  addHandler(el, event, code, null, true);
  if (trim || number) {
    addHandler(el, 'blur', '$forceUpdate()');
  }
}

/*  */

// normalize v-model event tokens that can only be determined at runtime.
// it's important to place the event as the first in the array because
// the whole point is ensuring the v-model callback gets called before
// user-attached handlers.
function normalizeEvents (on) {
  /* istanbul ignore if */
  if (isDef(on[RANGE_TOKEN])) {
    // IE input[type=range] only supports `change` event
    var event = isIE ? 'change' : 'input';
    on[event] = [].concat(on[RANGE_TOKEN], on[event] || []);
    delete on[RANGE_TOKEN];
  }
  // This was originally intended to fix #4521 but no longer necessary
  // after 2.5. Keeping it for backwards compat with generated code from < 2.4
  /* istanbul ignore if */
  if (isDef(on[CHECKBOX_RADIO_TOKEN])) {
    on.change = [].concat(on[CHECKBOX_RADIO_TOKEN], on.change || []);
    delete on[CHECKBOX_RADIO_TOKEN];
  }
}

var target$1;

function createOnceHandler$1 (event, handler, capture) {
  var _target = target$1; // save current target element in closure
  return function onceHandler () {
    var res = handler.apply(null, arguments);
    if (res !== null) {
      remove$2(event, onceHandler, capture, _target);
    }
  }
}

// #9446: Firefox <= 53 (in particular, ESR 52) has incorrect Event.timeStamp
// implementation and does not fire microtasks in between event propagation, so
// safe to exclude.
var useMicrotaskFix = isUsingMicroTask && !(isFF && Number(isFF[1]) <= 53);

function add$1 (
  name,
  handler,
  capture,
  passive
) {
  // async edge case #6566: inner click event triggers patch, event handler
  // attached to outer element during patch, and triggered again. This
  // happens because browsers fire microtask ticks between event propagation.
  // the solution is simple: we save the timestamp when a handler is attached,
  // and the handler would only fire if the event passed to it was fired
  // AFTER it was attached.
  if (useMicrotaskFix) {
    var attachedTimestamp = currentFlushTimestamp;
    var original = handler;
    handler = original._wrapper = function (e) {
      if (
        // no bubbling, should always fire.
        // this is just a safety net in case event.timeStamp is unreliable in
        // certain weird environments...
        e.target === e.currentTarget ||
        // event is fired after handler attachment
        e.timeStamp >= attachedTimestamp ||
        // bail for environments that have buggy event.timeStamp implementations
        // #9462 iOS 9 bug: event.timeStamp is 0 after history.pushState
        // #9681 QtWebEngine event.timeStamp is negative value
        e.timeStamp <= 0 ||
        // #9448 bail if event is fired in another document in a multi-page
        // electron/nw.js app, since event.timeStamp will be using a different
        // starting reference
        e.target.ownerDocument !== document
      ) {
        return original.apply(this, arguments)
      }
    };
  }
  target$1.addEventListener(
    name,
    handler,
    supportsPassive
      ? { capture: capture, passive: passive }
      : capture
  );
}

function remove$2 (
  name,
  handler,
  capture,
  _target
) {
  (_target || target$1).removeEventListener(
    name,
    handler._wrapper || handler,
    capture
  );
}

function updateDOMListeners (oldVnode, vnode) {
  if (isUndef(oldVnode.data.on) && isUndef(vnode.data.on)) {
    return
  }
  var on = vnode.data.on || {};
  var oldOn = oldVnode.data.on || {};
  target$1 = vnode.elm;
  normalizeEvents(on);
  updateListeners(on, oldOn, add$1, remove$2, createOnceHandler$1, vnode.context);
  target$1 = undefined;
}

var events = {
  create: updateDOMListeners,
  update: updateDOMListeners
};

/*  */

var svgContainer;

function updateDOMProps (oldVnode, vnode) {
  if (isUndef(oldVnode.data.domProps) && isUndef(vnode.data.domProps)) {
    return
  }
  var key, cur;
  var elm = vnode.elm;
  var oldProps = oldVnode.data.domProps || {};
  var props = vnode.data.domProps || {};
  // clone observed objects, as the user probably wants to mutate it
  if (isDef(props.__ob__)) {
    props = vnode.data.domProps = extend({}, props);
  }

  for (key in oldProps) {
    if (!(key in props)) {
      elm[key] = '';
    }
  }

  for (key in props) {
    cur = props[key];
    // ignore children if the node has textContent or innerHTML,
    // as these will throw away existing DOM nodes and cause removal errors
    // on subsequent patches (#3360)
    if (key === 'textContent' || key === 'innerHTML') {
      if (vnode.children) { vnode.children.length = 0; }
      if (cur === oldProps[key]) { continue }
      // #6601 work around Chrome version <= 55 bug where single textNode
      // replaced by innerHTML/textContent retains its parentNode property
      if (elm.childNodes.length === 1) {
        elm.removeChild(elm.childNodes[0]);
      }
    }

    if (key === 'value' && elm.tagName !== 'PROGRESS') {
      // store value as _value as well since
      // non-string values will be stringified
      elm._value = cur;
      // avoid resetting cursor position when value is the same
      var strCur = isUndef(cur) ? '' : String(cur);
      if (shouldUpdateValue(elm, strCur)) {
        elm.value = strCur;
      }
    } else if (key === 'innerHTML' && isSVG(elm.tagName) && isUndef(elm.innerHTML)) {
      // IE doesn't support innerHTML for SVG elements
      svgContainer = svgContainer || document.createElement('div');
      svgContainer.innerHTML = "<svg>" + cur + "</svg>";
      var svg = svgContainer.firstChild;
      while (elm.firstChild) {
        elm.removeChild(elm.firstChild);
      }
      while (svg.firstChild) {
        elm.appendChild(svg.firstChild);
      }
    } else if (
      // skip the update if old and new VDOM state is the same.
      // `value` is handled separately because the DOM value may be temporarily
      // out of sync with VDOM state due to focus, composition and modifiers.
      // This  #4521 by skipping the unnecessary `checked` update.
      cur !== oldProps[key]
    ) {
      // some property updates can throw
      // e.g. `value` on <progress> w/ non-finite value
      try {
        elm[key] = cur;
      } catch (e) {}
    }
  }
}

// check platforms/web/util/attrs.js acceptValue


function shouldUpdateValue (elm, checkVal) {
  return (!elm.composing && (
    elm.tagName === 'OPTION' ||
    isNotInFocusAndDirty(elm, checkVal) ||
    isDirtyWithModifiers(elm, checkVal)
  ))
}

function isNotInFocusAndDirty (elm, checkVal) {
  // return true when textbox (.number and .trim) loses focus and its value is
  // not equal to the updated value
  var notInFocus = true;
  // #6157
  // work around IE bug when accessing document.activeElement in an iframe
  try { notInFocus = document.activeElement !== elm; } catch (e) {}
  return notInFocus && elm.value !== checkVal
}

function isDirtyWithModifiers (elm, newVal) {
  var value = elm.value;
  var modifiers = elm._vModifiers; // injected by v-model runtime
  if (isDef(modifiers)) {
    if (modifiers.number) {
      return toNumber(value) !== toNumber(newVal)
    }
    if (modifiers.trim) {
      return value.trim() !== newVal.trim()
    }
  }
  return value !== newVal
}

var domProps = {
  create: updateDOMProps,
  update: updateDOMProps
};

/*  */

var parseStyleText = cached(function (cssText) {
  var res = {};
  var listDelimiter = /;(?![^(]*\))/g;
  var propertyDelimiter = /:(.+)/;
  cssText.split(listDelimiter).forEach(function (item) {
    if (item) {
      var tmp = item.split(propertyDelimiter);
      tmp.length > 1 && (res[tmp[0].trim()] = tmp[1].trim());
    }
  });
  return res
});

// merge static and dynamic style data on the same vnode
function normalizeStyleData (data) {
  var style = normalizeStyleBinding(data.style);
  // static style is pre-processed into an object during compilation
  // and is always a fresh object, so it's safe to merge into it
  return data.staticStyle
    ? extend(data.staticStyle, style)
    : style
}

// normalize possible array / string values into Object
function normalizeStyleBinding (bindingStyle) {
  if (Array.isArray(bindingStyle)) {
    return toObject(bindingStyle)
  }
  if (typeof bindingStyle === 'string') {
    return parseStyleText(bindingStyle)
  }
  return bindingStyle
}

/**
 * parent component style should be after child's
 * so that parent component's style could override it
 */
function getStyle (vnode, checkChild) {
  var res = {};
  var styleData;

  if (checkChild) {
    var childNode = vnode;
    while (childNode.componentInstance) {
      childNode = childNode.componentInstance._vnode;
      if (
        childNode && childNode.data &&
        (styleData = normalizeStyleData(childNode.data))
      ) {
        extend(res, styleData);
      }
    }
  }

  if ((styleData = normalizeStyleData(vnode.data))) {
    extend(res, styleData);
  }

  var parentNode = vnode;
  while ((parentNode = parentNode.parent)) {
    if (parentNode.data && (styleData = normalizeStyleData(parentNode.data))) {
      extend(res, styleData);
    }
  }
  return res
}

/*  */

var cssVarRE = /^--/;
var importantRE = /\s*!important$/;
var setProp = function (el, name, val) {
  /* istanbul ignore if */
  if (cssVarRE.test(name)) {
    el.style.setProperty(name, val);
  } else if (importantRE.test(val)) {
    el.style.setProperty(hyphenate(name), val.replace(importantRE, ''), 'important');
  } else {
    var normalizedName = normalize(name);
    if (Array.isArray(val)) {
      // Support values array created by autoprefixer, e.g.
      // {display: ["-webkit-box", "-ms-flexbox", "flex"]}
      // Set them one by one, and the browser will only set those it can recognize
      for (var i = 0, len = val.length; i < len; i++) {
        el.style[normalizedName] = val[i];
      }
    } else {
      el.style[normalizedName] = val;
    }
  }
};

var vendorNames = ['Webkit', 'Moz', 'ms'];

var emptyStyle;
var normalize = cached(function (prop) {
  emptyStyle = emptyStyle || document.createElement('div').style;
  prop = camelize(prop);
  if (prop !== 'filter' && (prop in emptyStyle)) {
    return prop
  }
  var capName = prop.charAt(0).toUpperCase() + prop.slice(1);
  for (var i = 0; i < vendorNames.length; i++) {
    var name = vendorNames[i] + capName;
    if (name in emptyStyle) {
      return name
    }
  }
});

function updateStyle (oldVnode, vnode) {
  var data = vnode.data;
  var oldData = oldVnode.data;

  if (isUndef(data.staticStyle) && isUndef(data.style) &&
    isUndef(oldData.staticStyle) && isUndef(oldData.style)
  ) {
    return
  }

  var cur, name;
  var el = vnode.elm;
  var oldStaticStyle = oldData.staticStyle;
  var oldStyleBinding = oldData.normalizedStyle || oldData.style || {};

  // if static style exists, stylebinding already merged into it when doing normalizeStyleData
  var oldStyle = oldStaticStyle || oldStyleBinding;

  var style = normalizeStyleBinding(vnode.data.style) || {};

  // store normalized style under a different key for next diff
  // make sure to clone it if it's reactive, since the user likely wants
  // to mutate it.
  vnode.data.normalizedStyle = isDef(style.__ob__)
    ? extend({}, style)
    : style;

  var newStyle = getStyle(vnode, true);

  for (name in oldStyle) {
    if (isUndef(newStyle[name])) {
      setProp(el, name, '');
    }
  }
  for (name in newStyle) {
    cur = newStyle[name];
    if (cur !== oldStyle[name]) {
      // ie9 setting to null has no effect, must use empty string
      setProp(el, name, cur == null ? '' : cur);
    }
  }
}

var style = {
  create: updateStyle,
  update: updateStyle
};

/*  */

var whitespaceRE = /\s+/;

/**
 * Add class with compatibility for SVG since classList is not supported on
 * SVG elements in IE
 */
function addClass (el, cls) {
  /* istanbul ignore if */
  if (!cls || !(cls = cls.trim())) {
    return
  }

  /* istanbul ignore else */
  if (el.classList) {
    if (cls.indexOf(' ') > -1) {
      cls.split(whitespaceRE).forEach(function (c) { return el.classList.add(c); });
    } else {
      el.classList.add(cls);
    }
  } else {
    var cur = " " + (el.getAttribute('class') || '') + " ";
    if (cur.indexOf(' ' + cls + ' ') < 0) {
      el.setAttribute('class', (cur + cls).trim());
    }
  }
}

/**
 * Remove class with compatibility for SVG since classList is not supported on
 * SVG elements in IE
 */
function removeClass (el, cls) {
  /* istanbul ignore if */
  if (!cls || !(cls = cls.trim())) {
    return
  }

  /* istanbul ignore else */
  if (el.classList) {
    if (cls.indexOf(' ') > -1) {
      cls.split(whitespaceRE).forEach(function (c) { return el.classList.remove(c); });
    } else {
      el.classList.remove(cls);
    }
    if (!el.classList.length) {
      el.removeAttribute('class');
    }
  } else {
    var cur = " " + (el.getAttribute('class') || '') + " ";
    var tar = ' ' + cls + ' ';
    while (cur.indexOf(tar) >= 0) {
      cur = cur.replace(tar, ' ');
    }
    cur = cur.trim();
    if (cur) {
      el.setAttribute('class', cur);
    } else {
      el.removeAttribute('class');
    }
  }
}

/*  */

function resolveTransition (def$$1) {
  if (!def$$1) {
    return
  }
  /* istanbul ignore else */
  if (typeof def$$1 === 'object') {
    var res = {};
    if (def$$1.css !== false) {
      extend(res, autoCssTransition(def$$1.name || 'v'));
    }
    extend(res, def$$1);
    return res
  } else if (typeof def$$1 === 'string') {
    return autoCssTransition(def$$1)
  }
}

var autoCssTransition = cached(function (name) {
  return {
    enterClass: (name + "-enter"),
    enterToClass: (name + "-enter-to"),
    enterActiveClass: (name + "-enter-active"),
    leaveClass: (name + "-leave"),
    leaveToClass: (name + "-leave-to"),
    leaveActiveClass: (name + "-leave-active")
  }
});

var hasTransition = inBrowser && !isIE9;
var TRANSITION = 'transition';
var ANIMATION = 'animation';

// Transition property/event sniffing
var transitionProp = 'transition';
var transitionEndEvent = 'transitionend';
var animationProp = 'animation';
var animationEndEvent = 'animationend';
if (hasTransition) {
  /* istanbul ignore if */
  if (window.ontransitionend === undefined &&
    window.onwebkittransitionend !== undefined
  ) {
    transitionProp = 'WebkitTransition';
    transitionEndEvent = 'webkitTransitionEnd';
  }
  if (window.onanimationend === undefined &&
    window.onwebkitanimationend !== undefined
  ) {
    animationProp = 'WebkitAnimation';
    animationEndEvent = 'webkitAnimationEnd';
  }
}

// binding to window is necessary to make hot reload work in IE in strict mode
var raf = inBrowser
  ? window.requestAnimationFrame
    ? window.requestAnimationFrame.bind(window)
    : setTimeout
  : /* istanbul ignore next */ function (fn) { return fn(); };

function nextFrame (fn) {
  raf(function () {
    raf(fn);
  });
}

function addTransitionClass (el, cls) {
  var transitionClasses = el._transitionClasses || (el._transitionClasses = []);
  if (transitionClasses.indexOf(cls) < 0) {
    transitionClasses.push(cls);
    addClass(el, cls);
  }
}

function removeTransitionClass (el, cls) {
  if (el._transitionClasses) {
    remove(el._transitionClasses, cls);
  }
  removeClass(el, cls);
}

function whenTransitionEnds (
  el,
  expectedType,
  cb
) {
  var ref = getTransitionInfo(el, expectedType);
  var type = ref.type;
  var timeout = ref.timeout;
  var propCount = ref.propCount;
  if (!type) { return cb() }
  var event = type === TRANSITION ? transitionEndEvent : animationEndEvent;
  var ended = 0;
  var end = function () {
    el.removeEventListener(event, onEnd);
    cb();
  };
  var onEnd = function (e) {
    if (e.target === el) {
      if (++ended >= propCount) {
        end();
      }
    }
  };
  setTimeout(function () {
    if (ended < propCount) {
      end();
    }
  }, timeout + 1);
  el.addEventListener(event, onEnd);
}

var transformRE = /\b(transform|all)(,|$)/;

function getTransitionInfo (el, expectedType) {
  var styles = window.getComputedStyle(el);
  // JSDOM may return undefined for transition properties
  var transitionDelays = (styles[transitionProp + 'Delay'] || '').split(', ');
  var transitionDurations = (styles[transitionProp + 'Duration'] || '').split(', ');
  var transitionTimeout = getTimeout(transitionDelays, transitionDurations);
  var animationDelays = (styles[animationProp + 'Delay'] || '').split(', ');
  var animationDurations = (styles[animationProp + 'Duration'] || '').split(', ');
  var animationTimeout = getTimeout(animationDelays, animationDurations);

  var type;
  var timeout = 0;
  var propCount = 0;
  /* istanbul ignore if */
  if (expectedType === TRANSITION) {
    if (transitionTimeout > 0) {
      type = TRANSITION;
      timeout = transitionTimeout;
      propCount = transitionDurations.length;
    }
  } else if (expectedType === ANIMATION) {
    if (animationTimeout > 0) {
      type = ANIMATION;
      timeout = animationTimeout;
      propCount = animationDurations.length;
    }
  } else {
    timeout = Math.max(transitionTimeout, animationTimeout);
    type = timeout > 0
      ? transitionTimeout > animationTimeout
        ? TRANSITION
        : ANIMATION
      : null;
    propCount = type
      ? type === TRANSITION
        ? transitionDurations.length
        : animationDurations.length
      : 0;
  }
  var hasTransform =
    type === TRANSITION &&
    transformRE.test(styles[transitionProp + 'Property']);
  return {
    type: type,
    timeout: timeout,
    propCount: propCount,
    hasTransform: hasTransform
  }
}

function getTimeout (delays, durations) {
  /* istanbul ignore next */
  while (delays.length < durations.length) {
    delays = delays.concat(delays);
  }

  return Math.max.apply(null, durations.map(function (d, i) {
    return toMs(d) + toMs(delays[i])
  }))
}

// Old versions of Chromium (below 61.0.3163.100) formats floating pointer numbers
// in a locale-dependent way, using a comma instead of a dot.
// If comma is not replaced with a dot, the input will be rounded down (i.e. acting
// as a floor function) causing unexpected behaviors
function toMs (s) {
  return Number(s.slice(0, -1).replace(',', '.')) * 1000
}

/*  */

function enter (vnode, toggleDisplay) {
  var el = vnode.elm;

  // call leave callback now
  if (isDef(el._leaveCb)) {
    el._leaveCb.cancelled = true;
    el._leaveCb();
  }

  var data = resolveTransition(vnode.data.transition);
  if (isUndef(data)) {
    return
  }

  /* istanbul ignore if */
  if (isDef(el._enterCb) || el.nodeType !== 1) {
    return
  }

  var css = data.css;
  var type = data.type;
  var enterClass = data.enterClass;
  var enterToClass = data.enterToClass;
  var enterActiveClass = data.enterActiveClass;
  var appearClass = data.appearClass;
  var appearToClass = data.appearToClass;
  var appearActiveClass = data.appearActiveClass;
  var beforeEnter = data.beforeEnter;
  var enter = data.enter;
  var afterEnter = data.afterEnter;
  var enterCancelled = data.enterCancelled;
  var beforeAppear = data.beforeAppear;
  var appear = data.appear;
  var afterAppear = data.afterAppear;
  var appearCancelled = data.appearCancelled;
  var duration = data.duration;

  // activeInstance will always be the <transition> component managing this
  // transition. One edge case to check is when the <transition> is placed
  // as the root node of a child component. In that case we need to check
  // <transition>'s parent for appear check.
  var context = activeInstance;
  var transitionNode = activeInstance.$vnode;
  while (transitionNode && transitionNode.parent) {
    context = transitionNode.context;
    transitionNode = transitionNode.parent;
  }

  var isAppear = !context._isMounted || !vnode.isRootInsert;

  if (isAppear && !appear && appear !== '') {
    return
  }

  var startClass = isAppear && appearClass
    ? appearClass
    : enterClass;
  var activeClass = isAppear && appearActiveClass
    ? appearActiveClass
    : enterActiveClass;
  var toClass = isAppear && appearToClass
    ? appearToClass
    : enterToClass;

  var beforeEnterHook = isAppear
    ? (beforeAppear || beforeEnter)
    : beforeEnter;
  var enterHook = isAppear
    ? (typeof appear === 'function' ? appear : enter)
    : enter;
  var afterEnterHook = isAppear
    ? (afterAppear || afterEnter)
    : afterEnter;
  var enterCancelledHook = isAppear
    ? (appearCancelled || enterCancelled)
    : enterCancelled;

  var explicitEnterDuration = toNumber(
    isObject(duration)
      ? duration.enter
      : duration
  );

  if (explicitEnterDuration != null) {
    checkDuration(explicitEnterDuration, 'enter', vnode);
  }

  var expectsCSS = css !== false && !isIE9;
  var userWantsControl = getHookArgumentsLength(enterHook);

  var cb = el._enterCb = once(function () {
    if (expectsCSS) {
      removeTransitionClass(el, toClass);
      removeTransitionClass(el, activeClass);
    }
    if (cb.cancelled) {
      if (expectsCSS) {
        removeTransitionClass(el, startClass);
      }
      enterCancelledHook && enterCancelledHook(el);
    } else {
      afterEnterHook && afterEnterHook(el);
    }
    el._enterCb = null;
  });

  if (!vnode.data.show) {
    // remove pending leave element on enter by injecting an insert hook
    mergeVNodeHook(vnode, 'insert', function () {
      var parent = el.parentNode;
      var pendingNode = parent && parent._pending && parent._pending[vnode.key];
      if (pendingNode &&
        pendingNode.tag === vnode.tag &&
        pendingNode.elm._leaveCb
      ) {
        pendingNode.elm._leaveCb();
      }
      enterHook && enterHook(el, cb);
    });
  }

  // start enter transition
  beforeEnterHook && beforeEnterHook(el);
  if (expectsCSS) {
    addTransitionClass(el, startClass);
    addTransitionClass(el, activeClass);
    nextFrame(function () {
      removeTransitionClass(el, startClass);
      if (!cb.cancelled) {
        addTransitionClass(el, toClass);
        if (!userWantsControl) {
          if (isValidDuration(explicitEnterDuration)) {
            setTimeout(cb, explicitEnterDuration);
          } else {
            whenTransitionEnds(el, type, cb);
          }
        }
      }
    });
  }

  if (vnode.data.show) {
    toggleDisplay && toggleDisplay();
    enterHook && enterHook(el, cb);
  }

  if (!expectsCSS && !userWantsControl) {
    cb();
  }
}

function leave (vnode, rm) {
  var el = vnode.elm;

  // call enter callback now
  if (isDef(el._enterCb)) {
    el._enterCb.cancelled = true;
    el._enterCb();
  }

  var data = resolveTransition(vnode.data.transition);
  if (isUndef(data) || el.nodeType !== 1) {
    return rm()
  }

  /* istanbul ignore if */
  if (isDef(el._leaveCb)) {
    return
  }

  var css = data.css;
  var type = data.type;
  var leaveClass = data.leaveClass;
  var leaveToClass = data.leaveToClass;
  var leaveActiveClass = data.leaveActiveClass;
  var beforeLeave = data.beforeLeave;
  var leave = data.leave;
  var afterLeave = data.afterLeave;
  var leaveCancelled = data.leaveCancelled;
  var delayLeave = data.delayLeave;
  var duration = data.duration;

  var expectsCSS = css !== false && !isIE9;
  var userWantsControl = getHookArgumentsLength(leave);

  var explicitLeaveDuration = toNumber(
    isObject(duration)
      ? duration.leave
      : duration
  );

  if (isDef(explicitLeaveDuration)) {
    checkDuration(explicitLeaveDuration, 'leave', vnode);
  }

  var cb = el._leaveCb = once(function () {
    if (el.parentNode && el.parentNode._pending) {
      el.parentNode._pending[vnode.key] = null;
    }
    if (expectsCSS) {
      removeTransitionClass(el, leaveToClass);
      removeTransitionClass(el, leaveActiveClass);
    }
    if (cb.cancelled) {
      if (expectsCSS) {
        removeTransitionClass(el, leaveClass);
      }
      leaveCancelled && leaveCancelled(el);
    } else {
      rm();
      afterLeave && afterLeave(el);
    }
    el._leaveCb = null;
  });

  if (delayLeave) {
    delayLeave(performLeave);
  } else {
    performLeave();
  }

  function performLeave () {
    // the delayed leave may have already been cancelled
    if (cb.cancelled) {
      return
    }
    // record leaving element
    if (!vnode.data.show && el.parentNode) {
      (el.parentNode._pending || (el.parentNode._pending = {}))[(vnode.key)] = vnode;
    }
    beforeLeave && beforeLeave(el);
    if (expectsCSS) {
      addTransitionClass(el, leaveClass);
      addTransitionClass(el, leaveActiveClass);
      nextFrame(function () {
        removeTransitionClass(el, leaveClass);
        if (!cb.cancelled) {
          addTransitionClass(el, leaveToClass);
          if (!userWantsControl) {
            if (isValidDuration(explicitLeaveDuration)) {
              setTimeout(cb, explicitLeaveDuration);
            } else {
              whenTransitionEnds(el, type, cb);
            }
          }
        }
      });
    }
    leave && leave(el, cb);
    if (!expectsCSS && !userWantsControl) {
      cb();
    }
  }
}

// only used in dev mode
function checkDuration (val, name, vnode) {
  if (typeof val !== 'number') {
    warn(
      "<transition> explicit " + name + " duration is not a valid number - " +
      "got " + (JSON.stringify(val)) + ".",
      vnode.context
    );
  } else if (isNaN(val)) {
    warn(
      "<transition> explicit " + name + " duration is NaN - " +
      'the duration expression might be incorrect.',
      vnode.context
    );
  }
}

function isValidDuration (val) {
  return typeof val === 'number' && !isNaN(val)
}

/**
 * Normalize a transition hook's argument length. The hook may be:
 * - a merged hook (invoker) with the original in .fns
 * - a wrapped component method (check ._length)
 * - a plain function (.length)
 */
function getHookArgumentsLength (fn) {
  if (isUndef(fn)) {
    return false
  }
  var invokerFns = fn.fns;
  if (isDef(invokerFns)) {
    // invoker
    return getHookArgumentsLength(
      Array.isArray(invokerFns)
        ? invokerFns[0]
        : invokerFns
    )
  } else {
    return (fn._length || fn.length) > 1
  }
}

function _enter (_, vnode) {
  if (vnode.data.show !== true) {
    enter(vnode);
  }
}

var transition = inBrowser ? {
  create: _enter,
  activate: _enter,
  remove: function remove$$1 (vnode, rm) {
    /* istanbul ignore else */
    if (vnode.data.show !== true) {
      leave(vnode, rm);
    } else {
      rm();
    }
  }
} : {};

var platformModules = [
  attrs,
  klass,
  events,
  domProps,
  style,
  transition
];

/*  */

// the directive module should be applied last, after all
// built-in modules have been applied.
var modules = platformModules.concat(baseModules);

var patch = createPatchFunction({ nodeOps: nodeOps, modules: modules });

/**
 * Not type checking this file because flow doesn't like attaching
 * properties to Elements.
 */

/* istanbul ignore if */
if (isIE9) {
  // http://www.matts411.com/post/internet-explorer-9-oninput/
  document.addEventListener('selectionchange', function () {
    var el = document.activeElement;
    if (el && el.vmodel) {
      trigger(el, 'input');
    }
  });
}

var directive = {
  inserted: function inserted (el, binding, vnode, oldVnode) {
    if (vnode.tag === 'select') {
      // #6903
      if (oldVnode.elm && !oldVnode.elm._vOptions) {
        mergeVNodeHook(vnode, 'postpatch', function () {
          directive.componentUpdated(el, binding, vnode);
        });
      } else {
        setSelected(el, binding, vnode.context);
      }
      el._vOptions = [].map.call(el.options, getValue);
    } else if (vnode.tag === 'textarea' || isTextInputType(el.type)) {
      el._vModifiers = binding.modifiers;
      if (!binding.modifiers.lazy) {
        el.addEventListener('compositionstart', onCompositionStart);
        el.addEventListener('compositionend', onCompositionEnd);
        // Safari < 10.2 & UIWebView doesn't fire compositionend when
        // switching focus before confirming composition choice
        // this also fixes the issue where some browsers e.g. iOS Chrome
        // fires "change" instead of "input" on autocomplete.
        el.addEventListener('change', onCompositionEnd);
        /* istanbul ignore if */
        if (isIE9) {
          el.vmodel = true;
        }
      }
    }
  },

  componentUpdated: function componentUpdated (el, binding, vnode) {
    if (vnode.tag === 'select') {
      setSelected(el, binding, vnode.context);
      // in case the options rendered by v-for have changed,
      // it's possible that the value is out-of-sync with the rendered options.
      // detect such cases and filter out values that no longer has a matching
      // option in the DOM.
      var prevOptions = el._vOptions;
      var curOptions = el._vOptions = [].map.call(el.options, getValue);
      if (curOptions.some(function (o, i) { return !looseEqual(o, prevOptions[i]); })) {
        // trigger change event if
        // no matching option found for at least one value
        var needReset = el.multiple
          ? binding.value.some(function (v) { return hasNoMatchingOption(v, curOptions); })
          : binding.value !== binding.oldValue && hasNoMatchingOption(binding.value, curOptions);
        if (needReset) {
          trigger(el, 'change');
        }
      }
    }
  }
};

function setSelected (el, binding, vm) {
  actuallySetSelected(el, binding, vm);
  /* istanbul ignore if */
  if (isIE || isEdge) {
    setTimeout(function () {
      actuallySetSelected(el, binding, vm);
    }, 0);
  }
}

function actuallySetSelected (el, binding, vm) {
  var value = binding.value;
  var isMultiple = el.multiple;
  if (isMultiple && !Array.isArray(value)) {
    warn(
      "<select multiple v-model=\"" + (binding.expression) + "\"> " +
      "expects an Array value for its binding, but got " + (Object.prototype.toString.call(value).slice(8, -1)),
      vm
    );
    return
  }
  var selected, option;
  for (var i = 0, l = el.options.length; i < l; i++) {
    option = el.options[i];
    if (isMultiple) {
      selected = looseIndexOf(value, getValue(option)) > -1;
      if (option.selected !== selected) {
        option.selected = selected;
      }
    } else {
      if (looseEqual(getValue(option), value)) {
        if (el.selectedIndex !== i) {
          el.selectedIndex = i;
        }
        return
      }
    }
  }
  if (!isMultiple) {
    el.selectedIndex = -1;
  }
}

function hasNoMatchingOption (value, options) {
  return options.every(function (o) { return !looseEqual(o, value); })
}

function getValue (option) {
  return '_value' in option
    ? option._value
    : option.value
}

function onCompositionStart (e) {
  e.target.composing = true;
}

function onCompositionEnd (e) {
  // prevent triggering an input event for no reason
  if (!e.target.composing) { return }
  e.target.composing = false;
  trigger(e.target, 'input');
}

function trigger (el, type) {
  var e = document.createEvent('HTMLEvents');
  e.initEvent(type, true, true);
  el.dispatchEvent(e);
}

/*  */

// recursively search for possible transition defined inside the component root
function locateNode (vnode) {
  return vnode.componentInstance && (!vnode.data || !vnode.data.transition)
    ? locateNode(vnode.componentInstance._vnode)
    : vnode
}

var show = {
  bind: function bind (el, ref, vnode) {
    var value = ref.value;

    vnode = locateNode(vnode);
    var transition$$1 = vnode.data && vnode.data.transition;
    var originalDisplay = el.__vOriginalDisplay =
      el.style.display === 'none' ? '' : el.style.display;
    if (value && transition$$1) {
      vnode.data.show = true;
      enter(vnode, function () {
        el.style.display = originalDisplay;
      });
    } else {
      el.style.display = value ? originalDisplay : 'none';
    }
  },

  update: function update (el, ref, vnode) {
    var value = ref.value;
    var oldValue = ref.oldValue;

    /* istanbul ignore if */
    if (!value === !oldValue) { return }
    vnode = locateNode(vnode);
    var transition$$1 = vnode.data && vnode.data.transition;
    if (transition$$1) {
      vnode.data.show = true;
      if (value) {
        enter(vnode, function () {
          el.style.display = el.__vOriginalDisplay;
        });
      } else {
        leave(vnode, function () {
          el.style.display = 'none';
        });
      }
    } else {
      el.style.display = value ? el.__vOriginalDisplay : 'none';
    }
  },

  unbind: function unbind (
    el,
    binding,
    vnode,
    oldVnode,
    isDestroy
  ) {
    if (!isDestroy) {
      el.style.display = el.__vOriginalDisplay;
    }
  }
};

var platformDirectives = {
  model: directive,
  show: show
};

/*  */

var transitionProps = {
  name: String,
  appear: Boolean,
  css: Boolean,
  mode: String,
  type: String,
  enterClass: String,
  leaveClass: String,
  enterToClass: String,
  leaveToClass: String,
  enterActiveClass: String,
  leaveActiveClass: String,
  appearClass: String,
  appearActiveClass: String,
  appearToClass: String,
  duration: [Number, String, Object]
};

// in case the child is also an abstract component, e.g. <keep-alive>
// we want to recursively retrieve the real component to be rendered
function getRealChild (vnode) {
  var compOptions = vnode && vnode.componentOptions;
  if (compOptions && compOptions.Ctor.options.abstract) {
    return getRealChild(getFirstComponentChild(compOptions.children))
  } else {
    return vnode
  }
}

function extractTransitionData (comp) {
  var data = {};
  var options = comp.$options;
  // props
  for (var key in options.propsData) {
    data[key] = comp[key];
  }
  // events.
  // extract listeners and pass them directly to the transition methods
  var listeners = options._parentListeners;
  for (var key$1 in listeners) {
    data[camelize(key$1)] = listeners[key$1];
  }
  return data
}

function placeholder (h, rawChild) {
  if (/\d-keep-alive$/.test(rawChild.tag)) {
    return h('keep-alive', {
      props: rawChild.componentOptions.propsData
    })
  }
}

function hasParentTransition (vnode) {
  while ((vnode = vnode.parent)) {
    if (vnode.data.transition) {
      return true
    }
  }
}

function isSameChild (child, oldChild) {
  return oldChild.key === child.key && oldChild.tag === child.tag
}

var isNotTextNode = function (c) { return c.tag || isAsyncPlaceholder(c); };

var isVShowDirective = function (d) { return d.name === 'show'; };

var Transition = {
  name: 'transition',
  props: transitionProps,
  abstract: true,

  render: function render (h) {
    var this$1 = this;

    var children = this.$slots.default;
    if (!children) {
      return
    }

    // filter out text nodes (possible whitespaces)
    children = children.filter(isNotTextNode);
    /* istanbul ignore if */
    if (!children.length) {
      return
    }

    // warn multiple elements
    if (children.length > 1) {
      warn(
        '<transition> can only be used on a single element. Use ' +
        '<transition-group> for lists.',
        this.$parent
      );
    }

    var mode = this.mode;

    // warn invalid mode
    if (mode && mode !== 'in-out' && mode !== 'out-in'
    ) {
      warn(
        'invalid <transition> mode: ' + mode,
        this.$parent
      );
    }

    var rawChild = children[0];

    // if this is a component root node and the component's
    // parent container node also has transition, skip.
    if (hasParentTransition(this.$vnode)) {
      return rawChild
    }

    // apply transition data to child
    // use getRealChild() to ignore abstract components e.g. keep-alive
    var child = getRealChild(rawChild);
    /* istanbul ignore if */
    if (!child) {
      return rawChild
    }

    if (this._leaving) {
      return placeholder(h, rawChild)
    }

    // ensure a key that is unique to the vnode type and to this transition
    // component instance. This key will be used to remove pending leaving nodes
    // during entering.
    var id = "__transition-" + (this._uid) + "-";
    child.key = child.key == null
      ? child.isComment
        ? id + 'comment'
        : id + child.tag
      : isPrimitive(child.key)
        ? (String(child.key).indexOf(id) === 0 ? child.key : id + child.key)
        : child.key;

    var data = (child.data || (child.data = {})).transition = extractTransitionData(this);
    var oldRawChild = this._vnode;
    var oldChild = getRealChild(oldRawChild);

    // mark v-show
    // so that the transition module can hand over the control to the directive
    if (child.data.directives && child.data.directives.some(isVShowDirective)) {
      child.data.show = true;
    }

    if (
      oldChild &&
      oldChild.data &&
      !isSameChild(child, oldChild) &&
      !isAsyncPlaceholder(oldChild) &&
      // #6687 component root is a comment node
      !(oldChild.componentInstance && oldChild.componentInstance._vnode.isComment)
    ) {
      // replace old child transition data with fresh one
      // important for dynamic transitions!
      var oldData = oldChild.data.transition = extend({}, data);
      // handle transition mode
      if (mode === 'out-in') {
        // return placeholder node and queue update when leave finishes
        this._leaving = true;
        mergeVNodeHook(oldData, 'afterLeave', function () {
          this$1._leaving = false;
          this$1.$forceUpdate();
        });
        return placeholder(h, rawChild)
      } else if (mode === 'in-out') {
        if (isAsyncPlaceholder(child)) {
          return oldRawChild
        }
        var delayedLeave;
        var performLeave = function () { delayedLeave(); };
        mergeVNodeHook(data, 'afterEnter', performLeave);
        mergeVNodeHook(data, 'enterCancelled', performLeave);
        mergeVNodeHook(oldData, 'delayLeave', function (leave) { delayedLeave = leave; });
      }
    }

    return rawChild
  }
};

/*  */

var props = extend({
  tag: String,
  moveClass: String
}, transitionProps);

delete props.mode;

var TransitionGroup = {
  props: props,

  beforeMount: function beforeMount () {
    var this$1 = this;

    var update = this._update;
    this._update = function (vnode, hydrating) {
      var restoreActiveInstance = setActiveInstance(this$1);
      // force removing pass
      this$1.__patch__(
        this$1._vnode,
        this$1.kept,
        false, // hydrating
        true // removeOnly (!important, avoids unnecessary moves)
      );
      this$1._vnode = this$1.kept;
      restoreActiveInstance();
      update.call(this$1, vnode, hydrating);
    };
  },

  render: function render (h) {
    var tag = this.tag || this.$vnode.data.tag || 'span';
    var map = Object.create(null);
    var prevChildren = this.prevChildren = this.children;
    var rawChildren = this.$slots.default || [];
    var children = this.children = [];
    var transitionData = extractTransitionData(this);

    for (var i = 0; i < rawChildren.length; i++) {
      var c = rawChildren[i];
      if (c.tag) {
        if (c.key != null && String(c.key).indexOf('__vlist') !== 0) {
          children.push(c);
          map[c.key] = c
          ;(c.data || (c.data = {})).transition = transitionData;
        } else {
          var opts = c.componentOptions;
          var name = opts ? (opts.Ctor.options.name || opts.tag || '') : c.tag;
          warn(("<transition-group> children must be keyed: <" + name + ">"));
        }
      }
    }

    if (prevChildren) {
      var kept = [];
      var removed = [];
      for (var i$1 = 0; i$1 < prevChildren.length; i$1++) {
        var c$1 = prevChildren[i$1];
        c$1.data.transition = transitionData;
        c$1.data.pos = c$1.elm.getBoundingClientRect();
        if (map[c$1.key]) {
          kept.push(c$1);
        } else {
          removed.push(c$1);
        }
      }
      this.kept = h(tag, null, kept);
      this.removed = removed;
    }

    return h(tag, null, children)
  },

  updated: function updated () {
    var children = this.prevChildren;
    var moveClass = this.moveClass || ((this.name || 'v') + '-move');
    if (!children.length || !this.hasMove(children[0].elm, moveClass)) {
      return
    }

    // we divide the work into three loops to avoid mixing DOM reads and writes
    // in each iteration - which helps prevent layout thrashing.
    children.forEach(callPendingCbs);
    children.forEach(recordPosition);
    children.forEach(applyTranslation);

    // force reflow to put everything in position
    // assign to this to avoid being removed in tree-shaking
    // $flow-disable-line
    this._reflow = document.body.offsetHeight;

    children.forEach(function (c) {
      if (c.data.moved) {
        var el = c.elm;
        var s = el.style;
        addTransitionClass(el, moveClass);
        s.transform = s.WebkitTransform = s.transitionDuration = '';
        el.addEventListener(transitionEndEvent, el._moveCb = function cb (e) {
          if (e && e.target !== el) {
            return
          }
          if (!e || /transform$/.test(e.propertyName)) {
            el.removeEventListener(transitionEndEvent, cb);
            el._moveCb = null;
            removeTransitionClass(el, moveClass);
          }
        });
      }
    });
  },

  methods: {
    hasMove: function hasMove (el, moveClass) {
      /* istanbul ignore if */
      if (!hasTransition) {
        return false
      }
      /* istanbul ignore if */
      if (this._hasMove) {
        return this._hasMove
      }
      // Detect whether an element with the move class applied has
      // CSS transitions. Since the element may be inside an entering
      // transition at this very moment, we make a clone of it and remove
      // all other transition classes applied to ensure only the move class
      // is applied.
      var clone = el.cloneNode();
      if (el._transitionClasses) {
        el._transitionClasses.forEach(function (cls) { removeClass(clone, cls); });
      }
      addClass(clone, moveClass);
      clone.style.display = 'none';
      this.$el.appendChild(clone);
      var info = getTransitionInfo(clone);
      this.$el.removeChild(clone);
      return (this._hasMove = info.hasTransform)
    }
  }
};

function callPendingCbs (c) {
  /* istanbul ignore if */
  if (c.elm._moveCb) {
    c.elm._moveCb();
  }
  /* istanbul ignore if */
  if (c.elm._enterCb) {
    c.elm._enterCb();
  }
}

function recordPosition (c) {
  c.data.newPos = c.elm.getBoundingClientRect();
}

function applyTranslation (c) {
  var oldPos = c.data.pos;
  var newPos = c.data.newPos;
  var dx = oldPos.left - newPos.left;
  var dy = oldPos.top - newPos.top;
  if (dx || dy) {
    c.data.moved = true;
    var s = c.elm.style;
    s.transform = s.WebkitTransform = "translate(" + dx + "px," + dy + "px)";
    s.transitionDuration = '0s';
  }
}

var platformComponents = {
  Transition: Transition,
  TransitionGroup: TransitionGroup
};

/*  */

// install platform specific utils
Vue.config.mustUseProp = mustUseProp;
Vue.config.isReservedTag = isReservedTag;
Vue.config.isReservedAttr = isReservedAttr;
Vue.config.getTagNamespace = getTagNamespace;
Vue.config.isUnknownElement = isUnknownElement;

// install platform runtime directives & components
extend(Vue.options.directives, platformDirectives);
extend(Vue.options.components, platformComponents);

// install platform patch function
Vue.prototype.__patch__ = inBrowser ? patch : noop;

// public mount method
Vue.prototype.$mount = function (
  el,
  hydrating
) {
  el = el && inBrowser ? query(el) : undefined;
  return mountComponent(this, el, hydrating)
};

// devtools global hook
/* istanbul ignore next */
if (inBrowser) {
  setTimeout(function () {
    if (config.devtools) {
      if (devtools) {
        devtools.emit('init', Vue);
      } else {
        console[console.info ? 'info' : 'log'](
          'Download the Vue Devtools extension for a better development experience:\n' +
          'https://github.com/vuejs/vue-devtools'
        );
      }
    }
    if (config.productionTip !== false &&
      typeof console !== 'undefined'
    ) {
      console[console.info ? 'info' : 'log'](
        "You are running Vue in development mode.\n" +
        "Make sure to turn on production mode when deploying for production.\n" +
        "See more tips at https://vuejs.org/guide/deployment.html"
      );
    }
  }, 0);
}

/*  */

var defaultTagRE = /\{\{((?:.|\r?\n)+?)\}\}/g;
var regexEscapeRE = /[-.*+?^${}()|[\]\/\\]/g;

var buildRegex = cached(function (delimiters) {
  var open = delimiters[0].replace(regexEscapeRE, '\\$&');
  var close = delimiters[1].replace(regexEscapeRE, '\\$&');
  return new RegExp(open + '((?:.|\\n)+?)' + close, 'g')
});



function parseText (
  text,
  delimiters
) {
  var tagRE = delimiters ? buildRegex(delimiters) : defaultTagRE;
  if (!tagRE.test(text)) {
    return
  }
  var tokens = [];
  var rawTokens = [];
  var lastIndex = tagRE.lastIndex = 0;
  var match, index, tokenValue;
  while ((match = tagRE.exec(text))) {
    index = match.index;
    // push text token
    if (index > lastIndex) {
      rawTokens.push(tokenValue = text.slice(lastIndex, index));
      tokens.push(JSON.stringify(tokenValue));
    }
    // tag token
    var exp = parseFilters(match[1].trim());
    tokens.push(("_s(" + exp + ")"));
    rawTokens.push({ '@binding': exp });
    lastIndex = index + match[0].length;
  }
  if (lastIndex < text.length) {
    rawTokens.push(tokenValue = text.slice(lastIndex));
    tokens.push(JSON.stringify(tokenValue));
  }
  return {
    expression: tokens.join('+'),
    tokens: rawTokens
  }
}

/*  */

function transformNode (el, options) {
  var warn = options.warn || baseWarn;
  var staticClass = getAndRemoveAttr(el, 'class');
  if (staticClass) {
    var res = parseText(staticClass, options.delimiters);
    if (res) {
      warn(
        "class=\"" + staticClass + "\": " +
        'Interpolation inside attributes has been removed. ' +
        'Use v-bind or the colon shorthand instead. For example, ' +
        'instead of <div class="{{ val }}">, use <div :class="val">.',
        el.rawAttrsMap['class']
      );
    }
  }
  if (staticClass) {
    el.staticClass = JSON.stringify(staticClass);
  }
  var classBinding = getBindingAttr(el, 'class', false /* getStatic */);
  if (classBinding) {
    el.classBinding = classBinding;
  }
}

function genData (el) {
  var data = '';
  if (el.staticClass) {
    data += "staticClass:" + (el.staticClass) + ",";
  }
  if (el.classBinding) {
    data += "class:" + (el.classBinding) + ",";
  }
  return data
}

var klass$1 = {
  staticKeys: ['staticClass'],
  transformNode: transformNode,
  genData: genData
};

/*  */

function transformNode$1 (el, options) {
  var warn = options.warn || baseWarn;
  var staticStyle = getAndRemoveAttr(el, 'style');
  if (staticStyle) {
    /* istanbul ignore if */
    {
      var res = parseText(staticStyle, options.delimiters);
      if (res) {
        warn(
          "style=\"" + staticStyle + "\": " +
          'Interpolation inside attributes has been removed. ' +
          'Use v-bind or the colon shorthand instead. For example, ' +
          'instead of <div style="{{ val }}">, use <div :style="val">.',
          el.rawAttrsMap['style']
        );
      }
    }
    el.staticStyle = JSON.stringify(parseStyleText(staticStyle));
  }

  var styleBinding = getBindingAttr(el, 'style', false /* getStatic */);
  if (styleBinding) {
    el.styleBinding = styleBinding;
  }
}

function genData$1 (el) {
  var data = '';
  if (el.staticStyle) {
    data += "staticStyle:" + (el.staticStyle) + ",";
  }
  if (el.styleBinding) {
    data += "style:(" + (el.styleBinding) + "),";
  }
  return data
}

var style$1 = {
  staticKeys: ['staticStyle'],
  transformNode: transformNode$1,
  genData: genData$1
};

/*  */

var decoder;

var he = {
  decode: function decode (html) {
    decoder = decoder || document.createElement('div');
    decoder.innerHTML = html;
    return decoder.textContent
  }
};

/*  */

var isUnaryTag = makeMap(
  'area,base,br,col,embed,frame,hr,img,input,isindex,keygen,' +
  'link,meta,param,source,track,wbr'
);

// Elements that you can, intentionally, leave open
// (and which close themselves)
var canBeLeftOpenTag = makeMap(
  'colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr,source'
);

// HTML5 tags https://html.spec.whatwg.org/multipage/indices.html#elements-3
// Phrasing Content https://html.spec.whatwg.org/multipage/dom.html#phrasing-content
var isNonPhrasingTag = makeMap(
  'address,article,aside,base,blockquote,body,caption,col,colgroup,dd,' +
  'details,dialog,div,dl,dt,fieldset,figcaption,figure,footer,form,' +
  'h1,h2,h3,h4,h5,h6,head,header,hgroup,hr,html,legend,li,menuitem,meta,' +
  'optgroup,option,param,rp,rt,source,style,summary,tbody,td,tfoot,th,thead,' +
  'title,tr,track'
);

/**
 * Not type-checking this file because it's mostly vendor code.
 */

// Regular Expressions for parsing tags and attributes
var attribute = /^\s*([^\s"'<>\/=]+)(?:\s*(=)\s*(?:"([^"]*)"+|'([^']*)'+|([^\s"'=<>`]+)))?/;
var dynamicArgAttribute = /^\s*((?:v-[\w-]+:|@|:|#)\[[^=]+\][^\s"'<>\/=]*)(?:\s*(=)\s*(?:"([^"]*)"+|'([^']*)'+|([^\s"'=<>`]+)))?/;
var ncname = "[a-zA-Z_][\\-\\.0-9_a-zA-Z" + (unicodeRegExp.source) + "]*";
var qnameCapture = "((?:" + ncname + "\\:)?" + ncname + ")";
var startTagOpen = new RegExp(("^<" + qnameCapture));
var startTagClose = /^\s*(\/?)>/;
var endTag = new RegExp(("^<\\/" + qnameCapture + "[^>]*>"));
var doctype = /^<!DOCTYPE [^>]+>/i;
// #7298: escape - to avoid being passed as HTML comment when inlined in page
var comment = /^<!\--/;
var conditionalComment = /^<!\[/;

// Special Elements (can contain anything)
var isPlainTextElement = makeMap('script,style,textarea', true);
var reCache = {};

var decodingMap = {
  '&lt;': '<',
  '&gt;': '>',
  '&quot;': '"',
  '&amp;': '&',
  '&#10;': '\n',
  '&#9;': '\t',
  '&#39;': "'"
};
var encodedAttr = /&(?:lt|gt|quot|amp|#39);/g;
var encodedAttrWithNewLines = /&(?:lt|gt|quot|amp|#39|#10|#9);/g;

// #5992
var isIgnoreNewlineTag = makeMap('pre,textarea', true);
var shouldIgnoreFirstNewline = function (tag, html) { return tag && isIgnoreNewlineTag(tag) && html[0] === '\n'; };

function decodeAttr (value, shouldDecodeNewlines) {
  var re = shouldDecodeNewlines ? encodedAttrWithNewLines : encodedAttr;
  return value.replace(re, function (match) { return decodingMap[match]; })
}

function parseHTML (html, options) {
  var stack = [];
  var expectHTML = options.expectHTML;
  var isUnaryTag$$1 = options.isUnaryTag || no;
  var canBeLeftOpenTag$$1 = options.canBeLeftOpenTag || no;
  var index = 0;
  var last, lastTag;
  while (html) {
    last = html;
    // Make sure we're not in a plaintext content element like script/style
    if (!lastTag || !isPlainTextElement(lastTag)) {
      var textEnd = html.indexOf('<');
      if (textEnd === 0) {
        // Comment:
        if (comment.test(html)) {
          var commentEnd = html.indexOf('-->');

          if (commentEnd >= 0) {
            if (options.shouldKeepComment) {
              options.comment(html.substring(4, commentEnd), index, index + commentEnd + 3);
            }
            advance(commentEnd + 3);
            continue
          }
        }

        // http://en.wikipedia.org/wiki/Conditional_comment#Downlevel-revealed_conditional_comment
        if (conditionalComment.test(html)) {
          var conditionalEnd = html.indexOf(']>');

          if (conditionalEnd >= 0) {
            advance(conditionalEnd + 2);
            continue
          }
        }

        // Doctype:
        var doctypeMatch = html.match(doctype);
        if (doctypeMatch) {
          advance(doctypeMatch[0].length);
          continue
        }

        // End tag:
        var endTagMatch = html.match(endTag);
        if (endTagMatch) {
          var curIndex = index;
          advance(endTagMatch[0].length);
          parseEndTag(endTagMatch[1], curIndex, index);
          continue
        }

        // Start tag:
        var startTagMatch = parseStartTag();
        if (startTagMatch) {
          handleStartTag(startTagMatch);
          if (shouldIgnoreFirstNewline(startTagMatch.tagName, html)) {
            advance(1);
          }
          continue
        }
      }

      var text = (void 0), rest = (void 0), next = (void 0);
      if (textEnd >= 0) {
        rest = html.slice(textEnd);
        while (
          !endTag.test(rest) &&
          !startTagOpen.test(rest) &&
          !comment.test(rest) &&
          !conditionalComment.test(rest)
        ) {
          // < in plain text, be forgiving and treat it as text
          next = rest.indexOf('<', 1);
          if (next < 0) { break }
          textEnd += next;
          rest = html.slice(textEnd);
        }
        text = html.substring(0, textEnd);
      }

      if (textEnd < 0) {
        text = html;
      }

      if (text) {
        advance(text.length);
      }

      if (options.chars && text) {
        options.chars(text, index - text.length, index);
      }
    } else {
      var endTagLength = 0;
      var stackedTag = lastTag.toLowerCase();
      var reStackedTag = reCache[stackedTag] || (reCache[stackedTag] = new RegExp('([\\s\\S]*?)(</' + stackedTag + '[^>]*>)', 'i'));
      var rest$1 = html.replace(reStackedTag, function (all, text, endTag) {
        endTagLength = endTag.length;
        if (!isPlainTextElement(stackedTag) && stackedTag !== 'noscript') {
          text = text
            .replace(/<!\--([\s\S]*?)-->/g, '$1') // #7298
            .replace(/<!\[CDATA\[([\s\S]*?)]]>/g, '$1');
        }
        if (shouldIgnoreFirstNewline(stackedTag, text)) {
          text = text.slice(1);
        }
        if (options.chars) {
          options.chars(text);
        }
        return ''
      });
      index += html.length - rest$1.length;
      html = rest$1;
      parseEndTag(stackedTag, index - endTagLength, index);
    }

    if (html === last) {
      options.chars && options.chars(html);
      if (!stack.length && options.warn) {
        options.warn(("Mal-formatted tag at end of template: \"" + html + "\""), { start: index + html.length });
      }
      break
    }
  }

  // Clean up any remaining tags
  parseEndTag();

  function advance (n) {
    index += n;
    html = html.substring(n);
  }

  function parseStartTag () {
    var start = html.match(startTagOpen);
    if (start) {
      var match = {
        tagName: start[1],
        attrs: [],
        start: index
      };
      advance(start[0].length);
      var end, attr;
      while (!(end = html.match(startTagClose)) && (attr = html.match(dynamicArgAttribute) || html.match(attribute))) {
        attr.start = index;
        advance(attr[0].length);
        attr.end = index;
        match.attrs.push(attr);
      }
      if (end) {
        match.unarySlash = end[1];
        advance(end[0].length);
        match.end = index;
        return match
      }
    }
  }

  function handleStartTag (match) {
    var tagName = match.tagName;
    var unarySlash = match.unarySlash;

    if (expectHTML) {
      if (lastTag === 'p' && isNonPhrasingTag(tagName)) {
        parseEndTag(lastTag);
      }
      if (canBeLeftOpenTag$$1(tagName) && lastTag === tagName) {
        parseEndTag(tagName);
      }
    }

    var unary = isUnaryTag$$1(tagName) || !!unarySlash;

    var l = match.attrs.length;
    var attrs = new Array(l);
    for (var i = 0; i < l; i++) {
      var args = match.attrs[i];
      var value = args[3] || args[4] || args[5] || '';
      var shouldDecodeNewlines = tagName === 'a' && args[1] === 'href'
        ? options.shouldDecodeNewlinesForHref
        : options.shouldDecodeNewlines;
      attrs[i] = {
        name: args[1],
        value: decodeAttr(value, shouldDecodeNewlines)
      };
      if (options.outputSourceRange) {
        attrs[i].start = args.start + args[0].match(/^\s*/).length;
        attrs[i].end = args.end;
      }
    }

    if (!unary) {
      stack.push({ tag: tagName, lowerCasedTag: tagName.toLowerCase(), attrs: attrs, start: match.start, end: match.end });
      lastTag = tagName;
    }

    if (options.start) {
      options.start(tagName, attrs, unary, match.start, match.end);
    }
  }

  function parseEndTag (tagName, start, end) {
    var pos, lowerCasedTagName;
    if (start == null) { start = index; }
    if (end == null) { end = index; }

    // Find the closest opened tag of the same type
    if (tagName) {
      lowerCasedTagName = tagName.toLowerCase();
      for (pos = stack.length - 1; pos >= 0; pos--) {
        if (stack[pos].lowerCasedTag === lowerCasedTagName) {
          break
        }
      }
    } else {
      // If no tag name is provided, clean shop
      pos = 0;
    }

    if (pos >= 0) {
      // Close all the open elements, up the stack
      for (var i = stack.length - 1; i >= pos; i--) {
        if (i > pos || !tagName &&
          options.warn
        ) {
          options.warn(
            ("tag <" + (stack[i].tag) + "> has no matching end tag."),
            { start: stack[i].start, end: stack[i].end }
          );
        }
        if (options.end) {
          options.end(stack[i].tag, start, end);
        }
      }

      // Remove the open elements from the stack
      stack.length = pos;
      lastTag = pos && stack[pos - 1].tag;
    } else if (lowerCasedTagName === 'br') {
      if (options.start) {
        options.start(tagName, [], true, start, end);
      }
    } else if (lowerCasedTagName === 'p') {
      if (options.start) {
        options.start(tagName, [], false, start, end);
      }
      if (options.end) {
        options.end(tagName, start, end);
      }
    }
  }
}

/*  */

var onRE = /^@|^v-on:/;
var dirRE = /^v-|^@|^:|^#/;
var forAliasRE = /([\s\S]*?)\s+(?:in|of)\s+([\s\S]*)/;
var forIteratorRE = /,([^,\}\]]*)(?:,([^,\}\]]*))?$/;
var stripParensRE = /^\(|\)$/g;
var dynamicArgRE = /^\[.*\]$/;

var argRE = /:(.*)$/;
var bindRE = /^:|^\.|^v-bind:/;
var modifierRE = /\.[^.\]]+(?=[^\]]*$)/g;

var slotRE = /^v-slot(:|$)|^#/;

var lineBreakRE = /[\r\n]/;
var whitespaceRE$1 = /\s+/g;

var invalidAttributeRE = /[\s"'<>\/=]/;

var decodeHTMLCached = cached(he.decode);

var emptySlotScopeToken = "_empty_";

// configurable state
var warn$2;
var delimiters;
var transforms;
var preTransforms;
var postTransforms;
var platformIsPreTag;
var platformMustUseProp;
var platformGetTagNamespace;
var maybeComponent;

function createASTElement (
  tag,
  attrs,
  parent
) {
  return {
    type: 1,
    tag: tag,
    attrsList: attrs,
    attrsMap: makeAttrsMap(attrs),
    rawAttrsMap: {},
    parent: parent,
    children: []
  }
}

/**
 * Convert HTML string to AST.
 */
function parse (
  template,
  options
) {
  warn$2 = options.warn || baseWarn;

  platformIsPreTag = options.isPreTag || no;
  platformMustUseProp = options.mustUseProp || no;
  platformGetTagNamespace = options.getTagNamespace || no;
  var isReservedTag = options.isReservedTag || no;
  maybeComponent = function (el) { return !!el.component || !isReservedTag(el.tag); };

  transforms = pluckModuleFunction(options.modules, 'transformNode');
  preTransforms = pluckModuleFunction(options.modules, 'preTransformNode');
  postTransforms = pluckModuleFunction(options.modules, 'postTransformNode');

  delimiters = options.delimiters;

  var stack = [];
  var preserveWhitespace = options.preserveWhitespace !== false;
  var whitespaceOption = options.whitespace;
  var root;
  var currentParent;
  var inVPre = false;
  var inPre = false;
  var warned = false;

  function warnOnce (msg, range) {
    if (!warned) {
      warned = true;
      warn$2(msg, range);
    }
  }

  function closeElement (element) {
    trimEndingWhitespace(element);
    if (!inVPre && !element.processed) {
      element = processElement(element, options);
    }
    // tree management
    if (!stack.length && element !== root) {
      // allow root elements with v-if, v-else-if and v-else
      if (root.if && (element.elseif || element.else)) {
        {
          checkRootConstraints(element);
        }
        addIfCondition(root, {
          exp: element.elseif,
          block: element
        });
      } else {
        warnOnce(
          "Component template should contain exactly one root element. " +
          "If you are using v-if on multiple elements, " +
          "use v-else-if to chain them instead.",
          { start: element.start }
        );
      }
    }
    if (currentParent && !element.forbidden) {
      if (element.elseif || element.else) {
        processIfConditions(element, currentParent);
      } else {
        if (element.slotScope) {
          // scoped slot
          // keep it in the children list so that v-else(-if) conditions can
          // find it as the prev node.
          var name = element.slotTarget || '"default"'
          ;(currentParent.scopedSlots || (currentParent.scopedSlots = {}))[name] = element;
        }
        currentParent.children.push(element);
        element.parent = currentParent;
      }
    }

    // final children cleanup
    // filter out scoped slots
    element.children = element.children.filter(function (c) { return !(c).slotScope; });
    // remove trailing whitespace node again
    trimEndingWhitespace(element);

    // check pre state
    if (element.pre) {
      inVPre = false;
    }
    if (platformIsPreTag(element.tag)) {
      inPre = false;
    }
    // apply post-transforms
    for (var i = 0; i < postTransforms.length; i++) {
      postTransforms[i](element, options);
    }
  }

  function trimEndingWhitespace (el) {
    // remove trailing whitespace node
    if (!inPre) {
      var lastNode;
      while (
        (lastNode = el.children[el.children.length - 1]) &&
        lastNode.type === 3 &&
        lastNode.text === ' '
      ) {
        el.children.pop();
      }
    }
  }

  function checkRootConstraints (el) {
    if (el.tag === 'slot' || el.tag === 'template') {
      warnOnce(
        "Cannot use <" + (el.tag) + "> as component root element because it may " +
        'contain multiple nodes.',
        { start: el.start }
      );
    }
    if (el.attrsMap.hasOwnProperty('v-for')) {
      warnOnce(
        'Cannot use v-for on stateful component root element because ' +
        'it renders multiple elements.',
        el.rawAttrsMap['v-for']
      );
    }
  }

  parseHTML(template, {
    warn: warn$2,
    expectHTML: options.expectHTML,
    isUnaryTag: options.isUnaryTag,
    canBeLeftOpenTag: options.canBeLeftOpenTag,
    shouldDecodeNewlines: options.shouldDecodeNewlines,
    shouldDecodeNewlinesForHref: options.shouldDecodeNewlinesForHref,
    shouldKeepComment: options.comments,
    outputSourceRange: options.outputSourceRange,
    start: function start (tag, attrs, unary, start$1, end) {
      // check namespace.
      // inherit parent ns if there is one
      var ns = (currentParent && currentParent.ns) || platformGetTagNamespace(tag);

      // handle IE svg bug
      /* istanbul ignore if */
      if (isIE && ns === 'svg') {
        attrs = guardIESVGBug(attrs);
      }

      var element = createASTElement(tag, attrs, currentParent);
      if (ns) {
        element.ns = ns;
      }

      {
        if (options.outputSourceRange) {
          element.start = start$1;
          element.end = end;
          element.rawAttrsMap = element.attrsList.reduce(function (cumulated, attr) {
            cumulated[attr.name] = attr;
            return cumulated
          }, {});
        }
        attrs.forEach(function (attr) {
          if (invalidAttributeRE.test(attr.name)) {
            warn$2(
              "Invalid dynamic argument expression: attribute names cannot contain " +
              "spaces, quotes, <, >, / or =.",
              {
                start: attr.start + attr.name.indexOf("["),
                end: attr.start + attr.name.length
              }
            );
          }
        });
      }

      if (isForbiddenTag(element) && !isServerRendering()) {
        element.forbidden = true;
        warn$2(
          'Templates should only be responsible for mapping the state to the ' +
          'UI. Avoid placing tags with side-effects in your templates, such as ' +
          "<" + tag + ">" + ', as they will not be parsed.',
          { start: element.start }
        );
      }

      // apply pre-transforms
      for (var i = 0; i < preTransforms.length; i++) {
        element = preTransforms[i](element, options) || element;
      }

      if (!inVPre) {
        processPre(element);
        if (element.pre) {
          inVPre = true;
        }
      }
      if (platformIsPreTag(element.tag)) {
        inPre = true;
      }
      if (inVPre) {
        processRawAttrs(element);
      } else if (!element.processed) {
        // structural directives
        processFor(element);
        processIf(element);
        processOnce(element);
      }

      if (!root) {
        root = element;
        {
          checkRootConstraints(root);
        }
      }

      if (!unary) {
        currentParent = element;
        stack.push(element);
      } else {
        closeElement(element);
      }
    },

    end: function end (tag, start, end$1) {
      var element = stack[stack.length - 1];
      // pop stack
      stack.length -= 1;
      currentParent = stack[stack.length - 1];
      if (options.outputSourceRange) {
        element.end = end$1;
      }
      closeElement(element);
    },

    chars: function chars (text, start, end) {
      if (!currentParent) {
        {
          if (text === template) {
            warnOnce(
              'Component template requires a root element, rather than just text.',
              { start: start }
            );
          } else if ((text = text.trim())) {
            warnOnce(
              ("text \"" + text + "\" outside root element will be ignored."),
              { start: start }
            );
          }
        }
        return
      }
      // IE textarea placeholder bug
      /* istanbul ignore if */
      if (isIE &&
        currentParent.tag === 'textarea' &&
        currentParent.attrsMap.placeholder === text
      ) {
        return
      }
      var children = currentParent.children;
      if (inPre || text.trim()) {
        text = isTextTag(currentParent) ? text : decodeHTMLCached(text);
      } else if (!children.length) {
        // remove the whitespace-only node right after an opening tag
        text = '';
      } else if (whitespaceOption) {
        if (whitespaceOption === 'condense') {
          // in condense mode, remove the whitespace node if it contains
          // line break, otherwise condense to a single space
          text = lineBreakRE.test(text) ? '' : ' ';
        } else {
          text = ' ';
        }
      } else {
        text = preserveWhitespace ? ' ' : '';
      }
      if (text) {
        if (!inPre && whitespaceOption === 'condense') {
          // condense consecutive whitespaces into single space
          text = text.replace(whitespaceRE$1, ' ');
        }
        var res;
        var child;
        if (!inVPre && text !== ' ' && (res = parseText(text, delimiters))) {
          child = {
            type: 2,
            expression: res.expression,
            tokens: res.tokens,
            text: text
          };
        } else if (text !== ' ' || !children.length || children[children.length - 1].text !== ' ') {
          child = {
            type: 3,
            text: text
          };
        }
        if (child) {
          if (options.outputSourceRange) {
            child.start = start;
            child.end = end;
          }
          children.push(child);
        }
      }
    },
    comment: function comment (text, start, end) {
      // adding anything as a sibling to the root node is forbidden
      // comments should still be allowed, but ignored
      if (currentParent) {
        var child = {
          type: 3,
          text: text,
          isComment: true
        };
        if (options.outputSourceRange) {
          child.start = start;
          child.end = end;
        }
        currentParent.children.push(child);
      }
    }
  });
  return root
}

function processPre (el) {
  if (getAndRemoveAttr(el, 'v-pre') != null) {
    el.pre = true;
  }
}

function processRawAttrs (el) {
  var list = el.attrsList;
  var len = list.length;
  if (len) {
    var attrs = el.attrs = new Array(len);
    for (var i = 0; i < len; i++) {
      attrs[i] = {
        name: list[i].name,
        value: JSON.stringify(list[i].value)
      };
      if (list[i].start != null) {
        attrs[i].start = list[i].start;
        attrs[i].end = list[i].end;
      }
    }
  } else if (!el.pre) {
    // non root node in pre blocks with no attributes
    el.plain = true;
  }
}

function processElement (
  element,
  options
) {
  processKey(element);

  // determine whether this is a plain element after
  // removing structural attributes
  element.plain = (
    !element.key &&
    !element.scopedSlots &&
    !element.attrsList.length
  );

  processRef(element);
  processSlotContent(element);
  processSlotOutlet(element);
  processComponent(element);
  for (var i = 0; i < transforms.length; i++) {
    element = transforms[i](element, options) || element;
  }
  processAttrs(element);
  return element
}

function processKey (el) {
  var exp = getBindingAttr(el, 'key');
  if (exp) {
    {
      if (el.tag === 'template') {
        warn$2(
          "<template> cannot be keyed. Place the key on real elements instead.",
          getRawBindingAttr(el, 'key')
        );
      }
      if (el.for) {
        var iterator = el.iterator2 || el.iterator1;
        var parent = el.parent;
        if (iterator && iterator === exp && parent && parent.tag === 'transition-group') {
          warn$2(
            "Do not use v-for index as key on <transition-group> children, " +
            "this is the same as not using keys.",
            getRawBindingAttr(el, 'key'),
            true /* tip */
          );
        }
      }
    }
    el.key = exp;
  }
}

function processRef (el) {
  var ref = getBindingAttr(el, 'ref');
  if (ref) {
    el.ref = ref;
    el.refInFor = checkInFor(el);
  }
}

function processFor (el) {
  var exp;
  if ((exp = getAndRemoveAttr(el, 'v-for'))) {
    var res = parseFor(exp);
    if (res) {
      extend(el, res);
    } else {
      warn$2(
        ("Invalid v-for expression: " + exp),
        el.rawAttrsMap['v-for']
      );
    }
  }
}



function parseFor (exp) {
  var inMatch = exp.match(forAliasRE);
  if (!inMatch) { return }
  var res = {};
  res.for = inMatch[2].trim();
  var alias = inMatch[1].trim().replace(stripParensRE, '');
  var iteratorMatch = alias.match(forIteratorRE);
  if (iteratorMatch) {
    res.alias = alias.replace(forIteratorRE, '').trim();
    res.iterator1 = iteratorMatch[1].trim();
    if (iteratorMatch[2]) {
      res.iterator2 = iteratorMatch[2].trim();
    }
  } else {
    res.alias = alias;
  }
  return res
}

function processIf (el) {
  var exp = getAndRemoveAttr(el, 'v-if');
  if (exp) {
    el.if = exp;
    addIfCondition(el, {
      exp: exp,
      block: el
    });
  } else {
    if (getAndRemoveAttr(el, 'v-else') != null) {
      el.else = true;
    }
    var elseif = getAndRemoveAttr(el, 'v-else-if');
    if (elseif) {
      el.elseif = elseif;
    }
  }
}

function processIfConditions (el, parent) {
  var prev = findPrevElement(parent.children);
  if (prev && prev.if) {
    addIfCondition(prev, {
      exp: el.elseif,
      block: el
    });
  } else {
    warn$2(
      "v-" + (el.elseif ? ('else-if="' + el.elseif + '"') : 'else') + " " +
      "used on element <" + (el.tag) + "> without corresponding v-if.",
      el.rawAttrsMap[el.elseif ? 'v-else-if' : 'v-else']
    );
  }
}

function findPrevElement (children) {
  var i = children.length;
  while (i--) {
    if (children[i].type === 1) {
      return children[i]
    } else {
      if (children[i].text !== ' ') {
        warn$2(
          "text \"" + (children[i].text.trim()) + "\" between v-if and v-else(-if) " +
          "will be ignored.",
          children[i]
        );
      }
      children.pop();
    }
  }
}

function addIfCondition (el, condition) {
  if (!el.ifConditions) {
    el.ifConditions = [];
  }
  el.ifConditions.push(condition);
}

function processOnce (el) {
  var once$$1 = getAndRemoveAttr(el, 'v-once');
  if (once$$1 != null) {
    el.once = true;
  }
}

// handle content being passed to a component as slot,
// e.g. <template slot="xxx">, <div slot-scope="xxx">
function processSlotContent (el) {
  var slotScope;
  if (el.tag === 'template') {
    slotScope = getAndRemoveAttr(el, 'scope');
    /* istanbul ignore if */
    if (slotScope) {
      warn$2(
        "the \"scope\" attribute for scoped slots have been deprecated and " +
        "replaced by \"slot-scope\" since 2.5. The new \"slot-scope\" attribute " +
        "can also be used on plain elements in addition to <template> to " +
        "denote scoped slots.",
        el.rawAttrsMap['scope'],
        true
      );
    }
    el.slotScope = slotScope || getAndRemoveAttr(el, 'slot-scope');
  } else if ((slotScope = getAndRemoveAttr(el, 'slot-scope'))) {
    /* istanbul ignore if */
    if (el.attrsMap['v-for']) {
      warn$2(
        "Ambiguous combined usage of slot-scope and v-for on <" + (el.tag) + "> " +
        "(v-for takes higher priority). Use a wrapper <template> for the " +
        "scoped slot to make it clearer.",
        el.rawAttrsMap['slot-scope'],
        true
      );
    }
    el.slotScope = slotScope;
  }

  // slot="xxx"
  var slotTarget = getBindingAttr(el, 'slot');
  if (slotTarget) {
    el.slotTarget = slotTarget === '""' ? '"default"' : slotTarget;
    el.slotTargetDynamic = !!(el.attrsMap[':slot'] || el.attrsMap['v-bind:slot']);
    // preserve slot as an attribute for native shadow DOM compat
    // only for non-scoped slots.
    if (el.tag !== 'template' && !el.slotScope) {
      addAttr(el, 'slot', slotTarget, getRawBindingAttr(el, 'slot'));
    }
  }

  // 2.6 v-slot syntax
  {
    if (el.tag === 'template') {
      // v-slot on <template>
      var slotBinding = getAndRemoveAttrByRegex(el, slotRE);
      if (slotBinding) {
        {
          if (el.slotTarget || el.slotScope) {
            warn$2(
              "Unexpected mixed usage of different slot syntaxes.",
              el
            );
          }
          if (el.parent && !maybeComponent(el.parent)) {
            warn$2(
              "<template v-slot> can only appear at the root level inside " +
              "the receiving component",
              el
            );
          }
        }
        var ref = getSlotName(slotBinding);
        var name = ref.name;
        var dynamic = ref.dynamic;
        el.slotTarget = name;
        el.slotTargetDynamic = dynamic;
        el.slotScope = slotBinding.value || emptySlotScopeToken; // force it into a scoped slot for perf
      }
    } else {
      // v-slot on component, denotes default slot
      var slotBinding$1 = getAndRemoveAttrByRegex(el, slotRE);
      if (slotBinding$1) {
        {
          if (!maybeComponent(el)) {
            warn$2(
              "v-slot can only be used on components or <template>.",
              slotBinding$1
            );
          }
          if (el.slotScope || el.slotTarget) {
            warn$2(
              "Unexpected mixed usage of different slot syntaxes.",
              el
            );
          }
          if (el.scopedSlots) {
            warn$2(
              "To avoid scope ambiguity, the default slot should also use " +
              "<template> syntax when there are other named slots.",
              slotBinding$1
            );
          }
        }
        // add the component's children to its default slot
        var slots = el.scopedSlots || (el.scopedSlots = {});
        var ref$1 = getSlotName(slotBinding$1);
        var name$1 = ref$1.name;
        var dynamic$1 = ref$1.dynamic;
        var slotContainer = slots[name$1] = createASTElement('template', [], el);
        slotContainer.slotTarget = name$1;
        slotContainer.slotTargetDynamic = dynamic$1;
        slotContainer.children = el.children.filter(function (c) {
          if (!c.slotScope) {
            c.parent = slotContainer;
            return true
          }
        });
        slotContainer.slotScope = slotBinding$1.value || emptySlotScopeToken;
        // remove children as they are returned from scopedSlots now
        el.children = [];
        // mark el non-plain so data gets generated
        el.plain = false;
      }
    }
  }
}

function getSlotName (binding) {
  var name = binding.name.replace(slotRE, '');
  if (!name) {
    if (binding.name[0] !== '#') {
      name = 'default';
    } else {
      warn$2(
        "v-slot shorthand syntax requires a slot name.",
        binding
      );
    }
  }
  return dynamicArgRE.test(name)
    // dynamic [name]
    ? { name: name.slice(1, -1), dynamic: true }
    // static name
    : { name: ("\"" + name + "\""), dynamic: false }
}

// handle <slot/> outlets
function processSlotOutlet (el) {
  if (el.tag === 'slot') {
    el.slotName = getBindingAttr(el, 'name');
    if (el.key) {
      warn$2(
        "`key` does not work on <slot> because slots are abstract outlets " +
        "and can possibly expand into multiple elements. " +
        "Use the key on a wrapping element instead.",
        getRawBindingAttr(el, 'key')
      );
    }
  }
}

function processComponent (el) {
  var binding;
  if ((binding = getBindingAttr(el, 'is'))) {
    el.component = binding;
  }
  if (getAndRemoveAttr(el, 'inline-template') != null) {
    el.inlineTemplate = true;
  }
}

function processAttrs (el) {
  var list = el.attrsList;
  var i, l, name, rawName, value, modifiers, syncGen, isDynamic;
  for (i = 0, l = list.length; i < l; i++) {
    name = rawName = list[i].name;
    value = list[i].value;
    if (dirRE.test(name)) {
      // mark element as dynamic
      el.hasBindings = true;
      // modifiers
      modifiers = parseModifiers(name.replace(dirRE, ''));
      // support .foo shorthand syntax for the .prop modifier
      if (modifiers) {
        name = name.replace(modifierRE, '');
      }
      if (bindRE.test(name)) { // v-bind
        name = name.replace(bindRE, '');
        value = parseFilters(value);
        isDynamic = dynamicArgRE.test(name);
        if (isDynamic) {
          name = name.slice(1, -1);
        }
        if (
          value.trim().length === 0
        ) {
          warn$2(
            ("The value for a v-bind expression cannot be empty. Found in \"v-bind:" + name + "\"")
          );
        }
        if (modifiers) {
          if (modifiers.prop && !isDynamic) {
            name = camelize(name);
            if (name === 'innerHtml') { name = 'innerHTML'; }
          }
          if (modifiers.camel && !isDynamic) {
            name = camelize(name);
          }
          if (modifiers.sync) {
            syncGen = genAssignmentCode(value, "$event");
            if (!isDynamic) {
              addHandler(
                el,
                ("update:" + (camelize(name))),
                syncGen,
                null,
                false,
                warn$2,
                list[i]
              );
              if (hyphenate(name) !== camelize(name)) {
                addHandler(
                  el,
                  ("update:" + (hyphenate(name))),
                  syncGen,
                  null,
                  false,
                  warn$2,
                  list[i]
                );
              }
            } else {
              // handler w/ dynamic event name
              addHandler(
                el,
                ("\"update:\"+(" + name + ")"),
                syncGen,
                null,
                false,
                warn$2,
                list[i],
                true // dynamic
              );
            }
          }
        }
        if ((modifiers && modifiers.prop) || (
          !el.component && platformMustUseProp(el.tag, el.attrsMap.type, name)
        )) {
          addProp(el, name, value, list[i], isDynamic);
        } else {
          addAttr(el, name, value, list[i], isDynamic);
        }
      } else if (onRE.test(name)) { // v-on
        name = name.replace(onRE, '');
        isDynamic = dynamicArgRE.test(name);
        if (isDynamic) {
          name = name.slice(1, -1);
        }
        addHandler(el, name, value, modifiers, false, warn$2, list[i], isDynamic);
      } else { // normal directives
        name = name.replace(dirRE, '');
        // parse arg
        var argMatch = name.match(argRE);
        var arg = argMatch && argMatch[1];
        isDynamic = false;
        if (arg) {
          name = name.slice(0, -(arg.length + 1));
          if (dynamicArgRE.test(arg)) {
            arg = arg.slice(1, -1);
            isDynamic = true;
          }
        }
        addDirective(el, name, rawName, value, arg, isDynamic, modifiers, list[i]);
        if (name === 'model') {
          checkForAliasModel(el, value);
        }
      }
    } else {
      // literal attribute
      {
        var res = parseText(value, delimiters);
        if (res) {
          warn$2(
            name + "=\"" + value + "\": " +
            'Interpolation inside attributes has been removed. ' +
            'Use v-bind or the colon shorthand instead. For example, ' +
            'instead of <div id="{{ val }}">, use <div :id="val">.',
            list[i]
          );
        }
      }
      addAttr(el, name, JSON.stringify(value), list[i]);
      // #6887 firefox doesn't update muted state if set via attribute
      // even immediately after element creation
      if (!el.component &&
          name === 'muted' &&
          platformMustUseProp(el.tag, el.attrsMap.type, name)) {
        addProp(el, name, 'true', list[i]);
      }
    }
  }
}

function checkInFor (el) {
  var parent = el;
  while (parent) {
    if (parent.for !== undefined) {
      return true
    }
    parent = parent.parent;
  }
  return false
}

function parseModifiers (name) {
  var match = name.match(modifierRE);
  if (match) {
    var ret = {};
    match.forEach(function (m) { ret[m.slice(1)] = true; });
    return ret
  }
}

function makeAttrsMap (attrs) {
  var map = {};
  for (var i = 0, l = attrs.length; i < l; i++) {
    if (
      map[attrs[i].name] && !isIE && !isEdge
    ) {
      warn$2('duplicate attribute: ' + attrs[i].name, attrs[i]);
    }
    map[attrs[i].name] = attrs[i].value;
  }
  return map
}

// for script (e.g. type="x/template") or style, do not decode content
function isTextTag (el) {
  return el.tag === 'script' || el.tag === 'style'
}

function isForbiddenTag (el) {
  return (
    el.tag === 'style' ||
    (el.tag === 'script' && (
      !el.attrsMap.type ||
      el.attrsMap.type === 'text/javascript'
    ))
  )
}

var ieNSBug = /^xmlns:NS\d+/;
var ieNSPrefix = /^NS\d+:/;

/* istanbul ignore next */
function guardIESVGBug (attrs) {
  var res = [];
  for (var i = 0; i < attrs.length; i++) {
    var attr = attrs[i];
    if (!ieNSBug.test(attr.name)) {
      attr.name = attr.name.replace(ieNSPrefix, '');
      res.push(attr);
    }
  }
  return res
}

function checkForAliasModel (el, value) {
  var _el = el;
  while (_el) {
    if (_el.for && _el.alias === value) {
      warn$2(
        "<" + (el.tag) + " v-model=\"" + value + "\">: " +
        "You are binding v-model directly to a v-for iteration alias. " +
        "This will not be able to modify the v-for source array because " +
        "writing to the alias is like modifying a function local variable. " +
        "Consider using an array of objects and use v-model on an object property instead.",
        el.rawAttrsMap['v-model']
      );
    }
    _el = _el.parent;
  }
}

/*  */

function preTransformNode (el, options) {
  if (el.tag === 'input') {
    var map = el.attrsMap;
    if (!map['v-model']) {
      return
    }

    var typeBinding;
    if (map[':type'] || map['v-bind:type']) {
      typeBinding = getBindingAttr(el, 'type');
    }
    if (!map.type && !typeBinding && map['v-bind']) {
      typeBinding = "(" + (map['v-bind']) + ").type";
    }

    if (typeBinding) {
      var ifCondition = getAndRemoveAttr(el, 'v-if', true);
      var ifConditionExtra = ifCondition ? ("&&(" + ifCondition + ")") : "";
      var hasElse = getAndRemoveAttr(el, 'v-else', true) != null;
      var elseIfCondition = getAndRemoveAttr(el, 'v-else-if', true);
      // 1. checkbox
      var branch0 = cloneASTElement(el);
      // process for on the main node
      processFor(branch0);
      addRawAttr(branch0, 'type', 'checkbox');
      processElement(branch0, options);
      branch0.processed = true; // prevent it from double-processed
      branch0.if = "(" + typeBinding + ")==='checkbox'" + ifConditionExtra;
      addIfCondition(branch0, {
        exp: branch0.if,
        block: branch0
      });
      // 2. add radio else-if condition
      var branch1 = cloneASTElement(el);
      getAndRemoveAttr(branch1, 'v-for', true);
      addRawAttr(branch1, 'type', 'radio');
      processElement(branch1, options);
      addIfCondition(branch0, {
        exp: "(" + typeBinding + ")==='radio'" + ifConditionExtra,
        block: branch1
      });
      // 3. other
      var branch2 = cloneASTElement(el);
      getAndRemoveAttr(branch2, 'v-for', true);
      addRawAttr(branch2, ':type', typeBinding);
      processElement(branch2, options);
      addIfCondition(branch0, {
        exp: ifCondition,
        block: branch2
      });

      if (hasElse) {
        branch0.else = true;
      } else if (elseIfCondition) {
        branch0.elseif = elseIfCondition;
      }

      return branch0
    }
  }
}

function cloneASTElement (el) {
  return createASTElement(el.tag, el.attrsList.slice(), el.parent)
}

var model$1 = {
  preTransformNode: preTransformNode
};

var modules$1 = [
  klass$1,
  style$1,
  model$1
];

/*  */

function text (el, dir) {
  if (dir.value) {
    addProp(el, 'textContent', ("_s(" + (dir.value) + ")"), dir);
  }
}

/*  */

function html (el, dir) {
  if (dir.value) {
    addProp(el, 'innerHTML', ("_s(" + (dir.value) + ")"), dir);
  }
}

var directives$1 = {
  model: model,
  text: text,
  html: html
};

/*  */

var baseOptions = {
  expectHTML: true,
  modules: modules$1,
  directives: directives$1,
  isPreTag: isPreTag,
  isUnaryTag: isUnaryTag,
  mustUseProp: mustUseProp,
  canBeLeftOpenTag: canBeLeftOpenTag,
  isReservedTag: isReservedTag,
  getTagNamespace: getTagNamespace,
  staticKeys: genStaticKeys(modules$1)
};

/*  */

var isStaticKey;
var isPlatformReservedTag;

var genStaticKeysCached = cached(genStaticKeys$1);

/**
 * Goal of the optimizer: walk the generated template AST tree
 * and detect sub-trees that are purely static, i.e. parts of
 * the DOM that never needs to change.
 *
 * Once we detect these sub-trees, we can:
 *
 * 1. Hoist them into constants, so that we no longer need to
 *    create fresh nodes for them on each re-render;
 * 2. Completely skip them in the patching process.
 */
function optimize (root, options) {
  if (!root) { return }
  isStaticKey = genStaticKeysCached(options.staticKeys || '');
  isPlatformReservedTag = options.isReservedTag || no;
  // first pass: mark all non-static nodes.
  markStatic$1(root);
  // second pass: mark static roots.
  markStaticRoots(root, false);
}

function genStaticKeys$1 (keys) {
  return makeMap(
    'type,tag,attrsList,attrsMap,plain,parent,children,attrs,start,end,rawAttrsMap' +
    (keys ? ',' + keys : '')
  )
}

function markStatic$1 (node) {
  node.static = isStatic(node);
  if (node.type === 1) {
    // do not make component slot content static. this avoids
    // 1. components not able to mutate slot nodes
    // 2. static slot content fails for hot-reloading
    if (
      !isPlatformReservedTag(node.tag) &&
      node.tag !== 'slot' &&
      node.attrsMap['inline-template'] == null
    ) {
      return
    }
    for (var i = 0, l = node.children.length; i < l; i++) {
      var child = node.children[i];
      markStatic$1(child);
      if (!child.static) {
        node.static = false;
      }
    }
    if (node.ifConditions) {
      for (var i$1 = 1, l$1 = node.ifConditions.length; i$1 < l$1; i$1++) {
        var block = node.ifConditions[i$1].block;
        markStatic$1(block);
        if (!block.static) {
          node.static = false;
        }
      }
    }
  }
}

function markStaticRoots (node, isInFor) {
  if (node.type === 1) {
    if (node.static || node.once) {
      node.staticInFor = isInFor;
    }
    // For a node to qualify as a static root, it should have children that
    // are not just static text. Otherwise the cost of hoisting out will
    // outweigh the benefits and it's better off to just always render it fresh.
    if (node.static && node.children.length && !(
      node.children.length === 1 &&
      node.children[0].type === 3
    )) {
      node.staticRoot = true;
      return
    } else {
      node.staticRoot = false;
    }
    if (node.children) {
      for (var i = 0, l = node.children.length; i < l; i++) {
        markStaticRoots(node.children[i], isInFor || !!node.for);
      }
    }
    if (node.ifConditions) {
      for (var i$1 = 1, l$1 = node.ifConditions.length; i$1 < l$1; i$1++) {
        markStaticRoots(node.ifConditions[i$1].block, isInFor);
      }
    }
  }
}

function isStatic (node) {
  if (node.type === 2) { // expression
    return false
  }
  if (node.type === 3) { // text
    return true
  }
  return !!(node.pre || (
    !node.hasBindings && // no dynamic bindings
    !node.if && !node.for && // not v-if or v-for or v-else
    !isBuiltInTag(node.tag) && // not a built-in
    isPlatformReservedTag(node.tag) && // not a component
    !isDirectChildOfTemplateFor(node) &&
    Object.keys(node).every(isStaticKey)
  ))
}

function isDirectChildOfTemplateFor (node) {
  while (node.parent) {
    node = node.parent;
    if (node.tag !== 'template') {
      return false
    }
    if (node.for) {
      return true
    }
  }
  return false
}

/*  */

var fnExpRE = /^([\w$_]+|\([^)]*?\))\s*=>|^function(?:\s+[\w$]+)?\s*\(/;
var fnInvokeRE = /\([^)]*?\);*$/;
var simplePathRE = /^[A-Za-z_$][\w$]*(?:\.[A-Za-z_$][\w$]*|\['[^']*?']|\["[^"]*?"]|\[\d+]|\[[A-Za-z_$][\w$]*])*$/;

// KeyboardEvent.keyCode aliases
var keyCodes = {
  esc: 27,
  tab: 9,
  enter: 13,
  space: 32,
  up: 38,
  left: 37,
  right: 39,
  down: 40,
  'delete': [8, 46]
};

// KeyboardEvent.key aliases
var keyNames = {
  // #7880: IE11 and Edge use `Esc` for Escape key name.
  esc: ['Esc', 'Escape'],
  tab: 'Tab',
  enter: 'Enter',
  // #9112: IE11 uses `Spacebar` for Space key name.
  space: [' ', 'Spacebar'],
  // #7806: IE11 uses key names without `Arrow` prefix for arrow keys.
  up: ['Up', 'ArrowUp'],
  left: ['Left', 'ArrowLeft'],
  right: ['Right', 'ArrowRight'],
  down: ['Down', 'ArrowDown'],
  // #9112: IE11 uses `Del` for Delete key name.
  'delete': ['Backspace', 'Delete', 'Del']
};

// #4868: modifiers that prevent the execution of the listener
// need to explicitly return null so that we can determine whether to remove
// the listener for .once
var genGuard = function (condition) { return ("if(" + condition + ")return null;"); };

var modifierCode = {
  stop: '$event.stopPropagation();',
  prevent: '$event.preventDefault();',
  self: genGuard("$event.target !== $event.currentTarget"),
  ctrl: genGuard("!$event.ctrlKey"),
  shift: genGuard("!$event.shiftKey"),
  alt: genGuard("!$event.altKey"),
  meta: genGuard("!$event.metaKey"),
  left: genGuard("'button' in $event && $event.button !== 0"),
  middle: genGuard("'button' in $event && $event.button !== 1"),
  right: genGuard("'button' in $event && $event.button !== 2")
};

function genHandlers (
  events,
  isNative
) {
  var prefix = isNative ? 'nativeOn:' : 'on:';
  var staticHandlers = "";
  var dynamicHandlers = "";
  for (var name in events) {
    var handlerCode = genHandler(events[name]);
    if (events[name] && events[name].dynamic) {
      dynamicHandlers += name + "," + handlerCode + ",";
    } else {
      staticHandlers += "\"" + name + "\":" + handlerCode + ",";
    }
  }
  staticHandlers = "{" + (staticHandlers.slice(0, -1)) + "}";
  if (dynamicHandlers) {
    return prefix + "_d(" + staticHandlers + ",[" + (dynamicHandlers.slice(0, -1)) + "])"
  } else {
    return prefix + staticHandlers
  }
}

function genHandler (handler) {
  if (!handler) {
    return 'function(){}'
  }

  if (Array.isArray(handler)) {
    return ("[" + (handler.map(function (handler) { return genHandler(handler); }).join(',')) + "]")
  }

  var isMethodPath = simplePathRE.test(handler.value);
  var isFunctionExpression = fnExpRE.test(handler.value);
  var isFunctionInvocation = simplePathRE.test(handler.value.replace(fnInvokeRE, ''));

  if (!handler.modifiers) {
    if (isMethodPath || isFunctionExpression) {
      return handler.value
    }
    return ("function($event){" + (isFunctionInvocation ? ("return " + (handler.value)) : handler.value) + "}") // inline statement
  } else {
    var code = '';
    var genModifierCode = '';
    var keys = [];
    for (var key in handler.modifiers) {
      if (modifierCode[key]) {
        genModifierCode += modifierCode[key];
        // left/right
        if (keyCodes[key]) {
          keys.push(key);
        }
      } else if (key === 'exact') {
        var modifiers = (handler.modifiers);
        genModifierCode += genGuard(
          ['ctrl', 'shift', 'alt', 'meta']
            .filter(function (keyModifier) { return !modifiers[keyModifier]; })
            .map(function (keyModifier) { return ("$event." + keyModifier + "Key"); })
            .join('||')
        );
      } else {
        keys.push(key);
      }
    }
    if (keys.length) {
      code += genKeyFilter(keys);
    }
    // Make sure modifiers like prevent and stop get executed after key filtering
    if (genModifierCode) {
      code += genModifierCode;
    }
    var handlerCode = isMethodPath
      ? ("return " + (handler.value) + "($event)")
      : isFunctionExpression
        ? ("return (" + (handler.value) + ")($event)")
        : isFunctionInvocation
          ? ("return " + (handler.value))
          : handler.value;
    return ("function($event){" + code + handlerCode + "}")
  }
}

function genKeyFilter (keys) {
  return (
    // make sure the key filters only apply to KeyboardEvents
    // #9441: can't use 'keyCode' in $event because Chrome autofill fires fake
    // key events that do not have keyCode property...
    "if(!$event.type.indexOf('key')&&" +
    (keys.map(genFilterCode).join('&&')) + ")return null;"
  )
}

function genFilterCode (key) {
  var keyVal = parseInt(key, 10);
  if (keyVal) {
    return ("$event.keyCode!==" + keyVal)
  }
  var keyCode = keyCodes[key];
  var keyName = keyNames[key];
  return (
    "_k($event.keyCode," +
    (JSON.stringify(key)) + "," +
    (JSON.stringify(keyCode)) + "," +
    "$event.key," +
    "" + (JSON.stringify(keyName)) +
    ")"
  )
}

/*  */

function on (el, dir) {
  if (dir.modifiers) {
    warn("v-on without argument does not support modifiers.");
  }
  el.wrapListeners = function (code) { return ("_g(" + code + "," + (dir.value) + ")"); };
}

/*  */

function bind$1 (el, dir) {
  el.wrapData = function (code) {
    return ("_b(" + code + ",'" + (el.tag) + "'," + (dir.value) + "," + (dir.modifiers && dir.modifiers.prop ? 'true' : 'false') + (dir.modifiers && dir.modifiers.sync ? ',true' : '') + ")")
  };
}

/*  */

var baseDirectives = {
  on: on,
  bind: bind$1,
  cloak: noop
};

/*  */





var CodegenState = function CodegenState (options) {
  this.options = options;
  this.warn = options.warn || baseWarn;
  this.transforms = pluckModuleFunction(options.modules, 'transformCode');
  this.dataGenFns = pluckModuleFunction(options.modules, 'genData');
  this.directives = extend(extend({}, baseDirectives), options.directives);
  var isReservedTag = options.isReservedTag || no;
  this.maybeComponent = function (el) { return !!el.component || !isReservedTag(el.tag); };
  this.onceId = 0;
  this.staticRenderFns = [];
  this.pre = false;
};



function generate (
  ast,
  options
) {
  var state = new CodegenState(options);
  var code = ast ? genElement(ast, state) : '_c("div")';
  return {
    render: ("with(this){return " + code + "}"),
    staticRenderFns: state.staticRenderFns
  }
}

function genElement (el, state) {
  if (el.parent) {
    el.pre = el.pre || el.parent.pre;
  }

  if (el.staticRoot && !el.staticProcessed) {
    return genStatic(el, state)
  } else if (el.once && !el.onceProcessed) {
    return genOnce(el, state)
  } else if (el.for && !el.forProcessed) {
    return genFor(el, state)
  } else if (el.if && !el.ifProcessed) {
    return genIf(el, state)
  } else if (el.tag === 'template' && !el.slotTarget && !state.pre) {
    return genChildren(el, state) || 'void 0'
  } else if (el.tag === 'slot') {
    return genSlot(el, state)
  } else {
    // component or element
    var code;
    if (el.component) {
      code = genComponent(el.component, el, state);
    } else {
      var data;
      if (!el.plain || (el.pre && state.maybeComponent(el))) {
        data = genData$2(el, state);
      }

      var children = el.inlineTemplate ? null : genChildren(el, state, true);
      code = "_c('" + (el.tag) + "'" + (data ? ("," + data) : '') + (children ? ("," + children) : '') + ")";
    }
    // module transforms
    for (var i = 0; i < state.transforms.length; i++) {
      code = state.transforms[i](el, code);
    }
    return code
  }
}

// hoist static sub-trees out
function genStatic (el, state) {
  el.staticProcessed = true;
  // Some elements (templates) need to behave differently inside of a v-pre
  // node.  All pre nodes are static roots, so we can use this as a location to
  // wrap a state change and reset it upon exiting the pre node.
  var originalPreState = state.pre;
  if (el.pre) {
    state.pre = el.pre;
  }
  state.staticRenderFns.push(("with(this){return " + (genElement(el, state)) + "}"));
  state.pre = originalPreState;
  return ("_m(" + (state.staticRenderFns.length - 1) + (el.staticInFor ? ',true' : '') + ")")
}

// v-once
function genOnce (el, state) {
  el.onceProcessed = true;
  if (el.if && !el.ifProcessed) {
    return genIf(el, state)
  } else if (el.staticInFor) {
    var key = '';
    var parent = el.parent;
    while (parent) {
      if (parent.for) {
        key = parent.key;
        break
      }
      parent = parent.parent;
    }
    if (!key) {
      state.warn(
        "v-once can only be used inside v-for that is keyed. ",
        el.rawAttrsMap['v-once']
      );
      return genElement(el, state)
    }
    return ("_o(" + (genElement(el, state)) + "," + (state.onceId++) + "," + key + ")")
  } else {
    return genStatic(el, state)
  }
}

function genIf (
  el,
  state,
  altGen,
  altEmpty
) {
  el.ifProcessed = true; // avoid recursion
  return genIfConditions(el.ifConditions.slice(), state, altGen, altEmpty)
}

function genIfConditions (
  conditions,
  state,
  altGen,
  altEmpty
) {
  if (!conditions.length) {
    return altEmpty || '_e()'
  }

  var condition = conditions.shift();
  if (condition.exp) {
    return ("(" + (condition.exp) + ")?" + (genTernaryExp(condition.block)) + ":" + (genIfConditions(conditions, state, altGen, altEmpty)))
  } else {
    return ("" + (genTernaryExp(condition.block)))
  }

  // v-if with v-once should generate code like (a)?_m(0):_m(1)
  function genTernaryExp (el) {
    return altGen
      ? altGen(el, state)
      : el.once
        ? genOnce(el, state)
        : genElement(el, state)
  }
}

function genFor (
  el,
  state,
  altGen,
  altHelper
) {
  var exp = el.for;
  var alias = el.alias;
  var iterator1 = el.iterator1 ? ("," + (el.iterator1)) : '';
  var iterator2 = el.iterator2 ? ("," + (el.iterator2)) : '';

  if (state.maybeComponent(el) &&
    el.tag !== 'slot' &&
    el.tag !== 'template' &&
    !el.key
  ) {
    state.warn(
      "<" + (el.tag) + " v-for=\"" + alias + " in " + exp + "\">: component lists rendered with " +
      "v-for should have explicit keys. " +
      "See https://vuejs.org/guide/list.html#key for more info.",
      el.rawAttrsMap['v-for'],
      true /* tip */
    );
  }

  el.forProcessed = true; // avoid recursion
  return (altHelper || '_l') + "((" + exp + ")," +
    "function(" + alias + iterator1 + iterator2 + "){" +
      "return " + ((altGen || genElement)(el, state)) +
    '})'
}

function genData$2 (el, state) {
  var data = '{';

  // directives first.
  // directives may mutate the el's other properties before they are generated.
  var dirs = genDirectives(el, state);
  if (dirs) { data += dirs + ','; }

  // key
  if (el.key) {
    data += "key:" + (el.key) + ",";
  }
  // ref
  if (el.ref) {
    data += "ref:" + (el.ref) + ",";
  }
  if (el.refInFor) {
    data += "refInFor:true,";
  }
  // pre
  if (el.pre) {
    data += "pre:true,";
  }
  // record original tag name for components using "is" attribute
  if (el.component) {
    data += "tag:\"" + (el.tag) + "\",";
  }
  // module data generation functions
  for (var i = 0; i < state.dataGenFns.length; i++) {
    data += state.dataGenFns[i](el);
  }
  // attributes
  if (el.attrs) {
    data += "attrs:" + (genProps(el.attrs)) + ",";
  }
  // DOM props
  if (el.props) {
    data += "domProps:" + (genProps(el.props)) + ",";
  }
  // event handlers
  if (el.events) {
    data += (genHandlers(el.events, false)) + ",";
  }
  if (el.nativeEvents) {
    data += (genHandlers(el.nativeEvents, true)) + ",";
  }
  // slot target
  // only for non-scoped slots
  if (el.slotTarget && !el.slotScope) {
    data += "slot:" + (el.slotTarget) + ",";
  }
  // scoped slots
  if (el.scopedSlots) {
    data += (genScopedSlots(el, el.scopedSlots, state)) + ",";
  }
  // component v-model
  if (el.model) {
    data += "model:{value:" + (el.model.value) + ",callback:" + (el.model.callback) + ",expression:" + (el.model.expression) + "},";
  }
  // inline-template
  if (el.inlineTemplate) {
    var inlineTemplate = genInlineTemplate(el, state);
    if (inlineTemplate) {
      data += inlineTemplate + ",";
    }
  }
  data = data.replace(/,$/, '') + '}';
  // v-bind dynamic argument wrap
  // v-bind with dynamic arguments must be applied using the same v-bind object
  // merge helper so that class/style/mustUseProp attrs are handled correctly.
  if (el.dynamicAttrs) {
    data = "_b(" + data + ",\"" + (el.tag) + "\"," + (genProps(el.dynamicAttrs)) + ")";
  }
  // v-bind data wrap
  if (el.wrapData) {
    data = el.wrapData(data);
  }
  // v-on data wrap
  if (el.wrapListeners) {
    data = el.wrapListeners(data);
  }
  return data
}

function genDirectives (el, state) {
  var dirs = el.directives;
  if (!dirs) { return }
  var res = 'directives:[';
  var hasRuntime = false;
  var i, l, dir, needRuntime;
  for (i = 0, l = dirs.length; i < l; i++) {
    dir = dirs[i];
    needRuntime = true;
    var gen = state.directives[dir.name];
    if (gen) {
      // compile-time directive that manipulates AST.
      // returns true if it also needs a runtime counterpart.
      needRuntime = !!gen(el, dir, state.warn);
    }
    if (needRuntime) {
      hasRuntime = true;
      res += "{name:\"" + (dir.name) + "\",rawName:\"" + (dir.rawName) + "\"" + (dir.value ? (",value:(" + (dir.value) + "),expression:" + (JSON.stringify(dir.value))) : '') + (dir.arg ? (",arg:" + (dir.isDynamicArg ? dir.arg : ("\"" + (dir.arg) + "\""))) : '') + (dir.modifiers ? (",modifiers:" + (JSON.stringify(dir.modifiers))) : '') + "},";
    }
  }
  if (hasRuntime) {
    return res.slice(0, -1) + ']'
  }
}

function genInlineTemplate (el, state) {
  var ast = el.children[0];
  if (el.children.length !== 1 || ast.type !== 1) {
    state.warn(
      'Inline-template components must have exactly one child element.',
      { start: el.start }
    );
  }
  if (ast && ast.type === 1) {
    var inlineRenderFns = generate(ast, state.options);
    return ("inlineTemplate:{render:function(){" + (inlineRenderFns.render) + "},staticRenderFns:[" + (inlineRenderFns.staticRenderFns.map(function (code) { return ("function(){" + code + "}"); }).join(',')) + "]}")
  }
}

function genScopedSlots (
  el,
  slots,
  state
) {
  // by default scoped slots are considered "stable", this allows child
  // components with only scoped slots to skip forced updates from parent.
  // but in some cases we have to bail-out of this optimization
  // for example if the slot contains dynamic names, has v-if or v-for on them...
  var needsForceUpdate = el.for || Object.keys(slots).some(function (key) {
    var slot = slots[key];
    return (
      slot.slotTargetDynamic ||
      slot.if ||
      slot.for ||
      containsSlotChild(slot) // is passing down slot from parent which may be dynamic
    )
  });

  // #9534: if a component with scoped slots is inside a conditional branch,
  // it's possible for the same component to be reused but with different
  // compiled slot content. To avoid that, we generate a unique key based on
  // the generated code of all the slot contents.
  var needsKey = !!el.if;

  // OR when it is inside another scoped slot or v-for (the reactivity may be
  // disconnected due to the intermediate scope variable)
  // #9438, #9506
  // TODO: this can be further optimized by properly analyzing in-scope bindings
  // and skip force updating ones that do not actually use scope variables.
  if (!needsForceUpdate) {
    var parent = el.parent;
    while (parent) {
      if (
        (parent.slotScope && parent.slotScope !== emptySlotScopeToken) ||
        parent.for
      ) {
        needsForceUpdate = true;
        break
      }
      if (parent.if) {
        needsKey = true;
      }
      parent = parent.parent;
    }
  }

  var generatedSlots = Object.keys(slots)
    .map(function (key) { return genScopedSlot(slots[key], state); })
    .join(',');

  return ("scopedSlots:_u([" + generatedSlots + "]" + (needsForceUpdate ? ",null,true" : "") + (!needsForceUpdate && needsKey ? (",null,false," + (hash(generatedSlots))) : "") + ")")
}

function hash(str) {
  var hash = 5381;
  var i = str.length;
  while(i) {
    hash = (hash * 33) ^ str.charCodeAt(--i);
  }
  return hash >>> 0
}

function containsSlotChild (el) {
  if (el.type === 1) {
    if (el.tag === 'slot') {
      return true
    }
    return el.children.some(containsSlotChild)
  }
  return false
}

function genScopedSlot (
  el,
  state
) {
  var isLegacySyntax = el.attrsMap['slot-scope'];
  if (el.if && !el.ifProcessed && !isLegacySyntax) {
    return genIf(el, state, genScopedSlot, "null")
  }
  if (el.for && !el.forProcessed) {
    return genFor(el, state, genScopedSlot)
  }
  var slotScope = el.slotScope === emptySlotScopeToken
    ? ""
    : String(el.slotScope);
  var fn = "function(" + slotScope + "){" +
    "return " + (el.tag === 'template'
      ? el.if && isLegacySyntax
        ? ("(" + (el.if) + ")?" + (genChildren(el, state) || 'undefined') + ":undefined")
        : genChildren(el, state) || 'undefined'
      : genElement(el, state)) + "}";
  // reverse proxy v-slot without scope on this.$slots
  var reverseProxy = slotScope ? "" : ",proxy:true";
  return ("{key:" + (el.slotTarget || "\"default\"") + ",fn:" + fn + reverseProxy + "}")
}

function genChildren (
  el,
  state,
  checkSkip,
  altGenElement,
  altGenNode
) {
  var children = el.children;
  if (children.length) {
    var el$1 = children[0];
    // optimize single v-for
    if (children.length === 1 &&
      el$1.for &&
      el$1.tag !== 'template' &&
      el$1.tag !== 'slot'
    ) {
      var normalizationType = checkSkip
        ? state.maybeComponent(el$1) ? ",1" : ",0"
        : "";
      return ("" + ((altGenElement || genElement)(el$1, state)) + normalizationType)
    }
    var normalizationType$1 = checkSkip
      ? getNormalizationType(children, state.maybeComponent)
      : 0;
    var gen = altGenNode || genNode;
    return ("[" + (children.map(function (c) { return gen(c, state); }).join(',')) + "]" + (normalizationType$1 ? ("," + normalizationType$1) : ''))
  }
}

// determine the normalization needed for the children array.
// 0: no normalization needed
// 1: simple normalization needed (possible 1-level deep nested array)
// 2: full normalization needed
function getNormalizationType (
  children,
  maybeComponent
) {
  var res = 0;
  for (var i = 0; i < children.length; i++) {
    var el = children[i];
    if (el.type !== 1) {
      continue
    }
    if (needsNormalization(el) ||
        (el.ifConditions && el.ifConditions.some(function (c) { return needsNormalization(c.block); }))) {
      res = 2;
      break
    }
    if (maybeComponent(el) ||
        (el.ifConditions && el.ifConditions.some(function (c) { return maybeComponent(c.block); }))) {
      res = 1;
    }
  }
  return res
}

function needsNormalization (el) {
  return el.for !== undefined || el.tag === 'template' || el.tag === 'slot'
}

function genNode (node, state) {
  if (node.type === 1) {
    return genElement(node, state)
  } else if (node.type === 3 && node.isComment) {
    return genComment(node)
  } else {
    return genText(node)
  }
}

function genText (text) {
  return ("_v(" + (text.type === 2
    ? text.expression // no need for () because already wrapped in _s()
    : transformSpecialNewlines(JSON.stringify(text.text))) + ")")
}

function genComment (comment) {
  return ("_e(" + (JSON.stringify(comment.text)) + ")")
}

function genSlot (el, state) {
  var slotName = el.slotName || '"default"';
  var children = genChildren(el, state);
  var res = "_t(" + slotName + (children ? ("," + children) : '');
  var attrs = el.attrs || el.dynamicAttrs
    ? genProps((el.attrs || []).concat(el.dynamicAttrs || []).map(function (attr) { return ({
        // slot props are camelized
        name: camelize(attr.name),
        value: attr.value,
        dynamic: attr.dynamic
      }); }))
    : null;
  var bind$$1 = el.attrsMap['v-bind'];
  if ((attrs || bind$$1) && !children) {
    res += ",null";
  }
  if (attrs) {
    res += "," + attrs;
  }
  if (bind$$1) {
    res += (attrs ? '' : ',null') + "," + bind$$1;
  }
  return res + ')'
}

// componentName is el.component, take it as argument to shun flow's pessimistic refinement
function genComponent (
  componentName,
  el,
  state
) {
  var children = el.inlineTemplate ? null : genChildren(el, state, true);
  return ("_c(" + componentName + "," + (genData$2(el, state)) + (children ? ("," + children) : '') + ")")
}

function genProps (props) {
  var staticProps = "";
  var dynamicProps = "";
  for (var i = 0; i < props.length; i++) {
    var prop = props[i];
    var value = transformSpecialNewlines(prop.value);
    if (prop.dynamic) {
      dynamicProps += (prop.name) + "," + value + ",";
    } else {
      staticProps += "\"" + (prop.name) + "\":" + value + ",";
    }
  }
  staticProps = "{" + (staticProps.slice(0, -1)) + "}";
  if (dynamicProps) {
    return ("_d(" + staticProps + ",[" + (dynamicProps.slice(0, -1)) + "])")
  } else {
    return staticProps
  }
}

// #3895, #4268
function transformSpecialNewlines (text) {
  return text
    .replace(/\u2028/g, '\\u2028')
    .replace(/\u2029/g, '\\u2029')
}

/*  */



// these keywords should not appear inside expressions, but operators like
// typeof, instanceof and in are allowed
var prohibitedKeywordRE = new RegExp('\\b' + (
  'do,if,for,let,new,try,var,case,else,with,await,break,catch,class,const,' +
  'super,throw,while,yield,delete,export,import,return,switch,default,' +
  'extends,finally,continue,debugger,function,arguments'
).split(',').join('\\b|\\b') + '\\b');

// these unary operators should not be used as property/method names
var unaryOperatorsRE = new RegExp('\\b' + (
  'delete,typeof,void'
).split(',').join('\\s*\\([^\\)]*\\)|\\b') + '\\s*\\([^\\)]*\\)');

// strip strings in expressions
var stripStringRE = /'(?:[^'\\]|\\.)*'|"(?:[^"\\]|\\.)*"|`(?:[^`\\]|\\.)*\$\{|\}(?:[^`\\]|\\.)*`|`(?:[^`\\]|\\.)*`/g;

// detect problematic expressions in a template
function detectErrors (ast, warn) {
  if (ast) {
    checkNode(ast, warn);
  }
}

function checkNode (node, warn) {
  if (node.type === 1) {
    for (var name in node.attrsMap) {
      if (dirRE.test(name)) {
        var value = node.attrsMap[name];
        if (value) {
          var range = node.rawAttrsMap[name];
          if (name === 'v-for') {
            checkFor(node, ("v-for=\"" + value + "\""), warn, range);
          } else if (name === 'v-slot' || name[0] === '#') {
            checkFunctionParameterExpression(value, (name + "=\"" + value + "\""), warn, range);
          } else if (onRE.test(name)) {
            checkEvent(value, (name + "=\"" + value + "\""), warn, range);
          } else {
            checkExpression(value, (name + "=\"" + value + "\""), warn, range);
          }
        }
      }
    }
    if (node.children) {
      for (var i = 0; i < node.children.length; i++) {
        checkNode(node.children[i], warn);
      }
    }
  } else if (node.type === 2) {
    checkExpression(node.expression, node.text, warn, node);
  }
}

function checkEvent (exp, text, warn, range) {
  var stripped = exp.replace(stripStringRE, '');
  var keywordMatch = stripped.match(unaryOperatorsRE);
  if (keywordMatch && stripped.charAt(keywordMatch.index - 1) !== '$') {
    warn(
      "avoid using JavaScript unary operator as property name: " +
      "\"" + (keywordMatch[0]) + "\" in expression " + (text.trim()),
      range
    );
  }
  checkExpression(exp, text, warn, range);
}

function checkFor (node, text, warn, range) {
  checkExpression(node.for || '', text, warn, range);
  checkIdentifier(node.alias, 'v-for alias', text, warn, range);
  checkIdentifier(node.iterator1, 'v-for iterator', text, warn, range);
  checkIdentifier(node.iterator2, 'v-for iterator', text, warn, range);
}

function checkIdentifier (
  ident,
  type,
  text,
  warn,
  range
) {
  if (typeof ident === 'string') {
    try {
      new Function(("var " + ident + "=_"));
    } catch (e) {
      warn(("invalid " + type + " \"" + ident + "\" in expression: " + (text.trim())), range);
    }
  }
}

function checkExpression (exp, text, warn, range) {
  try {
    new Function(("return " + exp));
  } catch (e) {
    var keywordMatch = exp.replace(stripStringRE, '').match(prohibitedKeywordRE);
    if (keywordMatch) {
      warn(
        "avoid using JavaScript keyword as property name: " +
        "\"" + (keywordMatch[0]) + "\"\n  Raw expression: " + (text.trim()),
        range
      );
    } else {
      warn(
        "invalid expression: " + (e.message) + " in\n\n" +
        "    " + exp + "\n\n" +
        "  Raw expression: " + (text.trim()) + "\n",
        range
      );
    }
  }
}

function checkFunctionParameterExpression (exp, text, warn, range) {
  try {
    new Function(exp, '');
  } catch (e) {
    warn(
      "invalid function parameter expression: " + (e.message) + " in\n\n" +
      "    " + exp + "\n\n" +
      "  Raw expression: " + (text.trim()) + "\n",
      range
    );
  }
}

/*  */

var range = 2;

function generateCodeFrame (
  source,
  start,
  end
) {
  if ( start === void 0 ) start = 0;
  if ( end === void 0 ) end = source.length;

  var lines = source.split(/\r?\n/);
  var count = 0;
  var res = [];
  for (var i = 0; i < lines.length; i++) {
    count += lines[i].length + 1;
    if (count >= start) {
      for (var j = i - range; j <= i + range || end > count; j++) {
        if (j < 0 || j >= lines.length) { continue }
        res.push(("" + (j + 1) + (repeat$1(" ", 3 - String(j + 1).length)) + "|  " + (lines[j])));
        var lineLength = lines[j].length;
        if (j === i) {
          // push underline
          var pad = start - (count - lineLength) + 1;
          var length = end > count ? lineLength - pad : end - start;
          res.push("   |  " + repeat$1(" ", pad) + repeat$1("^", length));
        } else if (j > i) {
          if (end > count) {
            var length$1 = Math.min(end - count, lineLength);
            res.push("   |  " + repeat$1("^", length$1));
          }
          count += lineLength + 1;
        }
      }
      break
    }
  }
  return res.join('\n')
}

function repeat$1 (str, n) {
  var result = '';
  if (n > 0) {
    while (true) { // eslint-disable-line
      if (n & 1) { result += str; }
      n >>>= 1;
      if (n <= 0) { break }
      str += str;
    }
  }
  return result
}

/*  */



function createFunction (code, errors) {
  try {
    return new Function(code)
  } catch (err) {
    errors.push({ err: err, code: code });
    return noop
  }
}

function createCompileToFunctionFn (compile) {
  var cache = Object.create(null);

  return function compileToFunctions (
    template,
    options,
    vm
  ) {
    options = extend({}, options);
    var warn$$1 = options.warn || warn;
    delete options.warn;

    /* istanbul ignore if */
    {
      // detect possible CSP restriction
      try {
        new Function('return 1');
      } catch (e) {
        if (e.toString().match(/unsafe-eval|CSP/)) {
          warn$$1(
            'It seems you are using the standalone build of Vue.js in an ' +
            'environment with Content Security Policy that prohibits unsafe-eval. ' +
            'The template compiler cannot work in this environment. Consider ' +
            'relaxing the policy to allow unsafe-eval or pre-compiling your ' +
            'templates into render functions.'
          );
        }
      }
    }

    // check cache
    var key = options.delimiters
      ? String(options.delimiters) + template
      : template;
    if (cache[key]) {
      return cache[key]
    }

    // compile
    var compiled = compile(template, options);

    // check compilation errors/tips
    {
      if (compiled.errors && compiled.errors.length) {
        if (options.outputSourceRange) {
          compiled.errors.forEach(function (e) {
            warn$$1(
              "Error compiling template:\n\n" + (e.msg) + "\n\n" +
              generateCodeFrame(template, e.start, e.end),
              vm
            );
          });
        } else {
          warn$$1(
            "Error compiling template:\n\n" + template + "\n\n" +
            compiled.errors.map(function (e) { return ("- " + e); }).join('\n') + '\n',
            vm
          );
        }
      }
      if (compiled.tips && compiled.tips.length) {
        if (options.outputSourceRange) {
          compiled.tips.forEach(function (e) { return tip(e.msg, vm); });
        } else {
          compiled.tips.forEach(function (msg) { return tip(msg, vm); });
        }
      }
    }

    // turn code into functions
    var res = {};
    var fnGenErrors = [];
    res.render = createFunction(compiled.render, fnGenErrors);
    res.staticRenderFns = compiled.staticRenderFns.map(function (code) {
      return createFunction(code, fnGenErrors)
    });

    // check function generation errors.
    // this should only happen if there is a bug in the compiler itself.
    // mostly for codegen development use
    /* istanbul ignore if */
    {
      if ((!compiled.errors || !compiled.errors.length) && fnGenErrors.length) {
        warn$$1(
          "Failed to generate render function:\n\n" +
          fnGenErrors.map(function (ref) {
            var err = ref.err;
            var code = ref.code;

            return ((err.toString()) + " in\n\n" + code + "\n");
        }).join('\n'),
          vm
        );
      }
    }

    return (cache[key] = res)
  }
}

/*  */

function createCompilerCreator (baseCompile) {
  return function createCompiler (baseOptions) {
    function compile (
      template,
      options
    ) {
      var finalOptions = Object.create(baseOptions);
      var errors = [];
      var tips = [];

      var warn = function (msg, range, tip) {
        (tip ? tips : errors).push(msg);
      };

      if (options) {
        if (options.outputSourceRange) {
          // $flow-disable-line
          var leadingSpaceLength = template.match(/^\s*/)[0].length;

          warn = function (msg, range, tip) {
            var data = { msg: msg };
            if (range) {
              if (range.start != null) {
                data.start = range.start + leadingSpaceLength;
              }
              if (range.end != null) {
                data.end = range.end + leadingSpaceLength;
              }
            }
            (tip ? tips : errors).push(data);
          };
        }
        // merge custom modules
        if (options.modules) {
          finalOptions.modules =
            (baseOptions.modules || []).concat(options.modules);
        }
        // merge custom directives
        if (options.directives) {
          finalOptions.directives = extend(
            Object.create(baseOptions.directives || null),
            options.directives
          );
        }
        // copy other options
        for (var key in options) {
          if (key !== 'modules' && key !== 'directives') {
            finalOptions[key] = options[key];
          }
        }
      }

      finalOptions.warn = warn;

      var compiled = baseCompile(template.trim(), finalOptions);
      {
        detectErrors(compiled.ast, warn);
      }
      compiled.errors = errors;
      compiled.tips = tips;
      return compiled
    }

    return {
      compile: compile,
      compileToFunctions: createCompileToFunctionFn(compile)
    }
  }
}

/*  */

// `createCompilerCreator` allows creating compilers that use alternative
// parser/optimizer/codegen, e.g the SSR optimizing compiler.
// Here we just export a default compiler using the default parts.
var createCompiler = createCompilerCreator(function baseCompile (
  template,
  options
) {
  var ast = parse(template.trim(), options);
  if (options.optimize !== false) {
    optimize(ast, options);
  }
  var code = generate(ast, options);
  return {
    ast: ast,
    render: code.render,
    staticRenderFns: code.staticRenderFns
  }
});

/*  */

var ref$1 = createCompiler(baseOptions);
var compile = ref$1.compile;
var compileToFunctions = ref$1.compileToFunctions;

/*  */

// check whether current browser encodes a char inside attribute values
var div;
function getShouldDecode (href) {
  div = div || document.createElement('div');
  div.innerHTML = href ? "<a href=\"\n\"/>" : "<div a=\"\n\"/>";
  return div.innerHTML.indexOf('&#10;') > 0
}

// #3663: IE encodes newlines inside attribute values while other browsers don't
var shouldDecodeNewlines = inBrowser ? getShouldDecode(false) : false;
// #6828: chrome encodes content in a[href]
var shouldDecodeNewlinesForHref = inBrowser ? getShouldDecode(true) : false;

/*  */

var idToTemplate = cached(function (id) {
  var el = query(id);
  return el && el.innerHTML
});

var mount = Vue.prototype.$mount;
Vue.prototype.$mount = function (
  el,
  hydrating
) {
  el = el && query(el);

  /* istanbul ignore if */
  if (el === document.body || el === document.documentElement) {
    warn(
      "Do not mount Vue to <html> or <body> - mount to normal elements instead."
    );
    return this
  }

  var options = this.$options;
  // resolve template/el and convert to render function
  if (!options.render) {
    var template = options.template;
    if (template) {
      if (typeof template === 'string') {
        if (template.charAt(0) === '#') {
          template = idToTemplate(template);
          /* istanbul ignore if */
          if (!template) {
            warn(
              ("Template element not found or is empty: " + (options.template)),
              this
            );
          }
        }
      } else if (template.nodeType) {
        template = template.innerHTML;
      } else {
        {
          warn('invalid template option:' + template, this);
        }
        return this
      }
    } else if (el) {
      template = getOuterHTML(el);
    }
    if (template) {
      /* istanbul ignore if */
      if (config.performance && mark) {
        mark('compile');
      }

      var ref = compileToFunctions(template, {
        outputSourceRange: "development" !== 'production',
        shouldDecodeNewlines: shouldDecodeNewlines,
        shouldDecodeNewlinesForHref: shouldDecodeNewlinesForHref,
        delimiters: options.delimiters,
        comments: options.comments
      }, this);
      var render = ref.render;
      var staticRenderFns = ref.staticRenderFns;
      options.render = render;
      options.staticRenderFns = staticRenderFns;

      /* istanbul ignore if */
      if (config.performance && mark) {
        mark('compile end');
        measure(("vue " + (this._name) + " compile"), 'compile', 'compile end');
      }
    }
  }
  return mount.call(this, el, hydrating)
};

/**
 * Get outerHTML of elements, taking care
 * of SVG elements in IE as well.
 */
function getOuterHTML (el) {
  if (el.outerHTML) {
    return el.outerHTML
  } else {
    var container = document.createElement('div');
    container.appendChild(el.cloneNode(true));
    return container.innerHTML
  }
}

Vue.compile = compileToFunctions;

module.exports = Vue;

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js"), __webpack_require__(/*! ./../../timers-browserify/main.js */ "./node_modules/timers-browserify/main.js").setImmediate))

/***/ }),

/***/ "./node_modules/vue/dist/vue.common.js":
/*!*********************************************!*\
  !*** ./node_modules/vue/dist/vue.common.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

if (false) {} else {
  module.exports = __webpack_require__(/*! ./vue.common.dev.js */ "./node_modules/vue/dist/vue.common.dev.js")
}


/***/ }),

/***/ "./node_modules/webpack/buildin/amd-options.js":
/*!****************************************!*\
  !*** (webpack)/buildin/amd-options.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* WEBPACK VAR INJECTION */(function(__webpack_amd_options__) {/* globals __webpack_amd_options__ */
module.exports = __webpack_amd_options__;

/* WEBPACK VAR INJECTION */}.call(this, {}))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/js/sites/side_menu/Menu.vue":
/*!***********************************************!*\
  !*** ./resources/js/sites/side_menu/Menu.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Menu_vue_vue_type_template_id_10032bb0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Menu.vue?vue&type=template&id=10032bb0& */ "./resources/js/sites/side_menu/Menu.vue?vue&type=template&id=10032bb0&");
/* harmony import */ var _Menu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Menu.vue?vue&type=script&lang=js& */ "./resources/js/sites/side_menu/Menu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Menu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Menu.vue?vue&type=style&index=0&lang=css& */ "./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Menu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Menu_vue_vue_type_template_id_10032bb0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Menu_vue_vue_type_template_id_10032bb0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/sites/side_menu/Menu.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/sites/side_menu/Menu.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/sites/side_menu/Menu.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Menu.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css&":
/*!********************************************************************************!*\
  !*** ./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css& ***!
  \********************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--9-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--9-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./Menu.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/sites/side_menu/Menu.vue?vue&type=template&id=10032bb0&":
/*!******************************************************************************!*\
  !*** ./resources/js/sites/side_menu/Menu.vue?vue&type=template&id=10032bb0& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_template_id_10032bb0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Menu.vue?vue&type=template&id=10032bb0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/Menu.vue?vue&type=template&id=10032bb0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_template_id_10032bb0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Menu_vue_vue_type_template_id_10032bb0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/sites/side_menu/SideMenu.vue":
/*!***************************************************!*\
  !*** ./resources/js/sites/side_menu/SideMenu.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SideMenu_vue_vue_type_template_id_5985241f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SideMenu.vue?vue&type=template&id=5985241f& */ "./resources/js/sites/side_menu/SideMenu.vue?vue&type=template&id=5985241f&");
/* harmony import */ var _SideMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SideMenu.vue?vue&type=script&lang=js& */ "./resources/js/sites/side_menu/SideMenu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _SideMenu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SideMenu.vue?vue&type=style&index=0&lang=css& */ "./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _SideMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SideMenu_vue_vue_type_template_id_5985241f___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SideMenu_vue_vue_type_template_id_5985241f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/sites/side_menu/SideMenu.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/sites/side_menu/SideMenu.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/sites/side_menu/SideMenu.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./SideMenu.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css&":
/*!************************************************************************************!*\
  !*** ./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css& ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--9-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--9-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./SideMenu.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_9_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/sites/side_menu/SideMenu.vue?vue&type=template&id=5985241f&":
/*!**********************************************************************************!*\
  !*** ./resources/js/sites/side_menu/SideMenu.vue?vue&type=template&id=5985241f& ***!
  \**********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_template_id_5985241f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./SideMenu.vue?vue&type=template&id=5985241f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/sites/side_menu/SideMenu.vue?vue&type=template&id=5985241f&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_template_id_5985241f___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SideMenu_vue_vue_type_template_id_5985241f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/sites/side_menu/index.js":
/*!***********************************************!*\
  !*** ./resources/js/sites/side_menu/index.js ***!
  \***********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _SideMenu__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SideMenu */ "./resources/js/sites/side_menu/SideMenu.vue");


vue__WEBPACK_IMPORTED_MODULE_0___default.a.config.productionTip = false;
vue__WEBPACK_IMPORTED_MODULE_0___default.a.config.silent = true;
new vue__WEBPACK_IMPORTED_MODULE_0___default.a({
  el: "#sideMenuComponent",
  components: {
    SideMenu: _SideMenu__WEBPACK_IMPORTED_MODULE_1__["default"]
  }
});

/***/ }),

/***/ "./resources/sass/custom_layout.scss":
/*!*******************************************!*\
  !*** ./resources/sass/custom_layout.scss ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/dashboards/warehouse/order.scss":
/*!********************************************************!*\
  !*** ./resources/sass/dashboards/warehouse/order.scss ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/warehouse/order.scss":
/*!*********************************************!*\
  !*** ./resources/sass/warehouse/order.scss ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!********************************************************************************************************************************************************************************************************!*\
  !*** multi ./resources/js/sites/side_menu/index.js ./resources/sass/dashboards/warehouse/order.scss ./resources/sass/custom_layout.scss ./resources/sass/warehouse/order.scss ./resources/css/app.css ***!
  \********************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /mnt/c/Users/mahendra_permana.SPN2K3/Documents/server/intranet-fe-ayana_wsl/resources/js/sites/side_menu/index.js */"./resources/js/sites/side_menu/index.js");
__webpack_require__(/*! /mnt/c/Users/mahendra_permana.SPN2K3/Documents/server/intranet-fe-ayana_wsl/resources/sass/dashboards/warehouse/order.scss */"./resources/sass/dashboards/warehouse/order.scss");
__webpack_require__(/*! /mnt/c/Users/mahendra_permana.SPN2K3/Documents/server/intranet-fe-ayana_wsl/resources/sass/custom_layout.scss */"./resources/sass/custom_layout.scss");
__webpack_require__(/*! /mnt/c/Users/mahendra_permana.SPN2K3/Documents/server/intranet-fe-ayana_wsl/resources/sass/warehouse/order.scss */"./resources/sass/warehouse/order.scss");
module.exports = __webpack_require__(/*! /mnt/c/Users/mahendra_permana.SPN2K3/Documents/server/intranet-fe-ayana_wsl/resources/css/app.css */"./resources/css/app.css");


/***/ })

/******/ });