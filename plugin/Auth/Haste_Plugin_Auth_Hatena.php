<?php
/**
 *  Haste_Plugin_Auth_Hatena.php
 *
 *  @author     halt feits <halt.feits at gmail.com>
 *  @package    Haste
 *  @version    0.1.1
 */

require_once 'Auth/Hatena.php';

/**
 *  Haste_Plugin_Auth_Hatena
 *
 *  @author     halt feits <halt.feits at gmail.com>
 *  @access     public
 *  @package    Haste
 */
class Haste_Plugin_Auth_Hatena
{
    /**
     * Hatena API Object
     * @var     Auth_Hatena
     * @access  private
     */
    var $hatena;

    var $auth_config;

    /**
     * Haste_Plugin_Auth_Hatena
     *
     * @access public
     */
    function Haste_Plugin_Auth_Hatena(&$controller)
    {
        $this->controller =& $controller;
        $this->ctl =& $this->controller;

        $this->backend =& $this->ctl->getBackend();
        $this->config =& $controller->getConfig();
        $this->logger =& $this->controller->getLogger();
        $this->session =& $this->ctl->getSession();
        $this->auth_config = $this->config->get('auth');

        $this->hatena = new Auth_Hatena(
            $this->auth_config['hatena_api_key'],
            $this->auth_config['hatena_api_secret']
        );

    }

    /**
     * getLoginUrl
     *
     * @access public
     */
    function getLoginUrl()
    {
        return $this->hatena->uri_to_login();
    }

    function getLogoutUrl()
    {
        return $this->config->get('base_url');
    }

    /**
     * login
     *
     * @access public
     */
    function login()
    {
        if (array_key_exists('cert', $_GET)) {
            if ($user = $this->hatena->login($_GET['cert'])) {

                if (!in_array($user['name'], $this->auth_config['hatena_id'])){
                    return false;
                }

                $this->session->start(60 * 60 * 48);
                $this->session->set('name', htmlspecialchars($user['name'], ENT_QUOTES));
                $this->session->set('image_url', $user['image_url']);
                $this->session->set('thumbnail_url', $user['thumbnail_url']);
                return true;
            }
        }

        return false;
    }

    /**
     * logout
     *
     */
    function logout()
    {
        $this->session->destroy();
    }

}
?>
