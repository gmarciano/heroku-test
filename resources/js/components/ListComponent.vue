<template>
  <div id="list" class="h-full max-h-full max-w-full overflow-hidden">
    <vueper-slides class="no-shadow" ref="slider"
                   disable-arrows-on-edges
                   :breakpoints="slideBreakpoints"
                   :arrows-outside="false"
                   :bullets="false"
                   :infinite="false"
                   :touchable="isMobile"
                   :visible-slides="4"
                   @before-slide="handleChange($event)">
      <vueper-slide v-for="(movie, mi) in movies" :key="mi" :class="{ 'active': active === mi }">
        <template v-slot:content>
          <div class="card bg-white max-h-full rounded-t-lg shadow-2xl text-center overflow-hidden md:rounded-b-lg lg:mx-auto"
               :class="[ active === mi ? 'focused flex h-full w-11/12 md:w-full p-0' : 'bg-gray-200 p-1 mt-16 md:mt-0' ]"
               @click="goToMovie(mi)">
            <div class="card-image h-full max-w-full overflow-hidden rounded-l-lg relative"
                 :class="[ active === mi ? 'md:w-1/2' : 'darker rounded-r-lg' ]">
              <img :src="movie.poster_path ? `${imagePath}${movie.poster_path}` : 'images/404.svg'"
                   :alt="movie.title + ' Poster' "
                   class="h-full w-full" :class="{ 'opacity-75 bg-gray-300': ! movie.poster_path }"
                   @click.prevent="tooglePoster(mi)"
                   @mouseover.prevent="expandPoster(mi)"
                   @mouseleave.prevent="shrinkPoster(mi)" />
            </div>

            <div class="card-data max-h-full max-w-full text-center overflow-auto md:text-left px-5 lg:py-6"
                 :class="{ 'md:w-1/2': active === mi }"
                 v-show="active === mi">
              <div class="flex items-center justify-between">
                <div :class="{ 'w-4/6': movie.adult } ">
                  <h3 class="text-2xl md:text-4xl lg:text-3xl text-gray-800 font-semibold mb-2">
                    {{ movie.title }}
                  </h3>
                </div>

                <div v-if="movie.adult" class="w-1/6">
                  <img class="w-6" alt="Adult movie" src="images/adult.svg" />
                </div>
              </div>

              <div id="movie-genres" class="flex flex-wrap -mx-2 opacity-75">
                <div v-for="(genre, gi) in movie.genre_ids"
                     class="rounded-full px-2 py-1 m-1 cursor-pointer"
                     :class="[ genres[genre].replace(' ', '-').toLowerCase() ]">
                  <p class="md:text-xl md:text-sm lg:text-xs text-gray-100 mb-0">{{ genres[genre] }}</p>
                </div>
              </div>

              <div v-if="active === mi">
                <p class="text-xl md:text-2xl lg:text-base text-gray-800 my-3">
                  {{ movie.overview || 'No description available' }}
                </p>
              </div>

              <p class="text-lg md:text-xl lg:text-sm text-gray-600 font-semibold mt-2">
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
        active: 0,
        posterHeight: '45%',
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
        // Force arrow to show after first change
        if (this.currentSlide === undefined) {
          const arrowPrev = document.getElementsByClassName('vueperslides__arrow--prev')[0];
          arrowPrev.style.display = 'inline-block';
        }

        if (event.nextSlide || index !== false) {
          this.active = index === false ? event.nextSlide.index : index;

          const backdrop = `${this.imagePath}${this.movies[this.active].backdrop_path}`;
          this.$emit('changeBackdrop', backdrop);
        }
      },
      goToMovie(movieIndex) {
        this.handleChange(false, movieIndex);
        this.$refs.slider.goToSlide(movieIndex);
      },
      expandPoster(index) {
        if (index === this.active) this.posterHeight = '100%';
      },
      shrinkPoster(index) {
        if (index === this.active) this.posterHeight = '45%';
      },
      tooglePoster(index) {
        if (index === this.active) {
          this.posterHeight = this.posterHeight === '45%' ? '100%' : '45%';
        }
      }
    },
    updated() {
      const sliderInner = document.getElementsByClassName('vueperslides__track-inner')[0],
        transitionValues = sliderInner.style.transform.split(/\w+\(|\);?/),
        matrix = transitionValues[1].split(/,\s?/g).map(parseInt);

      if (matrix[0] > 0) sliderInner.style.transform = 'translate3d(0, 0, 0)';
    }
  }
</script>

<style lang="scss">
  .vueperslides__arrows {
    height: 85vh;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;

    .vueperslides__arrow {
      outline: none !important;

      // Prevents arrow to showing up before needed
      &--prev {
        display: none;
      }
    }
  }

  .vueperslides__parallax-wrapper {
    padding-bottom: 200% !important;

    .vueperslides__track {
      height: 85vh;
      padding: 0 15px;

      .vueperslides__track-inner {
        display: flex;
        align-items: flex-end;
      }

      @media screen and (min-width: 1024px) {
        height: 83vh;
        padding: 0 115px;

        .vueperslides__track-inner {
          display: flex;
          align-items: center;

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

  #movie-genres {
    .action {
      background-color: #f50000;
    }
    .adventure {
      background-color: #F17105;
    }
    .animation {
      background-color: #402536;
    }
    .crime {
      background-color: #4E1A0F;
    }
    .comedy {
      background-color: #FFB018;
      color: #664909 !important;
    }
    .documentary {
      background-color: #262E31;
    }
    .drama {
      background-color: #640aa8;
    }
    .family {
      background-color: #A5B452;
      color: #474D23 !important;
    }
    .fantasy {
      background-color: #BC401A;
    }
    .history {
      background-color: #A0522D;
    }
    .horror {
      background-color: #150506;
    }
    .music {
      background-color: #1846B3;
    }
    .mystery {
      background-color: #2E4052;
    }
    .romance {
      background-color: #D11149;
    }
    .science-fiction {
      background-color: #181E38;
    }
    .thriller {
      background-color: #12101D;
    }
    .tv-movie {
      background-color: #C89F9C;
    }
    .war {
      background-color: #184a0b;
    }
    .western {
      background-color: #8B4513;
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
