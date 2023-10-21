<template>
    <div>
        <a
            href="#"
            @click.prevent="open()"
            :class="cssClasses"
        >
            <span class="fa-layers fa-fw m-0 position-relative d-flex flex-row">
                <i class="fas fa-shopping-cart"></i>

                <span 
                    v-if="! isEmpty"
                    class="fa-layers-counter bg-highlight text-white d-flex flex-column align-items-center justify-content-center" 
                    style="font-size: 0.7rem; position: absolute; border-radius: 50%; height: 20px; width: 20px; top: -4px; right: -10px;"
                >
                    <span class="d-flex">
                        {{ totalItems }}
                    </span>
                </span>

            </span>
        </a>

        <div class="shadow-lg offcanvas-collapse " :class="isOpen == true ? 'open' : ''">
            <div class="px-1 h-100" style="overflow: auto;">
                <div class="modal-header border-bottom-0 text-right d-block w-100">
                    <button class="btn position-relative btn-danger text-white rounded-circle shadow-lg" style="padding: 0px 6.5px;" @click="close()" aria-label="Close cart">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container-fluid mb-5">
                    <div class="row">
                        <div class="col-12" v-if="showSuccessAlert">
                            <div class="alert alert-success mb-1">
                                New item added to your cart.
                            </div>
                        </div>

                        <div v-if="isEmpty" class="col-12 pt-3 pb-1" @click.stop.prevent>
                            <div class="alert alert-danger mb-1">
                                Your cart is empty.
                            </div>
                        </div>

                        <div v-else>
                            <div class="col-12">
                                <div class="list-group py-0">
                                    <div v-for="cartItem of availabeCartItems" class="list-group-item list-group-item-action p-0 border-0 rounded">
                                        <cart-item-component :item="cartItem"></cart-item-component>
                                    </div>
                                </div>

                                <div class="py-3" @click.stop.prevent>
                                    <div class="text-right h5 mb-0">
                                        Subtotal : <span class="font-weight-bold">{{ cartSubtotal | currency }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-right">
                            <div class="btn-group">
                                <button class="btn btn-secondary" @click.prevent="close()">Continue shopping</button>
                                <a v-if="! isEmpty" class="btn btn-highlight text-white" @click.prevent="openCheckoutLink()">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    
    props: [
        'cssClasses',
        'checkoutUrl'
    ],

    data() {
        return {
            isOpen: false,
            loaded: false,
            showSuccessAlert: false,
            cartItems : []
        }
    },

    created(){
        let self = this;

        $.ajax({
            url: '/cart',
            type: 'GET'
        })
        .done(function(response) {
            self.loaded = true;
            self.cartItems = response.cartItems;
        })
        .fail(function() {
            self.cartItems = [];
        })
    },

    mounted(){

        let self = this;

        this.$root.$on('openCart', function() {
            self.open()
        });

        this.$root.$on('cartItemAdded', cartItem => {

            this.showSuccessAlert = true

            let itemExists = this.cartItems.filter(item => {
                return item.id === cartItem.id
            }).length !== 0

            if (itemExists) {
                this.cartItems = this.cartItems.map(item => {
                    if (item.id === cartItem.id) {
                        return cartItem
                    }
                    return item;
                })
            } else {
                this.cartItems.push(cartItem)
            }

            checkoutEcommerceEvent(this.availabeCartItems, 1);

            this.open()

            setTimeout(this.hideSuccessAlert, 3000)
        })
    },

    methods: {
        open() {
            this.isOpen = true;
            $('body').css('overflow-y', 'hidden')
        },

        openCheckoutLink() {
            location.href = this.checkoutUrl;
        },

        close() {
            this.isOpen = false;
            $('body').css('overflow-y', 'auto')
        },

        hideSuccessAlert(){
            this.showSuccessAlert = false
        }
    },
    computed: {

        isEmpty(){
            return this.cartItems.filter(item => {
                return item.deleted === false
            }).length === 0
        },

        totalItems(){
            return this.cartItems.filter(item => {
                return item.deleted === false
            }).length
        },

        availabeCartItems(){
            if (this.cartItems.length === 0) {
                return []
            }
            return this.cartItems.filter(item => {
                return item.deleted === false
            })
        },

        cartSubtotal(){

            if (this.cartItems.length === 0) {
                return 0
            }

            return this.cartItems.map(item => {
                if (item.deleted === true) {
                    return 0;
                }

                return item.bulkPrice * item.quantity
            })
            .reduce((accumulator, currentValue) => accumulator + currentValue)
        }
    }
};
</script>

<style lang="scss" scoped>
    .offcanvas-collapse {
        z-index: 9999;
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        width: 35%;
        overflow-y: auto;
        background-color: #fff;
        transition: -webkit-transform .3s ease-in-out;
        transition: transform .3s ease-in-out;
        transition: transform .3s ease-in-out, -webkit-transform .3s ease-in-out;
        -webkit-transform: translateX(100%);
        transform: translateX(100%);

        // lg
        @media (max-width: 1286px) {
            width: 44%;
        } 

        // lg
        @media (max-width: 1042px) {
            width: 49%;
        } 

        // lg
        @media (max-width: 900px) {
            width: 53%;
        } 

        // sm
        @media (max-width: 868px) {
            width: 55%;
        }

        // sm
        @media (max-width: 768px) {
            width: 65%;
        }
        
        @media (max-width: 668px) {
            width: 70%;
        }
        
        @media (max-width: 585px) {
            width: 85%;
        } 
        
        @media (max-width: 568px) {
            width: 90%;
        } 
        
        @media (max-width: 468px) {
            width: 100%;
        } 

        &.open {
            -webkit-transform: translateX(0%);
            transform: translateX(0%);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }
    }
</style>