<template>
  <div id="list" class="h-full max-h-full max-w-full overflow-hidden">
    <vueper-slides class="no-shadow" ref="slider"
                   :breakpoints="slideBreakpoints"
                   :arrows-outside="false"
                   :bullets="false"
                   :infinite="false"
                   :touchable="isMobile"
                   :visible-slides="4"
                   @before-slide="handleChange($event)">
      <vueper-slide v-for="(movie, mi) in shownMovies" :key="mi" :class="{ 'active': active === mi }">
        <template v-slot:content>
          <div class="card bg-white max-h-full rounded-t-lg shadow-2xl text-center overflow-hidden mx-auto md:rounded-b-lg"
               :class="[ active === mi ? 'focused h-full p-0 w-11/12 md:flex lg:w-full' : 'bg-gray-200 p-1 mt-16 md:mt-0' ]"
               @click="goToMovie(mi)">
            <div class="card-image max-w-full overflow-hidden relative p-1 rounded-b-lg md:rounded-b-none md:p-0 lg:h-full lg:rounded-l-lg"
                 :class="[ active === mi ? 'md:w-1/2' : 'darker lg:rounded-r-lg' ]"
                 :style="[ active === mi ? { 'maxHeight': posterHeight } : '' ]">
              <img :src="movie.poster_path ? `${imagePath}${movie.poster_path}` : 'images/404.svg'"
                   :alt="movie.title + ' Poster' "
                   class="h-full w-full rounded-lg md:rounded-none"
                   :class="{ 'opacity-75 bg-gray-300': ! movie.poster_path }" />

              <div class="info absolute py-2 px-1 flex justify-end rounded-b-lg md:hidden"
                   :class="[ posterHeight === '45%' ? 'bottom-0' : 'bottom' ]">
                <button @click.prevent="tooglePoster()">
                  <img src="images/info.svg" alt="More info" class="info-icon w-6" />
                </button>
              </div>
            </div>

            <div class="card-data max-h-full max-w-full text-center overflow-hidden md:text-left px-5 py-3 md:py-6"
                 :class="{ 'md:w-1/2': active === mi }"
                 v-show="active === mi">
              <div class="flex items-center justify-center md:justify-between">
                <div :class="{ 'w-4/6': movie.adult } ">
                  <h3 class="text-lg md:text-xl lg:text-2xl text-gray-800 font-semibold mb-2 leading-none">
                    {{ movie.title }}
                  </h3>
                </div>

                <div v-if="movie.adult" class="w-1/6">
                  <img class="w-6" alt="Adult movie" src="images/adult.svg" />
                </div>
              </div>

              <div id="movie-genres" class="flex flex-wrap -mx-2 opacity-75 justify-center md:justify-start">
                <div v-for="(genre, gi) in movie.genre_ids"
                     class="rounded-full px-2 py-1 m-1"
                     :class="[ genres[genre].replace(' ', '-').toLowerCase() ]">
                  <p class="text-xs text-gray-100 mb-0">{{ genres[genre] }}</p>
                </div>
              </div>

              <div v-if="active === mi">
                <p class="description text-sm text-gray-800 my-3 overflow-auto">
                  {{ movie.overview || 'No description available' }}
                </p>
              </div>

              <p class="text-sm text-red-700 font-semibold mt-2">
                Coming out {{ movie.release_date | formatDate }}
              </p>
            </div>
          </div>
        </template>
      </vueper-slide>
    </vueper-slides>
  </div>
</template>

