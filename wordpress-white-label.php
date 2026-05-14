<?php
/**
 * Plugin Name:       WordPress White Label
 * Plugin URI:        https://github.com/asifmohtesham/wordpress-white-label
 * Description:       White-label and customize your WordPress dashboard and login page. Inspired by Ultimate Dashboard free features.
 * Version:           0.1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Muhammad Asif Mohtesham
 * Author URI:        https://github.com/asifmohtesham
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-white-label
 * Domain Path:       /languages
 *
 * @package WpWhiteLabel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'WP_WHITE_LABEL_VERSION', '0.1.0' );
define( 'WP_WHITE_LABEL_FILE', __FILE__ );
define( 'WP_WHITE_LABEL_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_WHITE_LABEL_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load the plugin core.
 */
function wp_white_label_init() {
	require_once WP_WHITE_LABEL_DIR . 'includes/class-plugin.php';
	\WpWhiteLabel\Plugin::get_instance()->init();
}
add_action( 'plugins_loaded', 'wp_white_label_init' );
