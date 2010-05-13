=== BuddyPress Usernames Only ===
Contributors: r-a-y
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6F2EM2BPQ2DS
Tags: buddypress, username, usernames
Requires at least: WP 2.9 & BuddyPress 1.2
Tested up to: WP 2.9.2 & BuddyPress 1.2.3
Stable tag: 0.5

Override display names across your BuddyPress site with usernames.

== Description ==

This plugin overrides display names across your BuddyPress site with usernames. The plugin will make your BP install more intuitive and user-friendly when using the @mentions feature.


== Installation ==

1. Download, install and activate the plugin.
1. If you're using WP 3.0 in network mode or WPMU and you have enabled user blogs, activate the plugin sitewide.

Also, this plugin requires two core hacks to BuddyPress:

Change line 578 in /buddypress/bp-activity-templatetags.php to:

`$content .= '<div class="acomment-meta"><a href="' . bp_core_get_user_domain( $comment->user_id, $comment->user_nicename, $comment->user_login ) . '">' . apply_filters( 'bp_acomment_name', $comment->user_fullname, $comment ) . '</a> &middot; ' . sprintf( __( '%s ago', 'buddypress' ), bp_core_time_since( strtotime( $comment->date_recorded ) ) );`

Change line 1190 in /buddypress/bp-core.php to:

`return apply_filters( 'bp_core_get_user_displayname', $fullname, $user_id );`

Line numbers mentioned above reference BuddyPress 1.2.3.


== Frequently Asked Questions ==

#### Why does this plugin require a few hacks to BuddyPress? ####

As of BuddyPress 1.2.3, the two filters that require hacking needed to pass some additional variables.

I've requested that these modifications be included in a future release of BuddyPress.


#### So why release the plugin now? ####

Due to demand ;)


#### I still see some display names on my BP site! ####

* Did you apply the core hacks listed in the installation section?

* If your members frequently changed their display name, past activity updates will still show their old display name.
New activity updates will correctly show their username.

* If you're using WP 3.0 in network mode or WPMU and you're seeing display names on blog comments, please try following step 2 in the installation instructions.

The only place you should see a user's display name is on a member's profile loop, other than that if you notice a display name on any other page on BuddyPress, please let me know!


== Donate! ==

I'm a forum moderator on the buddypress.org forums.  I spend a lot of my free time helping people - pro bono!

If you downloaded this plugin and like it, please:

* [Fund my work soundtrack!](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KU38JAZ2DW8TW)  Music helps me help you!  A dollar lets me buy a new tune off Amazon MP3, Amie Street or emusic.com!  Or if you're feeling generous, you can help me buy a whole CD!  If you choose to donate, let me know what songs or which CD you want me to listen to! :)
* Rate this plugin
* Spread the gospel of BuddyPress


== Changelog ==

= 0.5 =
* First version!