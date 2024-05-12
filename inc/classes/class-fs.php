<?php

namespace Palacify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Fs {

	public function method() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	public function exists( $path ) {
		return $this->method()->exists( $path );
	}

	public function is_writable( $path ) {
		return $this->method()->is_writable( $path );
	}

	public function is_fully_writable( $path ) {
		if ( ! $this->is_writable( $path ) ) {
			return false;
		}

		if ( $this->is_file( $path ) ) {
			return true;
		}

		foreach ( $this->scandir( $path ) as $file ) {
			if ( '.' !== $file && '..' !== $file ) {
				$file = $path . '/' . $file;

				if ( ( $this->is_dir( $file ) && ! $this->is_fully_writable( $file ) ) || ! $this->is_writable( $file ) ) {
					return false;
				}
			}
		}

		return true;
	}

	public function get_contents( $path ) {
		return $this->method()->get_contents( $path );
	}

	public function put_contents( $path, $data, $chmod = false ) {
		return $this->method()->put_contents( $path, $data, $chmod );
	}

	public function delete( $path, $recursive = false ) {
		return $this->method()->delete( $path, $recursive );
	}

	public function rmdir( $path, $recursive = false ) {
		return $this->method()->rmdir( $path, $recursive );
	}

	public function mkdir( $path, $chmod = false, $chown = false, $chgrp = false ) {
		return $this->method()->mkdir( $path, $chmod, $chown, $chgrp );
	}

	public function is_dir( $path ) {
		return $this->method()->is_dir( $path );
	}

	public function is_file( $path ) {
		return $this->method()->is_file( $path );
	}

	public function mkdir_p( $path, $chmod = false ) {
		if ( empty( $path ) || $this->is_dir( $path ) ) {
			return true;
		}

		if ( ! $this->mkdir_p( dirname( $path ), $chmod ) ) {
			return false;
		}

		if ( ! $this->mkdir( $path, $chmod ) ) {
			return false;
		}

		return true;
	}

	public function scandir( $dir ) {
		$items  = $this->method()->dirlist( $dir );
		$result = array();

		foreach ( $items as $name => $_ ) {
			$result[] = $name;
		}

		return $result;
	}
}
