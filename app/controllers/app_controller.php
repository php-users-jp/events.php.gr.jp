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
}
