<?php
/**
 *  Ethna_AuthAdminAction
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Anubis
 *  @version    $Id: Ethna_AuthAdminActionClass.php 136 2006-08-17 05:17:17Z ha1t $
 */

require_once 'Auth_TypeKey.php';

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
class Ethna_AuthAdminActionClass extends Ethna_ActionClass
{

    //{{{ authenticate()
    /**
     * authentication
     *
     */
    function authenticate()
    {
        if ($this->session->isStart()) {
            return null;
        }

        $typekey_url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $base_url = $this->config->get('base_url');

        //set typekey token from config
        $this->typekey_token = $this->config->get('typekey_token');

        $this->TypeKey = new Auth_TypeKey();
        $this->TypeKey->site_token($this->typekey_token);
        $this->TypeKey->version('1.1');

        $this->signin_url = $this->TypeKey->urlSignIn($typekey_url);
        $this->signout_url = $this->TypeKey->urlSignOut($base_url);

        $this->af->setApp('signout_url', $this->signout_url);

        if ( !isset($_SESSION['name']) ) {

            if( $this->authTypeKey($_GET) === TRUE ){

                //success
                //$this->session->start();
                session_start();
                $_SESSION['name'] = $_GET['name'];
                $_SESSION['nick'] = $_GET['nick'];

            } else {

                //$this->session->destroy();
                print("Fail auth typekey");
                Event_Util::redirect($this->signout_url, 5);
                exit;

            }
        }

        $this->user = $this->backend->getManager('User');
        if ($this->user->isAdmin($_SESSION['name'])) {
            return null;
        } else {
            Event_Util::redirect($this->config->get('base_url') , 2, "You don't have a permission");
        }

    }
    //}}}

    //{{{ authTypeKey()
    /**
     * authTypeKey
     *
     * $query = array(
     *  'ts' => '',
     *  'email' => '',
     *  'name' => '',
     *  'nick' => '',
     *  'sig' => '',
     * )
     *
     * @access protected
     */
    function authTypeKey($query){
    
        $result = isset($query['ts'])
            && isset($query['email'])
            && isset($query['name'])
            && isset($query['nick'])
            && isset($query['sig']);
        
        if($result){
        
            $result = $this->TypeKey->verifyTypeKey($query);

            if (PEAR::isError($result)) {
                
                if($result->getMessage() == 'Timestamp from TypeKey is too old'){
                    header('Location: ' . $this->signout_url);
                    exit();
                    
                }

                if($result->getMessage() == 'Invalid signature'){
                    Ethna::raiseNotice('TypeKey Invalid signature');
                    return true;
                }
                
                Ethna::raiseError($result->getMessage());
                return false;
                
            } else {
                
                return true;
            
            }
            
        } else {
            
            header("Location: {$this->signin_url}");
            exit;
        
        }
    }
    //}}}

}
?>
