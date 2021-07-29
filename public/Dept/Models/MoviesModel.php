<?php

require_once("BaseModel.php");

use Luracast\Restler\RestException;

class MoviesModel extends BaseModel
{

    public function getTop($page = 1)
    {

        if (class_exists('Memcached')) {
            $cache = new Memcached();
            $cache->addServer('127.0.0.1', 11211);

            if (($response = $cache->get("top" . $page)) != null) {
                return $response;
            } else {

                $url = "https://imdb-api.com/en/API/Top250Movies/" . $this->key;
                $results = $this->callApi($url);
                $response = $this->buildTopArray($results, $page);
                $cache->set("top" . $page, $response, 86400) or die("couldn't save anything");
                return $response;
            }
        }
    }

    private function buildTopArray($results, $page)
    {
        $response = array();
        $o = ($page - 1) * 4;
        $target = $o + 4;

        for ($i = $o; $i < $target; $i++) {

            array_push(
                $response,
                array(
                    "id" => $results->items[$i]->id,
                    "title" => $results->items[$i]->title,
                    "year" => $results->items[$i]->year,
                    "poster" => $results->items[$i]->image,
                    "crew" => $results->items[$i]->crew,
                    "rank" => $results->items[$i]->rank,
                    "imdbRating" => $results->items[$i]->imDbRating
                )
            );
        }

        return $response;
    }



    public function getInTheaters($page = 1)
    {
        if (class_exists('Memcached')) {
            $cache = new Memcached();
            $cache->addServer('127.0.0.1', 11211);

            if (($response = $cache->get("inTheaters" . $page)) != null) {
                return $response;
            } else {

                $url = "https://imdb-api.com/en/API/InTheaters/" . $this->key;
                $results = $this->callApi($url);
                if ($page > (count($results->items) / 4))
                    throw new RestException(400, "There are no results for this page");
                $response = $this->buildInTheatersArray($results, $page);
                $cache->set("inTheaters" . $page, $response, 86400);
                return $response;
            }
        }
    }

    private function buildInTheatersArray($results, $page)
    {

        $response = array();
        $o = ($page - 1) * 8;
        $target = $o + 8;

        for ($i = $o; $i < $target; $i++) {

            if (!isset($results->items[$i]))
                break;

            array_push(
                $response,
                array(
                    "id" => $results->items[$i]->id,
                    "title" => $results->items[$i]->title,
                    "year" => $results->items[$i]->year,
                    "poster" => $results->items[$i]->image,
                    "directors" => $results->items[$i]->directors,
                    "plot" => $results->items[$i]->plot,
                    "imdbRating" => $results->items[$i]->imDbRating,
                    "releaseDate" => $results->items[$i]->releaseState
                )
            );
        }

        return $response;
    }



    public function getMovie($id)
    {
        if (class_exists('Memcached')) {
            $cache = new Memcached();
            $cache->addServer('127.0.0.1', 11211);
            if (($response = $cache->get($id)) != null) {
                // Cache hit
                return $response;
            } else {
                // Cache miss
                //Request movie data from imdb
                $generalUrl = "https://imdb-api.com/en/API/Title/" . $this->key . "/" . $id;
                $generalResults = $this->callApi($generalUrl);
                //Request oficial youtube trailer Id 
                $trailerUrl = "https://imdb-api.com/en/API/YoutubeTrailer/" . $this->key . "/" . $id;
                $trailerResults = $this->callApi($trailerUrl);
                //Request youtube trailer info

                $videoUrl = 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id=' . $trailerResults->videoId . '&key=' . $this->youtubeKey;
                $videoResults = $this->callApi($videoUrl);

                //Search videos query = title + actor interview
                $in = str_replace(' ', '%20', $generalResults->title);
                $interviewVideosUrl = "https://youtube.googleapis.com/youtube/v3/search?part=snippet&q=" . $in . "%20actor%20interviews&key=" . $this->youtubeKey;
                $interviewVideosResults = $this->callApi($interviewVideosUrl);



                //build custom response with relevant data
                $response = $this->buildMovieArray($generalResults, $trailerResults, $videoResults, $interviewVideosResults);



                // Cache the request for performance in future requests  
                $cache->set($id, $response, 1209600);

                return $response;
            }
        } else {
            die("Error while connecting to cache server");
        }
    }

