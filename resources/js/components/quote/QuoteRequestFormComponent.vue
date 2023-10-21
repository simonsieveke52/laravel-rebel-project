<template>
  <div class="container mt-3" v-if="initialized">
    <div class="form-group col-md-12 w-100">
      <label class="text-dark font-weight-bold w-100">Full Name <span class="text-danger">*</span></label> <input
          type="text"
          required
          name="name"
          class="form-control w-100"
          v-model="quote.name">
    </div>
    <div class="form-group w-100 col-md-6">
      <label class="text-dark font-weight-bold w-100">Phone <span class="text-danger">*</span></label><input
          type="text"
          required
          name="phone"
          class="form-control w-100"
          v-model="quote.phone">
    </div>
    <div class="form-group w-100 col-md-6">
      <label class="text-dark font-weight-bold w-100">Email <span class="text-danger">*</span></label><input
          type="email"
          required
          name="email"
          class="form-control w-100"
          v-model="quote.email">
    </div>
    <div class="form-group col-md-12 w-100">
      <label class="text-dark font-weight-bold w-100">Address </label><input
          type="text"
          name="address"
          class="form-control w-100"
          v-model="quote.address_1">
    </div>
    <div class="form-group col-md-12 w-100">
      <label class="text-dark font-weight-bold w-100">Address 2 </label><input
          type="text"
          class="form-control w-100"
          placeholder="Apartment or Suite Number"
          v-model="quote.address_2">
    </div>
    <div class="form-group w-100 col-md-5">
      <label class="text-dark font-weight-bold w-100">City </label><input
          type="text"
          name="city"
          class="form-control w-100"
          v-model="quote.city">
    </div>
    <div class="form-group col-md-5">
      <label class="text-dark font-weight-bold w-100">State </label><select
          class="form-control w-100"
          name="state"
          v-model="quote.state">
        <option value="" disabled>-- Select --</option>
        <option
            v-for="state in states"
            :value="state.abv">{{ state.name }}
        </option>
      </select>
    </div>
    <div class="form-group w-100 col-md-2">
      <label class="text-dark font-weight-bold w-100">Zip Code <span class="text-danger">*</span></label><input
          name="zip"
          type="text"
          required
          class="form-control w-100"
          v-model="quote.zip">
    </div>
    <div class="form-group col-md-12 w-100">
      <label class="text-dark font-weight-bold w-100">Additional Information </label><textarea
          name="zip"
          type="text"
          required
          class="form-control w-100"
          v-model="quote.message"></textarea>
    </div>
    <div
        class="row p-3">
      <div class="col-md-12" v-if="quote.emails && quote.emails.length > 0">
        <div
            class="form-group card w-100">
          <div class="card-header">
            Emails Sent to Customer
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm mb-0">
                <thead>
                <tr>
                  <th scope="col">Details</th>
                  <th scope="col">URL</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="email in quote.emails">
                  <td style="vertical-align: middle;" nowrap="true">
                    <ul class="list-unstyled">
                      <li><strong>Email: <a :href="'mailto:'+email.sent_to">{{email.sent_to}}</a></strong></li>
                      <li>Sent At: {{email.sent_at}}</li>
                      <li>Expires: {{email.expires_at}}</li>
                    </ul>

                  </td>
                  <td>
                    <small><a :href="email.url">{{email.url}}</a></small>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <h6 class="float-right">Emails Sent: {{ quote.emails.length }}</h6>
          </div>
        </div>
      </div>
      <div class="col-md-12 pb-4">
        <div class="card w-100">
          <div class="card-header">Product Search</div>
          <div class="card-body row m-0">
            <div class="form-group col-md-10 mb-0">
              <div>
                <label class="w-100">Product </label><input
                    type="text"
                    autofocus
                    class="form-control productSearch w-100"
                    name="search"
                    id="bloodhound-admin"
                    v-model.trim="product"
                    placeholder="Search products..."
                    aria-label="Product Search"
                    autocomplete="off">
              </div>
            </div>
            <div class="form-group col-md-2 mb-0">
              <label class="w-100">Quantity </label><input
                  min="10"
                  v-model="quantity"
                  max="9999"
                  class="form-control w-100">
            </div>
          </div>
          <div class="card-footer">
            <button
                class="btn btn-highlight float-right"
                :disabled="!valid_product"
                :title="!this.valid_product ? this.product === null ? 'Product Required' : 'Quantity must be between 10 and 10000' : 'Quantity Required'"
                @click="addProduct()">Add to Quote
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div
            class="form-group card w-100"
            v-if="quote.products && quote.products.length > 0">
          <div class="card-header">
              Products in Quote
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm mb-0">
                <thead>
                <tr>
                  <th scope="col" style="width: 12%">Image</th>
                  <th scope="col" style="width: 33%">Product Details</th>
                  <th scope="col" style="width: 15%">Quantity</th>
                  <th scope="col" style="width: 20%">Discount & Shipping</th>
                  <th scope="col" style="width: 20%; text-align: right;">Pricing</th>
                  <th scope="col" style="width: 10%"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in quote.products">
                  <td scope="row" style="vertical-align: top !important; padding-top: 16px;">
                    <img
                        v-if="product.images !== undefined && product.images[0] !== undefined && product.images[0] !== null"
                        class="img-fluid"
                        style="min-height: 120px; width: auto; max-height:120px;"
                        :src="'/storage/products/productImages/'+product.images[0].src"
                        :alt="product.name"
                    >
                    <div v-else style="height: 120px; width: 120px; background-color: #bdbdbd;" class="rounded"></div>
                  </td>
                  <td style="vertical-align: top !important; padding-top: 24px;">
                    <ul class="list-unstyled m-0" style="max-width: 400px !important; overflow-wrap: break-word">
                      <li class="font-weight-bold">
                        {{ product.name }}
                      </li>
                      <li>
                        SKU: {{ product.sku }} | Vendor: {{ product.vendor_code }}
                      </li>
                      <li>
                        Sell Price: ${{ product.price.toFixed(2) }}
                      </li>
                      <li>
                        Retail Cost: ${{ product.cost.toFixed(2) }}
                      </li>
                    </ul>
                  </td>
                  <td style="vertical-align: top !important; padding-top: 24px;">
                    <input class="form-control" type="number" min="10" max="9999" v-model="product.pivot.quantity">
                  </td>
                  <td nowrap="true" style="vertical-align: top !important; padding-top: 24px;">
                    <div class="input-group">
                      <ul class="list-unstyled w-100">
                        <li>
                        <input type="text" @change="getLineTotal(product)" class="form-control" style="max-width:100px" v-model="product.pivot.discount_value" aria-label="Discount">
                        <div class="input-group-append">
                          <select @change="getLineTotal(product)" class="form-control" v-model="product.pivot.discount_type">
                            <option value="%">%</option>
                            <option value="$">$</option>
                          </select>
                        </div>
                        </li>
                        <li v-show="!shippingCostOverride">
                          <hr style="border-top: 1px solid #d6d6d6;">
                        </li>
                        <li v-show="!shippingCostOverride" class="text-right">
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" :id="'free-shipping-'+product.id" v-model="product.pivot.free_shipping">
                            <label class="custom-control-label" :for="'free-shipping-'+product.id">Free Shipping</label>
                          </div>
                          <div v-if="product.shippingCost &&  product.pivot.quantity">Shipping Estimate: ${{ getProductShippingCost(product).toFixed(2) }}</div>
                        </li>
                      </ul>

                    </div>
                  </td>
                  <td style="vertical-align: top !important; padding-top: 24px; text-align: right;" nowrap="true">
                    <div>
                      Regular: <span>${{ (product.price * product.pivot.quantity).toFixed(2) }}</span>
                      <hr>
                    </div>
                    <div v-if="product.pivot.discount_amount !== '0.00'">
                      Discount: <span class="text-success">${{product.pivot.discount_amount}}</span>
                      <hr>
                    </div>
                    <div>
                      Quote: <span>${{getLineTotal(product)}}</span>
                      <hr>
                    </div>
                  </td>
                  <td style="vertical-align: middle">
                    <button
                        class="btn btn-dark"
                        @click="removeProduct(product.sku)"><i class="voyager-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td colspan="3" style="vertical-align: bottom !important;"><h4 class="m-0">Total:</h4></td>
                  <td class="text-right" style="vertical-align: bottom !important;">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="shipping-cost-override" v-model="shippingCostOverride">
                      <label class="custom-control-label" for="shipping-cost-override">Shipping Cost Override</label>
                    </div>
                    <div v-if="shippingCostOverride">
                      <input type="text"
                            @change="updateShippingCost"
                            class="form-control text-right"
                            v-model="quote.shipping_cost"
                            aria-label="shipping cost overrride">
                    </div>
                  </td>
                  <td style="vertical-align: bottom !important;">
                    <div class="row">
                      <div class="col-sm-7 mb-0 pr-1">Subtotal:</div>
                      <div class="col-sm-5 mb-0 text-right pl-0">${{ subtotal }}</div>
                    </div>
                    <div class="row">
                      <div class="col-sm-7 mb-0 pr-1">Shipping {{ shippingCostOverride ? 'Cost' : '(Est)' }}:</div>
                      <div class="col-sm-5 mb-0 text-right pl-0">
                        <span v-if="shippingCostOverride && Number(quote.shipping_cost) > -1">
                          {{ Number(quote.shipping_cost) === 0 ? 'FREE' : '$' + Number(quote.shipping_cost).toFixed(2) }}
                        </span>
                        <span v-else-if="!shippingCostOverride">
                          {{ Number(shippingCostEstimate) === 0 ? 'FREE' : '$' + shippingCostEstimate }}
                        </span>
                      </div>
                    </div>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group col-md-12">
        <button
            class="btn btn-lg btn-highlight float-right"
            style="text-decoration: none"
            :disabled="!valid_form"
            @click="submitForm">Send Quote to Customer
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "QuoteRequestFormComponent",
  props: {
    quoteId:     { type: Number, default: null },
    submitUrl:   { type: String },
    redirectUrl: { type: String },
  },
  data() {
    return {
      product: null,
      quantity: 10,
      search: null,
      suggestions: [],
      quote: {
        status: 'New',
        name: '',
        email: '',
        phone: '',
        address_1: '',
        address_2: '',
        city: '',
        state: '',
        zip: '',
        message: '',
        products: [],
        emails: [],
        shipping_cost: null
      },
      shippingCostOverride: false,
      initialized: false,
      states: {},
    }
  },
  created() {
    if (this.quoteId !== null) {
      this.getQuote();
    } else {
      this.initialized = true;
    }

    this.getStates();

    this.$nextTick(() => {
      $('#bloodhound-admin').typeahead({
        minLength: 2,
        highlight: true,
        hint: true,
      },
        {
          name: 'Product',
          limit: 20,
          display: function (item) {
            if (item && item.sku) {
              return item.sku
            }
            return ''
          },
          source: function (query, syncResults, asyncResults) {
            axios.get('/quoterequest/search', {
              responseType: 'json',
              params: {
                query: query
              }
            })
              .then((response) => {
                asyncResults(response.data);
              });
          },
          templates: {
            empty: '<div class="border p-3 rounded"><p>No products found</p></div>',
            pending: '<div class="m-3">Searching...<i class="ml-1 fa fa-spinner fa-spin"></i></div>',
            suggestion: (data) => {
              return `<div class="row m-0 hover-bg border-radius-0 cursor-pointer border-black justify-content-around">
                        <div class="col-4 m-0 align-self-center">
                            <img class="img-fluid" style="min-height: 120px; max-height:120px; width: auto;" src="${data['image']}">
                        </div>
                        <div class="col-6 align-self-center">
                            <h6>${data['name']}</h6>
                            <p>${data['sku']}</p>
                        </div>
                        <div class="col-2 align-self-center">
                            <p>$ ${data['price']}</p>
                        </div>
                    </div>`
            }
          }
        }
      ).on('typeahead:select', (e, item) => {
        this.product = item;
      });
    })
  },
  methods: {
    getStates() {
      axios.get('/api/country/1/state')
          .then((response) => {
            this.states = response.data;
          })
          .catch((error) => {
            console.log(error);
          });
    },
    getQuote() {
      axios.get('/fme-admin/quotes/'+this.quoteId)
          .then((response) => {
            this.quote = response.data;
            this.initialized = true;
            if (this.quote.shipping_cost !== null) {
              this.quote.shipping_cost = Number(this.quote.shipping_cost).toFixed(2)
              this.shippingCostOverride = true
            }
          })
          .catch((error) => {
            console.log(error);
          });
    },
    getLineTotal(product){
      let pivot = product.pivot;
      if(pivot.discount_type && pivot.discount_value){
        if(pivot.discount_value > (pivot.price * pivot.quantity)){
          pivot.discount_value = (pivot.price * pivot.quantity).toFixed(2);
        }
        if(pivot.discount_type === '%'){
          if(pivot.discount_value > 100){
            pivot.discount_value = 100
          }
          pivot.discount_amount = ((pivot.price * pivot.quantity).toFixed(2) - ((pivot.price * pivot.quantity) * ( 1 - (pivot.discount_value / 100))).toFixed(2)).toFixed(2);
          return ((pivot.price * pivot.quantity) * ( 1 - (pivot.discount_value / 100))).toFixed(2);
        }
        else{
          pivot.discount_amount = ((pivot.price * pivot.quantity).toFixed(2) - ((pivot.price * pivot.quantity) - pivot.discount_value).toFixed(2)).toFixed(2);
          return ((pivot.price * pivot.quantity) - pivot.discount_value).toFixed(2);
        }
      }
      return (pivot.price * pivot.quantity).toFixed(2);
    },
    addProduct() {
      this.product.pivot.quantity = this.quantity;
      this.product.pivot.discount_value = 0.00;
      this.product.pivot.discount_type = '%';
      this.quote.products.push(this.product);
      this.product = null;
      this.quantity = 10;
    },
    removeProduct(product) {
      this.quote.products.splice(this.quote.products.map(function (item) {
        return item.sku;
      }).indexOf(product), 1);
    },
    submitForm() {
      if (this.valid_form) {
        if (! this.shippingCostOverride) {
          this.quote.shipping_cost = null
        } else if (this.shippingCostOverride && this.quote.shipping_cost == null) {
          this.quote.shipping_cost = 0
        }
        axios.post(this.submitUrl, { data: this.quote, _method: 'put' })
          .then(() => {
            this.$nextTick(() => window.location.href = this.redirectUrl)
          })
          .catch(error => console.log(error));
      }
    },
    getProductShippingCost(product) {
      if (product.pivot.free_shipping) {
        return 0;
      }
      return Number(product.shippingCost * Number(product.pivot.quantity))
    },
    updateShippingCost() {
      if (isNaN(Number(this.quote.shipping_cost))) {
        this.quote.shipping_cost = null
      }
    }
  },
  computed: {
    valid_form() {
      return (this.quote.name !== null && this.quote.name !== "") && (this.quote.phone !== null && this.quote.phone !== "") && (this.quote.email !== null && this.quote.email !== "") && (this.quote.zip !== null && this.quote.zip !== "") && this.quote.products.length > 0;
    },
    valid_product() {
      return this.product != null && this.quantity != null && this.quantity > 9 && this.quantity < 10000;
    },
    subtotal() {
      return this.quote.products.reduce(function (a, b) {
        return a + ((b.pivot.quantity * b.pivot.price) - b.pivot.discount_amount);
      }, 0).toFixed(2);
    },
    shippingCostEstimate() {
      return this.quote.products.reduce((carry, product) => {
        return carry + this.getProductShippingCost(product)
      }, 0).toFixed(2);
    },
  }
}
</script>

<style scoped>
.mt-3 {
  margin-top: 2rem;
}
.w-100 {
  width: 100%;
}
</style>
