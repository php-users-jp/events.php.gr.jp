<?php
/**
 * Rakuten.php
 *
 * @package Text_PukiWiki
 * @author halt feits <halt.feits@gmail.com>
 */

require_once 'Text/PukiWiki/Plugin.php';

/**
 * Rakuten - PukiWiki plugin
 *
 * @package Text_PukiWiki
 * @author halt feits <halt.feits@gmail.com>
 */
class Text_PukiWiki_Rakuten extends Text_PukiWiki_Plugin
{
    var $use_cache = true;

    function load($arg)
    {
        $params = $this->parseArg($arg);

        if ($params[0] == "search") {
            $limit = 1;

            if (is_numeric($params[2])) {
                $limit = intval($params[2]);
                if ($limit < 1 || $limit > 3) {
                    $limit = 1;
                }
            }

            if ($this->use_cache === true) {
                $ctl =& Anubis_Controller::getInstance();
                $backend =& $ctl->getBackend();
                $plugin =& $backend->getPlugin();
                $cm =& $plugin->getPlugin('Cachemanager', 'Localfile');

                if ($cm->isCached($params[1], 3600,'plugin_rakuten')) {
                    return $cm->get($params[1], 3600, 'plugin_rakuten');
                }
            }

            return $this->search($params[1], $limit);
        }

        return $arg;
    }

    function parseArg($arg)
    {
        $params = explode(',', $arg);

        if (!is_array($params) || (count($params) < 2)) {
            //invalid arguments
            return false;
        }

        foreach ($params as $key => $value) 
        {
            $params[$key] = trim($value, " \"'");
        }

        return $params;
    }

    function search($keyword, $limit)
    {
        require_once 'Services/Rakuten.php';

        $dev_id = '434637cd52618592fbe80aa3c625a5b6';
        $afi_id = '06b2c372.4935dd5e.06b2c373.d61e8826';

        $keyword = mb_convert_encoding($keyword, 'UTF-8', 'EUC-JP');

        if ($limit === 1) {
          $hits = '2';
        } else {
          $hits = $limit;
        }

        // 楽天商品検索
        $api = Services_Rakuten::factory('ItemSearch', $dev_id, $afi_id);

        $api->execute(
            array(
                'keyword' => $keyword,
                'availability' => '1',
                'sort' => '+affiliateRate',
                'hits' => $hits,
            )
        );

        $data = $api->getResultData();
        $items = $data['Items']['Item'];
        $html = "";

        foreach ($items as $item)
        {
            if ($item['imageFlag'] = 1) {
                $html .= "<p>";
                $html .= "<a href=\"{$item['affiliateUrl']}\">";
                $html .= "<img src=\"{$item['mediumImageUrl']}\"><br />";
                $html .= "{$item['itemName']}</a>";
                $html .= "</p>";
            }

            if ($limit === 1) {
              break;
            }
        }
        
        $html = mb_convert_encoding($html, 'EUC-JP', 'UTF-8');

        if ($this->use_cache === true) {
            $ctl =& Anubis_Controller::getInstance();
            $backend =& $ctl->getBackend();
            $plugin =& $backend->getPlugin();
            $cm =& $plugin->getPlugin('Cachemanager', 'Localfile');

            $cm->set($keyword, $html, null, 'plugin_rakuten');
        }

        return $html;
    }
}
