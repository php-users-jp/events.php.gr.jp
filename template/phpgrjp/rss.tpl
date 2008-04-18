<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:ev="http://purl.org/rss/1.0/modules/event/">
<channel>
<title>{$app.title}</title>
<link>{$BASE_URL}</link>
<description>{$app.event.description|default:"Event"}</description>
<category>php.gr.jp</category>
<generator>Ethna</generator>
{foreach from=$app.recent item=item}
<item>
<title>{$item.name}</title>
<link>{$BASE_URL}/event_show/{$item.id}</link>
<guid>{$BASE_URL}/event_show/{$item.id}</guid>
<description>{$item.description}</description>
{* <author>{$item.author}</author> *}
<category>Event</category>
<pubDate>{$item.pubDate}</pubDate>
<ev:startdate>{$item.startdate}</ev:startdate>
<ev:enddate>{$item.enddate}</ev:enddate>
</item>
{/foreach}
</channel>
</rss>
