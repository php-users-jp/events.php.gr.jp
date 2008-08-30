<?php
function transformRSS($data) {
    return array(
        'title' => $data['Event']['title'],
        'link' => '/events/show/' . $data['Event']['id'],
        'guid' => '/events/show/' . $data['Event']['id'],
        'description' => $data['Event']['description'],
        'author' => 'halt',
        'pubDate' => $data['Event']['publish_date']
    );
}

echo $rss->items($events, 'transformRSS');
?>
