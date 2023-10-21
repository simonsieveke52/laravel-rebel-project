<template>
	
	<div>

		<h4 class="d-flex justify-content-between align-items-center mb-3">
			<span class="text-dark font-weight-bold">{{ title }}</span>
		</h4>

		<ul v-if="freeShipping" class="list-group mb-3">
			<label class="mb-0 border-secondary list-group-item list-group-item-secondary list-group-item-action d-flex justify-content-between">

				<span class="text-capitalize flex-fill flex-grow-1 text-dark">Free Shipping</span>

				<code class="flex-shrink-1 flex-fill text-right text-dark">
					$0.00
				</code>

			</label>
		</ul>

		<ul v-else class="list-group mb-3">

			<label v-for="shipping in shippingOptions" :for="'shipping-' + shipping.id" class="mb-0 border-secondary list-group-item list-group-item-secondary list-group-item-action d-flex justify-content-between">

				<span class="text-capitalize flex-fill flex-grow-1 text-dark">{{ shipping.name }}</span>

				<code class="flex-shrink-1 flex-fill text-right text-dark">
					{{ shipping.cost | currency }}
				</code>

			</label>

		</ul>
		
	</div>

</template>

<script>

	export default {

		props: [
			'title',
			'freeShipping'
		],

		data() {
	        return {
	            shippingMethod : 0,
	            shippingOptions : 0,
	        }
	    },

	    mounted(){

	    	let self = this;

	    	this.refresh()

	    	this.$root.$on('cartItemUpdated', function() {
	    		self.refresh()
	    	})
	    },

	    methods: {
	    	updateShipping(){

	    		let self = this

	    		$.ajax({
	    			url: '/shipping',
	    			type: 'put',
	    			dataType: 'json',
	    			data: {
		    			shipping: this.shippingMethod
		    		}
	    		})
	    		.done(function(response) {
	    			self.$root.$emit('shippingUpdated', self.selectedShipping);
	    		})
	    		.fail(function() {

	    		})
	    		.always(function() {

	    		});
	    	},

	    	refresh() {
	    		let self = this

	    		$.ajax({
	    			url: '/shipping',
	    			type: 'GET'
	    		})
	    		.done(function(response) {
	    			try {
		    			self.shippingOptions = response
		    			
		    			self.shippingMethod = self.shippingOptions.map(function(e) {
				    		return e.id
				    	})

		    			self.updateShipping()
	    			} catch (e) {
	    			}
	    		})
	    		.fail(function() {
	    			self.shippingOptions = {}
	    		})
	    	}
	    },

	    computed: {
	    	selectedShipping(){
	    		return this.shippingOptions;
	    	}
	    }
	}

</script>