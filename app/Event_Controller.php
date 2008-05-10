<?php
/**
 *  Event_Controller.php
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Event_Controller.php 200 2007-11-23 12:36:34Z halt $
 */

/** アプリケーションベースディレクトリ */
define('BASE', dirname(dirname(__FILE__)));

define('EVENT_VERSION', '1.0.4');

// include_pathの設定(アプリケーションディレクトリを追加)
$include_paths = array(
    //'system' => ini_get('include_path'), //libとappしかみない
    'app' => BASE . "/app",
    'lib' => BASE . "/lib",
);

ini_set('include_path', implode(PATH_SEPARATOR, $include_paths));

// デフォルトの文字エンコーディングの指定
mb_internal_encoding("UTF-8");

/** アプリケーションライブラリのインクルード */
include_once('Ethna/Ethna.php');
include_once('Event_Error.php');

require_once 'Ethna_AuthActionClass.php';
require_once 'Ethna_AuthAdminActionClass.php';
require_once 'Ethna_ActionError_UTF8.php';

require_once 'Event_UserManager.php';
require_once 'Event_Util.php';
require_once 'Event_DB.php';

require_once 'Text/PukiWiki.php';

require_once 'Haste_ActionForm.php';
require_once 'Haste_ViewClass.php';

/**
 *  Eventアプリケーションのコントローラ定義
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Event
 */
class Event_Controller extends Ethna_Controller
{
    /**#@+
     *  @access private
     */

    /**
     *  @var    string  アプリケーションID
     */
    var $appid = 'EVENT';

    /**
     *  @var    array   forward定義
     */
    var $forward = array(
        /*
         *  TODO: ここにforward先を記述してください
         *
         *  記述例：
         *
         *  'index'         => array(
         *      'view_name' => 'Event_View_Index',
         *  ),
         */
    );

    /**
     *  @var    array   action定義
     */
    var $action = array(
        /*
         *  TODO: ここにaction定義を記述してください
         *
         *  記述例：
         *
         *  'index'     => array(),
         */
    );

    /**
     *  @var    array   soap action定義
     */
    var $soap_action = array(
        /*
         *  TODO: ここにSOAPアプリケーション用のaction定義を
         *  記述してください
         *  記述例：
         *
         *  'sample'            => array(),
         */
    );

    /**
     *  @var    array       アプリケーションディレクトリ
     */
    var $directory = array(
        'action'        => 'app/action',
        'action_xmlrpc' => 'app/action_xmlrpc',
        'app'           => 'app',
        'etc'           => 'etc',
        'filter'        => 'filter',
        'locale'        => 'locale',
        'log'           => 'log',
        'plugin'        => 'plugin',
        'plugins'       => array('app/plugin_smarty'),
        'template'      => 'template',
        'template_c'    => 'tmp',
        'tmp'           => 'tmp',
        'view'          => 'app/view',
    );

    /**
     *  @var    array       DBアクセス定義
     */
    var $db = array(
        ''              => DB_TYPE_RW,
    );

    /**
     *  @var    array       拡張子設定
     */
    var $ext = array(
        'php'           => 'php',
        'tpl'           => 'tpl',
    );

    /**
     *  @var    array   クラス定義
     */
    var $class = array(
        /*
         *  TODO: 設定クラス、ログクラス、SQLクラスをオーバーライド
         *  した場合は下記のクラス名を忘れずに変更してください
         */
        'class'         => 'Ethna_ClassFactory',
        'backend'       => 'Ethna_Backend',
        'config'        => 'Ethna_Config',
        'db'            => 'Event_DB',
        'error'         => 'Ethna_ActionError_UTF8',
        'form'          => 'Haste_ActionForm',
        'i18n'          => 'Ethna_I18N',
        'logger'        => 'Ethna_Logger',
        'session'       => 'Ethna_Session',
        'sql'           => 'Ethna_AppSQL',
        'view'          => 'Haste_ViewClass',
    );

    /**
     *  @var    array       フィルタ設定
     */
    var $filter = array(
        /*
         *  TODO: フィルタを利用する場合はここにそのクラス名を
         *  記述してください
         *
         *  記述例：
         *
         *  'Event_Filter_ExecutionTime',
         */
         'InstallCheck',
    );

    /**
     *  @var    array   マネージャ一覧
     */
    var $manager = array(
        /*
         *  TODO: ここにアプリケーションのマネージャオブジェクト一覧を
         *  記述してください
         *
         *  記述例：
         *
         *  'um'    => 'User',
         */
         'db' => 'DB',
         'user' => 'User',
    );

    /**
     *  @var    array   smarty modifier定義
     */
    var $smarty_modifier_plugin = array(
        //app/plugin_smartyに追加してください
    );

    /**
     *  @var    array   smarty function定義
     */
    var $smarty_function_plugin = array(
        //app/plugin_smartyに追加してください
    );

    /**
     *  @var    array   smarty prefilter定義
     */
    var $smarty_prefilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty prefilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_prefilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty postfilter定義
     */
    var $smarty_postfilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty postfilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_postfilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty outputfilter定義
     */
    var $smarty_outputfilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty outputfilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_outputfilter_foo_bar',
         */
    );

    /**#@-*/

    /**
     *  遷移時のデフォルトマクロを設定する
     *
     *  @access protected
     *  @param  object  Smarty  $smarty テンプレートエンジンオブジェクト
     */
    function _setDefaultTemplateEngine(&$smarty)
    {
        /*
         *  TODO: ここでテンプレートエンジンの初期設定や
         *  全てのビューに共通なテンプレート変数を設定します
         *
         *  記述例：
         * $smarty->assign_by_ref('session_name', session_name());
         * $smarty->assign_by_ref('session_id', session_id());
         *
         * // ログインフラグ(true/false)
         * $session =& $this->getClassFactory('session');
         * if ($session && $this->session->isStart()) {
         *  $smarty->assign_by_ref('login', $session->isStart());
         * }
         */
        $Config = $this->getConfig();
        $smarty->assign('site_name', $Config->get('site_name') );
        $smarty->assign('BASE_URL', $Config->get('base_url') );
    }
    
    //{{{ _getActionName_Form
    /**
     *  フォームにより要求されたアクション名を返す
     *
     *  @access protected
     *  @return string  フォームにより要求されたアクション名
     */
    function _getActionName_Form()
    {
        isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO']) ?
            $arr = explode('/', $_SERVER['PATH_INFO']) :
            $arr = false;
        
        return $arr[1];
    }
    //}}}

    function getTemplateDir()
    {
        $template = $this->getDirectory('template');
        $config = $this->getConfig();

        if (is_dir($template . '/' . $config->get('theme'))) {
            return $template . '/' . $config->get('theme');
        } else {
            return $template . '/event';
        }
    }
}
?>
