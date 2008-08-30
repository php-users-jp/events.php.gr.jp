<h2>Config</h2>

<h3>System Environment</h3>
implement yet

<h3>管理権限者一覧</h3>

<table>
  <tr><th>name</th><th>edited_at</th><th>action</th></tr>
  <?php foreach ($admins as $admin) : ?>
  <tr>
  <td><?php echo $admin['User']['username'];?></td>
  <td><?php echo $admin['User']['modified'];?></td>
  <td>
<?php echo $form->create('User', array('type' => 'post', 'action' => 'downgrade')); ?>
<?php echo $form->hidden('username', array('type' => 'text', 'value' => $admin['User']['username'])); ?>
<?php echo $form->end('一般ユーザに降格'); ?>
  </td>
  </tr>
  <?php endforeach; ?>
</table>

<h3>管理者の追加</h3>
<div class="info">
  <p>追加するユーザのtypekeyアカウントを入力してsubmitを押してください</p>
</div>
<p>
<?php echo $form->create('User', array('type' => 'post', 'action' => 'upgrade')); ?>
<?php echo $form->input('username', array('type' => 'text')); ?>
<?php echo $form->end('管理者に昇格'); ?>
</p>
