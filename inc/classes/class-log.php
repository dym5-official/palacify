<?php

namespace Palacify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Log {

	use Internal;

	private function normalize( $prefix, $data ) {
		if ( ! is_string( $data ) ) {
			$data = \wp_json_encode( $data );
		}

		return $prefix . '' . $data;
	}

	private function get_time() {
		return date( 'Y-m-d H:i:s' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	}

	public function add_wo_time( $scope, $key, $data, $prefix = '' ) {
		return $this->main( 'data' )->append( $scope, $key, $this->normalize( $prefix, $data ) );
	}

	public function add( $scope, $key, $data, $prefix = '' ) {
		return $this->main( 'data' )->append( $scope, $key, '[' . $this->get_time() . '] ' . $this->normalize( $prefix, $data ) );
	}

	public function info( $scope, $key, $data ) {
		return $this->add( $scope, $key, $data, '..INFO... ' );
	}

	public function error( $scope, $key, $data ) {
		return $this->add( $scope, $key, $data, '..ERROR.. ' );
	}

	public function warn( $scope, $key, $data ) {
		return $this->add( $scope, $key, $data, '..WARN... ' );
	}

	public function get( $scope, $key ) {
		return $this->main( 'data' )->read( $scope, $key );
	}

	public function age( $scope, $key ) {
		return $this->main( 'data' )->age( $scope, $key );
	}

	public function rem( $scope, $key ) {
		return $this->main( 'data' )->rem( $scope, $key );
	}
}
