<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
<title>{$app.event.name}</title>
<link>{$BASE_URL}/event_show/{$app.event.id}</link>
<description>{$app.event.description|default:"Event"}</description>
<category>php.gr.jp</category>
<generator>Ethna</generator>
{foreach from=$app.detail item=item}
<item>
<title>{$item.name}</title>
<link>{$BASE_URL}/event_show/{$app.event.id}#{$item.category}{$item.id}</link>
<guid>{$BASE_URL}/event_show/{$app.event.id}#{$item.category}{$item.id}</guid>
<description>{$item.description}</description>
<author>{$item.author}</author>
<category>{$item.category|default:"Event"}</category>
<pubDate>{$item.pubDate}</pubDate>
</item>
{/foreach}
</channel>
</rss>
