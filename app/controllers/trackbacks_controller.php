<?php
/**
 *
 *
 */

class TrackbacksController extends AppController
{
    var $name = 'Trackback';

    /**
     * delete
     *
     */
    function delete($id)
    {
        if ($this->isAdmin()) {
            $this->redirect('/');
        }

        $trackback = $this->Trackback->findById($id);
        if ($trackback) {
            $this->Trackback->del($id);
            $this->redirect('/events/show/'.$trackback['Trackback']['event_id']);
        }

        $this->redirect('/');
    }
}

?>
