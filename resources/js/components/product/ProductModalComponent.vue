<template>

	<div class="modal fade" tabindex="-1" data-keyboard="true" role="dialog" aria-hidden="true" id="product-modal-component" data-backdrop="static">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">

				<div class="modal-header pb-0 border-0">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body pt-0 pb-5 min-h-md-420px">

					<div class="px-lg-3 row align-items-center">

						<div class="col-md-4 col-lg-6 mx-auto py-2 mt-1 mb-0 w-100 text-center align-items-center d-flex" v-if="currentProduct.id !== undefined">
							<img 
								data-error="/storage/notfound.jpg"
					            data-loading="/images/px.png"
					            src="/images/px.png"
				                :data-src="currentProduct.main_image"
					            v-lazy="currentProduct.main_image"
								class="d-block h-auto mx-auto img-fluid w-auto"
							>
						</div>

						<div class="col-md-8 col-lg-6 align-self-start" v-if="currentProduct !== undefined && currentProduct.id !== undefined">
							<h1 class="h3 text-left d-none d-sm-block font-size-md-1-5rem line-height-md-2-1rem font-weight-bold">{{ currentProduct.name }}</h1>
							<h2 class="h5 mb-2 text-left" v-if="currentProduct.brand">{{ currentProduct.brand.name }}</h2>
							<p class="mb-3 font-weight-light h4 text-left">
								<span class="text-decoration-line-through" v-if="currentProduct.original_price > currentProduct.price">{{ currentProduct.original_price | currency }}</span>
								<span class="text-highlight h3">{{ currentProduct.price | currency }}</span>
							</p>
							<table class="table">
								<tr>
									<th class="px-0 py-2 text-left">
										Brand
									</th>
									<td class="px-0 py-2 text-right" >
										<span v-if="currentProduct.brand">{{ currentProduct.brand.name }}</span>
									</td>
								</tr>
								<tr>
									<th class="px-0 py-2 text-left">Product Code</th>
									<td class="px-0 py-2 text-right">{{ currentProduct.sku }}</td>
								</tr>
								<tr>
									<th class="px-0 py-2 text-left">Availability</th>
									<td class="px-0 py-2 text-right">
										<span class="text-success" v-if="currentProduct.quantity > 0">{{ currentProduct.quantity }} in stock</span>
										<span class="text-danger" v-if="currentProduct.quantity <= 0">Out of Stock</span>
									</td>
								</tr>
							</table>

							<div class="form-group" v-if="products !== undefined && products.length > 0">
								<label for="product-options" class="h5 font-weight-normal">Options</label>
								<select v-model="selectedProduct" class="form-control" id="product-options">
									<option :value="product" selected>{{ product.name }}</option>
									<option v-for="productItem in products" :value="productItem">{{ productItem.name }}</option>
								</select>
							</div>

							<div class="d-flex justify-content-between mt-auto mb-0">	
								<product-cart-component class="d-flex flex-row align-items-center justify-content-center" :product="currentProduct"></product-cart-component>
							</div>

						</div>

					</div>

				</div>
				
			</div>
		</div>
	</div>

</template>
<script>
	export default {
		methods: {
			reduceQuantity() {
				if(this.quantity > 1) {
					this.quantity -= 1;
				}
			},
			raiseQuantity() {
				if(this.quantity < this.selectedProduct.quantity) {
					this.quantity += 1;
				}
			}
		},
		data(){
			return {
				quantity: 1,
				product: [],
				selectedProduct: [],
				products: []
			}
		},

		mounted(){

			this.$root.$on('showProductChildren', (product, products) => {
				$('#product-modal-component').modal('show')
				this.product = product
				this.products = products
				this.selectedProduct = this.product
			});

			this.$root.$on('cartItemAdded', () => {
				$('#product-modal-component').modal('hide')
				this.product = []
				this.products = []
				this.selectedProduct = []
			});
		},

		computed:{
			currentProduct(){
				if (this.selectedProduct === undefined || this.selectedProduct.id === this.product.id || this.selectedProduct.length === 0) {
					return this.product
				}

				return this.selectedProduct;
			}
		}
	}
</script>