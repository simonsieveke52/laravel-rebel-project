<template>

	<div :class="'product-container mb-3 mb-md-4 ' + viewClass" :data-id="product.id">

		<div class="card m-0 pt-1 pb-2 px-2 h-100 border-0 rounded-xl">

			<div v-if="! inStock" class="position-absolute" style="right: 5px">
	            <span class="badge badge-red shadow text-uppercase text-white py-1 px-2">Out of Stock</span>
	        </div>

			<div v-else-if="product.free_shipping == 1" class="position-absolute" style="right: 5px">
	            <span class="badge badge-primary shadow text-uppercase text-white py-1 px-2">Free Shipping</span>
	        </div>

            <a @click.prevent.stop="showProduct()" :href="productLink" class="text-center d-flex align-items-center justify-content-center min-h-170px h-100 p-3 p-lg-4 rounded-lg">
	            <img
		            data-error="/storage/notfound.jpg"
		            data-loading="/images/px.png"
		            src="/images/px.png"
	                :data-src="product.main_image"
		            v-lazy="product.main_image"
	                class="img-fluid img-responsive w-auto d-block m-auto max-h-200px h-auto"
	                :alt="product.name"
	            >
	        </a>

	        <div class="mt-auto mb-0">
				<div class="px-0 d-flex flex-column pb-0 max-h-125px">
					<div class="d-flex flex-column justify-content-end" style="min-height: 85px;">
						<h3 class="text-left card-title font-weight-bold h6 px-2 mt-0 mb-auto">
		                    <a @click.prevent.stop="showProduct()" :href="productLink" class="text-dark">
		                        {{ product.name | truncate(90) }}
		                    </a>
		                </h3>
		                <div class="my-2 d-flex pl-2 align-items-center">
							<span class="text-highlight font-weight-bold">{{ product.price | currency }}</span> &nbsp;
							<strike class="text-secondary-7 small">{{ product.original_price | currency }}</strike>
		                </div>
					</div>
		        </div>

		        <div class="px-2 py-1 bg-white text-center d-flex flex-row mt-auto mb-0 align-self-end align-items-center justify-content-between w-100">
	                <a 
	                	v-if="inStock"
	                    @click.prevent="$root.$emit('showProductChildren', product, product.children)"
	                    href="#"
	                    class="btn bg-highlight text-white rounded px-2"
	                >
	                    Buy Now
	                </a>

	                <div class="">
		                <add-to-favorites 
		                	class="d-flex justify-content-end justify-content-center h-auto"
		                    context="short"
		                    icon="fa-star"
		                    defaultFilled="true"
		                    :product="product">
		                </add-to-favorites>
		            </div>

	            </div>
	        </div>

		</div>

	</div>

</template>

<script>

	export default {

		props: {
			product: {
				type: Object,
				default: function() {
					return {}
				}
			},

			viewType: {
				type: String,
				default: function() {
					return 'grid'
				}
			}
		},

		data() {
			return {
				timeout: null
			}
		},

		mounted() {
			if(localStorage.scrollPosition !== null && localStorage.scrollPosition !== 0 && localStorage.scrollPosition != '0') {
				if (parseInt(localStorage.scrollPosition) <= $(document).height()) {
	                $("html, body").animate({ scrollTop: localStorage.scrollPosition }, 100);
	                localStorage.setItem('scrollPosition', 0)
				}
            }
		},

		methods: {
			showProduct() {

				let elm = $(this.$el).find('.card')
				let url = route('product.show', this.product.slug).url()

				try {
			      	localStorage.setItem("scrollPosition", $(document).scrollTop());
				} catch (e) {

				}

				$(elm).busyLoad('hide')
				$(elm).busyLoad('show')

				if (this.timeout !== null && this.timeout !== undefined) {
					clearTimeout(this.timeout)
				}

				try {
					window.dataLayer.push({
					    'event': 'productClick',
					    'ecommerce': {
					      'click': {
					        	'actionField': {'list': 'Product click'},
					        	'products': [{
							        'id': this.product.id,
							        'name': this.product.name,
							        'price': this.product.price,
							        'position': 1
					         	}]
					        }
					    },
					    'eventCallback': function() {
					       	$(elm).busyLoad('hide')
					       	document.location = url
					    }
				  	});
				  	this.timeout = setTimeout(function(){
				  		$(elm).busyLoad('hide')
				  	}, 800)
				} catch(e) {
					$(elm).busyLoad('hide')
					document.location = url
				}
			}
		},

		computed:{

			inStock(){
				return this.product.quantity > 0 && this.product.quantity >= this.product.quantity_per_case;
			},

			viewClass() {

				if (this.viewType === 'grid') {
					return 'col-lg-4 col-md-6 col-12';
				}

				if (this.viewType === 'grid-large') {
					return 'col-md-6 col-12';	
				}

				return 'col-12'
			},

			productLink() {
				let slug = this.product.slug;

				if (slug === '' || slug.length === '') {
					slug = this.product.id;
				}

				return route('product.show', slug).url()
			}
		}
	}

</script>
<style lang="scss" scoped>
	.product-container {
		.card {
			box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
		    transition: box-shadow .3s ease-in-out;
			
			&:hover{
				box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
			}
		}
	}
</style>