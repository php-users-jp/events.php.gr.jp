<?php
require_once 'Ethna/class/Ethna_ActionError.php';
class Ethna_ActionError_UTF8 extends Ethna_ActionError
{
    /**
     *  アプリケーションエラーメッセージを取得する
     *
     *  @access private
     *  @param  array   エラーエントリ
     *  @return string  エラーメッセージ
     */
    function _getMessage(&$error)
    {
        $af =& $this->_getActionForm();
        $form_name = $af->getName($error['name']);
        $form_name = mb_convert_encoding($form_name, 'EUC-JP', 'UTF-8');
        $result = str_replace("{form}", $form_name, $error['object']->getMessage());
        return mb_convert_encoding($result, 'UTF-8', 'EUC-JP');
    }
}
?>
