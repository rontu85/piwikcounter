<?php

/**
 * @pakage PiwikCounter
 */

class Visitors {

	private function getVisitors($start_date, $piwik_url, $site_id, $api_key, $unique = 0) {
	
		if ($piwik_url != null) {
		
			if ( $unique == 0) {
				$method = 'VisitsSummary.getVisits';
			}
			else {
				$method = 'VisitsSummary.getUniqueVisitors';
			}
		
			if ($start_date != null) {
				$end_date = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
				
				$url = $piwik_url .'/index.php?module=API&method=' .$method .'&idSite=' 
					.(int) $site_id .'&period=range&date=' 
					.$start_date .',' .$end_date 
					.'&format=xml&token_auth=' .$api_key;
			}
			else {
				$url = $piwik_url .'/index.php?module=API&method=' .$method .'&idSite='
					.(int) $site_id .'&period=day&date=today'
					.'&format=xml&token_auth=' .$api_key;
			}
				
			$xml_reader = new XMLReader();
				
			try 
			{ 
				$xml_reader->open($url, "UTF-8");
				$xml_reader->read();
				if ($xml_reader->name === "result")
				{
					return $xml_reader->readInnerXML();
				}
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
			
			$xml_reader->close();
			
		}
	}
	
	public function updateYesterdayVisitors() {
		// Get Visitors from Piwik
		$visitors_yesterday = $this->getVisitors( get_option('piwikcounter_start_date'), get_option('piwikcounter_piwik_url'), get_option('piwikcounter_site_id'), get_option('piwikcounter_auth_key'), get_option('piwikcounter_unique_visitors') );
		
		// Update
		if ( get_option( 'piwikcounter_visitors_yesterday' ) < $visitors_yesterday )
		{
			update_option( 'piwikcounter_visitors_yesterday', $visitors_yesterday );	
			update_option( 'piwikcounter_visitors_last_change', date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y"))) );
		}
	}
	
	public function getTodaysVisitors() {
		
		if ( ($this->minTimeDiffReached( get_option('piwikcounter_todays_visitors_last_change'), get_option('piwikcounter_update_every')) ) )
		{
			update_option( 'piwikcounter_todays_visitors_last_change', time() );
			update_option( 'piwikcounter_todays_visitors', $this->getVisitors( null, get_option('piwikcounter_piwik_url'), get_option('piwikcounter_site_id'), get_option('piwikcounter_auth_key'), get_option('piwikcounter_unique_visitors')) );
		}
	
		return (int) get_option('piwikcounter_todays_visitors');
	
	}
	
	// check the minimum time between two timestamps
	private function minTimeDiffReached($timestamp, $timedifference)
	{
		if ( time()-((int) $timedifference * 60) > (int) $timestamp )
		{
			return true;
		}	
		return false;
	}
	
}

?>