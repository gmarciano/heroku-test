<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class MoviesController extends Controller {

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getUpcomingMovies() {
        $data = json_decode($this->getTMDbData(
            config('tmdb.UPCOMING_LIST'),
            "&page={$this->request->page}"
        ), true);

        $tomorrow = Carbon::tomorrow();

        $upcomingMovies = [];

        foreach ($data['results'] as $index => $movie) {
            $releaseDate = Carbon::rawCreateFromFormat('Y-m-d', $movie['release_date']);

            if ($releaseDate > $tomorrow) {
                $upcomingMovies[] = $data['results'][$index];
            }
        }

        $data['results'] = $upcomingMovies;

        return response()->json($data);
    }

    public function getGenreList() {
        $genres = [];

        $data = json_decode($this->getTMDbData(config('tmdb.GENRE_LIST')), true);

        foreach ($data['genres'] as $genre) {
            $genres[$genre['id']] = $genre['name'];
        }

        return response()->json($data);
    }

    private function getTMDbData($path, $optional = '') {
        $lang = strstr($this->request->server('HTTP_ACCEPT_LANGUAGE'), ',', true);

        $url = env('TMDB_HOST') . "{$path}?api_key=" . env('TMDB_KEY')
            . "&language={$lang}{$optional}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $err = curl_errno($curl);

        if ($err) {
            throw new \Exception(curl_error($curl), $err);
        }

        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }
}
