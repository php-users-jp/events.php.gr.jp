<?php
/**
 * Raw.php
 *
 * @package Text_PukiWiki
 */

require_once 'Text/PukiWiki/Plugin.php';

/**
 * PukiWiki plugin
 *
 */
class Text_PukiWiki_Raw extends Text_PukiWiki_Plugin
{
    function load($arg)
    {
        return $arg;
    }
}
