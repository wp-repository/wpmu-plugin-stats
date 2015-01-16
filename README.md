# WPMU Plugin Stats #
**Contributors:** cfoellmann, MadtownLems, DeannaS, kgraeme  
**Tags:** WPMU, Wordpress Mu, Wordpress Multiuser, Plugin Stats , multisite, network, stats, usage  
**Requires at least:** 3.8  
**Tested up to:** 4.1  
**Stable tag:** 2.2.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Gives network admins an easy way to see what plugins are actively used on the sites of a multisite installation

## Description ##

This plugin give you a count and the listing of sites using your installed plugins.

Usage data is cached in a Transient (non-autoloading) but the data collection process can be a very expensive operation depending on plugin and (especially) site count.
Check the [FAQ](https://wordpress.org/plugins/wpmu-plugin-stats/faq/ "Frequently Asked Questions") for more details on caching.

### Other Multisite Plugins ###
[WPMU Theme Usage Info](https://wordpress.org/plugins/wpmu-theme-usage-info/)
[WPMU Plugin Manager](https://wordpress.org/plugins/wpmu-plugin-manager/)

> __Requires a WordPress Multisite Installation__
>
> _JavaScript needs to be enabled to see the list of sites using a plugin_

### Development ###

* GitHub Repository: [wpmu-plugin-stats](https://github.com/wp-repository/wpmu-plugin-stats)
* Issue-Tracker: [GitHub Issue Tracker](https://github.com/wp-repository/wpmu-theme-usage-info/issues) **Please use the Issue-Tracker at GitHub!!**
* Translation: [https://www.transifex.com/projects/p/wpmu-plugin-stats/](https://www.transifex.com/projects/p/wpmu-plugin-stats/)

## Installation ##

1. Install by searching "WPMU Plugin Stats" on Plugins > Add New > Search
2. Activate by clicking "Network Activate"

## Frequently Asked Questions ##

### When is the stats data refreshed? ###

 - Auto refresh on every plugin activation and deactivation
 - Auto refresh on `network/plugins.php` if Transient is expired (2h/24h on large networks)
 - Manual refresh if you visit `network/plugins.php?manual-stats-refresh=1`

### What happens on large installations ###

 - Auto refresh is not running on plugin (de)activation
 - Stats data is being regenerated every 24h (see action `wpmu_plugin_stats_refresh`)

### Hooks ###

[Filter] `wpmu_plugin_stats_refresh` - Manually set the expiration time of the data (Transient)

## Screenshots ##

### 1. Extended Plugin Table on network/plugins.php ###
![Extended Plugin Table on network/plugins.php](https://raw.githubusercontent.com/wp-repository/wpmu-plugin-stats/develop/.assets/screenshot-1.png)


## Upgrade Notice ##

**ATTENTION:**
When you update to version >2.0 the plugin gets deactivated automatically.
You need to reactivate by clicking "Network Activate". No data is lost.

## Changelog ##

### 2.2 (2015-01-15) ###
 * Integrated data into 'plugins.php' table
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