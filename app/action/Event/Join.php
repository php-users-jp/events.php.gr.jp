<?php
/**
 *  Event/Join.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Join.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_Joinフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventJoin extends Haste_ActionForm
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
        'event_id' => array(
            'name' => 'id',
            'required' => true,
            'type' => VAR_TYPE_INT,
        ),
        'join_comment' => array(
            'name' => 'Comment',
            'required' => false,
            'type' => VAR_TYPE_STRING,
        ),
        'join' => array(
            'name' => 'submit',
            'required' => true,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  Event_Joinアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventJoin extends Ethna_AuthActionClass
{
    /**
     *  Event_Joinアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        if ($this->af->validate() > 0) {
            return 'error';
        }

        $this->db = $this->backend->getDB();

        return null;
    }

    /**
     *  Event_Joinアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $param = $this->af->getArray();
        $param['comment'] = $param['join_comment'];
        $param['account_name'] = $_SESSION['name'];
        $param['account_nick'] = $_SESSION['nick'];
        $param['register_at'] = date('Y-m-d H:i:s');

        $this->db->AutoExecute('event_attendee', $param, 'INSERT');

        Event_Util::redirect(
            $this->config->get('base_url') . "/event_show/{$param['event_id']}",
            2,
            'joined event'
        );
    }

}
?>
