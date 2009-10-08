<?php
class Twitter extends AppModel {
  const API_SEARCH = 'http://search.twitter.com/';
  const PAGE_LIMIT = 10;
  const CACHE_DURATION = '+5 minutes' ;

  public $useTable = false;

	public function read( $query, $rpp=self::PAGE_LIMIT )
	{
	  $q = $query ;
	  if ( is_array($query) ) {
	    $q = null ;
      foreach( $query as $key ) {
        if ( !is_null($q) ) 
          $q .= "+OR+" ;
          
        $q .= $key ; 
      }
    }
    
		if (!$tweets = Cache::read($q, 'default')) {
      App::import('Core', 'HttpSocket');
  		$this->Socket = new HttpSocket(self::API_SEARCH);
		  $tweets = json_decode($this->Socket->get("/search.json?q={$q}&rpp={$rpp}"));
			Cache::write($q, $tweets, array('duration' => self::CACHE_DURATION));
		}

		return $tweets ;
  }
}
