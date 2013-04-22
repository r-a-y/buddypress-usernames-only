<?php
/**
 * BP Usernames Only Core
 *
 * @package BP-Usernames-Only
 * @subpackage Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Set 'BP_SHOW_DISPLAYNAME_ON_PROFILE' constant if not already defined
if( ! defined( 'BP_SHOW_DISPLAYNAME_ON_PROFILE' ) ) {
	define( 'BP_SHOW_DISPLAYNAME_ON_PROFILE', true );
}

	if( (bool) BP_SHOW_DISPLAYNAME_ON_PROFILE === true ) {
		// only show the display name for the <h1> tag on a member profile
		add_action( 'bp_before_member_header', create_function( '',
				'remove_filter( "bp_displayed_user_fullname", "ray_bp_displayed_user_fullname" );'
		), 99 );

		// add back the username filter
		add_action( 'bp_before_member_header_meta', create_function( '',
				'add_filter( "bp_displayed_user_fullname",    "ray_bp_displayed_user_fullname" );'
		), 0 );

		// support for BP Lists
		if ( function_exists( 'bp_is_lists_component' ) && bp_is_lists_component() && bp_is_single_item() )
			add_filter( 'bp_displayed_user_fullname', 'ray_bp_displayed_user_fullname' );

	} else {
		add_filter( 'bp_displayed_user_fullname', 'ray_bp_displayed_user_fullname' );
	}


/* UTILITY *********************************************************/

/**
 * Returns either the username or user nicename depending on the BuddyPress
 * username compatibility mode.
 *
 * @since 0.6
 *
 * @param obj $userdata Object The userdata that includes the user_login and user_nicename.
 * @mixed String on success; boolean false on failure
 */
function ray_bp_username_compatibility( $userdata ) {
	if ( empty( $userdata ) )
		return false;

	if ( bp_is_username_compatibility_mode() )
		return $userdata->user_login;

	return $userdata->user_nicename;
}

if ( ! function_exists( 'str_replace_first' ) ) :
/**
 * Replaces the first match in a target string.
 *
 * @since 0.6
 *
 * @param string $search The text we want to get rid of
 * @param string $replace The text we want to replace with
 * @param string $subject The string we want to do the replacing with
 */
function str_replace_first( $search, $replace, $subject ) {
	$pos = strpos( $subject, $search );

	if ( $pos !== false ) {
		$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
	}

	return $subject;
}
endif;

/**
 * Replaces the first occurence of anchor text in a string.
 *
 * @since 0.6
 *
 * @param string $subject The string we want to replace the first occurence of anchor text for.
 * @param string $replacement The anchor text we want to replace with
 * @return string
 */
function ray_replace_first_anchor_text( $subject, $replacement ) {
	$before_anchor_text = strpos( $subject, '">' ) + 2;
	$after_anchor_text  = strpos( $subject, '</a' );

	return substr_replace( $subject, $replacement, $before_anchor_text, $after_anchor_text - $before_anchor_text );
}

/**
 * Fix first instance of anchor element to use double quotations instead of
 * single quotations in anchor tag.
 *
 * This is not perfect.
 *
 * @since 0.6
 *
 * @param string $content
 * @return string
 */
function ray_fix_first_anchor( $content ) {
	$content = str_replace_first( "href='", 'href="', $content );
	$content = str_replace_first( "'>",     '">',     $content );

	return $content;
}


/* CORE OVERRIDES **************************************************/

function ray_bp_core_get_userlink( $link, $user_id ) {
	global $bp;

	if ( bp_loggedin_user_id() == $user_id ) {
		$displayed_user = $bp->loggedin_user->userdata;
	} elseif ( bp_displayed_user_id() == $user_id ) {
		$displayed_user = $bp->displayed_user->userdata;
	} else {
		if ( empty( $bp->usernames_only->userdata ) ) {
			$bp->usernames_only = new stdClass;
			$bp->usernames_only->userdata = array();
		}

		$displayed_user = false;

		// try to get locally-cached value first
		if ( ! empty( $bp->usernames_only->userdata[$user_id] ) ) {
			$displayed_user = $bp->usernames_only->userdata[$user_id];
		}

		// no cached value, so query for it
		if ( $displayed_user === false ) {
			$displayed_user = bp_core_get_core_userdata( $user_id );

			// cache it for later use
			$bp->usernames_only->userdata[$user_id] = $displayed_user;
		}

	}

	return ray_replace_first_anchor_text( $link, ray_bp_username_compatibility( $displayed_user ) );
}
add_filter( 'bp_core_get_userlink', 'ray_bp_core_get_userlink', 1, 2 );

