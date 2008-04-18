<?php
/**
 *  Event/Admin.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Admin.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_Adminフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventAdmin extends Haste_ActionForm
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
 *  Event_Adminアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventAdmin extends Ethna_AuthAdminActionClass
{
    /**
     *  Event_Adminアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();
        return null;
    }

    /**
     *  Event_Adminアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $recent_event = $this->db->getRecentEvent(50, true);
        $this->af->setApp('recent_event', $recent_event);
        return 'event/admin';
    }
}
?>
