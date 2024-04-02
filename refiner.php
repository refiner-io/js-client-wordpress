<?php
/**
 * Plugin Name: Refiner
 * Description: Micro-surveys that drive user engagement. Our simple and beautiful survey widgets get you the responses you need to grow your business - on brand and perfectly timed.
 * Author: Refiner
 * Author URI: https://www.refiner.io/
 * Version: 1.0.0
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: refiner
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', 'refiner_plugin_init' );

function refiner_plugin_init() {

	if ( ! class_exists( 'WP_Refiner' ) ) :

		class WP_Refiner {

			const VERSION = '1.0.0';	

			public function __construct() {
				add_action( 'admin_init', array($this, 'install'));
				$this->init();
			}

			public function init() {
				$this->init_admin();
				$this->enqueue_script();
				$this->enqueue_admin_styles();
			}

			public function init_admin() {
				register_setting('refiner', 'refiner_project_id');
				register_setting('refiner', 'refiner_identify_users');
				add_action('admin_menu', array($this, 'add_menu_item') );
				
			}

			public function add_menu_item() {
				add_submenu_page('options-general.php', 'Refiner Plugin Settings', 'Refiner', 'manage_options', 'refiner_settings', array($this, 'admin_settings_view'));				
			}

			public static function admin_settings_view()
			{
				require_once plugin_dir_path( __FILE__ ) . 'admin/settings.php';
			}

			public function update_plugin_version() {
				delete_option('refiner_version');
				update_option('refiner_version', self::VERSION);
			}

			public static function refiner_script()
			{
				$refiner_project_id = trim(get_option('refiner_project_id'));
				if (!$refiner_project_id) {
					return;
				}

				$code = "<script async src=\"https://js.refiner.io/v001/client.js\"></script>";
				$code .= "<script>window._refinerQueue = window._refinerQueue || [];function _refiner(){_refinerQueue.push(arguments);}_refiner('setProject', '" . $refiner_project_id. "');</script>";
				$code .= "<script>_refiner('setInstallationMethod', 'wordpress');</script>";

				if (get_option('refiner_identify_users') === 'yes' && $user = wp_get_current_user()) {
					$id = esc_html__($user->name ? $user->name : $user->ID); 
					$name = esc_html__(trim($user->first_name . ' ' . $user->last_name));
					$email = $user->user_email;
					$code .= "<script>_refiner('identifyUser', {id: '" . $id . "', name: '" . $name ."', email: '" . $email ."'});</script>";
				}

				echo $code;
			}

			private function enqueue_script() {
				add_action( 'wp_head', array($this, 'refiner_script') );
			}

			private function enqueue_admin_styles() {
				add_action( 'admin_enqueue_scripts', array($this, 'refiner_admin_styles' ) );
			}

			public static function refiner_admin_styles() {
				wp_register_style('refiner_custom_admin_style', 
					plugins_url( 'admin/assets/main.css', __FILE__ ), array(), '2020-05-07', 'all');
				wp_enqueue_style('refiner_custom_admin_style' );
			}

			public function install() {
				if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
					return;
				}

				if ( ( self::VERSION !== get_option( 'refiner_version' ) ) ) {

					$this->update_plugin_version();
				}
			}
			public function plugin_action_links( $links ) {
				$plugin_links = array(
					'<a href="admin.php?page=refiner-settings">Settings</a>',
					'<a href="https://refiner.io/documentation/kb/getting-started/popup-survey-wordpress/">Support</a>',
				);
				return array_merge( $plugin_links, $links );
			}
		}

	endif;

	new WP_Refiner();

}
