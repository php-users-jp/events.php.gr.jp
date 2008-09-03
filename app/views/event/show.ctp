<hr />
<ul class="menu">
<?php if ($session->read('role') == 'admin'): ?>
  <li><?php echo $html->link('このイベントを編集する', '/events/edit/' . $event_id); ?></li>
<?php endif; ?>
<?php if ($joined && !$canceled): ?>
<li><?php echo $html->link('Wikiページを編集する', '/event_pages/edit/'.$event_id); ?></li>
<?php endif;?>
<li><?php echo $html->link('このイベントのRSS', '/events/rss/' . $event_id); ?></li>
</ul>

<div>
<h2>Event Entry::<?php echo $data['Event']['name']; ?></h2>
<dl>
<dt>イベント内容</dt><dd><?php echo $data['Event']['description']; ?></dd>
<dt>イベント開催時間</dt><dd><?php echo $datespan->display($data['Event']['start_date'], $data['Event']['end_date']);?></dd>
<!-- <dt>イベント終了時間</dt><dd>{$app.event.end_date}</dd> -->
<dt>募集開始時間</dt><dd><?php echo $data['Event']['accept_date']; ?></dd>
<dt>イベント申し込み締め切り時間</dt>
  <dd><?php echo $data['Event']['due_date']; ?></dd>
<?php if (!empty($data['Event']['map'])): ?>
<dt>地図</dt>
<dd>
<script type="text/javascript" src="http://slide.alpslab.jp/scrollmap.js"></script>
<div class="alpslab-slide">
<?php echo $data['Event']['map']; ?>
</div>
<p>※会場までの地図です。<br />
地図は<em>最寄の駅</em>を表示しており、<img src="{$BASE_URL}/../theme/phpgrjp/images/map-play.png" />をクリックすることにより、<em>会場までの順路が表示されます</em>。<br />
また、地図をドラッグ＆ドロップにて動かすことができます。<br />
<br />
操作方法の詳細については<a href="http://www.alpslab.jp/slide_howto.html#4">貼り付けた地図の操作方法</a>を参照してください</p>
</dd>
<?php endif; ?>
</dl>
</div>

<div>
<?php echo $data['EventPage']['content']; ?>
</div>

<h3>参加メンバー一覧</h3>
<ul>
<li>募集人数:<?php echo $data['Event']['max_register']; ?></li>
<li>現在の参加人数:<?php echo $attendee_count; ?></li>
<li>残り:<?php echo $attendee_nokori; ?></li>
</ul>

<div align="center">
<table>
  <tr><th>name</th><th>comment</th><th>timestamp</th><th>action</th></tr>
  <?php foreach ($data['EventAttendee'] as $key => $item): ?>

  <?php if ($item['canceled'] == 1): ?>
    <tr class="canceled">
  <?php else: ?>
    <?php if (($key % 2) == 0): ?>
    <tr class="odd">
    <?php else: ?>
    <tr class="even">
    <?php endif; ?>
  <?php endif; ?>
    <td><a href="http://profile.typekey.com/<?php echo $item['User']['username']; ?>"><?php echo $item['User']['nickname']; ?></a></td>
    <td><?php echo h($item['comment']); ?></td>
    <td><?php echo $item['created']; ?></td>
    <td>
    <?php /* 自分のでまだキャンセルしてなかったらキャンセルリンクを出す */ ?>
    <?php if ($item['User']['id'] == $session->read('id') && ($item['canceled'] != 1)): ?>
      <?php echo $html->link('cancel', '/event_attendees/cancel/'.$item['id'], null, 'ドタキャン対策の為、キャンセルするとそのイベントには二度と参加できません。キャンセルしますか？'); ?>
    <?php endif; ?>
    <?php if (($session->read('role') == 'admin') && ($item['canceled'] == 1)): ?>
      &nbsp;<?php echo $html->link('キャンセル解除', '/event_attendees/cancelrevert/'.$item['id']); ?>
    <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="4">
    <?php if ($session->check('username')): ?>
      <p><strong>イベントに参加する</strong></p>
      <?php if ($is_over || ($attendee_nokori <= 0)): ?>
      <p>このイベントの募集は終了しました。</p>
        <?php if ($joined): ?>
          <?php if ($data['Event']['private_description']): ?>
            <p><?php echo $data['Event']['private_description']; ?></p>
          <?php endif; ?>
        <?php endif; ?>
      <?php elseif (strtotime($data['Event']['accept_date']) > time()): ?>
      <p>このイベントは <?php echo $data['Event']['accept_date']; ?> から応募開始します。</p> 
      <?php elseif ($joined): ?>
        <?php if ($data['Event']['private_description']): ?>
          <p><?php echo $data['Event']['private_description']; ?></p>
        <?php else: ?>
          <p>あなたはすでにイベントに参加しています。</p>
        <?php endif; ?>
      <?php else: ?>
        <div class="info">
          <p>イベントに参加する場合は下のフォームにコメントを書いてjoinボタンを押してください。</p>
        </div>

        <?php echo $form->create('EventAttendee', array('type'  => 'post', 'action' => 'join')); ?>
        <?php echo $form->hidden('EventAttendee.event_id', array('value' => $event_id)); ?>
        <?php echo $form->input('EventAttendee.comment', array('type' => 'text', 'size' => '45')); ?>
        <?php echo $form->end('参加する'); ?>
      <?php endif; ?>
    <?php else: ?>
      <div class="info">
      <p>イベントに参加したりコメントする場合は<?php echo $html->link('ログイン', '/users/login');?>してください。</p>
      </div>
    <?php endif; ?>
    </td>
  </tr>
