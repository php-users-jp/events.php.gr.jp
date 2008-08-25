<?php
  echo $form->create('User', array('action' => 'openid_add'));
  echo $form->input('nickname');
  echo $form->submit();
  echo $form->end();
?>
