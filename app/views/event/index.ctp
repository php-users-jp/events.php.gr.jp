<div>
<h2>{"Recent Event"|i18n}</h2>
{foreach from=$app.recent_event item=item}
<h3>{$item.name}&nbsp;{$item.date}</h3>
<p>{$item.description}</p>
<p><a href="{$BASE_URL}/event_show/{$item.id}">PermaLink</a>
{/foreach}
</div>
