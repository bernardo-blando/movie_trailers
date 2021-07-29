<?php
use \Luracast\Restler\iAuthenticate;
use \Luracast\Restler\Resources;
use \Luracast\Restler\Defaults;

use Dotenv\Dotenv;
$dotEnv = Dotenv::createUnsafeImmutable(__DIR__. "/../");
$dotEnv->load();

class AccessControl implements iAuthenticate
{
    public static $requires = 'user';
    public static $role = 'user';
    public function __isAllowed()
    {
        $roles = array($_ENV["API_KEY"] => 'user');
        $userClass = Defaults::$userIdentifierClass;
        if (isset($_GET['api_key'])) {
            if (!array_key_exists($_GET['api_key'], $roles)) {
                $userClass::setCacheIdentifier($_GET['api_key']);
                return false;
            }
        } else {
            return false;
        }
        static::$role = $roles[$_GET['api_key']];
        $userClass::setCacheIdentifier(static::$role);
        Defaults::$accessControlFunction = 'AccessControl::verifyAccess';
        return static::$requires == static::$role;
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
            ? static::$role == $requires
            : true;
    }
}