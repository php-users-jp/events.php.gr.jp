<?php
/**
 * login.ctp
 *
 * vim:set fenc=utf-8:
 */

if (isset($message)) {
    echo '<p class="error">'.$message.'</p>';
}

echo $form->create(
    'User', array('type' => 'post', 'action' => 'login')
);
echo 'TypeKey のユーザ名を入れてください';
echo $form->hidden(
    'OpenidUrl.provider_url',
    array('value' => 'http://profile.typekey.com/')
);
echo $form->input('OpenidUrl.username', array('label' => false));
echo $form->end('Login');
?>
