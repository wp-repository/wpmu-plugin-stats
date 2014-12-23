# WPMU Plugin Stats #
**Contributors:** cfoellmann, MadtownLems, DeannaS, kgraeme  
**Tags:** WPMU, Wordpress Mu, Wordpress Multiuser, Plugin Stats , multisite, network  
**Requires at least:** 3.1  
**Tested up to:** 4.1  
**Stable tag:** 2.1.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Gives network admins an easy way to see what plugins are actively used the sites of a multisite installation

## Description ##

This plugin provides a snapshot view of which blogs are using any particular plugin. 
Because the time to generate stats can be quite large, network plugin useage is cached and can be regenerated anytime via the "Regenerate" button.

### Development ###

* GitHub Repository: [wpmu-plugin-stats](https://github.com/wp-repository/wpmu-plugin-stats)
* Issue-Tracker: [GitHub Issue Tracker](https://github.com/wp-repository/wpmu-theme-usage-info/issues) **Please use the Issue-Tracker at GitHub!!**
* Translation: [https://www.transifex.com/projects/p/wpmu-plugin-stats/](https://www.transifex.com/projects/p/wpmu-plugin-stats/)

## Installation ##

1. Install by searching "WPMU Plugin Stats" on Plugins > Add New > Search
2. Activate by clicking "Network Activate"

## Screenshots ##

### 1. Adminstrator view of list of plugins installed. ###
![Adminstrator view of list of plugins installed.](https://raw.githubusercontent.com/wp-repository/wpmu-plugin-stats/develop/.assets/screenshot-1.png)


## Upgrade Notice ##

**ATTENTION:**
When you update to version 2.1 the plugin gets deactivated automatically.
You need to reactivate by clicking "Network Activate". No data is lost.

## Changelog ##
### 2.1 (2014-12-23) ###
 * Updated tablesort.js (to 2.5)
 * Moved from storing data in option to transient
 * Changed main filename resulting in a deactivation after update

### 2.0.1 ###
 * fix for sites with empty title

### 2.0 ###
 * added some hooks
** * testing for WP 3.8+ (Trunk:** 3.9-alpha)  
 * removal of build tests for now
 * removed support for all external plugins for now
 * added cleanup of settings on removal (via uninstall.php)

### 1.6 ###
* added Spanish translation by Eduardo Larequi (https://github.com/elarequi)

### 1.5 ###
* translation support
* fixes for WP 3.5
* fix + update of tablesort js library

### 1.3.2 ###
* minor cleanups, should work with 3.4.2, and we'll go from here with better support!

### 1.2 ###
* updated for new network admin menu in 3.1, eliminated use of plugin on less than WP 3.0

### 1.1 ###
* minor tweak to eliminate content shift