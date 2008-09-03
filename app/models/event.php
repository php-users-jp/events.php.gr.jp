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

    /**
     * findRssById
     *
     */
    function findRssById($event_id)
    {
        $has_many = array(
            'EventComment' => array(
                'className' => 'EventComment',
                'foreignKey' => 'event_id'
            ),
            'EventAttendee' => array(
                'className' => 'EventAttendee',
                'foreignKey' => 'event_id'
            ),
        );

        $has_one = array(
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
            )
        );

        $this->bindModel(array('hasMany' => $has_many));
        $this->EventComment->bindModel(array('belongsTo' => $has_one));
        $this->EventAttendee->bindModel(array('belongsTo' => $has_one));

        $event = $this->findById($event_id, null, null, 2);

        return $event;
    }

}

?>
