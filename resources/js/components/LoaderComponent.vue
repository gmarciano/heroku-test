<template>
  <div id="loader" class="fixed h-screen w-screen z-20 bg-gray-100">
    <div class="header flex items-center pl-10 pt-5">
      <h1 class="text-5xl text-gray-800 font-semibold">Moovie</h1>
    </div>

    <div class="content flex flex-col justify-center items-center">
      <img src="images/loader.svg" alt="Loading...">
      <h3 class="text-2xl text-gray-700 font-light text-center montserrat mt-3">{{ percentage }}%</h3>
    </div>
  </div>
</template>

<script>
  export default {
    name: "LoaderComponent.vue",
    props: [ 'percentage' ]
  }
</script>

<style lang="scss">
  #loader {
    img {
      animation: rotation 1.5s infinite;
      animation-timing-function: linear;
      filter: invert(.1);
    }

    @keyframes rotation {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(350deg);
      }
    }
  }
</style>
