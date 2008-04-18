<?php
/**
 *  {$action_name}.php
 *
 *  @author     {$author}
 *  @package    {$project_id}
 *  @version    $Id: skel.entry_cli.php 432 2006-11-28 04:52:54Z ichii386 $
 */
chdir(dirname(__FILE__));
require_once '{$dir_app}/{$project_id}_Controller.php';

ini_set('max_execution_time', 0);

{$project_id}_Controller::main_CLI('{$project_id}_Controller', '{$action_name}');
?>
