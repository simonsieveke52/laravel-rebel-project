<template>
    <div class="px-1 py-1 pl-sm-2 pr-sm-1 py-sm-2" v-if="item.deleted !== true" @click.parent.stop>
        <div class="d-flex flex-row flex-nowrap flex-nowrap justify-content-center align-items-center">

            <div class="d-flex align-items-center justify-content-center bg-white h-auto w-auto p-1 rounded-lg" style="min-width: 105px; min-height: 105px;">
                <img   
                    @click.stop.prevent
                    :src="item.attributes.main_image" 
                    class="img-fluid max-h-100px max-w-100px mx-auto" 
                    :alt="item.name" 
                >
            </div>

            <div class="flex-column py-2 px-2 w-100 align-items-start justify-content-start d-flex">
                <a class="mb-2 d-block text-wrap small text-left" :href="route('product.show', item.id).url()">
                    {{ item.name }}
                </a>
                <div class="btn-group">
                    <button type="button" @click.prevent="reduceQuantity" class="btn btn-highlight">
                        -
                    </button>
                    <input @click.prevent type="number" value="1" class="form-control min-w-50px max-w-70px rounded-0" min="1" v-model.number="quantity">
                    <button type="button" @click.prevent="raiseQuantity" class="btn btn-highlight">
                        +
                    </button>
                </div>
            </div>

            <div class="py-2 text-dark text-right px-2" @click.stop.prevent>
                <div class="text-right">
                    <div>
                        <small v-if="item.bulkPrice < item.price" class="text-decoration-line-through text-danger small">
                            {{ item.price | currency }}
                        </small>
                        {{ item.bulkPrice | currency }}
                    </div>
                    <div class="small" v-if="quantity > 1">(+ {{ percentDiscount(quantity) }}% off) Bulk Discount</div>
                </div>
            </div>

            <button class="btn text-hover-danger" aria-label="remove item from cart" @click.stop.prevent="deleteItem()">
                <i class="fa fa-trash-alt"></i>
            </button>

        </div>
    </div>
</template>

<script>
export default {

    props: ['item'],

    data() {
        return {
            ajaxRequest: null
        }
    },

    methods: {

        reduceQuantity() {
            if(this.quantity > 1) {
                this.quantity -= 1;
            }
        },

        raiseQuantity() {
            this.quantity += 1;
        },

        deleteItem(){

            if (! confirm('Are you sure you want to remove ' + this.item.name + ' from your cart?')) {
                return true;
            } 

            let self = this;

            $.ajax({
                url: '/cart/' + this.item.id,
                type: 'DELETE'
            })
            .done(function() {
                self.item.deleted = true
                self.$root.$emit('cartItemDeleted', self.item)
            })
            .fail(function() {
                alert('Please refresh this page and try again')
            })
        },
        percentDiscount(quantity) {
            if([2, 3].includes(quantity)) {
                return 4;
            }
            if([4, 5, 6].includes(quantity)) {
                return 5;
            }

            return 6;
        }
    },
    computed: {

        quantity: {
            
            // getter
            get: function () {
                return this.item.quantity
            },

            // setter
            set: function (newValue) {

                let oldValue = this.item.quantity;

                if (newValue > 0) {
                    this.item.quantity = newValue
                } else {
                    this.item.quantity = 1
                }

                try {
                    if (this.ajaxRequest !== null) {
                        this.ajaxRequest.abort();
                    }
                } catch (e) {

                }

                let self = this

                this.ajaxRequest = $.ajax({
                    url: '/cart/' + this.item.id,
                    type: 'PUT',
                    data: {
                        quantity: newValue
                    },
                })
                .done(function(data) {
                    if(typeof data === 'object' && 'maxQuantity' in data) {
                        self.quantity = data.maxQuantity;
                        alert(data.message);
                    }

                    if (typeof data === 'number' && parseInt(data) > 0) {
                        self.item.bulkPrice = data;
                    }

                    self.$root.$emit('cartItemUpdated', self.item)
                })
                .fail(function(response) {
                    try {
                        alert(response.responseJSON.message)
                        self.item.quantity = oldValue;
                    } catch (e) {

                    }
                })

            }
        }
    }
};
</script>