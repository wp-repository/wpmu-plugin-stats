=== WPMU Plugin Stats ===
Contributors: DeannaS, kgraeme, MadtownLems
Tags: WPMU, Wordpress Mu, Wordpress Multiuser, Plugin Stats 
Requires at least: 3.1
Tested up to: 3.5.1
Stable tag: trunk



Provides info to site admins as to which plugins are activated sitewide, and which blogs plugins are activated on. For users of <a href="http://firestats.cc/wiki/WPMUPluginCommander">Plugin Commander</a> or <a href="http://wpmudev.org/project/wpmu-plugin-manager">Plugin Manager</a>, it also provides information on which plugins are auto activated, user controlled, or supporter-controlled (for Plugin Manager). 

== Description ==
Included files:

* cets\_plugin\_stats.php
* cets\_plugin\_stats folder - lib folder - tablesort.js

This plugin provides a snapshot view of which blogs are using any particular plugin. 

For sites that are using Plugin Commander to manage plugins, additional columns for the Plugin Commander settings of Auto Activate and User Controlled are included.

For sites that are using Plugin Manager, additional columns for the Plugin Manager settings of Auto Activate, User Controlled and Supporter Controlled are included.

Because the time to generate stats can be quite large, network plugin useage is cached and can be regenerated anytime via the "Regenerate" button.

Contribute Translations at [https://translate.foe-services.de/projects/cets_plugin_stats](https://translate.foe-services.de/projects/cets_plugin_stats)

== Installation ==

1. Place the cets\_plugin\_info.php file and directory in the wp-content/mu-plugins folder.
2. In 3.1+, go to network admin -> Plugins -> Plugin Stats to view information.

== Screenshots ==

1. Adminstrator view of list of plugins installed.
2. Administrator view of list of plugins installed with Plugin Commander settings.
3. Administrator view of list of plugins intalled with Plugin Manager settings. (View also shows regenerate button.)

== Changelog ==

1.3.2 - minor cleanups, should work with 3.4.2, and we'll go from here with better support!
1.2 - updated for new network admin menu in 3.1, eliminated use of plugin on less than WP 3.0
1.1 - minor tweak to eliminate content shift