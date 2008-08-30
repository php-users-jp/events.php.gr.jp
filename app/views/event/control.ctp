<div>
<h2>イベント管理</h2>

<p>
<?php echo $html->link('新規イベント登録', '/events/edit'); ?>
</p>

<table>
<tr>
  <th>action</th>
  <th>start_date</th>
  <th>duedate(イベント締め切り)</th>
  <th>event</th>
  <th>private</th>
</tr>

<?php foreach ($events as $event): ?>
<tr>
  <td>
  <?php echo $html->link('編集', '/events/edit/'.$event['Event']['id']); ?>
  &nbsp;:&nbsp;
  <?php echo $html->link('削除', '/events/delete/'.$event['Event']['id'], null, '削除しても良いですか？'); ?>
  </td>
  <td><?php echo $event['Event']['start_date']; ?></td>
  <td><?php echo $event['Event']['due_date']; ?></td>
  <td><?php echo $html->link($event['Event']['name'], '/events/show/'.$event['Event']['id']); ?></td>
  <td><?php echo $event['Event']['private']; ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>
