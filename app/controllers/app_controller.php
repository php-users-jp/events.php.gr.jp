<?php
/**
 *
 *
 */

/**
 * AppController
 *
 */
class AppController extends Controller
{
    /**
     * checkSession
     *
     *
     */
    function checkSession()
    {
        return $this->Session->check('Auth');
    }

    /**
     * isAdmin()
     */
    function isAdmin()
    {
        if ($this->Session->read('role') == 'admin') {
            return true;
        } else {
            return false;
        }
    }
}
