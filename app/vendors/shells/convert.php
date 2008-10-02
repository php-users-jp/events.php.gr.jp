<?php
class ConvertShell extends Shell {
	
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
		
			'DROP TABLE event_attendee_tmp',

			'CREATE TABLE event_attendee_tmp (
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
			'COMMIT',
		);
		
		foreach ($sql_list as $sql) {
			$this->out($sql);
			$ret = $event_attendee->query($sql);
			$this->out(var_export($ret,true));
		}
		$this->out( "done!!");
		
	}
	
}
?>