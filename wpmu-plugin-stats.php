<?php
/**
 * @author    Christian Foellmann & Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @copyright Copyright (c) 2009 - 2014, Cooperative Extension Technology Services
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   WP-Repository\WPMU_Plugin_Stats
 * @version   2.1.0
 */
/*
Plugin Name: WPMU Plugin Stats
Plugin URI: https://wordpress.org/plugins/wpmu-plugin-stats/
Description: Gives network admins an easy way to see what plugins are actively used the sites of a multisite installation
Version: 2.1.0
Author: Christian Foellmann & Jason Lemahieu
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpmu-plugin-stats
Domain Path: /languages
Network: true

	WPMU Plugin Stats

	Copyright (C) 2014 Christian Foellmann (http://christian.foellmann.de)
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

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

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
	public $version = '2.1.0';

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
		add_action( 'network_admin_menu', array( $this, 'network_admin_menu' ) );
		add_action( 'plugins_loaded',     array( $this, 'load_plugin_textdomain' ) );
		add_action( 'load-plugins_page_wpmu-plugin-stats', array( $this, 'load_admin_assets' ) );

		/** Filters ***********************************************************/
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

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

	/**
	 * Fetch sites and the active plugins for every single site
	 *
	 * @since	1.0.0
	 *
	 * @see get_plugins()
	 * @see switch_to_blog()
	 * @see trailingslashit()
	 * @see get_bloginfo()
	 * @see get_option()
	 * @see restore_current_blog()
	 *
	 * @global object $wpdb
	 * @global array  $current_site
	 */
	private function generate_plugin_blog_list() {

		global $wpdb, $current_site;

		$blogs   = $wpdb->get_results( "SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC" );
		$plugins = get_plugins();

		if ( $blogs ) {

			foreach ( $blogs as $blog ) {
				switch_to_blog( $blog->blog_id );

				if ( constant( 'VHOST' ) == 'yes' ) {
					$blogurl = $blog->domain;
				} else {
					$blogurl = trailingslashit( $blog->domain . $blog->path );
				}

				$blog_info = array(
					'name' => get_bloginfo( 'name' ),
					'url' => $blogurl,
				);
				$active_plugins = get_option( 'active_plugins' );

				if ( sizeOf( $active_plugins ) > 0) {
					foreach ( $active_plugins as $plugin ) {

						//jason adding check for plugin existing on system
						if ( isset( $plugins[ $plugin ] ) ) {
							$this_plugin = $plugins[ $plugin ];

							if ( isset( $this_plugin['blogs'] ) && is_array( $this_plugin['blogs'] ) ) {
								array_push( $this_plugin['blogs'], $blog_info );
							} else {
								$this_plugin['blogs'] = array();
								array_push( $this_plugin['blogs'], $blog_info );
							}
							unset( $plugins[ $plugin] );
							$plugins[ $plugin ] = $this_plugin;
						} else {
							//this 'active' plugin is no longer on the system, so do nothing here?  (or could theoretically deactivate across all sites)
							//unset($plugins[$plugin]);
						}
					}
				} // foreach ($active_plugin as $plugin)

				restore_current_blog();

			}

		}

		// Set the site option to hold all this
		/*
		$old_stats = get_site_option('cets_plugin_stats_data', 'not-yet-set');
		if ($old_stats == 'not-yet-set') {
				add_site_option('cets_plugin_stats_data', $plugins);
		} else {  */
		ksort( $plugins );
		set_site_transient( 'plugin_stats_data', $plugins, 24 * HOUR_IN_SECONDS );

		return $plugins;
		//}

	} // END generate_plugin_blog_list()

	/**
	 * Add the menu item
	 *
	 * @since 1.0.0
	 *
	 * @see    add_submenu_page()
	 * @action network_admin_menu
	 * @hook   filter wpmu_plugin_stats_cap Defaults 'manage_network'
	 */
	public function network_admin_menu() {

		add_submenu_page(
			'plugins.php',
			__( 'Plugin Statistics', 'wpmu-plugin-stats' ),
			__( 'Statistics', 'wpmu-plugin-stats' ),
			apply_filters( 'wpmu_plugin_stats_cap', 'manage_network' ),
			'wpmu-plugin-stats',
			array( $this, 'plugin_stats_page' )
		);

	} // END network_admin_menu()

	/**
	 * Create a function to actually display stuff on plugin usage
	 *
	 * @since 1.0.0
	 *
	 * @see  get_site_option()
	 * @see  maybe_unserialize()
	 * @uses generate_plugin_blog_list()
	 */
	public function plugin_stats_page() {

		// Get the time when the plugin list was last generated
		$plugin_stats_data = get_site_transient( 'plugin_stats_data' );

		if ( ! $plugin_stats_data || ( isset( $_POST['action'] ) && $_POST['action'] === 'update' ) )  {
			$plugin_stats_data = $this->generate_plugin_blog_list();
		}
		?>
		<style type="text/css">
			table#wpmu-active-plugins { margin-top: 6px; }
			.bloglist { display:none; }
			span.plugin-not-found { color: red; }
			.plugins .active td.plugin-title { border-left: 4px solid #2EA2CC; font-weight: 700; }
		</style>
		<div class="wrap">
			<h2><?php _e( 'Plugin Statistics', 'wpmu-plugin-stats' ); ?></h2>
			<table class="wp-list-table widefat plugins" id="wpmu-active-plugins">
				<thead>
					<tr>
						<th class="nocase">
							<?php _e( 'Plugin', 'wpmu-plugin-stats' ); ?>
						</th>
						<th class="case" style="text-align: center !important">
							<?php _e( 'Activated Sitewide', 'wpmu-plugin-stats' ); ?>
						</th>
						<th class="num">
							<?php _e( 'Total Blogs', 'wpmu-plugin-stats' ); ?>
						</th>
						<th width="200px">
							<?php _e( 'Blog Titles', 'wpmu-plugin-stats' ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="nocase">
							<?php _e( 'Plugin', 'wpmu-plugin-stats' ); ?>
						</th>
						<th class="case" style="text-align: center !important">
							<?php _e( 'Activated Sitewide', 'wpmu-plugin-stats' ); ?>
						</th>
						<th class="num">
							<?php _e( 'Total Blogs', 'wpmu-plugin-stats' ); ?>
						</th>
						<th width="200px">
							<?php _e( 'Blog Titles', 'wpmu-plugin-stats' ); ?>
						</th>
					</tr>
				</tfoot>
				<tbody id="plugins">
					<?php $this->data_table( $plugin_stats_data ); ?>
				</tbody>
			</table>
			<div class="tablenav bottom">
				<div class="alignleft actions bulkactions">
					<form name="plugininfoform" action="" method="post">
						<?php submit_button( __( 'Regenerate', 'wpmu-plugin-stats' ) ); ?>
						<input type="hidden" name="action" value="update" />
					</form>
				</div>
			</div>
		</div><!-- .wrap -->
	<?php
	} // END plugin_stats_page()

	/**
	 * Generate the plugin table body
	 * 
	 * @since 2.1.0
	 * 
	 * @param  array $plugin_stats_data
	 * @return string
	 */
	private function data_table( $plugin_stats_data ) {

		$active_sitewide_plugins = get_site_option( 'active_sitewide_plugins' );
		$counter                 = 0;

		foreach ( $plugin_stats_data as $file => $info ) {
			$counter               = $counter + 1;
			$active_count          = isset( $info['blogs'] ) ? sizeOf( $info['blogs'] ) : 0;
			$is_activated_sitewide = ( is_array( $active_sitewide_plugins ) && array_key_exists( $file, $active_sitewide_plugins ) ) ? true : false;
			?>
			<tr valign="top" class="<?php echo $is_activated_sitewide ? 'active' : 'inactive'; ?>">
				<td class="plugin-title">
					<?php echo $this->plugin_title( $file, $info ); ?>
				</td>
				<td align="center">
					<?php
					if ( $is_activated_sitewide ) {
						_e( 'Yes' );
					} else {
						_e( 'No' );
					}
					?>
				</td>
				<td align="center">
					<?php echo $active_count; ?>
				</td>
				<td width="200px">
					<?php $this->active_blogs_list( $counter, $info ); ?>
				</td>
		<?php }
	} // END data_table()

	/**
	 * Generate proper plugin title
	 * 
	 * @since 2.1.0
	 * 
	 * @param  string $file
	 * @param  array $info
	 * @return string
	 */
	private function plugin_title( $file, $info ) {

		if ( isset( $info['Name'] ) ) {
			if ( strlen( $info['Name'] ) ) {
				$plugin_title = $info['Name'];
			} else {
				$plugin_title = $file;
			}
		} else {
			$plugin_title = $file . ' <span class="plugin-not-found">(' . __( 'Plugin File Not Found!', 'wpmu-plugin-stats' ) . ')</span>';
		}

		return esc_html( $plugin_title );

	} // END plugin_title()

	/**
	 * List all sites the plugin is active on
	 * 
	 * @since 2.1.0
	 * 
	 * @param  int $counter
	 * @param  array $info
	 * @return string
	 */
	private function active_blogs_list( $counter, $info ) {
		?>
		<a href="javascript:void(0)" onClick="jQuery('#bloglist_<?php echo esc_attr( $counter ); ?>').toggle(400);">
			<?php _e( 'Show/Hide Blogs', 'wpmu-plugin-stats' ); ?>
		</a>
		<ul class="bloglist" id="bloglist_<?php echo esc_attr( $counter ); ?>">
			<?php
			if ( isset( $info['blogs'] ) && is_array( $info['blogs'] ) ) {
				foreach ( $info['blogs'] as $blog ) {
					$link_title = empty( $blog['name'] ) ? $blog['url'] : $blog['name'];
					echo '<li><a href="http://' . $blog['url'] . '" target="new">' . $link_title . '</a></li>';
				}
			} else {
				echo '<li>' . esc_html__( 'N/A', 'wpmu-plugin-stats' ) . '</li>';
			}
			?>
		</ul>
	<?php

	} // END active_blogs_list()

	/**
	 * Load assets on the page
	 *
	 * @since 1.0.0
	 *
	 * @see p_enqueue_script()
	 * @see plugins_url()
	 * @action load-plugins_page_wpmu-plugin-stats
	 * @hook   filter wpmu_plugin_stats_debug	Defaults {@see WP_DEBUG}
	 */
	public function load_admin_assets() {

		$dev = apply_filters( 'wpmu_plugin_stats_debug', WP_DEBUG ) ? '' : '.min';

		wp_enqueue_script( 'tablesort', plugins_url( 'assets/js/tablesort' . $dev . '.js', __FILE__ ), array(), '2.5', true );

	} // END load_admin_assets()

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
	 * Add link to the GitHub repo to the plugin listing
	 *
	 * @since 1.0.0
	 *
	 * @see plugin_basename()
	 *
	 * @param  array $links
	 * @param  string $file
	 * @return array $links
	 */
	public function plugin_row_meta( $links, $file ) {

		if ( $file == plugin_basename( __FILE__ ) ) {
			return array_merge(
				$links,
				array( '<a href="https://github.com/wp-repository/wpmu-plugin-stats" target="_blank">GitHub</a>' )
			);
		}

		return $links;

	} // END plugin_row_meta()

	/**
	 * Pre-Activation checks
	 *
	 * Checks if this is a multisite installation
	 *
	 * @since 2.1.0
	 *
	 * @param bool $network_wide
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
 * @var object $wpmu_plugin_stats Holds the instantiated class {@uses WPMU_Plugin_Stats}
 */
function WPMU_Plugin_Stats() {
	return WPMU_Plugin_Stats::instance();
} // END WPMU_Plugin_Stats()

WPMU_Plugin_Stats();
