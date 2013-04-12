<?php

/**
 * @pakage PiwikCounter
 */

class PiwikCounterWidget extends WP_Widget {
	
	function PiwikCounterWidget() 
	{
		parent::WP_Widget(false, $name = 'PiwikCounter');
	}

	function form($instance) 
	{
		$title = esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
	}

	function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
	}

	function widget($args, $instance) 
	{
		extract( $args );
        
		$visitors = new Visitors();
		
		// Update piwikcounter_visitors_yesterday if date of last change and actual date don't match
		if ( strtotime(get_option('piwikcounter_visitors_last_change')) !== mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) {
				$visitors->updateYesterdayVisitors();
		}
		
		// Get today's visits
		$visits_today = (int) $visitors->getTodaysVisitors();
		$all_visits = (int) $visits_today + (int) get_option('piwikcounter_visitors_yesterday');
		
		$title = apply_filters('widget_title', $instance['title']);
		
		echo $before_widget;
		
		echo '<!-- PiwikCounter -->';
		
        if ( $title ) { echo $before_title . $title . $after_title; }
		
		echo '<div id="piwikcounter_widget" style="text-align: center;">';
		
		// Output complete amount of visitors since the specified date
		if($all_visits == 0)
		{
			echo __("0<br/>visitors since", 'piwikcounter');
		}
		else
		{
			printf( _n( "%d<br/>visitor since", "%d<br/>visitors since", $all_visits, 'piwikcounter' ), $all_visits);	
		}

		setlocale(LC_TIME, WPLANG);
		echo strftime(" %B %Y", strtotime(get_option('piwikcounter_start_date'))) .'<br/>';
		//echo date(" F Y", strtotime(get_option('piwikcounter_start_date')) ) .'<br/>';
		
		// Output amount of visitors who came today
		if(get_option("piwikcounter_visits_today_visible") == 1)
		{
			if($visits_today == 0)
			{
				echo __("0<br/>visits today", 'piwikcounter');
			}
			else
			{
				printf(_n("%d<br/>visit today", "%d<br/>visits today", $visits_today, 'piwikcounter'), $visits_today);
			}
		}
		
		echo '</div>';
	
		echo '<!-- End PiwikCounter -->';
		echo $after_widget;
	}

}

?>