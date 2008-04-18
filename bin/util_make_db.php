<?php
/**
 * make empty database script
 *
 * @author halt feits <halt.feits@gmail.com>
 * @version $Id: util_make_db.php 97 2006-07-09 17:27:26Z halt $
 */

$filename = "empty.db";
$sql_file = "structure.sql";
$sql = file($sql_file);

$handle = sqlite_open($filename);

foreach ($sql as $line) {
    sqlite_query($handle, $line);
}

sqlite_query($handle, "VACUUM");
?>
