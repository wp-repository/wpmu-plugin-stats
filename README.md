# WPMU Plugin Stats
__Provides info to site admins as to which plugins are activated sitewide, and which blogs plugins are activated on.__

## Details
[Homepage][1.1] | [WordPress.org][1.2]

| WordPress					| Version			| *		| Development				|					|
| ----:						| :----				| :---: | :----						| :----				|
| Requires at least:		| __3.4__			| *		| [GitHub-Repository][1.3]	| [Translate][1.7]	|
| Tested up to:				| __3.5.1__			| *		| [Issue-Tracker][1.4]		|					|
| Current stable release:	| __[1.5][1.5]__	| *		| [WordPress.org-SVN][1.6]	|					|

[1.1]: https://github.com/wp-repository/wpmu-plugin-stats
[1.2]: http://wordpress.org/extend/plugins/wpmu-plugin-stats/
[1.3]: https://github.com/wp-repository/wpmu-plugin-stats
[1.4]: https://github.com/wp-repository/wpmu-plugin-stats/issues
[1.5]: https://github.com/wp-repository/wpmu-plugin-stats/archive/1.5.zip
[1.6]: http://plugins.trac.wordpress.org/browser/wpmu-plugin-stats/
[1.7]: https://translate.foe-services.de/projects/cets_plugin_stats

### Description
This plugin provides a snapshot view of which blogs are using any particular plugin. 

For sites that are using Plugin Commander to manage plugins, additional columns for the Plugin Commander settings of Auto Activate and User Controlled are included.

For sites that are using Plugin Manager, additional columns for the Plugin Manager settings of Auto Activate, User Controlled and Supporter Controlled are included.

Because the time to generate stats can be quite large, network plugin useage is cached and can be regenerated anytime via the "Regenerate" button.


## Development
### Developers
| Name					| GitHub				| WordPress.org			| Web									| Status				|
| :----					| :----					| :----					| :----									| ----:					|
| Kevin Graeme			| -						| [kgraeme][2.1.2]		| -										| Inactive				|
| Deanna Schneider		| -						| [deannas][2.2.2]		| http://deannaschneider.wordpress.com/ | Inactive				|
| Jason Lemahieu		| [MadtownLems][2.3.1]	| [MadtownLems][2.3.2]	| http://www.jasonlemahieu.com/			| Inactive				|
| Christian Foellmann	| [cfoellmann][2.4.1]	| [cfoellmann][2.4.2]	| http://www.foe-services.de			| Current maintainer	|

[2.1.2]: http://profiles.wordpress.org/kgraeme/
[2.2.2]: http://profiles.wordpress.org/DeannaS/
[2.3.1]: https://github.com/MadtownLems
[2.3.2]: http://profiles.wordpress.org/MadtownLems/
[2.4.1]: https://github.com/cfoellmann
[2.4.2]: http://profiles.wordpress.org/cfoellmann


## License
TBD


## Changelog
* __1.3.2__
	* minor cleanups, should work with 3.4.2, and we'll go from here with better support!
* __1.2__
	* updated for new network admin menu in 3.1, eliminated use of plugin on less than WP 3.0
* __1.1__
	* minor tweak to eliminate content shift
