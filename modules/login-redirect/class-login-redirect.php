<?php
/**
 * Login Redirect module — redirect users after login based on role,
 * and block unauthorized wp-admin access.
 *
 * @package WpWhiteLabel\Modules\LoginRedirect
 */

namespace WpWhiteLabel\Modules\LoginRedirect;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Login Redirect module.
 */
class LoginRedirect {

	/** Settings option key. */
	const OPTION_KEY = 'wp_white_label_login_redirect';

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( 'login_redirect', [ $this, 'handle_login_redirect' ], 10, 3 );
		add_action( 'admin_init',      [ $this, 'block_unauthorized_admin' ] );
	}

	/**
	 * Redirect a user to their role-specific URL after login.
	 *
	 * @param string           $redirect_to           Default redirect URL.
	 * @param string           $requested_redirect_to URL the user was trying to reach.
	 * @param \WP_User|\WP_Error $user               Logged-in user object.
	 * @return string
	 */
	public function handle_login_redirect( string $redirect_to, string $requested_redirect_to, $user ): string {
		if ( is_wp_error( $user ) || ! ( $user instanceof \WP_User ) ) {
			return $redirect_to;
		}

		$options = get_option( self::OPTION_KEY, [] );

		foreach ( $user->roles as $role ) {
			$url = $options[ 'redirect_' . $role ] ?? '';
			if ( ! empty( $url ) ) {
				return esc_url_raw( $url );
			}
		}

		return $redirect_to;
	}

	/**
	 * Redirect users who try to access wp-admin when they are not allowed.
	 * Roles that have a redirect URL set are considered "not allowed" in wp-admin.
	 *
	 * @return void
	 */
	public function block_unauthorized_admin(): void {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$options = get_option( self::OPTION_KEY, [] );
		$user    = wp_get_current_user();

		foreach ( $user->roles as $role ) {
			$url = $options[ 'redirect_' . $role ] ?? '';
			if ( ! empty( $url ) && ! empty( $options[ 'block_admin_' . $role ] ) ) {
				wp_safe_redirect( esc_url_raw( $url ) );
				exit;
			}
		}
	}
}
