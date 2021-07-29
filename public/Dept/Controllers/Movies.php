<?php

use Luracast\Restler\RestException;

require_once "Models/MoviesModel.php";
require_once __DIR__ . "/../Controllers/Base.php";

class Movies extends Base
{

    public function __construct()
    {
        $this->dp = new MoviesModel();
    }

    /**
     * @access public
     * 
     * @param string $id {@from path}
     */
    public function get($id)
    {
        $data = $this->dp->getMovie($id);
        return $this->buildApiResponse(200, $data);
    }
    /**
     * @access public
     *  
     * @param int $page
     */
    public function getTop($page)
    {

        $page = intval($page);
        $data = $this->dp->getTop($page);

        return $this->buildApiResponse(200, $data);
    }
    /**
     * @access public
     * 
     * @param int $page
     */
    public function getInTheaters($page)
    {
        $data = $this->dp->getInTheaters($page);
        return $this->buildApiResponse(200, $data);
    }

    /**
     * @access public
     * 
     * 
     * @param string $search {@from path}
     * 
     */
    public function getSearch($search)
    {

        if (empty($search)) {
            throw new RestException(400, "Search cannot be empty");
        }
        $data =  $this->dp->getSearchResults($search);

        return $this->buildApiResponse(200, $data);
    }
}