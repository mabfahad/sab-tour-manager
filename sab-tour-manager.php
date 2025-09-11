<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://fahad-assignment.online
 * @since             1.0.0
 * @package           Sab_Tour_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       sab-tour-manager
 * Plugin URI:        https://fahad-assignment.online
 * Description:       Trip Manager – A WordPress plugin to manage and showcase trips. Create trips with destinations, prices, and durations, allow users to search and filter trips, view trip details, and enable seamless booking with pre-selected trips.
 * Version:           1.0.0
 * Author:            Md Abdullah Al Fahad
 * Author URI:        https://fahad-assignment.online/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sab-tour-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SAB_TOUR_MANAGER_VERSION', '1.0.0' );

// Define plugin path
define( 'SAB_PATH', plugin_dir_path( __FILE__ ) );

// Auto-include all PHP files from "controller" folder
foreach ( glob( SAB_PATH . 'controller/*.php' ) as $file ) {
    include_once $file;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sab-tour-manager-activator.php
 */
function activate_sab_tour_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sab-tour-manager-activator.php';
	Sab_Tour_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sab-tour-manager-deactivator.php
 */
function deactivate_sab_tour_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sab-tour-manager-deactivator.php';
	Sab_Tour_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sab_tour_manager' );
register_deactivation_hook( __FILE__, 'deactivate_sab_tour_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sab-tour-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sab_tour_manager() {

	$plugin = new Sab_Tour_Manager();
	$plugin->run();

}
run_sab_tour_manager();
