require('es6-promise').polyfill();
window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');
require('bootstrap');
require('busy-load');
require('corejs-typeahead');

window.axios = require('axios');

window.Vue = require('vue');

window.rateYo = require('rateyo');

require('history.js/history.js');
require('history.js/history.adapter.ender.js');

require('./shared.js')

jQuery(window).bind("pageshow", function(event) {
    if (event.originalEvent.persisted) {
       window.location.reload();
    }
});

prepareAjaxHeader()

let numeral = require('numeral');

Vue.component(
    'product-nutrition',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-nutrition" */
        './components/product/ProductNutrition'
    )
);

Vue.component(
    'product-cart-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-cart-component" */
        './components/product/ProductCartComponent'
    )
);

Vue.component(
    'product-images-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-images-component" */
        './components/product/ProductImagesComponent'
    )
);

Vue.component(
    'products-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "products-component" */
        './components/product/ProductsComponent'
    )
);

Vue.component(
    'quote-request',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-component" */
        './components/quote/QuoteRequestComponent'
        )
);

Vue.component(
    'product-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-component" */
        './components/product/ProductComponent'
    )
);

Vue.component(
    'product-page-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-page-component" */
        './components/product/ProductPageComponent'
    )
);

Vue.component(
    'product-modal-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "product-modal-component" */
        './components/product/ProductModalComponent'
    )
);

Vue.component(
    'add-to-favorites',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "add-to-favorites" */
        './components/product/AddToFavorites'
    )
);

Vue.component(
    'favorites-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "favorites-component" */
        './components/product/FavoritesComponent'
    )
);

Vue.component(
    'add-to-cart-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "add-to-cart-component" */
        './components/cart/AddToCartComponent'
    )
);

Vue.component(
    'cart-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "cart-component" */
        './components/cart/CartComponent'
    )
);

Vue.component(
    'cart-item-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "cart-item-component" */
        './components/cart/CartItemComponent'
    )
);

Vue.component(
    'cart-overview-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "cart-overview-component" */
        './components/cart/CartOverviewComponent'
    )
);

Vue.component(
    'coupon-code-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "coupon-code-component" */
        './components/cart/CouponCodeComponent'
    )
);

Vue.component(
    'pagination',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "laravel-vue-pagination" */
        'laravel-vue-pagination'
    )
);

Vue.component(
    'money-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "money-component" */
        './components/general/MoneyComponent'
    )
);

Vue.component(
    'address-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "address-component" */
        './components/address/AddressComponent'
    )
);

Vue.component(
    'city-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "city-component" */
        './components/address/CityComponent'
    )
);

Vue.component(
    'state-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "state-component" */
        './components/address/StateComponent'
    )
);

Vue.component(
    'shipping-options-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "shipping-options-component" */
        './components/shipping/ShippingOptionsComponent'
    )
);

Vue.component(
    'checkout-component',
    () => import(
        /* webpackPrefetch: true */
        /* webpackChunkName: "shipping-options-component" */
        './components/checkout/CheckoutComponent'
    )
);

Vue.filter('currency', function (value) {
	return numeral(value).format('$0,0.00')
})

Vue.filter('strtolower', function (value) {
    try {
        return value.toLowerCase()
    } catch (e) {
        return value
    }
})

Vue.filter('truncate', window.truncate)

import VueLazyload from 'vue-lazyload'
Vue.use(VueLazyload)

import route from 'ziggy'
import { Ziggy } from './routes.js'

window.route = route;
window.Ziggy = Ziggy;

Vue.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    }
});

window.app = new Vue({
    el: '#app',
    mounted() {

        let self = this

        $(function() {

            if ($('#categories-filter-container').find('ul').length > 1) {
                $('#categories-filter-container').find('ul').last().find('a').addClass('text-red')
            }

            let mainDocumentTitle = document.title

            History.Adapter.bind(window, 'statechange', function(){

                var state = History.getState()

                try {

                    if (state.data.state === "pagination") {

                        self.$emit(state.data.event, state.data.page)

                    } else {

                        var item = $('.nav-item[data-slug="'+ state.data.slug +'"]')

                        document.title = mainDocumentTitle

                        if (state.data.slug === '') {

                            browseCategory(item, state)
                            return true;

                        } else if (item.length == 0) {
                            item = $('[data-slug="'+ state.data.slug +'"]')
                            if( item.length == 0 ){
                                location.reload()
                                return false
                            }
                        }

                        browseCategory(item, state)
                    }
                } catch (e) {
                }
            })

            setTimeout(function() {
                  $('[data-toggle="tooltip"]').tooltip()
            }, 500)
        })
    },
    methods: {
        toggleElement(elm) {
            if ($(elm).hasClass('open')) {
                $('body').css('overflow-y', 'auto')
                $(elm).removeClass('open')
            } else {
                $('body').css('overflow-y', 'hidden')
                $(elm).removeClass('open').addClass('open')
            }
        }
    }
});

$('.quote-typeahead').typeahead({
    highlight: true,
    hint: true,
    minLength: 2
},
{
    name: 'Product',
    limit: Infinity,
    display: 'name',
    source: function (query,syncResults,asyncResults) {
        $.get('/quoterequest/search', {
            responseType: 'json',
            params: {
                query: query
            }
        }).then(function (response) {
            asyncResults(response.data);
        });
    },
    templates: {
        empty: '<div class="bg-white border p-3 rounded"><p>No results found</p></div>',
        pending: '<div>Searching...</div>',
        suggestion: function(data) {
            return `<div class="row hover-bg border-radius-0 cursor-pointer bg-white border-secondary2 justify-content-around">
                <div class="col-4 m-0 align-self-center">
                    <img class="img-fluid" style="min-height: 120px; width: auto;" src="${ data['image']}">
                </div>
                <div class="col-8 pl-0 align-self-center">
                    <h6>${ data['name'] }</h6>
                </div>
            </div>`;
        }
    }
});
