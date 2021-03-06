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
        $preview = '';
        
        if ($id == false) {
            $this->flash('指定のWikiページは存在しません', '/');
        }

        if ($this->data) {
            // @TODO もっとうまいやりかたはないの？
            if (isset($this->params['form']['preview'])) {
                $page = $this->data;
                $preview = $pukiwiki->toHtml($page['EventPage']['content']);
                $this->set('page', $page);
            } else {
                $this->data['EventPage']['user_id'] = $this->Session->read('id');
                $this->EventPage->save($this->data);
                $this->flash('Wikiページの更新が完了しました', '/events/show/' . $this->data['EventPage']['event_id']);
            }
        } else {
            $this->EventPage->af_through_flag = true;
            $page = $this->EventPage->findByEventId($id, null, 'EventPage.created DESC');
            if ($page) {
                $this->data = $page;
                $this->set('page', $page);
            }
        }

        $this->set('preview',$preview);
        $this->set('event_id', $id);
        $this->set('pukiwiki', $pukiwiki);
    }
}

?>
