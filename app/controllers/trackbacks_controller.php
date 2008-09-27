<?php
/**
 *
 *
 */

class TrackbacksController extends AppController
{
    var $name = 'Trackback';

    /**
     * delete
     *
     */
    public function delete($id)
    {
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }

        $trackback = $this->Trackback->findById($id);
        if ($trackback) {
            $this->Trackback->del($id);
            $this->redirect('/events/show/'.$trackback['Trackback']['event_id']);
        }

        $this->redirect('/');
    }

    /**
     * receive
     *
     */
    public function receive($event_id) {

        require_once APP . 'Services/Trackback.php';
        $trackback = Services_Trackback::create(array('id' => $event_id));
        $result = $trackback->receive();

        if (!PEAR::isError($result)) {
            echo $trackback->getResponseSuccess();
            
            $row = array(
                'id'           => NULL,
                'event_id'   => $event_id,
                'url'          => $trackback->get('url'),
                'title'        => $trackback->get('title'),
                'excerpt'      => $trackback->get('excerpt'),
                'blog_name'    => $trackback->get('blog_name'),
                'receive_time' => date('Y-m-d H:i:s'),
                'remote_addr'  => $_SERVER['REMOTE_ADDR']
            );
        
        } else {
            echo $trackback->getResponseError(1, "Test error");
            exit;
        }

        if ($this->validateTrackback($row['url'], $row['excerpt'])) {
            foreach ($row as $key => $value) {
                $row[$key] = mb_convert_encoding(
                    $value,
                    'UTF-8',
                    mb_detect_encoding($row['excerpt']
                ));
            }
            $this->Trackback->save(array('Trackback' => $row));
        } else {
            $this->storeTrackback($row);
        }

        exit;

    }

    /**
     * validateTrackback
     *
     * @access protected
     */
    protected function validateTrackback($url, $excerpt = "")
    {
        $my_host = $_SERVER['HTTP_HOST'];
        
        if (strpos($excerpt, $my_host) !== false) {
            return true;
        }

        $value = $this->fetchUrl($url);

        if ($value == false) {
            //$this->logger->log(LOG_NOTICE, 'validateTrackback: body not found');
            return false;
        } else {
            if (strpos($value, $my_host) !== false) {
                return true;
            } else {
                //$this->logger->log(LOG_NOTICE, 'validateTrackback: my host not found');
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
    protected function storeTrackback($row)
    {
        // @TODO ブロックしたトラックバックをlogディレクトリにおく
    }

    /**
     * fetchUrl
     *
     * @access protected
     * @param string $url
     * @return string
     */
    protected function fetchUrl($url)
    {
        $php_version = PHP_VERSION;

        if ($php_version{0} == "5") {
            $header = get_headers($url);
            if (is_array($header) && (strpos($header[0], '404') === false)) {
                return false;
            } else {
                return file_get_contents($url);
            }
        } else {
            return file_get_contents($url);
        }
    }
}

?>
