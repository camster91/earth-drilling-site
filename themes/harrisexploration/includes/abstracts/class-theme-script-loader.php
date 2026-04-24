<?php

/**
 * Class Theme_Script_Loader
 */
abstract class Theme_Script_Loader {
	/**
	 * Contains an array of script handles registered by the theme.
	 *
	 * @var array
	 */
	private $scripts = array();

	/**
	 * Contains an array of style handles registered by the theme.
	 *
	 * @var array
	 */
	private $styles = array();

	/**
	 * Contains an array of script handles to be localized by the theme.
	 *
	 * @var array
	 */
	private $wp_localize_scripts = array();

	/**
	 * Theme_Script_Loader constructor.
	 */
	public function __construct() {
		add_action( 'wp_print_scripts', array( $this, 'localize_enqueued_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( $this, 'localize_enqueued_scripts' ), 5 );
	}

	/**
	 * Register scripts.
	 *
	 * @param $scripts
	 */
	public function register_scripts( $scripts ) {
		foreach ( $scripts as $name => $props ) {
			$this->register_script( $name, $props );
		}
	}

	/**
	 * Register a script for use.
	 *
	 * @param $handle
	 * @param $props
	 */
	private function register_script( $handle, $props ) {
		$this->scripts[ $handle ] = $props;
		wp_register_script( $handle, $props['src'], $props['deps'], '', $props['in_footer'] );
	}

	/**
	 * Register styles.
	 *
	 * @param $styles
	 */
	public function register_styles( $styles ) {
		foreach ( $styles as $name => $props ) {
			$this->register_style( $name, $props );
		}
	}

	/**
	 * Register a style for use.
	 *
	 * @param $handle
	 * @param $props
	 */
	private function register_style( $handle, $props ) {
		$this->styles[ $handle ] = $props;
		wp_register_style( $handle, $props['src'], $props['deps'] );
	}

	/**
	 * Enqueue styles.
	 *
	 * @param $styles
	 */
	public function enqueue_styles( $styles ) {
		if ( $styles ) {
			foreach ( $styles as $handle => $args ) {
				$this->enqueue_style( $handle, $args );
			}
		}
	}

	/**
	 * Register and enqueue a styles for use.
	 *
	 * @param $handle
	 * @param $args
	 */
	public function enqueue_style( $handle, $args ) {
		if ( ! in_array( $handle, $this->styles, true ) && isset( $args['src'] ) ) {
			$this->register_style( $handle, $args );
		}
		wp_enqueue_style( $handle );
	}

	/**
	 * Enqueue a script for use.
	 *
	 * @param $handle
	 */
	public function enqueue_script( $handle ) {
		if ( array_key_exists( $handle, $this->scripts ) ) {
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Localize scripts only when enqueued.
	 */
	public function localize_enqueued_scripts() {
		$scripts = array_keys( $this->scripts );

		foreach ( $scripts as $handle ) {
			$this->localize_script( $handle );
		}
	}

	/**
	 * Localize a script once.
	 *
	 * @param string $handle Script handle the data will be attached to.
	 */
	private function localize_script( $handle ) {
		if ( ! in_array( $handle, $this->wp_localize_scripts, true ) && wp_script_is( $handle ) ) {
			$script = $this->scripts[ $handle ];

			if ( ! isset( $script['params'] ) ) {
				return;
			}

			$name                        = str_replace( '-', '_', $handle ) . 'Params';
			$this->wp_localize_scripts[] = $handle;
			wp_add_inline_script(
				$handle,
				$this->prepare_var_to_localize_script(
					$name,
					$script['params']
				),
				'before'
			);
		}
	}

	/**
	 * A custom variable localizer that doesn't output the unnecessary script/javascript tags.
	 *
	 * @param string $variable_name
	 * @param        $variable
	 *
	 * @return string
	 */
	private function prepare_var_to_localize_script( string $variable_name, $variable ) {
		$var_json_string = json_encode( $variable );

		return "var $variable_name = $var_json_string;";
	}

	/**
	 * Return asset URL.
	 *
	 * @param $filename
	 *
	 * @return string
	 */
	public function get_asset_url( $filename ) {
		$url       = '';
		$extension = pathinfo( $filename, PATHINFO_EXTENSION );
		$name      = pathinfo( $filename, PATHINFO_FILENAME );

		if ( 'js' === $extension || 'css' === $extension ) {
			$folder    = 'js' === $extension ? 'scripts' : 'styles';
			$dist_path = THEME_ABSPATH . 'dist/' . $folder;

			if ( is_dir( $dist_path ) ) {
				$assets = new DirectoryIterator( $dist_path );

				foreach ( $assets as $asset ) {
					$file     = basename( $asset );
					$basename = pathinfo( $file, PATHINFO_FILENAME );

					if ( strpos( $basename, $name ) !== false ) {
						$url = THEME_URI . 'dist/' . $folder . '/' . $file;
						break;
					}
				}
			}
		}

		return $url;
	}
}
