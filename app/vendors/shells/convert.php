<?php
/**
 * ConvertShell
 *
 * 1バージョンずつ更新していく感じで
 */
class ConvertShell extends Shell
{
    public $uses = array('System');
    var $support_update_version = array(
        '2.1.0' => 'update203to210',
        '2.0.3' => 'update202to203',
    );
    
    /**
     * update203to210
     *
     */
    public function update203to210()
    {
        $sql_list = array(
'BEGIN TRANSACTION',

'CREATE TABLE openids (
 id INTEGER NOT NULL PRIMARY KEY,
 user_id INTEGER NOT NULL,
 username VARCHAR,
 password VARCHAR,
 provider_url VACHAR,
 created TIMESTAMP,
 modified TIMESTAMP
);',

'INSERT INTO openids SELECT
 NULL,
 id,
 username,
 password,
 provider_url,
 created,
 modified
 FROM user',

'CREATE TEMPORARY TABLE user_tmp (
 id INTEGER NOT NULL PRIMARY KEY,
 nickname VARCHAR,
 role VARCHAR,
 created TIMESTAMP,
 modified TIMESTAMP
);',

'INSERT INTO user_tmp SELECT id,nickname,role,created,modified FROM user',

'DROP TABLE user',

'CREATE TABLE user (
 id INTEGER NOT NULL PRIMARY KEY,
 nickname VARCHAR,
 role VARCHAR,
 created TIMESTAMP,
 modified TIMESTAMP
);',

'INSERT INTO user SELECT * FROM user_tmp',

'DROP TABLE user_tmp',

"UPDATE system SET v_value = '2.1.0' WHERE v_column = 'version'",

'COMMIT',
        );

        foreach ($sql_list as $sql) {
            $this->out($sql);
            $ret = $this->System->query($sql);
            if ($ret === false) {
                $this->System->query("ROLLBACK");
                $this->out("ROLLBACK!");
                return -1;
            }
        }

        $this->out( "done!!");
  }

    /*
     * appディレクトリ内で下記のコマンドを実行でスキーマ変更を実施
     * php ../cake/console/cake.php --working `pwd` convert update202to203
     */
    public function update202to203()
    {
        $sql_list = array(
            'BEGIN TRANSACTION',
        
            'CREATE TEMPORARY TABLE event_attendee_tmp (
                id INTEGER NOT NULL PRIMARY KEY,
                event_id INTEGER, 
                user_id INTEGER, 
                comment VARCHAR, 
                party INTEGER DEFAULT "0",
                canceled INTEGER, 
                created TIMESTAMP, 
                modified TIMESTAMP )',

            'INSERT INTO event_attendee_tmp SELECT id,event_id,user_id,comment,0, canceled,created,modified FROM event_attendee',
            'DROP TABLE event_attendee',

            'CREATE TABLE event_attendee (
                id INTEGER NOT NULL PRIMARY KEY,
                event_id INTEGER, 
                user_id INTEGER, 
                comment VARCHAR, 
                party INTEGER DEFAULT "0",
                canceled INTEGER, 
                created TIMESTAMP, 
                modified TIMESTAMP )',
            'INSERT INTO event_attendee SELECT * FROM event_attendee_tmp',

            'CREATE TEMPORARY TABLE system_tmp (
                id INTEGER,
                v_column VARCHAR,
                v_value VARCHAR,
                modified TIMESTAMP )',

            'INSERT INTO system_tmp SELECT ROWID,column,value,edited_at FROM system',
            'DROP TABLE system',

            'CREATE TABLE system (
                id INTEGER NOT NULL PRIMARY KEY,
                v_column VARCHAR,
                v_value VARCHAR,
                modified TIMESTAMP )',

            'INSERT INTO system SELECT * FROM system_tmp',

            'CREATE TEMPORARY TABLE event_page_tmp (
                id       INTEGER,
                event_id INTEGER,
                user_id  INTEGER,
                content  VARCHAR,
                created  TIMESTAMP)',

            'INSERT INTO event_page_tmp SELECT ROWID,event_id,NULL,content,timestamp FROM event_page',
            'DROP TABLE event_page',

            'CREATE TABLE event_page (
                id       INTEGER NOT NULL PRIMARY KEY,
                event_id INTEGER,
                user_id  INTEGER,
                content  VARCHAR,
                created  TIMESTAMP)',

            'INSERT INTO event_page SELECT * FROM event_page_tmp',

            'CREATE TEMPORARY TABLE trackback_tmp (
                id           INTEGER,
                event_id     INTEGER,
                url          VARCHAR,
                title        VARCHAR,
                excerpt      VARCHAR,
                blog_name    VARCHAR,
                receive_time TIMESTAMP,
                remote_addr  VARCHAR)',
            'INSERT INTO trackback_tmp SELECT ROWID,event_id,url,title,excerpt,blog_name,receive_time,remote_addr FROM trackback',
            'DROP TABLE trackback',

            'CREATE TABLE trackback (
                id           INTEGER NOT NULL PRIMARY KEY,
                event_id     INTEGER,
                url          VARCHAR,
                title        VARCHAR,
                excerpt      VARCHAR,
                blog_name    VARCHAR,
                receive_time TIMESTAMP,
                remote_addr  VARCHAR)',

            'INSERT INTO trackback SELECT * FROM trackback_tmp',

            "UPDATE system SET v_value = '2.0.3' WHERE v_column = 'version'",

            'COMMIT',
        );
        
        foreach ($sql_list as $sql) {
            $this->out($sql);
            $ret = $this->System->query($sql);
            if ($ret === false) {
                $this->System->query("ROLLBACK");
                $this->out("ROLLBACK!");
                return -1;
            }
        }

        $this->out( "done!!");
        
    }

    public function version()
    {
        $version = $this->System->getVersion();

        if ($version === false) {
            $this->out("error: can't find version number");
        } else {
            $this->out($version);
        }
    }

    public function update()
    {
        $version = $this->System->getVersion();

        $max_version = max(array_keys($this->support_update_version));

        if ($this->cast2Int($version) < $this->cast2Int($max_version)) {
            $this->out('更新があります');
        } else {
            $this->out('すでに最新版です');
        }
    }

    private function cast2Int($version_number)
    {
        $number = 0;
        $base_number = array(
            10000,
            100,
            1,
        );

        $parts = explode('.', $version_number);

        foreach($parts as $key => $value) {
            $value = (int)$value; 
            $value*= $base_number[$key];
            $number += $value;
        }

        return $number;
    }
    
}
?>
