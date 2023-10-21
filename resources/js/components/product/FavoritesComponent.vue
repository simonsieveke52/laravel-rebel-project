<template>
    <span class="fa-layers fa-fw m-0 position-relative d-flex flex-row">
        <i class="fas fa-heart text-black" style="font-size: 1.7rem; padding-bottom: 0.35rem;"></i>
        <span 
            class="fa-layers-counter bg-highlight text-white d-flex flex-column align-items-center justify-content-center" 
            style="font-size: 0.7rem; position: absolute; border-radius: 50%; height: 20px; width: 20px; right: -20px; top: 0;"
        >
            <span class="d-flex">
                {{ favoritesCounter }}
            </span>
        </span>
    </span>
</template>
<script>
export default {

    props: {
        cssClass: {
            type: String,
            default: 'text-muted'
        }
    },

    data() {
        return {
            favoritesCounter: 0,
        }
    },

    mounted() {
        this.favoritesCounter = this.getFavoritesCounter();

        this.$root.$on('favorites_updated', () => {
            this.favoritesCounter = this.getFavoritesCounter();            
        })
    },

    methods : {
        getFavoritesCounter() {
            try {
                let favorites = localStorage.getItem('favorites')
                if (favorites === null || favorites === undefined) {
                    localStorage.setItem('favorites', JSON.stringify([]))
                    return 0;
                }
                return JSON.parse(favorites).length;
            } catch (e) {
                return 0;
            }
        }
    },
};
</script>