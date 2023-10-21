<template>
	<section>
		<div class="col-12 d-block d-sm-none">
			<h1 class="h3 mt-3 font-size-1-3rem line-height-1-8rem">{{ currentProduct.name }}</h1>
		</div>

		<div class="col-lg-6">
			<div class="product--image__wrapper alert alert-light text-center d-flex align-items-center mb-2 mb-md-0 position-relative">
				<div v-if="currentProduct.free_shipping == 1" class="position-absolute" style="left: 1rem; top: 1rem; z-index: 3;">
		            <span class="badge badge-primary shadow text-uppercase text-white py-1 px-2">Free Shipping</span>
		        </div>

				<product-images-component :product="currentProduct" :productOptions="productOptions"></product-images-component>
			</div>
		</div>

		<div class="col-lg-6">

			<h1 class="h3 d-none d-sm-block font-size-md-1-5rem line-height-md-2-1rem">{{ currentProduct.name }}</h1>

			<div class="form-group my-4">
				<money-component class="text-decoration-line-through font-weight-light" :value="currentProduct.original_price"></money-component>
				<money-component class="text-highlight font-weight-bold h3" :value="currentProduct.price"></money-component>
				&nbsp;
				<a href="#bulk-price" @click.prevent="scrollTo('bulk-price')" id="bulk-price-link" class="small">
					See special bulk pricing
				</a>
			</div>

			<div class="d-block d-lg-none my-4" v-if="productOptions.length > 0">
				<label class="font-weight-bold">Select an option</label>
				<select class="form-control rounded-sm" v-model="currentProduct">
					<option :value="p" v-for="p in productOptions">
						{{ p.name }}
					</option>
				</select>
			</div>

			<div class="d-flex d-lg-none mb-3 justify-content-between">	
				<product-cart-component 
					class="d-flex flex-row align-items-center justify-content-center" 
					:product="currentProduct"
				>
				</product-cart-component>
				<add-to-favorites :product="currentProduct"></add-to-favorites>
			</div>

			<div class="p-3 bg-grey-light rounded-lg mb-3">
				<div class="mb-0 product-info-table">
					<div>
						<div class="row">
							<div class="col-6 py-2 px-0 border-0">
								Product ID
							</div>
							<div class="col-6 py-2 px-0 border-0 text-right text-highlight">
								{{ currentProduct.id }}
							</div>
						</div>
			
						<div class="row" v-if="currentProduct.price_each !== null && currentProduct.price_each !== ''">
							<div class="col-6 py-2 px-0">
								Price each
							</div>
							<div class="col-6 py-2 px-0 text-right text-highlight">
								<money-component :value="currentProduct.price_each"></money-component>
							</div>
						</div>
			
						<div class="row" v-if="
							currentProduct.price_size !== null && 
							currentProduct.price_size !== '' && 
							currentProduct.price_size != currentProduct.price"
						>
							<div class="col-6 py-2 px-0">
								Price per {{ currentProduct.size_uom }}
							</div>
							<div class="col-6 py-2 px-0 text-right text-highlight">
								<money-component :value="currentProduct.price_size"></money-component>
							</div>
						</div>
			
						<div class="row" v-if="currentProduct.sku !== null && currentProduct.sku !== ''">
							<div class="col-6 py-2 px-0">
								SKU:
							</div>
							<div class="col-6 py-2 px-0 text-right text-highlight">
								{{ currentProduct.sku }}
							</div>
						</div>
					</div>
					<div id="additional-product-info-table" class="collapse">

						<div class="row" v-if="currentProduct.category !== undefined && currentProduct.category !== null">
							<div class="col-6 py-2 px-0">
								Category:
							</div>
							<div class="col-6 py-2 px-0 text-right">
								<span class="text-highlight">{{ currentProduct.name }}</span>
							</div>
						</div>
			
						<div class="row" v-if="currentProduct.brand !== undefined && currentProduct.brand !== null">
							<div class="col-6 py-2 px-0">
								Brand:
							</div>
							<div class="col-6 py-2 px-0 text-right">
								<span class="text-highlight">{{ currentProduct.brand.name }}</span>
							</div>
						</div>

						<div class="row" v-if="currentProduct.mpn !== null && currentProduct.mpn !== ''">
							<div class="col-6 py-2 px-0">
								Model Number:
							</div>
							<div class="col-6 py-2 px-0 text-right">
								<span class="text-highlight">{{ currentProduct.mpn }}</span>
							</div>
						</div>

						<div class="row" v-if="
							currentProduct.weight !== null && 
							currentProduct.weight !== '' && 
							currentProduct.weight_uom !== null && 
							currentProduct.weight_uom !== ''"
						>
							<div class="col-6 py-2 px-0">
								Weight:
							</div>
							<div class="col-6 py-2 px-0 text-right">
								<span class="text-highlight">{{ currentProduct.weight }} {{ currentProduct.weight_uom }}</span>
							</div>
						</div>

						<div class="row" v-if="currentProduct.upc !== null && currentProduct.upc !== ''">
							<div class="col-6 py-2 px-0">
								GTIN/UPC:
							</div>
							<div class="col-6 py-2 px-0 text-right">
								<span class="text-highlight">{{ currentProduct.upc }}</span>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-6"></div>
						<div class="col-6 py-2 text-right">
							<a 
							  	role="button" 
							  	class="additional-info-toggle font-weight-bold collapsed" 
							  	@click="additionalInfoToggleClicked = ! additionalInfoToggleClicked"
							  	data-toggle="collapse" 
							  	data-target="#additional-product-info-table"
							  	v-html="additionalInfoToggleClicked ? 'More information' : 'Less information'"
							>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="d-none d-lg-block my-4 pt-1 pb-2" v-if="productOptions.length > 0">
				<label class="font-weight-bold">Select an option</label>
				<select class="form-control rounded-sm" v-model="currentProduct">
					<option :value="p" v-for="p in productOptions">
						{{ p.name }}
					</option>
				</select>
			</div>

			<div class="d-none d-lg-flex justify-content-between">	
				<product-cart-component 
					class="d-flex flex-row align-items-center justify-content-center" 
					:product="currentProduct"
				>
				</product-cart-component>

				<add-to-favorites 
					:product="currentProduct"
				>	
				</add-to-favorites>
			</div>

			<div 
				class="p-4 rounded bg-light mb-3 mt-4" 
				v-if="currentProduct.short_description !== null && currentProduct.short_description !== ''" 
				v-html="currentProduct.short_description"
			>
				
			</div>
			
			<div class="mt-4">
				<h2 class="h6 mb-3 font-weight-bold cursor-pointer" id="bulk-price" @click.prevent="scrollTo('bulk-price')">Share</h2>
				<ul class="list-inline">
					<li class="list-inline-item px-1">
						<a target="_blank" class="text-red" :href="'https://www.facebook.com/sharer/sharer.php?u=' + route('product.show', currentProduct.slug).url()">
							<i class="fab fa-facebook-f"></i>
						</a>
					</li>
					<li class="list-inline-item px-1">
						<a 
							class="text-red"
							target="_blank" 
							:href="'https://twitter.com/intent/tweet?text=Check out this ' + 
								currentProduct.name + ' on ' + route('product.show', currentProduct.slug).url()" 
						>
							<i class="fab fa-twitter"></i>
						</a>
					</li>
					<li class="list-inline-item px-1">
						<a 
							target="_blank"
							:href="'https://pinterest.com/pin/create/button/?url='+ route('product.show', currentProduct.slug).url() +'&media='+ (url + '/' + (currentProduct.main_image[0] === '/' ? currentProduct.main_image.substring(1) : currentProduct.main_image)) +'&description=Check out this ' + currentProduct.name"
							class="text-red">
							<i class="fab fa-pinterest-p"></i>
						</a>
					</li>
					<li class="list-inline-item px-1">
						<a 
							class="text-red"
							target="_blank" 
							:href="'mailto:?subject=Check out this ' + currentProduct.name + '&amp;body=Check out this ' + currentProduct.name + ' on ' + route('product.show', currentProduct.slug).url()" 
						>
							<i class="fa fa-share-alt"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="col-12 mb-4 mt-lg-5">
			<div class="row">
				<div class="col-12">
					<p class="mb-4">
				        <a :href="route('quoterequest.index', { product: currentProduct.sku })">
				        	Interested in 10 or more cases? Click Here for a Quote
				        </a>
				    </p>
				    <div class="d-flex bulk-price-table mb-5 col-12">
				        <div class="row bulk-price-table__row border-bottom font-weight-bold">
				            <div class="col-md-2 py-2 bulk-price-table__cell">Quantity</div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">1</div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">2-3</div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">4-6</div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">7-9</div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">10+</div>
				        </div>
				        <div class="row bulk-price-table__row">
				            <div class="col-md-2 py-2 px-0 font-weight-bold bulk-price-table__cell" id="bulk-price-table-per-unit">Price Per Unit</div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">
				            	{{ currentProduct.price * 1  | currency }}
				            </div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">
				            	{{ currentProduct.price * .96 | currency }}
				            </div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">
				            	{{ currentProduct.price * .95 | currency }}
				            </div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">
				            	{{ currentProduct.price * .94 | currency }}
				            </div>
				            <div class="col-md-2 py-2 px-0 bulk-price-table__cell">
				            	<a :href="route('quoterequest.index', { product: currentProduct.sku })">
				            		Click here
				            	</a>
				            </div>
				        </div>
				    </div>
				</div>
			</div>

			<div class="row" v-if="currentProduct.text_description !== null && currentProduct.text_description !== ''">
				<div class="col-12 mb-5">
			    	<h2 class="h3 mb-3">Details</h2>
			    	<div class="pb-2 mb-3 jq-description" v-html="currentProduct.text_description"></div>
			    </div>
			</div>

			<div class="row" v-if="
				currentProduct.brand !== undefined && 
				currentProduct.brand !== null && 
				currentProduct.brand.description !== null && 
				currentProduct.brand.description !== ''"
			>
				<div class="col-12 mb-5">
			    	<h2 class="h3 mb-3" v-if="currentProduct.brand.name !== null && currentProduct.brand.name !== ''">
			    		{{ currentProduct.brand.name }}
			    	</h2>
			    	<div class="pb-2 mb-3 jq-description" v-html="currentProduct.brand.description"></div>
			    </div>
			</div>

			<div class="row" v-if="currentProduct.nutrition !== undefined && currentProduct.nutrition !== null">
				<div class="col-12 my-5">
					<div style="max-width: 340px;">
				        <product-nutrition class="border border-dark bg-light" :nutrition-data="currentProduct.nutrition">
				        </product-nutrition>
					</div>
				</div>
			</div>

			<div class="row" v-if="boughtTogether.length > 0">
				<div class="col-12">
			        <h2 class="h3 mb-3">Frequently Bought Together</h2>
			    </div>

			    <product-component 
			    	v-for="productBought in boughtTogether"
	                :product="productBought" 
	                class="col-md-6" 
	                view-type="custom" 
	                :key="productBought.id"
	            >
	            </product-component>
			</div>
		</div>

	</section>
