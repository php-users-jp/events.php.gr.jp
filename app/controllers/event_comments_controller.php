<?php
/**
 *
 *
 */
class EventCommentsController extends AppController {

    var $name = 'EventComments';
    var $uses = array('EventComment', 'User');

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

    /**
     * delete
     *
     */
    function delete($comment_id)
    {
        $comment = $this->EventComment->findById($comment_id);
        if (!$comment) {
            $this->redirect('/');
        }

        $user = $this->User->findById($comment['EventComment']['user_id']);

        $username = $this->Session->read('username');
        
        if ($user['User']['username'] == $username) {
            $this->EventComment->del($comment['EventComment']['id']);
        } else if ($this->Session->read('role') == 'admin') {
            $this->EventComment->del($comment['EventComment']['id']);
        }

        $this->redirect('/events/show/'.$comment['EventComment']['event_id']);
    }
}
?>
