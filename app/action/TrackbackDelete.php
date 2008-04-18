<?php
/**
 *  TrackbackDelete.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: /event/trunk/skel/skel.action.php 1497 2007-03-26T16:56:26.006971Z svm  $
 */

/**
 *  TrackbackDeleteフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_TrackbackDelete extends Haste_ActionForm
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
        'post' => array(
            'name' => 'Submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  TrackbackDeleteアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_TrackbackDelete extends Ethna_AuthActionClass
{
    /**
     * path_info:id
     * @var     integer
     * @access  public
     */
    var $id;

    /**
     *  TrackbackDeleteアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        if ($this->user->isAdmin($_SESSION['name'])) {
            return null;
        }

        if (!$this->user->existsAdmin()) {
            $this->user->setAdmin($_SESSION['name']);
            return null;
        }

        Event_Util::redirect($this->config->get('base_url'), 2, '権限がありません');
    }

    /**
     *  TrackbackDeleteアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $path_info = explode('/', $_SERVER['PATH_INFO']);
        $this->id = $path_info[2];

        if ($this->af->validate() > 0) {
            return $this->confirm();
        } else {
            return $this->delete();
        }
        return 'TrackbackDelete';
    }

    function delete()
    {
        $db = $this->backend->getDB();
        
        $trackback = $db->getRow("SELECT * FROM trackback WHERE id = ?", array($this->id));

        $db->execute("DELETE FROM trackback WHERE id = ?", array($trackback['id']));
        
        Event_Util::redirect($this->config->get('base_url') . "/event_show/{$trackback['event_id']}", 2, 'トラックバックを削除しました');
    }

    function confirm()
    {
        $db = $this->backend->getDB();

        $trackback = $db->getRow("SELECT * FROM trackback WHERE id = ?", array($this->id));
        $this->af->setApp("trackback", $trackback);

        return 'trackbackdelete-confirm';
    }
}
?>
