<?php
/**
 *  Ethna_AuthAction
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Anubis
 *  @version    $Id: Ethna_AuthActionClass.php 136 2006-08-17 05:17:17Z ha1t $
 */

require_once 'Auth_TypeKey.php';

/**
 *  Ethna_AuthActionClass
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
class Ethna_AuthActionClass extends Ethna_ActionClass
{

    /**
     * Typekey Object
     * @var     object
     * @access  protected
     */
    var $TypeKey;

    var $typekey_token;
    var $signin_url;
    var $signout_url;
    
    //{{{ authenticate()
    /**
     * authentication
     *
     */
    function authenticate()
    {
        $this->user = $this->backend->getManager('User');
        $this->logger = $this->backend->getLogger();

        if ($this->session->isStart()) {
            $this->logger->log(LOG_DEBUG, 'Session started');
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
        
        $this->af->setApp('signin_url', $this->signin_url);
        $this->af->setApp('signout_url', $this->signout_url);
        
        if ( is_null($this->session->get('name')) ) {
        
            if( $this->authTypeKey($_GET) === TRUE ){
                
                //success
                $this->logger->log(LOG_DEBUG, 'Authenticate OK!');
                $this->session->start();
                $this->session->set('name', $_GET['name']);
                $this->session->set('nick', $_GET['nick']);
                $this->session->set('is_admin', $this->user->isAdmin($_GET['name']));
            
            } else {

                $this->session->destroy();
                print("fail auth typekey");
                Aero_Util::move($this->signout_url, "5");
                exit;
            
            }
        
        }

        return null;
       
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
