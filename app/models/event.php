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
        $event['Event']['description'] = $this->convertDescription($event['Event']['description'], false);

        return $event;
    }

    /**
     * convertDescription
     *
     */
    function convertDescription($description, $joined = null)
    {
        if ($joined == null) {
        }

        if (!$joined) {
            $replaced = '';
        } else {
            $replaced = '\1';
        }

        $description = preg_replace("_\(\(\((.*?)\)\)\)_ims", $replaced, $description);
        return $description;
    }

    /**
     * afterFind
     *
     */
    function afterFind($result)
    {
        foreach ($result as $key => $row) {
            $result[$key]['Event']['description'] = $this->convertDescription($row['Event']['description'], $this->joined($row['Event']['id']));
        }

        return $result;
    }

    /**
     * joined
     *
     */
    function joined($event_id, $user_id = null)
    {
        if ($user_id == null) {
            if (isset($_SESSION['id'])) {
                $user_id = $_SESSION['id'];
            } else {
                return false;
            }
        }

        $result = $this->EventAttendee->find(
            'all',
            array('Event.id' => $event_id)
        );

        foreach ($result as $row) {
            // 自分が参加していたらフラグをたてる
            if ($user_id == $row['EventAttendee']['user_id']) {
                return true;
            }
        }
    }

}

?>
