(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["product-images-component"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/Carousel.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'Carousel',
  data: function data() {
    return {
      //Index of the active image
      activeImage: 0,
      //Hold the timeout, so we can clear it when it is needed
      autoSlideTimeout: null,
      //If the timer is stopped e.g. when hovering over the carousel
      stopSlider: false,
      //Hold the time left until changing to the next image
      timeLeft: 0,
      //Hold the interval so we can clear it when needed
      timerInterval: null,
      //Every 10ms decrease the timeLeft
      countdownInterval: 10
    };
  },
  computed: {
    // currentImage gets called whenever activeImage changes
    // and is the reason why we don't have to worry about the 
    // big image getting updated
    currentImage: function currentImage() {
      this.timeLeft = this.autoSlideInterval;
      return this.images[this.activeImage].big;
    },
    progressBar: function progressBar() {
      //Calculate the width of the progressbar
      return 100 - this.timeLeft / this.autoSlideInterval * 100;
    }
  },
  methods: {
    // Go forward on the images array 
    // or go at the first image if you can't go forward
    nextImage: function nextImage() {
      var active = this.activeImage + 1;

      if (active >= this.images.length) {
        active = 0;
      }

      this.activateImage(active);
    },
    // Go backwards on the images array 
    // or go at the last image
    prevImage: function prevImage() {
      var active = this.activeImage - 1;

      if (active < 0) {
        active = this.images.length - 1;
      }

      this.activateImage(active);
    },
    activateImage: function activateImage(imageIndex) {
      this.activeImage = imageIndex;
    },
    //Wait until 'interval' and go to the next image;
    startTimer: function startTimer(interval) {
      if (interval && interval > 0 && !this.stopSlider) {
        var self = this;
        clearTimeout(this.autoSlideTimeout);
        this.autoSlideTimeout = setTimeout(function () {
          self.nextImage();
          self.startTimer(self.autoSlideInterval);
        }, interval);
      }
    },
    //Stop the timer when hovering over the carousel
    stopTimer: function stopTimer() {
      clearTimeout(this.autoSlideTimeout);
      this.stopSlider = true;
      clearInterval(this.timerInterval);
    },
    //Restart the timer(with 'timeLeft') when leaving from the carousel
    restartTimer: function restartTimer() {
      this.stopSlider = false;
      clearInterval(this.timerInterval);
      this.startCountdown();
      this.startTimer(this.timeLeft);
    },
    //Start countdown from 'autoSlideInterval' to 0
    startCountdown: function startCountdown() {
      if (!this.showProgressBar) return;
      var self = this;
      this.timerInterval = setInterval(function () {
        self.timeLeft -= self.countdownInterval;

        if (self.timeLeft <= 0) {
          self.timeLeft = self.autoSlideInterval;
        }
      }, this.countdownInterval);
    }
  },
  created: function created() {
    //Check if startingImage prop was given and if the index is inside the images array bounds
    if (this.startingImage && this.startingImage >= 0 && this.startingImage < this.images.length) {
      this.activeImage = this.startingImage;
    } //Check if autoSlideInterval prop was given and if it is a positive number


    if (this.autoSlideInterval && this.autoSlideInterval > this.countdownInterval) {
      //Start the timer to go to the next image
      this.startTimer(this.autoSlideInterval);
      this.timeLeft = this.autoSlideInterval; //Start countdown to show the progressbar

      this.startCountdown();
    }
  },
  props: ['startingImage', 'images', 'autoSlideInterval', 'showProgressBar']
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/babel-loader/lib??ref--12-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/ProductImagesComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function($) {/* harmony import */ var _components_product_Carousel_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../components/product/Carousel.vue */ "./resources/js/components/product/Carousel.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'OtherComponent',
  // Include the Carousel here
  components: {
    Carousel: _components_product_Carousel_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: ['product'],
  data: function data() {
    return {
      selectedImage: null
    };
  },
  watch: {
    product: function product() {
      this.updateCurrentImage(this.product.main_image);
    }
  },
  mounted: function mounted() {
    this.updateCurrentImage(this.product.main_image);
  },
  methods: {
    updateCurrentImage: function updateCurrentImage(image) {
      console.log(image);

      if (image === undefined || image === null || image === '') {
        this.selectedImage = '/storage/notfound.jpg';
        return;
      }

      this.selectedImage = image.includes('storage/product-images/') === false ? '/storage/products/productImages/' + image : image;
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

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--7-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.card-carousel[data-v-49fa84eb] {\n    -webkit-user-select: none;\n       -moz-user-select: none;\n        -ms-user-select: none;\n            user-select: none;\n    position: relative;\n}\n.progressbar[data-v-49fa84eb] {\n    display: block;\n    width: 100%;\n    height: 5px;\n    position: absolute;\n    background-color: rgba(221, 221, 221, 0.25);\n    z-index: 1;\n}\n.progressbar > div[data-v-49fa84eb] {\n    background-color: rgba(255, 255, 255, 0.52);\n    height: 100%;\n}\n.thumbnails[data-v-49fa84eb] {\n    display: flex;\n    justify-content: space-evenly;\n    flex-direction: row;\n}\n.thumbnail-image[data-v-49fa84eb] {\n    display: flex;\n    align-items: center;\n    cursor: pointer;\n    padding: 2px;\n}\n.thumbnail-image > img[data-v-49fa84eb] {\n    width: 100%;\n    height: auto;\n    transition: all 250ms;\n}\n.thumbnail-image:hover > img[data-v-49fa84eb], \n.thumbnail-image.active > img[data-v-49fa84eb] {\n    opacity: 0.6;\n    box-shadow: 2px 2px 6px 1px rgba(0,0,0, 0.5);\n}\n.card-img[data-v-49fa84eb] {\n    position: relative;\n    margin-bottom: 15px;\n}\n.card-img > img[data-v-49fa84eb] {\n    display: block;\n    margin: 0 auto;\n}\n.actions[data-v-49fa84eb] {\n    font-size: 1.5em;\n    height: 40px;\n    position: absolute;\n    top: 50%;\n    margin-top: -20px;\n    width: 100%;\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    color: #585858;\n}\n.actions > span[data-v-49fa84eb] {\n    cursor: pointer;\n    transition: all 250ms;\n    font-size: 45px;\n}\n.actions > span.prev[data-v-49fa84eb] {\n    margin-left: 5px;\n}\n.actions > span.next[data-v-49fa84eb] {\n    margin-right: 5px;\n}\n.actions > span[data-v-49fa84eb]:hover {\n    color: #eee;\n}\n", ""]);

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--7-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--7-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--7-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=template&id=49fa84eb&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/product/Carousel.vue?vue&type=template&id=49fa84eb&scoped=true& ***!
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
  return _c(
    "div",
    {
      staticClass: "card-carousel",
      on: { mouseover: _vm.stopTimer, mouseleave: _vm.restartTimer }
    },
    [
      _vm.autoSlideInterval && _vm.showProgressBar
        ? _c("div", { staticClass: "progressbar" }, [
            _c("div", { style: { width: _vm.progressBar + "%" } })
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("div", { staticClass: "card-img" }, [
        _c("img", { attrs: { src: _vm.currentImage, alt: "" } }),
        _vm._v(" "),
        _c("div", { staticClass: "actions" }, [
          _c("span", { staticClass: "prev", on: { click: _vm.prevImage } }, [
            _vm._v("\n                ‹\n            ")
          ]),
          _vm._v(" "),
          _c("span", { staticClass: "next", on: { click: _vm.nextImage } }, [
            _vm._v("\n                ›\n            ")
          ])
        ])
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "thumbnails" },
        _vm._l(_vm.images, function(image, index) {
          return _c(
            "div",
            {
              key: image.id,
              class: [
                "thumbnail-image",
                _vm.activeImage == index ? "active" : ""
              ],
              on: {
                click: function($event) {
                  return _vm.activateImage(index)
                }
              }
            },
            [_c("img", { attrs: { src: image.thumb } })]
          )
        }),
        0
      )
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



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
  return _c(
    "div",
    { staticClass: "row justify-content-center align-items-center mx-auto" },
    [
      _c(
        "button",
        {
          staticClass: "btn btn-sm link--gallery-zoom",
          attrs: { title: "click to zoom" },
          on: {
            click: function($event) {
              return _vm.zoomImage()
            }
          }
        },
        [_c("i", { staticClass: "fa fa-search" })]
      ),
      _vm._v(" "),
      _vm._m(0),
      _vm._v(" "),
      _vm._m(1),
      _vm._v(" "),
      _vm.images.length > 0
        ? _c(
            "div",
            {
              staticClass:
                "ml-0 mr-0 ml-sm-3 mt-5 mr-sm-3 mt-lg-5 col-12 row d-flex justify-content-center"
            },
            [
              _c(
                "div",
                {
                  staticClass:
                    "border mb-3 product-small-image-container d-flex mx-3 align-items-center p-3",
                  on: {
                    click: function($event) {
                      return _vm.updateCurrentImage(_vm.product.main_image)
                    }
                  }
                },
                [
                  _c("img", {
                    staticClass: "m-auto w-100 d-block w-auto h-auto",
                    staticStyle: { "max-width": "35px", "max-height": "35px" },
                    attrs: { src: _vm.product.main_image }
                  })
                ]
              ),
              _vm._v(" "),
              _vm._l(_vm.images, function(image) {
                return _c(
                  "a",
                  {
                    staticClass:
                      "border mb-3 product-small-image-container d-flex mx-3 align-items-center justify-content-center p-3",
                    on: {
                      click: function($event) {
                        return _vm.updateCurrentImage(image.src)
                      }
                    }
                  },
                  [
                    _c("img", {
                      staticClass: "m-auto w-100 d-block w-auto h-auto",
                      staticStyle: {
                        "max-width": "35px",
                        "max-height": "35px"
                      },
                      attrs: {
                        src: "/storage/products/productImages/" + image.src
                      }
                    })
                  ]
                )
              })
            ],
            2
          )
        : _vm._e()
    ]
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "modal modal--zoom-component",
        attrs: {
          tabindex: "-1",
          "data-keyboard": "true",
          "aria-hidden": "true",
          id: "zoom-modal-component"
        }
      },
      [
        _c("div", { attrs: { role: "document" } }, [
          _c("div", { staticClass: "modal-content container my-4" }, [
            _c(
              "button",
              {
                staticClass: "close",
                attrs: {
                  type: "button",
                  "aria-label": "Close",
                  "data-dismiss": "modal"
                }
              },
              [_c("span", { attrs: { "aria-hidden": "true" } }, [_vm._v("×")])]
            ),
            _vm._v(" "),
            _c("div", {
              staticClass:
                "modal--zoom-component__img-wrapper d-flex align-items-center text-center p-5"
            })
          ])
        ])
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass:
          "col-12 text-center py-0 h-100 justify-content-center align-items-center d-flex w-100 min-h-100px min-h-sm-420px"
      },
      [
        _c("div", [
          _c("img", {
            staticClass:
              "img-fluid rounded product-img-responsive image--gallery--active",
            attrs: { src: "/images/tt-tiot-1494__1-300x300-resize.jpg" }
          })
        ])
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

/***/ "./resources/js/components/product/Carousel.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/product/Carousel.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Carousel_vue_vue_type_template_id_49fa84eb_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Carousel.vue?vue&type=template&id=49fa84eb&scoped=true& */ "./resources/js/components/product/Carousel.vue?vue&type=template&id=49fa84eb&scoped=true&");
/* harmony import */ var _Carousel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Carousel.vue?vue&type=script&lang=js& */ "./resources/js/components/product/Carousel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Carousel_vue_vue_type_style_index_0_id_49fa84eb_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css& */ "./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Carousel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Carousel_vue_vue_type_template_id_49fa84eb_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Carousel_vue_vue_type_template_id_49fa84eb_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "49fa84eb",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/product/Carousel.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/product/Carousel.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/product/Carousel.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/babel-loader/lib??ref--12-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Carousel.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_babel_loader_lib_index_js_ref_12_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css& ***!
  \***************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_style_index_0_id_49fa84eb_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--7-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--7-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=style&index=0&id=49fa84eb&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_style_index_0_id_49fa84eb_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_style_index_0_id_49fa84eb_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_style_index_0_id_49fa84eb_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_style_index_0_id_49fa84eb_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_style_index_0_id_49fa84eb_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/product/Carousel.vue?vue&type=template&id=49fa84eb&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/product/Carousel.vue?vue&type=template&id=49fa84eb&scoped=true& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_template_id_49fa84eb_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Carousel.vue?vue&type=template&id=49fa84eb&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/product/Carousel.vue?vue&type=template&id=49fa84eb&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_template_id_49fa84eb_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Carousel_vue_vue_type_template_id_49fa84eb_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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