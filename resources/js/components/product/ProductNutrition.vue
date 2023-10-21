<template>
	<div v-if="nutrition !== undefined && nutrition !== null">
		<div class="px-3 pb-2 mt-2">
			<h1 class="h3 border-bottom text-dark font-weight-bolder">Nutrition Facts</h1>
			<div>
				<p v-if="nutrition.number_of_servings_per_package !== null" class="mb-0">{{ nutrition.number_of_servings_per_package }} servings per container</p>
				<p class="mb-0 d-flex flex-row align-items-center justify-content-between">
					<span class="font-weight-bolder">Serving Size</span>
					<code class="text-dark">{{ nutrition.serving_size }}{{ nutrition.serving_size_uom | strtolower }}</code>
				</p>
			</div>
		</div>
		<div class="bg-dark py-1 border-dark"></div>
		<div class="px-3 py-2">
			<p class="mb-n1">Amount Per Serving</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between h3 font-weight-bolder">
				<span>Calories</span>
				<code class="text-dark">{{ nutrition.calories }}</code>
			</p>
		</div>
		<div class="bg-dark py-1 border-dark"></div>
		<div class="px-3 py-2">
			<p class="mb-0 text-right small font-weight-bolder">% Daily Value*</p>
			<div>
				<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted">
					<span>
						<span class="font-weight-bolder">Total Fat</span>
						<code class="text-dark">{{ nutrition.total_fat }}{{ nutrition.total_fat_uom | strtolower }}</code>
					</span>
					<code class="text-dark" v-if="nutrition.total_fat_rdi !== null">{{ nutrition.total_fat_rdi }}%</code>
				</p>

				<p class="pl-3 mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted">
					<span>Saturated Fat <code class="text-dark">{{ nutrition.saturated_fat }}{{ nutrition.sat_fat_uom | strtolower }}</code></span>
					<code class="text-dark" v-if="nutrition.sat_fat_rdi !== null">{{ nutrition.sat_fat_rdi }}%</code>
				</p>
			</div>
			<div>
				<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted">
					<span class="font-weight-bolder">
						Sodium
						<code class="font-weight-normal text-dark">{{ nutrition.sodium }}{{ nutrition.sodium_uom | strtolower }}</code>
					</span>
					<code class="text-dark" v-if="nutrition.sodium_rdi !== null">{{ nutrition.sodium_rdi }}%</code>
				</p>
			</div>
			<div>
				<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted">
					<span>
						<span class="font-weight-bolder">Total Carbohydrate</span> 
						<code class="text-dark">{{ nutrition.carbohydrates }}{{ nutrition.carb_uom | strtolower }}</code>
					</span>
					<code class="text-dark">{{ nutrition.carb_rdi }}%</code>
				</p>
				<p class="pl-3 mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted">
					<span>Dietary Fiber {{ nutrition.total_diet_fiber }}{{ nutrition.total_diet_fiber_uom | strtolower }}</span>
					<code class="text-dark" v-if="nutrition.total_diet_fiber_rdi !== null">{{ nutrition.total_diet_fiber_rdi }}%</code>
				</p>
				<p class="pl-3 mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted">
					<span>Total Sugars {{ nutrition.total_sugar }}{{ nutrition.total_sugar_uom | strtolower }}</span>
					<code class="text-dark" v-if="nutrition.total_sugar_rdi !== null">{{ nutrition.total_sugar_rdi }}%</code>
				</p>
			</div>
			<div>
				<p class="mb-0">
					<span class="font-weight-bolder">Protein</span> <code class="text-dark">{{ nutrition.protein }}{{ nutrition.protein_uom | strtolower }}</code>
				</p>
			</div>
		</div>
		<div class="bg-dark py-1 border-dark"></div>
		<div class="px-3 py-2">
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.vitamin_a !== null && nutrition.vitamin_a !== 0">
				<span>Vitamin A {{ nutrition.vitamin_a }}{{ nutrition.vitamin_a_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.vitamin_a_rdi !== null">{{ nutrition.vitamin_a_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.vitamin_b12 !== null && nutrition.vitamin_b12 !== 0">
				<span>Vitamin B12 {{ nutrition.vitamin_b12 }}{{ nutrition.vitamin_b12_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.vitamin_b12_rdi !== null">{{ nutrition.vitamin_b12_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.vitamin_b6 !== null && nutrition.vitamin_b6 !== 0">
				<span>Vitamin B6 {{ nutrition.vitamin_b6 }}{{ nutrition.vitamin_b6_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.vitamin_b6_rdi !== null">{{ nutrition.vitamin_b6_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.vitamin_c !== null && nutrition.vitamin_c !== 0">
				<span>Vitamin C {{ nutrition.vitamin_c }}{{ nutrition.vitamin_c_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.vitamin_c_rdi !== null">{{ nutrition.vitamin_c_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.vitamin_d !== null && nutrition.vitamin_d !== 0">
				<span>Vitamin D {{ nutrition.vitamin_d }}{{ nutrition.vitamin_d_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.vitamin_d_rdi !== null">{{ nutrition.vitamin_d_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.vitamin_e !== null && nutrition.vitamin_e !== 0">
				<span>Vitamin E {{ nutrition.vitamin_e }}{{ nutrition.vitamin_e_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.vitamin_e_rdi !== null">{{ nutrition.vitamin_e_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.vitamin_k !== null && nutrition.vitamin_k !== 0">
				<span>Vitamin K {{ nutrition.vitamin_k }}{{ nutrition.vitamin_k_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.vitamin_k_rdi !== null">{{ nutrition.vitamin_k_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.magnesium !== null">
				<span>Biotin {{ nutrition.biotin }}{{ nutrition.biotin_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.biotin_rdi !== null">{{ nutrition.biotin_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.magnesium !== null">
				<span>Copper {{ nutrition.copper }}{{ nutrition.copper_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.copper_rdi !== null">{{ nutrition.copper_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.magnesium !== null">
				<span>Magnesium {{ nutrition.magnesium }}{{ nutrition.magnesium_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.magnesium_rdi !== null">{{ nutrition.magnesium_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.calcium !== null">
				<span>Calcium {{ nutrition.calcium }}{{ nutrition.calcium_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.calcium_rdi !== null">{{ nutrition.calcium_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.calcium !== null">
				<span>Potassium {{ nutrition.potassium }}{{ nutrition.potassium_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.potassium_rdi !== null">{{ nutrition.potassium_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.iron !== null">
				<span>Iron {{ nutrition.iron }}{{ nutrition.iron_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.iron_rdi !== null">{{ nutrition.iron_rdi }}%</code>
			</p>
			<p class="mb-0 d-flex flex-row align-items-center justify-content-between border-bottom border-muted" v-if="nutrition.zinc !== null">
				<span>Zinc {{ nutrition.zinc }}{{ nutrition.zinc_uom | strtolower }}</span>
				<code class="text-dark" v-if="nutrition.zinc_rdi !== null">{{ nutrition.zinc_rdi }}%</code>
			</p>
		</div>
		<div class="bg-dark py-1 border-dark"></div>
		<div class="px-3 py-2 mb-0 small">
			<p class="mb-2" v-if="nutrition.ingredients !== null">
				<span class="font-weight-bolder">Ingredients:</span> <span class="font-weight-light">{{ nutrition.ingredients }}</span>
			</p>
			<p class="mb-2" v-if="nutrition.prep_cook_suggestions !== null">
				<span class="font-weight-bolder">Cooking Suggestions:</span> <span class="font-weight-light">{{ nutrition.prep_cook_suggestions }}</span>
			</p>
			<p class="mb-2" v-if="nutrition.serving_suggestion !== null">
				<span class="font-weight-bolder">Serving Suggestions:</span> <span class="font-weight-light">{{ nutrition.serving_suggestion }}</span>
			</p>
			<p class="mb-2" v-if="nutrition.benefits !== null">
				<span class="font-weight-bolder">Benefits:</span> <span class="font-weight-light">{{ nutrition.benefits }}</span>
			</p>
		</div>
		<div class="bg-dark py-1 border-dark"></div>
		<div class="px-3 py-2 mb-0 small">
			<span>
				*The % Daily Value (DV) tells you how much a nutrient in a serving of food contributes to a daily diet. 2,000 calories a day is used for general nutrition advice. These values were calculated and therefore are approximate. For more accuracy, testing is advised.
			</span>
		</div>
	</div>
</template>

<script>
	export default {

		props: [
			'nutritionData'
		],

		data() {
			return {
				nutrition: null,
			}
		},

		mounted() {
			this.nutrition = this.nutritionData.content
		}
	}
</script>