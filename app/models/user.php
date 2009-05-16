<?php
/**
 *
 *
 */

/**
 * User
 *
 */
class User extends AppModel
{
    public $name = 'User';
    public $useTable = 'user';
    public $Openid;

    /**
     *
     *
     */
    public function findFromRequest($username, $provider_url)
    {
      App::import('Model', 'Openid');

      $this->Openid = new Openid();

      $this->Openid->bindModel(
        array(
          'belongsTo' => array('User')
        )
      );

      $user = $this->Openid->find('first',
        array(
          'conditions'    => array(
            'Openid.username' => $username,
            'Openid.provider_url' => $provider_url,
          ),
        )
      );

      return $user;
    }
}

?>
