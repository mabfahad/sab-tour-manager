<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://fahad-assignment.online
 * @since      1.0.0
 *
 * @package    Sab_Tour_Manager
 * @subpackage Sab_Tour_Manager/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sab_Tour_Manager
 * @subpackage Sab_Tour_Manager/includes
 * @author     Md Abdullah Al Fahad <mabf.fahad@gmail.com>
 */
class Sab_Tour_Manager_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sab-tour-manager',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
