<?php
/**
 * @author WP-Cloud <code@wp-cloud.de>
 * @license GPLv2 <http://www.gnu.org/licenses/gpl-2.0.html>
 * @package WP-Repository\WPMU_Plugin_Stats
 */

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_site_option( 'cets_plugin_stats_data' );
delete_site_option( 'cets_plugin_stats_data_freshness' );
