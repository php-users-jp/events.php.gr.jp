<?php
/**
 *  Admin.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Admin.php 205 2007-12-20 10:31:35Z halt $
 */

require_once 'AdminAdd.php';

/**
 *  Adminフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_Admin extends Event_Form_AdminAdd
{
}

/**
 *  Adminアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_Admin extends Ethna_AuthAdminActionClass
{
    /**
     *  Adminアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->user = $this->backend->getManager('User');

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
     *  Adminアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $system_env = array();
        $system_env['php_version'] = PHP_VERSION;
        $system_env['ethna_version'] = ETHNA_VERSION;
        $system_env['system_version'] = EVENT_VERSION;

        $this->af->setApp('system_env', $system_env);
        $this->af->setApp('admin_list', $this->user->getAdminList());
        return 'admin';
    }
}
?>