    private function buildMovieArray($generalResults, $trailerResults, $videoResults, $interviewVideosResults)
    {

        $actorList = array();
        if (isset($generalResults->actorList)) {
            foreach ($generalResults->actorList as $item) {
                array_push(
                    $actorList,
                    array(
                        "id" => $item->id,
                        "name" => $item->name,
                        "image" => $item->image,
                        "role" => $item->asCharacter,
                    ),
                );
            }
        }


        $interviewVideos = array();
        if (isset($interviewVideosResults->items)) {
            foreach ($interviewVideosResults->items as $item) {
                array_push(
                    $interviewVideos,
                    array(
                        "url" => "https://www.youtube.com/watch?v=" . $item->id->videoId,
                        "videoId" => $item->id->videoId,
                        "title" => $item->snippet->title,
                        "thumbnails" => array(
                            "small" => $item->snippet->thumbnails->default->url,
                            "medium" => $item->snippet->thumbnails->medium->url,
                            "large" => $item->snippet->thumbnails->high->url
                        ),
                    ),
                );
            }
        }


        $similarMovies = array();
        if (isset($generalResults->similars)) {
            foreach ($generalResults->similars as $i => $item) {
                array_push(
                    $similarMovies,
                    array(
                        "id" => $item->id,
                        "title" => $item->title,
                        "poster" => $item->image,
                        "year" => $item->year,
                        "imdbRating" => $item->imDbRating,
                    ),
                );
            }
        }

        $response = array(
            "id" => $generalResults->id,
            "title" => $generalResults->title,
            "year" => $generalResults->year,
            "genres" => $generalResults->genres,
            "poster" => $generalResults->image,
            "directors" => $generalResults->directors,
            "plot" => $generalResults->plot,
            "imdbRating" => $generalResults->imDbRating,
            "actorList" => $actorList,
            "youtubeTrailer" => array(
                "videoId" => $videoResults->items[0]->id,
                "url" => $trailerResults->videoUrl,
                "title" => $videoResults->items[0]->snippet->title,
                "duration" => $videoResults->items[0]->contentDetails->duration,
                "channelId" => $videoResults->items[0]->snippet->channelId,
                "channelName" => $videoResults->items[0]->snippet->channelTitle,
                "thumbnails" => array(
                    "small" => $videoResults->items[0]->snippet->thumbnails->default->url,
                    "medium" => $videoResults->items[0]->snippet->thumbnails->medium->url,
                    "large" => $videoResults->items[0]->snippet->thumbnails->high->url
                ),
                "stats" => array(
                    "likes" => $videoResults->items[0]->statistics->likeCount,
                    "dislikes" => $videoResults->items[0]->statistics->dislikeCount,
                    "views" => $videoResults->items[0]->statistics->viewCount,
                    "comments" => $videoResults->items[0]->statistics->commentCount,
                ),
            ),
            "interviewVideos" => $interviewVideos,
            "similarMovies" => $similarMovies,
        );

        return $response;
    }



    public function getSearchResults($search)
    {
        $input = htmlspecialchars($search);
        if (class_exists('Memcached')) {
            $cache = new Memcached();
            $cache->addServer('127.0.0.1', 11211);
            if (($response = $cache->get("search" . $input)) != null) {
                // Cache hit
                return $response;
            } else {
                //Cache miss
                $url = "https://imdb-api.com/en/API/SearchMovie/" . $this->key . "/" . $input;
                $searchResults = $this->callApi($url);

                if (!$searchResults->results) {
                    $newSearch = explode(" ", $search);
                    $nWords = count($newSearch);
                    for ($i = 0; $i < $nWords; $i++) {
                        $url = "https://imdb-api.com/en/API/SearchMovie/" . $this->key . "/" . htmlspecialchars($newSearch[$i]);
                        $searchResults = $this->callApi($url);
                        if ($searchResults->results)
                            break;
                    }
                    //Abandoned script
                    // if (isset($newSearch[1])){
                    //     $url = "https://imdb-api.com/en/API/SearchMovie/" . $this->key . "/" . htmlspecialchars($newSearch[0]);
                    //     $searchResults = $this->callApi($url);

                    //     if(!$searchResults->results){

                    //         $url = "https://imdb-api.com/en/API/SearchMovie/" . $this->key . "/" . htmlspecialchars($newSearch[1]);
                    //         $searchResults = $this->callApi($url);

                    //         if(!$searchResults->results && isset($newSearch[2])){

                    //             $url = "https://imdb-api.com/en/API/SearchMovie/" . $this->key . "/" . htmlspecialchars($newSearch[2]);
                    //             $searchResults = $this->callApi($url);
                    //             if(!$searchResults->results){
                    //                 throw new RestException(204, "No results");
                    //             }
                    //         }
                    //     }
                    // }
                }

                $response = $this->buildSearchArray($searchResults);
                $cache->set("search" . $input, $response, 86400);
                return $response;
            }
        } else {
            die("Error while connecting to cache server");
        }
    }

    private function buildSearchArray($searchResults)
    {
        $response = array();
        if (isset($searchResults->results)) {
            foreach ($searchResults->results as $item) {
                array_push(
                    $response,
                    array(
                        "id" => $item->id,
                        "title" => $item->title,
                        "year" => preg_replace('/[^0-9.]+/', '', $item->description),
                        "image" => $item->image,
                    ),
                );
            }
        }
        return $response;
    }
}