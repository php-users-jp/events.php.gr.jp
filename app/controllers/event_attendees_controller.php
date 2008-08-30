<?php
/**
 *
 *
 */
class EventAttendeesController extends AppController {

    var $name = 'EventAttendees';

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

    function cancelrevert($id)
    {
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

}
?>
