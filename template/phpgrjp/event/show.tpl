{include file="header.tpl"}
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
<h2>Event Entry::{$app.event.name}</h2>
<dl>
<dt>イベント内容</dt><dd>{$app_ne.event.description}</dd>
<dt>イベント開催時間</dt><dd>{datespan start=$app.event.start_date end=$app.event.end_date}</dd>
<!-- <dt>イベント終了時間</dt><dd>{$app.event.end_date}</dd> -->
<dt>募集開始時間</dt><dd>{$app.event.accept_date}</dd>
<dt>イベント申し込み締め切り時間</dt><dd>{$app.event.due_date}</dd>
{if $app_ne.event.map}
<dt>地図</dt>
<dd>
<p>{$app_ne.map}</p>
<p>※会場までの地図です。<br />
地図は<em>最寄の駅</em>を表示しており、<img src="{$BASE_URL}/../theme/phpgrjp/images/map-play.png" />をクリックすることにより、<em>会場までの順路が表示されます</em>。<br />
また、地図をドラッグ＆ドロップにて動かすことができます。<br />
<br />
操作方法の詳細については<a href="http://www.alpslab.jp/slide_howto.html#4">貼り付けた地図の操作方法</a>を参照してください</p>
</dd>
{/if}
</dl>
</div>

{* Wiki Page *}
<div>
{$app_ne.page.content|parse_pukiwiki}
</div>

<h3>参加メンバー一覧</h3>
<ul>
<li>募集人数:{$app.event.max_register}</li>
<li>現在の参加人数:{$app.attendee_count}</li>
<li>残り:{$app.attendee_nokori}</li>
</ul>

<div align="center">
<table>
  <tr><th>name</th><th>comment</th><th>timestamp</th><th>action</th></tr>
  {foreach from=$app.attendee item=item}
  {if $item.canceled == 1}
    {cycle name=list print=false values=odd,even}
    <tr class="canceled">
  {else}
    <tr class="{cycle name=list values=odd,even}">
  {/if}
    <td><a href="http://profile.typekey.com/{$item.account_name}">{$item.account_nick}</a></td>
    <td>{$item.comment}</td>
    <td>{$item.register_at}</td>
    <td>
    {if ($item.account_name == $smarty.session.name) && ($item.canceled != 1)}
      <a href="{$BASE_URL}/eventcancel/{$item.id}">cancel</a>
    {/if}
    {if $smarty.session.is_admin && ($item.canceled == 1)}
      &nbsp;<a href="{$BASE_URL}/eventcancelrevert/{$item.id}">キャンセル解除</a>
    {/if}
    </td>
  </tr>
  {/foreach}
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

{foreach name=comment from=$app.comments item=comment key=key}
<div class="section">
<h4>
{$key}&nbsp;<a href="http://profile.typekey.com/{$comment.name}">{$comment.nick|escape}</a>
</h4>
  <p>
  {$comment.comment|nl2br}
{if $smarty.session.is_admin}
  &nbsp;<a href="{$BASE_URL}/event_commentdelete/{$comment.id}">[delete]</a>
{/if}
  </p>
</div>
{/foreach}

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
    <p>イベントに参加したりコメントする場合は<a href="{$BASE_URL}/login">ログイン</a>してください。</p>
  </div>
{/if}

<div id="trackback">
<h3>Trackback</h3>
{* TrackBack AutoDiscovery *}
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
{foreach name=trackback from=$app.trackbacks item=trackback}
{if $smarty.foreach.trackback.first}
<dl>
{/if}
<dt>
<a href="{$trackback.url}">{$trackback.title} - {$trackback.blog_name|escape}</a> - {$trackback.receive_time}
{if $smarty.session.is_admin}
&nbsp;<a href="{$BASE_URL}/trackbackdelete/{$trackback.id}">delete</a>
{/if}
</dt>
<dd><p>{$trackback.excerpt|nl2br}</p></dd>
{if $smarty.foreach.trackback.last}
</dl>
{/if}
{/foreach}
<p>
TrackBackPingURL:<input onfocus="this.select()" readonly="readonly" value="{$BASE_URL}/receiver/{$app.event.id}" size="45" id="ping-uri" name="ping-uri" type="text" />
</p>
<p class="notice">
本文にこのサイトへのリンクが含まれているTrackbackのみ受け付ける仕様になっています
</p>
</div>

{include file="footer.tpl"}
