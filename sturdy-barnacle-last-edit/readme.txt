=== SB Show Last Edit Date ===
Contributors: nynomnom
Donate link: https://ko-fi.com/kristinaq
Tags: last edit date, last modified, last updated, meta box, options page
Requires at least: 4.7
Tested up to: 6.7
Stable tag: 1.0.1
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Display the last date and time a post or page was updated.

== Description ==

SB Show Last Edit Date is a WordPress plugin that allows you to display the last date and time a post or page was updated. It provides a customizable meta box on post and page editing screens to enable or disable the display of last edit information. You can choose the position of the last edit info within the content and even globally disable it for all posts and/or pages through the plugin's settings page.

Features:

- Display the last updated information for posts and pages.
- Customizable position of last edit info within the content (before or after).
- Option to globally disable last edit info for all posts and/or pages.
- Individual control per post/page to override global settings.
- Proper timezone support based on your WordPress settings.

== Installation ==

1. Upload the `sb-show-last-edit-date` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Customize the plugin settings under 'Settings' -> 'SB Show Last Edit Date'.

== Frequently Asked Questions ==

= How do I disable the last edit info for a specific post or page? =

On the post or page editing screen, you'll find a meta box labeled 'SB Show Last Edit Date'. Check the 'Disable Last Edit Info' checkbox to disable the last edit info for that specific post or page.

= How do I change the position of the last edit info within the content? =

In the plugin's settings page, you can choose the position of the last edit info. It can be displayed either before or after the content.

= Can I globally disable the last edit info for all posts and/or pages? =

Yes, the plugin provides an options page where you can globally disable the last edit info for all posts and/or pages.

= What if my date and time are not displayed correctly? =

The plugin uses your WordPress timezone settings. Make sure you have the correct timezone set in Settings > General.

== Changelog ==

= 1.0.1 =
* Added UI option to control position (before/after content)
* Enhanced input sanitization and validation for improved security
* Added proper text domain loading for translations
* Added WordPress version compatibility check
* Fixed plugin_action_links implementation
* Improved error handling with try/catch for date operations
* Added WP_DEBUG support for error logging
* Improved meta box with global settings information
* Updated compatibility to WordPress 6.7

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.1 =
This update adds new configuration options, improves security, and ensures compatibility with WordPress 6.7.

= 1.0.0 =
Initial release.