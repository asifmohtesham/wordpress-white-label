<?php
/**
 * Cleanup module — removes unnecessary admin UI elements.
 *
 * Features:
 *  - Remove "Help" tab
 *  - Remove "Screen Options" tab
 *  - Remove all or individual default WordPress dashboard widgets
 *
 * @package WpWhiteLabel\Modules\Cleanup
 */

namespace WpWhiteLabel\Modules\Cleanup;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cleanup module.
 */
class Cleanup {

	/** Settings option key. */
	const OPTION_KEY = 'wp_white_label_cleanup';

	/** Default dashboard meta boxes that can be individually toggled. */
	const DEFAULT_WIDGETS = [
		'dashboard_activity',
		'dashboard_right_now',
		'dashboard_quick_press',
		'dashboard_primary',       // WordPress news feed.
		'dashboard_site_health',
	];

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( 'screen_options_show_screen', [ $this, 'maybe_hide_screen_options' ] );
		add_filter( 'contextual_help',            [ $this, 'maybe_hide_help_tab' ], 999, 2 );
		add_action( 'wp_dashboard_setup',         [ $this, 'remove_dashboard_widgets' ] );
	}

	/**
	 * Get a single cleanup setting.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	private function get( string $key, $default = false ) {
		$options = get_option( self::OPTION_KEY, [] );
		return $options[ $key ] ?? $default;
	}

	/**
	 * Hide the Screen Options tab if the setting is enabled.
	 *
	 * @param bool $show_screen Whether to show Screen Options.
	 * @return bool
	 */
	public function maybe_hide_screen_options( bool $show_screen ): bool {
		if ( $this->get( 'hide_screen_options' ) ) {
			return false;
		}
		return $show_screen;
	}

	/**
	 * Remove all contextual help tabs if the setting is enabled.
	 *
	 * @param mixed           $old_help Unused.
	 * @param \WP_Screen      $screen   Current screen.
	 * @return mixed
	 */
	public function maybe_hide_help_tab( $old_help, \WP_Screen $screen ) {
		if ( $this->get( 'hide_help_tab' ) ) {
			$screen->remove_help_tabs();
		}
		return $old_help;
	}

	/**
	 * Remove default dashboard widgets based on per-widget settings.
	 *
	 * @return void
	 */
	public function remove_dashboard_widgets(): void {
		$remove_all = $this->get( 'remove_all_widgets', false );

		foreach ( self::DEFAULT_WIDGETS as $widget_id ) {
			if ( $remove_all || $this->get( 'remove_' . $widget_id, false ) ) {
				remove_meta_box( $widget_id, 'dashboard', 'normal' );
				remove_meta_box( $widget_id, 'dashboard', 'side' );
			}
		}
	}
}
