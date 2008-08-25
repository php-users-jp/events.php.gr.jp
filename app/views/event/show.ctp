<hr />
<ul class="menu">
{if $smarty.session.name && $smarty.session.is_admin}
    <li><a href="{$BASE_URL}/event_post/{$app.event.id}">このイベントを編集する</a></li>
{/if}
{if $app.joined && !$app.canceled}
<li><a href="{$BASE_URL}/event_page/{$app.event.id}">Wikiページを編集する</a></li>
{/if}
<li><a href="{$BASE_URL}/rss/{$app.event.id}">RSS</a></li>
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
    <td><a href="http://profile.typekey.com/<?php echo $item['account_name']; ?>"><?php echo $item['account_nick']; ?></a></td>
    <td><?php echo $item['comment']; ?></td>
    <td><?php echo $item['register_at']; ?></td>
    <td>
    <?php /* 自分のでまだキャンセルしてなかったらキャンセルリンクを出す */ ?>
    {if ($item.account_name == $smarty.session.name) && ($item.canceled != 1)}
      <a href="{$BASE_URL}/eventcancel/{$item.id}">cancel</a>
    {/if}
    {if $smarty.session.is_admin && ($item.canceled == 1)}
      &nbsp;<a href="{$BASE_URL}/eventcancelrevert/{$item.id}">キャンセル解除</a>
    {/if}
    </td>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="4">
    {if isset($smarty.session.name)}
      <p><strong>イベントに参加する</strong></p>
      {if $app.is_over || ($app.attendee_nokori <= 0)}
      <p>このイベントの募集は終了しました。</p>
        {if $app.joined}
            {if $app.event.private_description}
            <p>{$app_ne.event.private_description}</p>
            {/if}
        {/if}
      {elseif strtotime($app.event.accept_date) > $smarty.now}
        <p>このイベントは {$app.event.accept_date} から応募開始します。</p> 
      {elseif $app.joined}
        {if $app_ne.event.private_description}
          <p>{$app_ne.event.private_description}</p>
        {else}
          <p>あなたはすでにイベントに参加しています。</p>
        {/if}
      {else}
        <div class="info">
          <p>イベントに参加する場合は下のフォームにコメントを書いてjoinボタンを押してください。</p>
        </div>

        {form method="post" action="$BASE_URL/event_join"}
        <input type="hidden" name="event_id" value="{$app.event.id}" />
        {form_name name="join_comment" }:<br />{form_input name="join_comment" attr='size="100"'} {form_input name="join"}
        {/form}
      {/if}
    {else}
      <div class="info">
        <p>イベントに参加したりコメントする場合は<a href="{$BASE_URL}/login">ログイン</a>してください。</p>
      </div>
    {/if}
    </td>
  </tr>
</table>
</div>

<div class="section" id="comments">
<h3>コメント一覧</h3>

<?php foreach ($data['EventComment'] as $key => $comment): ?>
<div class="section">
<h4>
<?php echo $key; ?> &nbsp; <?php echo $html->link(h($comment['nick']), "http://profile.typekey.com/{$comment['name']}"); ?>
</h4>
  <p>
<?php echo nl2br($comment['comment']); ?>
{if $smarty.session.is_admin}
  &nbsp;<a href="{$BASE_URL}/event_commentdelete/{$comment.id}">[delete]</a>
{/if}
  </p>
</div>
<?php endforeach; ?>

</div>

{if isset($smarty.session.name)}
  <div id="commentform">
    {form method="post" action="$BASE_URL/event_show/`$app.event.id`"}
    {if count($errors)}
    <ul>
    {foreach from=$errors item=error}
      <li>{$error}</li>
    {/foreach}
    </ul>
    {/if}
    コメントする{form_input name="comment" attr='size="100"'} {form_input name="post"}
    {/form}
  </div>
{else}
  <div class="info">
    <p>イベントに参加したりコメントする場合は<?php echo $html->link('ログイン', '/users/login'); ?>してください。</p>
  </div>
{/if}

<div id="trackback">
<h3>Trackback</h3>
<!--
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">
<rdf:Description
  rdf:about="{$BASE_URL}/event_show/{$app.event.id}"
  trackback:ping="{$BASE_URL}/receiver/{$app.event.id}"
  dc:title="{$row.title}"
  dc:identifier="{$BASE_URL}/event_show/{$app.event.id}" />
</rdf:RDF>
-->
<dl>
<?php foreach ($data['Trackback'] as $trackback): ?>
<dt>
<?php echo $html->link(h($trackback['blog_name']), $trackback['url']); ?> - <?php echo $trackback['receive_time']; ?>
{if $smarty.session.is_admin}
&nbsp;<a href="{$BASE_URL}/trackbackdelete/{$trackback.id}">delete</a>
{/if}
</dt>
<dd><p><?php echo nl2br($trackback['excerpt']); ?></p></dd>
<?php endforeach; ?>
</dl>

<p>
TrackBackPingURL:<input onfocus="this.select()" readonly="readonly" value="{$BASE_URL}/receiver/{$app.event.id}" size="45" id="ping-uri" name="ping-uri" type="text" />
</p>

<p class="notice">
本文にこのサイトへのリンクが含まれているTrackbackのみ受け付ける仕様になっています
</p>

</div>
