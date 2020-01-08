<?php

Route::get('/genreList', '\App\Api\V1\Controllers\MoviesController@getGenreList');
Route::get('/upcomingMovies', '\App\Api\V1\Controllers\MoviesController@getUpcomingMovies');
