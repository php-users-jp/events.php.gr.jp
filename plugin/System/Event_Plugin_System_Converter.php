<?php
/**
 *  Event_Plugin_System_Converter.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Anubis
 *  @version    $Id: Event_Plugin_System_Converter.php 402 2006-07-03 05:52:09Z halt $
 */

/**
 *  Event_Plugin_System_Converter
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Anubis
 */
class Event_Plugin_System_Converter
{
    function Event_Plugin_System_Converter()
    {
        $this->ctl =& Ethna_Controller::getInstance();
        $this->backend =& $this->ctl->getBackend();
        $this->config =& $this->ctl->getConfig();
        $this->logger =& $this->ctl->getLogger();
    }

    /**
     * getDBVersion
     *
     * @param void
     * @return string
     */
    function getDBVersion()
    {
        $DB = $this->backend->getDB();
        $query = "SELECT value FROM system WHERE column = ?";
        $result = $DB->db->getOne($query, array('version'));
        
        if ($result === false) {

            $query = "SELECT * FROM referer LIMIT 1";
            if (Ethna::isError($DB->query($query))) {
                $result = "0.0.1";
            } else {
                $result = "0.0.2";
            }

        }

        return $result;
    }

    /**
     * getLatestDBVersion
     *
     * @return string
     */
    function getLatestDBVersion()
    {
        $schema_path = BASE . '/schema/sql.php';
        
        if (!file_exists($schema_path)) {
            return false;
        }
            
        //get $sql
        include $schema_path;

        return max(array_keys($sql));

    }

    /**
     * updateDB
     *
     * @param string $version
     * @return bool
     */
    function updateDB($version)
    {
        $schema_path = BASE . '/schema/sql.php';
        $DB = $this->backend->getDB();

        if (!file_exists($schema_path)) {
            $this->logger->log(LOG_WARNING, "schema file not found [{$schema_path}]");
            return false;
        }
            
        //get $sql
        include $schema_path;

        if (!isset($sql[$version])) {
            $this->logger->log(LOG_WARNING, "schema data not found for {$version}");
            return false;
        }

        $DB->begin();

        if (is_array($sql[$version])) {

            foreach ($sql[$version] as $single_query) {

                $result = $DB->query($single_query);

                if (Ethna::isError($result)) {
                    $DB->rollback();
                    return $result->getMessage();
                }

            }
                
        } else {

            $result = $DB->query($sql[$version]);

            if (Ethna::isError($result)) {
                $DB->rollback();
                return $result->getMessage();
            }
        }

        $DB->commit();

        return true;

    }
}
?>
