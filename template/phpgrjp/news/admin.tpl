{include file="header.tpl"}
<div>
<h2>News Administration</h2>
<p><a href="{$BASE_URL}/news_post">NewsPost</a></p>
{foreach from=$app.recent_news item=item}
<p>
<a href="{$BASE_URL}/news_post/{$item.id}">edit</a> / <a href="{$BASE_URL}/news_delete/{$item.id}">delete</a> <a href="{$BASE_URL}/news_show/{$item.id}">{$item.title}</a>
</p>
{/foreach}
</div>
{include file="footer.tpl"}

