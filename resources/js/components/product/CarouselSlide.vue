<template>
  <img
    v-show="visibleIndex === index"
    class="carousel-slide"
    :src="getImgUrl(image)"
    alt="Product image"
  />
</template>

<script>
export default {
  props: {
    image: {
      type: String,
      default() {
        return "";
      },
    },
    index: {
      type: Number,
      default() {
        return 0;
      },
    },
    visibleIndex: {
      type: Number,
      default() {
        return 0;
      },
    },
  },
  methods: {
    getImgUrl(pic) {
      return require(`https://www.sartorius.com/resource/image/10550/16x9/1050/590/acaab72df96332f5fe32ffeed6945536/3E22CB7B8D31ABA8AFD92036412503D8/lt-filtratio-6907.jpg`);
    },
  },
};
</script>

<style scoped>
@media (min-width: 1024px) {
  .carousel-slide {
    border-radius: var(--border-radius-size);
    cursor: pointer;
  }
}
</style>
