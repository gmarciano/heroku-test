<template>
  <div id="loader" class="fixed h-screen w-screen z-20 bg-gray-100">
    <div class="header items-center pl-10 pt-5 hidden md:flex">
      <h1 class="text-5xl text-gray-800 font-semibold">Moovie</h1>
    </div>

    <div class="content flex flex-col justify-center items-center">
      <h1 class="text-2xl text-gray-800 font-semibold md:hidden mb-4">Moovie</h1>

      <img src="images/loader.svg" alt="Loading..." class="w-1/4 md:2/12 lg:w-1/12" />
      <h3 class="text-base text-gray-700 font-light text-center montserrat mt-3 md:text-2xl">{{ percentage }}%</h3>
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

    @media screen and (max-width: 760px) {
      .content {
        height: 100vh;
      }
    }
  }
</style>
