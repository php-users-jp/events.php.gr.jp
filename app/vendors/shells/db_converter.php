<?php
/**
 *
 *
 */

class Converter
{
    protected $db;

    function __construct()
    {
        $this->db = new PDO('sqlite2:event.test');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function createTable()
    {
        $queries = array();
        $queries[] = <<<EOD
BEGIN TRANSACTION;
EOD;
        $queries[] = <<<EOD
CREATE TEMPORARY TABLE event_attendee_tmp (
 id INTEGER NOT NULL PRIMARY KEY,
 event_id INTEGER,
 user_id  INTEGER,
 comment VARCHAR,
 canceled INTEGER,
 created  TIMESTAMP,
 modified TIMESTAMP
);
EOD;
        $queries[] = <<<EOD
INSERT INTO event_attendee_tmp
  SELECT
    event_attendee.id AS id, event_id, user.id AS user_id, comment, canceled, register_at AS created, register_at AS modified
    FROM event_attendee
      LEFT JOIN user ON event_attendee.account_name = user.username;
EOD;
        $queries[] = <<<EOD
DROP TABLE event_attendee;
EOD;
        $queries[] = <<<EOD
CREATE TABLE event_attendee (
 id INTEGER NOT NULL PRIMARY KEY,
 event_id INTEGER,
 user_id  INTEGER,
 comment  VARCHAR,
 canceled INTEGER,
 created  TIMESTAMP,
 modified TIMESTAMP
);
EOD;
        $queries[] = <<<EOD
INSERT INTO event_attendee SELECT * FROM event_attendee_tmp;
EOD;

        $queries[] = <<<EOD
CREATE TEMPORARY TABLE event_comment_tmp (
 id INTEGER NOT NULL PRIMARY KEY,
 event_id INTEGER,
 user_id  INTEGER,
 comment VARCHAR,
 created  TIMESTAMP,
 ip VARCHAR,
 ua VARCHAR
);
EOD;
        $queries[] = <<<EOD
INSERT INTO event_comment_tmp
  SELECT event_comment.id AS id, event_id, user.id AS user_id, comment, NULL AS created, ip, ua
    FROM event_comment
      LEFT JOIN user ON event_comment.name = user.username;
EOD;
        $queries[] = <<<EOD
DROP TABLE event_comment;
EOD;
        $queries[] = <<<EOD
CREATE TABLE event_comment (
 id INTEGER NOT NULL PRIMARY KEY,
 event_id INTEGER,
 user_id INTEGER,
 comment VARCHAR,
 created TIMESTAMP,
 ip VARCHAR,
 ua VARCHAR
);
EOD;
        $queries[] = <<<EOD
INSERT INTO event_comment SELECT * FROM event_comment_tmp;
EOD;
        $queries[] = <<<EOD
COMMIT;
EOD;

        try {
        foreach ($queries as $query) {
            $this->db->exec($query);
        }
        } catch (Exception $e) {
            $this->db->exec('ROLLBACK;');
            var_dump($e->getMessage());
        }

    }

    /**
     * user情報の移動
     *
     */
    function convertUserAccount()
    {
        $query = <<<EOD
CREATE TABLE user(
 id INTEGER NOT NULL PRIMARY KEY,
 nickname VARCHAR,
 username VARCHAR,
 password VARCHAR,
 provider_url VACHAR,
 role VARCHAR,
 created TIMESTAMP,
 modified TIMESTAMP
);
EOD;
        $this->db->exec($query);

        $query = <<<EOD
SELECT * FROM event_attendee GROUP BY account_name;
EOD;
        $insert_sql = <<<EOD
INSERT INTO user (
  nickname,
  username,
  password,
  provider_url,
  role,
  created,
  modified
) VALUES (
  :account_nick,
  :account_name,
  :password,
  :provider_url,
  :role,
  :created,
  :modified
);
EOD;

        try {
            $stm = $this->db->query($query);
            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

            $prepared = $this->db->prepare($insert_sql);
            $this->db->exec("BEGIN TRANSACTION;");
            foreach ($rows as $row) {
                $exists = $this->existsUser($row['account_name'], 'http://profile.typekey.com/');
                if ($exists) {
                    continue;
                }

                $row['created'] = date('Y-m-d H:i:s');
                $row['modified'] = $row['created'];
                $row['role'] = 'user';
                $row['provider_url'] = 'http://profile.typekey.com/';
                $row['password'] = "{$row['account_nick']}:{$row['account_name']}";

                foreach (array('account_nick','account_name','password','provider_url','role','created','modified') as $key) {
                    $prepared->bindValue(':'.$key, $row[$key]);
                }

                $prepared->execute();
            }
            $this->db->exec("COMMIT;");
        } catch (Exception $e) {
            $this->db->exec("ROLLBACK;");
            var_dump($e->getMessage());
        }

        $query = <<<EOD
SELECT * FROM event_comment GROUP BY name;
EOD;

        try {
            $stm = $this->db->query($query);
            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

            $insert_sql = <<<EOD
INSERT INTO user (
  nickname,
  username,
  password,
  provider_url,
  role,
  created,
  modified
) VALUES (
  :nick,
  :name,
  :password,
  :provider_url,
  :role,
  :now,
  :now2
);
EOD;
            $prepared = $this->db->prepare($insert_sql);
            $this->db->exec("BEGIN TRANSACTION;");
            foreach ($rows as $row) {
                $exists = $this->existsUser($row['name'], 'http://profile.typekey.com/');
                if ($exists) {
                    continue;
                }

                $row['now'] = date('Y-m-d H:i:s');
                $row['now2'] = $row['now'];
                $row['role'] = 'user';
                $row['provider_url'] = 'http://profile.typekey.com/';
                $row['password'] = "{$row['nick']}:{$row['name']}";

                foreach (array('nick','name','password','provider_url','role','now','now2') as $key) {
                    $prepared->bindValue(':'.$key, $row[$key]);
                }

                $prepared->execute();
            }
            $this->db->exec("COMMIT;");
        } catch (Exception $e) {
            $this->db->exec("ROLLBACK;");
            var_dump($e->getMessage());
        }

    }

    /**
     * existsUser
     *
     */
    function existsUser($name, $provider_url)
    {
        $query = <<<EOD
SELECT count(*) as count FROM user
  WHERE username = '{$name}' AND provider_url = '{$provider_url}'
EOD;
        $stm = $this->db->query($query);
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] == 0) {
            return false;
        } else {
            return true;
        }
    }
}

$converter = new Converter();
$converter->convertUserAccount();
$converter->createTable();
?>
