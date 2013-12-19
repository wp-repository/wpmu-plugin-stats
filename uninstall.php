<?php
/**
 * @author WP-Cloud <code@wp-cloud.de>
 * @license GPLv2 <http://www.gnu.org/licenses/gpl-2.0.html>
 * @package WPMU Plugin Stats
 */

//avoid direct calls to this file
if ( ! function_exists( 'add_filter' ) ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

delete_site_option( 'cets_plugin_stats_data' );
delete_site_option( 'cets_plugin_stats_data_freshness' );
