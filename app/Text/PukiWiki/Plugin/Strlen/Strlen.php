<?php
/**
 * Strlen.php
 *
 * @package Text_PukiWiki
 * @author TSURUOKA Naoya <tsuruoka@labs.cybozu.co.jp>
 */

require_once 'Text/PukiWiki/Plugin.php';

/**
 * strlen - PukiWiki plugin
 *
 * @package Text_PukiWiki
 * @author TSURUOKA Naoya <tsuruoka@labs.cybozu.co.jp>
 */
class Text_PukiWiki_Strlen extends Text_PukiWiki_Plugin
{
    function load($arg)
    {
        return mb_strlen($this->getSource(), 'UTF-8');
    }
}
