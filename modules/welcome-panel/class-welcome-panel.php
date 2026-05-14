<?php
/**
 * Welcome Panel module — replace the default WordPress welcome panel.
 *
 * @package WpWhiteLabel\Modules\WelcomePanel
 */

namespace WpWhiteLabel\Modules\WelcomePanel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Welcome Panel module.
 */
class WelcomePanel {

	/** Settings option key. */
	const OPTION_KEY = 'wp_white_label_welcome_panel';

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		$options = get_option( self::OPTION_KEY, [] );

		if ( ! empty( $options['enabled'] ) ) {
			remove_action( 'welcome_panel', 'wp_welcome_panel' );
			add_action( 'welcome_panel', [ $this, 'render' ] );
		}
	}

	/**
	 * Render the custom welcome panel content.
	 *
	 * @return void
	 */
	public function render(): void {
		$options = get_option( self::OPTION_KEY, [] );
		$content = $options['content'] ?? '';

		if ( ! empty( $content ) ) {
			echo '<div class="wp-white-label-welcome-panel">';
			echo wp_kses_post( wpautop( $content ) );
			echo '</div>';
		}
	}
}
