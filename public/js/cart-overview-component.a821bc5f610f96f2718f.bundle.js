(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["cart-overview-component"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartOverviewComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/cart/CartOverviewComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function($) {function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    _typeof = function _typeof(obj) {
      return typeof obj;
    };
  } else {
    _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
} //
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
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      loaded: false,
      taxRate: 0,
      discount: 0,
      currentZipcode: '',
      shipping: [],
      zipcodes: [],
      cartItems: []
    };
  },
  props: {
    freeShipping: Boolean,
    leadCaptured: Boolean,
    orderId: Number,
    contactInfo: Object
  },
  watch: {
    'currentZipcode': function currentZipcode(val, oldVal) {
      var self = this;
      $.ajax({
        url: '/tax/' + val,
        type: 'PUT',
        dataType: 'json',
        data: {
          zipcode: val
        }
      }).done(function (response) {
        self.taxRate = response;
      }).fail(function () {
        self.taxRate = 0;
      });
    }
  },
  mounted: function mounted() {
    var _this = this;

    this.refresh();
    this.$root.$on('cartItemUpdated', function (cartItem) {
      _this.cartItems = _this.cartItems.map(function (item) {
        if (item.id === cartItem.id) {
          return cartItem;
        }

        return item;
      });
    });
    this.$root.$on('cartItemDeleted', function (cartItem) {
      _this.cartItems = _this.cartItems.filter(function (item) {
        return item.id !== cartItem.id;
      });
    });
    this.$root.$on('shippingUpdated', function (shipping) {
      if (_this.freeShipping === true) {
        _this.shipping = 0;
      } else {
        _this.shipping = shipping;
      }
    });
    this.$root.$on('cartTaxUpdated', function (zipcode) {
      _this.zipcodes.push(zipcode);

      _this.currentZipcode = _this.zipcode;
    });
    this.$root.$on('couponCodeAdded', function (discount) {
      _this.refresh();
    });
  },
  methods: {
    openCart: function openCart() {
      this.$root.$emit('openCart');
    },
    refresh: function refresh() {
      var self = this;
      $.ajax({
        url: '/cart',
        type: 'GET',
        dataType: 'json'
      }).done(function (response) {
        self.loaded = true;
        self.cartItems = response.cartItems;
        self.taxRate = response.taxRate;
        self.discount = response.discount;
      });
    }
  },
  computed: {
    totalItems: function totalItems() {
      return this.cartItems.filter(function (item) {
        return item.deleted === false;
      }).length;
    },
    availabeCartItems: function availabeCartItems() {
      if (this.cartItems.length === 0) {
        return [];
      }

      return this.cartItems.filter(function (item) {
        return item.deleted === false;
      });
    },
    shippingPrice: function shippingPrice() {
      var cost = 0;

      if (_typeof(this.shipping) === 'object') {
        cost = parseFloat(this.shipping.cost);
        cost = isNaN(cost) ? 0 : cost;
      }

      var sum = 0;

      if (cost === 0 && _typeof(this.shipping) === 'object') {
        this.shipping.map(function (e) {
          sum += eval(e.cost);
        });
      }

      return sum;
    },
    taxValue: function taxValue() {
      return this.cartSubtotal * this.taxRate / 100;
    },
    zipcode: function zipcode() {
      if (this.zipcodes.length === 0) {
        return false;
      }

      var shippingZipcode = this.zipcodes.filter(function (zipcode) {
        return zipcode.addressType == 'shipping';
      });

      if (shippingZipcode.length) {
        return shippingZipcode[shippingZipcode.length - 1].zipcode;
      }

      return this.zipcodes[this.zipcodes.length - 1].zipcode;
    },
    cartSubtotal: function cartSubtotal() {
      var subtotal = 0;
      this.cartItems.map(function (cartItem) {
        subtotal += cartItem.bulkPrice * cartItem.quantity;
        return cartItem;
      });
      return subtotal;
    },
    cartTotal: function cartTotal() {
      return this.cartSubtotal + this.shippingPrice + this.taxValue - this.discount;
    }
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartOverviewComponent.vue?vue&type=template&id=76671e80&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/cart/CartOverviewComponent.vue?vue&type=template&id=76671e80& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
  return _c("div", [
    _c("h1", { staticClass: "text-highlight h4 mb-4 text-uppercase" }, [
      _vm._v("Order Summary")
    ]),
    _vm._v(" "),
    _vm.totalItems > 0
      ? _c("div", [
          _c("h2", { staticClass: "h5 mb-2 font-weight-bold" }, [
            _vm._v("Items To Ship")
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "p-2 bg-white rounded-lg shadow-sm" }, [
            _c(
              "table",
              { staticClass: "table-sm table-hovered table-borderless mb-3" },
              [
                _c(
                  "tbody",
                  _vm._l(_vm.availabeCartItems, function(cartItem) {
                    return _c(
                      "tr",
                      { staticClass: "border-bottom border-secondary" },
                      [
                        _c("td", { staticClass: "align-top" }, [
                          _c(
                            "code",
                            { staticClass: "text-dark font-weight-bold" },
                            [_vm._v("(" + _vm._s(cartItem.quantity) + ")")]
                          ),
                          _vm._v(" "),
                          _c(
                            "a",
                            {
                              staticClass: "text-dark",
                              attrs: {
                                href: _vm
                                  .route("product.show", cartItem.id)
                                  .url()
                              }
                            },
                            [
                              _vm._v(
                                "\n\t\t\t\t\t\t\t\t" +
                                  _vm._s(cartItem.name) +
                                  "\n\t\t\t\t\t\t\t"
                              )
                            ]
                          )
                        ]),
                        _vm._v(" "),
                        _c("td", { staticClass: "align-middle" }, [
                          _c(
                            "a",
                            {
                              staticClass: "text-default",
                              attrs: { href: "#" },
                              on: {
                                click: function($event) {
                                  $event.preventDefault()
                                  return _vm.openCart()
                                }
                              }
                            },
                            [_vm._v("Edit")]
                          )
                        ]),
                        _vm._v(" "),
                        _c("td", { staticClass: "align-middle text-right" }, [
                          cartItem.bulkPrice < cartItem.price
                            ? _c(
                                "small",
                                { staticClass: "text-decoration-line-through" },
                                [
                                  _vm._v(
                                    "\n\t\t\t\t\t\t\t\t" +
                                      _vm._s(
                                        _vm._f("currency")(
                                          cartItem.price * cartItem.quantity
                                        )
                                      ) +
                                      "\n\t\t\t\t\t\t\t"
                                  )
                                ]
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "code",
                            {
                              staticClass:
                                "text-dark font-weight-bold text-nowrap"
                            },
                            [
                              _vm._v(
                                _vm._s(
                                  _vm._f("currency")(
                                    cartItem.bulkPrice * cartItem.quantity
                                  )
                                )
                              )
                            ]
                          )
                        ])
                      ]
                    )
                  }),
                  0
                )
              ]
            ),
            _vm._v(" "),
            _c("div", [
              _c(
                "div",
                {
                  staticClass:
                    "d-flex flex-column align-items-center justify-content-between px-1"
                },
                [
                  _c(
                    "div",
                    {
                      staticClass:
                        "d-flex flex-row w-100 flex-fill align-items-center justify-content-between"
                    },
                    [
                      _c(
                        "div",
                        {
                          staticClass: "align-top py-0 font-weight-bold",
                          attrs: { colspan: "2" }
                        },
                        [_vm._v("Subtotal")]
                      ),
                      _vm._v(" "),
                      _c("div", [
                        _c(
                          "code",
                          { staticClass: "font-weight-bold py-0 text-dark" },
                          [_vm._v(_vm._s(_vm._f("currency")(_vm.cartSubtotal)))]
                        )
                      ])
                    ]
                  ),
                  _vm._v(" "),
                  _vm.discount > 0
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "d-flex flex-row w-100 flex-fill align-items-center justify-content-between"
                        },
                        [
                          _c(
                            "div",
                            {
                              staticClass: "align-top py-0 font-weight-bold",
                              attrs: { colspan: "2" }
                            },
                            [_vm._v("Discount")]
                          ),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "code",
                              {
                                staticClass: "font-weight-bold py-0 text-dark"
                              },
                              [_vm._v(_vm._s(_vm._f("currency")(_vm.discount)))]
                            )
                          ])
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.taxValue > 0
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "d-flex flex-row w-100 flex-fill align-items-center justify-content-between"
                        },
                        [
                          _c(
                            "div",
                            {
                              staticClass: "align-top py-0 font-weight-bold",
                              attrs: { colspan: "2" }
                            },
                            [_vm._v("Tax")]
                          ),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "code",
                              {
                                staticClass: "font-weight-bold py-0 text-dark"
                              },
                              [_vm._v(_vm._s(_vm._f("currency")(_vm.taxValue)))]
                            )
                          ])
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.leadCaptured
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "d-flex flex-row w-100 flex-fill align-items-center justify-content-between"
                        },
                        [
                          _c(
                            "div",
                            {
                              staticClass: "align-top py-0 font-weight-bold",
                              attrs: { colspan: "2" }
                            },
                            [_vm._v("Shipping")]
                          ),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "code",
                              {
                                staticClass: "font-weight-bold py-0 text-dark"
                              },
                              [
                                _vm._v(
                                  _vm._s(_vm._f("currency")(_vm.shippingPrice))
                                )
                              ]
                            )
                          ])
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass:
                        "d-flex flex-row w-100 flex-fill align-items-center justify-content-between"
                    },
                    [
                      _c(
                        "div",
                        {
                          staticClass: "align-top py-0 font-weight-bold",
                          attrs: { colspan: "2" }
                        },
                        [_vm._v("Total")]
                      ),
                      _vm._v(" "),
                      _c("div", [
                        _c(
                          "code",
                          { staticClass: "font-weight-bold py-0 text-dark" },
                          [_vm._v(_vm._s(_vm._f("currency")(_vm.cartTotal)))]
                        )
                      ])
                    ]
                  )
                ]
              )
            ])
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "mt-5" },
            [
              _c(
                "coupon-code-component",
                {
                  attrs: {
                    "order-id": _vm.orderId,
                    "contact-info": _vm.contactInfo
                  }
                },
                [
                  _c("h2", { staticClass: "h5 mb-2 font-weight-bold" }, [
                    _vm._v("Have A Promo Code")
                  ])
                ]
              )
            ],
            1
          )
        ])
      : _c("div", [
          _vm.loaded
            ? _c("div", { staticClass: "alert alert-danger mb-0" }, [
                _vm._v("\n\t\t\tYour cart is empty.\n\t\t")
              ])
            : _vm._e()
        ])
  ])
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

