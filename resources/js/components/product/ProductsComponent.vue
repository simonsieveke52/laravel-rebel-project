<template>
	<div>

		<div class="row mb-3 mb-lg-5" v-if="displayBreadcrumb === true && response !== null && response.category !== undefined">
			<div class="col-12">
				<section class="section section--breadcrumb text-left">
				    <div class="text-secondary-5">
				        <a href="/">Home</a>
	                	<a 
	                		v-for="(category, index) in response.parentCategories" 
	                		:href="route('category.filter', category.slug).url()" 
	                		:class="index == response.parentCategories.length - 1 ? 'text-muted' : ''"
	                	>
	                		<span class="px-1 px-sm-2">/</span>{{ category.name }}
	                	</a>
				    </div>
				</section>
			</div>
		</div>

		<div class="row" v-if="displayCategoryName === true && response !== null && response.category !== undefined && response.category !== null">
			<div class="col-12">
				<h1 class="h2 mt-2 mt-lg-0 font-size-1-5rem fon-size-md-2rem text-center text-uppercase mb-3 mb-lg-5 d-flex align-items-center justify-content-center">
					{{ response.category.name }}
				</h1>
			</div>
		</div>

		<form class="row jq-filters-container">
			<div class="col-12 col-sm-8 col-lg-9 order-2 order-sm-1">
				<div class="row">
					<div class="col-6 col-lg-4">
						<div class="form-group d-flex flex-column">
							<label class="h6 font-weight-bold text-capitalize text-nowrap text-black">Products per page</label>
							<select  @change="getResults()" v-model="perPage" name="perPage" class="form-control form-control-sm text-center border-highlight border px-2 py-1 rounded text-highlight bg-white">
					            <option value="50">50 Per Page</option>
					            <option value="24">24 Per Page</option>
					            <option value="16">16 Per Page</option>
					            <option value="8">8 Per Page</option>
					        </select>
						</div>
					</div>

					<div class="col-6 col-lg-4">
						<div class="form-group d-flex flex-column">
							<label class="h6 font-weight-bold text-capitalize text-nowrap text-black">Sort by</label>
							<select @change="getResults()" v-model="sortBy" name="sortBy" class="form-control form-control-sm text-center border-highlight border px-2 py-1 rounded text-highlight bg-white">
								<option value="relevance" >Relevance</option>
								<option value="h-t-l">Price Highest to Lowest</option>
								<option value="l-t-h">Price Lowest to Highest</option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-sm-4 col-lg-3 order-1 order-sm-2">
				<div class="form-group d-flex flex-column">
					<label class="h6 font-weight-bold text-capitalize text-black">Display</label>
					<div class="btn-group">
						<button @click="viewType = 'list'" class="btn btn-sm" :class="viewType != 'list' ? 'btn-outline-highlight' : 'btn-highlight'"><i class="fas fa-bars"></i></button>
						<button @click="viewType = 'grid-large'" class="btn btn-sm" :class="viewType != 'grid-large' ? 'btn-outline-highlight' : 'btn-highlight'"><i class="fas fa-th-large"></i></button>
						<button @click="viewType = 'grid'" class="btn btn-sm" :class="viewType != 'grid' ? 'btn-outline-highlight' : 'btn-highlight'"><i class="fas fa-th"></i></button>
					</div>
				</div>
			</div>
		</form>

		<div v-if="response !== null && response.category !== undefined" class="row">
			<div class="col-12 text-right">
				<small v-if="response.products !== undefined">Total products: {{ response.products.total }}</small>
			</div>
		</div>

		<div v-if="response !== null && response.category !== undefined" class="row">
			<product-component v-for="product in products.data" :product="product" :view-type="viewType" :key="product.id"></product-component>
		</div>
		<div class="row">
			<div class="col-12 mt-4" v-if="response !== null && (response.products === undefined || response.products.total === 0)">
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					No products in this category match your filters.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12 mt-3">
				<pagination :limit="3" :data="products" align="right" class="flex-wrap" @pagination-change-page="getResultsWithPagination"></pagination>
			</div>
		</div>
	</div>
</template>

