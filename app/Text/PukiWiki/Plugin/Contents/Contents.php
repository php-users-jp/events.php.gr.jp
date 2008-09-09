<?php
/**
 * Contents.php
 *
 * @package Text_PukiWiki
 * @author TSURUOKA Naoya <tsuruoka@labs.cybozu.co.jp>
 */

require_once 'PukiWiki/Plugin.php';

/**
 * contents - PukiWiki plugin
 *
 * @package Text_PukiWiki
 * @author TSURUOKA Naoya <tsuruoka@labs.cybozu.co.jp>
 */
class Text_PukiWiki_Contents extends Text_PukiWiki_Plugin
{
    function load($arg)
    {
        return $this->parseSource($this->getSource());
    }

    function parseSource($src)
    {
        $match = "";
        preg_match_all('/(\*{1,5})(.*?)\n/', $src, $match, PREG_SET_ORDER);

        return $this->parseList($match);
    }

    function parseList(&$params, $current_level = 1)
    {
        $list = array();
        $list[] = "<ul>\n";
        while(!is_null($param = array_shift($params))) {
            $level = strlen($param[1]);
            $value = trim($param[2]);

            if ($current_level < $level) {
                array_pop($list);
                array_unshift($params, $param);
                $list[] = $this->parseList($params, $level) . "</li>\n";
            } else if ($level < $current_level) {
                array_unshift($params, $param);
                break;
            } else {
                $list[] = "<li>{$value}";
                $list[] = "</li>\n";
            }
                
        }
        $list[] = "</ul>\n";

        return implode('', $list);
    }

}
