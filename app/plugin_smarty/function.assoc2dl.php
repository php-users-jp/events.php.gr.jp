<?php
/**
 *  smarty.function.assoc2dl
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Haste
 *  @version    $Id$
 */

/**
 * assoc2dl
 *
 */
function smarty_function_assoc2dl($params, $smarty)
{
    if (!isset($params['value']) && !is_array($params['value'])) {
        print('error:invalid parameter');
    }

    $data = $params['value'];

    foreach ($data as $key => $value) {

        $html .= "<dt>{$key}</dt><dd>{$value}</dd>\n";

    }

    print("<dl>\n{$html}</dl>\n");
}
?>
