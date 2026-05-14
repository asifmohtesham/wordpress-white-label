<?php
/**
 * Branding module — customizes admin identity elements.
 *
 * Features:
 *  - Custom "Howdy" greeting text
 *  - Custom admin footer text (left)
 *  - Hide WordPress version in admin footer (right)
 *  - Custom dashboard headline
 *
 * @package WpWhiteLabel\Modules\Branding
 */

namespace WpWhiteLabel\Modules\Branding;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Branding module.
 */
class Branding {

	/** Settings option key. */
	const OPTION_KEY = 'wp_white_label_branding';

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( 'admin_footer_text', [ $this, 'custom_footer_text' ] );
		add_filter( 'update_footer',      [ $this, 'hide_version' ], 11 );
		add_filter( 'gettext',            [ $this, 'change_howdy' ], 10, 3 );
		add_action( 'welcome_panel',       [ $this, 'custom_dashboard_headline' ] );
	}

	/**
	 * Get a single branding setting.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	private function get( string $key, $default = '' ) {
		$options = get_option( self::OPTION_KEY, [] );
		return $options[ $key ] ?? $default;
	}

	/**
	 * Replace admin footer left text.
	 *
	 * @return string
	 */
	public function custom_footer_text(): string {
		$text = $this->get( 'footer_text' );
		if ( ! empty( $text ) ) {
			return wp_kses_post( $text );
		}
		return '';
	}

	/**
	 * Hide WordPress version string in admin footer right.
	 *
	 * @return string
	 */
	public function hide_version(): string {
		if ( $this->get( 'hide_wp_version', false ) ) {
			return '';
		}
		return get_bloginfo( 'version' );
	}

	/**
	 * Replace the "Howdy" greeting in the admin bar.
	 *
	 * @param string $translated  Translated text.
	 * @param string $text        Original text.
	 * @param string $domain      Text domain.
	 * @return string
	 */
	public function change_howdy( string $translated, string $text, string $domain ): string {
		$custom = $this->get( 'howdy_text' );
		if ( ! empty( $custom ) && 'Howdy, %1$s' === $text && 'default' === $domain ) {
			return esc_html( $custom ) . ', %1$s';
		}
		return $translated;
	}

	/**
	 * Print a custom headline at the top of the Welcome panel.
	 * Only fires if the default welcome panel has not been removed.
	 *
	 * @return void
	 */
	public function custom_dashboard_headline(): void {
		$headline = $this->get( 'dashboard_headline' );
		if ( ! empty( $headline ) ) {
			echo '<h2 class="wp-white-label-headline">' . esc_html( $headline ) . '</h2>';
		}
	}
}