<script>

	export default {

		props: {
			startPage: {
				type: Number,
				default: 1
			},
			initialLoad: {
		      	type: Boolean,
		      	default: true
		    },
		    displayCategoryName: {
		      	type: Boolean,
		      	default: true
		    },
		    displayBreadcrumb: {
		      	type: Boolean,
		      	default: true
		    },
		},

		data(){
			return {
				response: null,
				products: {},

				page: 1,
				viewType: 'grid',
				sortBy: 'relevance',
				perPage: 24
			}
		},

		mounted() {
            
			let self = this
			
			this.loadLocalStorage()

			this.$root.$on('refresh_products', function(response) {
				self.response = response
				self.products = response.products
			})

			this.$root.$on('refreshProducts', function(page) {
				self.getResults(page, function() {
					$("html").animate({ scrollTop: 0 }, 100);
				})
			})

			if (this.initialLoad === true) {
				
				let url = new URL(location.href);
				let page = url.searchParams.get('page');

				if (page === null || page === undefined) {
					page = this.startPage > 0 ? this.startPage : 1;
				}

				this.getResults(page)
			}

			if (window.initProductsComponent !== undefined) {
				window.initProductsComponent()
			}

		},

		watch: {
			viewType(newValue) {
				try {
                	localStorage.setItem('viewType', JSON.stringify(newValue))
                } catch (e) {
                }
			}
		},

		methods: {

			loadLocalStorage() {
				let item = null;
				try {
                	item = localStorage.getItem('viewType')
                	item = JSON.parse(item)
                	if (item !== undefined && item !== null) {
						this.viewType = item
                	}
                } catch (e) {
                }

                try {
                	item = localStorage.getItem('sortBy')
                	item = JSON.parse(item)
                	if (item !== undefined && item !== null) {
						this.sortBy = item
                	}
                } catch (e) {
                }

                try {
                	item = localStorage.getItem('perPage')
                	item = JSON.parse(item)
                	if (item !== undefined && item !== null) {
						this.perPage = item
                	}
                } catch (e) {
                }
			},

			saveLocalStorage() {
                try {
                	localStorage.setItem('viewType', JSON.stringify(this.viewType))
                } catch (e) {
                }

                try {
                	localStorage.setItem('sortBy', JSON.stringify(this.sortBy))
                } catch (e) {
                }

                try {
                	localStorage.setItem('perPage', JSON.stringify(this.perPage))
                } catch (e) {
                }
            },

			getResults(page = 1, callback = undefined) {

				let self = this

				$('.jq-page-content').busyLoad('show')

				let vars = location.search
					.split('&')
					.filter(function(e) {
						return e.search('page=') === -1
					})
					.filter(function(e) {
						return e.length > 0;
					})

				let delimiter = vars.length > 0 ? '&' : '?'
				let link = delimiter + 'page=' + page;
				let url = location.protocol + '//' + location.hostname + location.pathname + vars.join('&') + link;

				$.ajax({
					url: url,
					type: 'POST',
					dataType: 'json',
					data: {
						sortBy: this.sortBy,
						perPage: this.perPage
					},
				})
				.done(function(response) {
					self.response = Object.assign({}, self.response, response)
					self.products = response.products
					self.saveLocalStorage()
				})
				.always(function() {
					$('.jq-page-content').busyLoad('hide')
					try {
						if (callback !== undefined) {
							callback()
						}
					} catch (e) {

					}
				});
			},

			getResultsWithPagination(page = 1) {
				this.triggerHistory(page)
			},

			triggerHistory(page) {
				let state = History.getState()
				let vars = location.search
					.split('&')
					.filter(function(e) {
						return e.search('page=') === -1
					})
					.filter(function(e) {
						return e.length > 0;
					})

				let delimiter = vars.length > 0 ? '&' : '?'
				let link = delimiter + 'page=' + page;

				if (page === 1) {
					link = '';
				}

				let url = location.protocol + '//' + location.hostname + location.pathname + vars.join('&') + link;

				History.pushState({
						event: 'refreshProducts',
						page: page,
						state: 'pagination'
					}, 
					document.title,
					url
				);

				state = History.getState()

				if (Object.keys(state.data).length === 0 && state.data.constructor === Object) {
	                this.getResults(page, function() {
						$("html, body").animate({ scrollTop: 0 }, 100);
					})
	            }
			},
		}
	}

</script>