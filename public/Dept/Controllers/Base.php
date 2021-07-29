<?php

use Luracast\Restler\RestException;

class Base
{
  public function buildApiResponse($code = 200, $data = array()){
        $response = array(
            "code" => $code,
            "totalItems" => count($data),
            "data" => $data
        );
      return $response;
  }
}