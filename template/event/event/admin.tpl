{include file="header.tpl"}
<div>
<h2>Event Administration</h2>
<p>
<a href="{$BASE_URL}/event_post">EventPost</a>
</p>
<table>
<tr><th>action</th><th>date</th><th>duedate</th><th>event</th></tr>
{foreach from=$app.recent_event item=item}
<tr>
<td>
<a href="{$BASE_URL}/event_post/{$item.id}">edit</a> / <a href="{$BASE_URL}/event_delete/{$item.id}">delete</a> 
</td>
<td>{$item.date}</td>
<td>{$item.duedate}</td>
<td><a href="{$BASE_URL}/event_show/{$item.id}">{$item.name}</a></td>
</tr>
{/foreach}
</table>
</div>
{include file="footer.tpl"}

