<?php
// vim: foldmethod=marker
/**
 *  plugin sample file.
 *
 *  @author     ICHII Takashi <ichii386@schweetheart.jp>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Sample
 *  @version    $Id: sample_plugin.php 411 2006-11-17 02:32:32Z ichii386 $
 */

// {{{ {$application_id}_Plugin_Sample_Phpinfo
/**
 *  plugin sample class.
 *
 *  @author     ICHII Takashi <ichii386@schweetheart.jp>
 *  @access     public
 *  @package    Sample
 */
class {$application_id}_Plugin_Sample_Phpinfo
{
    // {{{ perform
    /**
     *  do phpinfo().
     *
     *  @access public
     */
    function perform()
    {
        phpinfo();
    }
    // }}}
}
// }}}
?>
