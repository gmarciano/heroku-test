# Moovie
<br>

## About Moovie
Moovie is a web application designed at first to show a list of upcoming movies,
and for that consumes a third-party API (TMDb) as the main database.
The app is a MVP, and it's public, so feel free to submit a PR :)
<br>

You can access the [live demo here.](https://upcomingmoovie.herokuapp.com)
<br>

To run it on your environment all you gotta do is get a key for TMDb API, and run the following commands: 
```bash
$ composer install
$ npm install --save
$ npm run watch
```

<br>

## Architecture
As a PHP developer I made the project in Laravel using Laravel mix, this way we have:

| Stack    | Technology |
| -------- | ---------- |
| Backend  | Laravel    |
| Frontend | Vue        |

Beside this I opted to use tailwind as my CSS framework.

<br>

## More about it
The project was not designed, so I used what I know about UX/UI to made the front.
Any ideas about how to improve it will be welcomed.

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
