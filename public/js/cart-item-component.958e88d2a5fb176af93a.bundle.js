(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["cart-item-component"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartItemComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/cart/CartItemComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************/
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


/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['item'],
  data: function data() {
    return {
      ajaxRequest: null
    };
  },
  methods: {
    reduceQuantity: function reduceQuantity() {
      if (this.quantity > 1) {
        this.quantity -= 1;
      }
    },
    raiseQuantity: function raiseQuantity() {
      this.quantity += 1;
    },
    deleteItem: function deleteItem() {
      if (!confirm('Are you sure you want to remove ' + this.item.name + ' from your cart?')) {
        return true;
      }

      var self = this;
      $.ajax({
        url: '/cart/' + this.item.id,
        type: 'DELETE'
      }).done(function () {
        self.item.deleted = true;
        self.$root.$emit('cartItemDeleted', self.item);
      }).fail(function () {
        alert('Please refresh this page and try again');
      });
    },
    percentDiscount: function percentDiscount(quantity) {
      if ([2, 3].includes(quantity)) {
        return 4;
      }

      if ([4, 5, 6].includes(quantity)) {
        return 5;
      }

      if ([7, 8, 9].includes(quantity)) {
        return 6;
      }
    }
  },
  computed: {
    quantity: {
      // getter
      get: function get() {
        return this.item.quantity;
      },
      // setter
      set: function set(newValue) {
        var oldValue = this.item.quantity;

        if (newValue > 0) {
          this.item.quantity = newValue;
        } else {
          this.item.quantity = 1;
        }

        try {
          if (this.ajaxRequest !== null) {
            this.ajaxRequest.abort();
          }
        } catch (e) {}

        var self = this;
        this.ajaxRequest = $.ajax({
          url: '/cart/' + this.item.id,
          type: 'PUT',
          data: {
            quantity: newValue
          }
        }).done(function (data) {
          if (_typeof(data) === 'object' && 'maxQuantity' in data) {
            self.quantity = data.maxQuantity;
            alert(data.message);
          }

          console.log(data);
          self.$root.$emit('cartItemUpdated', self.item);
        }).fail(function (response) {
          try {
            alert(response.responseJSON.message);
            self.item.quantity = oldValue;
          } catch (e) {}
        });
      }
    }
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartItemComponent.vue?vue&type=template&id=23bc65b4&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/cart/CartItemComponent.vue?vue&type=template&id=23bc65b4& ***!
  \*************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, exports) {

throw new Error("Module build failed (from ./node_modules/vue-loader/lib/loaders/templateLoader.js):\nSyntaxError: Unexpected token (1:2855)\n    at Parser.pp$4.raise (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2757:13)\n    at Parser.pp.unexpected (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:647:8)\n    at Parser.pp$3.parseExprAtom (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2196:10)\n    at Parser.<anonymous> (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:6003:24)\n    at Parser.parseExprAtom (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:6129:31)\n    at Parser.pp$3.parseExprSubscripts (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2047:19)\n    at Parser.pp$3.parseMaybeUnary (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2024:17)\n    at Parser.pp$3.parseExprOp (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1985:41)\n    at Parser.pp$3.parseExprOp (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1987:19)\n    at Parser.pp$3.parseExprOps (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1968:91)\n    at Parser.pp$3.parseMaybeConditional (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1949:19)\n    at Parser.pp$3.parseMaybeAssign (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1925:19)\n    at Parser.pp$3.parseParenAndDistinguishExpression (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2238:30)\n    at Parser.pp$3.parseExprAtom (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2163:41)\n    at Parser.<anonymous> (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:6003:24)\n    at Parser.parseExprAtom (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:6129:31)\n    at Parser.pp$3.parseExprSubscripts (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2047:19)\n    at Parser.pp$3.parseMaybeUnary (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2024:17)\n    at Parser.pp$3.parseExprOps (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1966:19)\n    at Parser.pp$3.parseMaybeConditional (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1949:19)\n    at Parser.pp$3.parseMaybeAssign (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1925:19)\n    at Parser.pp$3.parseExprList (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2663:20)\n    at Parser.pp$3.parseExprAtom (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2175:26)\n    at Parser.<anonymous> (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:6003:24)\n    at Parser.parseExprAtom (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:6129:31)\n    at Parser.pp$3.parseExprSubscripts (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2047:19)\n    at Parser.pp$3.parseMaybeUnary (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2024:17)\n    at Parser.pp$3.parseExprOps (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1966:19)\n    at Parser.pp$3.parseMaybeConditional (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1949:19)\n    at Parser.pp$3.parseMaybeAssign (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1925:19)\n    at Parser.pp$3.parseExprList (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2663:20)\n    at Parser.pp$3.parseSubscripts (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2075:29)\n    at Parser.pp$3.parseExprSubscripts (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2050:21)\n    at Parser.pp$3.parseMaybeUnary (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:2024:17)\n    at Parser.pp$3.parseExprOps (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1966:19)\n    at Parser.pp$3.parseMaybeConditional (/Users/norredine/Code/rebel-smuggling/node_modules/vue-template-es2015-compiler/buble.js:1949:19)");

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

/***/ "./resources/js/components/cart/CartItemComponent.vue":
/*!************************************************************!*\
  !*** ./resources/js/components/cart/CartItemComponent.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CartItemComponent_vue_vue_type_template_id_23bc65b4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CartItemComponent.vue?vue&type=template&id=23bc65b4& */ "./resources/js/components/cart/CartItemComponent.vue?vue&type=template&id=23bc65b4&");
/* harmony import */ var _CartItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CartItemComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/cart/CartItemComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CartItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CartItemComponent_vue_vue_type_template_id_23bc65b4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _CartItemComponent_vue_vue_type_template_id_23bc65b4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/cart/CartItemComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/cart/CartItemComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/js/components/cart/CartItemComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CartItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/babel-loader/lib??ref--12-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./CartItemComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartItemComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CartItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/cart/CartItemComponent.vue?vue&type=template&id=23bc65b4&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/cart/CartItemComponent.vue?vue&type=template&id=23bc65b4& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CartItemComponent_vue_vue_type_template_id_23bc65b4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./CartItemComponent.vue?vue&type=template&id=23bc65b4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/cart/CartItemComponent.vue?vue&type=template&id=23bc65b4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CartItemComponent_vue_vue_type_template_id_23bc65b4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CartItemComponent_vue_vue_type_template_id_23bc65b4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);