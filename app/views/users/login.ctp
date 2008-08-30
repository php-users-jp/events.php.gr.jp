<?php
/**
 * login.ctp
 *
 * vim:set fenc=utf-8:
 */

if (isset($message)) {
    echo '<p class="error">'.$message.'</p>';
}
?>
<h2>OpenIDによるログイン</h2>
<p>
本システムではOpenIDによるユーザ認証を行っています。旧来のTypeKey認証から別の認証方式を利用したい場合は、一度TypeKey認証でログインした後、「ユーザ設定」から他のOpenID認証にログインして設定のひきつぎを行ってください。
</p>

<h3>TypeKeyでログイン</h3>
<?php 
echo $form->create(
    'User', array('type' => 'post', 'action' => 'login')
);
echo 'TypeKeyのユーザ名を入れてください';
echo $form->hidden(
    'OpenidUrl.provider_url',
    array('value' => 'http://profile.typekey.com/')
);
echo $form->input('OpenidUrl.username', array('label' => false));
echo $form->end('Login');
?>

<h3>はてなでログイン</h3>
<?php
echo $form->create(
    'User', array('type' => 'post', 'action' => 'login')
);
echo 'はてなのユーザ名を入れてください';
echo $form->hidden('OpenidUrl.provider_url', array('value' => 'http://www.hatena.ne.jp/'));
echo $form->input('OpenidUrl.username', array('label' => false));
echo $form->end('はてなでlogin');
?>

<h3>mixiでログイン</h3>
<?php
echo $form->create(
    'User', array('type' => 'post', 'action' => 'login')
);
echo $form->hidden(
    'OpenidUrl.provider_url',
    array('value' => 'http://mixi.jp/')
);
echo $form->end('mixiでlogin');
?>
