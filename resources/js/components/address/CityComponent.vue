<template>
	<div>
		<label class="text-dark font-weight-bold" for="state">{{ label }} <small class="text-dark">(required)</small></label>
		<input type="text" :name="addressType + '_address_city'" class="form-control" v-model="city" required>
		<slot></slot>
	</div>
</template>

<script>
	export default {

		props: [
			'label',
			'addressType',
			'selectedCity'
		],

		data(){
			return {
				city: ''
			}
		},

		created(){
			if (this.selectedCity !== '' && this.selectedCity !== undefined) {
				this.city = this.selectedCity;
			}
		}
	}
</script>