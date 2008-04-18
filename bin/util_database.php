<?php
/**
 * sqlite database utilities
 *
 * @author halt feits <halt.feits@gmail.com>
 *
 * Example:
 * php util_database getVersion //show database version
 * php util_database vacuum     //vacuum database
 *
 * @todo parse dsn in project-ini.php
 */

$filename = '/home/halt/dbs/event.db';

if (!file_exists($filename)) {
    exit("ERROR:database file not found\n");
}

//load $sql
require_once dirname(dirname(__FILE__)) . '/schema/sql.php';

$arg = array();
$arg[1] = $_SERVER['argv'][1];
isset($_SERVER['argv'][2]) ? $arg[2] = $_SERVER['argv'][2] : $arg[2] = "";

switch ($arg[1]) {

case 'update':
    update($filename, $sql);
    break;

case 'query':
    $query = $_SERVER['argv'][2];
    $sqlite = new SQLiteManager($filename);
    var_dump($sqlite->query($query));
    break;

case 'vacuum':
    $sqlite = new SQLiteManager($filename);
    var_dump($sqlite->query("VACUUM"));
    break;

default:
    if ($_SERVER['argc'] >= 2) {
        $sqlite = new SQLiteManager($filename);
        print_r(array($sqlite->$arg[1]($arg[2])));
    }
}

function update($filename, $update_sql)
{
    $sqlite = new SQLiteManager($filename);
    $version = $sqlite->getVersion();
    $latest_version = max(array_keys($update_sql));

    if ($version <= $latest_version) {
        $sql = $update_sql[$version];
        foreach ($sql as $query) {
            $sqlite->query($query);
        }
    } else {
        print("database is latest\n");
    }
}

/**
 * SQLiteManager
 *
 */
class SQLiteManager
{
    /**
     * database filename
     * @var     string
     * @access  protected
     */
    var $filename;

    function SQLiteManager($filename)
    {
        $this->filename = $filename;
    }

    function show_tables()
    {
        $query = "SELECT tbl_name FROM sqlite_master";
        $handle = sqlite_open($this->filename);
        $result = sqlite_array_query($handle, $query, SQLITE_ASSOC);

        foreach($result as $value) {
            $table_list[] = $value['tbl_name'];
        }

        return $table_list;
    }

    /**
     * table_info
     *
     * @param string $table
     */
    function table_info($table)
    {
        $query = "PRAGMA table_info('{$table}');";
        var_dump($query);

        $handle = sqlite_open($this->filename);
        return sqlite_array_query($handle, $query, SQLITE_ASSOC);
    }

    function databae_info()
    {
        $query = "PRAGMA database_list;";

        $handle = sqlite_open($this->filename);
        return sqlite_array_query($handle, $query, SQLITE_ASSOC);
    }

    function query($query)
    {
        $handle = sqlite_open($this->filename);
        return sqlite_array_query($handle, $query, SQLITE_ASSOC);
    }

    function getVersion()
    {
        $query = "SELECT count(value) AS value FROM system WHERE column = 'version'"; 
        $result = $this->query($query);

        if ($result[0]['value'] == 0) {
            $query = "INSERT INTO system (column, value) VALUES ('version', '0.0.0');";
            $this->query($query);
        }

        $query = "SELECT value FROM system WHERE column = 'version'";
        $result = $this->query($query);
        return $result[0]['value'];
    }

}
?>
