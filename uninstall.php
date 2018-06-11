<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Cleanup Transient
delete_site_transient( 'plugin_stats_data' );

// Delete legacy options
delete_site_option( 'cets_plugin_stats_data' );
delete_site_option( 'cets_plugin_stats_data_freshness' );