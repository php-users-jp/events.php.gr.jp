<?php
/**
 *
 * vim: fenc=utf-8
 *
 */

class EventsController extends AppController
{
    var $name = 'Event';
    var $helpers = array('Rss', 'Datespan');
    var $uses = array('Event', 'Trackback');

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
        $id = (int)$id;
        $this->set('event_id', $id);

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
        $has_one2 = array(
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
            )
        );

        $this->Event->bindModel(array('hasMany' => $has_many, 'hasOne' => $has_one));

        $this->Event->EventComment->bindModel(array('belongsTo' => $has_one2));
        $this->Event->EventAttendee->bindModel(array('belongsTo' => $has_one2));

        $re = $this->Event->findById($id, null,null,2);

        // WikiPageのレンダリング
        require_once APP . 'Text/PukiWiki.php';
        $pukiwiki = new Text_PukiWiki();
        $re['EventPage']['content'] = $pukiwiki->toHtml($re['EventPage']['content']);

        $attendee_count = 0;
        $joined = false;
        $canceled = false;
        foreach ($re['EventAttendee'] as $row) {
            // 自分が参加していたらフラグをたてる
            if ($this->Session->read('id') == $row['User']['id']) {
                $joined = true;
                if ($row['canceled'] == 1) {
                    $canceled = true;
                }
            }

            if ($row['canceled'] != 1) {
                $attendee_count++;
            }
        }

        $this->set('joined', $joined);
        $this->set('canceled', $canceled);
        $this->set('attendee_count', $attendee_count);
        $this->set('attendee_nokori', $re['Event']['max_register'] - $attendee_count);
        $this->set('is_over', $this->Event->isOver($id));
        $this->set('data', $re);
    }

    /**
     * control
     *
     */
    function control()
    {
        // adminじゃなければさようなら
        if ($this->Session->read('role') != 'admin') {
            $this->redirect('/');
        }

        $events = $this->Event->find('all', array('order' => 'Event.id DESC'));
        $this->set('events', $events);
    }

    /**
     * add
     *
     */
    function add()
    {
        // adminじゃなければさようなら
        if ($this->Session->read('role') != 'admin') {
            $this->redirect('/');
        }

        if ($this->data) {
            $this->Event->save($this->data);
        }

        $this->redirect('/events/control');
    }

    /**
     * edit
     *
     */
    function edit($event_id)
    {
        // adminじゃなければさようなら
        if ($this->Session->read('role') != 'admin') {
            $this->redirect('/');
        }

        if ($this->data) {
        } else {
            $event = $this->Event->findById($event_id);
            $this->data = $event;
            $this->set('event', $event);
        }
    }

    /**
     * rss
     *
     */
    function rss($id)
    {
        $this->layout = 'rss';
        $this->set('channel', array(
            'title' => "events.php.gr.jp",
            'description' => 'Feed',
        ));

        if ($id == 'trackback') {
            $result = $this->Trackback->find('all', array(
                'order' => 'Trackback.id DESC',
                'limit' => '20',
            ));

            $this->set('events', $result);

        } else if (is_numeric($id)) {

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

            $this->Event->bindModel(array('hasMany' => $has_many));
            $this->Event->EventComment->bindModel(array('belongsTo' => $has_one));
            $this->Event->EventAttendee->bindModel(array('belongsTo' => $has_one));

            $event = $this->Event->findById($id, null, null, 2);
            if (!$event) {
                $this->index();
                return null;
            }

            $result = array();

            foreach ($event['EventComment'] as $event_comment) {
                $item = array();
                $item['Event']['title'] = 'comment';
                $item['Event']['description'] = $event_comment['User']['nickname'] .':'.$event_comment['comment'];
                $item['Event']['id'] = $event_comment['event_id'];
                $item['Event']['publish_date'] = $event_comment['created'];
                if (!$event_comment['created']) {
                    $item['Event']['publish_date'] = date('Y-m-d H:i:s');
                }
                $key = strtotime($item['Event']['publish_date']) . '0';
                $result[$key] = $item;
            }

            foreach ($event['EventAttendee'] as $event_attendee) {
                $item = array();
                $item['Event']['title'] = 'joined';
                $item['Event']['description'] = $event_attendee['User']['nickname'] .':'.$event_attendee['comment'];
                $item['Event']['id'] = $event_attendee['event_id'];
                $item['Event']['publish_date'] = $event_attendee['created'];
                if (!$event_attendee['created']) {
                    $item['Event']['publish_date'] = date('Y-m-d H:i:s');
                }
                $key = strtotime($item['Event']['publish_date']) . '1';
                $result[$key] = $item;
            }

            krsort($result);

            $events = array();
            foreach ($result as $value) {
                $events[] = $value;
            }

            $this->set('events', $events);

        } else {
            $this->index();
        }

    }

}

?>
