(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["product-page-component"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductPageComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductPageComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    product: {
      type: Object
    }
  },
  data: function data() {
    return {
      currentProduct: {}
    };
  },
  mounted: function mounted() {
    this.currentProduct = this.product;
  },
  computed: {
    url: function url() {
      return window.origin;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductPageComponent.vue?vue&type=template&id=2113120a&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductPageComponent.vue?vue&type=template&id=2113120a& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
  return _c("section", [
    _c("div", { staticClass: "col-12 d-block d-sm-none" }, [
      _c("h1", { staticClass: "h3 mt-3 font-size-1-3rem line-height-1-8rem" }, [
        _vm._v(_vm._s(_vm.currentProduct.name))
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "col-lg-6" }, [
      _c(
        "div",
        {
          staticClass:
            "product--image__wrapper alert alert-light text-center d-flex align-items-center mb-2 mb-md-0 position-relative"
        },
        [
          _vm.currentProduct.free_shipping == 1
            ? _c(
                "div",
                {
                  staticClass: "position-absolute",
                  staticStyle: { left: "1rem", top: "1rem", "z-index": "3" }
                },
                [
                  _c(
                    "span",
                    {
                      staticClass:
                        "badge badge-primary shadow text-uppercase text-white py-1 px-2"
                    },
                    [_vm._v("Free Shipping")]
                  )
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _c("product-images-component", {
            attrs: { product: _vm.currentProduct }
          })
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "col-lg-6" }, [
      _c(
        "h1",
        {
          staticClass:
            "h3 d-none d-sm-block font-size-md-1-5rem line-height-md-2-1rem"
        },
        [_vm._v(_vm._s(_vm.currentProduct.name))]
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "form-group mt-4" },
        [
          _c("money-component", {
            staticClass: "text-decoration-line-through",
            attrs: { value: _vm.currentProduct.original_price }
          }),
          _vm._v(" "),
          _c("money-component", {
            staticClass: "text-highlight h3",
            attrs: { value: _vm.currentProduct.price }
          }),
          _vm._v(" "),
          _c("a", { attrs: { href: "#bulk-price", id: "bulk-price-link" } }, [
            _vm._v("See special bulk pricing")
          ])
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "d-flex d-lg-none mb-3 justify-content-between" },
        [
          _c("product-cart-component", {
            staticClass:
              "d-flex flex-row align-items-center justify-content-center",
            attrs: { product: _vm.currentProduct }
          }),
          _vm._v(" "),
          _c("add-to-favorites", { attrs: { product: _vm.currentProduct } })
        ],
        1
      ),
      _vm._v(" "),
      _c("div", { staticClass: "p-3 bg-grey-light rounded-lg mb-3" }, [
        _c("div", { staticClass: "mb-0 product-info-table" }, [
          _c("div", [
            _c("div", { staticClass: "row" }, [
              _c("div", { staticClass: "col-6 py-2 px-0 border-0" }, [
                _vm._v("\n\t\t\t\t\t\t\tProduct ID\n\t\t\t\t\t\t")
              ]),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass:
                    "col-6 py-2 px-0 border-0 text-right text-highlight"
                },
                [
                  _vm._v(
                    "\n\t\t\t\t\t\t\t" +
                      _vm._s(_vm.currentProduct.id) +
                      "\n\t\t\t\t\t\t"
                  )
                ]
              )
            ]),
            _vm._v(" "),
            _vm.currentProduct.price_each !== null &&
            _vm.currentProduct.price_each !== ""
              ? _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-6 py-2 px-0" }, [
                    _vm._v("\n\t\t\t\t\t\t\tPrice each\n\t\t\t\t\t\t")
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass: "col-6 py-2 px-0 text-right text-highlight"
                    },
                    [
                      _c("money-component", {
                        attrs: { value: _vm.currentProduct.price_each }
                      })
                    ],
                    1
                  )
                ])
              : _vm._e(),
            _vm._v(" "),
            _vm.currentProduct.price_size !== null &&
            _vm.currentProduct.price_size !== "" &&
            _vm.currentProduct.price_size != _vm.currentProduct.price
              ? _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-6 py-2 px-0" }, [
                    _vm._v(
                      "\n\t\t\t\t\t\t\tPrice per " +
                        _vm._s(_vm.currentProduct.size_uom) +
                        "\n\t\t\t\t\t\t"
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass: "col-6 py-2 px-0 text-right text-highlight"
                    },
                    [
                      _c("money-component", {
                        attrs: { value: _vm.currentProduct.price_size }
                      })
                    ],
                    1
                  )
                ])
              : _vm._e(),
            _vm._v(" "),
            _vm.currentProduct.sku !== null && _vm.currentProduct.sku !== ""
              ? _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-6 py-2 px-0" }, [
                    _vm._v("\n\t\t\t\t\t\t\tSKU:\n\t\t\t\t\t\t")
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass: "col-6 py-2 px-0 text-right text-highlight"
                    },
                    [
                      _vm._v(
                        "\n\t\t\t\t\t\t\t" +
                          _vm._s(_vm.currentProduct.sku) +
                          "\n\t\t\t\t\t\t"
                      )
                    ]
                  )
                ])
              : _vm._e()
          ]),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "collapse",
              attrs: { id: "additional-product-info-table" }
            },
            [
              _vm.currentProduct.category !== undefined &&
              _vm.currentProduct.category !== null
                ? _c("div", { staticClass: "row" }, [
                    _c("div", { staticClass: "col-6 py-2 px-0" }, [
                      _vm._v("\n\t\t\t\t\t\t\tCategory:\n\t\t\t\t\t\t")
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "col-6 py-2 px-0 text-right" }, [
                      _c("span", { staticClass: "text-highlight" }, [
                        _vm._v(_vm._s(_vm.currentProduct.name))
                      ])
                    ])
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.currentProduct.brand !== undefined &&
              _vm.currentProduct.brand !== null
                ? _c("div", { staticClass: "row" }, [
                    _c("div", { staticClass: "col-6 py-2 px-0" }, [
                      _vm._v("\n\t\t\t\t\t\t\tBrand:\n\t\t\t\t\t\t")
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "col-6 py-2 px-0 text-right" }, [
                      _c("span", { staticClass: "text-highlight" }, [
                        _vm._v(_vm._s(_vm.currentProduct.brand.name))
                      ])
                    ])
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.currentProduct.mpn !== null && _vm.currentProduct.mpn !== ""
                ? _c("div", { staticClass: "row" }, [
                    _c("div", { staticClass: "col-6 py-2 px-0" }, [
                      _vm._v("\n\t\t\t\t\t\t\tModel Number:\n\t\t\t\t\t\t")
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "col-6 py-2 px-0 text-right" }, [
                      _c("span", { staticClass: "text-highlight" }, [
                        _vm._v(_vm._s(_vm.currentProduct.mpn))
                      ])
                    ])
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.currentProduct.weight !== null &&
              _vm.currentProduct.weight !== "" &&
              _vm.currentProduct.weight_uom !== null &&
              _vm.currentProduct.weight_uom !== ""
                ? _c("div", { staticClass: "row" }, [
                    _c("div", { staticClass: "col-6 py-2 px-0" }, [
                      _vm._v("\n\t\t\t\t\t\t\tWeight:\n\t\t\t\t\t\t")
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "col-6 py-2 px-0 text-right" }, [
                      _c("span", { staticClass: "text-highlight" }, [
                        _vm._v(
                          _vm._s(_vm.currentProduct.weight) +
                            " " +
                            _vm._s(_vm.currentProduct.weight_uom)
                        )
                      ])
                    ])
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.currentProduct.upc !== null && _vm.currentProduct.upc !== ""
                ? _c("div", { staticClass: "row" }, [
                    _c("div", { staticClass: "col-6 py-2 px-0" }, [
                      _vm._v("\n\t\t\t\t\t\t\tGTIN/UPC:\n\t\t\t\t\t\t")
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "col-6 py-2 px-0 text-right" }, [
                      _c("span", { staticClass: "text-highlight" }, [
                        _vm._v(_vm._s(_vm.currentProduct.upc))
                      ])
                    ])
                  ])
                : _vm._e()
            ]
          ),
          _vm._v(" "),
          _vm._m(0)
        ])
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "d-none d-lg-flex justify-content-between" },
        [
          _c("product-cart-component", {
            staticClass:
              "d-flex flex-row align-items-center justify-content-center",
            attrs: { product: _vm.currentProduct }
          }),
          _vm._v(" "),
          _c("add-to-favorites", { attrs: { product: _vm.currentProduct } })
        ],
        1
      ),
      _vm._v(" "),
      _vm.currentProduct.short_description !== null &&
      _vm.currentProduct.short_description !== ""
        ? _c("div", {
            staticClass: "p-4 rounded bg-light mb-3 mt-4",
            domProps: {
              innerHTML: _vm._s(_vm.currentProduct.short_description)
            }
          })
        : _vm._e(),
      _vm._v(" "),
      _c("div", { staticClass: "mt-3" }, [
        _c(
          "a",
          {
            staticClass: "h6 font-weight-bold cursor-pointer",
            attrs: { href: "#bulk-price-table-per-unit" },
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.scrollTo($event)
              }
            }
          },
          [_vm._v("Share")]
        ),
        _vm._v(" "),
        _c("ul", { staticClass: "list-inline" }, [
          _c("li", { staticClass: "list-inline-item px-1" }, [
            _c(
              "a",
              {
                staticClass: "text-red",
                attrs: {
                  target: "_blank",
                  href:
                    "https://www.facebook.com/sharer/sharer.php?u=" +
                    _vm.route("product.show", _vm.currentProduct.slug).url()
                }
              },
              [_c("i", { staticClass: "fab fa-facebook-f" })]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "list-inline-item px-1" }, [
            _c(
              "a",
              {
                staticClass: "text-red",
                attrs: {
                  target: "_blank",
                  href:
                    "https://twitter.com/intent/tweet?text=Check out this " +
                    _vm.currentProduct.name +
                    " on " +
                    _vm.route("product.show", _vm.currentProduct.slug).url()
                }
              },
              [_c("i", { staticClass: "fab fa-twitter" })]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "list-inline-item px-1" }, [
            _c(
              "a",
              {
                staticClass: "text-red",
                attrs: {
                  target: "_blank",
                  href:
                    "https://pinterest.com/pin/create/button/?url=" +
                    _vm.route("product.show", _vm.currentProduct.slug).url() +
                    "&media=" +
                    (_vm.url + "/" + _vm.currentProduct.main_image) +
                    "&description=Check out this " +
                    _vm.currentProduct.name
                }
              },
              [_c("i", { staticClass: "fab fa-pinterest-p" })]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "list-inline-item px-1" }, [
            _c(
              "a",
              {
                staticClass: "text-red",
                attrs: {
                  target: "_blank",
                  href:
                    "mailto:?subject=Check out this " +
                    _vm.currentProduct.name +
                    "&body=Check out this " +
                    _vm.currentProduct.name +
                    " on " +
                    _vm.route("product.show", _vm.currentProduct.slug).url()
                }
              },
              [_c("i", { staticClass: "fa fa-share-alt" })]
            )
          ])
        ])
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "col-10 mb-4 mx-auto" }, [
      _c("p", [
        _c(
          "a",
          {
            attrs: {
              href: _vm.route("quoterequest.index", {
                product: _vm.currentProduct.sku
              })
            }
          },
          [
            _vm._v(
              "\n\t        \tInterested in 10 or more cases? Click Here for a Quote\n\t        "
            )
          ]
        )
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "d-flex bulk-price-table mb-5" }, [
        _vm._m(1),
        _vm._v(" "),
        _c("div", { staticClass: "row bulk-price-table__row" }, [
          _c(
            "div",
            {
              staticClass:
                "col-md-2 py-2 px-0 font-weight-bold bulk-price-table__cell",
              attrs: { id: "bulk-price-table-per-unit" }
            },
            [_vm._v("Price Per Unit")]
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
            [
              _vm._v(
                "\n\t            \t" +
                  _vm._s(_vm._f("currency")(_vm.currentProduct.price * 1)) +
                  "\n\t            "
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
            [
              _vm._v(
                "\n\t            \t" +
                  _vm._s(_vm._f("currency")(_vm.currentProduct.price * 0.96)) +
                  "\n\t            "
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
            [
              _vm._v(
                "\n\t            \t" +
                  _vm._s(_vm._f("currency")(_vm.currentProduct.price * 0.95)) +
                  "\n\t            "
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
            [
              _vm._v(
                "\n\t            \t" +
                  _vm._s(_vm._f("currency")(_vm.currentProduct.price * 0.94)) +
                  "\n\t            "
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
            [
              _c(
                "a",
                {
                  attrs: {
                    href: _vm.route("quoterequest.index", {
                      product: _vm.currentProduct.sku
                    })
                  }
                },
                [_vm._v("\n\t            \t\tClick here\n\t            \t")]
              )
            ]
          )
        ])
      ])
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-6" }),
      _vm._v(" "),
      _c("div", { staticClass: "col-6 py-2 text-right" }, [
        _c(
          "a",
          {
            staticClass: "additional-info-toggle font-weight-bold collapsed",
            attrs: {
              role: "button",
              "data-toggle": "collapse",
              "data-target": "#additional-product-info-table"
            }
          },
          [_vm._v("\n\t\t\t\t\t\t\tMore information\n\t\t\t\t\t\t")]
        )
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "row bulk-price-table__row border-bottom font-weight-bold"
      },
      [
        _c("div", { staticClass: "col-md-2 py-2 bulk-price-table__cell" }, [
          _vm._v("Quantity")
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
          [_vm._v("1")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
          [_vm._v("2-3")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
          [_vm._v("4-6")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
          [_vm._v("7-9")]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2 py-2 px-0 bulk-price-table__cell" },
          [_vm._v("10+")]
        )
      ]
    )
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

/***/ "./resources/js/components/product/ProductPageComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/product/ProductPageComponent.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProductPageComponent_vue_vue_type_template_id_2113120a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProductPageComponent.vue?vue&type=template&id=2113120a& */ "./resources/js/components/product/ProductPageComponent.vue?vue&type=template&id=2113120a&");
/* harmony import */ var _ProductPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProductPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/product/ProductPageComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProductPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProductPageComponent_vue_vue_type_template_id_2113120a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProductPageComponent_vue_vue_type_template_id_2113120a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/product/ProductPageComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/product/ProductPageComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/product/ProductPageComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/babel-loader/lib??ref--12-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductPageComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/product/ProductPageComponent.vue?vue&type=template&id=2113120a&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/product/ProductPageComponent.vue?vue&type=template&id=2113120a& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductPageComponent_vue_vue_type_template_id_2113120a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductPageComponent.vue?vue&type=template&id=2113120a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductPageComponent.vue?vue&type=template&id=2113120a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductPageComponent_vue_vue_type_template_id_2113120a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductPageComponent_vue_vue_type_template_id_2113120a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);