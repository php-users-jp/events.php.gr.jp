<?php
/**
 *  Event/Show.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Show.php 199 2007-11-23 12:36:33Z halt $
 */

require_once 'Parser.php';

/**
 *  Event_Showフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventShow extends Haste_ActionForm
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
        'join_comment' => array(
            'name' => 'comment',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'comment' => array(
            'name' => 'comment',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'join' => array(
            'name' => 'Join',
            'required' => false,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
        'post' => array(
            'name' => 'Submit',
            'required' => false,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
     );
}

/**
 *  Event_Showアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventShow extends Ethna_ActionClass
{
    var $id;

    /**
     *  Event_Showアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();
        $path_info = explode('/', $_SERVER['PATH_INFO']);

        if (isset($path_info[2])) {

            $this->id = intval($path_info[2]);
            
            if ($this->af->get('comment') && $this->af->get('post')) {
                if ($this->af->validate() == 0) {
                    $this->registerComment($this->af->getArray(false));
                }
            }

            $event = $this->db->getEventFromId($this->id);
            $comments = $this->db->getEventComments($this->id);
            $attendee = $this->db->getEventAttendeeFromId($this->id);
            $page = $this->db->getEventPageFromEventId($this->id);
            $attendee_count = 0;

            $this->af->setApp('site_title', $event['name']);

            $query = "SELECT * FROM trackback WHERE event_id = ?";
            $trackbacks = $this->db->getAll($query, array($this->id));

            foreach ($attendee as $row) {
                if (isset($_SESSION['name']) && $row['account_name'] == $_SESSION['name']) {
                    $this->af->setApp('joined', true);
                    if ($row['canceled'] == 1) {
                        $this->af->setApp('canceled', true);
                    }
                }

                if ($row['canceled'] != 1) {
                    $attendee_count++;
                }
            }

            // UserManager の取得。join状況の判定に用いる
            $user = $this->backend->getManager('User');
            $isjoined = ($this->session->isStart()) ? $user->isJoined($_SESSION['name'], $this->id) : false;

            if (is_string($event['map'])) {

                if (preg_match('/\(\(\(.+?\)\)\)/', $event['map'])
                    && !$isjoined) {
                    
                    $map = "<strong>マップは申し込み完了後・ログイン時に表示されます。</strong>";

                }
                else {
                    $event['map'] = str_replace(array("(((",")))"), array("",""), $event['map']);

                    $map = '<script type="text/javascript" src="http://slide.alpslab.jp/scrollmap.js"></script>';
                    $map.= "\n";
                    $map.= "<div class=\"alpslab-slide\">".$event['map'].'</div>';
                }


            } else {
                $map = "";
            }
            
            if (!$isjoined) {
                $event['description'] = str_replace(array("(((",")))"), array("",""), preg_replace('/\(\(\(.+?\)\)\)/s', "", $event['description']));
            }

            $event['description'] = str_replace(array("(((",")))"), array("",""), $event['description']);
            $event['description'] = Parser::parseAnubis($event['description']);

            if ($trackbacks != false) {
                $this->af->setApp('trackbacks', $trackbacks);
            }

            $this->af->setAppNE('page', $page);
            $this->af->setApp('event', $event);
            $this->af->setAppNE('event', $event);
            $this->af->setApp('comments', $comments);
            $this->af->setApp('attendee', $attendee);
            $this->af->setApp('attendee_count', $attendee_count);
            $this->af->setApp('attendee_nokori', $event['max_register'] - $attendee_count);
            $this->af->setApp('is_over', $this->db->isOverEvent($path_info[2]));
            $this->af->setAppNE('map', $map);

        }

        return null;
    }

    function registerComment($param)
    {
        $param['event_id'] = $this->id;
        $param['name'] = $_SESSION['name'];
        $param['nick'] = $_SESSION['nick'];
        $param['timestamp'] = date('Y-m-d H:i:s');
        $param['ua'] = $_SERVER['HTTP_USER_AGENT'];
        $param['ip'] = $_SERVER['REMOTE_ADDR'];

        $this->db->autoExecute('event_comment', $param, 'INSERT');

        Event_Util::redirect($this->config->get('base_url') . '/event_show/' . $this->id, 1, 'コメントの投稿に成功しました。');
    }

    /**
     *  Event_Showアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $ctl = $this->backend->getController();
        $smarty = $ctl->getTemplateEngine();
        $smarty->assign('redirect', '<link rel="alternate" type="application/rss+xml" title="RSS" href="' . $this->config->get('base_url') . '/rss/' . $this->id . '" />' . "\n");

        return 'event/show';
    }
}
?>
