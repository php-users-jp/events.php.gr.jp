{include file="header.tpl"}
<div>
<h2>Event Entry::{$app.event.name}</h2>
<p>{$app.event.date}</p>
<p>{$app_ne.event.description}</p>
{if $app_ne.map}
<p>{$app_ne.map}</p>
<p>会場までの地図です。地図の操作方法については<a href="http://www.alpslab.jp/slide_howto.html#4">貼り付けた地図の操作方法</a>を参照してください</p>
{/if}
</div>

<h3>参加メンバー一覧</h3>
<p>参加募集開始までしばらくおまちください。</p>
{*
<p>現在の参加人数:{$app.attendee|@count}<br>
残り:{$app.attendee_nokori}
</p>
{assoc2table value=$app.attendee}
<table>
<tr><th>name</th><th>comment</th><th>timestamp</th><th>action</th></tr>
{foreach from=$app.attendee item=item}
{if $item.canceled == 1}
<tr class="canceled">
{else}
<tr>
{/if}
<td>{$item.account_nick}</td>
<td>{$item.comment}</td>
<td>{$item.register_at}</td>
<td>
{if $item.account_name == $smarty.session.name}
<a href="{$BASE_URL}/eventcancel/{$item.id}">cancel</a>
{/if}
</td>
</tr>
{/foreach}
</table>
*}

<h3>コメント一覧</h3>
<p>参加募集開始までしばらくおまちください。</p>
{*
{foreach name=comment from=$app.comments item=comment key=key}
{if $smarty.foreach.comment.first}
<dl>
{/if}
<dt style="clear:left; matgin-top:1.5em">
{if $comment.url}
<a href="{$comment.url}">{$comment.name|escape}</a> - {$comment.timestamp}
{else}
{$key} - {$comment.nick|escape}
{/if}
</dt>
<dd>
{if $comment.url}
<p>
<img style="float:left; matgin-bottom:1.5em;" src="http://img.simpleapi.net/small/{$comment.url}/" />
</p>
<p>
{$comment.comment|nl2br}
</p>
{else}
<p>{$comment.comment|nl2br}</p>
{/if}
</dd>
{if $smarty.foreach.comment.last}
</dl>
{/if}
{/foreach}

{if isset($smarty.session.name)}
    <h3>イベントに参加する</h3>

    {if $app.joined}
    <p>あなたはすでにイベントに参加しています。</p>
    {else}
    <div class="info">
    <p>イベントに参加する場合は下のフォームにコメントを書いてjoinボタンを押してください。</p>
    </div>

    <form method="post" action="{$BASE_URL}/event_join">
    <input type="hidden" name="event_id" value="{$app.event.id}" />
    {form_name name="join_comment"}{form_input name="join_comment"}{form_input name="join"}
    </form>
    {/if}

    <h3>コメントする</h3>
    <form method="post" action="{$BASE_URL}/event_show/{$app.event.id}">

<div style="clear:left" id="commentform">
{if count($errors)}
 <ul>
  {foreach from=$errors item=error}
   <li>{$error}</li>
  {/foreach}
 </ul>
{/if}
{form_input name="comment"}{form_input name="post"}
</form>
</div>

{else}
    <div class="info">
    <p>イベントに参加したりコメントする場合は<a href="{$BASE_URL}/login">ログイン</a>してください。</p>
    </div>
{/if}
*}

{if $smarty.session.name && $smarty.session.is_admin}
    <a href="{$BASE_URL}/event_post/{$app.event.id}">Edit this Event</a>
{/if}
{include file="footer.tpl"}
