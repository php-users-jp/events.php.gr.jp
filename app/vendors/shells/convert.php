<?php
/**
 * ConvertShell
 *
 */
class ConvertShell extends Shell {

    var $support_update_version = array(
        '2.0.3' => 'update202to203',
    );
    
    /*
     * appディレクトリ内で下記のコマンドを実行でスキーマ変更を実施
     * php ../cake/console/cake.php --working `pwd` convert update202to203
     */
    function update202to203(){

        require_once CONFIGS . 'database.php';
        require_once CAKE.'libs/model/model.php';
        require_once CAKE.'libs/model/app_model.php';
        require_once APP.'models/event_attendee.php';
        
        
        $event_attendee = new EventAttendee();
        
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
            $ret = $event_attendee->query($sql);
            if ($ret === false) {
                $event_attendee->query("ROLLBACK");
                $this->out("ROLLBACK!");
                return -1;
            }
        }

        $this->out( "done!!");
        
    }

    function version()
    {
        $version = $this->getVersion();

        if ($version === false) {
            $this->out("error: can't find version number");
        } else {
            $this->out($version);
        }
    }

    private function getVersion()
    {
        require_once CONFIGS . 'database.php';
        require_once CAKE.'libs/model/model.php';
        require_once CAKE.'libs/model/app_model.php';
        require_once APP.'models/system.php';
        
        $system = new System();

        $re = $system->findByVColumn('version');

        if ($re === false) {
            return false;
        } else {
            return $re['System']['v_value'];
        }
    }

    function update()
    {
        $version = $this->getVersion();

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
