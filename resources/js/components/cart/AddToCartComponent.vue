<template>
	<div :class="cssClass" @click="addToCart()">
		<slot></slot>
        <div v-if="quantity > 1" class="text-success" id="discount-applied-message">
            Bulk discount applied! ({{ percentDiscount }}% off)
        </div>
	</div>
</template>

<script>
export default {
    props: [
        'productId',
        'cssClass',
        'quantity'
    ],
    methods: {
        addToCart() {
            try {
                let self = this
                $.busyLoadFull('show')
                $.ajax({
                    url: '/cart',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: this.productId,
                        quantity: this.quantity
                    }
                })
                .done(function(response) {
                    self.$root.$emit('cartItemAdded', response);
                })
                .always(function(response) {
                    if(response.status == 422) {
                        alert("We're sorry. We were unable to add " + self.quantity + " of this product to the cart.");
                    }
                    
                    $.busyLoadFull('hide')
                });
            } catch (e) {
                alert(e.response.data.message);
                $.busyLoadFull('hide')
            }
        }
    },
    computed: {
        percentDiscount() {
            if([2, 3].includes(this.quantity)) {
                return 4;
            }
            if([4, 5, 6].includes(this.quantity)) {
                return 5;
            }
            if([7, 8, 9].includes(this.quantity)) {
                return 6;
            }
        }
    }
};
</script>