<?php
/**
 *
 * Event_Util
 *
 * @access public
 */
class Event_Util
{
    /**
     *
     * redirect
     *
     * @return  string  $redirect_url
     * @return  integer $sec
     * @return  string  $message
     * @access  public
     */
    function redirect($redirect_url, $sec, $message)
    {
        $controller = Ethna_Controller::getInstance();
        $smarty = $controller->getTemplateEngine();
        if (!method_exists($smarty, 'fetch')) {
            $smarty = $smarty->engine;
        }

        $meta = '<meta http-equiv="pragma" content="no-cache">';
        $meta.= '<meta http-equiv="cache-control" content="no-cache">';
        $meta.= '<meta http-equiv="expires" content="Sat, 31 Aug 2002 17:35:42 GMT">';
        $meta.= "<meta http-equiv=\"refresh\" content=\"{$sec};url={$redirect_url}\">";
        $smarty->assign('redirect', $meta);
        $smarty->assign('message', $message);

$result = $smarty->fetch('redirect.tpl');

        header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
print($result);
//        print($smarty->fetch('redirect.tpl'));
        exit;
    }

    /**
     * getPathinfoArg
     *
     */
    function getPathinfoArg($path_info_query = "")
    {
        if ($path_info_query == "") {
            $path_info_query = $_SERVER['PATH_INFO'];
        }

        $path_info = explode('/', $path_info_query);

        if (isset($path_info[2])) {
            return $path_info[2];
        }

        return false;
    }

    //{{{ unhtmlentities
    function unhtmlentities($string)
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip ($trans_tbl);
        return strtr ($string, $trans_tbl);
    }
    //}}}
}
?>
