<?php
/**
 *
 *
 */

/**
 * EventPagesController
 *
 */
class EventPagesController extends AppController
{
    var $name = 'EventPage';

    /**
     * edit
     *
     */
    public function edit($id = false)
    {
        // WikiPageのレンダリング
        require_once APP . 'Text/PukiWiki.php';
        $pukiwiki = new Text_PukiWiki();

        if ($this->data) {
            // @TODO もっとうまいやりかたはないの？
            if (isset($_POST['preview'])) {
                $page = $this->data;
                $page['EventPage']['html'] = $pukiwiki->toHtml($page['EventPage']['content']);
                $this->set('page', $page);
            } else {
                $this->data['EventPage']['timestamp'] = time();
                $this->EventPage->save($this->data);
                $this->redirect('/events/show/' . $this->data['EventPage']['event_id']);
            }
        } else {
            $page = $this->EventPage->findByEventId($id, null, 'EventPage.timestamp DESC');
            if ($page) {
                $page['EventPage']['html'] = $pukiwiki->toHtml($page['EventPage']['content']);

                $this->data = $page;
                $this->set('page', $page);
            }
        }

        $this->set('event_id', $id);
        $this->set('pukiwiki', $pukiwiki);
    }

    /**
     * preview
     *
     */
    public function preview($id)
    {
    }
}

?>
