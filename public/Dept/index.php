<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once "../../restler.php";
//require_once("Auth/AccessControl.php");

use Luracast\Restler\Defaults;
use Luracast\Restler\Explorer;
use Luracast\Restler\Filter\RateLimit;
use Luracast\Restler\Restler;




// Defaults::$crossOriginResourceSharing = true;
// Defaults::$accessControlAllowOrigin = 'https://www.revistarad.site';
// //Defaults::$accessControlAllowOrigin = '*';



//RateLimit::setLimit('hour', 300);

Explorer\v2\Explorer::$hideProtected = false;

$r = new Restler();

$r->setSupportedFormats('JsonFormat', 'HtmlFormat', 'UploadFormat');
//$r->setOverridingFormats('HtmlFormat', 'UploadFormat', 'JsonFormat');


/*///////////////////////////////////////////////////
                    CLASSES
//////////////////////////////////////////////////*/

$r->addAPIClass('Explorer');

//DEBUG
require_once("Controllers/Say.php");
$r->addAPIClass("Say");

require_once("Controllers/Movies.php");
$r->addAPIClass("Movies");
//$r->addAPIClass("Access");

// // $r->addAPIClass("Math");

//AUTH
//$r->addAuthenticationClass('AccessControl');




//$r->addFilterClass('RateLimit');

/*///////////////////////////////////////////////////
                    
//////////////////////////////////////////////////*/

$r->handle();