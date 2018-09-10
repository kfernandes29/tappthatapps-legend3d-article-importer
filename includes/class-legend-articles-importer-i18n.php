<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.tappthatapps.com
 * @since      1.0.0
 *
 * @package    Legend_Articles_Importer
 * @subpackage Legend_Articles_Importer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Legend_Articles_Importer
 * @subpackage Legend_Articles_Importer/includes
 * @author     Kevin Fernandes <kevin@tappthatapps.com>
 */
class Legend_Articles_Importer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'legend-articles-importer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
