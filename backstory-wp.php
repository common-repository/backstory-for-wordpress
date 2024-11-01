<?php
/*
Plugin Name: Backstory for WP
Plugin URI: http://getbackstory.com
Description: This plugin makes it super simple to use Backstory on your blog.
Version: 0.4a
Author: Scrimble Inc
Author URI: http://getbackstory.com
*/

function backstory_footer_add() {
	$backstory_pid = get_option('backstory_pid');
	if ($backstory_pid != '') {
    	echo '<script type="text/javascript">var pid = \'' . $backstory_pid . '\';</script><script type="text/javascript" src="http://alpha.getbackstory.com/gbs_setup_1_2.js"></script>';
	}
	else {
		echo '<script type="text/javascript" src="http://alpha.getbackstory.com/gbs_setup_1_2.js"></script>';
	}
}

function backstory_add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=backstory-settings">'.__("Settings", "backstory-settings").'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

function backstory_add_options() {
    $backstory_admin_page = add_options_page('Backstory Settings', 'Backstory', 'manage_options', 'backstory-settings', 'backstory_options');
	add_action('load-' . $backstory_admin_page, 'backstory_load_function');
}

function backstory_load_function() {
	remove_action('admin_notices', 'backstory_admin_notices');
}

function backstory_admin_notices() {
	echo "<div id='notice' class='updated fade'><p>Backstory is not configured yet. <a href='options-general.php?page=backstory-settings'>Click here</a> to set it up.</p></div>\n";
}

function backstory_options() {
	//must check that the user has the required capability 
	if (!current_user_can('manage_options'))
	{
	  wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	$backstory_pid = get_option('backstory_pid');

	if (isset($_POST['backstory_pid']) && $_POST['backstory_submitted'] == 'Y') {
		// Read their posted value
		$backstory_pid = $_POST['backstory_pid'];
		
		// Save the posted value in the database
		update_option('backstory_pid', $backstory_pid);
	}

	?>
	<div class="wrap">
    	<h2>Backstory Settings</h2>

	<?php
		if ($_REQUEST['backstory_edit'] == '1') {
		?>

			<form name="backstory" method="post" action="options-general.php?page=backstory-settings"; ?>">
			<input type="hidden" name="backstory_submitted" value="Y">
			
			<p>Your Backstory PID:
			<input type="text" name="backstory_pid" value="<?php echo $backstory_pid; ?>" size="10"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>
			</form>
			<p><hr /></p>
			<h4>To get your PID, create an account or log into the Backstory dashboard below. Once you're there, you can find the PID on the "Domains" tab.</h4>
			<p><iframe src="http://getbackstory.com" width="100%" height="425"></iframe></p>

		<?php
		}
		elseif ($backstory_pid == '') {
		?>

			<form name="backstory" method="post" action="options-general.php?page=backstory-settings"; ?>">
			<input type="hidden" name="backstory_submitted" value="Y">
			
			<p>Your Backstory PID:
			<input type="text" name="backstory_pid" value="<?php echo $backstory_pid; ?>" size="10"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>
			</form>
			<p><hr /></p>
			<h4>To get your PID, create an account or log into the Backstory dashboard below. Once you're there, you can find the PID on the "Domains" tab.</h4>
			<p><iframe src="http://getbackstory.com" width="100%" height="425"></iframe></p>

		<?php
		}
		else {
		?>
			<h3 style="top: -15px; position: relative; margin-bottom: -10px;">Your PID: <?php echo $backstory_pid; ?> <a href="options-general.php?page=backstory-settings&backstory_edit=1">(edit this)</a></h3>
			<p><iframe src="http://alpha.getbackstory.com" width="100%" height="425"></iframe></p>
		<?php
		}
		?>

		</div>

	<?php

}

add_action('wp_footer', 'backstory_footer_add');
add_action('admin_menu', 'backstory_add_options');
if (get_option('backstory_pid') == '') {
	add_action( 'admin_notices', 'backstory_admin_notices' );
}
add_filter('plugin_action_links', 'backstory_add_settings_link', 10, 2 );

?>