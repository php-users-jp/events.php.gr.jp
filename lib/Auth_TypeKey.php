<?php
// vim: foldmethod=marker
// Copyright (c) 2004 Daiji Hriata All Right Reserved.
// $Id: Auth_TypeKey.php 7 2006-04-29 15:22:55Z halt $
// 
// Auth_TypeKey Class
//
// Simple example:
//
// $tk = new Auth_TypeKey();
// $tk->site_token('PUTYOURTYPEKEYTOKEN');
// $tk->verifyTypeKey( $msg );
// if (PEAR::isError($result)) {
//     echo "INVALID";
// } else {
//     echo "VALID";
// }

require_once 'PEAR.php';

/**
* Error code
*/
define('AUTH_TYPEKEY_ERROR_INVALID', -1);
define('AUTH_TYPEKEY_ERROR_TIMEOUT', -2);
define('AUTH_TYPEKEY_ERROR_NOT_EXIST_BIGINT', -3);
define('AUTH_TYPEKEY_ERROR_NOT_RETRIEVE_PUBKEY', -4);
define('AUTH_TYPEKEY_ERROR_NOT_SET_SITE_TOKEN', -5);
define('AUTH_TYPEKEY_ERROR_NOT_EXIST_GMP', -6);
define('AUTH_TYPEKEY_ERROR_NOT_EXIST_BCMATH', -7);

class Auth_TypeKey extends PEAR
{
    /**
     * TypeKey Public_Key URLs
     */ 
    var $AUTH_TYPEKEY_BASEURL = 'https://www.typekey.com/t/typekey/';
    var $AUTH_TYPEKEY_SIG_URL = 'http://www.typekey.com/extras/regkeys.txt';

    /**
     * TypeKey version
     */
    var $AUTH_TYPEKEY_VERSION = '1';

    /**
     * TypeKey requires site_token
     */
    var $AUTH_TYPEKEY_SITE_TOKEN = NULL;


    /**
     * time limitatino of TypeKey validation (sec)
     */
    var $AUTH_TYPEKEY_TIMEOUT = 300;

    /**
     * function to compute BIGINT
     */
    var $AUTH_TYPEKEY_BIGINT = '';

	//{{{ Auth_TypeKey
    /**
     * Initialize.
     *
     * @param array $init        parameters for initialize: 
     *                          'version' : TypeKey version
     *                          'token' : TypeKey Site Token
     *
     * @return mixed  true on success. PEAR_Error on failure.
     * 
     * @access public
     */
    function Auth_TypeKey ( $init = array() )
    {
        $this->PEAR();
        if (!$this->_exist_bigint()) {
            return PEAR::raiseError("BCMATH or GMP is required", 
                                    AUTH_TYPEKEY_ERROR_NOT_EXIST_BIGINT);
        }
        if (array_key_exists('version', $init)) {
            $this->version($init['version']);
        }
        if (array_key_exists('token', $init)) {
            $this->site_token($init['token']);
        }
        return true;
    }
	//}}}

	//{{{ version
    /**
     * Set/show TypeKey version
     *
     * @param  string $version   TypeKey version to set.  
     *                           If nothing, just look.
     *
     * @return string            Current setting of TypeKey version
     * 
     * @access public
     */
    function version($version = NULL)
    {
        if (!is_null($version)) {
            $this->AUTH_TYPEKEY_VERSION = $version;
        }
        return $this->AUTH_TYPEKEY_VERSION;
    }
	//}}}

	//{{{ site_token
    /**
     * Set/show TypeKey Site Token
     *
     * @param  string $version        TypeKey Site Token to set.  
     *                                if nothing, just look.
     *
     * @return mixed  string          Current setting of TypeKey Site Token 
     *                                if set. PEAR_Error if not set.
     * 
     * @access public
     */
    function site_token($token = NULL) 
    {
        if (!is_null($token)) {
            $this->AUTH_TYPEKEY_SITE_TOKEN = $token;
        }
        if (is_null($this->AUTH_TYPEKEY_SITE_TOKEN)) {
            return PEAR::raiseError('SITE TOKEN is not set.',
                                     AUTH_TYPEKEY_ERROR_NOT_SET_SITE_TOKEN);
        }
        return $this->AUTH_TYPEKEY_SITE_TOKEN;
    }
	//}}}