</template>

<script>
	export default {
		props: {
			product: {
				type: Object
			},
			boughtTogether: {
				type: Array,
			},
			children: {
				type: Array,
				default: []
			},
			parent: {
				type: Object,
				default: function () {
					return {}
				}
			},
		},

		data() {
			return {
				additionalInfoToggleClicked: false,
				currentProduct: this.product
			}
		},

		mounted() {
			if (this.productOptions.length > 0) {
				this.productOptions.map(p => {
					if (p.id === this.currentProduct.id) {
						this.currentProduct = p;
					}
				})
			}
		},

		methods: {
			scrollTo(id) {
				let elm = document.getElementById(id)

				elm.scrollIntoView({
	                behavior: "smooth",
	                block: "start",
	                inline: "nearest"
	            });
			}
		},

		computed: {
			url() {
				return window.origin;
			},

			productOptions() {
				if (this.parent === null && this.children.length === 0 ) {
					return []
				}

				let options = []
				let children = this.children;

				if (children.length === 0 && this.parent !== null) {
					children = this.parent.children
				}

				if (this.parent === null) {
					options.push(this.product)
				}

				if (this.parent !== null) {
					options.push(this.parent)
				}

				if (children.length > 0) {
					for (var i = children.length - 1; i >= 0; i--) {
						options.push(children[i])
					}
				}

				return options;
			}
		}
	}
</script>