<script>
  import moment from 'moment';
  import { VueperSlides, VueperSlide } from 'vueperslides';
  import 'vueperslides/dist/vueperslides.css';

  export default {
    name: 'List',
    components: {
      VueperSlides,
      VueperSlide
    },
    props: [ 'genres', 'movies', 'imagePath', 'isMobile' ],
    data() {
      return {
        shownMovies: [],
        shownMoviesLength: 7,
        active: 3,
        posterHeight: '100%',
        slideBreakpoints: {
          1200: {
            arrows: false,
            draggingDistance: 200,
            visibleSlides: 1
          }
        }
      }
    },
    filters: {
      formatDate(date) {
        return moment(date).format('DD/MM/YYYY')
      }
    },
    methods: {
      handleChange(event, index = false) {
        if (index) {
          if (index > this.active) {
            while (index > this.active) {
              this.$emit('updateMovies', { action: 'forward' });
              index--;
            }
          } else if (index < this.active) {
            while (index < this.active) {
              this.$emit('updateMovies', { action: 'back' });
              index++;
            }
          }
        } else {
          if (event.currentSlide.index === 0 && event.nextSlide.index === 6) {
            this.$emit('updateMovies', { action: 'back' });
          } else if (
            event.currentSlide === undefined ||
            event.currentSlide.index < event.nextSlide.index ||
            event.currentSlide.index === 6 && event.nextSlide.index === 0
          ) {
            this.$emit('updateMovies', { action: 'forward' });
          } else {
            this.$emit('updateMovies', { action: 'back' });
          }
        }

        const backdrop = `${this.imagePath}${this.movies[this.active].backdrop_path}`;
        this.$emit('changeBackdrop', backdrop);
      },
      goToMovie(movieIndex) {
        this.handleChange(false, movieIndex);
      },
      tooglePoster() {
        this.posterHeight = this.posterHeight === '100%' ? '45%' : '100%';
      },
    },
    watch: {
      movies(newValue) {
        this.shownMovies = newValue.slice(0, this.shownMoviesLength);

        if (this.shownMovies.length < 7) {
          this.active = Math.floor(this.shownMovies.length / 2);
        } else {
          this.active = 3;
        }
      }
    }
  }
</script>

<style lang="scss">
  .card {
    .card-image {
      .info {
        left: 0.25rem;
        right: 0.25rem;
        background: linear-gradient(to top, #0000008c, transparent);

        &.bottom {
          bottom: 0.25rem;
        }

        .info-icon {
          filter: invert(1);
        }
      }
    }

    .card-data {
      .description {
        max-height: 150px;

        @media screen and (min-width: 1024px) {
          max-height: 240px;
        }
      }
    }

    @media screen and (max-width: 760px) {
      height: 85vh;
    }
  }

  .vueperslides__arrows {
    height: 15vh;
    position: fixed;
    right: 0;
    bottom: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 30;

    .vueperslides__arrow {
      border: 1px solid #ffffff;
      border-radius: 75px;
      margin: 0 15px;
      position: unset;
      outline: none !important;
      transform: none;

      &--prev {
        padding: 0 4px 0 2px;
      }

      &--next {
        padding: 0 2px 0 4px;
      }

      svg {
        width: 2.5em;
      }
    }
  }

  .vueperslides__parallax-wrapper {
    padding-bottom: 200% !important;

    .vueperslides__track {
      height: 87vh;
      padding: 0 15px;

      .vueperslides__track-inner {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        transform: none !important;

        .vueperslide {
          max-height: 100%;
        }
      }

      @media screen and (min-width: 768px) {
        padding: 0;

        .vueperslides__track-inner {
          display: flex;
          align-items: center;
        }
      }

      @media screen and (min-width: 1024px) {
        height: 80vh;
        padding: 0 115px;

        .vueperslides__track-inner {
          .vueperslide {
            margin: 0 15px;
            width: 22% !important;

            &.active {
              height: 75%;
              width: 50% !important;

              img {
                height: 100%;
              }
            }

            .card {
              transition: max-height 300ms, max-width 300ms;
            }

            &:not(.active) {
              height: 66%;

              .card {
                height: 100%;
              }
            }
          }
        }
      }
    }
  }

  ::-webkit-scrollbar {
    width: .25em;
  }
  ::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  }
  ::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: .25em solid slategrey;
  }
</style>
