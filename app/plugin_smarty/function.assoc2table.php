<?php
/**
 *  smarty.function.assoc2table
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Haste
 *  @version    $Id$
 */

/**
 * assoc2table
 *
 */
function smarty_function_assoc2table($params, $smarty)
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
