<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:ev="http://purl.org/rss/1.0/modules/event/">
<channel>
<title>{$app.title} - TrackBack</title>
<link>{$BASE_URL}</link>
<description>{$app.event.description|default:"Event"}</description>
<category>php.gr.jp</category>
<generator>Ethna</generator>
{foreach from=$app.recent item=item}
<item>
<title>{$item.title} - {$item.name}</title>
<link>{$BASE_URL}/event_show/{$item.event_id}</link>
<guid>{$BASE_URL}/event_show/{$item.event_id}</guid>
<description>{$item.excerpt}</description>
<category>TrackBack</category>
<pubDate>{$item.receive_time}</pubDate>
<ev:startdate>{$item.startdate}</ev:startdate>
<ev:enddate>{$item.enddate}</ev:enddate>
</item>
{/foreach}
</channel>
</rss>
