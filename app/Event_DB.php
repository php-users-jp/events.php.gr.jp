<?php
require_once 'Ethna/class/DB/Ethna_DB_ADOdb.php';

class Event_DB extends Ethna_DB_ADOdb
{
    /**
     * isOverEvent
     *
     */
    function isOverEvent($event_id, $timestamp = null)
    {
        if (is_null($timestamp)) {
            $timestamp = time();
        }

        $query = "SELECT due_date FROM event WHERE id = ? LIMIT 1";
        $duedate = $this->db->getOne($query, array($event_id));

        if (strtotime($duedate) < $timestamp) {
            return true;
        } else {
            return false;
        }
    }

    function getEventPageFromEventId($event_id)
    {
        $query = "SELECT * FROM event_page WHERE event_id = ?";
        $query.= "ORDER BY timestamp DESC LIMIT 1";

        return $this->getRow($query, array($event_id));
    }

    /**
     * getEventAttendeeFromId
     *
     * @param int $event_id
     */
    function getEventAttendeeFromId($event_id)
    {
        $query = "SELECT * FROM event_attendee";
        $query.= " WHERE event_id = ?";

        return $this->getAll($query, array($event_id));
    }

    function getEventComments($event_id)
    {
        $query = "SELECT * FROM event_comment WHERE event_id = ?";
        return $this->getAll($query, array($event_id));
    }

    /**
     * getEventFromId
     *
     * @access public
     * @param int $id
     * @return array
     */
    function getEventFromId($id)
    {
        $id = intval($id);
        $query = "SELECT * FROM event WHERE id = ?"; 
        
        return $this->getRow($query, array($id));
    }

    /**
     * postNews
     *
     * @access public
     */
    function postNews($param)
    {
        unset($param['submit']);
        $param['author'] = $_SESSION['name'];
        
        if (is_numeric($param['id'])) {
            $param['id'] = intval($param['id']);
            $this->db->AutoExecute('news', $param, 'UPDATE', "id = {$param['id']}");
        } else {
            unset($param['id']);
            $this->db->AutoExecute('news', $param, 'INSERT');
        }
    }

    /**
     * deleteEventFromId
     *
     */
    function deleteEventFromId($id)
    {
        $id = intval($id);
        $query = "DELETE FROM event WHERE id = {$id}";
        $query_attendee = "DELETE FROM event_attendee WHERE event_id = {$id}";

        $this->begin();
        $this->query($query);
        $this->query($query_attendee);
        $this->commit();
    }

    /**
     * deleteNewsFromId
     *
     * @access public
     * @param int $id
     */
    function deleteNewsFromId($id)
    {
        $id = intval($id);
        $query = "DELETE FROM news WHERE id = {$id}";

        $this->begin();
        $this->query($query);
        $this->commit();
    }

    /**
     * postEvent
     *
     * @access public
     * @param array $param
     */
    function postEvent($param)
    {
        unset($param['submit']);
        $param['author'] = $_SESSION['name'];
        $param['max_register'] = intval($param['max_register']);

        if (is_numeric($param['id'])) {
            $param['id'] = intval($param['id']);
            $this->db->AutoExecute('event', $param, 'UPDATE', "id = {$param['id']}");
        } else {
            unset($param['id']);
            $this->db->AutoExecute('event', $param, 'INSERT');
        } 
    }

    function getEventComment($id)
    {
        $query = "SELECT * FROM event_comment WHERE id = ?";
        return $this->db->getRow($query, array($id));
        
    }
    
    function deleteCommentFromEvent($comment_id)
    {
        $comment_id = intval($comment_id);
        $query = "DELETE FROM event_comment WHERE id = ?";
        return $this->db->query($query, array($comment_id));
    }

    /**
     * getNewsFromId
     *
     * @access public
     * @param int $id
     */
    function getNewsFromId($id)
    {
        $id = intval($id);
        $query = "SELECT * FROM news WHERE id = ?"; 

        return $this->getRow($query, array($id));
    }

    function postEventPage($param)
    {
        $this->db->AutoExecute('event_page', $param, 'INSERT');
    }

    /**
     * vacuum
     *
     * @todo check db type
     */
    function vacuum()
    {
        $this->query("VACUUM");
    }

    /**
     * getRecentEvent
     *
     * @access public
     * @param int $limit
     * @param bool $is_admin
     * @param array
     */
    function getRecentEvent($limit = 5, $is_admin = false, $offset = 0)
    {
        $limit = intval($limit);
        $offset = intval($offset);
        $date = date("Y-m-d H:i:s");

        if ($is_admin) {

            $query = "SELECT * FROM event";
            $query.= " ORDER BY publish_date DESC";
            $query.= " LIMIT ?";

            $param = array($limit);

        } else {

            $query = "SELECT * FROM event";
            $query.= " WHERE private = 0";
            $query.= " AND publish_date < '{$date}'";
            $query.= " ORDER BY id DESC";
            $query.= " LIMIT ?,?";
            
            $param = array($offset, $limit);

        }

        return $this->getAll($query, $param);
    }

    /**
     * getRecentNews
     *
     * @param int $limit
     * @return array
     */
    function getRecentNews($limit = 5)
    {
        $limit = intval($limit);
        $date = date('Y-m-d H:i:s');

        $query = "SELECT * FROM news";
        $query.= " WHERE duedate <= ?";
        $query.= " ORDER BY date DESC";
        $query.= " LIMIT ?";

        return $this->getAll($query, array($date, $limit));
    }

    function getTrackbackList($limit = 20)
    {
        $limit = intval($limit);

        $query = "SELECT trackback.id as id, event_id, url, title, excerpt, blog_name, receive_time, remote_addr, name";
        $query.= " FROM trackback INNER JOIN event ON trackback.event_id=event.id";
        $query.= " ORDER BY trackback.id DESC LIMIT ?";

        return $this->getAll($query, array($limit));
    }
}
?>
