<?php

namespace Palacify\Mod\HtmlBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Palacify\Mod;
use Palacify\Internal;

class Html_Blocks {

	use Internal;

	public function __construct() {
		$this->main( 'api' )->add( 'html-blocks', 'add-update', array( $this, 'add_update' ) );
		$this->main( 'api' )->add( 'html-blocks', 'list', array( $this, 'list' ) );
		$this->main( 'api' )->add( 'html-blocks', 'delete', array( $this, 'delete' ) );

		add_shortcode( 'palacify-html-block', array( $this, 'shortcode' ) );
	}

	public function add_update( $args ) {
		$action = sanitize_key( $args['POST']['action'] ?? '' );
		$id     = sanitize_key( $args['POST']['id'] ?? '' );

		if ( ! in_array( $action, array( 'add', 'edit' ), true ) || ( 'edit' === $action && empty( $id ) ) ) {
			return $this->main( 'api' )->bad();
		}

		$errors = array();
		$name   = sanitize_text_field( $args['POST']['name'] ?? '' );
		$desc   = sanitize_text_field( $args['POST']['desc'] ?? '' );
		$html   = $args['POST']['html'] ?? '';
		$html   = is_string( $html ) ? $html : '';

		if ( empty( $name ) ) {
			$errors['name'] = 'Required';
		}

		if ( 0 !== count( $errors ) ) {
			return $this->main( 'api' )->unprocessable( $errors );
		}

		if ( 'edit' === $action && ! $this->main( 'data' )->exists( 'mod/html-blocks/items', $id ) ) {
			return $this->main( 'api' )->notfound();
		}

		$record = array(
			'name' => $name,
			'desc' => $desc,
			'html' => $html,
		);

		if ( 'add' === $action ) {
			$record['_id'] = $this->main( 'data' )->insert(
				'mod/html-blocks/items',
				$record,
				array(
					'_ct' => time(),
				)
			);

			return $this->main( 'api' )->success( $record );
		}

		if ( 'edit' === $action ) {
			$this->main( 'data' )->merge( 'mod/html-blocks/items', $id, $record );
			$record['_id'] = $id;

			return $this->main( 'api' )->success( $record );
		}

		return $this->main( 'api' )->bad();
	}

	public function list() {
		$items = $this->main( 'data' )->all( 'mod/html-blocks/items' );

		return $this->main( 'api' )->success( $items );
	}

	public function delete( $args ) {
		$id = sanitize_key( $args['POST']['id'] ?? '' );

		if ( empty( $id ) ) {
			$this->main( 'api' )->bad();
		}

		$this->main( 'data' )->rem( 'mod/html-blocks/items', $id );

		return $this->main( 'api' )->success();
	}

	public function shortcode( $atts ) {
		$id = is_array( $atts ) && isset( $atts['id'] ) ? $atts['id'] : '';

		if ( ! empty( $id ) ) {
			$block = $this->main( 'data' )->get( 'mod/html-blocks/items', $id );

			if ( $block && isset( $block['html'] ) && ! empty( $block['html'] ) ) {
				return wp_kses_post( apply_filters( 'the_content', $block['html'] ) );
			}
		}
	}
}

Mod::init( 'html-blocks', Html_Blocks::class );
