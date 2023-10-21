(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["product-modal-component"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductModalComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductModalComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function($) {//
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
  methods: {
    reduceQuantity: function reduceQuantity() {
      if (this.quantity > 1) {
        this.quantity -= 1;
      }
    },
    raiseQuantity: function raiseQuantity() {
      if (this.quantity < this.selectedProduct.quantity) {
        this.quantity += 1;
      }
    }
  },
  data: function data() {
    return {
      quantity: 1,
      product: [],
      selectedProduct: [],
      products: []
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.$root.$on('showProductChildren', function (product, products) {
      $('#product-modal-component').modal('show');
      _this.product = product;
      _this.products = products;
      _this.selectedProduct = _this.product;
    });
    this.$root.$on('cartItemAdded', function () {
      $('#product-modal-component').modal('hide');
      _this.product = [];
      _this.products = [];
      _this.selectedProduct = [];
    });
  },
  computed: {
    currentProduct: function currentProduct() {
      if (this.selectedProduct === undefined || this.selectedProduct.id === this.product.id || this.selectedProduct.length === 0) {
        return this.product;
      }

      return this.selectedProduct;
    }
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductModalComponent.vue?vue&type=template&id=475a66b8&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductModalComponent.vue?vue&type=template&id=475a66b8& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
    {
      staticClass: "modal fade",
      attrs: {
        tabindex: "-1",
        "data-keyboard": "true",
        role: "dialog",
        "aria-hidden": "true",
        id: "product-modal-component",
        "data-backdrop": "static"
      }
    },
    [
      _c(
        "div",
        { staticClass: "modal-dialog modal-xl", attrs: { role: "document" } },
        [
          _c("div", { staticClass: "modal-content" }, [
            _vm._m(0),
            _vm._v(" "),
            _c("div", { staticClass: "modal-body pt-0 pb-5 min-h-md-420px" }, [
              _c("div", { staticClass: "px-lg-3 row align-items-center" }, [
                _vm.currentProduct.id !== undefined
                  ? _c(
                      "div",
                      {
                        staticClass:
                          "col-md-4 col-lg-6 mx-auto py-2 mt-1 mb-0 w-100 text-center align-items-center d-flex"
                      },
                      [
                        _c("img", {
                          directives: [
                            {
                              name: "lazy",
                              rawName: "v-lazy",
                              value: _vm.currentProduct.main_image,
                              expression: "currentProduct.main_image"
                            }
                          ],
                          staticClass:
                            "d-block h-auto mx-auto img-fluid w-auto",
                          attrs: {
                            "data-error": "/storage/notfound.jpg",
                            "data-loading": "/images/px.png",
                            src: "/images/px.png",
                            "data-src": _vm.currentProduct.main_image
                          }
                        })
                      ]
                    )
                  : _vm._e(),
                _vm._v(" "),
                _vm.currentProduct !== undefined &&
                _vm.currentProduct.id !== undefined
                  ? _c(
                      "div",
                      { staticClass: "col-md-8 col-lg-6 align-self-start" },
                      [
                        _c(
                          "h1",
                          {
                            staticClass:
                              "h3 text-left d-none d-sm-block font-size-md-1-5rem line-height-md-2-1rem font-weight-bold"
                          },
                          [_vm._v(_vm._s(_vm.currentProduct.name))]
                        ),
                        _vm._v(" "),
                        _vm.currentProduct.brand
                          ? _c("h2", { staticClass: "h5 mb-2 text-left" }, [
                              _vm._v(_vm._s(_vm.currentProduct.brand.name))
                            ])
                          : _vm._e(),
                        _vm._v(" "),
                        _c(
                          "p",
                          {
                            staticClass: "mb-3 font-weight-light h4 text-left"
                          },
                          [
                            _vm.currentProduct.original_price >
                            _vm.currentProduct.price
                              ? _c(
                                  "span",
                                  {
                                    staticClass: "text-decoration-line-through"
                                  },
                                  [
                                    _vm._v(
                                      _vm._s(
                                        _vm._f("currency")(
                                          _vm.currentProduct.original_price
                                        )
                                      )
                                    )
                                  ]
                                )
                              : _vm._e(),
                            _vm._v(" "),
                            _c("span", { staticClass: "text-highlight h3" }, [
                              _vm._v(
                                _vm._s(
                                  _vm._f("currency")(_vm.currentProduct.price)
                                )
                              )
                            ])
                          ]
                        ),
                        _vm._v(" "),
                        _c("table", { staticClass: "table" }, [
                          _c("tr", [
                            _c("th", { staticClass: "px-0 py-2 text-left" }, [
                              _vm._v(
                                "\n\t\t\t\t\t\t\t\t\tBrand\n\t\t\t\t\t\t\t\t"
                              )
                            ]),
                            _vm._v(" "),
                            _c("td", { staticClass: "px-0 py-2 text-right" }, [
                              _vm.currentProduct.brand
                                ? _c("span", [
                                    _vm._v(
                                      _vm._s(_vm.currentProduct.brand.name)
                                    )
                                  ])
                                : _vm._e()
                            ])
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _c("th", { staticClass: "px-0 py-2 text-left" }, [
                              _vm._v("Product Code")
                            ]),
                            _vm._v(" "),
                            _c("td", { staticClass: "px-0 py-2 text-right" }, [
                              _vm._v(_vm._s(_vm.currentProduct.sku))
                            ])
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _c("th", { staticClass: "px-0 py-2 text-left" }, [
                              _vm._v("Availability")
                            ]),
                            _vm._v(" "),
                            _c("td", { staticClass: "px-0 py-2 text-right" }, [
                              _vm.currentProduct.quantity > 0
                                ? _c("span", { staticClass: "text-success" }, [
                                    _vm._v(
                                      _vm._s(_vm.currentProduct.quantity) +
                                        " in stock"
                                    )
                                  ])
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.currentProduct.quantity <= 0
                                ? _c("span", { staticClass: "text-danger" }, [
                                    _vm._v("Out of Stock")
                                  ])
                                : _vm._e()
                            ])
                          ])
                        ]),
                        _vm._v(" "),
                        _vm.products !== undefined && _vm.products.length > 0
                          ? _c("div", { staticClass: "form-group" }, [
                              _c(
                                "label",
                                {
                                  staticClass: "h5 font-weight-normal",
                                  attrs: { for: "product-options" }
                                },
                                [_vm._v("Options")]
                              ),
                              _vm._v(" "),
                              _c(
                                "select",
                                {
                                  directives: [
                                    {
                                      name: "model",
                                      rawName: "v-model",
                                      value: _vm.selectedProduct,
                                      expression: "selectedProduct"
                                    }
                                  ],
                                  staticClass: "form-control",
                                  attrs: { id: "product-options" },
                                  on: {
                                    change: function($event) {
                                      var $$selectedVal = Array.prototype.filter
                                        .call($event.target.options, function(
                                          o
                                        ) {
                                          return o.selected
                                        })
                                        .map(function(o) {
                                          var val =
                                            "_value" in o ? o._value : o.value
                                          return val
                                        })
                                      _vm.selectedProduct = $event.target
                                        .multiple
                                        ? $$selectedVal
                                        : $$selectedVal[0]
                                    }
                                  }
                                },
                                [
                                  _c(
                                    "option",
                                    {
                                      attrs: { selected: "" },
                                      domProps: { value: _vm.product }
                                    },
                                    [_vm._v(_vm._s(_vm.product.name))]
                                  ),
                                  _vm._v(" "),
                                  _vm._l(_vm.products, function(productItem) {
                                    return _c(
                                      "option",
                                      { domProps: { value: productItem } },
                                      [_vm._v(_vm._s(productItem.name))]
                                    )
                                  })
                                ],
                                2
                              )
                            ])
                          : _vm._e(),
                        _vm._v(" "),
                        _c(
                          "div",
                          {
                            staticClass:
                              "d-flex justify-content-between mt-auto mb-0"
                          },
                          [
                            _c("product-cart-component", {
                              staticClass:
                                "d-flex flex-row align-items-center justify-content-center",
                              attrs: { product: _vm.currentProduct }
                            })
                          ],
                          1
                        )
                      ]
                    )
                  : _vm._e()
              ])
            ])
          ])
        ]
      )
    ]
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "modal-header pb-0 border-0" }, [
      _c(
        "button",
        {
          staticClass: "close",
          attrs: {
            type: "button",
            "data-dismiss": "modal",
            "aria-label": "Close"
          }
        },
        [_c("span", { attrs: { "aria-hidden": "true" } }, [_vm._v("×")])]
      )
    ])
  }
]
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

/***/ "./resources/js/components/product/ProductModalComponent.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/product/ProductModalComponent.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProductModalComponent_vue_vue_type_template_id_475a66b8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProductModalComponent.vue?vue&type=template&id=475a66b8& */ "./resources/js/components/product/ProductModalComponent.vue?vue&type=template&id=475a66b8&");
/* harmony import */ var _ProductModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProductModalComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/product/ProductModalComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProductModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProductModalComponent_vue_vue_type_template_id_475a66b8___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProductModalComponent_vue_vue_type_template_id_475a66b8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/product/ProductModalComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/product/ProductModalComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/product/ProductModalComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/babel-loader/lib??ref--12-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductModalComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductModalComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductModalComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/product/ProductModalComponent.vue?vue&type=template&id=475a66b8&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/product/ProductModalComponent.vue?vue&type=template&id=475a66b8& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductModalComponent_vue_vue_type_template_id_475a66b8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductModalComponent.vue?vue&type=template&id=475a66b8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductModalComponent.vue?vue&type=template&id=475a66b8&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductModalComponent_vue_vue_type_template_id_475a66b8___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductModalComponent_vue_vue_type_template_id_475a66b8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);