/***/ "./resources/js/components/cart/CartOverviewComponent.vue":
/*!****************************************************************!*\
  !*** ./resources/js/components/cart/CartOverviewComponent.vue ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CartOverviewComponent_vue_vue_type_template_id_76671e80___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CartOverviewComponent.vue?vue&type=template&id=76671e80& */ "./resources/js/components/cart/CartOverviewComponent.vue?vue&type=template&id=76671e80&");
/* harmony import */ var _CartOverviewComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CartOverviewComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/cart/CartOverviewComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CartOverviewComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CartOverviewComponent_vue_vue_type_template_id_76671e80___WEBPACK_IMPORTED_MODULE_0__["render"],
  _CartOverviewComponent_vue_vue_type_template_id_76671e80___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/cart/CartOverviewComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/cart/CartOverviewComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/cart/CartOverviewComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CartOverviewComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/babel-loader/lib??ref--12-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./CartOverviewComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartOverviewComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CartOverviewComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/cart/CartOverviewComponent.vue?vue&type=template&id=76671e80&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/cart/CartOverviewComponent.vue?vue&type=template&id=76671e80& ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CartOverviewComponent_vue_vue_type_template_id_76671e80___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./CartOverviewComponent.vue?vue&type=template&id=76671e80& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartOverviewComponent.vue?vue&type=template&id=76671e80&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CartOverviewComponent_vue_vue_type_template_id_76671e80___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CartOverviewComponent_vue_vue_type_template_id_76671e80___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);