<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.tappthatapps.com
 * @since             1.0.0
 * @package           Legend_Articles_Importer
 *
 * @wordpress-plugin
 * Plugin Name:       Legend Articles Importer
 * Plugin URI:        tappthatapps.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kevin Fernandes
 * Author URI:        http://www.tappthatapps.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       legend-articles-importer
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-legend-articles-importer-activator.php
 */
function activate_legend_articles_importer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-legend-articles-importer-activator.php';
	Legend_Articles_Importer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-legend-articles-importer-deactivator.php
 */
function deactivate_legend_articles_importer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-legend-articles-importer-deactivator.php';
	Legend_Articles_Importer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_legend_articles_importer' );
register_deactivation_hook( __FILE__, 'deactivate_legend_articles_importer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-legend-articles-importer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_legend_articles_importer() {

	$plugin = new Legend_Articles_Importer();
	$plugin->run();

}
run_legend_articles_importer();
