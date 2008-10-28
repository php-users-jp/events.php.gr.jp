<?php
/**
 * event_attendees_controller.php
 *
 */
class EventAttendeesController extends AppController {

    var $name = 'EventAttendees';

    /**
     * cancel
     *
     */
    function cancel($id)
    {
        $event_attendee = $this->EventAttendee->findById($id);
        if (!$event_attendee) {
            $this->redirect('/');
        }

        if ($this->Session->read('role') == 'admin' || $this->Session->read('id') == $event_attendee['EventAttendee']['user_id']) {
            $event_attendee['EventAttendee']['canceled'] = 1;
            $this->EventAttendee->save($event_attendee);
        }

        $this->redirect('/events/show/' . $event_attendee['EventAttendee']['event_id']);
    }

    /**
     * cancelrevert
     *
     */
    function cancelrevert($id)
    {
        // adminじゃなければさようなら
        if ($this->Session->read('role') != 'admin') {
            $this->redirect('/');
        }

        $event_attendee = $this->EventAttendee->findById($id);
        if (!$event_attendee) {
            $this->redirect('/');
        }

        if ($this->Session->read('role') == 'admin' || $this->Session->read('id') == $event_attendee['EventAttendee']['user_id']) {
            $event_attendee['EventAttendee']['canceled'] = 0;
            $this->EventAttendee->save($event_attendee);
        }

        $this->redirect('/events/show/' . $event_attendee['EventAttendee']['event_id']);
    }

    /**
     * join
     *
     * @TODO もうちょっと綺麗になおす
     */
    function join()
    {
        if (!$this->Session->check('id')) {
            $this->redirect('/users/login');
        }

        if ($this->data) {
            $this->data['EventAttendee']['user_id'] = $this->Session->read('id');
            $this->data['EventAttendee']['canceled'] = 0;
            $this->EventAttendee->save($this->data);
        }

        $this->redirect('/events/show/' . $this->data['EventAttendee']['event_id']);
    }

    function party($id)
    {
        $event_attendee = $this->EventAttendee->findById($id);
        if (!$event_attendee) {
            $this->redirect('/');
        }

        if ($this->Session->read('role') == 'admin' || $this->Session->read('id') == $event_attendee['EventAttendee']['user_id']) {
            $event_attendee['EventAttendee']['party'] = 1;
            $this->EventAttendee->save($event_attendee);
        }

        $this->redirect('/events/show/' . $event_attendee['EventAttendee']['event_id']);
    }
    
    function party_cancel($id)
    {
        $event_attendee = $this->EventAttendee->findById($id);
        if (!$event_attendee) {
            $this->redirect('/');
        }

        if ($this->Session->read('role') == 'admin' || $this->Session->read('id') == $event_attendee['EventAttendee']['user_id']) {
            $event_attendee['EventAttendee']['party'] = 0;
            $this->EventAttendee->save($event_attendee);
        }

        $this->redirect('/events/show/' . $event_attendee['EventAttendee']['event_id']);
    }

    /**
     * 管理者による強制追加アクション
     *
     * @param コメントID $comment_id
     */
    function extra_join($id){

    	if ($this->Session->read('role') != 'admin') {
    		$this->redirect('/events/show/');
    		return;
    	}
    	
    	App::import('model','EventComment');
    	$event_comment = new EventComment();
    	$comment = $event_comment->findById($id);
    	
    	if (empty($comment)) {
    		$this->redirect('/events/');
    		return;    		
    	}
    	
	
    	$cond = array(
    	    'event_id' =>$comment['EventComment']['event_id'],
    	    'user_id' => $comment['EventComment']['user_id']
    	  );
    	$data = array(
    	  'EventAttendee' => $cond
    	);

    	$row = $this->EventAttendee->find('all',array( 'conditions' =>$cond));

    	if ( !empty($row)) {
     		$this->redirect('/events/show/'. $comment['EventComment']['event_id']);
    		return;    		   		
    	}
    	
     	$this->EventAttendee->create();
    	$this->EventAttendee->save($data);
        $this->flash('参加者に追加しました。','/events/show/' . $comment['EventComment']['event_id']);    	

    }
}
?>
