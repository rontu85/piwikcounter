<?php

/**
 * @pakage PiwikCounter
 */

/*
Plugin Name: PiwikCounter
Plugin URI: http://piwikcounter.rontu.de
Description: PiwikCounter allows you to show the summary of (unique) visitors as a widget on your blog.
Version: 0.3.0
Author: Tobias Etzold
Email: tobias.etzold@googlemail.com
Author URI: http://blog.rontu.de
License: GPLv2 or later
Text Domain: piwikcounter
*/

$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'piwikcounter', false, $plugin_dir.'/language/' );

require_once 'class.Visitors.php';
require_once 'class.PiwikCounterWidget.php';
require_once 'class.PiwikCounterAdministration.php';

// options for PiwikCounter
add_option( 'piwikcounter_piwik_url', '', '', 'yes' );				// URL of your Piwik installation
add_option( 'piwikcounter_site_id', '', '', 'yes' );				// identification number of your tracked website
add_option( 'piwikcounter_auth_key', '', '', 'yes' );				// your authorisation key for piwik with viewing rights for the specified site id
add_option( 'piwikcounter_start_date', '', '', 'yes' );				// date when tracking in piwik began (date YYYY-MM-DD)
add_option( 'piwikcounter_unique_visitors', '', '', 'yes' );		// all visitors or unique visitors (bool)
add_option( 'piwikcounter_visitors_yesterday', '', '', 'yes' );		// quantity of visitors until yesterday
add_option( 'piwikcounter_visitors_last_change', '2000-01-01', '', 'yes' );	// last modification date of piwikcounter_visitors_yesterday (date YYYY-MM-DD)
add_option( 'piwikcounter_visits_today_visible', '1', '', 'yes' );	// enables visability of visits for today
add_option( 'piwikcounter_todays_visitors', '', '', 'yes' );		// cached amount of visitors for today
add_option( 'piwikcounter_todays_visitors_last_change', '0000000001', '', 'yes' ); // last modification of piwikcounter_todays_visitors (timestamp)
add_option( 'piwikcounter_update_every', '5', '', 'yes' );			// time in minutes between updates of todays visitors

$pca = new PiwikCounterAdministration();

//Actions und Filter
add_action('widgets_init', create_function('', 'return register_widget("PiwikCounterWidget");'));

?>