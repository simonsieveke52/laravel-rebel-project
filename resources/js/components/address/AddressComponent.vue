<template>
	<div>
		<div class="mb-3">
			<div v-if="! onCheckout">
				<label class="text-dark font-weight-bold" for="address">Address Type <small class="text-dark">(required)</small></label>

				<select
					name="address_type"
					class="form-control mb-3 col-6"
					placeholder=""
					v-model="type"
				>
				<option value="billing" v-bind:selected="type == 'billing'">Billing</option>
				<option value="shipping" v-bind:selected="type == 'shipping'">Shipping</option>
				</select>
			</div>
			<input
				v-else
				type="hidden"
				name="address_type"
				v-model="type"
			/>

			<label class="text-dark font-weight-bold" for="address">Address <small class="text-dark">(required)</small></label>

			<input
				type="text"
				class="form-control"
				placeholder="1234 Main St"
				v-model="address_1"
				required=""
				:name="type + '_address_1'"
			>

			<div v-if="hasError(type + '_address_1')" class="invalid-feedback">
				{{ getError(type + '_address_1') }}
			</div>

			<small class="text-muted"><i>We do not ship to PO Boxes</i></small>

		</div>
		<div class="mb-3">
			<label class="text-dark font-weight-bold" for="address2">Address 2 <small class="text-dark">(Optional)</small></label>

			<input
				type="text"
				class="form-control"
				placeholder="Apartment or suite"
				v-model="address_2"
				:name="type + '_address_2'"
			>

		</div>
		<div class="row">
			<div class="col-md-3 mb-3">
				<label class="text-dark font-weight-bold" for="zip">Zip <small class="text-dark">(required)</small></label>

				<input
					type="number"
					class="form-control"
					placeholder="Your zipcode"
					required=""
					v-model="zipcode"
					maxlength="5"
					:name="type + '_address_zipcode'"
				>
			</div>
			<div class="col-md-4 mb-3">

 				<city-component label="City" :selected-city="city" :address-type="type">
 					<div v-if="hasError(type + '_address_city')" class="invalid-feedback d-block">
						{{ getError(type + '_address_city') }}
					</div>
 				</city-component>

			</div>

			<div class="col-md-5 mb-3">

				<state-component label="State" :selected-state-id="state_id" :address-type="type"></state-component>

				<div v-if="hasError(type + '_address_state_id')" class="invalid-feedback d-block">
					{{ getError(type + '_address_state_id') }}
				</div>

			</div>

		</div>
	</div>
</template>

<script>

	import errorsBag from '../../helpers/errors';

	export default {

		props: [
			'addressType', 'errors', 'address', 'onCheckout'
		],

		data(){
			return {
				type: this.addressType,
				address_1: '' ,
				address_2: '' ,
				zipcode: '' ,
				state_id: '' ,
				state: '',
				city: '',
			}
		},

		watch: {
			'type': function (val, oldVal) {
				if (val && val !== oldVal) {
		   			this.type = val;
		   		}
			},

		   	'state': function (val, oldVal) {
		   		if (val) {
		   			this.state_id = val.value;
		   		} else {
		   			this.state_id = null;
		   		}
		   	},

		   	'zipcode': function (val, oldVal){
		   		if (val !== null && val.length === 5) {
		   			this.$root.$emit('cartTaxUpdated', {
		   				zipcode: val,
		   				addressType: this.addressType,
		   			})
		   		}
		   	}

		},

		created(){
			this.address_1 = this.address.address_1;
			this.address_2 = this.address.address_2;
			this.zipcode = this.address.zipcode;
			this.city = this.address.city
			this.state_id = parseInt(this.address.state_id)
		},

		methods: {

			hasError(attribute){
				return errorsBag.has(this.errors, attribute)
			},

			getError(attribute){
				return errorsBag.get(this.errors, attribute)
			}
		}
	};
</script>

<style slot>
	.was-validated .v-select input.form-control:valid, .v-select input.form-control.is-valid{
		border: none !important;
		background-image: none !important;
		padding-right: 0px !important;
	}
	.was-validated .form-control:valid:focus, .form-control.is-valid:focus{
		box-shadow: none;
	}
</style>
