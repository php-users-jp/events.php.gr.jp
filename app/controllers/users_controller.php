<?php
/**
 * users_controller.php
 *
 */

/**
 * UsersController
 *
 */
class UsersController extends AppController
{
    var $name = 'Users';
    var $helpers = array('Html', 'Form');
    var $components = array('Auth', 'Openid');

    function beforeFilter()
    {
        //$this->Auth->userScope = array('User.flag' => 0);
        $this->Auth->loginAction = '/users/userlogin';
        $this->Auth->logoutAction = '/users/userlogout';
        $this->Auth->allow(
            'add',
            'login',
            'userlogout',
            'openid_add',
            'index',
            'view'
        );
    }

    function beforerender()
    {
        $user = $this->Auth->user();
        $this->set('auth', $user['User']['nickname']);
    }

    protected function getRequest()
    {
        $returnTo = 'http://'.$_SERVER['SERVER_NAME'].$this->here;
        $provider_list = array(
            'http://profile.typekey.com/',
            'http://www.hatena.ne.jp/',
            'http://mixi.jp/',
        );

        if (!empty($this->data)) {
            try {
                if (!in_array($this->data['OpenidUrl']['provider_url'], $provider_list)) {
                    throw new Exception('不正なプロバイダURLです');
                }
                $login_url = $this->data['OpenidUrl']['provider_url'];
                if (!empty($this->data['OpenidUrl']['username'])) {
                    $login_url .= $this->data['OpenidUrl']['username'] . '/';
                }
                $this->Openid->authenticate(
                    $login_url,
                    $returnTo,
                    'http://'.$_SERVER['SERVER_NAME']
                );
            } catch (InvalidArgumentException $e) {
                $this->setMessage('Invalid OpenID');
            } catch (Exception $e) {
                $this->setMessage($e->getMessage());
            }
        } elseif (count($_GET) > 1) {
            $response = $this->Openid->getResponse($returnTo);

            if ($response->status == Auth_OpenID_CANCEL) {
                $this->setMessage('Verification cancelled');
            } elseif ($response->status == Auth_OpenID_FAILURE) {
                $this->setMessage('OpenID verification failed: '.$response->message);
            } elseif ($response->status == Auth_OpenID_SUCCESS) {
                $this->setMessage('successfully authenticated!');
                return $response;
            }
        }

        return false;
    }

    function login()
    {
        $response = $this->getRequest();
        if ($response) {
            $username = end(explode('/', trim($response->identity_url, '/')));
            $provider_url = dirname(trim($response->identity_url, '/')) . '/';

            $user = $this->User->find('first',array(
                'conditions'    => array(
                    'User.username' => $username,
                    'User.provider_url' => $provider_url,
                ),
                'recursive' => -1
            ));

            if (!empty($user)) {
                //登録されているOpenIDユーザなら
                $data = array(
                    'User.username' => $user['User']['username'],
                    'User.password' => $user['User']['password'],
                    'User.provider_url' => $user['User']['provider_url']
                );
                foreach ($user['User'] as $key => $value) {
                    $this->Session->write($key, $value);
                }
                $this->Session->write('identity_url', $response->identity_url);
                $this->__autologinForOpenid($data);
            } else {
                //登録されているOpenIDユーザならニックネーム登録
                $this->Session->write('username', $username);
                $this->Session->write('provider_url', $provider_url);
                $this->Session->write('identity_url', $response->identity_url);
                $this->redirect('/users/openid_add');
            }
        }
    }

    /**
     * openid_add
     *
     */
    function openid_add()
    {
        if (!empty($this->data)) {

            if ($this->Session->check('identity_url')) {
                $identity_url = $this->Session->read('identity_url');
                $username = $this->Session->read('username');
                $provider_url = $this->Session->read('provider_url');
            } else {
                exit;
            }

            $data = $this->data;
            $this->User->create();
            $savedata = array(
                'username' => $username,
                'provider_url' => $provider_url,
                'password' => $this->Auth->password(time()),
                'nickname' => $data['User']['nickname'],
                'role' => 'user',
            );
            $this->User->set($savedata);
            $validates = $this->User->validates();
            if ($this->User->validates()) {
                $this->User->save();
                $this->Session->delete('identity_url');
                $this->Session->setFlash('ニックネームの登録が終了しました。');

                $data = array(
                    'User.username' => $savedata['username'],
                    'User.password' => $savedata['password']
                );
                $this->__autologinForOpenid($data);
            }
        }
    }

    function userlogin()
    {

    }    

    function userlogout()
    {
        $this->Session->setFlash('ログアウトしました');
        $this->Auth->logout();
        $this->Session->destroy();
        $this->redirect('/events/index');
    }

    function __autologinForOpenid($user){
        //$this->Auth->userScope = array('User.flag' => 1);
        $this->Auth->login($user);
        $this->redirect('/events/index');
    }

    /**
     * control
     *
     */
    function control()
    {
        // adminじゃなければさようなら
        if ($this->Session->read('role') != 'admin') {
            $this->redirect('/');
        }

        // @TODO システムバージョン等を表示する事
        
        $users = $this->User->find('all', array('conditions' => "User.role = 'admin'"));
        $this->set('admins', $users);
    }

    /**
     * upgrade
     *
     */
    function upgrade()
    {
        // adminじゃなければさようなら
        if ($this->Session->read('role') != 'admin') {
            $this->redirect('/');
        }

        if ($this->data) {
            $user = $this->User->findByUsername($this->data['User']['username']);
            if ($user) {
                $user['User']['role'] = 'admin';
            }
            $this->User->save($user);
        }

        $this->redirect('/users/control');
    }

    /**
     * downgrade
     *
     */
    function downgrade()
    {
        // adminじゃなければさようなら
        if ($this->Session->read('role') != 'admin') {
            $this->redirect('/');
        }

        if ($this->data) {
            $user = $this->User->findByUsername($this->data['User']['username']);
            if ($user) {
                $user['User']['role'] = 'user';
            }
            $this->User->save($user);
        }

        $this->redirect('/users/control');
    }

    /**
     * config
     *
     */
    function config()
    {
        if (!$this->isUser()) {
            $this->redirect('/');
        }

        $response = $this->getRequest();
        if ($response) {
            $username = end(explode('/', trim($response->identity_url, '/')));
            $provider_url = dirname(trim($response->identity_url, '/')) . '/';

            $exists_user = $this->User->find('first',array(
                'conditions'    => array(
                    'User.username' => $username,
                    'User.provider_url' => $provider_url,
                ),
                'recursive' => -1
            ));

            if ($exists_user) {
                $this->setMessage('そのユーザはすでに存在する為、アカウント設定を変更する事はできません');
                $this->redirect('/users/config');
            }

            $user = $this->User->findById($this->Session->read('id'));
            $user['User']['username'] = $username;
            $user['User']['provider_url'] = $provider_url;
            $this->User->save($user);

            $this->userlogout();
        }
    }

    private function setMessage($message) {
        $this->set('message', $message);
    }
}
?>
