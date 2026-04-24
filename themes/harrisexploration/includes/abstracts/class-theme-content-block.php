<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Theme_Content_Block
 */
abstract class Theme_Content_Block {
	/**
	 * @var int
	 */
	protected $id = 0;

	/**
	 * @var string
	 */
	protected $key = '';

	/**
	 * Gets content block meta using supplied id and key.
	 *
	 * @param $prop
	 *
	 * @return mixed
	 */
	public function get_prop( $prop = '' ) {
		return get_post_meta( $this->id, $this->key . '_' . $prop, true );
	}
}
