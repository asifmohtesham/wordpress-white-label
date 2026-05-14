<?php
/**
 * Login Customizer module — customize wp-login.php via the WP Customizer.
 *
 * Free controls:
 *  - Logo: image, width, height, URL, link text
 *  - Background: color, image, size, position, repeat
 *  - Form: background color, border radius, padding
 *  - Button: background color, text color, border radius
 *  - Custom CSS for login page
 *
 * @package WpWhiteLabel\Modules\LoginCustomizer
 */

namespace WpWhiteLabel\Modules\LoginCustomizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Login Customizer module.
 */
class LoginCustomizer {

	/** Customizer section ID prefix. */
	const SECTION_PREFIX = 'wp_white_label_login_';

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		// Allow disabling via filter (same pattern as Ultimate Dashboard).
		if ( ! apply_filters( 'wp_white_label_login_customizer', true ) ) {
			return;
		}

		add_action( 'customize_register',      [ $this, 'register_customizer' ] );
		add_action( 'login_enqueue_scripts',    [ $this, 'enqueue_login_styles' ] );
		add_filter( 'login_headerurl',          [ $this, 'custom_logo_url' ] );
		add_filter( 'login_headertext',         [ $this, 'custom_logo_text' ] );
	}

	/**
	 * Register Customizer panel, sections, and controls.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP Customizer instance.
	 * @return void
	 */
	public function register_customizer( \WP_Customize_Manager $wp_customize ): void {
		// Panel.
		$wp_customize->add_panel(
			'wp_white_label_login',
			[
				'title'    => __( 'Login Page', 'wp-white-label' ),
				'priority' => 200,
			]
		);

		$this->add_logo_section( $wp_customize );
		$this->add_background_section( $wp_customize );
		$this->add_form_section( $wp_customize );
		$this->add_button_section( $wp_customize );
	}

	// -------------------------------------------------------------------------
	// Logo section
	// -------------------------------------------------------------------------

	/**
	 * Add logo section and controls.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP Customizer.
	 * @return void
	 */
	private function add_logo_section( \WP_Customize_Manager $wp_customize ): void {
		$section = self::SECTION_PREFIX . 'logo';

		$wp_customize->add_section( $section, [
			'title' => __( 'Logo', 'wp-white-label' ),
			'panel' => 'wp_white_label_login',
		] );

		$controls = [
			[ 'logo_image',  __( 'Logo Image', 'wp-white-label' ),  'image',  '' ],
			[ 'logo_width',  __( 'Logo Width (px)', 'wp-white-label' ), 'text', '80' ],
			[ 'logo_height', __( 'Logo Height (px)', 'wp-white-label' ), 'text', '80' ],
			[ 'logo_url',    __( 'Logo Link URL', 'wp-white-label' ),   'url',  home_url() ],
			[ 'logo_text',   __( 'Logo Link Text', 'wp-white-label' ),  'text', get_bloginfo( 'name' ) ],
		];

		$this->register_controls( $wp_customize, $section, $controls );
	}

	// -------------------------------------------------------------------------
	// Background section
	// -------------------------------------------------------------------------

	/**
	 * Add background section and controls.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP Customizer.
	 * @return void
	 */
	private function add_background_section( \WP_Customize_Manager $wp_customize ): void {
		$section = self::SECTION_PREFIX . 'background';

		$wp_customize->add_section( $section, [
			'title' => __( 'Background', 'wp-white-label' ),
			'panel' => 'wp_white_label_login',
		] );

		$controls = [
			[ 'bg_color',    __( 'Background Color', 'wp-white-label' ),  'color',  '#f0f0f1' ],
			[ 'bg_image',    __( 'Background Image', 'wp-white-label' ),   'image',  '' ],
			[ 'bg_size',     __( 'Background Size', 'wp-white-label' ),    'text',   'cover' ],
			[ 'bg_position', __( 'Background Position', 'wp-white-label' ), 'text',  'center center' ],
			[ 'bg_repeat',   __( 'Background Repeat', 'wp-white-label' ),  'text',   'no-repeat' ],
		];

		$this->register_controls( $wp_customize, $section, $controls );
	}

	// -------------------------------------------------------------------------
	// Form section
	// -------------------------------------------------------------------------

	/**
	 * Add form section and controls.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP Customizer.
	 * @return void
	 */
	private function add_form_section( \WP_Customize_Manager $wp_customize ): void {
		$section = self::SECTION_PREFIX . 'form';

		$wp_customize->add_section( $section, [
			'title' => __( 'Login Form', 'wp-white-label' ),
			'panel' => 'wp_white_label_login',
		] );

		$controls = [
			[ 'form_bg_color',     __( 'Form Background Color', 'wp-white-label' ), 'color', '#ffffff' ],
			[ 'form_border_radius', __( 'Form Border Radius (px)', 'wp-white-label' ), 'text', '4' ],
			[ 'form_padding',      __( 'Form Padding (px)', 'wp-white-label' ),     'text',  '26' ],
		];

		$this->register_controls( $wp_customize, $section, $controls );
	}

	// -------------------------------------------------------------------------
	// Button section
	// -------------------------------------------------------------------------

	/**
	 * Add button section and controls.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP Customizer.
	 * @return void
	 */
	private function add_button_section( \WP_Customize_Manager $wp_customize ): void {
		$section = self::SECTION_PREFIX . 'button';

		$wp_customize->add_section( $section, [
			'title' => __( 'Submit Button', 'wp-white-label' ),
			'panel' => 'wp_white_label_login',
		] );

		$controls = [
			[ 'btn_bg_color',     __( 'Button Background Color', 'wp-white-label' ), 'color', '#2271b1' ],
			[ 'btn_text_color',   __( 'Button Text Color', 'wp-white-label' ),       'color', '#ffffff' ],
			[ 'btn_border_radius', __( 'Button Border Radius (px)', 'wp-white-label' ), 'text', '3' ],
		];

		$this->register_controls( $wp_customize, $section, $controls );
	}

	// -------------------------------------------------------------------------
	// Helper: register controls
	// -------------------------------------------------------------------------

	/**
	 * Register a batch of Customizer settings + controls.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP Customizer.
	 * @param string                $section      Section ID.
	 * @param array                 $controls     Array of [id, label, type, default].
	 * @return void
	 */
	private function register_controls( \WP_Customize_Manager $wp_customize, string $section, array $controls ): void {
		foreach ( $controls as [ $id, $label, $type, $default ] ) {
			$setting_id = self::SECTION_PREFIX . $id;

			$wp_customize->add_setting(
				$setting_id,
				[
					'default'           => $default,
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				]
			);

			$control_args = [
				'label'   => $label,
				'section' => $section,
				'type'    => $type,
			];

			if ( 'color' === $type ) {
				$wp_customize->add_control(
					new \WP_Customize_Color_Control( $wp_customize, $setting_id, $control_args )
				);
			} elseif ( 'image' === $type ) {
				$wp_customize->add_control(
					new \WP_Customize_Image_Control( $wp_customize, $setting_id, $control_args )
				);
			} else {
				$wp_customize->add_control( $setting_id, $control_args );
			}
		}
	}

	// -------------------------------------------------------------------------
	// CSS output
	// -------------------------------------------------------------------------

	/**
	 * Get a customizer setting value.
	 *
	 * @param string $key     Setting key (without prefix).
	 * @param mixed  $default Fallback.
	 * @return mixed
	 */
	private function setting( string $key, $default = '' ) {
		return get_theme_mod( self::SECTION_PREFIX . $key, $default );
	}

	/**
	 * Enqueue dynamically-generated CSS on the login page.
	 *
	 * @return void
	 */
	public function enqueue_login_styles(): void {
		$logo_image       = $this->setting( 'logo_image' );
		$logo_width       = absint( $this->setting( 'logo_width', 80 ) );
		$logo_height      = absint( $this->setting( 'logo_height', 80 ) );
		$bg_color         = sanitize_hex_color( $this->setting( 'bg_color', '#f0f0f1' ) );
		$bg_image         = esc_url( $this->setting( 'bg_image' ) );
		$bg_size          = sanitize_text_field( $this->setting( 'bg_size', 'cover' ) );
		$bg_position      = sanitize_text_field( $this->setting( 'bg_position', 'center center' ) );
		$bg_repeat        = sanitize_text_field( $this->setting( 'bg_repeat', 'no-repeat' ) );
		$form_bg          = sanitize_hex_color( $this->setting( 'form_bg_color', '#ffffff' ) );
		$form_radius      = absint( $this->setting( 'form_border_radius', 4 ) );
		$form_padding     = absint( $this->setting( 'form_padding', 26 ) );
		$btn_bg           = sanitize_hex_color( $this->setting( 'btn_bg_color', '#2271b1' ) );
		$btn_color        = sanitize_hex_color( $this->setting( 'btn_text_color', '#ffffff' ) );
		$btn_radius       = absint( $this->setting( 'btn_border_radius', 3 ) );

		$css = 'body.login {';
		$css .= 'background-color:' . $bg_color . ';';
		if ( $bg_image ) {
			$css .= 'background-image:url(' . $bg_image . ');';
			$css .= 'background-size:' . $bg_size . ';';
			$css .= 'background-position:' . $bg_position . ';';
			$css .= 'background-repeat:' . $bg_repeat . ';';
		}
		$css .= '}';

		if ( $logo_image ) {
			$css .= '.wp-login-logo a {';
			$css .= 'background-image:url(' . esc_url( $logo_image ) . ') !important;';
			$css .= 'background-size:contain !important;';
			$css .= 'background-repeat:no-repeat !important;';
			$css .= 'background-position:center !important;';
			$css .= 'width:' . $logo_width . 'px !important;';
			$css .= 'height:' . $logo_height . 'px !important;';
			$css .= '}';
		}

		$css .= '#loginform,#lostpasswordform,#registerform {';
		$css .= 'background:' . $form_bg . ';';
		$css .= 'border-radius:' . $form_radius . 'px;';
		$css .= 'padding:' . $form_padding . 'px;';
		$css .= '}';

		$css .= '.wp-core-ui .button-primary {';
		$css .= 'background:' . $btn_bg . ';';
		$css .= 'border-color:' . $btn_bg . ';';
		$css .= 'color:' . $btn_color . ';';
		$css .= 'border-radius:' . $btn_radius . 'px;';
		$css .= '}';

		wp_add_inline_style( 'login', $css );
	}

	// -------------------------------------------------------------------------
	// Login page hooks
	// -------------------------------------------------------------------------

	/**
	 * Replace the login header logo URL.
	 *
	 * @return string
	 */
	public function custom_logo_url(): string {
		$url = esc_url( $this->setting( 'logo_url', home_url() ) );
		return $url ?: home_url();
	}

	/**
	 * Replace the login header logo link text.
	 *
	 * @return string
	 */
	public function custom_logo_text(): string {
		$text = $this->setting( 'logo_text', get_bloginfo( 'name' ) );
		return $text ?: get_bloginfo( 'name' );
	}
}
