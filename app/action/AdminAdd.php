<?php
/**
 *  AdminAdd.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: AdminAdd.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  AdminAddフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_AdminAdd extends Haste_ActionForm
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
        'name' => array(
            'name' => 'name',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'submit' => array(
            'name' => 'submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  AdminAddアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_AdminAdd extends Ethna_AuthAdminActionClass
{
    /**
     *  AdminAddアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        if ($this->af->validate() > 0) {
            return 'adminadd';
        } else {
            return null;
        }
    }

    /**
     *  AdminAddアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $user = $this->backend->getManager("user");
        $user->setAdmin($this->af->get('name'));
        Event_Util::redirect($this->config->get('base_url') . '/admin', 1, 'added admin user');
    }
}
?>
