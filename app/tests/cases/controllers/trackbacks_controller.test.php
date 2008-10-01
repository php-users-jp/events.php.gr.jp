<?php
/**
 * trackbacks_controllerのユニットテスト
 * 
 * @author Yusuke Ando <ando@rikezemi.com>
 * 
 */
//本当はApp::importすべきだろうけれど様子見
require_once APP.'controllers/app_controller.php';
require_once APP.'controllers/trackbacks_controller.php';


class TrackbacksControllerTest extends TrackbacksController {
	var $name = 'TrackbacksControllerTest';
	
	function validateTrackback($url,$excerpt){
		return parent::validateTrackback($url,$excerpt);
	}
}

class TrackbacksControllerTestCase extends CakeTestCase {

	/**
	 * 内部で利用するグローバル変数を退避しておく
	 *
	 */
	function startCase(){
		$this->org_server = $_SERVER;	
	}
	/**
	 * 内部で利用しているグローバル変数を元に戻す
	 *
	 */
	function endCase(){
		$_SERVER = $this->org_server;
	}
	
	/**
	 * ほぼ呼ぶだけのテスト
	 *
	 */
	function test_validateTrackback() {
		$this->TrackbackControllerTest = new TrackbacksControllerTest();

		$url= "http://{$_SERVER['HTTP_HOST']}/";
		$excerpt = $_SERVER['HTTP_HOST'];
		$result = $this->TrackbackControllerTest->validateTrackback($url,$excerpt);
		$this->assertTrue($result);
	}

	/**
	 * リンク形式の文字列から呼ぶ
	 *
	 */
	function test_validateTrackbackNormal() {
		$this->TrackbackControllerTest = new TrackbacksControllerTest();

		$url= "http://{$_SERVER['HTTP_HOST']}/";
		$excerpt = '<a href="http://'.$_SERVER['HTTP_HOST'].'/">hoge</a>';
		$result = $this->TrackbackControllerTest->validateTrackback($url,$excerpt);
		$this->assertTrue($result);
	}
	
	/**
	 * sotarokのはてダを取得して検査してみるテスト
	 *
	 */
	function test_validateTrackbackPage() {
		$this->TrackbackControllerTest = new TrackbacksControllerTest();

		//内部で参照しているグローバルを書き換える
		$_SERVER['HTTP_HOST'] = "events.php.gr.jp";
		$url= 'http://d.hatena.ne.jp/sotarok/20080930/php_study_36';
		$excerpt = implode('',file('http://d.hatena.ne.jp/sotarok/20080930/php_study_36'));
		$result = $this->TrackbackControllerTest->validateTrackback($url,$excerpt);
		$this->assertTrue($result);	
	}
}
?>