<?php
class Twitter extends AppModel {
  const API_SEARCH = 'http://search.twitter.com/';
  const PAGE_LIMIT = 10;
  const STATUS_CACHE_DURATION = '+5 minutes' ;
  const DATA_CACHE_DURATION = '+1 day' ;

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

    $tweets = null ;
    
		if (!$status = Cache::read("status_".$q, 'default')) {
		  $this->log("Twitter Status Cache MISS({$q})", LOG_ERR) ;
  		
  		$result = $this->search( "/search.json?q={$q}&rpp={$rpp}" ) ;
  		if ( is_string($result) ) { 		
		    $tweets = json_decode($result);
  			Cache::write($q, $tweets, array('duration' => self::DATA_CACHE_DURATION));
  			Cache::write("status_".$q, 1, array('duration' => self::STATUS_CACHE_DURATION));
  		} else {
		    if (!$tweets = Cache::read($q, 'default')) {
		      $this->log("Twitter Data Cache MISS-1({$q})", LOG_ERR) ;
    		  $tweets = new stdClass();
    		  $tweets->error = is_null($result['code'])?999:$result['code'] ;
	      } else {
  			  Cache::write($q, $tweets, array('duration' => self::DATA_CACHE_DURATION));
        }
		  }
		} else {
		  $this->log("TwitterCache HIT({$q})", LOG_ERR ) ;
	    if (!$tweets = Cache::read($q, 'default')) {
	      $this->log("Twitter Data Cache MISS-2({$q})", LOG_ERR) ;
  		  $tweets = new stdClass();
  		  $tweets->error = 999 ;
      } else {
			  Cache::write($q, $tweets, array('duration' => self::DATA_CACHE_DURATION));
      }
		}

		return $tweets ;
  }
  
  function search( $url ) 
  {
    App::import('Core', 'HttpSocket');
		$this->Socket = new HttpSocket(self::API_SEARCH);
		$result = $this->Socket->get($url) ;
  	if ( !$result ) {
	    $this->log( "twitter seach error", LOG_ERR) ; 
  	  return array('code' => $this->Socket->response['status']['code']) ;
  	}
  	
  	if ( $this->Socket->response['status']['code'] != '200' ) {
		    $this->log( sprintf("twitter seach responce error(%S)", $this->Socket->response['status']['code']), LOG_ERR) ; 
  	  return array('code' => $this->Socket->response['status']['code']) ;
    }
    
  	return $result ;
  }
}
