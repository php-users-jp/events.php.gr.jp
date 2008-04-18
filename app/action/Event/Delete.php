<?php
/**
 *  Event/Delete.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Delete.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_Deleteフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventDelete extends Haste_ActionForm
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
        'submit' => array(
            'name' => 'submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  Event_Deleteアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventDelete extends Ethna_AuthAdminActionClass
{
    /**
     *  Event_Deleteアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();
        $id = Event_Util::getPathInfoArg();

        if (!is_numeric($id)) {
            print('invalid query error!');
            exit();
        }

        if ($this->af->get('submit')) {
            return null;
        } else {
            $this->af->setApp('event', $this->db->getEventFromId($id));
            return 'event-delete-confirm';
        }
    }

    /**
     *  Event_Deleteアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $id = Event_Util::getPathInfoArg();
        $this->db->deleteEventFromId($id);
        Event_Util::redirect($this->config->get('base_url') . '/event_admin', 1, "イベントを削除しました。");
    }
}
?>
