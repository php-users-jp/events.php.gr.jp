<?php
/**
 *  Haste_Plugin_Auth_TypeKey.php
 *
 *  @author     halt <halt.feits at gmail.com>
 *  @package    Haste
 *  @version    $Id: skel.app_manager.php,v 1.1 2006/04/20 05:15:42 halt1983 Exp $
 */

require_once 'Auth_TypeKey.php';

/**
 *  Haste_Plugin_Auth_TypeKey
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    EthnaCart
 */
class Haste_Plugin_Auth_Typekey
{
    /**
     * TypeKey API Object
     * @var     Auth_TypeKey
     * @access  private
     */
    var $typekey;

    var $auth_config;

    /**
     * Haste_Plugin_Auth_Typekey
     *
     * @access public
     */
    function Haste_Plugin_Auth_Typekey(&$controller)
    {
        $this->controller  =& $controller;
        $this->ctl         =& $this->controller;
        $this->backend     =& $this->ctl->getBackend();
        $this->config      =& $controller->getConfig();
        $this->logger      =& $this->controller->getLogger();
        $this->session     =& $this->ctl->getSession();
        $this->auth_config = $this->config->get('auth');
       
        $this->typekey = new Auth_TypeKey();
        $this->typekey->site_token($this->auth_config['typekey_token']);
        $this->typekey->version('1.1');
        
    }

    //{{{ getLoginUrl
    /**
     * getLoginUrl
     *
     * @access public
     */
    function getLoginUrl()
    {
        $typekey_url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $this->typekey->urlSignIn($typekey_url);
    }
    //}}}

    //{{{ getLogoutUrl
    /**
     * getLogoutUrl
     *
     * @access public
     */
    function getLogoutUrl()
    {
        $typekey_url = $this->config->get('base_url');
        return $this->typekey->urlSignOut($typekey_url);
    }
    //}}}

    /**
     * login
     *
     * @access public
     */
    function login($query = null)
    {
        if ($query == null) {
            $query = $_GET;
        }

        $author = $this->auth_config['author'];

        if (!is_array($author)) {
            $author = array($author);
        }

        $result = isset($query['ts'])
            && isset($query['email'])
                && isset($query['name'])
                    && isset($query['nick'])
                        && isset($query['sig']);

        if ($result) {

            $result = $this->typekey->verifyTypeKey($query);

            if (PEAR::isError($result)) {
                if ($result->getMessage() != 'Invalid signature') {
                    return Ethna::raiseError($result->getMessage());
                }
            }

            if (!in_array($_GET['name'], $author) && current($author) != 'any') {
                return Ethna::raiseError('invalid user');
            }

            //success
            $this->session->start(60 * 60 * 48);
            $this->session->set('name', $_GET['name']);
            $this->session->set('nick', $_GET['nick']);

            return true;

        }

        return false;
    }

}
?>
