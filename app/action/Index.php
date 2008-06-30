<?php
/**
 *  Index.php
 *
 *  @author     {$author}
 *  @package    Event
 *  @version    $Id: Index.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  indexフォームの実装
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Event
 */
class Event_Form_Index extends Haste_ActionForm
{
    /**
     *  @access private
     *  @var    array   フォーム値定義
     */
    var $form = array(
        /*
         *  TODO: このアクションが使用するフォーム値定義を記述してください
         *
         *  記述例(typeを除く全ての要素は省略可能)：
         *
         *  'sample' => array(
         *      'name'          => 'サンプル',      // 表示名
         *      'required'      => true,            // 必須オプション(true/false)
         *      'min'           => null,            // 最小値
         *      'max'           => null,            // 最大値
         *      'regexp'        => null,            // 文字種指定(正規表現)
         *      'custom'        => null,            // メソッドによるチェック
         *      'filter'        => null,            // 入力値変換フィルタオプション
         *      'form_type'     => FORM_TYPE_TEXT,  // フォーム型
         *      'type'          => VAR_TYPE_INT,    // 入力値型
         *  ),
         */
        'start' => array(
            'name' => 'page',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_INT,
        ),
    );
}

/**
 *  indexアクションの実装
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Event
 */
class Event_Action_Index extends Ethna_ActionClass
{
    /**
     *  indexアクションの前処理
     *
     *  @access public
     *  @return string      Forward先(正常終了ならnull)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  indexアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $this->db = $this->backend->getDB();

        $this->getPager();
        
        $recent_event = $this->db->getRecentEvent(5, false, $this->offset);


        if (!$recent_event) {
            return 'index';
        }
        //strip html tag from description
        foreach ($recent_event as $key => $value) {
            $recent_event[$key]['description'] = strip_tags(preg_replace("/\(\(\(.+\)\)\)/s", "", $value['description']));
        }

        $this->af->setApp('recent_news', $this->db->getRecentNews());
        $this->af->setApp('recent_event', $recent_event);
        return 'index';
    }

    function getPager() {

        $date = date('Y-m-d H:i:s');
        $sql = "SELECT count(*) FROM event";
        $sql .= " WHERE private = 0 AND publish_date < '{$date}'";

        $this->total = $this->db->db->getOne($sql);
        $this->offset = $this->af->get('start') == null ? 0 : $this->af->get('start');
        $this->count = 5;

        $pager = Ethna_Util::getDirectLinkList($this->total, $this->offset, $this->count);
        $next = $this->offset + $this->count;
        if($next < $this->total){
            $last = ceil($this->total / $this->count);
            $this->af->setApp('hasnext', true);
            $this->af->setApp('next', $next);
            $this->af->setApp('last', ($last * $this->count) - $this->count);
        }
        $prev = $this->offset - $this->count;
        if($this->offset - $this->count >= 0){
            $this->af->setApp('hasprev', true);
            $this->af->setApp('prev', $prev);
        }
        $this->af->setApp('current', $this->offset);
        $this->af->setApp('link', '');
        $this->af->setApp('pager', $pager);
    }
}
?>
