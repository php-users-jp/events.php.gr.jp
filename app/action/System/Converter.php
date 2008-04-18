<?php
/**
 *  System/Converter.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: /project/event/trunk/skel/skel.action.php 10 2006-04-29T15:04:12.368054Z halt  $
 */

/**
 *  System_Converterフォームの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_SystemConverter extends Haste_ActionForm
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
    );
}

/**
 *  System_Converterアクションの実装
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_SystemConverter extends Ethna_ActionClass
{
    /**
     *  System_Converterアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $ctl = $this->backend->getController();
        $plugin = $ctl->getPlugin();
        $this->system =  $plugin->getPlugin('System', 'Converter');

        $version = $this->system->getDBVersion();
        $result = $this->system->updateDB($version);

        $latest_version = $this->system->getLatestDBVersion();

        $this->af->setApp('latest_version', $latest_version);
        $this->af->setApp('version', $version);
        
        if ($result !== true) {
            var_dump($result);
        } else {
            print("upgraded from {$version}");
        }

        return null;
    }

    /**
     *  System_Converterアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        return null;
    }
}
?>
