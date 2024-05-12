<?php

namespace Palacify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Data_FS extends Data {

	use Internal;

	public $db;
	public $fs;

	public function __construct( $db = true ) {
		if ( $db ) {
			$this->db = new Data_DB( false );
		}

		$this->fs = $this;
	}

	private function file( $scope, $key ) {
		$scope = $this->norm( $scope );
		$key   = $this->norm( $key );

		return PALACIFY__DATA_DIR . '/' . $scope . '/' . $key . '.php';
	}

	public function set( $scope, $key, $data ) {
		$file = $this->file( $scope, $key );
		$dir  = dirname( $file );

		if ( ! file_exists( $dir ) && ! $this->main( 'fs' )->mkdir_p( $dir, PALACIFY__DATA_DIR_PERM ) ) {
			return false;
		}

		if ( false === $this->main( 'fs' )->put_contents( $file, '<?php exit; ?>' . serialize( $data ) ) ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			return false;
		}

		return $key;
	}

	public function get( $scope, $key, $default_value = null, $ttl = -1, $unserialize = true ) {
		if ( $this->exists( $scope, $key, $ttl ) ) {
			$file    = $this->file( $scope, $key );
			$content = $this->main( 'fs' )->get_contents( $file );

			if ( $unserialize ) {
				return unserialize( substr( $content, 14 ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
			}

			return substr( $content, 14 );
		}

		return $default_value;
	}

	public function exists( $scope, $key, $ttl = -1 ) {
		$file = $this->file( $scope, $key );

		if ( file_exists( $file ) ) {
			$age = time() - filemtime( $file );

			if ( $ttl < 0 || $age <= $ttl ) {
				return true;
			}
		}

		return false;
	}

	public function rem( $scope, $key ) {
		$file = $this->file( $scope, $key );

		if ( file_exists( $file ) ) {
			return \wp_delete_file( $file );
		}

		return false;
	}

	public function age( $scope, $key ) {
		$file = $this->file( $scope, $key );

		if ( file_exists( $file ) ) {
			return time() - filemtime( $file );
		}

		return false;
	}

	public function list( $scope ) {
		$scope = $this->norm( $scope );
		$dir   = PALACIFY__DATA_DIR . '/' . $scope;
		$items = array();

		foreach ( $this->main( 'fs' )->scandir( $dir ) as $item ) {
			if ( preg_match( '/\.php$/', $item ) ) {
				$items[] = substr( $item, 0, -4 );
			}
		}

		return $items;
	}

	public function rmrf( $scope ) {
		$scope = $this->norm( $scope );
		$scope = trim( $scope, '/' );
		$path  = PALACIFY__DATA_DIR . '/' . $scope;

		return $this->main( 'fs' )->delete( $path, true );
	}

	public function append( $scope, $key, $data, $separator = "\n" ) {
		$file = $this->file( $scope, $key );
		$dir  = dirname( $file );

		if ( ! file_exists( $dir ) && ! $this->main( 'fs' )->mkdir_p( $dir, PALACIFY__DATA_DIR_PERM ) ) {
			return false;
		}

		$separator = file_exists( $file ) ? $separator : '<?php exit; ?>';
		$separator = $separator ? $separator : '';

		return error_log( $separator . $data, 3, $file ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
