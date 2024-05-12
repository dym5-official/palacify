<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function palacify() {

	if ( ! isset( $GLOBALS['PALACIFY'] ) || ! $GLOBALS['PALACIFY'] ) {
		$GLOBALS['PALACIFY'] = new \Palacify\Main();
	}

	return $GLOBALS['PALACIFY'];
}
