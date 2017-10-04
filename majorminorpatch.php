<?php
/**
 * MajorMinorPatch
 *
 * @package     MajorMinorPatch
 * @author      Kristoffer Lorentsen
 * @copyright   2017 Kristoffer Lorentsen
 * @license     GPL-3.0
 *
 * @wordpress-plugin
 * Plugin Name: MajorMinorPatch - versioning for Wordpress pages
 * Plugin URI: https://github.com/krilor/majorminorpatch
 * Description:
 * Version: 0.0.0
 * Author: Kristoffer Lorentsen
 * Author URI: http://lorut.no
 * Text Domain: majminpat
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/krilor/majorminorpatch/
 * GitHub Branch: master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.0
 * @package    MajorMinorPatch
 * @author     Kristoffer Lorentsen <kristoffer@lorut.no>
 */
class MajorMinorPatch {
	/* Singleton */
  protected static $instance;
  public $version;
	public $meta_key;

	/**
	 * Main plugin class instance
	 *
	 * Modelled after EDD, to insure that there is only one instance of the plugin at the time. Prevents globals. Follows singleton pattern.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ){

			$class = self::class;

			self::$instance = new $class;

		}

		return self::$instance;

	}

  /**
   * Construct and define hooks
   */
	public function __construct() {

    $this->version = '0.0.0';
		$this->meta_key = 'majminpat-version';
		$this->define_hooks();

	}

	/**
	 * Add rewrite rules for the baseline and versions
	 *
	 * Valid URLs are with or without parent page slug, then only version, only baseline or both version and baseline in both ways
	 * SITE_NAME/(page-parent-slug/)?page-slug/baseline/2017-03-04T23:00:00/version/0.1.0(/)?
	 * SITE_NAME/(page-parent-slug/)?page-slug/version/0.1.0/baseline/2017-03-04T23:00:00(/)?
	 * SITE_NAME/(page-parent-slug/)?page-slug/baseline/2017-03-04T23:00:00(/)?
	 * SITE_NAME/(page-parent-slug/)?page-slug/version/0.1.0(/)?
	 */
	public function add_rewrite_rules(){
		return;
	}

  /**
	 * Register all of the plugin hooks
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function define_hooks() {

		// Shortcode to list revisions
		add_shortcode( 'listrevisions', array( $this, 'list_revisions' ) );

		// Add meta key to revisions
		add_filter( 'wp_post_revision_meta_keys', array( $this, 'add_version_meta_key_to_revision' ) );
		add_filter( 'wp_post_revision_single_meta_keys', array( $this, 'add_version_meta_key_to_revision' ) );

	}

	public function list_revisions() {
		$postid = get_the_ID();
		//$revs = wp_get_post_revisions($postid);
		// https://developer.wordpress.org/reference/classes/wp_query/parse_query/
		// https://johnblackbourn.com/post-meta-revisions-wordpress

		$version = '2';
		$revs = $this->get_page_version( $postid, $version );
		var_dump($revs);
		return 'Some random HTML';
	}

	/**
	* Get the latest version of the page
	*/
	function get_page_version( $id, $version, $year = false, $month = false, $day = false ){
		$args = array(
			'posts_per_page' => 1,
			'post_parent' => $id,
			'post_type' => 'revision',
			'post_status' => 'inherit',
			'order' => 'DESC',
			'orderby' => 'date ID',
			'check_enabled' => true,
			'meta_key' => $this->meta_key,
			'meta_value' => $version,
			/*'meta_query' => array(
				'relation' => 'OR', // Optional, defaults to "AND"
				array(
					'key'     => $this->meta_key,
					'value'   => $version,
					'compare' => '='
				)
		)*/ );

		if ( $year && $month && $day ) {
			$args['date_query'] = array(
															'after' => array(
																	'year'  => $year,
																	'month' => $month,
																	'day'   => $day,
															),
														);
		}

		return get_posts( $args );
	}

	/**
	 * Hooks into WP-Post-Meta-Revisions to add a special meta key that is needed for versioning
	 *
	 */

	public function add_version_meta_key_to_revision( $keys ){
		$keys[] = $this->meta_key;
    return $keys;
	}

}



/**
 * Execution of the plugin.
 *
 * Can also be used to get the plugin singleton
 *
 * @since    0.0.0
 */
function majminpat() {
	return MajorMinorPatch::instance();
}
majminpat();

?>
