<?php
/**
 *  Event/Post.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Post.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_Postフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventPost extends Haste_ActionForm
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
            'required' => false,
            'form_type' => FORM_TYPE_HIDDEN,
            'type' => VAR_TYPE_STRING,
        ),
        'name' => array(
            'name' => 'イベント名',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'max_register' => array(
            'name' => "収容可能人数",
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_INT,
        ),
        'description' => array(
            'name' => 'イベントの内容',
            'required' => true,
            'form_type' => FORM_TYPE_TEXTAREA,
            'type' => VAR_TYPE_STRING,
        ),
        'private_description' => array(
            'name' => 'ログイン後に表示するメッセージ',
            'required' => true,
            'form_type' => FORM_TYPE_TEXTAREA,
            'type' => VAR_TYPE_STRING,
        ),
        'start_date' => array(
            'name' => 'イベント開始時間',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'end_date' => array(
            'name' => 'イベント終了時間',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'publish_date' => array(
            'name' => 'イベント案内の公開日',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'map' => array(
            'name' => '会場までの地図(ALPSLAB Slide)',
            'required' => false,
            'form_type' => FORM_TYPE_TEXTAREA,
            'type' => VAR_TYPE_STRING,
        ),
        'private' => array(
            'name' => 'イベントを非表示にする(1/0)',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_INT,
        ),
        'due_date' => array(
            'name' => '締切日',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
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
 *  Event_Postアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventPost extends Ethna_AuthAdminActionClass
{
    /**
     *  Event_Postアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();

        $this->af->set('id', Event_Util::getPathinfoArg());

        if(!$this->af->get('submit') && $this->af->get('id')){
            $form = $this->db->getEventFromId($this->af->get('id'));
            foreach ($form as $key => $value) {
                $this->af->set($key, $value);
            }
        }

        if(!$this->af->get('submit') && !$this->af->get('id')){
            $this->af->set('date', date('Y-m-d H:i:s'));
            $this->af->set('duedate', date('Y-m-d H:i:s'));
        }

        if ($this->af->get('submit') && ($this->af->validate() == 0)) {

            //unescape
            $param = $this->af->getArray();
            $param['description'] = Event_Util::unhtmlentities($param['description']);
            $param['private_description'] = Event_Util::unhtmlentities($param['private_description']);

            $this->db->postEvent($param);
            Event_Util::redirect($this->config->get('base_url') . "/event_admin", 1, "now loading...");
        }

        return null;
    }

    /**
     *  Event_Postアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        return 'event/post';
    }
}
?>
