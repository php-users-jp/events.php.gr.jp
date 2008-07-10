<?php
/**
 *	Rss/Trackback.php
 *
 *	@author		{$author}
 *	@package	Event
 *	@version	$Id: skel.action.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *	rss_trackbackフォームの実装
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class Event_Form_RssTrackback extends Ethna_ActionForm
{
	/**
	 *	@access	private
	 *	@var	array	フォーム値定義
	 */
	var	$form = array(
		/*
		'sample' => array(
			'name'			=> 'サンプル',		// 表示名
			'required'      => true,			// 必須オプション(true/false)
			'min'           => null,			// 最小値
			'max'           => null,			// 最大値
			'regexp'        => null,			// 文字種指定(正規表現)
			'custom'        => null,			// メソッドによるチェック
			'filter'        => null,			// 入力値変換フィルタオプション
			'form_type'     => FORM_TYPE_TEXT,	// フォーム型
			'type'          => VAR_TYPE_INT,	// 入力値型
		),
		*/
	);
}

/**
 *	rss_trackbackアクションの実装
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class Event_Action_RssTrackback extends Ethna_ActionClass
{
	/**
	 *	rss_trackbackアクションの前処理
	 *
	 *	@access	public
	 *	@return	string		遷移名(正常終了ならnull, 処理終了ならfalse)
	 */
	function prepare()
	{
		return null;
	}

	/**
	 *	rss_trackbackアクションの実装
	 *
	 *	@access	public
	 *	@return	string	遷移名
	 */
	function perform()
	{
        $this->db = $this->backend->getDB();
        $recent = $this->db->getTrackbackList(20);

        foreach ($recent as $key => $value) {
            $recent[$key]['pubDate'] = date('r', strtotime($value['receive_time']));
            $recent[$key]['title'] = $value['title'] . ' - ' . $value['blog_name'];
            $recent[$key]['receive_time'] = date('[Y-m-d]', strtotime($value['receive_time']));
            $recent[$key]['url'] = $value['url'];
        }

        $this->af->setApp('recent', $recent);
        $this->af->setApp('title', $this->config->get('site_name'));

        header("Content-type: text/xml;charset=UTF-8");
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", strtotime($recent[0]['publish_date']) ) . " GMT" );

        return 'rss_trackback';
	}
}
?>
