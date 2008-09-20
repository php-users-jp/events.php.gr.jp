<?php
/**
 * events_controller.php
 *
 * vim: fenc=utf-8
 *
 */

/**
 * EventsController
 *
 *
 */
class EventsController extends AppController
{
    var $name = 'Event';
    var $helpers = array('Rss', 'Datespan', 'Javascript');
    var $uses = array('Event', 'Trackback');

    /**
     * index
     *
     */
    function index()
    {
        $this->paginate = array('Event'  => array(
            'conditions' => array(
                'Event.private' => 0,
                'Event.publish_date <' => date('Y-m-d H:i:s')
            ),
            'limit' => 5,
            'order' => array('Event.id' => 'desc')
        ));

        $result = $this->paginate();
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
                'order' => 'EventPage.timestamp DESC',
                'foreignKey' => 'event_id',
            )
        );
        $has_one2 = array(
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
            )
        );

        $this->Event->bindModel(
            array('hasMany' => $has_many, 'hasOne' => $has_one)
        );

        $this->Event->EventComment->bindModel(array('belongsTo' => $has_one2));
        $this->Event->EventAttendee->bindModel(array('belongsTo' => $has_one2));

        $re = $this->Event->findById($id, null, null, 2);
        if (!$re) {
            // @TODO 404だしたい
            $this->redirect('/');
        }

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
            $this->flash('イベント情報を登録しました','/events/control');
            return;
        }

        //viewを流用
        $this->render('edit');
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
            $this->Event->af_through_flag = true;
            $event = $this->Event->findById($event_id);
            $this->data = $event;
            $this->set('event', $event);
        }
    }

    /**
     * rss
     *
     */
    function rss($id = false)
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

            $event = $this->Event->findRssById($id);

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