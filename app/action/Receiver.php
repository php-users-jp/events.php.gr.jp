<?php
/**
 *  Receiver.php
 *
 *  トラックバックを受け取るコードです．
 *  http://example.com/event/index.php/receiver/5
 *  のようなTrackbackURLにトラックバックを送信する事で
 *  検証し，登録します．
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  receiverフォームの実装
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_Receiver extends Haste_ActionForm
{
    /**
     *  @access private
     *  @var    array   フォーム値定義
     */
    var $form = array(
        /*
        'sample' => array(
            'name'          => 'サンプル',      // 表示名
            'required'      => true,            // 必須オプション(true/false)
            'min'           => null,            // 最小値
            'max'           => null,            // 最大値
            'regexp'        => null,            // 文字種指定(正規表現)
            'custom'        => null,            // メソッドによるチェック
            'filter'        => null,            // 入力値変換フィルタオプション
            'form_type'     => FORM_TYPE_TEXT   // フォーム型
            'type'          => VAR_TYPE_INT,    // 入力値型
        ),
        */
    );
}

require_once 'Services/Trackback.php';

/**
 *  receiverアクションの実装
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_Receiver extends Ethna_ActionClass
{
    var $logger;

    /**
     *  receiverアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->logger =& $this->backend->getLogger();
        return null;
    }

    /**
     *  receiverアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $db = $this->backend->getDB();

        $get_query = explode('/', $_SERVER['PATH_INFO']);
        $tb_id = $get_query[2];

        $trackback = Services_Trackback::create(array('id' => $tb_id));
        $result = $trackback->receive();

        if ( !PEAR::isError($result) ) {
            echo $trackback->getResponseSuccess();
            
            $row = array(
                'event_id'   => $tb_id,
                'url'          => $trackback->get('url'),
                'title'        => $trackback->get('title'),
                'excerpt'      => $trackback->get('excerpt'),
                'blog_name'    => $trackback->get('blog_name'),
                'receive_time' => date('Y-m-d H:i:s'),
                'remote_addr'  => $_SERVER['REMOTE_ADDR']
            );
        
        } else {
            echo $trackback->getResponseError(1, "Test error");
            return null;
        }

        if ($this->validateTrackback($row['url'], $row['excerpt'])) {
            foreach ($row as $key => $value) {
                $row[$key] = mb_convert_encoding(
                    $value,
                    'UTF-8',
                    mb_detect_encoding($row['excerpt']
                ));
            }
            $db->autoExecute('trackback', $row, 'INSERT');
        } else {
            $this->storeTrackback($row);
        }

        return null;
    }

    /**
     * validateTrackback
     *
     * @access protected
     */
    function validateTrackback($url, $excerpt = "")
    {
        $my_url = parse_url($this->config->get('base_url'));
        $my_host = $my_url['host'];
        
        if (strpos($excerpt, $my_host) !== false) {
            return true;
        }

        $value = $this->fetchUrl($url);

        if ($value == false) {
            $this->logger->log(LOG_NOTICE, 'validateTrackback: body not found');
            return false;
        } else {
            if (strpos($value, $my_host) !== false) {
                return true;
            } else {
                $this->logger->log(LOG_NOTICE, 'validateTrackback: my host not found');
                return false;
            }
        }
    }

    /**
     * storeTrackback
     *
     * @access protected
     * @param array $row
     * @return bool
     */
    function storeTrackback($row)
    {
        $ctl = $this->backend->getController();
        $log_path = $ctl->getDirectory('log') . '/trackback.log';

        if (!file_exists($log_path)) {
            $this->logger->log(LOG_NOTICE, "{$log_path} not found");
            if (is_writable(dirname($log_path))) {
                touch($log_path);
            } else {
                $this->logger->log(LOG_WARNING, "log directory can't touch");
                return false;
            }
        }
        
        if (!is_writable($log_path)) {
            $this->logger->log(LOG_WARNING, "{$log_path} permission denied");
            return false;
        }

        $log_data = file_get_contents($log_path);
        $data = "";
        foreach ($row as $key => $value) {
            $data.= "{$key} : {$value}\n";
        }
        $data .= "\n";
        file_put_contents($log_path, $log_data . $data);

        return true;
    }

    /**
     * fetchUrl
     *
     * @access protected
     * @param string $url
     * @return string
     */
    function fetchUrl($url)
    {
        $php_version = PHP_VERSION;

        if (file_exists_ex('Zend/Http/Client.php') && $php_version{0} == "5") {
            require_once 'Zend/Http/Client.php';
            $client = new Zend_Http_Client($url);
            $response = $client->request();
            return $response->getBody();
        } else {
            return file_get_contents($url);
        }
    }
}
?>
