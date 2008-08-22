<?php
/**
 * datespan.php
 *
 * vim:fenc=utf-8
 */

/**
 * Datespan
 *
 */
class DatespanHelper extends Helper
{
    /**
     * display
     *
     */
    function display($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);

        $ret = date('Y-m-d H:i', $start);
        $ret.= ' 〜 ';
        if (date('Y', $start) != date('Y', $end)) {
            $ret.= date('Y-', $end);
        }
        if (date('Y-m-d', $start) != date('Y-m-d', $end)) {
            $ret.= date('m-d ', $end);
        }
        $ret.= date('H:i', $end);

        return $ret;
    }

}
?>
