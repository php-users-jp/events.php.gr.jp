<?php
/**
 *  News/Post.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Post.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  News_Postフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_NewsPost extends Haste_ActionForm
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
            'name' => 'name',
            'required' => false,
            'form_type' => FORM_TYPE_HIDDEN,
            'type' => VAR_TYPE_STRING,
        ),
        'title' => array(
            'name' => 'Title',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'date' => array(
            'name' => 'Date',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'duedate' => array(
            'name' => 'Due Date',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'description' => array(
            'name' => 'Description',
            'required' => true,
            'form_type' => FORM_TYPE_TEXTAREA,
            'type' => VAR_TYPE_STRING,
        ),
        'submit' => array(
            'name' => 'Submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  News_Postアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_NewsPost extends Ethna_AuthActionClass
{
    /**
     *  News_Postアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();
        $id = Event_Util::getPathinfoArg();

        if (is_numeric($id) && !$this->af->get('submit')) {

            $entry = $this->db->getNewsFromId($id);
            foreach ($entry as $key => $item) {
                $this->af->set($key, $item);
            }

        } else if (!$this->af->get('submit')) {
            $this->af->set('date', date('Y-m-d H:i:s'));
            $this->af->set('duedate', date('Y-m-d H:i:s'));
        }

        if ($this->af->get('submit') && ($this->af->validate() == 0)) {
           $this->db->postNews($this->af->getArray()); 
           Event_Util::redirect($this->config->get('base_url') . "/news_admin", 1, "now loading...");
        }
        return null;
    }

    /**
     *  News_Postアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        return 'news/post';
    }
}
?>
