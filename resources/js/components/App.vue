<template>
  <div class="flex flex-col justify-between max-h-screen h-screen max-w-screen overflow-hidden">
    <div id="backdrop" class="fixed top-0 bottom-0 left-0 right-0 opacity-75 darker"
         :style="{ backgroundImage: `url(${backdrop})` }">
    </div>

    <Header
      :is-mobile="isMobile"
      @doSearch="doSearch">
    </Header>

    <LoaderComponent
      v-if="loading"
      :percentage="percentage">
    </LoaderComponent>

    <ListComponent
      :genres="genres"
      :movies="movies"
      :image-path="imagePath"
      :is-mobile="isMobile"
      @changeBackdrop="changeBackdrop"
      @updateMovies="changeMoviesArray">
    </ListComponent>
  </div>
</template>

<script>
  import Header from "./Header";
  import LoaderComponent from "./LoaderComponent";
  import ListComponent from "./ListComponent";

  export default {
    name: "App",
    components: {
      Header,
      LoaderComponent,
      ListComponent
    },
    data() {
      return {
        backdrop: '',
        genres: {},
        movies: [],
        allMovies: [],
        page: 1,
        totalPages: 1,
        imagePath: 'https://image.tmdb.org/t/p/original',
        isMobile: false,
        search: '',
        genre: false,
        loading: true,
        percentage: 0,
        showList: 0
      }
    },
    methods: {
      changeBackdrop(backdrop) {
        this.backdrop = backdrop;
      },
      changeMoviesArray({ action }) {
        if (action === 'forward') {
          const removedEl = this.movies.shift()
          this.movies.push(removedEl)
        } else {
          const removedEl = this.movies.pop()
          this.movies.unshift(removedEl)
        }
      },
      async getUpcomingMovies() {
        this.loading = true;

        const { data } = await axios.get(`/api/upcomingMovies?page=${this.page}`);

        this.allMovies = this.allMovies.concat(data.results);
        this.backdrop = `${this.imagePath}${this.allMovies[0].backdrop_path}`;
        this.totalPages = data.total_pages;
        this.percentage = Math.ceil((this.page / this.totalPages) * 100);

        if (this.page <= this.totalPages) {
          this.page++;
          this.getUpcomingMovies();
        } else {
          for (let i = 0; i < 3; i++) {
            const removedEl = this.allMovies.pop();
            this.allMovies.unshift(removedEl);
          }

          this.movies = this.allMovies;
          this.loading = false;
        }
      },
      doSearch(search) {
        this.search = search;

        if (! search.length) {
          this.movies = this.allMovies;
          return;
        }

        this.movies = [];

        const searchRegex = new RegExp(search, 'i');

        this.movies = this.allMovies.filter((movie, index) => {
          return movie.title.match(searchRegex);
        });

        if (this.movies.length) {
          this.backdrop = `${this.imagePath}${this.movies[0].backdrop_path}`
        } else {
          this.backdrop = this.isMobile ? 'images/404.svg' : 'images/404-md.svg';
        }
      }
    },
    async mounted() {
      if (window.screen.availWidth <= 768) this.isMobile = true;

      const { data: { genres } } = await axios.get('/api/genreList');

      this.genres = genres.reduce((map, next)  => {
        map[next.id] = next.name;
        return map;
      }, {});

      this.getUpcomingMovies();
    }
  }
</script>

<style lang="scss">
  #app {
    img {
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      user-select: none;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0);

      &:focus {
        outline: none;
      }
    }

    #backdrop {
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }

    #search-input {
      &::placeholder {
        color: #f3f3f3;
      }
      &:hover, &:active, &:focus {
        outline: none;
      }
    }
  }
</style>
