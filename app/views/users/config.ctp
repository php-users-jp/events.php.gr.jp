<h2>ユーザ設定の変更</h2>

<p>
利用するOpendIDサーバを変更する事ができます
</p>

<?php
if (isset($message)) {
    echo '<p class="error">'.$message.'</p>';
}
?>

<h3>TypeKeyにきりかえる<h3>
<?php 
echo $form->create(
    'User', array('type' => 'post', 'action' => 'config')
);
echo 'TypeKeyのユーザ名を入れてください';
echo $form->hidden(
    'OpenidUrl.provider_url',
    array('value' => 'http://profile.typekey.com/')
);
echo $form->input('OpenidUrl.username', array('label' => false));
echo $form->end('Login');
?>

<h3>はてなにきりかえる<h3>

<h3>mixiにきりかえる<h3>
<?php
echo $form->create(
    'User', array('type' => 'post', 'action' => 'config')
);
echo $form->hidden(
    'OpenidUrl.provider_url',
    array('value' => 'http://mixi.jp/')
);
echo $form->end('mixiでlogin');
?>

