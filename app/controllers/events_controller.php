<?php
/**
 *
 * vim: fenc=utf-8
 *
 */

class EventsController extends AppController
{
    var $name = 'Event';
    var $helpers = array('Datespan');

    /**
     * index
     *
     */
    function index()
    {
        $result = $this->Event->find(
            'all',
            array(
                'conditions' => array(
                    'Event.private' => 0,
                    'Event.publish_date <' => date('Y-m-d H:i:s')
                ),
                'limit' => 5,
                'order' => array('Event.id' => 'desc')
            )
        );

        $this->set('events', $result);

    }

    /**
     * show
     *
     */
    function show($id)
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
            'Trackback' => array(
                'className' => 'Trackback',
                'foreignKey' => 'event_id'
            )
        );
        $has_one = array(
            'EventPage' => array(
                'className' => 'EventPage',
                'foreignKey' => 'event_id',
            )
        );

        $this->Event->bindModel(array('hasMany' => $has_many, 'hasOne' => $has_one));

        $re = $this->Event->findById($id);

        // WikiPageのレンダリング
        require_once APP . 'Text/PukiWiki.php';
        $pukiwiki = new Text_PukiWiki();
        $re['EventPage']['content'] = $pukiwiki->toHtml($re['EventPage']['content']);

        $attendee_count = 0;
        foreach ($re['EventAttendee'] as $row) {
            // 自分が参加していたらフラグをたてる
            /*
            if (isset($_SESSION['name']) && $row['account_name'] == $_SESSION['name']) {
                $this->af->setApp('joined', true);
                if ($row['canceled'] == 1) {
                    $this->af->setApp('canceled', true);
                }
            }
             */

            if ($row['canceled'] != 1) {
                $attendee_count++;
            }
        }

        $this->set('attendee_count', $attendee_count);
        $this->set('attendee_nokori', $re['Event']['max_register'] - $attendee_count);
        $this->set('data', $re);
    }
}


?>
