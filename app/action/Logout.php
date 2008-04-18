<?php
/**
 *  /Logout.php
 *
 *  @author     your name
 *  @package    Event
 *  @version    $Id: Logout.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  blog_logoutフォームの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Event
 */
class Event_Form_Logout extends Haste_ActionForm
{
    /**
     *  @access private
     *  @var    array   フォーム値定義
     */
    var $form = array(
        /*
        'sample' => array(
            'name'          => 'サンプル',      // 表示名
            'required'      => true,            // 必須オプション(true/false)
            'min'           => null,            // 最小値
            'max'           => null,            // 最大値
            'regexp'        => null,            // 文字種指定(正規表現)
            'custom'        => null,            // メソッドによるチェック
            'filter'        => null,            // 入力値変換フィルタオプション
            'form_type'     => FORM_TYPE_TEXT   // フォーム型
            'type'          => VAR_TYPE_INT,    // 入力値型
        ),
        */
    );
}

/**
 *  blog_logoutアクションの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Event
 */
class Event_Action_Logout extends Ethna_ActionClass
{
    /**
     *  blog_logoutアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  blog_logoutアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $typekey_url = $this->config->get('typekey_url');
        $typekey_token = $this->config->get('typekey_token');
    
        $this->session->destroy();

        $tk = new Auth_TypeKey();
        $tk->site_token($typekey_token);
        
        $signin_url = $tk->urlSignIn($typekey_url);
        $signout_url = $tk->urlSignOut($this->config->get('base_url'));
        
        //$this->af->setApp('signout_url', $signout_url);
        header('Location: ' . $signout_url);
        exit();
    
    }
}
?>
