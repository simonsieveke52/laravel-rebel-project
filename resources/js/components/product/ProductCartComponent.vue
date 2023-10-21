<template>
	<div>
        <div v-if="product.quantity > 0" class="d-flex flex-row align-items-center justify-content-around">
            <div class="btn-group mr-3 mr-md-2 mr-lg-3">
                <button type="button" @click.prevent="reduceQuantity" class="btn btn-highlight font-size-only-1rem font-size-only-md-0-85rem font-size-only-lg-1rem">
                    -
                </button>
                <input type="number" value="1" class="form-control font-size-only-1rem font-size-only-md-0-85rem font-size-only-lg-1rem w-50px w-sm-70px px-1 px-md-2 rounded-0" min="1" v-model.number="selectedQuantity" :style="invalidQuantity ? 'background-color: #ffa4a4' : ''">
                <button type="button" @click.prevent="raiseQuantity" class="btn btn-highlight font-size-only-1rem font-size-only-md-0-85rem font-size-only-lg-1rem">
                    +
                </button>
            </div>
            <div class="text-nowrap">
				<add-to-cart-component :quantity="selectedQuantity" :product-id="product.id">
					<button type="button" class="btn bg-highlight text-white btn-block font-size-only-1rem font-size-only-md-0-85rem font-size-only-lg-1rem">
						Add To Cart
					</button>
				</add-to-cart-component>
            </div>
        </div>
        <div v-else class="d-block w-100">
        	<div class="alert alert-danger">
				<p class="m-0">Out of Stock</p>
        	</div>
		</div>

	</div>
</template>

<script>
	export default{
		props: [
			'product'
		],
		watch: {
			product(newValue) {
				this.quantity = 1;
			}	
		},
		methods: {
			reduceQuantity() {
				if(this.quantity > 1) {
					this.quantity -= 1;
				} else {
					this.quantity = 1;
				}

				this.invalidQuantity = false
			},
			raiseQuantity() {
				this.selectedQuantity++;
			}
		},
		data(){
			return {
				quantity: 1,
				invalidQuantity: false
			}
		},
		computed: {
			selectedQuantity: {
			    get: function () {
			      	return this.quantity
			    },
			    // setter
			    set: function (newValue) {
			      	if(Number.parseInt(newValue) < Number.parseInt(this.product.quantity)) { 
						this.quantity = Number.parseInt(newValue);
						this.invalidQuantity = false
					} else {
						this.invalidQuantity = true
					}
			    }
			}
		}
	}
</script>