<?php
/*
Plugin Name:    Plugin Stats
Plugin URI:     http://wordpress.org/extend/plugins/wpmu-plugin-stats/
Description:    WordPress plugin for letting site admins easily see what plugins are actively used on which sites
Version:        1.3.3
Author:         <a href="#" target="_target">Kevin Graeme</a>, <a href="#" target="_target">Deanna Schneider</a> & <a href="#" target="_target">Jason Lemahieu</a>
License:        TBD
License URI:    TBD
Text Domain:    cets_plugin_stats
Domain Path:    /languages/

Copyright:
    Copyright 2009-2012 Board of Regents of the University of Wisconsin System
	Cooperative Extension Technology Services
	University of Wisconsin-Extension           
*/
load_plugin_textdomain( 'cets_plugin_stats', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

class cets_Plugin_Stats {

        function cets_plugin_stats() {
                global $wp_version;
                //only run this code if we're at at least version 3.0
                if ( version_compare( $wp_version, '3.0', '>=' ) ) {

                        // Add the site admin config page
                        if (function_exists('is_network_admin')) {
                                add_action('network_admin_menu', array(&$this, 'plugin_stats_add_page'));
                        }
                        else{
                                add_action('admin_menu', array(&$this, 'plugin_stats_add_page'));
                        }	
                }
                else{

                        return;
                }
        }	


        function generate_plugin_blog_list() {
                global $wpdb, $current_site;

                        $blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC");
                        $blogplugins = array();
                        $processedplugins = array();

                        $plugins = get_plugins();


                        if ($blogs) {
                        foreach ($blogs as $blog) {

                                switch_to_blog($blog->blog_id);
                                if( constant( 'VHOST' ) == 'yes' ) {
                                        $blogurl = $blog->domain;			
                                } else {
                                        $blogurl =  trailingslashit( $blog->domain . $blog->path );
                                }

                                $blog_info = array('name' => get_bloginfo('name'), 'url' => $blogurl);

                                $active_plugins = get_option('active_plugins');


                                if (sizeOf($active_plugins) > 0) {
                                        foreach ($active_plugins as $plugin) {

                                            //jason adding check for plugin existing on system
                                            if (isset($plugins[$plugin])) {

                                                $this_plugin = $plugins[$plugin];
                                                if (isset($this_plugin['blogs']) && is_array($this_plugin['blogs'])) {
                                                    array_push($this_plugin['blogs'], $blog_info);
                                                } else {
                                                    $this_plugin['blogs'] = array();
                                                    array_push($this_plugin['blogs'], $blog_info);
                                                }
                                                unset($plugins[$plugin]);
                                                $plugins[$plugin] = $this_plugin;
                                            } else {
                                                //this 'active' plugin is no longer on the system, so do nothing here?  (or could theoretically deactivate across all sites)
                                                //unset($plugins[$plugin]);
                                            //
                                                                    }
                                        }
                                }		//foreach ($active_plugin as $plugin)

                                restore_current_blog();

                                }

                        }

                // Set the site option to hold all this
                /*
                $old_stats = get_site_option('cets_plugin_stats_data', 'not-yet-set');
                if ($old_stats == 'not-yet-set') {
                        add_site_option('cets_plugin_stats_data', $plugins);
                } else {  */
                        update_site_option('cets_plugin_stats_data', $plugins);
                //} 

                update_site_option('cets_plugin_stats_data_freshness', time());

        }

        // Create a function to add a menu item for site admins
        function plugin_stats_add_page() {
            if (is_super_admin()) {
                if (function_exists('is_network_admin')) {
                    //+3.1
                    $page = add_submenu_page('plugins.php', __('Plugin Stats', 'cets_plugin_stats'), __('Plugin Stats', 'cets_plugin_stats'), 'manage_network', basename(__FILE__), array(&$this, 'plugin_stats_page'));
                } else {
                    //-3.1
                    $page = add_submenu_page('wpmu-admin.php', __('Plugin Stats', 'cets_plugin_stats'), __('Plugin Stats', 'cets_plugin_stats'), 'manage_network', basename(__FILE__), array(&$this, 'plugin_stats_page'));
                }

                wp_enqueue_script('custom-script', plugins_url('js/tablesort-2.4.min.js', __FILE__), false, true);
            }
        }

        // Create a function to actually display stuff on plugin usage
        function plugin_stats_page(){

                // Get the time when the plugin list was last generated
                $gen_time = get_site_option('cets_plugin_stats_data_freshness');


                if ((time() - $gen_time) > 3600 || (isset($_POST['action']) && $_POST['action'] == 'update'))  {
                        // if older than an hour, regenerate, just to be safe
                                $this->generate_plugin_blog_list();	
                }
                $list = get_site_option('cets_plugin_stats_data');
                ksort($list);

                // if you're using plugin commander, these two values will be populated
                $auto_activate = explode(',',get_site_option('pc_auto_activate_list'));
                $user_control = explode(',',get_site_option('pc_user_control_list'));

                // if you're using plugin manager, these values will be populated
                $pm_auto_activate = explode(',',get_site_option('mp_pm_auto_activate_list'));
                $pm_user_control = explode(',',get_site_option('mp_pm_user_control_list'));
                $pm_supporter_control = explode(',',get_site_option('mp_pm_supporter_control_list'));
                $pm_auto_activate_status = ($pm_auto_activate[0] == '' || $pm_auto_active[0] == 'EMPTY' ? 0 : 1);
                $pm_user_control_status = ($pm_user_control[0] == '' || $pm_user_control == 'EMPTY' ? 0 : 1);
                $pm_supporter_control_status = ($pm_supporter_control[0] == '' || $pm_supporter_control == 'EMPTY' ? 0 : 1);


                // this is the built-in sitewide activation
                $active_sitewide_plugins = maybe_unserialize( get_site_option( 'active_sitewide_plugins') );
                ?>
                <!-- Some extra CSS -->
                <style type="text/css">
                        .bloglist {
                                display:none;
                        }
                        .pc_settings_heading {
                                text-align: center; 
                                border-right:  3px solid black;
                                border-left: 3px solid black;

                        }
                        .pc_settings_left {
                                border-left: 3px solid black;
                        }
                        .pc_settings_right {
                                border-right: 3px solid black;
                        }
                        span.plugin-not-found {
                                color: red;
                        }
                        .widefat tbody tr:hover td, .table-hover tbody tr:hover th {
                        background-color: #DDD;
                        }
                </style>

                <div class="wrap">
                        <?php screen_icon( 'plugins' ); ?>
                        <h2><?php _e( 'Plugin Stats', 'cets_plugin_stats'); ?></h2>
                        <table class="widefat" id="cets_active_plugins">

                                <thead>
                                        <?php if (sizeOf($auto_activate) > 1 || sizeOf($user_control) > 1 || $pm_auto_activate_status == 1 || $pm_user_control_status == 1|| $pm_supporter_control_status == 1 ) {
                                        ?>
                                        <tr>
                                                <th style="width: 25%;" >&nbsp;</th>
                                                <?php if (sizeOf($auto_activate) > 1 || sizeOf($user_control) > 1){
                                                ?>
                                                <th colspan="2" class="pc_settings_heading"><?php _e( 'Plugin Commander Settings', 'cets_plugin_stats'); ?></th>

                                                <?php	
                                                }
                                                if ($pm_auto_activate_status == 1 || $pm_user_control_status == 1|| $pm_supporter_control_status == 1){
                                                ?>
                                                <th colspan="3" align="center" class="pc_settings_heading"><?php _e( 'Plugin Manager Settings', 'cets_plugin_stats'); ?></th>
                                                <?php	
                                                }
                                                ?>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th  style="width: 20%;">&nbsp;</th>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                                <th class="nocase"><?php _e( 'Plugin', 'cets_plugin_stats'); ?></th>

                                                <?php if (sizeOf($auto_activate) > 1 || sizeOf($user_control) > 1){
                                                ?>
                                                <th class="nocase pc_settings_left"><?php _e( 'Auto Activate', 'cets_plugin_stats'); ?></th>
                                                <th class="nocase pc_settings_right"><?php _e( 'User Controlled', 'cets_plugin_stats'); ?></th>
                                                <?php	
                                                }
                                                if ($pm_auto_activate_status == 1 || $pm_user_control_status == 1|| $pm_supporter_control_status == 1){
                                                ?>
                                                <th class="nocase pc_settings_left"><?php _e( 'Auto Activate', 'cets_plugin_stats'); ?></th>
                                                <th class="nocase"><?php _e( 'User Controlled', 'cets_plugin_stats'); ?></th>
                                                <th class="nocase pc_settings_right"><?php _e( 'Supporter Controlled', 'cets_plugin_stats'); ?></th>
                                                <?php	
                                                }
                                                ?>
                                                <th class="case" style="text-align: center !important"><?php _e( 'Activated Sitewide', 'cets_plugin_stats'); ?></th>
                                                <th class="num"><?php _e( 'Total Blogs', 'cets_plugin_stats'); ?></th>
                                                <th width="200px"><?php _e( 'Blog Titles', 'cets_plugin_stats'); ?></th>

                                        </tr>
                                </thead>
                                <tbody id="plugins">
                                    <?php
                                    $counter = 0;
                                    foreach ($list as $file => $info){
                                            $counter = $counter + 1;


                                            echo('<tr valign="top"><td>');

                                            //jason checking for non-existant plugins
                                            if (isset($info['Name'])) {
                                                    if (strlen($info['Name'])) {
                                                            $thisName = $info['Name'];
                                                    } else {
                                                            $thisName = $file;
                                                    }
                                            } else {
                                                    $thisName = $file . " <span class='plugin-not-found'>(" . __('Plugin File Not Found!', 'cets_plugin_stats') . ")</span>";
                                            }


                                            echo ($thisName . '</td>');
                                            // plugin commander columns	
                                            if (sizeOf($auto_activate) > 1 || sizeOf($user_control) > 1) {
                                                    echo ('<td align="center" class="pc_settings_left">');
                                                    if (in_array($file, $auto_activate)) {_e( 'Yes' );}
                                            else {_e( 'No', 'cets_plugin_stats');}
                                                    echo('</td><td align="center" class="pc_settings_right">');
                                                    if (in_array($file, $user_control)) {_e( 'Yes' );}
                                            else {_e( 'No', 'cets_plugin_stats');}
                                                    echo("</td>");

                                            }
                                            // plugin manager columns
                                            if ($pm_auto_activate_status == 1 || $pm_user_control_status == 1 || $pm_supporter_control_status == 1) {
                                                    echo ('<td align="center" class="pc_settings_left">');
                                                    if (in_array($file, $pm_auto_activate)) {_e( 'Yes' );}
                                            else {_e( 'No', 'cets_plugin_stats');}
                                                    echo('</td><td align="center">');
                                                    if (in_array($file, $pm_user_control)) {_e( 'Yes' );}
                                            else {_e( 'No', 'cets_plugin_stats');}
                                                    echo('</td><td align="center" class="pc_settings_right">');
                                            if (in_array($file, $pm_supporter_control)) {_e( 'Yes' );}
                                            else {_e( 'No', 'cets_plugin_stats');}
                                            echo("</td>");

                                            }

                                            echo ('<td align="center">');
                                            if (is_array($active_sitewide_plugins) && array_key_exists($file, $active_sitewide_plugins)) {_e( 'Yes' );}
                                            else {_e( 'No', 'cets_plugin_stats');}

                                            if (isset($info['blogs'])) {
                                                    $numBlogs = sizeOf($info['blogs']);
                                            } else {
                                                    $numBlogs = 0;
                                            }

                                            echo ('</td><td align="center">' . $numBlogs . '</td><td>');
                                            ?>
                                            <a href="javascript:void(0)" onClick="jQuery('#bloglist_<?php echo $counter; ?>').toggle(400);"><?php _e( 'Show/Hide Blogs', 'cets_plugin_stats'); ?></a>


                                            <?php
                                            echo ('<ul class="bloglist" id="bloglist_' . $counter  . '">');
                                            if (isset($info['blogs']) && is_array($info['blogs'])){
                                                    foreach($info['blogs'] as $blog){
                                                            echo ('<li><a href="http://' . $blog['url'] . '" target="new">' . $blog['name'] . '</a></li>');
                                                            }

                                                    }
                                            else echo ("<li>" . __('N/A', 'cets_plugin_stats') . "</li>");	
                                            echo ('</ul></td>');


                                    }
                                    ?>
                            </tbody>
                    </table>
                    <p>
                        <?php 
                            if (time()-$gen_time > 60) { $lastregen = (round((time() - $gen_time)/60, 0)) . " " . __('minutes', 'cets_plugin_stats'); } 
                            else { $lastregen = __('less than 1 minute', 'cets_plugin_stats'); }
                        printf( __('This data is not updated as blog users update their plugins. It was last generated %s ago.', 'cets_plugin_stats'), $lastregen ) ; ?> 
                        <form name="plugininfoform" action="" method="post">
                            <input type="submit" class="button-primary" value="<?php _e( 'Regenerate', 'cets_plugin_stats'); ?>"><input type="hidden" name="action" value="update" />
                        </form>

                    </p>
            <?php
            }

}// end class

add_action( 'plugins_loaded', create_function( '', 'global $cets_Plugin_Stats; $cets_Plugin_Stats = new cets_Plugin_Stats();' ) );