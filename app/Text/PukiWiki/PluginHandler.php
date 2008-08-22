<?php
/**
 * PluginHandler.php
 *
 * @package Text_PukiWiki
 * @author TSURUOKA Naoya <tsuruoka@labs.cybozu.co.jp>
 */

/**
 * Text_PukiWiki_PluginHandler class
 *
 * @package Text_PukiWiki
 * @author TSURUOKA Naoya <tsuruoka@labs.cybozu.co.jp>
 */
class Text_PukiWiki_PluginHandler
{
    var $plugin_dir;

    function Text_PukiWiki_PluginHandler()
    {
        $this->plugin_dir = dirname(__FILE__) . "/Plugin";
    }

    function getPlugin($r_plugin_name, $r_src)
    {
        $plugin_class = ucfirst(basename($r_plugin_name));
        $plugin_path = "{$this->plugin_dir}/{$plugin_class}/{$plugin_class}.php";
        
        if (!file_exists($plugin_path)) {
            return "plugin {$r_plugin_name} is not found";
        }

        require_once $plugin_path;
        $plugin_class_name = "Text_PukiWiki_{$plugin_class}";
        $plugin = new $plugin_class_name($r_src);

        return $plugin;
    }
}
?>