/**
 * Used in member profile header.
 */
function ray_bp_displayed_user_fullname() {
	global $bp;

	return ray_bp_username_compatibility( $bp->displayed_user->userdata );
}

/**
 * Used in private messages (sent between blah and x)
 */
function ray_bp_get_loggedin_user_fullname() {
	global $bp;

	return ray_bp_username_compatibility( $bp->loggedin_user->userdata );
}
add_filter( 'bp_get_loggedin_user_fullname', 'ray_bp_get_loggedin_user_fullname' );

/**
 * Used for group invite friends list
 */
function ray_bp_get_member_name( $name ) {
	global $members_template;

	return ray_bp_username_compatibility( $members_template->member );
}
add_filter( 'bp_get_member_name' , 'ray_bp_get_member_name' );

/**
 * Used in a lot of places
 * - email notifications
 * - messages subnav (From: x)
 * - private messages (sent between x and blah)
 */
function ray_bp_core_get_user_displayname( $name, $user_id ) {
	global $bp;

	if ( bp_loggedin_user_id() == $user_id ) {
		$displayed_user = $bp->loggedin_user->userdata;
	} elseif ( bp_displayed_user_id() == $user_id ) {
		$displayed_user = $bp->displayed_user->userdata;
	} else {
		if ( empty( $bp->usernames_only->userdata ) ) {
			$bp->usernames_only = new stdClass;
			$bp->usernames_only->userdata = array();
		}

		$displayed_user = false;

		// try to get locally-cached value first
		if ( ! empty( $bp->usernames_only->userdata[$user_id] ) ) {
			$displayed_user = $bp->usernames_only->userdata[$user_id];
		}

		// no cached value, so query for it
		if ( $displayed_user === false ) {
			$displayed_user = bp_core_get_core_userdata( $user_id );

			// cache it for later use
			$bp->usernames_only->userdata[$user_id] = $displayed_user;
		}
	}

	return ray_bp_username_compatibility( $displayed_user );
}
add_filter( 'bp_core_get_user_displayname', 'ray_bp_core_get_user_displayname', 1, 2 );

/**
 * Change BP followers members listing
 */
function ray_bp_get_user_firstname( $name ) {
	global $members_template;

	if( ! empty( $members_template->member->user_login ) )
		return ray_bp_username_compatibility( $members_template->member );

	if ( bp_displayed_user_id() ) {
		global $bp;

		return ray_bp_username_compatibility( $bp->displayed_user->userdata );
	}

	return $name;
}
add_filter( 'bp_get_user_firstname' , 'ray_bp_get_user_firstname' );

/**
 * Change "What's new, username" in post form
 *
 * @since 0.6
 */
function ray_bp_whats_new_firstname( $name ) {
	if ( bp_loggedin_user_id() ) {
		global $bp;

		return ray_bp_username_compatibility( $bp->loggedin_user->userdata );
	}

	return $name;
}
add_action( 'bp_before_activity_post_form', create_function( '',
		'add_filter( "bp_get_user_firstname",    "ray_bp_whats_new_firstname" );'
), 99 );

add_action( 'bp_activity_post_form_options', create_function( '',
		'remove_filter( "bp_get_user_firstname", "ray_bp_whats_new_firstname" );'
), 0 );

/**
 * Used in <title> tag
 */
function ray_bp_page_title( $title, $sep ) {
	if ( bp_displayed_user_id() ) {
		global $bp;

		$title = str_replace(
			$bp->displayed_user->fullname . ' ' . $sep,
			ray_bp_username_compatibility( $bp->displayed_user->userdata ) . ' ' . $sep,
			$title
		);
	}

	return $title;
}
add_filter( 'wp_title', 'ray_bp_page_title', 20, 2 );


/* GROUP OVERRIDES *************************************************/

/**
 * Used in group member listing
 */