	//{{{ verifyTypeKey
    /**
     * Verify the TypeKey authentication requirement
     *
     * @param  array $query        TypeKey Site Token to set.  if nothing, just look.
     * @param  array $key          Public Keys of TypeKey service.  if NULL, using preset URLs.
     *
     * @return mixed  true on success.  PEAR_Error on failure.
     * 
     * @access public
     */
    function verifyTypeKey ( $query,  $key = NULL ) 
    {
        // Retrieve key per each request.
        if (is_null($key)) {
            $key = $this->_fetch_key('');
            if ($key == false) {
                return PEAR::raiseError('Cannot get pubkey.',
                                        AUTH_TYPEKEY_ERROR_NOT_RETRIEVE_PUBKEY);
            }
        }

        foreach ( array( 'email', 'name', 'nick', 'ts', 'sig') as $i ) {
            $$i = $query[$i];
        }
        
        // The nickname field is sent as url_encoded utf-8 data(like %xx%xx... )
        // from Tyepkey but signed before encoded.  Usually all fields are
        // decoded by PHP automatically, but it might cause invalid_error,
        // especially using with mb_stirng.  rawurlencoded strings is
        // better for that case.  It can be gotten from QUERY_STRING
        $nick = rawurldecode($nick);

        switch ($this->version()) {
        case '1':
            $message = implode('::', array( $email, $name, $nick, $ts));
            break;
        case '1.1':
          $token = $this->site_token();
          if (PEAR::isError($token)) {
            return $token;
          }                
          $message = implode('::', array( $email, $name, $nick, $ts, $token));
                break;
        default:
          // default is version '1'
          $message = implode('::', array( $email, $name, $nick, $ts));
          break;
        }
        
		if( $this->_dsa_verify($message, $sig, $key) == true) {
            if ( time() - $ts > $this->AUTH_TYPEKEY_TIMEOUT ) {
                return PEAR::raiseError("Timestamp from TypeKey is too old", 
                                        AUTH_TYPEKEY_ERROR_TIMEOUT);
            }
            return true;
        } else {
            return PEAR::raiseError("Invalid signature", 
                                    AUTH_TYPEKEY_ERROR_INVALID);
        }
    }
	//}}}

	//{{{ urlSignIn
    /* Generate URL to link to Sign-In using TypeKey service
     * 
     * @param  string  $return_url        URL to be returned after authentication
     * @param  boolean $need_email        if true, require email address of user.
     *
     * @return mixed   string  $url                URL to link TypeKey Sign-In
     *                 PEAR_Error on failure
     *
     * @access public
     */

    function urlSignIn ($return_url, $need_email = false, $options = array ())
    {
        $token = $this->site_token();
        if (PEAR::isError($token)) {
            return $token;
        }

        $option_query = '';
        foreach ($options as $key => $value) {
            $option_query .= '&' . $key . '=' . $value;
        }

        $url = $this->AUTH_TYPEKEY_BASEURL;
        $url .= "login?t=" . $token;
        $url .= (($need_email == 1) ? "&need_email=1" : '');
        $url .= (($this->version()) ? "&v=" . $this->version() : '');
        $url .= $option_query;
        $url .= "&_return=" . rawurlencode($return_url);
        return $url;
    }    
	//}}}

	//{{{ urlSignOut
    /* Generate URL to link to sign out
     * 
     * @param  string  $return_url        URL to be returned after authentication
     *
     * @return string  $url                URL to link TypeKey sign out.
     *
     * @access public
     */
    function urlSignOut ( $return_url ) {
        $url = $this->AUTH_TYPEKEY_BASEURL;
        $url .= "logout?";
        $url .= "_return=" . rawurlencode($return_url);
        return $url;
    }    
	//}}}

