<?php
/**
 *
 *
 */

/**
 * Event
 *
 * #test __setup
 * <code>
 * $this->obj = new Event();
 * $this->db = $this->obj->getDataSource();
 *
 * $fixture = 'event_test';
 * require_once(TESTS . 'fixtures/event_test_fixture.php');
 * $fixtureClass = Inflector::camelize($fixture) . 'Fixture';
 * $this->_fixtures[Inflector::camelize($fixture)] =& new $fixtureClass($this->db);
 * $this->_fixtureClassMap[Inflector::camelize($fixture)] = $fixture;
 *
 * $sources = $this->db->listSources();
 * foreach ($this->_fixtures as $fixture) {
 *     if (in_array($fixture->table, $sources)) {
 *         $fixture->drop($this->db);
 *     }
 *
 *     $fixture->create($this->db);
 * }
 *
 * // Create records
 * if (isset($this->_fixtures) && isset($this->db)) {
 *     foreach ($this->_fixtures as $fixture) {
 *         $inserts = $fixture->insert($this->db);
 *     }
 * }
 * </code>
 *
 * #test __teardown
 * <code>
 * if (isset($this->_fixtures) && isset($this->db)) {
 *     foreach (array_reverse($this->_fixtures) as $fixture) {
 *         $fixture->drop($this->db);
 *     }
 * }
 * $this->obj = null;
 * </code>
 */
class Event extends AppModel
{
    var $name = 'Event';
    var $useTable = 'event';

    /**
     * isOver
     *
     * #test
     * <code>
     * $base = $this->obj->query('SELECT id FROM event limit 1');
     * #true(#f($base[0][0]['id']));
     * #false(#f(-1));
     * </code>
     */
    function isOver($event_id, $timestamp = null)
    {
        if (is_null($timestamp)) {
            $timestamp = time();
        }

        $query = "SELECT due_date FROM event WHERE id = ? LIMIT 1";
        $result = $this->query($query, array($event_id));

        if (isset($result[0][0]['due_date'])) {
            $duedate = $result[0][0]['due_date'];
        } else {
            return false;
        }

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
        if (!isset($this->EventAttendee)) {
            $has_many = array(
                'EventAttendee' => array(
                    'className' => 'EventAttendee',
                    'foreignKey' => 'event_id'
                )
            );
            $this->bindModel(
                array('hasMany' => $has_many)
            );
        }

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
