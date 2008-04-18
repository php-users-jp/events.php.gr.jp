<?php
/**
 *	Event_Filter_ExecutionTime.php
 *
 *	@author		{$author}
 *	@package	Event
 *	@version	$Id: Event_Filter_ExecutionTime.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *	実行時間計測フィルタの実装
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class Event_Filter_ExecutionTime extends Ethna_Filter
{
	/**#@+
	 *	@access	private
	 */

	/**
	 *	@var	int		開始時間
	 */
	var	$stime;

	/**#@-*/


	/**
	 *	実行前フィルタ
	 *
	 *	@access	public
	 */
	function preFilter()
	{
		$stime = explode(' ', microtime());
		$stime = $stime[1] + $stime[0];
		$this->stime = $stime;
	}

	/**
	 *	実行後フィルタ
	 *
	 *	@access	public
	 */
	function postFilter()
	{
		$etime = explode(' ', microtime());
		$etime = $etime[1] + $etime[0];
		$time   = round(($etime - $this->stime), 4);

		print "\n<!-- page was processed in $time seconds -->\n";
	}
}
?>
