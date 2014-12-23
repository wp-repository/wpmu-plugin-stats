<?php
/**
 * @author    Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @copyright Copyright (c) 2009 - 2014, Cooperative Extension Technology Services
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package WP-Repository\WPMU_Plugin_Stats
 */

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_site_option( 'cets_plugin_stats_data' );
delete_site_option( 'cets_plugin_stats_data_freshness' );