function ray_bp_get_group_member_link() {
	global $members_template;

	return '<a href="' . bp_core_get_user_domain( $members_template->member->user_id, $members_template->member->user_nicename, $members_template->member->user_login ) . '">' . ray_bp_username_compatibility( $members_template->member ) . '</a>';
}
add_filter( 'bp_get_group_member_link', 'ray_bp_get_group_member_link' );


/* ACTIVITY OVERRIDES **********************************************/

/**
 * Fix first instance of activity action to use double quotations instead of
 * single quotations in anchor tag.
 *
 * Runs at priority 0; just before ray_bp_get_activity_action().
 *
 * @since 0.6
 */
function ray_bp_fix_activity_action( $action ) {
	return ray_fix_first_anchor( $action );
}

/**
 * Used in parent activity update
 */
function ray_bp_get_activity_action( $action, $activity ) {
	return ray_replace_first_anchor_text( $action, ray_bp_username_compatibility( $activity ) );
}
// bug in BP 1.7; activity admin page is missing the other filter parameters
// for this hook, so we only add this hook on the frontend or during AJAX.
//
// for the AJAX check, we're checking HTTP_X_REQUESTED_WITH b/c BP 1.5 doesn't
// use admin-ajax.php.
if ( ! is_admin() || ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) ) {
	add_filter( 'bp_get_activity_action', 'ray_bp_fix_activity_action', 0 );
	add_filter( 'bp_get_activity_action', 'ray_bp_get_activity_action', 1, 2 );
}

/**
 * Replaces username in the "In reply to" activity item.
 *
 * This is new in BuddyPress 1.5.
 * See /bp-themes/bp-default/activity/entry.php.
 *
 * @since 0.6
 */
function ray_bp_get_activity_parent_content( $content ) {
	global $activities_template;

	$parent_id = $activities_template->activity->item_id;

	return ray_replace_first_anchor_text( $content, ray_bp_username_compatibility( $activities_template->activity_parents[$parent_id] ) );
}
add_filter( 'bp_get_activity_parent_content', 'ray_bp_get_activity_parent_content' );

/**
 * Used in activity comments
 */
function ray_bp_acomment_name( $name, $comment ) {
	return ray_bp_username_compatibility( $comment );
}
add_filter( 'bp_acomment_name' , 'ray_bp_acomment_name', 1, 2 );

/**
 * RSS feed title
 */
function ray_bp_get_activity_feed_item_title( $title ) {
	global $activities_template;

	return stripslashes( str_replace_first( $activities_template->activity->user_fullname, ray_bp_username_compatibility( $activities_template->activity ), $title ) );
}
add_filter( 'bp_get_activity_feed_item_title', 'ray_bp_get_activity_feed_item_title' );


/* FORUM OVERRIDES *************************************************/

/**
 * Used in forum topics
 */
function ray_bp_get_the_topic_post_poster_name( $name ) {
	global $topic_template;

	if ( bp_is_username_compatibility_mode() ) {
		$username = $topic_template->post->poster_login;
	} else {
		$username = $topic_template->post->poster_nicename;
	}

	return ray_replace_first_anchor_text( $name, $username );
}
add_filter( 'bp_get_the_topic_post_poster_name' , 'ray_bp_get_the_topic_post_poster_name' );

/**
 * Used in forum directory loop
 */
function ray_bp_get_the_topic_last_poster_name( $name ) {
	global $forum_template;

	if ( bp_is_username_compatibility_mode() ) {
		$username = $forum_template->topic->topic_last_poster_login;
	} else {
		$username = $forum_template->topic->topic_last_poster_nicename;
	}

	return ray_replace_first_anchor_text( $name, $username );
}
add_filter( 'bp_get_the_topic_last_poster_name', 'ray_bp_get_the_topic_last_poster_name' );

/**
 * Used in the bbPress plugin
 *
 * @since 0.6
 */
