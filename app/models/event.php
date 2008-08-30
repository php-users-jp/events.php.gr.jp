<?php
/**
 *
 *
 */

class Event extends AppModel
{
    var $name = 'Event';
    var $useTable = 'event';

    /**
     * isOver
     *
     */
    function isOver($event_id, $timestamp = null)
    {
        if (is_null($timestamp)) {
            $timestamp = time();
        }

        $query = "SELECT due_date FROM event WHERE id = ? LIMIT 1";
        $result = $this->query($query, array($event_id));

        $duedate = $result[0][0]['due_date'];

        if (strtotime($duedate) < $timestamp) {
            return true;
        } else {
            return false;
        }
    }

}

?>
