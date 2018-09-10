<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.tappthatapps.com
 * @since      1.0.0
 *
 * @package    Legend_Articles_Importer
 * @subpackage Legend_Articles_Importer/admin/partials
 */
 // wp_deregister_script( 'jquery' );
 wp_enqueue_script( 'jquery', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . '/node_modules/jquery/dist/jquery.min.js', false, false, false);
 wp_enqueue_style( 'bootstrap', plugin_dir_url( dirname( dirname( __FILE__ ) ) )  . '/node_modules/bootstrap/dist/css/bootstrap.min.css' );
 wp_enqueue_script( 'bootstrap', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . '/node_modules/bootstrap/dist/js/bootstrap.min.js', array( 'jquery' ), false, true );
 wp_enqueue_script( 'underscore', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . '/node_modules/underscore/underscore-min.js', false, false, true );
 wp_enqueue_script( 'admin', plugin_dir_url( dirname( __FILE__ ) ) . '/js/legend-articles-importer-admin.js', array( 'jquery' ), false, true );
 wp_localize_script( 'admin', 'ajax', array(
   'ajax_url' => admin_url( 'admin-ajax.php' )
 ) );

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h1 class="mb-5">Welcome to the Legend Article Importer</h1>

<h2 class="mb-5">Click start to import all articles from legend3d.com</h2>

<form id="import-articles" class="form" method="post">

  <button class="btn btn-primary" type="submit" name="button">Go</button>

</form>
