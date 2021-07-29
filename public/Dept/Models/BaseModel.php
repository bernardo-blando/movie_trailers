<?php

require_once '../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotEnv = Dotenv::createUnsafeImmutable(__DIR__ . "/../");
$dotEnv->load();

// require_once '../../vendor/vimeo/vimeo-api/autoload.php';
use Vimeo\Vimeo;

class BaseModel
{
    protected $key;
    protected $omdbKey;
    protected $youtubeKey;
    function __construct()
    {
        $this->key = $_ENV["IMDB_API_KEY3"];
        $this->omdbKey = $_ENV["OMDB_API_KEY"];
        $this->youtubeKey = $_ENV["YOUTUBE_API_KEY"];
    }



    protected static function callApi($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);
        return $response;
    }

    // protected function callVimeoApi($endPoint, $params)
    // {

    //     $client = new Vimeo($_ENV["VIMEO_CLIENT_ID], $_ENV["VIMEO_CLIENT_SECRET], $_ENV["VIMEO_CLIENT_TOKEN]);

    //     $response = $client->request($endPoint, $params, 'GET');
    //     return $response;
    // }


}