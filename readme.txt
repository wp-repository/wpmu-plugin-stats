=== WPMU Plugin Stats ===
Contributors: DeannaS, kgraeme
Tags: WPMU, Wordpress Mu, Wordpress Multiuser, Plugin Stats 
Requires at least: 2.7
Tested up to: 2.8.5.2
Stable tag: trunk



Provides info to site admins as to which plugins are activated sitewide, and which blogs plugins are activated on. For users of <a href="http://firestats.cc/wiki/WPMUPluginCommander">Plugin Commander</a> or <a href="http://wpmudev.org/project/wpmu-plugin-manager">Plugin Manager</a>, it also provides information on which plugins are auto activated, user controlled, or supporter-controlled (for Plugin Manager). 


== Description ==
Included files:

* cets\_plugin\_stats.php
* cets\_plugin\_stats folder - lib folder - tablesort.js

Best practice for upgrading plugins has always been to first deactivate the plugin, upgrade, and then reactivate the plugin. For site admins of WPMU sites, this is a laborious process, partly because you'd need to go through each blog to determine whether or not the plugin has been activated. This plugin provides a snapshot view of which blogs are using any particular plugin. 

For sites that are using Plugin Commander to manage plugins, additional columns for the Plugin Commander settings of Auto Activate and User Controlled are included.

For sites that are using Plugin Manager, additional columns for the Plugin Manager settings of Auto Activate, User Controlled and Supporter Controlled are included.

Data is regenerated on viewing the plugin stats page if the data is more than one hour old. Data can be regenerated anytime via the "Regenerate" button.

== Installation ==

1. Place the cets\_plugin\_info.php file and directory in the wp-content/mu-plugins folder.
1. Go to site admin -> Plugin Stats to view information.

== Screenshots ==

1. Adminstrator view of list of plugins installed.
2. Administrator view of list of plugins installed with Plugin Commander settings.
3. Administrator view of list of plugins intalled with Plugin Manager settings. (View also shows regenerate button.)


