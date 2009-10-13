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

//		Cache::set(array('duration' => self::CACHE_DURATION));
		if (!$tweets = Cache::read($q, 'default')) {
		  $this->log("TwitterCache MISS({$q})", LOG_ERR) ;
      App::import('Core', 'HttpSocket');
  		$this->Socket = new HttpSocket(self::API_SEARCH);
		  $tweets = json_decode($this->Socket->get("/search.json?q={$q}&rpp={$rpp}"));
//		  Cache::set(array('duration' => self::CACHE_DURATION));
//			Cache::write($q, $tweets, 'default');
			Cache::write($q, $tweets, array('duration' => self::CACHE_DURATION));
		} else {
		  $this->log("TwitterCache HIT({$q})", LOG_ERR ) ;
		}

		return $tweets ;
  }
}
