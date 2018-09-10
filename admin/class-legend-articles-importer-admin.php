<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.tappthatapps.com
 * @since      1.0.0
 *
 * @package    Legend_Articles_Importer
 * @subpackage Legend_Articles_Importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Legend_Articles_Importer
 * @subpackage Legend_Articles_Importer/admin
 * @author     Kevin Fernandes <kevin@tappthatapps.com>
 */
class Legend_Articles_Importer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Legend_Articles_Importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Legend_Articles_Importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/legend-articles-importer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Legend_Articles_Importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Legend_Articles_Importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/legend-articles-importer-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Legend3D.com Article Importer', $this->plugin_name ),
			__( 'Legend3D.com Article Importer', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);

	}

	public function display_options_page() {

		include_once 'partials/legend-articles-importer-admin-display.php';

	}

	public function get_articles() {

		$query = new WP_Query(
			array(
				'post_type' => 'article',
				'posts_per_page' => '-1'
			)
		);

		$articles = $query->posts;

		foreach ($articles as $article) {

			$attachments = get_children(
				array(
					'post_parent' => $article->ID,
					'post_type' => 'attachment',
					'post_mime_type' => 'image'
				)
			);

			foreach ($attachments as $attachment) {
				wp_delete_attachment( $attachment->ID, true );
			}

			wp_delete_post( $article->ID, true );

		}

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, "http://www.legend3d.com/press/" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		$html = curl_exec( $ch );

		curl_close( $ch );

		wp_send_json_success( [ "output" => $html ] );

		wp_die();

	}

	public function save_articles() {

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		$articles = $_POST[ 'articles' ];

		$upload_dir = wp_upload_dir();

		if ( !file_exists( $upload_dir[ 'basedir' ] . '/articles' ) ) {
			wp_mkdir_p( $upload_dir[ 'basedir' ] . '/articles' );
		}

		foreach ($articles as $article) {

			$isPDF = strpos( $article[ 'link' ], 'pdf' );

			// Insert Article
			$post = wp_insert_post(
				array(
					'guid' => $wp_upload_dir['url'] . basename( $image_name ),
					'post_title' => wp_strip_all_tags( $article['title'] ),
					'post_type' => 'article',
					'post_status' => 'publish',
					'meta_input' => array(
						'publication_author' => wp_strip_all_tags( $article['author'] )
					)
				)
			);

			// If article has an image, add image attachment
			if ( $article[ 'image' ] ) {

				$image_name = basename( $article[ 'image' ] );
				$image_path = '/articles/' . $image_name;
				$image_file = $upload_dir[ 'basedir' ] . $image_path;

				file_put_contents( $image_file, file_get_contents( $article[ 'image' ] ) );

				$attachment = array(
					'guid' => home_url() . $image_path,
					'post_mime_type' => wp_check_filetype( $image_name )[ 'type' ],
					'post_title' => sanitize_file_name( $image_name ),
					'post_content' => '',
					'post_status' => 'inherit'
				);

				$attachment_id = wp_insert_attachment( $attachment, $image_file, $post );

				$attach_data = wp_generate_attachment_metadata( $attachment_id, $image_file );

 				wp_update_attachment_metadata( $attachment_id, $attach_data );

				set_post_thumbnail( $post, $attachment_id );

			}

			// If article is a PDF, add PDF attachment
			if ( $isPDF ) {

				$link_name = basename( $article[ 'link' ] );
				$link_path = '/articles/' . $link_name;
				$link_file = $upload_dir[ 'basedir' ] . $link_path;

				file_put_contents( $link_file, file_get_contents( $article[ 'link' ] ) );

				$attachment = array(
					'guid' => home_url() . $link_path,
					'post_mime_type' => wp_check_filetype( $link_name )[ 'type' ],
					'post_title' => sanitize_file_name( $link_name ),
					'post_content' => '',
					'post_status' => 'inherit'
				);

				$attachment_id = wp_insert_attachment( $attachment, $link_file, $post );

				$attach_data = wp_generate_attachment_metadata( $attachment_id, $link_file );

 				wp_update_attachment_metadata( $attachment_id, $attach_data );

			}

			wp_update_post(
				array(
					"ID" => $post,
					"meta_input" => array(
						'link_url' => $isPDF ? $upload_dir[ 'baseurl' ] . $link_path : wp_strip_all_tags( $article['link'] ),
					)
				)
			);

		}

		wp_send_json_success( true );

		wp_die();

	}

}