</table>
</div>

<div class="section" id="comments">
<h3>コメント一覧</h3>

<?php foreach ($data['EventComment'] as $key => $comment): ?>
<div class="section">
<h4>
<?php echo $key; ?> &nbsp; <?php echo $html->link(h($comment['User']['nickname']), "http://profile.typekey.com/{$comment['User']['username']}"); ?>
</h4>
  <p>
<?php echo h($comment['comment']); ?>
<?php if ($comment['User']['id'] == $session->read('id') || $session->read('role') == 'admin'): ?> 
    &nbsp;<?php echo $html->link('[delete]', '/event_comments/delete/'.$comment['id'], null, '削除しても良いですか？');?>
<?php endif; ?>
  </p>
</div>
<?php endforeach; ?>

</div>

<?php if ($session->check('username')): ?>
  <div id="commentform">
<?php
echo $form->create(
    'EventComment', array('type' => 'post', 'action' => 'join')
);
echo $form->hidden('EventComment.event_id', array('value' => $event_id));
echo $form->input('EventComment.comment', array('type' => 'text', 'size' => '45'));
echo $form->end('コメントする');
?>
  </div>
<?php else: ?>
  <div class="info">
    <p>イベントに参加したりコメントする場合は<?php echo $html->link('ログイン', '/users/login'); ?>してください。</p>
  </div>
<?php endif; ?>

<div id="trackback">
<h3>Trackback</h3>
<!--
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">
<rdf:Description
  rdf:about="<?php echo $html->base;?>/events/show/<?php echo $event_id;?>"
  trackback:ping="<?php echo $html->base . '/trackbacks/receive/' . $event_id; ?>"
  dc:title="<?php echo $Event['Event']['title']; ?>"
  dc:identifier="<?php echo $html->base;?>/events/show/<?php echo $event_id;?>" />
</rdf:RDF>
-->
<dl>
<?php foreach ($data['Trackback'] as $trackback): ?>
<dt>
<?php echo $html->link(h($trackback['blog_name']), $trackback['url']); ?> - <?php echo $trackback['receive_time']; ?>
<?php if ($session->read('role') == 'admin'): ?>
&nbsp;<?php echo $html->link('delete', '/trackbacks/delete/' . $trackback['id']); ?>
<?php endif; ?>
</dt>
<dd><p><?php echo nl2br($trackback['excerpt']); ?></p></dd>
<?php endforeach; ?>
</dl>

<p>
TrackBackPingURL:<input onfocus="this.select()"
   readonly="readonly"
   value="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $html->base . '/trackbacks/receive/' . $event_id; ?>" size="45" id="ping-uri" name="ping-uri" type="text" />
</p>

<p class="notice">
本文にこのサイトへのリンクが含まれているTrackbackのみ受け付ける仕様になっています
</p>

</div>
