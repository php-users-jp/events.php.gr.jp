<?php
/**
 *  News/Delete.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Delete.php 199 2007-11-23 12:36:33Z halt $
 */

require_once dirname(dirname(__FILE__)) . '/Event/Delete.php';

/**
 *  News_Deleteフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_NewsDelete extends Event_Form_EventDelete
{
}

/**
 *  News_Deleteアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_NewsDelete extends Ethna_ActionClass
{
    /**
     *  News_Deleteアクションの前処理
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
            $this->af->setApp('news', $this->db->getNewsFromId($id));
            return 'news-delete-confirm';
        }

    }

    /**
     *  News_Deleteアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $id = Event_Util::getPathInfoArg();

        $this->db->deleteNewsFromId($id);
        Event_Util::redirect($this->config->get('base_url') . '/news_admin', 1, "Newsを削除しました。");
    }
}
?>
