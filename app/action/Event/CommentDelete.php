<?php
/**
 *  Event/CommentDelete.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: CommentDelete.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_CommentDeleteフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventCommentDelete extends Haste_ActionForm
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
            'name' => 'Submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),

    );
}

/**
 *  Event_CommentDeleteアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventCommentDelete extends Ethna_AuthAdminActionClass
{
    /**
     *  Event_CommentDeleteアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();

        $id = intval(Event_Util::getPathinfoArg());

        if ($this->af->get('submit') && $this->af->validate() == 0) {
            return null;
        } else {
            $this->af->setApp('comment', $this->db->getEventComment($id));
            return 'event-comment-delete';
        }
    }

    /**
     *  Event_CommentDeleteアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $id = intval(Event_Util::getPathinfoArg());
        $this->db->deleteCommentFromEvent($id);

        $url = $this->config->get('base_url');
        Event_Util::redirect($url , 2, 'Comment Deleted');
        
    }
}
?>
