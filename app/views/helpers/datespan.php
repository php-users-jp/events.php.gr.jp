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
     * #test
     * <code>
     * #eq('2008-09-26 19:00 〜 21:00', #f('2008-09-26 19:00:00', '2008-09-26 21:00:00'));
     * #eq('2008-09-24 19:00 〜 09-26 21:00', #f('2008-09-24 19:00:00', '2008-09-26 21:00:00'));
     * #eq('2007-09-24 19:00 〜 2008-09-26 21:00', #f('2007-09-24 19:00:00', '2008-09-26 21:00:00'));
     * </code>
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
