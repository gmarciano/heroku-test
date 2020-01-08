<template>
  <div class="flex justify-between items-center px-10 py-5 z-10 header">
    <h1 class="text-5xl font-semibold" v-show="! isMobile || ! showSearch">Moovie</h1>

    <div id="search" class="flex items-center h-full">
      <div class="flex items-center py-2" :class="{ 'border-b': ! isMobile || showSearch }">
        <img class="w-6 cursor-pointer" alt="Search icon" src="images/search-icon.svg"
             @click.prevent="expandSearch()" />
        <input id="search-input" ref="search"
               class="bg-transparent ml-2 w-0 md:w-64 text-xl"
               :class="{ 'w-full': showSearch }"
               v-model="search" @input.prevent="doSearch()" @keyup.enter="doSearch(true)"
               type="text" placeholder="Search..." />
      </div>
    </div>
  </div>
</template>

<script>
  import _ from "lodash";

  export default {
    name: "Header",
    props: [ 'isMobile' ],
    data() {
      return {
        search: '',
        showSearch: false
      }
    },
    methods: {
      doSearch: _.debounce(function(force = false) {
        if (this.isMobile && ! force) return;

        this.showSearch = false;

        this.$emit('doSearch', this.search);
      }, 500),
      expandSearch() {
        this.showSearch = true;
        this.$refs.search.focus();
      }
    }
  }
</script>

<style scoped>

</style>
