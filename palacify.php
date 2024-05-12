<?php
/**
 * Plugin Name: Palacify
 * Description: Custom HTML with shortcode.
 * Version: 1.0.0
 * Requires PHP: 7.2
 * Requires at least: 6.2
 * Author: DYM5
 * Author URI: https://dym5.com/
 * Text Domain: palacify
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$consts = array(
	'PALACIFY__DATA_STORE'      => 'db',
	'PALACIFY__DATA_DIR'        => WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'palacify',
	'PALACIFY__DATA_DIR_PERM'   => 0755,
	'PALACIFY__DATA_DB_TABLE'   => 'palacify',
	'PALACIFY__ACCESS_USER_CAP' => 'edit_posts',
);

foreach ( $consts as $const => $value ) {
	if ( ! defined( $const ) ) {
		define( $const, $value );
	}
}

define( 'PALACIFY__VERSION', '1.0.0' );
define( 'PALACIFY__PLUGIN_DIR', __DIR__ );
define( 'PALACIFY__BASENAME', basename( __DIR__ ) );
define( 'PALACIFY__PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

spl_autoload_register(
	function ( $class_name ) {
		$class_name = ltrim( $class_name, '\\' );
		$class_name = str_replace( '\\', '/', $class_name );
		$class_name = str_replace( '_', '-', $class_name );
		$class_name = ltrim( $class_name, '-' );
		$class_name = strtolower( $class_name );

		$namespace = 'palacify/';

		if ( 0 === strpos( $class_name, $namespace ) ) {
			$class_name = substr( $class_name, strlen( $namespace ) );
			$file1      = __DIR__ . '/inc/classes/class-' . $class_name . '.php';
			$file2      = __DIR__ . '/inc/classes/trait-' . $class_name . '.php';

			if ( file_exists( $file1 ) ) {
				require_once $file1;
				return;
			}

			if ( file_exists( $file2 ) ) {
				require_once $file2;
			}
		}
	}
);

$GLOBALS['PALACIFY'] = new \Palacify\Main( __FILE__ );

require_once __DIR__ . '/inc/funcs/functions.php';

register_activation_hook( __FILE__, array( $GLOBALS['PALACIFY'], 'activation' ) );

require_once __DIR__ . '/mods/index.php';
