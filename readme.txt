=== BuddyPress Usernames Only ===
Contributors: r-a-y
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6F2EM2BPQ2DS
Tags: buddypress, username, usernames
Requires at least: WP 3.0 & BuddyPress 1.5
Tested up to: WP 3.5.x & BuddyPress 1.7
Stable tag: 0.6

Override display names across your BuddyPress site with usernames.

== Description ==

This plugin overrides display names across your BuddyPress site with usernames. The plugin will make your BP install more intuitive and user-friendly when using the @mentions feature.


== Installation ==

#### This plugin requires BuddyPress 1.5 ####

1. Download, install and activate the plugin.
1. If you're using network mode and you have enabled user blogs, network-activate the plugin.

== Frequently Asked Questions ==

#### I still see some display names on my BP site! ####

* If you're using WP 3.0 in network mode and you're seeing display names on blog comments, please try following step 2 in the installation instructions.

The only place you should see a user's display name is on a member's profile, other than that if you notice a display name on any other page on BuddyPress, please let me know!


#### Internal configuration ####

By default, display names are enabled in the member header on profile pages.  If you prefer usernames to be shown, add the following snippet to your wp-config.php file:

`define( 'BP_SHOW_DISPLAYNAME_ON_PROFILE', false );`


== Donate! ==

If you downloaded this plugin and like it, please:

* [Donate!](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6F2EM2BPQ2DS)  
* Rate this plugin
* Spread the gospel of BuddyPress


== Changelog ==

= 0.6 =
* Requires at least BuddyPress 1.5
* Performance enhancements
* Add support for BP username compatibility mode
* Add support for WP Toolbar
* Add support for bbPress
* Various fixes
* Thanks to the Hamilton-Wentworth District School Board for sponsoring this release

= 0.58 =
* Fix unique identifier block on member profiles again! (thanks to rossagrant and erich73 for reporting)

= 0.57 =
* Fix unique identifier block on member profiles (thanks to mrjarbenne for reporting)

= 0.56 =
* Fix username overlap (thanks to fzncloud and nuprn1 for reporting)
* Replace display name in `<title>` tag (thanks to meini for reporting)

= 0.55 =
* Added button support for the [BuddyPress Followers](http://wordpress.org/extend/plugins/buddypress-followers) plugin

= 0.54 =
* Fix multiple replacement in activity updates again! (thanks to intimez for reporting)

= 0.53 =
* Removed bp_member_name filter (fixes compatibility with [Welcome Pack](http://wordpress.org/extend/plugins/welcome-pack))
* Tagged compatibility with BP 1.2.4
* Removed packaged replacement files as core hacks are no longer needed

= 0.52 =
* Show display name in member profiles by default
* Fix multiple replacement in activity updates and RSS feeds (thanks to ekawaii for reporting)
* Fix comment author usernames (thanks to piphut and intimez for reporting)
* Added modified core files to the plugin


= 0.51 =
* Forgot to uncomment a filter! D'oh!

= 0.5 =
* First version!