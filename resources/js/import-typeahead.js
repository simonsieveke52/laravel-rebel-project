require('jquery');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
require('bootstrap');
require('corejs-typeahead');
window.Vue = require('vue');

if ($('#quote_form').length > 0) {
  Vue.component('quote-form', require('./components/quote/QuoteRequestFormComponent.vue').default);

  let quote_form = new Vue({
    el: '#quote_form',
  });
}
