{include file="header.tpl"}
<div>
<h2>{"Recent News"|i18n}</h2>
{foreach from=$app.recent_news item=item}
<h3>{$item.title}&nbsp;{$item.date}</h3>
<p>{$item.description}</p>
<p><a href="{$BASE_URL}/news_show/{$item.id}">PermaLink</a>
{/foreach}
</div>
<div>
<h2>{"Recent Event"|i18n}</h2>
{foreach from=$app.recent_event item=item}
<h3>{$item.name}&nbsp;{$item.date}</h3>
<p>{$item.description}</p>
<p><a href="{$BASE_URL}/event_show/{$item.id}">PermaLink</a>
{/foreach}
</div>
{include file="footer.tpl"}
