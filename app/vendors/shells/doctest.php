<?php
/**
 *
 *
 */

/**
 *
 *
 */
class DoctestShell extends Shell {

    /**
     * model
     *
     */
    function model()
    {
        require_once CONFIGS . 'database.php';
        App::Import('Core', array('Model', 'AppModel'));
        $this->doctest('models');
    }

    /**
     * doctest
     *
     */
    function doctest($place)
    {
        require_once 'Maple4/DocTest.php';

        $pathname = dirname(dirname(dirname(__FILE__))) . '/' . $place;

        $options = array(
            'compileDir' => dirname(dirname(dirname(__FILE__))) . '/tmp/tests/' . $place,
            'color' => true,
            'report' => null,
            'forceCompile' => true,
            'notify' => null,
        );

        Maple4_DocTest::create()->run($pathname, $options);
    }
}

?>