	//{{{ _fetch_key
    /* 
     * Fetch keys of TypeKey service
     * 
     * @param  string $url        URL of TypeKey Public Keys
     *
     * @return mixed  array of Public keys of TypeKey services
     *                false on error
     *
     * @access private
     */
    function _fetch_key ( $url = '')
    {
        if ($url == '') {
            $url = $this->AUTH_TYPEKEY_SIG_URL;
        }
        $lines = @file($url);
        if ($lines == false) {
            return false;
        }
        $key_raw = explode(" ", $lines[0]);

        foreach ($key_raw as $e) {
            list($key_index, $key_value) = explode("=", $e);
            $key[$key_index] = trim($key_value);
        }
        return $key;
    }
	//}}}

	//{{{ _exist_bigint
    /* 
     * check existing bigint function
     * 
     * @return boolean  true if available bigint funciton gmp or bcmath
     *                  false if not available.
     *
     * @access private
     *
     */
    function _exist_bigint () 
    {
        $extension_bigint = array( 'bcmath', 'gmp' );
        $exist_bigint = false;
        foreach ($extension_bigint as $ext) {
            if (extension_loaded($ext)) {
                $exist_bigint = true;
                $this->AUTH_TYPEKEY_BIGINT = $ext;
            }
        }
        return $exist_bigint;
    }
	//}}}

	//{{{ _dsa_verify
    /* 
     * dsa verification
     * 
     * @param  string $message Message to verify
     * @param  string @sig     Sign for the message, two keys are 
     *                         included separeted by ':' (colon)
     * @param  array  $key     ublic keys for the signiture
     *
     * @access private
     *
     */
    function _dsa_verify ( $message, $sig, $key )
    {
        $func = $this->AUTH_TYPEKEY_BIGINT;
        $func = '_dsa_verify_' . $func;
        return $this->$func( $message, $sig, $key );
    }
	//}}}

	//{{{ _dsa_verify_gmp
    /* 
     * dsa verification using gmp
     * 
     * @param  string $message Message to verify
     * @param  string @sig     Sign for the message, two keys are 
     *                         included separeted by ':' (colon)
     * @param  array  $key     ublic keys for the signiture
     *
     * @access private
     *
     */
    function _dsa_verify_gmp ( $message, $sig, $key )
    {
        list( $r_sig, $s_sig ) = explode(":", $sig );
        $r_sig = base64_decode($r_sig);
        $s_sig = base64_decode($s_sig);

        foreach ($key as $i => $v) {
            $key[$i] = gmp_init($v);
        }
        $s1 = gmp_init($this->_gmp_bindec($r_sig));
        $s2 = gmp_init($this->_gmp_bindec($s_sig));

        $w = gmp_invert($s2,$key['q']);
        $hash_m = gmp_init('0x' . sha1($message));

        $u1 = gmp_mod(gmp_mul($hash_m,$w),$key['q']);
        $u2 = gmp_mod(gmp_mul($s1,$w),$key['q']);

        $v = gmp_mod( 
                 gmp_mod( 
                     gmp_mul(
                         gmp_powm($key['g'],$u1,$key['p']), 
                         gmp_powm($key['pub_key'],$u2,$key['p'])),
                     $key['p']), 
                 $key['q']);
        if (gmp_cmp($v, $s1) == 0) {
            return true;
        } else {
            return false;
        }
    }
	//}}}

	//{{{ _gmp_bindec
    /* 
     * gmp_bindec
     * 
     * @param  string $bin  binary data
     *
     * @return gmp    value of $bin
     *
     * @access private
     */
    function _gmp_bindec ($bin) 
    {
        $dec = gmp_init(0);
        while (strlen($bin)) {
            $i = ord(substr($bin, 0, 1));
                $dec = gmp_add(gmp_mul($dec,256),$i);
                $bin = substr($bin, 1);
            }
        return gmp_strval($dec);
    }
	//}}}

