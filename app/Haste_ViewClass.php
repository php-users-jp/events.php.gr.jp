<?php
// vim: foldmethod=marker
/**
 *  Haste_ViewClass.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Haste
 *  @version    $Id: app.viewclass.php,v 1.1 2006/08/22 15:52:26 fujimoto Exp $
 */

/**
 *  viewクラス
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Haste
 *  @access     public
 */
class Haste_ViewClass extends Ethna_ViewClass
{

    // {{{ getFormBlock
    /**
     *  フォームタグを取得する(type="form")
     *
     *  @access protected
     */
    function getFormBlock($content, $params)
    {
        // method
        if (isset($params['method']) === false) {
            $params['method'] = 'post';
        }

        // csrf plugin auto set
        if ($this->af->use_csrf_plugin == true) {
            Ethna_Util::setCsrfID();
            $buf = array();
            $csrf = smarty_function_csrfid($buf, $renderer);
            $content .= $csrf;
        }

        // duplicatepost auto set
        if ($this->af->use_duplicate_post_plugin == true) {
            $buf = array();
            $uniqid = smarty_function_uniqid($buf, $renderer);
            $content .= $uniqid;
        }

        // json validate
        if ($params['validate'] = 'json') {
            $base_url = $this->config->get('url');
            $base_url = $base_url . basename($_SERVER['SCRIPT_NAME']);

            unset($params['validate']);

            $action = explode('_', get_class($this->af));
            $action = strtolower(end($action));
            $params['onsubmit'] = "return validate('{$action}', '{$base_url}');";
            $params['id'] = "actionform_{$action}";
            $params['name'] = "actionform_{$action}";

            $content .= '<div id="actionform_' . $action . '_errors"></div>';
        }

        return $this->_getFormInput_Html('form', $params, $content, false);
    }
    // }}}

    /**
     *  指定されたフォーム項目に対応するフォームタグを取得する
     *
     *  @access public
     *  @todo   JavaScript対応
     */
    function getFormInput($name, $action, $params)
    {
        $af =& $this->_getHelperActionForm($action, $name);
        if ($af === null) {
            return '';
        }

        $def = $af->getDef($name);
        if ($def === null) {
            return '';
        }

        if (isset($def['form_type']) === false) {
            $def['form_type'] = FORM_TYPE_TEXT;
        }

        // 配列フォームが何回呼ばれたかを保存するカウンタ
        if (is_array($def['type'])) {
            static $form_counter = array();
            if (isset($form_counter[$action]) === false) {
                $form_counter[$action] = array();
            }
            if (isset($form_counter[$action][$name]) === false) {
                $form_counter[$action][$name] = 0;
            }
            $def['_form_counter'] = $form_counter[$action][$name]++;
        }

        if (is_string($def['form_type'])) {

            $form_type = trim(ucfirst($def['form_type']));
            $plugin_list = $this->plugin->getPluginList('FormType');

            //search from plugin
            if (in_array($form_type, array_keys($plugin_list))) {
                $plugin = $plugin_list[$form_type];
                return $plugin->fetch($name, $def, $params);
            } else {
                //search in method list
                $method_list = get_class_methods($this);
                $form_type_list = array();

                foreach ($method_list as $method_name) {
                    if (strpos($method_name, '_getFormInput_') === 0) {
                        $parts = explode('_', $method_name);
                        $form_type_list[] = end($parts);
                    }
                }

                if (in_array($form_type, $form_type_list)) {
                    $function_name = '_getFormInput_' . $form_type;
                    return $this->$function_name($name, $def, $params);
                } else {
                    return $input = $this->_getFormInput_Text($name, $def, $params);
                }
            }
            

        } else {

            switch ($def['form_type']) {
            case FORM_TYPE_BUTTON:
                $input = $this->_getFormInput_Button($name, $def, $params);
                break;

            case FORM_TYPE_CHECKBOX:
                $def['option'] = $this->_getSelectorOptions($af, $def, $params);
                $input = $this->_getFormInput_Checkbox($name, $def, $params);
                break;

            case FORM_TYPE_FILE:
                $input = $this->_getFormInput_File($name, $def, $params);
                break;

            case FORM_TYPE_HIDDEN:
                $input = $this->_getFormInput_Hidden($name, $def, $params);
                break;

            case FORM_TYPE_PASSWORD:
                $input = $this->_getFormInput_Password($name, $def, $params);
                break;

            case FORM_TYPE_RADIO:
                $def['option'] = $this->_getSelectorOptions($af, $def, $params);
                $input = $this->_getFormInput_Radio($name, $def, $params);
                break;

            case FORM_TYPE_SELECT:
                $def['option'] = $this->_getSelectorOptions($af, $def, $params);
                $input = $this->_getFormInput_Select($name, $def, $params);
                break;

            case FORM_TYPE_SUBMIT:
                $input = $this->_getFormInput_Submit($name, $def, $params);
                break;

            case FORM_TYPE_TEXTAREA:
                $input = $this->_getFormInput_Textarea($name, $def, $params);
                break;

            case FORM_TYPE_TEXT:
            default:
                $input = $this->_getFormInput_Text($name, $def, $params);
                break;
            }
            
            return $input;

        }

    }

    function _setDefault(&$render)
    {
        $headers_sent = false;
        foreach (headers_list() as $header) {
            if (stripos($header, 'content-type') === 0) {
                $headers_sent = true;
            }
        }
        if (!$headers_sent) {
            header('Content-Type: text/html; charset=UTF-8');
        }
    }

}
?>
