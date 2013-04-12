<?php

/**
 * @pakage PiwikCounter
 */

class PiwikCounterAdministration {

	function __construct()
	{
		add_action('admin_menu', array( &$this, 'getAdminMenu' ) );
	}

	public function getAdminMenu()
	{
		add_options_page('PiwikCounter Options', 'PiwikCounter', 'manage_options', 'piwik-counter-administration', array( &$this, 'getAdminOptions') );
	}
	
	public function getAdminOptions()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.', 'piwikcounter') );
		}
	
		if( isset($_POST['unique_visitors']) ) {

			update_option( 'piwikcounter_piwik_url', $_POST[ 'piwik_url' ] );
			update_option( 'piwikcounter_site_id', $_POST[ 'site_id' ] );
			update_option( 'piwikcounter_auth_key', $_POST[ 'auth_key' ] );
			update_option( 'piwikcounter_start_date', $_POST[ 'start_date' ] );
			update_option( 'piwikcounter_unique_visitors', $_POST[ 'unique_visitors' ] );
			
			if ( $this->validateInt( $_POST[ 'update_interval' ], 0, 60 ) == $_POST[ 'update_interval' ] )
			{
				update_option( 'piwikcounter_update_every', $_POST[ 'update_interval' ] );
			}
			
			if (isset($_POST[ 'visits_today_visible' ]) &&  ($_POST[ 'visits_today_visible' ] == 1)) {
				update_option( 'piwikcounter_visits_today_visible', $_POST[ 'visits_today_visible' ] );
			}
			else
			{
				update_option( 'piwikcounter_visits_today_visible', 0 );
			}
			
			$visitors = new Visitors();
			$visitors->updateYesterdayVisitors();
			
			?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
			<?php

		}
				
		echo '<div class="wrap">';
		echo '<div id="icon-options-general" class="icon32"><br /></div>';
		echo "<h2>" . __( 'PiwikCounter Settings', 'piwikcounter' ) . "</h2>";
		
		?>
				
		<form name="piwik_counter_administration" method="post" action="">
		
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e("Piwik installation", 'piwikcounter'); ?></th>
					<td>
						<input type="text" size="40" name="piwik_url" value="<?php echo get_option('piwikcounter_piwik_url') ?>" /> (http://domain.tld)
					</td>
				</tr>
		
				<tr valign="top">
					<th scope="row"><?php _e("Site ID", 'piwikcounter'); ?></th>
					<td>
						<input type="text" size="40" name="site_id" value="<?php echo get_option('piwikcounter_site_id') ?>" />
					</td>
				</tr>
		
				<tr valign="top">
					<th scope="row"><?php _e("Authorisation Key", 'piwikcounter'); ?></th>
					<td>
						<input type="text" size="40" name="auth_key" value="<?php echo get_option('piwikcounter_auth_key') ?>" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e("Start date", 'piwikcounter'); ?></th>
					<td>
						<input type="text" size="10" name="start_date" value="<?php echo get_option('piwikcounter_start_date') ?>" /> (YYYY-MM-DD)
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e("Type of visits", 'piwikcounter'); ?></th>
					<td>
						<input type="radio" name="unique_visitors" value="1" 
							<?php if (get_option('piwikcounter_unique_visitors') == 1) { echo 'checked="checked"'; } ?> /> unique visitors<br />
						<input type="radio" name="unique_visitors" value="0" 
							<?php if (get_option('piwikcounter_unique_visitors') == 0) { echo 'checked="checked"'; } ?> /> all visits
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e("Update interval", 'piwikcounter'); ?></th>
					<td>
						<input type="text" size="4" name="update_interval" value="<?php echo get_option('piwikcounter_update_every') ?>" /> (time in minutes between 0 and 60)
					</td>
				</tr>
		
				<tr valign="top">
					<th scope="row"><?php _e("Todays visitors", 'piwikcounter'); ?></th>
					<td>
						<input type="checkbox" name="visits_today_visible" value="1" 
							<?php if (get_option('piwikcounter_visits_today_visible') == 1) { echo 'checked="checked"'; } ?> /> 
							<?php _e("Show todays visitors", 'piwikcounter'); ?>
					</td>
				</tr>
			</table>
			
		<p><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>

		</form>
		
		</div>
		<?php
	}
	
	private function validateInt($value, $min_range = null, $max_range = null) {
		
		if ($min_range != null AND $max_range != null) {
			$int_options = array("options"=>array("min_range"=>$min_range, "max_range"=>$max_range));
		} 
		else if ($min_range == null AND $max_range != null) {
			$int_options = array("options"=>array("max_range"=>$max_range));
		}
		else if ($min_range != null AND $max_range == null) {
			$int_options = array("options"=>array("min_range"=>$min_range));
		}

		return filter_var($value, FILTER_VALIDATE_INT, $int_options);
	}

}

?>