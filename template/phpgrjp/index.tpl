{include file="header.tpl"}
{*
<div>
<h2>{"Recent News"|i18n}</h2>
{foreach from=$app.recent_news item=item}
<h3>{$item.title}&nbsp;{$item.date}</h3>
<p>{$item.description}</p>
<p><a href="{$BASE_URL}/news_show/{$item.id}">Permalink</a>
{/foreach}
</div>
*}
<div>
<h2>{"Recent Event"|i18n}</h2>
{foreach from=$app.recent_event item=item}
<h3>{$item.name}&nbsp;{$item.date}</h3>
<p>{$item.description|replace:"\r\n":""|mb_truncate:128}</p>
<table>
<tr><th>イベント開始時間</th><td>{$item.start_date}</td></tr>
<tr><th>イベント終了時間</th><td>{$item.end_date}</td></tr>
<tr><th>イベント申し込み締め切り時間</th><td>{$item.due_date}</td></tr>
<tr><th>募集人数</th><td>{$item.max_register}人</td></tr>
</table>
<p><a href="{$BASE_URL}/event_show/{$item.id}">このイベントに参加する/詳細を見る</a>
{/foreach}
</div>
<p>
{if $app.hasprev}
  <a href="{$app.link}?start=0">最初</a>&nbsp;<a href="{$app.link}?start={$app.prev}">&lt;&lt;</a>
{else}
  最初&nbsp;&lt;&lt;
{/if}

{foreach from=$app.pager item=page}
  {if $page.offset == $app.current}
    <strong>{$page.index}</strong>
  {else}
    <a href="{$app.link}?start={$page.offset}">{$page.index}</a>
  {/if}
  &nbsp;
{/foreach}

{if $app.hasnext}
  <a href="{$app.link}?start={$app.next}">&gt;&gt;</a>
  &nbsp;
  <a href="{$app.link}?start={$app.last}">最後</a>
{else}
  &gt;&gt;最後&nbsp;
{/if}
</p>
{include file="footer.tpl"}
