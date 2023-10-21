<template>
    <div>
        <slot></slot>
        <div class="input-group">
            <input type="text" class="form-control border-highlight" placeholder="Promo Code" aria-label="Promo Code" v-model="code">
            <div class="input-group-append">
                <button class="btn btn-outline-highlight" type="button" @click.prevent="applyCouponCode()">Apply</button>
            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: {
      orderId:      Number,
      contactInfo:  Object,
    },
    data() {
        return {
            code: ''
        }
    },

    mounted() {
        if (window.coupon !== undefined && window.coupon !== null && window.coupon != '') {
            this.code = window.coupon;
            this.applyCouponCode();
        }
    },

    methods: {
        applyCouponCode() {
            if (this.code == '') {
                return false;
            }

            axios.post(route('applyCouponCode').url(), {
              code: this.code,
              orderId: this.orderId,
              contactInfo: this.contactInfo,
            }).then(response => {
                if(response.data === 'code invalid') {
                    alert('We\'re sorry, that coupon code is not currently valid.')
                    return false;
                } else {
                    this.$root.$emit('couponCodeAdded', response.data);
                }
            }).catch(() => alert('Invalid or expired promocode'))
        }
    }
};

</script>
