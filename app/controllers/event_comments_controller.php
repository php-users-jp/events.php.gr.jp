<?php
/**
 *
 *
 */
class EventCommentsController extends AppController {

    var $name = 'EventComments';

    /**
     * join
     *
     */
    function join()
    {
        if ($this->data) {
            $this->data['EventComment']['event_id'] = (int)$this->data['EventComment']['event_id'];
            $user_id = $this->Session->read('id');
            if (is_array($user)) {
                $save_data = $this->data['EventComment'];
                $save_data['user_id'] = $user_id;
                $save_data['ip'] = $_SERVER['REMOTE_ADDR'];
                $save_data['ua'] = $_SERVER['HTTP_USER_AGENT'];
                $this->EventComment->save($save_data);
                $this->redirect('/events/show/'.$this->data['EventComment']['event_id']);
            }
        }

        if ($this->data['EventComment']['event_id']) {
            $this->redirect('/events/show/'.$this->data['EventComment']['event_id']);
        } else {
            $this->redirect('/');
        }
    }
}
?>
