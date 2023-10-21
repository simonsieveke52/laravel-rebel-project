<template>
	<form
      id="checkout"
	    method="POST"
	    class="form mt-4 form--checkout needs-validation jq-checkout-form mb-3"
      :class="{ 'was-validated': hasErrors }"
	    novalidate=""
      >
	    <div class="container">
	        <input type="hidden" name="_token" v-model="form.csrf">
	        <input type="hidden" name="payment_method" v-model="form.payment_method">

          <div v-if="alert !== ''" class="row mb-3 mb-lg-4">
            <div class="col-12 col-md-10 col-lg-9 col-xl-8 mx-auto alert alert-danger alert-dismissible mb-0 border-radius-0">
              <button @click="alert = ''" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ alert }}
            </div>
          </div>

	        <div class="row">

            <div class="col-12 order-xl-1"
                 :class="{ 'col-md-7' :leadCaptured, 'col-md-11 col-lg-10 col-xl-8 mx-auto': !leadCaptured }">

	                <h4 class="d-flex justify-content-between align-items-center mb-3">
	                    <span class="text-dark font-weight-bold">Personal information</span>
	                </h4>

	                <div class="mb-4">
	                	<div class="row">
	                		<div class="col-md-6">
	                			<div class="form-group">
	                				<label class="font-weight-bold text-dark" for="first_name">First Name</label>
			                        <input
			                            type="text"
			                            class="form-control"
			                            id="first_name"
			                            placeholder="Enter your first name"
                                  v-model="form.contactInfo.first_name"
			                            name="first_name"
			                            required=""
			                        >

                              <div v-if="hasErrors && validationErrors.first_name" class="invalid-feedback d-block">
                                {{ validationErrors.first_name | formatError }}
                              </div>
	                			</div>
	                		</div>
	                		<div class="col-md-6">
	                			<div class="form-group">
	                				<label class="font-weight-bold text-dark" for="last_name">Last Name</label>
			                        <input
			                            type="text"
			                            class="form-control"
			                            id="last_name"
			                            placeholder="Enter your last name"
                                  v-model="form.contactInfo.last_name"
			                            name="last_name"
			                            required=""
			                        >

                              <div v-if="hasErrors && validationErrors.last_name" class="invalid-feedback d-block">
                                {{ validationErrors.last_name | formatError }}
                              </div>
	                			</div>
	                		</div>
	                		<div v-if="leadCaptured" class="col-md-6 col-lg-6">
	                			<div class="form-group">
		                            <label class="font-weight-bold text-dark" for="phone">
                                  Phone <small class="text-dark">(required)</small>
                                </label>
		                            <input
		                                type="tel"
		                                class="form-control"
		                                id="phone"
		                                placeholder="3105551212"
		                                required=""
		                                name="phone"
                                    v-model="form.contactInfo.phone"
		                            >

                              <div v-if="validationErrors.phone" class="invalid-feedback d-block">
                                {{ validationErrors.phone | formatError }}
                              </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                              <label class="font-weight-bold text-dark" for="email">Email <small class="text-dark">(required)</small></label>
                              <input
                                  type="email"
                                  class="form-control"
                                  id="email"
                                  placeholder="email@example.com"
                                  required=""
                                  name="email"
                                  v-model="form.contactInfo.email"
                              >

                            <div v-if="validationErrors.email" class="invalid-feedback d-block">
                              {{ validationErrors.email | formatError }}
                            </div>
                          </div>
                        </div>
                        <div :class="leadCaptured ? 'col-xl-6' : 'col-md-6'">
                          <div class="form-group">
                              <label class="text-dark font-weight-bold" for="origin_id">What best describes you/your industry? <small class="text-dark">(required)</small></label>
                              <select
                                id="origin_id"
                                name="origin_id"
                                class="custom-select"
                                :class="{ 'placeholder': form.contactInfo.origin_id === '' }"
                                width="100%"
                                v-model="form.contactInfo.origin_id"
                                required=""
                                >
                                <option value="" disabled>-- Select --</option>
                                <option v-for="origin in orderOrigins" :value="origin.id">{{ origin.name }}</option>
                              </select>
                              <div v-if="validationErrors.origin_id" class="invalid-feedback d-block">
                                {{ validationErrors.origin_id | formatError }}
                              </div>
                          </div>
                        </div>
                    </div>

                    <div class="row mt-2" v-if="!leadCaptured">
                      <div class="col-12 text-right">
                        <button @click="captureLead" type="button" class="btn btn-highlight py-2 px-5">Continue</button>
                      </div>
                    </div>
	                </div>

                  <div v-if="leadCaptured">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-3 font-weight-bold text-dark">Billing address</h4>
                        </div>
                    </div>

                    <div id="billing-address">
                      <address-component
                        :address="form.billingAddress"
                        :on-checkout="true"
                        address-type="billing"
                        :errors="[]"
                        >
                        </address-component>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <hr class="mb-4">
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox mb-4">
                        <input type="hidden" name="shipping_address_different" value="false" >
                        <input
                            type="checkbox"
                            class="custom-control-input"
                            id="shipping_address_different"
                            name="shipping_address_different"
                            value="true"
                            v-model="form.shipping_address_different"
                            @change="toggleShippingAddress"
                        >
                        <label class="custom-control-label text-dark" for="shipping_address_different">
                            Shipping Address is different from billing address
                        </label>
                    </div>

                    <div id="shipping-address" style="display: none;">
                        <div class="border-secondary rounded alert bg-light mb-5">
                            <address-component
                                :address="form.shippingAddress"
                                :on-checkout="true"
                                address-type="shipping"
                                :errors="errors"
                            >
                            </address-component>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                        <div class="payment rounded-lg bg-lighter shadow p-4 border border-secondary mb-5 mt-4 mt-lg-0" id="credit-card-container">
                            <div class="mb-0">
                                <div class="row">
                                    <div class="col-xl-12 mb-3">
                                        <label class="font-weight-bold text-dark" for="cc_number">Credit card number</label>
                                        <input
                                          name="cc_number"
                                          type="text"
                                          class="form-control"
                                          id="cc_number"
                                          v-model="form.cc.number"
                                          required="">

                                        <div v-if="hasErrors && validationErrors.cc_number" class="invalid-feedback d-block">
                                            {{ validationErrors.cc_number | formatError }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-xl-6 mb-3">
                                        <label class="font-weight-bold text-dark" for="cc_name">Name on card</label>
                                        <input
                                          name="cc_name"
                                          type="text"
                                          class="form-control"
                                          id="cc_name"
                                          v-model="form.cc.name"
                                          required="">
                                        <small class="text-dark">Full name as displayed on card</small>

                                        <div v-if="hasErrors && validationErrors.cc_name" class="invalid-feedback d-block">
                                          {{ validationErrors.cc_name | formatError }}
                                        </div>

                                    </div>
                                    <div class="col-8 col-xl-4 mb-3">
                                        <label class="font-weight-bold text-dark text-nowrap" for="cc_expiration">Expiration (Month/Year)</label>

                                        <div class="d-flex flex-row">
                                          <div class="mr-2 flex-fill">
                                            <input
                                              name="cc_expiration_month"
                                              type="number"
                                              step="1"
                                              min="01"
                                              max="12"
                                              class="form-control"
                                              placeholder="Month"
                                              id="cc_expiration_month"
                                              required=""
                                              maxlength="2">
                                          </div>
                                          <div class="flex-fill">
                                            <input
                                              name="cc_expiration_year"
                                              type="number"
                                              step="1"
                                              min="19"
                                              max="30"
                                              class="form-control rounded"
                                              placeholder="Year"
                                              id="cc_expiration_year"
                                              required=""
                                              maxlength="2">
                                          </div>
                                        </div>

                                        <div v-if="hasErrors && validationErrors.cc_expiration_month" class="invalid-feedback d-block">
                                            {{ validationErrors.cc_expiration_month | formatError }}
                                        </div>

                                        <div v-if="hasErrors && validationErrors.cc_expiration_year" class="invalid-feedback d-block">
                                            {{ validationErrors.cc_expiration_year | formatError }}
                                        </div>
                                    </div>
                                    <div class="col-4 col-xl-2 mb-3">

                                        <label class="font-weight-bold text-dark" for="cc_cvv">CVV</label>

                                        <input
                                          name="cc_cvv"
                                          type="text"
                                          class="form-control"
                                          id="cc_cvv"
                                          v-model="form.cc.cvv"
                                          required="">

                                        <div v-if="hasErrors && validationErrors.cc_cvv" class="invalid-feedback d-block">
                                            {{ validationErrors.cc_cvv | formatError }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <button
                                          type="submit"
                                          class="btn btn-highlight py-2 px-5"
                                          @click="showLoadingSpinner"
                                        >
                                          Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
	            </div>


              <div v-if="leadCaptured"
                class="col-12 col-md-5 col-lg-5 offset-lg-0 col-xl-4 offset-xl-1 order-xl-2 mb-4">
	                <div class="rounded-lg bg-lighter border-highlight border shadow px-4 pt-3 pb-4 mb-5">
	                    <cart-overview-component
                        :free-shipping="freeShipping"
                        :lead-captured="leadCaptured"
                        :order-id="orderId"
                        :contact-info="form.contactInfo">
                      </cart-overview-component>
	                </div>
                  <div class="mb-5">
                    <shipping-options-component
                        title="Your shipping method"
                        :free-shipping="freeShipping"
                        >
                    </shipping-options-component>
                  </div>
                  <div class="d-lg-block d-none">
                    <div class="card-wrapper pt-3 row"></div>
                  </div>
	            </div>

	        </div>
	    </div>
	</form>
</template>

<script>
  const Card = require('card')

  export default {
    props: {
      old:          Object,
      session:      Object,
      errors:       Object,
      order:        Number,
      freeShipping: Boolean,
      orderOrigins: Array,
    },
    data() {
      return {
        alert: '',
        leadCaptureErrors: {},
        orderId: null,
        form: {
          csrf: $('meta[name="_token"]').attr('content'),
          contactInfo: {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            origin_id: '',
          },
          shipping_address_different: false,
          payment_method: 'credit_card',
          cc: {
            name: '',
            number: '',
            exp_month: '',
            exp_year: '',
            cvv: '',
          },
          billingAddress: {
            address_1: '',
            address_2: '',
            city: '',
            state_id: '',
            zipcode: '',
          },
          shippingAddress: {
            address_1: '',
            address_2: '',
            city: '',
            state_id: '',
            zipcode: '',
          }
        }
      }
    },
    methods: {
      initFormData() {
        if (this.order && Number(this.order) > 0) {
          this.orderId = Number(this.order)
          this.readyForCheckout()
        }

        this.form.contactInfo.first_name   = this.old.first_name && this.old.first_name.length > 0  ? this.old.first_name : this.session.first_name
        this.form.contactInfo.last_name    = this.old.last_name && this.old.last_name.length > 0  ? this.old.last_name : this.session.last_name
        this.form.contactInfo.phone        = this.old.phone && this.old.phone.length > 0  ? this.old.phone : this.session.phone
        this.form.contactInfo.email        = this.old.email && this.old.email.length > 0  ? this.old.email : this.session.email
        this.form.contactInfo.origin_id    = this.old.origin_id && this.old.origin_id > 0 ? this.old.origin_id : this.session.origin_id

        this.form.billingAddress.address_1 = this.old.billing_address_1 && this.old.billing_address_1.length > 0  ? this.old.billing_address_1 : this.session.billing_address_1
        this.form.billingAddress.address_2 = this.old.billing_address_2 && this.old.billing_address_2.length > 0  ? this.old.billing_address_2 : this.session.billing_address_2
        this.form.billingAddress.city      = this.old.billing_address_city && this.old.billing_address_city.length > 0  ? this.old.billing_address_city : this.session.billing_address_city
        this.form.billingAddress.state_id  = this.old.billing_address_state_id && this.old.billing_address_state_id.length > 0  ? this.old.billing_address_state_id : this.session.billing_address_state_id
        this.form.billingAddress.zipcode   = this.old.billing_address_zipcode && this.old.billing_address_zipcode.length > 0  ? this.old.billing_address_zipcode : this.session.billing_address_zipcode

        this.form.cc.name                  = this.old.cc_name && this.old.cc_name.length > 0  ? this.old.cc_name : ''
        this.form.cc.number                = this.old.cc_number && this.old.cc_number.length > 0  ? this.old.cc_number : ''
        this.form.cc.cvv                   = this.old.cc_cvv && this.old.cc_cvv.length > 0  ? this.old.cc_cvv : ''

        if (this.old.cc_expiration_month && this.old.cc_expiration_month.length > 0) {
          this.form.cc.exp_month = this.old.cc_expiration_month
          Vue.nextTick(() => $('input[name="cc_expiration_month"]').val(this.old.cc_expiration_month))
        }

        if (this.old.cc_expiration_year && this.old.cc_expiration_year.length > 0) {
          this.form.cc.exp_year = this.old.cc_expiration_year
          Vue.nextTick(() => $('input[name="cc_expiration_year"]').val(this.old.cc_expiration_year))
        }

        if (this.old.shipping_address_different && this.old.shipping_address_different === 'true'
          || this.session.shipping_address_different && this.session.shipping_address_different === 'true') {
          this.form.shipping_address_different = true
          this.form.shippingAddress.address_1  = this.old.shipping_address_1 && this.old.shipping_address_1.length > 0  ? this.old.shipping_address_1 : this.session.shipping_address_1
          this.form.shippingAddress.address_2  = this.old.shipping_address_2 && this.old.shipping_address_2.length > 0  ? this.old.shipping_address_2 : this.session.shipping_address_2
          this.form.shippingAddress.city       = this.old.shipping_address_city && this.old.shipping_address_city.length > 0  ? this.old.shipping_address_city : this.session.shipping_address_city
          this.form.shippingAddress.state_id   = this.old.shipping_address_state_id && this.old.shipping_address_state_id.length > 0  ? this.old.shipping_address_state_id : this.session.shipping_address_state_id
          this.form.shippingAddress.zipcode    = this.old.shipping_address_zipcode && this.old.shipping_address_zipcode.length > 0  ? this.old.shipping_address_zipcode : this.session.shipping_address_zipcode
        }
      },
      captureLead() {
        this.showLoadingSpinner()
        this.alert = ''
        axios.post(route('abandoned-cart.store').url(), this.form.contactInfo)
          .then(({ data }) => {
            this.orderId = data.order_id
            this.readyForCheckout()
          })
          .catch(({ response }) => {
            if (response.data.errors) {
              this.leadCaptureErrors = response.data.errors
            } else {
                this.alert = "We're sorry, but we can't proceed with the order right now. Please try again or contact support."
            }
            this.hideLoadingSpinner()
          })
      },
      readyForCheckout() {
        this.leadCaptureErrors = {}
        Vue.nextTick(() => {
          this.initializeCard()
          this.hideLoadingSpinner()
          if (this.form.shipping_address_different) {
            $('#shipping-address').slideDown()
          }
        })
      },
      toggleShippingAddress() {
        if (this.form.shipping_address_different) {
          $('#shipping-address').slideDown()
        } else {
          $('#shipping-address').slideUp()
        }
      },
      showLoadingSpinner() {
        $.busyLoadFull('show')
      },
      hideLoadingSpinner() {
        $.busyLoadFull('hide')
      },
      initializeCard() {
        let card = new Card({
          form: 'form.jq-checkout-form',
          container: '.card-wrapper',
          width: 300,
          formSelectors: {
            numberInput: 'input#cc_number',
            expiryInput: 'input#cc_expiration_month, input#cc_expiration_year',
            nameInput: 'input#cc_name',
            cvcInput: 'input#cc_cvv'
          }
        });
      },
    },
    computed: {
      leadCaptured() {
        return this.orderId !== null && this.orderId > 0
      },
      hasErrors() {
        return Object.keys(this.errors).length > 0 || Object.keys(this.leadCaptureErrors).length > 0
      },
      validationErrors() {
        if (Object.keys(this.errors).length > 0) {
          return this.errors
        } else if (Object.keys(this.leadCaptureErrors).length > 0) {
          return this.leadCaptureErrors
        }
        return {}
      }
    },
    filters: {
      formatError(error) {
        if (Array.isArray(error)) {
          return error.join(' ')
        } else if (typeof error === 'string') {
          return error
        }
        return ''
      }
    },
    mounted() {
      this.initFormData()
    }
  }
</script>
