<template>
	<div>

		<label class="text-dark font-weight-bold" for="state">{{ label }} <small class="text-dark">(required)</small></label>

		<select
			name="state"
			ref="state"
			autocomplete="shipping region"
			class="custom-select"
      :class="{ 'placeholder': state === null }"
			width="100%"
			v-model="state"
			@change="updateStateId(state)"
		>
			<option :value="null" disabled>-- Select --</option>
			<option v-for="state in states" :value="state.abv">{{ state.name }}</option>
		</select>

		<div class="d-none">
			<input type="hidden" :name="addressType + '_address_state_id'" v-model="state_id">
		</div>

	</div>
</template>

<script>

	export default {

		props: [
			'label',
			'addressType',
			'selectedStateId'
		],

		data(){
			return {
				state_id: null,
				states: [],
				state: null
			}
		},

		watch: {
			state(value) {
        this.updateStateId(value)
			},
			state_id(value) {
        this.updateStateId(value)
			},
		},

		created(){
			this.getStates()
		},

		mounted() {
			let self = this;
		},

		methods: {
      getStates() {
        axios.get('/api/country/1/state')
            .then((response) => {
              this.states = response.data;
              if (this.selectedStateId) {
                  this.state_id = this.selectedStateId
                  this.updateState()
              }
            })
            .catch((error) => {
              console.log(error);
            });
      },
      updateStateId(value) {
				try {
					if (value !== undefined && value !== null) {
						let states = this.states.filter(state => {
							return state.abv == value
						})
						try {
							this.state_id = states[0].id
						} catch (e) {

						}
					} else {
						this.state_id = null
					}
				} catch (e) {

				}
      },
      updateState() {
        try {
          if (this.state_id === undefined || this.state_id === null || this.states.length === 0) {
            return false
          }

          let state = this.states.filter(state => state.id === this.state_id)[0]
          if (state !== undefined && state !== null && state.abv) {
            this.state = state.abv
          }
        } catch(e) {

        }
      }
		}
	}

</script>
