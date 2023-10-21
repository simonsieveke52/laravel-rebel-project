<template>

	<div>

		<h1 class="text-highlight h4 mb-4 text-uppercase">Order Summary</h1>

		<div v-if="totalItems > 0">

			<h2 class="h5 mb-2 font-weight-bold">Items To Ship</h2>

			<div class="p-2 bg-white rounded-lg shadow-sm">
				<table class="table-sm table-hovered table-borderless mb-3">
					<tbody>
						<tr class="border-bottom border-secondary" v-for="cartItem in availabeCartItems">
							<td class="align-top">
								<code class="text-dark font-weight-bold">({{ cartItem.quantity }})</code>
								<a class="text-dark" :href="route('product.show', cartItem.id).url()">
									{{ cartItem.name }}
								</a>
							</td>
							<td class="align-middle">
								<a href="#" class="text-default" @click.prevent="openCart()">Edit</a>
							</td>
							<td class="align-middle text-right">
								<small style="margin-bottom: -5px;" class="d-block text-decoration-line-through text-danger" v-if="cartItem.bulkPrice < cartItem.price">
									{{ cartItem.price * cartItem.quantity | currency }}
								</small>
								<code class="d-block text-dark font-weight-bold text-nowrap">{{ cartItem.bulkPrice * cartItem.quantity | currency }}</code>
							</td>
						</tr>
					</tbody>
				</table>
				<div>
					<div class="d-flex flex-column align-items-center justify-content-between px-1">
						<div class="d-flex flex-row w-100 flex-fill align-items-center justify-content-between">
							<div class="align-top py-0 font-weight-bold" colspan="2">Subtotal</div>
							<div>
								<code class="font-weight-bold py-0 text-dark">{{ cartSubtotal | currency }}</code>
							</div>
						</div>
						<div class="d-flex flex-row w-100 flex-fill align-items-center justify-content-between" v-if="discount > 0">
							<div class="align-top py-0 font-weight-bold" colspan="2">Discount</div>
							<div>
								<code class="font-weight-bold py-0 text-dark">{{ discount | currency }}</code>
							</div>
						</div>
						<div class="d-flex flex-row w-100 flex-fill align-items-center justify-content-between" v-if="taxValue > 0">
							<div class="align-top py-0 font-weight-bold" colspan="2">Tax</div>
							<div>
								<code class="font-weight-bold py-0 text-dark">{{ taxValue | currency }}</code>
							</div>
						</div>
						<div v-if="leadCaptured" class="d-flex flex-row w-100 flex-fill align-items-center justify-content-between">
							<div class="align-top py-0 font-weight-bold" colspan="2">Shipping</div>
							<div>
								<code class="font-weight-bold py-0 text-dark">{{ shippingPrice | currency }}</code>
							</div>
						</div>
						<div class="d-flex flex-row w-100 flex-fill align-items-center justify-content-between">
							<div class="align-top py-0 font-weight-bold" colspan="2">Total</div>
							<div>
								<code class="font-weight-bold py-0 text-dark">{{ cartTotal | currency }}</code>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-5">
	            <coupon-code-component :order-id="orderId" :contact-info="contactInfo">
	            	<h2 class="h5 mb-2 font-weight-bold">Have A Promo Code</h2>
	            </coupon-code-component>
			</div>
		</div>

		<div v-else>

			<div class="alert alert-danger mb-0" v-if="loaded">
				Your cart is empty.
			</div>

		</div>

	</div>

</template>

<script>

	export default {

		data() {
	        return {
	            loaded: false,
	            taxRate: 0,
	            discount: 0,
	            currentZipcode: '',
	            shipping: [],
	            zipcodes: [],
				cartItems : [],
	        }
		},

    props: {
      freeShipping: Boolean,
      leadCaptured: Boolean,
      orderId:      Number,
      contactInfo:  Object,
    },

	    watch: {
		   	'currentZipcode': function(val, oldVal){

		   		let self = this

		   		$.ajax({
		   			url: '/tax/' + val,
		   			type: 'PUT',
		   			dataType: 'json',
		   			data: {
			   			zipcode: val
			   		}
		   		})
		   		.done(function(response) {
		   			self.taxRate = response
		   		})
		   		.fail(function() {
		   			self.taxRate = 0
		   		})
			},
	    },

	    mounted(){

	        this.refresh()

	    	this.$root.$on('cartItemUpdated', cartItem => {
	    		this.cartItems = this.cartItems.map(item => {
	    			if( item.id === cartItem.id ){
	    				return cartItem;
	    			}
	    			return item
	    		})
	    	})

	    	this.$root.$on('cartItemDeleted', cartItem => {
	    		this.cartItems = this.cartItems.filter(item => {
	    			return item.id !== cartItem.id
	    		})
	    	})

	    	this.$root.$on('shippingUpdated', shipping => {
				if (this.freeShipping === true) {
					this.shipping = 0
				} else {
	    			this.shipping = shipping
				}
	    	})

	    	this.$root.$on('cartTaxUpdated', zipcode => {
	    		this.zipcodes.push(zipcode)
	    		this.currentZipcode = this.zipcode
	    	})

	    	this.$root.$on('couponCodeAdded', discount => {
				this.refresh()
	    	})
	    },

	    methods: {

	    	openCart() {
	    		this.$root.$emit('openCart');
	    	},

	    	refresh() {

	    		let self = this

	    		$.ajax({
	    			url: '/cart',
	    			type: 'GET',
	    			dataType: 'json',
	    		})
	    		.done(function(response) {
		          	self.loaded = true;
		            self.cartItems = response.cartItems;
		            self.taxRate = response.taxRate
					self.discount = response.discount
	    		})
	    	}
	    },

	    computed: {

	        totalItems(){
	            return this.cartItems.filter(item => {
	                return item.deleted === false
	            }).length
	        },

	        availabeCartItems(){
	            if (this.cartItems.length === 0) {
	                return []
	            }
	            return this.cartItems.filter(item => {
	                return item.deleted === false
	            })
	        },

	        shippingPrice(){

	        	let cost = 0;

	        	if (typeof(this.shipping) === 'object') {
	        		cost = parseFloat(this.shipping.cost)
	        		cost = isNaN(cost) ? 0 : cost;
	        	}

        		let sum = 0;

	        	if (cost === 0 && typeof(this.shipping) === 'object') {
	        		this.shipping.map(function(e) {
	        			sum += eval(e.cost)
	        		})
	        	}

	        	return sum
	        },

	        taxValue(){
	        	return ( this.cartSubtotal * this.taxRate/100 )
	        },

	        zipcode(){

	        	if (this.zipcodes.length === 0) {
	        		return false
	        	}

	        	let shippingZipcode = this.zipcodes.filter(zipcode => {
	        		return zipcode.addressType == 'shipping'
	        	})

	        	if (shippingZipcode.length) {
	        		return shippingZipcode[ shippingZipcode.length - 1 ].zipcode
	        	}

	        	return this.zipcodes[ this.zipcodes.length - 1 ].zipcode
			},

			cartSubtotal() {
				let subtotal = 0

				this.cartItems.map(function(cartItem) {
					subtotal += (cartItem.bulkPrice * cartItem.quantity)
					return cartItem
				})

				return subtotal;
			},

	        cartTotal(){
	        	return (this.cartSubtotal + this.shippingPrice + this.taxValue) - this.discount
	        },
	    }

	}

</script>
