<?php
/**
 *  function.rss
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Haste
 *  @version    $Id$
 */

/**
 * rss
 *
 * @access public
 * @author halt <halt.feits@gmail.com>
 */
function smarty_function_rss($params, $smarty)
{
    require_once "Cache/Lite.php";

    if (isset($params['encoding_from'])) {
        $encoding_from = $params['encoding_from'];
    } else {
        $encoding_from = 'UTF-8';
    }

    if (isset($params['encoding_to'])) {
        $encoding_to = $params['encoding_to'];
    } else {
        $encoding_to = 'euc-jp';
    }

    $Controller =& Ethna_Controller::getInstance();
    $dir_cache = $Controller->getDirectory('tmp');
    $options = array(
        'cacheDir' => $dir_cache . '/',
        'lifeTime' => 3600
    );
    $CacheLite = new Cache_Lite($options);

    $url = $params['url'];

    if ( $data = $CacheLite->get($url)) {
        print($data);
    } else {

        $ret[] = '<ul class="plugin_rss">';
        $xml = @simplexml_load_file($url);

        if ( $xml == false) {
            $buf = "<ul>\n";
            $buf = "<li>RSSを取得できません。</li>\n";
            $buf = "</ul>\n";
            $buf = mb_convert_encoding($buf, $encoding_to, $encoding_from);
            print($buf);
            return false;
        }

        foreach($xml->item as $item){

            /**
             * Namespace付きの子要素を取得
             * この場合、<dc:date>要素が対象
             */
            $dc = $item->children('http://purl.org/dc/elements/1.1/');

            $date = isset($dc->date) ? '&nbsp;(' . date('Y-m-d H:i', strtotime($dc->date)) . ')' : '';
            $link = str_replace('&', '&amp;', $item->link);
            $title = mb_convert_encoding($item->title, 'UTF-8', 'auto');
            $line = '<li>';
            $line.= "<a href=\"{$link}\">{$title}</a>";
            $line.= '</li>';

            $ret[] = $line;
        }

        $ret[] = '</ul>';
        $data = join("\n", $ret);
        $data = mb_convert_encoding($data, $encoding_to, $encoding_from);
        $CacheLite->save($data);
        print($data);
    }

}

/**
 * assoc2table
 *
 */
function assoc2table($params, $smarty)
{
    if (!isset($params['value'])) {
        print('error:invalid parameter');
    }

    $data = $params['value'];
    $i = 0;
    $th = "<tr>";
    $tr = "";

    foreach ($data as $row) {

        $tr .= "<tr>\n";

        foreach ($row as $key => $value) {

            if ($i == 0) {
                $th .= "<th>{$key}</th>\n";
            }

            $tr.= "<td>{$value}</td>";
        }

        $tr .= "</tr>\n";

        $i++;

    }

    $th.= "</tr>\n";
    $html = "<table class=\"assoc2table\">\n";
    $html.= "{$th}{$tr}";
    $html.= "</table>";

    print($html);
}
?>