function ray_get_the_author_display_name( $name, $user_id ) {
	// test to see if we're on a BP group forum page or on any bbPress page
	if ( bp_is_group_forum() || (
		function_exists( 'bbpress' ) && is_bbpress()
	) ) {
		// cache username queries with static variable
		//
		// tried stuffing in $bp global but didn't work properly
		// probably due to object buffering in bbP
		static $bp_uso_data = array();

		$name = false;

		// try to get locally-cached value first
		if ( ! empty( $bp_uso_data[$user_id] ) ) {
			$name = $bp_uso_data[$user_id];
		}

		// no cached value, so query for it
		if ( $name === false ) {
			$field = bp_is_username_compatibility_mode() ? 'user_login' : 'user_nicename';

			$name = get_the_author_meta( $field, $user_id );

			// cache it for later use in the loop
			$bp_uso_data[$user_id] = $name;
		}

	}

	return $name;
}
add_filter( 'get_the_author_display_name', 'ray_get_the_author_display_name', 10, 2 );


/* MESSAGE OVERRIDES ***********************************************/

/**
 * Used in message loop
 */
function ray_bp_get_the_thread_message_sender_name() {
	global $bp, $thread_template;

	if ( bp_loggedin_user_id() == $thread_template->message->sender_id ) {
		$displayed_user = $bp->loggedin_user->userdata;
	} elseif ( bp_displayed_user_id() == $thread_template->message->sender_id ) {
		$displayed_user = $bp->displayed_user->userdata;
	} else {
		if ( empty( $bp->usernames_only->userdata ) ) {
			$bp->usernames_only = new stdClass;
			$bp->usernames_only->userdata = array();
		}

		$displayed_user = false;

		// try to get locally-cached value first
		if ( ! empty( $bp->usernames_only->userdata[$thread_template->message->sender_id] ) ) {
			$displayed_user = $bp->usernames_only->userdata[$thread_template->message->sender_id];
		}

		// no cached value, so query for it
		if ( $displayed_user === false ) {
			$displayed_user = bp_core_get_core_userdata( $thread_template->message->sender_id );

			// cache it for later use in the loop
			$bp->usernames_only->userdata[$thread_template->message->sender_id] = $displayed_user;
		}

	}

	return ray_bp_username_compatibility( $displayed_user );
}
add_filter( 'bp_get_the_thread_message_sender_name', 'ray_bp_get_the_thread_message_sender_name' );

/**
 * Override display name for ajax message reply
 *
 * Hopefully there aren't any side-effects with doing this.
 */
function ray_bp_message_reply_ajax_sent_name() {
	global $bp;

	$bp->loggedin_user->fullname = ray_bp_username_compatibility( $bp->loggedin_user->userdata );
}
add_action( 'bp_before_message_meta', 'ray_bp_message_reply_ajax_sent_name' );


/* BLOG OVERRIDES **************************************************/

/**
 * Used in comment author link
 *
 * @todo Locally-cache value. Useful in a comment loop.
 */
function ray_get_comment_author( $author ) {
	global $bp, $comment;

	if( $comment->user_id > 0 ) {
		if ( bp_loggedin_user_id() == $comment->user_id ) {
			$displayed_user = $bp->loggedin_user->userdata;
		} else {
			if ( empty( $bp->usernames_only->userdata ) ) {
				$bp->usernames_only = new stdClass;
				$bp->usernames_only->userdata = array();
			}

			$displayed_user = false;

			// try to get locally-cached value first
			if ( ! empty( $bp->usernames_only->userdata[$comment->user_id] ) ) {
				$displayed_user = $bp->usernames_only->userdata[$comment->user_id];
			}

			// no cached value, so query for it
			if ( $displayed_user === false ) {
				$displayed_user = bp_core_get_core_userdata( $comment->user_id );

				// cache it for later use in the loop
				$bp->usernames_only->userdata[$comment->user_id] = $displayed_user;
			}
		}

		return ray_bp_username_compatibility( $displayed_user );

	} else {
		return $author;
	}
}
add_filter( 'get_comment_author', 'ray_get_comment_author' );


/* ADMIN BAR *******************************************************/

/**
 * Replaces display name in WP Toolbar. Only takes effect in WP 3.3+.
 *
 * Hacks the $current_user global before the "My Account" menu is output.
 *
 * @since 0.6
 */
function ray_wp_toolbar_title( $wp_admin_bar ) {
	global $current_user, $bp;

	$current_user->display_name = ray_bp_username_compatibility( $bp->loggedin_user->userdata );
}
add_action( 'admin_bar_menu', 'ray_wp_toolbar_title', 6 );
