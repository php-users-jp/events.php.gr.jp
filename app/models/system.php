<?php
/**
 *
 *
 */

/**
 * System
 *
 */
class System extends AppModel
{
  public $name = 'System';
  public $useTable = 'system';

  /**
   * DBのバージョンを返す
   *
   */
  public function getVersion()
  {
    $re = $this->findByVColumn('version');

    if ($re === false) {
      return false;
    } else {
      return $re['System']['v_value'];
    }
  }
}

?>
