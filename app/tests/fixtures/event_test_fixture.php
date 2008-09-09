<?php
/**
 *
 *
 */

/**
 * EventTestFixture
 *
 */
class EventTestFixture extends CakeTestFixture
{
    var $name = 'EventTest';
    var $import = array('model' => 'Event', 'records' => true);

    var $records = array(
        array(
            'id' => '9000',
            'author' => 'test',
            'name' => 'test',
            'max_register' => '10',
            'description'  => 'test',
            'private_description' => 'test',
            'map' => '',
            'start_date' => '2008-01-01-01 00:00:00',
            'end_date' => '2008-01-01-01 00:00:00',
            'due_date' => '2008-01-01-01 00:00:00',
            'publish_date' => '2008-01-01-01 00:00:00',
            'private' => '0',
        )
    );
}

?>
