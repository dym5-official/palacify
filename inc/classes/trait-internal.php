<?php

namespace Palacify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Internal {

	public function main( $key = null ) {
		if ( null !== $key ) {
			return palacify()->$key;
		}

		return palacify();
	}
}
