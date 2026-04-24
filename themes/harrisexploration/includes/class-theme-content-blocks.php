<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Content_Blocks
 */
class Theme_Content_Blocks {
	/**
	 * @var int
	 */
	private $id = 0;

	/**
	 * @var bool
	 */
	private $enabled = false;

	/**
	 * @var Theme_Content_Block_Row[]
	 */
	private $rows = array();

	/**
	 * Theme_Content_Blocks constructor.
	 *
	 * @param int $id
	 */
	function __construct( $id = 0 ) {
		if ( is_numeric( $id ) && $id > 0 ) {
			$this->set_id( $id );
			$this->set_enabled();

			if ( $this->get_id() ) {
				$this->set_rows();
			}
		}
	}

	/**
	 * Set ID.
	 *
	 * @param int $id ID.
	 */
	private function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Set enabled.
	 */
	private function set_enabled() {
		$this->enabled = ! is_search() && ! is_404() && 'templates/content-blocks.php' === get_page_template_slug( $this->get_id() );
	}

	/**
	 * Returns the current object ID.
	 *
	 * @return int
	 */
	private function get_id() {
		return $this->id;
	}

	/**
	 * Set up content block rows.
	 */
	private function set_rows() {
		$blocks = get_post_meta( $this->get_id(), 'blocks', true );

		if ( $blocks ) {
			for ( $i = 0; $i < $blocks; $i ++ ) {
				$this->rows[] = new Theme_Content_Block_Row( $this->get_id(), $i );
			}
		}
	}

	/**
	 * Render each content block row.
	 */
	public function render() {
		if ( $this->is_enabled() && $this->rows ) {
			foreach ( $this->rows as $row ) {
				$row->render();
			}
		}
	}

	/**
	 * Returns whether or not hero is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return $this->enabled;
	}

	/**
	 * Public getter for rows.
	 *
	 * @return Theme_Content_Block_Row[]
	 */
	public function get_rows() {
		return $this->rows;
	}
}
