<?php
// vim: foldmethod=marker
/**
 *  Haste_ActionForm.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Haste
 *  @version    $Id: app.actionform.php,v 1.1 2006/08/22 15:52:26 fujimoto Exp $
 */

// {{{ Haste_ActionForm
/**
 *  アクションフォームクラス
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Haste
 *  @access     public
 */
class Haste_ActionForm extends Ethna_ActionForm
{
    /**#@+
     *  @access private
     */

    /** @var    bool    バリデータにプラグインを使うフラグ */
    var $use_validator_plugin = true;
    var $use_csrf_plugin = true;
    var $use_duplicate_post_plugin = true;
    /**#@-*/

    /** 
     * _validatePlus
     *
     */
    function _validatePlus()
    {   
        if ($this->use_csrf_plugin == true) {
            if (!Ethna_Util::isCsrfSafe()) {
                $this->ae->add(null, '外部からのリクエストは受け付けていません');
            }   
        }   
        if ($this->use_duplicate_post_plugin == true) {
            if (Ethna_Util::isDuplicatePost()) {
                $this->ae->add(null, '二重投稿は受け付けていません');
            }   
        }   
    }  

}
// }}}
?>
