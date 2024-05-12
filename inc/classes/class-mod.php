<?php

namespace Palacify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mod {

	private static $mods = array();

	public static function init( $name, $classname ) {
		self::$mods[ $name ] = new $classname();
	}

	public static function get( $name ) {
		return isset( self::$mods[ $name ] ) ? self::$mods[ $name ] : null;
	}
}
