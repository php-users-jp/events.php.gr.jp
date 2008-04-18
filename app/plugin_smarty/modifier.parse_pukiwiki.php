<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

require_once 'Text/PukiWiki.php';

/**
 * PukiWikiParser Smarty Plugin
 *
 * @param string $string
 * @return string
 */
function smarty_modifier_parse_pukiwiki($string)
{
    $pukiwiki = new Text_PukiWiki();
    return $pukiwiki->toHtml($string);
}

/* vim: set expandtab: */

?>
