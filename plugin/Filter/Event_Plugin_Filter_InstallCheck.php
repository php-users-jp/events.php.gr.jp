<?php
/**
 *  Event_Plugin_Filter_InstallCheck.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Ethna
 *  @version    $Id: app.plugin.filter.default.php,v 1.2 2006/11/06 14:31:24 cocoitiban Exp $
 */

/**
 *  実行時間計測フィルタプラグインの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Ethna
 */
class Event_Plugin_Filter_InstallCheck extends Ethna_Plugin_Filter
{

    /**
     *  実行前フィルタ
     *
     *  @access public
     */
    function preFilter()
    {
        $tmp = $this->ctl->getDirectory('tmp');
        $config = $this->ctl->getConfig();
        $dsn = $config->get('dsn');

        $error = '';

        //tmpディレクトリに書き込み権限があるかチェック
        if (!is_writable($tmp)) {
            $error.= "<p><strong>キャッシュディレクトリに書き込み権限がありません。({$tmp})に書き込み権限を付加してください</strong></p>";
        }
        //schemaディレクトリ,dbファイルに書き込み権限があるかチェック
        if (preg_match('|sqlite://[^@]+@[^/]+/(.+)|', $dsn, $matchs)) {
            $dbfile = $matchs[1];
            if (!is_writable($dbfile)) {
                $error.= "<p><strong>データベースファイルに書き込み権限がありません。({$dbfile})に書き込み権限を付加してください</strong></p>";
            }
            $dbdir = dirname($dbfile);
            if (!is_writable($dbdir)) {
                $error.= "<p><strong>データベースディレクトリに書き込み権限がありません。({$dbdir})に書き込み権限を付加してください</strong></p>";
            }
        }

        if ($error) {
            header('Content-type: text/html; charset=UTF-8');
            $html = <<<EOD
<html>
  <head>
    <title>Ethna Install Error</title>
  </head>
  <body>
    <h1>Ethna Install Error</h1>
    {$error}
  </body>
</html>
EOD;
            echo $html;
            exit();
        }
    }

    /**
     *  アクション実行前フィルタ
     *
     *  @access public
     *  @param  string  $action_name    実行されるアクション名
     *  @return string  null:正常終了 (string):実行するアクション名を変更
     */
    function preActionFilter($action_name)
    {
        return null;
    }

    /**
     *  アクション実行後フィルタ
     *
     *  @access public
     *  @param  string  $action_name    実行されたアクション名
     *  @param  string  $forward_name   実行されたアクションからの戻り値
     *  @return string  null:正常終了 (string):遷移名を変更
     */
    function postActionFilter($action_name, $forward_name)
    {
        return null;
    }

    /**
     *  実行後フィルタ
     *
     *  @access public
     */
    function postFilter()
    {
        return null;
    }
}
?>
