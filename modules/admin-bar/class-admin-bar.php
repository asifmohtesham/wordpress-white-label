<?php
/**
 * Admin Bar module — hide the admin bar for specific user roles.
 *
 * @package WpWhiteLabel\Modules\AdminBar
 */

namespace WpWhiteLabel\Modules\AdminBar;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Bar module.
 */
class AdminBar {

	/** Settings option key. */
	const OPTION_KEY = 'wp_white_label_admin_bar';

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'maybe_hide_admin_bar' ] );
	}

	/**
	 * Hide the admin bar for roles that have been opted in.
	 *
	 * @return void
	 */
	public function maybe_hide_admin_bar(): void {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$hide_roles = (array) get_option( self::OPTION_KEY . '_roles', [] );
		if ( empty( $hide_roles ) ) {
			return;
		}

		$user = wp_get_current_user();
		foreach ( $user->roles as $role ) {
			if ( in_array( $role, $hide_roles, true ) ) {
				show_admin_bar( false );
				return;
			}
		}
	}
}
