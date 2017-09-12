<?php
/**
 * @author    WPStore.io <code@wpstore.io>
 * @copyright Copyright (c) 2017 WPStore.io (http://www.wpstore.io)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   WPStore\Plugins\WPMU_Plugin_Stats
 * @version   2.3.0
 */
/*
Plugin Name: WPMU Plugin Stats
Plugin URI:  https://wordpress.org/plugins/wpmu-plugin-stats/
Description: Gives network admins an easy way to see what plugins are actively used on the sites of a multisite installation
Version:     2.3.0
Author:      WPStore.io
Author URI:  https://www.wpstore.io
Donate link: https://www.wpstore.io/donate
License:     GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpmu-plugin-stats
Domain Path: /languages
Network:     true

	WPMU Plugin Stats
	Copyright (C) 2017 WPStore.io (http://www.wpstore.io)
	Copyright (C) 2014 - 2017 Christian Foellmann (http://christian.foellmann.de)
	Copyright (C) 2009 - 2013 Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
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
*/

namespace WPStore\Plugins;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct access! This plugin requires WordPress to be loaded.' );
}

/**
 * Main class to run the plugin
 *
 * @since 1.0.0
 */
class WPMU_Plugin_Stats {

	/**
	 * Current version of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string $version
	 */
	public $version = '2.3.0';

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		/* Do nothing here */
	} // END __construct()

	/**
	 * Hook in actions and filters
	 *
	 * @since 2.1.0
	 */
	private function setup_actions() {

		/** Actions ***********************************************************/
		add_action( 'plugins_loaded',               array( $this, 'load_plugin_textdomain' )        );
		add_action( 'admin_head-plugins.php',       array( $this, 'add_css'                )        );
		add_action( 'load-plugins.php',             array( $this, 'load'                   )        );
		add_action( 'manage_plugins_custom_column', array( $this, 'column_active'          ), 10, 3 );
		add_action( 'activated_plugin',             array( $this, 'auto_refresh'           ), 10, 2 );
		add_action( 'deactivated_plugin',           array( $this, 'auto_refresh'           ), 10, 2 );

		/** Filters ************************************ *********************/
		add_filter( 'manage_plugins-network_columns', array( $this, 'add_column' ) );

		/** (De-)Activation ***************************************************/
		register_activation_hook( __FILE__, array( 'WPMU_Plugin_Stats', 'activation' ) );

	} // END setup_actions()

	/**
	 * Getter method for retrieving the object instance.
	 *
	 * @since 1.0.0
	 *
	 * @return WPMU_Plugin_Stats|null The instance object
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new WPMU_Plugin_Stats;
			$instance->setup_actions();
		}

		// Always return the instance
		return $instance;

	} // END instance()

	public function load() {

		$plugin_stats_data = get_site_transient( 'plugin_stats_data' );

		if ( ! $plugin_stats_data || isset( $_GET['manual-stats-refresh'] ) ) {
			$plugin_stats_data = $this->generate_plugin_blog_list();
		}

	} // END load()

	public function add_css() {
		?>
<style type="text/css">
	.column-active p { width: 200px; }
	.siteslist { display:none; }
</style>
		<?php
	} // END add_css()

	/**
	 * Add the 'Usage' column to WP_Plugins_List_Table (network)
	 *
	 * @since 2.1.0
	 *
	 * @param  array The columns array of the table
	 * @return array
	 */
	public function add_column( $columns ) {

		$columns['active'] = __( 'Usage', 'wpmu-plugin-stats' );

		return $columns;

	} // END add_columns

	/**
	 * Output the stats data for the plugins
	 *
	 * @param  string $column_name
	 * @param  string $plugin_file
	 * @param  array $plugin_data
	 *
	 * @return string|null HTML output for the column
	 */
	public function column_active( $column_name, $plugin_file, $plugin_data ) {

			$network_data   = get_site_transient( 'plugin_stats_data' );
			$network_active = is_plugin_active_for_network( $plugin_file );

			$id             = isset( $plugin_data['id'] )            ? $plugin_data['id']            : rand( 999999, 9999999 );
			$data           = isset( $network_data[ $plugin_file ] ) ? $network_data[ $plugin_file ] : 0;
			$active_count   = isset( $data['sites'] )                ? sizeOf( $data['sites'] )      : 0;

			echo '<p>';
			if ( 0 === $active_count && ! $network_active ) {

				_e( 'Not Active on any site', 'wpmu-plugin-stats' );

			} elseif ( $network_active ) {

				echo '<b>' . __( 'Active on all sites', 'wpmu-plugin-stats' ) . '</b>';

			} else {

				printf(
					_n( 'Active on %2$s %1$d site %3$s', 'Active on %2$s %1$d sites %3$s', $active_count, 'wpmu-plugin-stats' ),
					$active_count,
					"<a href=\"javascript:;\" onClick=\"jQuery('ul[id*=\'siteslist_{$id}\']').toggle(400);\">",
					'</a>'
				);

			}
			echo '</p>';

			if ( isset( $data['sites'] ) && is_array( $data['sites'] ) ) {
				echo "<ul class=\"siteslist\" id=\"siteslist_{$id}\">";
				foreach ( $data['sites'] as $site ) {
					$link_title = empty( $site['name'] ) ? $site['url'] : $site['name'];
					echo '<li><a href="http://' . esc_html( $site['url'] ) . '" target="new">' .  esc_html( $link_title ) . '</a></li>';
				}
				echo '</ul>';
			}

	} // END column_active()

	/**
	 * Fetch sites and the active plugins for every single site
	 *
	 * @todo If wp_is_large_network() this function could time out
     * @todo Maybe use 'WP Object Cache' https://codex.wordpress.org/Class_Reference/WP_Object_Cache
	 * @since 1.0.0
	 *
	 * @global object $wpdb
	 * @global array $current_site
	 * @return array
	 */
	private function generate_plugin_blog_list() {

		global $wpdb, $current_site;

		$select  = $wpdb->prepare( "SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d ORDER BY domain ASC", $current_site->id );
		$sites   = $wpdb->get_results( $select );
		$plugins = get_plugins();

		foreach ( $sites as $site ) {
			switch_to_blog( $site->blog_id );

			if ( constant( 'VHOST' ) === 'yes' ) {
				$siteurl = $site->domain;
			} else {
				$siteurl = trailingslashit( $site->domain . $site->path );
			}

			$active_plugins = get_option( 'active_plugins' );
			$site_info      = array(
				'name' => get_bloginfo( 'name' ),
				'url'  => $siteurl,
			);

			if ( sizeOf( $active_plugins ) > 0 ) {
				foreach ( $active_plugins as $plugin ) {

					//jason adding check for plugin existing on system
					if ( isset( $plugins[ $plugin ] ) ) {
						$this_plugin = $plugins[ $plugin ];

						if ( isset( $this_plugin['sites'] ) && is_array( $this_plugin['sites'] ) ) {
							array_push( $this_plugin['sites'], $site_info );
						} else {
							$this_plugin['sites'] = array();
							array_push( $this_plugin['sites'], $site_info );
						}
						unset( $plugins[ $plugin ] );
						$plugins[ $plugin ] = $this_plugin;
					} else {
						//this 'active' plugin is no longer on the system, so do nothing here?  (or could theoretically deactivate across all sites)
						//unset($plugins[$plugin]);
					}
				} // END foreach [Plugins]
			}
			restore_current_blog();
		} // END foreach 'sites'

		ksort( $plugins );

		$hours   = wp_is_large_network() ? 24 : 2;
		$refresh = apply_filters( 'wpmu_plugin_stats_refresh' , $hours * HOUR_IN_SECONDS );

		set_site_transient( 'plugin_stats_data', $plugins, $refresh );

		return $plugins;

	} // END generate_plugin_blog_list()

	/**
	 * Regenerate the statistics on every theme switch network-wide
	 *
	 * @since 1.0.0
	 *
	 * @uses generate_plugin_blog_list()
	 * @action switch_theme
	 */
	public function auto_refresh() {

		if ( wp_is_large_network() ) {
			$this->load();
		} else {
			$this->generate_plugin_blog_list();
		}

	} // END auto_refresh()

	/**
	 * Load the plugin's textdomain hooked to 'plugins_loaded'.
	 *
	 * @since 1.0.0
	 *
	 * @see    load_plugin_textdomain()
	 * @see    plugin_basename()
	 * @action plugins_loaded
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpmu-plugin-stats',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);

	} // END load_plugin_textdomain()

	/**
	 * Pre-Activation checks
	 *
	 * Checks if this is a multisite installation
	 *
	 * @since 2.1.0
	 */
	public static function activation() {

		if ( ! is_multisite() ) {
			wp_die( __( 'This plugin only runs on multisite installations. The functionality makes no sense for WP single sites.', 'wpmu-plugin-stats' ) );
		}

		// Delete legacy options
		delete_site_option( 'cets_plugin_stats_data' );
		delete_site_option( 'cets_plugin_stats_data_freshness' );

	} // END activation()

} // END class WPMU_Plugin_Stats

/**
 * Instantiate the main class
 *
 * @since 2.1.0
 *
 * @return null|WPMU_Plugin_Stats
 */
function WPMU_Plugin_Stats() {
	return WPMU_Plugin_Stats::instance();
} // END WPMU_Plugin_Stats()

WPMU_Plugin_Stats();
