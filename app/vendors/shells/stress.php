<?php
class StressShell extends Shell 
{
	/**
	 * id 1のイベントに200人の参加者を流し込むシェル
	 *
	 */
	function loadManyData(){
		
		require_once CONFIGS . 'database.php';
        require_once CAKE.'libs/model/model.php';
        require_once CAKE.'libs/model/app_model.php';
        require_once APP.'models/event_attendee.php';
        require_once APP.'models/user.php';
		
        $user = new User();
        
        for ($i = 0;$i<200;$i++) {
        	$data = array(
        		'nickname' => 'nick'.sprintf("%04d",$i),
        	    'username' => 'nick'.sprintf("%04d",$i),
        	);
        	$user->create($data);
        	$user->save();
        }
		$attendee = new EventAttendee();
		
		for ($i = 2;$i<200;$i++){
			$data = array(
				'event_id' => 1,
				'user_id' => $i,
				'party' => ($i%2)
			);
			$attendee->create($data);
			$attendee->save();
		}
	}
}
?>