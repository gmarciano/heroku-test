# Moovie
<br>

## About Moovie
Moovie is a web application designed at first to show a list of upcoming movies,
and for that it consumes a third-party API (TMDb) as the main database.
The app is a MVP, and it's public, so feel free to submit a PR :)
<br>

You can access the [live demo here.](https://upcomingmoovie.herokuapp.com)
<br>

To run it on your environment all you gotta do is run the following commands: 
```bash
$ composer install
$ npm install --save
$ npm run watch
```
After that you need to get a [TMDb API key](https://developers.themoviedb.org/3/getting-started/introduction) and set it in the **.env** file

<br>

## Architecture
As a PHP developer I made the project in Laravel using Laravel mix, this way we have:

| Stack    | Technology |
| -------- | ---------- |
| Backend  | Laravel    |
| Frontend | Vue        |

Besides this I opted to use tailwind as my CSS framework.

<br>

## Overall impression
The project had no layout design, so I used my knowledge in UX and UI to come up with it.
###### *(Any ideas about how to improve it will be welcomed)*
Also, I had a 5 days deadline to delivery this project, so it was made in a kinda of rush, but there are some fixes that I gotta do.

<br>

## Things to improve
- [ ] Change the 'Comming out' layout
- [ ] Create a loading response while searching
- [ ] Create a filter for movie genre on click
- [ ] Create a language manager
- [ ] Create my own list component and remove vueperslides

<br>

## Libraries
List of default libraries for Laravel Mix:

- **[axios](https://github.com/axios/axios)** used for Promise API requests
- **[cross-env](https://github.com/kentcdodds/cross-env)** used for facilitating setting env variables
- **[laravel-mix](https://github.com/JeffreyWay/laravel-mix)** used for compiling the assets
- **[lodash](https://github.com/lodash/lodash)** used with debounce
- **[resolve-url-loader](https://github.com/bholloway/resolve-url-loader)** used for resolving sass url rewriting 
- **[sass](https://github.com/sass/dart-sass)** used for importing sass
- **[sass-loader](https://github.com/webpack-contrib/sass-loader)** used to compile sass
- **[vue](https://github.com/vuejs/vue)** used as my frontend framework
- **[vue-template-compiler](https://github.com/vuejs/vue)** used for pre-compiling templates

List of non default libraries:
- **[moment](https://github.com/moment/moment)** used for easy date formatting
- **[tailwindcss](https://github.com/tailwindcss/tailwindcss)** used as my CSS framework
- **[vueperslides](https://github.com/antoniandre/vueper-slides)** used for the slider component
