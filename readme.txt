=== WPMU Plugin Stats ===
Contributors: DeannaS, kgraeme, MadtownLems, cfoellmann
Tags: WPMU, Wordpress Mu, Wordpress Multiuser, Plugin Stats , multisite, network
Requires at least: 2.8.0
Tested up to: 2.1
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides info to site admins as to which plugins are activated sitewide, and which blogs plugins are activated on.

== Description ==

This plugin provides a snapshot view of which blogs are using any particular plugin. 
Because the time to generate stats can be quite large, network plugin useage is cached and can be regenerated anytime via the "Regenerate" button.

= Development =

* GitHub Repository: [wpmu-plugin-stats](https://github.com/wp-repository/wpmu-plugin-stats)
* Issue-Tracker: [WPMU Plugin Stats Issues](https://github.com/wp-repository/wpmu-theme-usage-info/issues) **Please use the Issue-Tracker at GitHub!!**
* Translation: [Translate > WPMU Plugin Stats](http://wp-translate.org/projects/wpmu-plugin-stats)

== Installation ==

1. Install by searching "WPMU Plugin Stats" on Plugins > Add New > Search
2. Activate by clicking "Network Activate"

== Screenshots ==

1. Adminstrator view of list of plugins installed.

== Upgrade Notice ==

**ATTENTION:**
When you update to version 2.1 the plugin gets deactivated automatically.
You need to reactivate by clicking "Network Activate". No data is lost.

== Changelog ==
= 2.1 =
 * Updated tablesort.js (to 2.5)
 * 

= 2.0.1 =
 * fix for sites with empty title

= 2.0 =
 * added some hooks
 * testing for WP 3.8+ (Trunk: 3.9-alpha)
 * removal of build tests for now
 * removed support for all external plugins for now
 * added cleanup of settings on removal (via uninstall.php)

= 1.6 =
* added Spanish translation by Eduardo Larequi (https://github.com/elarequi)

= 1.5 =
* translation support
* fixes for WP 3.5
* fix + update of tablesort js library

= 1.3.2 =
* minor cleanups, should work with 3.4.2, and we'll go from here with better support!

= 1.2 =
* updated for new network admin menu in 3.1, eliminated use of plugin on less than WP 3.0

= 1.1 =
* minor tweak to eliminate content shift