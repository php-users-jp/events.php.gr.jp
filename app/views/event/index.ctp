<?php
// vim:fenc=utf-8
?>
<div>
<h2>Recent Event</h2>
<?php foreach($events as $event): ?>

<h3><?php echo $event['Event']['name']; ?></h3>
<p>
<?php
echo mb_strcut(
    strip_tags(
        str_replace("\r\n", '', $event['Event']['description'])
    ), 0, 256, 'utf-8');
?>
</p>

<table>
<tr>
  <th>募集開始</th>
  <td><?php echo $event['Event']['accept_date']; ?></td>
</tr>
<tr>
  <th>イベント開催時間</th>
  <td><?php echo $datespan->display($event['Event']['start_date'], $event['Event']['end_date']); ?></td>
</tr>
<tr>
  <th>イベント申し込み締め切り時間</th>
  <td><?php echo $event['Event']['due_date']; ?></td>
</tr>
<tr>
  <th>募集人数</th>
  <td><?php echo $event['Event']['max_register']; ?>人</td>
</tr>
</table>

<p><?php echo $html->link('このイベントに参加する/詳細を見る', '/events/show/' . $event['Event']['id']); ?>

<?php endforeach; ?>

<div class="pager">
  <?php echo $this->renderElement('paginator'); ?>
</div>

</div>
