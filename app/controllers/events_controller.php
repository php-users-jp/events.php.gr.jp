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
        foreach ($re['EventAttendee'] as $row) {
            // 自分が参加していたらフラグをたてる
            if ($this->Session->read('username') == $row['User']['username']) {
                $this->set('joined', true);
                if ($row['canceled'] == 1) {
                    $this->set('canceled', true);
                } else {
                    $this->set('canceled', false);
                }
            } else {
                $this->set('joined', false);
            }

            if ($row['canceled'] != 1) {
                $attendee_count++;
            }
        }

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
     * post
     *
     */
    function post()
    {
        if ($this->data) {
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
        $this->index();
    }

}

?>
