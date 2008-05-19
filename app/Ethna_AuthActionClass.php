<?php
/**
 *  Ethna_AuthAction
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Anubis
 *  @version    $Id$
 */

require_once 'Auth_TypeKey.php';

/**
 *  Ethna_AuthActionClass
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @access     public
 *  @package    Anubis
 *
 */
class Ethna_AuthActionClass extends Ethna_ActionClass
{

    //{{{ authenticate()
    /**
     * authentication
     *
     */
    function authenticate()
    {
        if (!$this->session->isStart()) {
            $config = $this->config->get('auth');
            if (isset($config['type']) && $config['type'] != 'none') {
                $this->redirect('/login');
            }
        }

        return parent::authenticate();
    }
    //}}}

    //{{{ redirect
    /**
     * redirect
     *
     * @access public
     */
    function redirect($action = "")
    {
        $url = $this->config->get('base_url') . $action;
        $html = <<<EOD
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="refresh" CONTENT="0;URL={$url}">
<meta name="robots" content="INDEX,NOFOLLOW">
<title>Redirecting to {$url}</title>
</head>
<body>
<p>if not start redirect, click <a href="{$url}">this link</a></p>
</body>
</html>
EOD;
        print($html);
        exit();
    }
    //}}}

}
?>
