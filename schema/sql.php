<?php
/**
 * update database script
 *
 * @package Event
 * @author halt feits <halt.feits@gmail.com>
 */
$sql = array();

/**
 * 0.0.1 to 0.0.2
 *
 * rename and add column
 */
$sql['0.0.0'][] = <<<EOD
CREATE TABLE event_page (
    id INTEGER NOT NULL PRIMARY KEY,
    event_id INTEGER,
    author VARCHAR,
    timestamp TIMESTAMP,
    content VARCHAR
);
EOD;

$sql['0.0.0'][] = <<<EOD
UPDATE system SET value = '0.0.1' WHERE column = 'version'
EOD;

/**
 * 0.0.1 to 0.0.2
 *
 * rename and add column
 */
$sql['0.0.1'][0] = <<<EOD
CREATE TEMPORARY TABLE event_alter (
    id INTEGER NOT NULL PRIMARY KEY,
    author VARCHAR,
    name VARCHAR(64),
    max_register INTEGER,
    description VARCHAR,
    private_description VARCHAR,
    map VARCHAR,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    due_date TIMESTAMP,
    publish_date TIMESTAMP,
    private INT DEFAULT '0'
);
EOD;

$sql['0.0.1'][1] = <<<EOD
INSERT INTO event_alter SELECT
    id AS id,
    author AS author,
    name AS name,
    max_register AS max_register,
    description AS description,
    private_description AS private_description,
    map AS map,
    null AS start_date,
    duedate AS end_date,
    null AS due_date,
    date AS publish_date,
    private AS private
    FROM event;
EOD;

$sql['0.0.1'][2] = <<<EOD
DROP TABLE event;
EOD;

$sql['0.0.1'][3] = <<<EOD
CREATE TABLE event (
    id INTEGER NOT NULL PRIMARY KEY,
    author VARCHAR,
    name VARCHAR(64),
    max_register INTEGER,
    description VARCHAR,
    private_description VARCHAR,
    map VARCHAR,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    due_date TIMESTAMP,
    publish_date TIMESTAMP,
    private INT DEFAULT '0'
);
EOD;

$sql['0.0.1'][4] = <<<EOD
INSERT INTO event SELECT * FROM event_alter;
EOD;

$sql['0.0.1'][5] = <<<EOD
DROP TABLE event_alter;
EOD;

//increment version
$sql['0.0.1'][6] = <<<EOD
UPDATE system SET value = "0.0.2" WHERE column = 'version';
EOD;

$sql['0.0.2'][] = <<<EOD
CREATE TABLE trackback (
  id INTEGER  PRIMARY KEY,
  event_id INTEGER ,
  url VARCHAR ,
  title VARCHAR , 
  excerpt VARCHAR , 
  blog_name VARCHAR , 
  receive_time TIMESTAMP ,
  remote_addr VARCHAR 
);
EOD;

//increment version
$sql['0.0.2'][] = <<<EOD
UPDATE system SET value = "0.0.3" WHERE column = 'version';
EOD;

?>
