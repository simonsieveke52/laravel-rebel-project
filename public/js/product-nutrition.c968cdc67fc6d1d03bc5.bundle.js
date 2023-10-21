(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["product-nutrition"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductNutrition.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductNutrition.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['nutritionData'],
  data: function data() {
    return {
      nutrition: null
    };
  },
  mounted: function mounted() {
    this.nutrition = this.nutritionData.content;
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductNutrition.vue?vue&type=template&id=5a323f74&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductNutrition.vue?vue&type=template&id=5a323f74& ***!
  \***************************************************************************************************************************************************************************************************************************/
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
  return _vm.nutrition !== undefined && _vm.nutrition !== null
    ? _c("div", [
        _c("div", { staticClass: "px-3 pb-2 mt-2" }, [
          _c(
            "h1",
            { staticClass: "h3 border-bottom text-dark font-weight-bolder" },
            [_vm._v("Nutrition Facts")]
          ),
          _vm._v(" "),
          _c("div", [
            _vm.nutrition.number_of_servings_per_package !== null
              ? _c("p", { staticClass: "mb-0" }, [
                  _vm._v(
                    _vm._s(_vm.nutrition.number_of_servings_per_package) +
                      " servings per container"
                  )
                ])
              : _vm._e(),
            _vm._v(" "),
            _c(
              "p",
              {
                staticClass:
                  "mb-0 d-flex flex-row align-items-center justify-content-between"
              },
              [
                _c("span", { staticClass: "font-weight-bolder" }, [
                  _vm._v("Serving Size")
                ]),
                _vm._v(" "),
                _c("code", { staticClass: "text-dark" }, [
                  _vm._v(
                    _vm._s(_vm.nutrition.serving_size) +
                      _vm._s(
                        _vm._f("strtolower")(_vm.nutrition.serving_size_uom)
                      )
                  )
                ])
              ]
            )
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "bg-dark py-1 border-dark" }),
        _vm._v(" "),
        _c("div", { staticClass: "px-3 py-2" }, [
          _c("p", { staticClass: "mb-n1" }, [_vm._v("Amount Per Serving")]),
          _vm._v(" "),
          _c(
            "p",
            {
              staticClass:
                "mb-0 d-flex flex-row align-items-center justify-content-between h3 font-weight-bolder"
            },
            [
              _c("span", [_vm._v("Calories")]),
              _vm._v(" "),
              _c("code", { staticClass: "text-dark" }, [
                _vm._v(_vm._s(_vm.nutrition.calories))
              ])
            ]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "bg-dark py-1 border-dark" }),
        _vm._v(" "),
        _c("div", { staticClass: "px-3 py-2" }, [
          _c("p", { staticClass: "mb-0 text-right small font-weight-bolder" }, [
            _vm._v("% Daily Value*")
          ]),
          _vm._v(" "),
          _c("div", [
            _c(
              "p",
              {
                staticClass:
                  "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
              },
              [
                _c("span", [
                  _c("span", { staticClass: "font-weight-bolder" }, [
                    _vm._v("Total Fat")
                  ]),
                  _vm._v(" "),
                  _c("code", { staticClass: "text-dark" }, [
                    _vm._v(
                      _vm._s(_vm.nutrition.total_fat) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.total_fat_uom)
                        )
                    )
                  ])
                ]),
                _vm._v(" "),
                _vm.nutrition.total_fat_rdi !== null
                  ? _c("code", { staticClass: "text-dark" }, [
                      _vm._v(_vm._s(_vm.nutrition.total_fat_rdi) + "%")
                    ])
                  : _vm._e()
              ]
            ),
            _vm._v(" "),
            _c(
              "p",
              {
                staticClass:
                  "pl-3 mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
              },
              [
                _c("span", [
                  _vm._v("Saturated Fat "),
                  _c("code", { staticClass: "text-dark" }, [
                    _vm._v(
                      _vm._s(_vm.nutrition.saturated_fat) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.sat_fat_uom))
                    )
                  ])
                ]),
                _vm._v(" "),
                _vm.nutrition.sat_fat_rdi !== null
                  ? _c("code", { staticClass: "text-dark" }, [
                      _vm._v(_vm._s(_vm.nutrition.sat_fat_rdi) + "%")
                    ])
                  : _vm._e()
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", [
            _c(
              "p",
              {
                staticClass:
                  "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
              },
              [
                _c("span", { staticClass: "font-weight-bolder" }, [
                  _vm._v("\n\t\t\t\t\tSodium\n\t\t\t\t\t"),
                  _c("code", { staticClass: "font-weight-normal text-dark" }, [
                    _vm._v(
                      _vm._s(_vm.nutrition.sodium) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.sodium_uom))
                    )
                  ])
                ]),
                _vm._v(" "),
                _vm.nutrition.sodium_rdi !== null
                  ? _c("code", { staticClass: "text-dark" }, [
                      _vm._v(_vm._s(_vm.nutrition.sodium_rdi) + "%")
                    ])
                  : _vm._e()
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", [
            _c(
              "p",
              {
                staticClass:
                  "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
              },
              [
                _c("span", [
                  _c("span", { staticClass: "font-weight-bolder" }, [
                    _vm._v("Total Carbohydrate")
                  ]),
                  _vm._v(" "),
                  _c("code", { staticClass: "text-dark" }, [
                    _vm._v(
                      _vm._s(_vm.nutrition.carbohydrates) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.carb_uom))
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("code", { staticClass: "text-dark" }, [
                  _vm._v(_vm._s(_vm.nutrition.carb_rdi) + "%")
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "p",
              {
                staticClass:
                  "pl-3 mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
              },
              [
                _c("span", [
                  _vm._v(
                    "Dietary Fiber " +
                      _vm._s(_vm.nutrition.total_diet_fiber) +
                      _vm._s(
                        _vm._f("strtolower")(_vm.nutrition.total_diet_fiber_uom)
                      )
                  )
                ]),
                _vm._v(" "),
                _vm.nutrition.total_diet_fiber_rdi !== null
                  ? _c("code", { staticClass: "text-dark" }, [
                      _vm._v(_vm._s(_vm.nutrition.total_diet_fiber_rdi) + "%")
                    ])
                  : _vm._e()
              ]
            ),
            _vm._v(" "),
            _c(
              "p",
              {
                staticClass:
                  "pl-3 mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
              },
              [
                _c("span", [
                  _vm._v(
                    "Total Sugars " +
                      _vm._s(_vm.nutrition.total_sugar) +
                      _vm._s(
                        _vm._f("strtolower")(_vm.nutrition.total_sugar_uom)
                      )
                  )
                ]),
                _vm._v(" "),
                _vm.nutrition.total_sugar_rdi !== null
                  ? _c("code", { staticClass: "text-dark" }, [
                      _vm._v(_vm._s(_vm.nutrition.total_sugar_rdi) + "%")
                    ])
                  : _vm._e()
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", [
            _c("p", { staticClass: "mb-0" }, [
              _c("span", { staticClass: "font-weight-bolder" }, [
                _vm._v("Protein")
              ]),
              _vm._v(" "),
              _c("code", { staticClass: "text-dark" }, [
                _vm._v(
                  _vm._s(_vm.nutrition.protein) +
                    _vm._s(_vm._f("strtolower")(_vm.nutrition.protein_uom))
                )
              ])
            ])
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "bg-dark py-1 border-dark" }),
        _vm._v(" "),
        _c("div", { staticClass: "px-3 py-2" }, [
          _vm.nutrition.vitamin_a !== null && _vm.nutrition.vitamin_a !== 0
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Vitamin A " +
                        _vm._s(_vm.nutrition.vitamin_a) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.vitamin_a_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.vitamin_a_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.vitamin_a_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.vitamin_b12 !== null && _vm.nutrition.vitamin_b12 !== 0
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Vitamin B12 " +
                        _vm._s(_vm.nutrition.vitamin_b12) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.vitamin_b12_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.vitamin_b12_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.vitamin_b12_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.vitamin_b6 !== null && _vm.nutrition.vitamin_b6 !== 0
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Vitamin B6 " +
                        _vm._s(_vm.nutrition.vitamin_b6) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.vitamin_b6_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.vitamin_b6_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.vitamin_b6_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.vitamin_c !== null && _vm.nutrition.vitamin_c !== 0
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Vitamin C " +
                        _vm._s(_vm.nutrition.vitamin_c) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.vitamin_c_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.vitamin_c_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.vitamin_c_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.vitamin_d !== null && _vm.nutrition.vitamin_d !== 0
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Vitamin D " +
                        _vm._s(_vm.nutrition.vitamin_d) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.vitamin_d_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.vitamin_d_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.vitamin_d_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.vitamin_e !== null && _vm.nutrition.vitamin_e !== 0
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Vitamin E " +
                        _vm._s(_vm.nutrition.vitamin_e) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.vitamin_e_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.vitamin_e_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.vitamin_e_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.vitamin_k !== null && _vm.nutrition.vitamin_k !== 0
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Vitamin K " +
                        _vm._s(_vm.nutrition.vitamin_k) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.vitamin_k_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.vitamin_k_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.vitamin_k_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.magnesium !== null
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Biotin " +
                        _vm._s(_vm.nutrition.biotin) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.biotin_uom))
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.biotin_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.biotin_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.magnesium !== null
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Copper " +
                        _vm._s(_vm.nutrition.copper) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.copper_uom))
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.copper_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.copper_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.magnesium !== null
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Magnesium " +
                        _vm._s(_vm.nutrition.magnesium) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.magnesium_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.magnesium_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.magnesium_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.calcium !== null
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Calcium " +
                        _vm._s(_vm.nutrition.calcium) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.calcium_uom))
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.calcium_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.calcium_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.calcium !== null
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Potassium " +
                        _vm._s(_vm.nutrition.potassium) +
                        _vm._s(
                          _vm._f("strtolower")(_vm.nutrition.potassium_uom)
                        )
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.potassium_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.potassium_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.iron !== null
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Iron " +
                        _vm._s(_vm.nutrition.iron) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.iron_uom))
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.iron_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.iron_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.zinc !== null
            ? _c(
                "p",
                {
                  staticClass:
                    "mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted"
                },
                [
                  _c("span", [
                    _vm._v(
                      "Zinc " +
                        _vm._s(_vm.nutrition.zinc) +
                        _vm._s(_vm._f("strtolower")(_vm.nutrition.zinc_uom))
                    )
                  ]),
                  _vm._v(" "),
                  _vm.nutrition.zinc_rdi !== null
                    ? _c("code", { staticClass: "text-dark" }, [
                        _vm._v(_vm._s(_vm.nutrition.zinc_rdi) + "%")
                      ])
                    : _vm._e()
                ]
              )
            : _vm._e()
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "bg-dark py-1 border-dark" }),
        _vm._v(" "),
        _c("div", { staticClass: "px-3 py-2 mb-0 small" }, [
          _vm.nutrition.ingredients !== null
            ? _c("p", { staticClass: "mb-2" }, [
                _c("span", { staticClass: "font-weight-bolder" }, [
                  _vm._v("Ingredients:")
                ]),
                _vm._v(" "),
                _c("span", { staticClass: "font-weight-light" }, [
                  _vm._v(_vm._s(_vm.nutrition.ingredients))
                ])
              ])
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.prep_cook_suggestions !== null
            ? _c("p", { staticClass: "mb-2" }, [
                _c("span", { staticClass: "font-weight-bolder" }, [
                  _vm._v("Cooking Suggestions:")
                ]),
                _vm._v(" "),
                _c("span", { staticClass: "font-weight-light" }, [
                  _vm._v(_vm._s(_vm.nutrition.prep_cook_suggestions))
                ])
              ])
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.serving_suggestion !== null
            ? _c("p", { staticClass: "mb-2" }, [
                _c("span", { staticClass: "font-weight-bolder" }, [
                  _vm._v("Serving Suggestions:")
                ]),
                _vm._v(" "),
                _c("span", { staticClass: "font-weight-light" }, [
                  _vm._v(_vm._s(_vm.nutrition.serving_suggestion))
                ])
              ])
            : _vm._e(),
          _vm._v(" "),
          _vm.nutrition.benefits !== null
            ? _c("p", { staticClass: "mb-2" }, [
                _c("span", { staticClass: "font-weight-bolder" }, [
                  _vm._v("Benefits:")
                ]),
                _vm._v(" "),
                _c("span", { staticClass: "font-weight-light" }, [
                  _vm._v(_vm._s(_vm.nutrition.benefits))
                ])
              ])
            : _vm._e()
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "bg-dark py-1 border-dark" }),
        _vm._v(" "),
        _vm._m(0)
      ])
    : _vm._e()
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "px-3 py-2 mb-0 small" }, [
      _c("span", [
        _vm._v(
          "\n\t\t\t*The % Daily Value (DV) tells you how much a nutrient in a serving of food contributes to a daily diet. 2,000 calories a day is used for general nutrition advice. These values were calculated and therefore are approximate. For more accuracy, testing is advised.\n\t\t"
        )
      ])
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

/***/ "./resources/js/components/product/ProductNutrition.vue":
/*!**************************************************************!*\
  !*** ./resources/js/components/product/ProductNutrition.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProductNutrition_vue_vue_type_template_id_5a323f74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProductNutrition.vue?vue&type=template&id=5a323f74& */ "./resources/js/components/product/ProductNutrition.vue?vue&type=template&id=5a323f74&");
/* harmony import */ var _ProductNutrition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProductNutrition.vue?vue&type=script&lang=js& */ "./resources/js/components/product/ProductNutrition.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProductNutrition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProductNutrition_vue_vue_type_template_id_5a323f74___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProductNutrition_vue_vue_type_template_id_5a323f74___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/product/ProductNutrition.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/product/ProductNutrition.vue?vue&type=script&lang=js&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/product/ProductNutrition.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductNutrition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/babel-loader/lib??ref--12-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductNutrition.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductNutrition.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductNutrition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/product/ProductNutrition.vue?vue&type=template&id=5a323f74&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/product/ProductNutrition.vue?vue&type=template&id=5a323f74& ***!
  \*********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductNutrition_vue_vue_type_template_id_5a323f74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductNutrition.vue?vue&type=template&id=5a323f74& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductNutrition.vue?vue&type=template&id=5a323f74&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductNutrition_vue_vue_type_template_id_5a323f74___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductNutrition_vue_vue_type_template_id_5a323f74___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);