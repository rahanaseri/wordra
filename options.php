<?php
/*
Plugin Name: wordra Plugin
Description: A plugin to upload and display videos using cassandra database
Author: Raha Naseri
Version: 0.1
Company Name: Big Industries
*/
require_once 'list.php';
require_once 'rg.php';

 // add the admin options page
add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
add_options_page('wordra plugin Page', 'wordra Plugin Settings', 'manage_options', 'plugin', 'plugin_options_page');
}
?>
<?php // display the admin options page
function plugin_options_page() {
?>
<div>
<form action="options.php" method="post">
<?php settings_fields('plugin_options'); ?>
<?php do_settings_sections('plugin'); ?>

<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form></div>

<?php
}?>
<?php
function wordra_settings_api_init() {
// Add the section to our setting page so we can add our fields to it
add_settings_section(
'wordra_setting_section',
'Setting',
'wordra_setting_section_callback_function',
'plugin'
);

// Add the field with the names and function to use for our new settings, put it in our new section
add_settings_field(
'wordra_setting_name',
'IPaddress',
'wordra_setting_callback_function',
'plugin',
'wordra_setting_section'
);

// Register our setting in our setting page
register_setting( 'plugin_options', 'wordra_setting_name' );
}

add_action( 'admin_init', 'wordra_settings_api_init' );

/*
 * Settings section callback function
 */

function wordra_setting_section_callback_function() {
$IpAddress = get_option( 'wordra_setting_name', false );
echo '<p>the current value of database IPaddress is: '.$IpAddress.'<p>';
}

/*
 * Callback function for our example setting
 */

function wordra_setting_callback_function() {
$IpAddress = esc_attr( get_option( 'wordra_setting_name' ) );
echo "<input type='text' name='wordra_setting_name' value='$IpAddress' />";
}

