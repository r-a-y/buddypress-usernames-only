<?php
/*
Plugin Name: BuddyPress Usernames Only
Description: Override display names across your BuddyPress site with usernames.
Author: r-a-y
Author URI: http://buddypress.org/community/members/r-a-y
Plugin URI: http://wordpress.org/extend/plugins/buddypress-usernames-only
Version: 0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6F2EM2BPQ2DS
*/

/**
 * BP Usernames Only
 *
 * @package BP-Usernames-Only
 * @subpackage Loader
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Only load the plugin code if BuddyPress is activated.
 */
function bp_usernames_init() {
	// only supported in BP 1.5
	if ( version_compare( BP_VERSION, '1.3', '>' ) ) {
		require( dirname( __FILE__ ) . '/bp-usernames-only.php' );

	// admin notice for BP 1.2.x
	} else {
		add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>' . __( \"BuddyPress Usernames Only v0.6 is only supported in BuddyPress 1.5+. If you want to continue using BuddyPress 1.2, please downgrade back to BP Usernames Only v0.58.\", 'bp-usernames-only' ) . '</p></div>';" ) );
		return;	
	}		
}
// hooked to priority 6 so the plugin can intercept bp_setup_nav() at priority 7
add_action( 'bp_init', 'bp_usernames_init', 6 );
