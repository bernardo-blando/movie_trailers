<?php

use \Luracast\Restler\iAuthenticate;
use \Luracast\Restler\Resources;
use \Luracast\Restler\Defaults;
use Luracast\Restler\RestException;

/**
 * @access private
 */
class AccessControl implements iAuthenticate
{
    private static $key = 'arevistaRADeamelhorrevistadeativismo'; //Application Key
    protected static $requires = 'user';
    protected static $role = 'user';


    private static function decodePayload($encodedPayload)
    {
        return json_decode(self::urlsafeB64Decode($encodedPayload));
    }
    public static function isAdmin()
    {
        $user = self::getLoggedUser();
        if ($user->role != "admin") {
            return false;
        }
        return true;
    }
    public static function isLoggedUser($id)
    {
        $user = self::getLoggedUser();

        if ($user->id == $id) {
            return true;
        }
        return false;
    }
    public static function getLoggedUser()
    {
        if (self::verifyToken()) {
            $token = self::receiveAndParseToken();
            $r = self::decodePayload($token["payload"]);
            return $r;
        } else {
            throw new RestException("not allowed");
        }
    }
    /**
     * @access public
     *
     */
    private static function receiveAndParseToken()
    {
        $http_header = apache_request_headers();
        if (isset($http_header['Authorization']) && $http_header['Authorization'] != null) {
            $bearer = explode(' ', $http_header['Authorization']);

            // //$bearer[0] = 'bearer';
            // //$bearer[1] = 'token jwt';

            $fulltoken = explode('.', $bearer[1]);

            $token["header"] = $fulltoken[0];
            $token["payload"] = $fulltoken[1];
            $token["sign"] = $fulltoken[2];

            return $token;
        }
        return null;
    }
    /**
     * @access public
     *
     */
    public static function verifyToken()
    {
        $token = self::receiveAndParseToken();
        if (isset($token)) {
            //Conferir Assinatura
            $valid = hash_hmac('sha256', $token["header"] . "." . $token["payload"], self::$key, true);
            $valid = self::base64UrlEncode($valid);
            if ($token["sign"]  === $valid) {
                return true;
            }
        }

        return false;
    }
    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    private static function urlsafeB64Decode($input)
    {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }

        return \base64_decode(\strtr($input, '-_', '+/'));
    }

    public function __isAllowed()
    {
        $roles = array('user' => 'user', 'admin' => 'admin');
        $userClass = Defaults::$userIdentifierClass;
        if ($this::verifyToken()) {
            $token = $this->receiveAndParseToken();
            if (isset($token)) {
                $payload = $this->decodePayload($token["payload"]);

                if (!array_key_exists($payload->role, $roles)) {
                    $userClass::setCacheIdentifier($_GET['api_key']);
                    return false;
                }


                if ($payload->role == "user" || $payload->role == "admin") {
                    static::$role = $payload->role;
                    $userClass::setCacheIdentifier(static::$role);
                    Defaults::$accessControlFunction = 'AccessControl::verifyAccess';
                    return static::$requires == static::$role || static::$role == 'admin';
                } else {
                    return false;
                }
            }
            return false;
        }
        return false;
    }

    public function __getWWWAuthenticateString()
    {
        return 'Query name="api_key"';
    }

    /**
     * @access private
     */
    public static function verifyAccess(array $m)
    {
        $requires =
            isset($m['class']['AccessControl']['properties']['requires'])
            ? $m['class']['AccessControl']['properties']['requires']
            : false;
        return $requires
            ? static::$role == 'admin' || static::$role == $requires
            : true;
    }

    /*Criei os dois métodos abaixo, pois o jwt.io agora recomenda o uso do 'base64url_encode' no lugar do 'base64_encode'*/
    private static function base64UrlEncode($data)
    {
        // First of all you should encode $data to Base64 string
        $b64 = base64_encode($data);

        // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
        if ($b64 === false) {
            return false;
        }

        // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
        $url = strtr($b64, '+/', '-_');

        // Remove padding character from the end of line and return the Base64URL result
        return rtrim($url, '=');
    }
}