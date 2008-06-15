<?php
/** 
 * 
 * 
 */

function smarty_function_datespan($params, &$smarty)
{
    $start = strtotime($params['start']);
    $end = strtotime($params['end']);

    $ret = date('Y-m-d H:i', $start);
    $ret.= ' ～ ';
    if (date('Y', $start) != date('Y', $end)) {
        $ret.= date('Y-', $end);
    }
    if (date('Y-m-d', $start) != date('Y-m-d', $end)) {
        $ret.= date('m-d ', $end);
    }
    $ret.= date('H:i', $end);
    return $ret;
}
