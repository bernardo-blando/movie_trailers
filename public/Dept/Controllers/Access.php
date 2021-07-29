<?php

use Luracast\Restler\RestException;

require_once "./AUTH/AccessControl.php";
class Access
{
    public function all()
    {
        return "public api, all are welcome";
    }

    /**
     * @access protected
     *
     */
    public function user()
    {

        return "protected api, only user and admin can access";
    }

    /**
     * @access protected
     *
     */
    public function admin()
    {
        AccessControl::isAdmin();
        return "protected api, only admin can access";
    }
}
