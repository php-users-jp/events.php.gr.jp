<?php
/**
 *  Ethna_AuthAdminAction
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Anubis
 *  @version    $Id: Ethna_AuthAdminActionClass.php 136 2006-08-17 05:17:17Z ha1t $
 */

require_once 'Ethna_AuthActionClass.php';

/**
 *  Ethna_AuthAdminActionClass
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @access     public
 *  @package    Anubis
 *
 * $config = array(
 *     'base_url' => 'http://example.com/index.php',
 *     'typekey_token' => 'typekey_token',
 * );    
 */
class Ethna_AuthAdminActionClass extends Ethna_AuthActionClass
{

    /**
     * authentication
     *
     */
    function authenticate()
    {
        $this->user = $this->backend->getManager('User');
        if (!$this->user->isAdmin($_SESSION['name'])) {
            Event_Util::redirect($this->config->get('base_url') , 2, "You don't have a permission");
        }
        
        return parent::authenticate();
    }

}
?>
