<?php
/**
 *  Event_UserManager.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Event_UserManager.php 73 2006-06-14 05:56:49Z halt $
 */

/**
 *  Event_UserManager
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_UserManager extends Ethna_AppManager
{
    /**
     * Database
     * @var     Object
     * @access  protected
     */
    var $adodb;

    /**
     * Event_UserManager
     *
     */
    function Event_UserManager($backend)
    {
        $this->adodb = $backend->getDB();

        parent::Ethna_AppManager($backend);
    }

    //{{{ existsAdmin
    /**
     * existsAdmin
     *
     *
     */
    function existsAdmin()
    {
        $query = "SELECT * FROM system WHERE value = 'admin'";
        $row = $this->adodb->getAll($query);

        if (count($row) > 0) {
            return true;
        } else {
            return false;
        }
    }
    //}}}

    /**
     * isAdmin
     *
     */
    function isAdmin($username)
    {
        if ($this->config->get('typekey_admin') == $username) {
            return true;
        }

        $query = "SELECT * FROM system WHERE column = ?";
        $row = $this->adodb->getAll($query, array($username));

        if (count($row) == 1) {
            return true;
        } else {
            return false;
        }

    }
    
    /**
     * 
     *
     */ 
    function  isJoined($username, $event_id)
    {
        $query = "SELECT * FROM event_attendee WHERE account_name = ? AND event_id = ?";
        $row = $this->adodb->getAll($query, array($username, $event_id));

        if (count($row) == 1) {
            return true;
        }
        else {
            return false;
        }
        
    }

    /**
     * getAdminList
     *
     */
    function getAdminList()
    {
        $query = "SELECT * FROM system WHERE value = 'admin'";
        return $this->adodb->db->getAll($query);
    }

    /**
     * deleteAdmin
     *
     */
    function deleteAdmin($username)
    {
        $query = "DELETE FROM system WHERE column = ?";
        return $this->adodb->query($query, array($username));
    }

    /**
     * setAdmin
     *
     * @access public
     * @param string $username
     */
    function setAdmin($username)
    {
        $this->deleteAdmin($username);

        $record['edited_at'] = date('Y-m-d H:i:s');
        $record['value'] = 'admin';
        $record['column'] = $username;

        return $this->adodb->autoExecute('system', $record, 'INSERT');
    }
}
?>
