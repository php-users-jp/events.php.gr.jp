<?php
/**
 *  Login.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Login.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Loginフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_Login extends Haste_ActionForm
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
            'form_type'     => FORM_TYPE_TEXT,  // フォーム型
            'type'          => VAR_TYPE_INT,    // 入力値型
        ),
        */
    );
}

/**
 *  Loginアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_Login extends Ethna_AuthActionClass
{
    /**
     * authenticate
     *
     * @access public
     */
    function authenticate()
    {
        //disable authenticate
        return null;
    }

    /**
     *  Loginアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $config = $this->config->get('auth');
        $controller =& $this->backend->getController();
        $plugin     =& $controller->getPlugin();
        $this->auth =& $plugin->getPlugin('Auth', ucfirst($config['type']));

        $this->af->setApp('login_url', $this->auth->getLoginUrl());

        if (!isset($_GET['name'])) {
            header('Location: ' . $this->auth->getLoginUrl());
        }

        return null;
    }

    /**
     *  Loginアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $result = $this->auth->login();
        if (!Ethna::isError($result) && $result !== false) {
            $this->user = $this->backend->getManager('User');
            $this->session->set('name', $_GET['name']);
            $this->session->set('nick', $_GET['nick']);
            $this->session->set('is_admin', $this->user->isAdmin($_GET['name']));
            $this->redirect();
        }

        return 'login';
    }
}
?>
