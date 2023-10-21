<template>

	<div class="row justify-content-center align-items-center mx-auto">

		<button title="click to zoom" class="btn btn-sm link--gallery-zoom" @click="zoomImage()">
			<i class="fa fa-search"></i>
		</button>
		<div class="modal modal--zoom-component" tabindex="-1" data-keyboard="true" aria-hidden="true" id="zoom-modal-component">
			<div role="document">
				<div class="modal-content container my-4">
					<button type="button" class="close" aria-label="Close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
					</button>
					<div class="modal--zoom-component__img-wrapper d-flex align-items-center text-center p-5">

					</div>
				</div>
			</div>
		</div>
		<div class="col-12 text-center py-0 h-100 justify-content-center align-items-center d-flex w-100 min-h-100px min-h-sm-420px">
			<div>
				<img class="img-fluid rounded product-img-responsive image--gallery--active" :src="selectedImage">
			</div>
		</div>

		<div class="ml-0 mr-0 ml-sm-3 mt-5 mr-sm-3 mt-lg-5 col-12 row d-flex justify-content-center" v-if="images.length > 0">
			<a v-for="image in images" 
				:class="image == selectedImage ? 'border mb-3 product-small-image-container d-flex mx-3 align-items-center justify-content-center p-3 border-primary' : 'border mb-3 product-small-image-container d-flex mx-3 align-items-center justify-content-center p-3'"
				@click="updateCurrentImage(image)"
			>
				<img 
					:src="image" 
					class="m-auto w-100 d-block w-auto h-auto"
					style="max-width: 35px; max-height: 35px;"
				>
			</a>
		</div>

		
	</div>
	
</template>

<script>

	export default {

		props: [
			'product', 'productOptions'
		],

		data(){
			return {
				selectedImage: null
			}
		},

		watch: {
			product(){
				this.updateCurrentImage(this.product.main_image)
			}
		},

		mounted() {
			this.updateCurrentImage(this.product.main_image)
		},

		methods: {

			updateCurrentImage(image){
				if (image === undefined || image === null || image === '') {
					this.selectedImage = '/storage/notfound.jpg';
					return;
				}

				this.selectedImage = image.includes('storage/product-images/') === false 
					? '/storage/products/productImages/' + image 
					: image

				return;
			},

			zoomImage(){
				var $zoomContainer = $('#zoom-modal-component');
				var $imgWrapper = $zoomContainer.find('.modal--zoom-component__img-wrapper');
				$zoomContainer.modal();
				$imgWrapper.append('<img src="' + this.selectedImage + '" class="img-fluid d-block m-auto" alt="full sized image" />');
				$('#zoom-modal-component').on('hide.bs.modal', function (e) {
					$imgWrapper.empty();
				})
			}

		},

		computed: {

			images(){
				var images = [];
				for (var i = 0; i < this.productOptions.length; i ++) {
					images.push(this.productOptions[i].main_image )
				}
				return images;
			},

			mainImage(){

				if (this.images.length === 0) {
					return undefined
				}

				let images = this.images.filter(img => {
					return img.is_main == 1
				})

				if (images.length === 0) {
					return this.images[0]
				}

				return images[0]
			}

		}
	}
	
</script>

<style lang="scss" scoped>
	
	.link--gallery-zoom {
		position: absolute;
		top: 1rem;
		right: 1rem;
		color: #222;
		font-size: 1.25rem;
		z-index: 90;
	}

	.product-small-image-container{
		opacity: 0.8;
		cursor: pointer;
		transition: all .4s ease;
		&:hover{
			opacity: 1;
		}
	}

	
	.modal {
    &--zoom-component {
		.modal-content,
		&__img-wrapper {
			img {
				object-fit: contain;
				height: 100%;
			}
		}
		.close {
			width: 25px;
			height: 25px;
			border-radius: 50%;
			background: #f1f1f1;
			position: absolute;
			right: 1rem;
			top: 1rem;
			transition: all .4s ease;
			&:hover {
				background: #e5e5e5;
			}
		}
	}
}
</style>