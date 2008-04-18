<?php
/**
 *  {$view_path}
 *
 *  @author     {$author}
 *  @package    Event
 *  @version    $Id: skel.view_test.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *  {$forward_name}ビューの実装
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Event
 */
class {$view_class}_TestCase extends Ethna_UnitTestCase
{
    /**
     *  @access private
     *  @var    string  ビュー名
     */
    var $forward_name = '{$forward_name}';

    /**
     *    テストの初期化
     *
     *    @access public
     */
    function setUp()
    {
        $this->createPlainActionForm(); // アクションフォームの作成
        $this->createViewClass();       // ビューの作成
    }

    /**
     *    テストの後始末
     *
     *    @access public
     */
    function tearDown()
    {
    }

    /**
     *  {$forward_name}遷移前処理のサンプルテストケース
     *
     *  @access public
     */
    /*
    function test_viewSample()
    {
        // フォームの設定
        $this->af->set('id', 1);

        // {$forward_name}遷移前処理
        $this->vc->preforward();
        $this->assertNull($this->af->get('data'));
    }
    */
}
?>
