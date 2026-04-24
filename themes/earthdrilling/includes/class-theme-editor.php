<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Editor
 */
class Theme_Editor {
	/**
	 * Init custom admin TinyMCE editor hooks.
	 */
	public static function init() {
		add_filter( 'tiny_mce_before_init', array( __CLASS__, 'tiny_mce_before_init' ) );
		add_filter( 'mce_buttons', array( __CLASS__, 'add_mce_buttons' ) );
		add_filter( 'mce_buttons_2', array( __CLASS__, 'mce_buttons' ) );
	}

	/**
	 * Callback function to filter the MCE settings.
	 *
	 * @param $init_array
	 *
	 * @return mixed
	 */
	public static function tiny_mce_before_init( $init_array ) {
		$style_formats = array(
			array(
				'title'    => 'Lead Paragraph',
				'selector' => 'p',
				'classes'  => 'lead',
			),
			array(
				'title'    => 'Small',
				'selector' => 'p',
				'classes'  => 'small',
			),
		);

		$init_array['style_formats'] = json_encode( $style_formats );

		return $init_array;
	}

	/**
	 * Add buttons to the TinyMCE editor.
	 *
	 * @param $buttons
	 *
	 * @return array
	 */
	public static function add_mce_buttons( $buttons ) {
		$buttons[] = 'hr';
		$buttons[] = 'superscript';
		$buttons[] = 'subscript';

		return $buttons;
	}

	/**
	 * Filters the second-row list of TinyMCE buttons (Visual tab)
	 *
	 * @link https://developer.wordpress.org/reference/hooks/mce_buttons_2/
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	public static function mce_buttons( $buttons ) {
		array_unshift(
			$buttons,
			'styleselect'
		);

		return $buttons;
	}
}

Theme_Editor::init();
