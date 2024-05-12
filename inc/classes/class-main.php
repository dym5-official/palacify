<?php

namespace Palacify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Main {

	protected $props = array();
	protected $file  = null;

	public function __construct( $file ) {
		$this->file = $file;

		$this->props['fs']   = new Fs();
		$this->props['data'] = 'db' === PALACIFY__DATA_STORE ? new Data_DB() : new Data_FS();
		$this->props['log']  = new Log();
		$this->props['http'] = new Http();
		$this->props['api']  = new Api();

		Admin::init();
	}

	public function activation() {
		global $wpdb;

        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
		$wpdb->query(
			$wpdb->prepare(
				'CREATE TABLE IF NOT EXISTS %i (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`_key` varchar(255) DEFAULT NULL,
				`_data` text DEFAULT NULL,
				`_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
				`_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				PRIMARY KEY (`id`),
				UNIQUE KEY `unique_key` (`_key`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;',
				$wpdb->prefix . '' . PALACIFY__DATA_DB_TABLE,
			)
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
	}

	public function __get( $prop ) {
		if ( isset( $this->props[ $prop ] ) ) {
			return $this->props[ $prop ];
		}
	}
}