	//{{{ _dsa_verify_bcmath
    /* 
     * dsa verification using bcmath
     * 
     * @param  string $message Message to verify
     * @param  string @sig     Sign for the message, two keys are 
     *                         included separeted by ':' (colon)
     * @param  array  $key     ublic keys for the signiture
     *
     * @access private
     *
     */
    function _dsa_verify_bcmath ( $message, $sig, $key )
    {
        list( $r_sig, $s_sig ) = explode(":", $sig );

        $r_sig = base64_decode($r_sig);
        $s_sig = base64_decode($s_sig);

        $s1 = $this->_bc_bindec($r_sig);
        $s2 = $this->_bc_bindec($s_sig);

        $w = $this->_bc_invert($s2,$key['q']);
        $hash_m = $this->_bc_hexdec(sha1($message));

        $u1 = bcmod(bcmul($hash_m,$w),$key['q']);
        $u2 = bcmod(bcmul($s1,$w),$key['q']);

        $v = bcmod( 
                 bcmod( 
                     bcmul(
                         bcmod($this->_bc_powmod($key['g'],$u1,$key['p']), $key['p']),
                         bcmod($this->_bc_powmod($key['pub_key'],$u2,$key['p']),$key['p'])),
                     $key['p']), 
                 $key['q']);

        if (bccomp($v, $s1) == 0) {
            return true;
        } else {
            return false;
        }
    }
	//}}}

	//{{{ _bc_hexdec
    /* 
     * _bc_hexdec
     * 
     * @param  string $hex
     *
     * @return string dec converted from $hex
     *
     * @access private
     */
    function _bc_hexdec ($hex)
    {
        $dec = "0";
        while (strlen($hex)) {
            $i = HexDec(substr($hex, 0, 4));
            $dec = bcadd(bcmul($dec,65536),$i);
            $hex = substr($hex, 4);
        }
        return $dec;
    }
	//}}}

	//{{{ _bc_bindec
    /* 
     * _bc_bindec
     * 
     * @param  string $bin
     *
     * @return string dec converted from $bin
     *
     * @access private
     */
    function _bc_bindec ($bin)
    {
        $dec = "0";
        while (strlen($bin)) {
            $i = ord(substr($bin, 0, 1));
            $dec = bcadd(bcmul($dec,256),$i);
            $bin = substr($bin, 1);
        }
        return $dec;
    }
	//}}}

	//{{{ _bc_invert
    /* 
     * _bc_invert
     * 
     * @param  string $x, $y
     *
     * @return string inverse $x and $y
     *
     * @access private
     */
    function _bc_invert ($x, $y) 
    {
        while (bccomp($x,0)<0) { 
            $x = bcadd($x,$y); 
        }
        $r = $this->_bc_exgcd($x, $y);
        if ($r[2] == 1) {
            $a = $r[0];
            while (bccomp($a,0)<0) {
                $a = bcadd($a,$y);
            }
            return $a;
        } else {
            return false;
        }
    }
	//}}}

	//{{{ _bc_exgcd
    /* 
     * _bc_exgcd
     * 
     * @param  string $x, $y
     *
     * @return string extended gcd of $x and $y
     *
     * @access private
     */
    function _bc_exgcd ($x, $y) 
    {
        $a0 = 1; $a1 = 0;
        $b0 = 0; $b1 = 1;
        $c = 0;
        while($y > 0) {
            $q = bcdiv($x, $y, 0);
            $r = bcmod($x, $y);
            $x = $y; $y = $r;
            $a2 = bcsub($a0, bcmul($q,$a1));
            $b2 = bcsub($b0, bcmul($q,$b1));
            $a0 = $a1; $a1 = $a2;
            $b0 = $b1; $b1 = $b2;
        }
        return (array($a0, $b0, $x));
    }
	//}}}

	//{{{ _bc_powmod
    /* 
     * _bc_powmod
     * 
     * @param  string $x, $y, $mod
     *
     * @return string bcmod(bcpow($x, $y), $mod)
     *
     * @access private
     */
    function _bc_powmod ($x, $y, $mod)
    {
        if (function_exists('bcpowmod')) {
            return bcpowmod($x, $y, $mod);
        } else {
            if (bccomp($y,1) == 0) {
                return bcmod($x, $mod);
            } else if (bcmod($y,2) == 0) {
                return bcmod(bcpow($this->_bc_powmod($x, bcdiv($y,2), $mod), 2), $mod);
            } else {
                return bcmod(bcmul($x, $this->_bc_powmod($x, bcsub($y,1), $mod)), $mod);
            }
        }
    }
	//}}}
}
?>
