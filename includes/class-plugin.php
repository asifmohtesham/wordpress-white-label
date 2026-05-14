<?php
/**
 * Core plugin class — boots all modules.
 *
 * @package WpWhiteLabel
 */

namespace WpWhiteLabel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin bootstrap.
 */
class Plugin {

	/** @var Plugin|null Singleton instance. */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/** Private constructor — use get_instance(). */
	private function __construct() {}

	/**
	 * Register the autoloader and boot each module.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register_autoloader();
		$this->load_modules();
	}

	/**
	 * PSR-4 style autoloader for the WpWhiteLabel namespace.
	 *
	 * @return void
	 */
	private function register_autoloader(): void {
		spl_autoload_register(
			function ( string $class ) {
				$prefix = 'WpWhiteLabel\\';
				$base   = WP_WHITE_LABEL_DIR;

				if ( 0 !== strpos( $class, $prefix ) ) {
					return;
				}

				$relative = substr( $class, strlen( $prefix ) );
				$parts    = explode( '\\', $relative );

				// Convert last part: MyClass -> class-my-class.php
				$last     = array_pop( $parts );
				$filename = 'class-' . strtolower( preg_replace( '/([A-Z])/', '-$1', lcfirst( $last ) ) ) . '.php';

				// Sub-namespace -> subdirectory (e.g. Modules\Branding -> modules/branding/)
				$subdir = '';
				if ( ! empty( $parts ) ) {
					$subdir = implode( '/', array_map( 'strtolower', $parts ) ) . '/';
				}

				$file = $base . $subdir . $filename;
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		);
	}

	/**
	 * Instantiate and init each feature module.
	 *
	 * @return void
	 */
	private function load_modules(): void {
		$modules = [
			\WpWhiteLabel\Modules\Branding\Branding::class,
			\WpWhiteLabel\Modules\Cleanup\Cleanup::class,
			\WpWhiteLabel\Modules\AdminBar\AdminBar::class,
			\WpWhiteLabel\Modules\WelcomePanel\WelcomePanel::class,
			\WpWhiteLabel\Modules\LoginCustomizer\LoginCustomizer::class,
			\WpWhiteLabel\Modules\LoginRedirect\LoginRedirect::class,
			\WpWhiteLabel\Modules\Widget\WidgetPostType::class,
			\WpWhiteLabel\Modules\Widget\WidgetRenderer::class,
			\WpWhiteLabel\Modules\AdminPage\AdminPagePostType::class,
			\WpWhiteLabel\Modules\AdminPage\AdminPageRenderer::class,
			\WpWhiteLabel\Modules\CustomCss\CustomCss::class,
			\WpWhiteLabel\Modules\Settings\Settings::class,
			\WpWhiteLabel\Modules\Tools\Tools::class,
		];

		foreach ( $modules as $module_class ) {
			if ( class_exists( $module_class ) ) {
				( new $module_class() )->init();
			}
		}
	}
}
