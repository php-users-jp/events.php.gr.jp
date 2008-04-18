<?php
/**
 *  EventCancelRevert.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: skel.action.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *  EventCancelRevertフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventCancelRevert extends Ethna_ActionForm
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
        'id' => array(
            'name' => 'id',
            'required' => true,
            'form_type' => FORM_TYPE_HIDDEN,
            'type' => VAR_TYPE_INT,
        ),
        'submit' => array(
            'name' => 'submit',
            'required' => false,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  EventCancelRevertアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventCancelRevert extends Ethna_AuthAdminActionClass
{
    /**
     *  EventCancelRevertアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();

        $id = Event_Util::getPathInfoArg();
        if (is_numeric($id)) {
            $this->af->set('id', $id);
        }

        $this->record = $this->getEventAttendeeFromId($this->af->get('id'));
        /*
        if ($this->record['account_name'] != $_SESSION['name']) {
            $this->ae->add('security', 'access denied');
            return 'error';
        }
         */

        if (!$this->af->get('submit')) {
            $record = $this->record;
            unset($record['ip']);
            unset($record['ua']);
            unset($record['event_id']);
            $this->af->setApp('record', $record);

            return 'event-cancel-revert-confirm';
        }
    }

    /**
     *  EventCancelRevertアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        if ($this->af->validate() > 0) {
            return 'error';
        }

        $param['canceled'] = 0;

        $this->db->autoExecute('event_attendee',
            $param,
            'UPDATE',
            "id = {$this->record['id']}"
        );

        Event_Util::redirect($this->config->get('base_url') . '/event_show/' . $this->record['event_id'], '1', 'イベントキャンセルを解除しました。');

        return null;
    }

    /**
     * getEventAttendeeFromId
     *
     */
    function getEventAttendeeFromId($event_id)
    {
        $query = "SELECT * FROM event_attendee";
        $query.= " WHERE id = ?";

        return $this->db->getRow($query, array((int)$event_id));
    }
}
?>
