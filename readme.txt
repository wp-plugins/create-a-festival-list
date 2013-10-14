=== Custom Post type OR Festival List ===
Contributors: SP Technolab
Tags: news, custom post type, festival list, section like post, post, page
Requires at least: 3.1
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an extensible  custom post type OR  Festival List to Wordpress.

== Description ==

This plugin add a  custom post type OR  Festival List to your Wordpress site..

The plugin adds a Festival tab(you can also change the name if you want to create a custom post type. Just samll change in the code ie 'menu_name'  => __('YOUR CUSTOM POST NAME', 'sp_festivals') to your admin menu, which allows you to enter Festival Title and Description  items just as you would regular posts.
Now just create a new page and add this short code '[sp_festivals limit="-1"]'.

== Installation ==

1. Upload the 'Festivals list' folder to the '/wp-content/plugins/' directory.
2. Activate the Festivals list plugin through the 'Plugins' menu in WordPress.
3. Add a new page and add this short code "[sp_festivals limit="-1"]".


== Frequently Asked Questions ==

= What festivals list templates are available? =

There is one templates named 'festivals.php' which work like same as defult POST TYPE in wordpress.

= What's the easiest way to create my own custom version of the festivals templates? =

Just open "festivals.php" file and change the labels name under FUNCTION sp_festivals_setup_post_types { ... }

= Are there shortcodes for festivals items? =

Yes, Add a new page and add this short code '[sp_festivals limit="-1"]'.



== Screenshots ==

1. all Festivals
2. edit
3. preview

== Changelog ==

= 1.0 =
* Initial release
* Adds custom post type
* Adds festivals list


== Upgrade Notice ==

= 1.0 =
Initial release
