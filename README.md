# WPMU Plugin Stats
__Provides info to site admins as to which plugins are activated sitewide, and which blogs plugins are activated on.__

[Homepage][1.1] | [WordPress.org][1.2]

| WordPress					| Version			| *		| Development				|					|
| ----:						| :----				| :---: | :----						| :----				|
| Requires at least:		| __3.1__			| *		| [GitHub-Repository][1.3]	| [Translate][1.7]	|
| Tested up to:				| __3.5.1__			| *		| [Issue-Tracker][1.4]		| [WordPress.org-SVN][1.6] |
| Current stable release:	| __[2.0][1.5]__	| *		| Current dev version:		| [2.1][1.8]	|

[1.1]: https://github.com/wp-repository/wpmu-plugin-stats
[1.2]: http://wordpress.org/plugins/wpmu-plugin-stats/
[1.3]: https://github.com/wp-repository/wpmu-plugin-stats
[1.4]: https://github.com/wp-repository/wpmu-plugin-stats/issues
[1.5]: https://github.com/wp-repository/wpmu-plugin-stats/archive/2.0.zip
[1.6]: http://plugins.trac.wordpress.org/browser/wpmu-plugin-stats/
[1.7]: http://wp-translate.org/projects/wpmu-plugin-stats
[1.8]: https://github.com/wp-repository/wpmu-plugin-stats/archive/master.zip

### Description
This plugin provides a snapshot view of which blogs are using any particular plugin. 

For sites that are using Plugin Commander to manage plugins, additional columns for the Plugin Commander settings of Auto Activate and User Controlled are included.

For sites that are using Plugin Manager, additional columns for the Plugin Manager settings of Auto Activate, User Controlled and Supporter Controlled are included.

Because the time to generate stats can be quite large, network plugin useage is cached and can be regenerated anytime via the "Regenerate" button.


## Developers
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
__[GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)__

	WPMU Plugin Stats

	Copyright (C) 2009 - 2013 Board of Regents of the University of Wisconsin System
	Cooperative Extension Technology Services
	University of Wisconsin-Extension

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>. 


## Changelog
* __2.1-beta__ _[future plans/roadmap][4.1]_
	* TBD
* __2.0__
	* added some hooks
	* testing for WP 3.8+
	* removal of build tests for now
	* added cleanup of settings on removal (via uninstall.php)
* __1.6__
	* added build testing via travis-ci.org (https://travis-ci.org/wp-repository/wpmu-plugin-stats)
	* added Spanish translation by Eduardo Larequi (https://github.com/elarequi)
	* added uninstall function to remove settings on deactivation
	* dropped PHP4 support + no testing on PHP version < 5.3
* __1.5__
	* moved development to GitHub
	* full translation support
	* German language support
	* UI polished with tabs and functioning table-sorting
* __1.3.2__
	* minor cleanups, should work with 3.4.2, and we'll go from here with better support!
* __1.2__
	* updated for new network admin menu in 3.1, eliminated use of plugin on less than WP 3.0
* __1.1__
	* minor tweak to eliminate content shift

[4.1]: ../../issues