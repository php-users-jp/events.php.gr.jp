<?php
/**
 * index.php
 *
 * @author halt feits <halt.feits@gmail.com>
 * @version $Id: index.php 44 2006-05-31 06:36:14Z halt $
 */

require_once( dirname( dirname(__FILE__) ) . '/app/Event_Controller.php');

Event_Controller::main('Event_Controller', 'index');
?>
