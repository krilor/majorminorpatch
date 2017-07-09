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
		$this->define_hooks();

	}

  /**
	 * Register all of the plugin hooks
	 *
	 * @since    0.0.0
	 * @access   private
	 */
	private function define_hooks() {

    return;
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
