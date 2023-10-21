(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["product-images-component"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function($) {/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-slick-carousel */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.umd.js");
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-slick-carousel/dist/vue-slick-carousel.css */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.css");
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
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
  components: {
    VueSlickCarousel: vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: ['product'],
  data: function data() {
    return {
      selectedImage: null,
      items: [1, 2, 3, 4, 5]
    };
  },
  watch: {
    product: function product() {
      this.updateCurrentImage(this.product.images[0].src);
    }
  },
  mounted: function mounted() {
    this.updateCurrentImage(this.product.images[0].src);
  },
  methods: {
    updateCurrentImage: function updateCurrentImage(image) {
      if (image === undefined || image === null || image === '') {
        this.selectedImage = '/storage/notfound.jpg';
        return;
      }

      this.selectedImage = image.includes('storage/product-images/') === false ? '/images/' + image : image;
      return;
    },
    zoomImage: function zoomImage() {
      var $zoomContainer = $('#zoom-modal-component');
      var $imgWrapper = $zoomContainer.find('.modal--zoom-component__img-wrapper');
      $zoomContainer.modal();
      $imgWrapper.append('<img src="' + this.selectedImage + '" class="img-fluid d-block m-auto" alt="full sized image" />');
      $('#zoom-modal-component').on('hide.bs.modal', function (e) {
        $imgWrapper.empty();
      });
    }
  },
  computed: {
    images: function images() {
      var images = [];

      try {
        if (this.product.images && this.product.images.length > 1) {
          images = this.product.images.filter(function (img) {
            return img.is_main == 0;
          });
        }
      } catch (e) {}

      return images;
    },
    mainImage: function mainImage() {
      if (this.images.length === 0) {
        return undefined;
      }

      var images = this.images.filter(function (img) {
        return img.is_main == 1;
      });

      if (images.length === 0) {
        return this.images[0];
      }

      return images[0];
    }
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".link--gallery-zoom[data-v-6d6bd87e] {\n  position: absolute;\n  top: 1rem;\n  right: 1rem;\n  color: #222;\n  font-size: 1.25rem;\n  z-index: 90;\n}\n.product-small-image-container[data-v-6d6bd87e] {\n  opacity: 0.8;\n  cursor: pointer;\n  transition: all 0.4s ease;\n}\n.product-small-image-container[data-v-6d6bd87e]:hover {\n  opacity: 1;\n}\n.modal--zoom-component .modal-content img[data-v-6d6bd87e], .modal--zoom-component__img-wrapper img[data-v-6d6bd87e] {\n  -o-object-fit: contain;\n     object-fit: contain;\n  height: 100%;\n}\n.modal--zoom-component .close[data-v-6d6bd87e] {\n  width: 25px;\n  height: 25px;\n  border-radius: 50%;\n  background: #f1f1f1;\n  position: absolute;\n  right: 1rem;\n  top: 1rem;\n  transition: all 0.4s ease;\n}\n.modal--zoom-component .close[data-v-6d6bd87e]:hover {\n  background: #e5e5e5;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--8-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************/
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
    _c(
      "div",
      [
        _c(
          "VueSlickCarousel",
          { ref: "c1", attrs: { asNavFor: _vm.$refs.c2, focusOnSelect: true } },
          _vm._l(_vm.items, function(item, index) {
            return _c("div", { key: index }, [_c("h1", [_vm._v(_vm._s(item))])])
          }),
          0
        )
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "div",
      [
        _c(
          "VueSlickCarousel",
          { ref: "c2", attrs: { asNavFor: _vm.$refs.c1, focusOnSelect: true } },
          _vm._l(_vm.items, function(item, index) {
            return _c("div", { key: index }, [_c("h1", [_vm._v(_vm._s(item))])])
          }),
          0
        )
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/product/ProductImagesComponent.vue":
/*!********************************************************************!*\
  !*** ./resources/js/components/product/ProductImagesComponent.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProductImagesComponent_vue_vue_type_template_id_6d6bd87e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true& */ "./resources/js/components/product/ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true&");
/* harmony import */ var _ProductImagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProductImagesComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ProductImagesComponent_vue_vue_type_style_index_0_id_6d6bd87e_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true& */ "./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ProductImagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProductImagesComponent_vue_vue_type_template_id_6d6bd87e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProductImagesComponent_vue_vue_type_template_id_6d6bd87e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "6d6bd87e",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/product/ProductImagesComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/babel-loader/lib??ref--12-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductImagesComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true&":
/*!******************************************************************************************************************************!*\
  !*** ./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_style_index_0_id_6d6bd87e_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--8-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=style&index=0&id=6d6bd87e&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_style_index_0_id_6d6bd87e_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_style_index_0_id_6d6bd87e_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_style_index_0_id_6d6bd87e_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_style_index_0_id_6d6bd87e_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_style_index_0_id_6d6bd87e_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/product/ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/product/ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true& ***!
  \***************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_template_id_6d6bd87e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=template&id=6d6bd87e&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_template_id_6d6bd87e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductImagesComponent_vue_vue_type_template_id_6d6bd87e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);