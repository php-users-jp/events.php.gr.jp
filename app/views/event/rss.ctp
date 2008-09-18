<?php
function transformRSS($data) {
    if (isset($data['Trackback'])) {
        $result = array(
            'title' => $data['Trackback']['title'],
            'link' => '/events/show/' . $data['Trackback']['event_id'],
            'guid' => '/events/show/' . $data['Trackback']['event_id'],
            'description' => $data['Trackback']['excerpt'],
            'author' => 'event',
            'pubDate' => $data['Trackback']['receive_time']
        );
    } else {
        $result =  array(
            'title' => $data['Event']['name'],
            'link' => '/events/show/' . $data['Event']['id'],
            'guid' => '/events/show/' . $data['Event']['id'],
            'description' => $data['Event']['description'],
            'author' => 'event',
            'pubDate' => $data['Event']['publish_date']
        );
    }

    return $result;
}

echo $rss->items($events, 'transformRSS');
?>
