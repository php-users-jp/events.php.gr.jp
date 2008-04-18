{include file="header.tpl"}
<div>
<h2>Event Administration</h2>
<p>
<a href="{$BASE_URL}/event_post">新規イベント登録</a>
</p>
<table>
<tr>
<th>action</th><th>start_date</th><th>duedate(イベント締め切り)</th><th>event</th>
<th>private</th>
</tr>
{foreach from=$app.recent_event item=item}
<tr>
<td>
<a href="{$BASE_URL}/event_post/{$item.id}">編集</a> / <a href="{$BASE_URL}/event_delete/{$item.id}">削除</a> 
</td>
<td>{$item.start_date}</td>
<td>{$item.due_date}</td>
<td><a href="{$BASE_URL}/event_show/{$item.id}">{$item.name}</a></td>
<td>{$item.private}</td>
</tr>
{/foreach}
</table>
</div>
{include file="footer.tpl"}

