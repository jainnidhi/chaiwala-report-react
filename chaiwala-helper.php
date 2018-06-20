<?php
/**
 * Plugin Name: Chaiwala Helper
 * Plugin URI: 
 * Description: 
 * Version: 1.0.0
 * Author: Team IdeaBox
 * Author URI: 
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: chaiwala
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'CHAIWALA_DIR', plugin_dir_path( __FILE__ ) );
define( 'CHAIWALA_URL', plugins_url( '/', __FILE__ ) );
define( 'CHAIWALA_PATH', plugin_basename( __FILE__ ) );
define( 'CHAIWALA_FILE', __FILE__ );

final class Chaiwala_Helper {

	public static $instance;

	public function __construct() {
		require_once CHAIWALA_DIR . 'classes/class-chaiwala-db.php';
		require_once CHAIWALA_DIR . 'classes/class-chaiwala-api.php';
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Chaiwala_Helper ) ) {
			self::$instance = new Chaiwala_Helper();
		}

		return self::$instance;
	}
}

$chaiwala_helper = Chaiwala_Helper::get_instance();
