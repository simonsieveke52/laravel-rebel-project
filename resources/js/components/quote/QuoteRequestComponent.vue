<template>
  <div class="container mt-3">
    <div
        v-if="!submitted"
        class="row p-3">
      <div class="col-8">
        <h1 class="mb-4">Quote Request</h1>
      </div>
      <div class="alert" v-if="errors.length">
        <b>Please correct the following error(s):</b>
      <ul>
        <li v-for="error in errors">{{ error }}</li>
      </ul>
      </div>
      <div class="col-12">
        <p>Thank you for your interest in buying in bulk. Please add the products you are interested in along with the
           quantity and some contact information.</p>
        <p> Once your quote has been submitted, a sales representative will contact you in <strong>1-2 business
                                                                                                   days</strong>. For
            immediate assistance email us at <a href="mailto:support@rebelsmuggling.com">support@rebelsmuggling.com</a>
        </p>
      </div>
      <div class="col-12 pb-4">
        <div class="card w-100">
          <div class="card-header">Product Search</div>
          <div class="card-body row m-0">
            <div class="form-group col-md-10 col-12 mb-0">
              <div>
                <label class="w-100">Product <input
                    type="text"
                    autofocus
                    class="form-control productSearch w-100"
                    name="search"
                    id="bloodhound"
                    v-model.trim="product"
                    placeholder="Search products..."
                    aria-label="Product Search"
                    autocomplete="off"> </label>
              </div>
            </div>
            <div class="form-group col-md-2 col-12 mb-0">
              <label class="w-100">Quantity <input
                  min="10"
                  v-model="quantity"
                  max="9999"
                  class="form-control w-100"> </label>
            </div>
          </div>
          <div class="card-footer custom-control">
            <button
                class="btn btn-highlight float-right"
                :disabled="!valid_product"
                :title="!this.valid_product ? this.product === null ? 'Product Required' : 'Quantity must be between 10 and 10000' : 'Quantity Required'"
                @click="addProduct()">Add to Quote
            </button>

          </div>
        </div>
      </div>
      <div class="col-12">
        <div
            class="form-group card w-100"
            v-if="form.products.length > 0">
          <div class="card-header">
            <h6 class="card-title">
              Products in Quote </h6>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm mb-0">
                <thead>
                <tr>
                  <th scope="col">Image</th>
                  <th scope="col">Product Name / Sku</th>
                  <th scope="col">Quantity</th>
                  <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in form.products">
                  <th scope="row">
                    <img
                        class="img-fluid"
                        style="min-height: 120px; width: auto; max-height:120px"
                        :src="product.image"
                        :alt="product.name">
                  </th>
                  <td style="vertical-align: middle">
                    <ul class="list-unstyled">
                      <li class="font-weight-bold">
                        {{ product.name }}
                      </li>
                      <li>
                        {{ product.sku }}
                      </li>
                    </ul>
                  </td>
                  <td style="vertical-align: middle">
                    <input class="form-control w-100" type="number" min="10" max="9999" v-model="product.quantity">
                  </td>
                  <td style="vertical-align: middle">
                    <button
                        class="btn btn-dark"
                        @click="removeProduct(product.sku)"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group col-12">
        <label class="text-dark font-weight-bold w-100">Full Name <span class="text-danger">*</span> <input
            type="text"
            required
            name="name"
            class="form-control w-100"
            v-model="form.name"> </label>
      </div>
      <div class="form-group col-12 col-md-6">
        <label class="text-dark font-weight-bold w-100">Phone <span class="text-danger">*</span><input
            type="text"
            required
            name="phone"
            class="form-control w-100"
            v-model="form.phone"> </label>
      </div>
      <div class="form-group col-12 col-md-6">
        <label class="text-dark font-weight-bold w-100">Email <span class="text-danger">*</span><input
            type="email"
            required
            name="email"
            class="form-control w-100"
            v-model="form.email"> </label>
      </div>
      <div class="form-group col-12">
        <label class="text-dark font-weight-bold w-100">Address <input
            type="text"
            name="address"
            class="form-control w-100"
            v-model="form.address_1"> </label>
      </div>
      <div class="form-group col-12">
        <label class="text-dark font-weight-bold w-100">Address 2 <input
            type="text"
            class="form-control w-100"
            placeholder="Apartment or Suite Number"
            v-model="form.address_2"> </label>
      </div>
      <div class="form-group col-12 col-md-5">
        <label class="text-dark font-weight-bold w-100">City <input
            type="text"
            name="city"
            class="form-control w-100"
            v-model="form.city"> </label>
      </div>
      <div class="form-group col-12 col-md-5">
        <label class="text-dark font-weight-bold w-100">State <select
            class="form-control w-100"
            name="state"
            v-model="form.state">
          <option value="">-- Select --</option>
          <option
              v-for="state in states"
              :value="state.abv">{{ state.name }}
          </option>
        </select> </label>
      </div>
      <div class="form-group col-12 col-md-2">
        <label class="text-dark font-weight-bold w-100">Zip Code <span class="text-danger">*</span><input
            name="zip"
            type="text"
            required
            class="form-control w-100"
            v-model="form.zip"> </label>
      </div>
      <div class="form-group col-12">
        <label class="text-dark font-weight-bold w-100">Additional Information <textarea
            name="zip"
            type="text"
            required
            class="form-control w-100"
            v-model="form.message"></textarea> </label>
      </div>
      <div class="col-8">
        <p> Once your quote has been submitted, a sales representative will contact you in <strong>1-2 business
                                                                                                   days</strong>. For
            immediate assistance email us at <a href="mailto:support@rebelsmuggling.com">support@rebelsmuggling.com</a>
        </p>
      </div>
      <div class="form-group col-4">
        <button
            class="btn btn-lg btn-highlight float-right"
            :disabled="!valid_form"
            @click="submitForm()">Submit Quote Request
        </button>
      </div>
    </div>
    <div v-else>
      <div class="col-8">
        <h1 class="mb-4">Request Successful</h1>
      </div>
      <div class="col-12">
        <p>Thank you for your interest in buying bulk. Your quote has been successfully submitted. A sales
           representative will contact you in <strong>1-2 business days</strong>. For immediate assistance email us at
          <a href="mailto:support@rebelsmuggling.com">support@rebelsmuggling.com</a>
        </p>
        <a class="btn btn-highlight float-right" href="/">Continue Shopping</a>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "QuoteRequestComponent",
  props: {
    submitUrl: '',
    productRequest: '',
  },
  data() {
    return {
      submitted: false,
      errors: [],
      form: {
        name: null,
        email: null,
        phone: null,
        address_1: null,
        address_2: null,
        city: null,
        state: null,
        state_id: null,
        zip: null,
        products: [],
        message: null,
      },
      formDefault: {
        name: null,
        email: null,
        phone: null,
        address_1: null,
        address_2: null,
        city: null,
        state: null,
        state_id: null,
        zip: null,
        products: [],
        message: null,
      },
      product: null,
      quantity: 10,
      search: null,
      suggestions: [],
      states: {},
      reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
    }
  },
  mounted() {
    this.getStates();
    $('#bloodhound').typeahead({
          minLength: 2,
          highlight: true,
          hint: true,
        },
        {
          name: 'Product',
          limit: 20,
          display: function (item) {
            return item.sku
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
    if(this.productRequest !== ''){
      this.product = JSON.parse(this.productRequest)
      this.product.quantity = this.quantity;
      this.product.total = this.product.price * this.product.quantity;
      this.form.products.push(this.product);
      this.product = null;
      this.quantity = 10;
    }
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
    addProduct() {
      this.product.quantity = this.quantity;
      this.product.total = this.product.price * this.product.quantity;
      this.form.products.push(this.product);
      this.product = null;
      this.quantity = 10;
    },
    removeProduct(product) {
      this.form.products.splice(this.form.products.map(function (item) {
        return item.sku;
      }).indexOf(product), 1);
    },
    submitForm() {
      if (this.valid_form) {
        axios.post(this.submitUrl, this.form)
            .then(() => {
              this.$nextTick(() => {
                this.form = Object.assign({}, this.formDefault);
                this.submitted = true;
              })
            })
            .catch((error) => {
              console.log(error);
            });
      }
      this.errors = [];
      if(!this.form.name && this.form.name !== ""){
        this.errors.push('Name required.');
      }
      if(!this.form.phone && this.form.phone !== ""){
        this.errors.push('Phone required.');
      }
      if(!this.form.email && this.form.email !== ""){
        this.errors.push('Email required.');
      }
      if(!this.form.zip && this.form.zip !== ""){
        this.errors.push('Zip Code required.');
      }
      if(this.email && !this.form.valid_email){
        this.errors.push('Valid Email required.');
      }
    },
  },
  computed: {
    valid_form() {
      return (this.form.name !== null && this.form.name !== "") && (this.form.phone !== null && this.form.phone !== "") && (this.form.email !== null && this.form.email !== "") && (this.form.zip !== null && this.form.zip !== "") && this.valid_email && this.form.products.length > 0;
    },
    valid_product() {
      return this.product != null && this.quantity != null && this.quantity > 9 && this.quantity < 10000;
    },
    valid_email() {
      return this.reg.test(this.form.email);
    },
    subtotal() {
      return this.form.products.reduce(function (a, b) {
        return a + b['total']
      }, 0).toFixed(2);
    }
  }
}
</script>
<style scoped></style>