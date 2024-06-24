<?php

namespace Palacify;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {

	public static function init() {
		return new self();
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function add_admin_menu() {
		$icon = PALACIFY__PLUGIN_URL . '/assets/img/icon.svg';

		add_menu_page(
			'Palacify',                      // Page title
			'Palacify',                      // Menu title
			PALACIFY__ACCESS_USER_CAP,       // Capability
			'palacify',                      // Menu slug
			array( $this, 'admin_page' ), // Callback
			$icon,                        // Icon URL
			40,                           // Position
		);
	}

	public function enqueue_assets( $hook ) {
		if ( 'toplevel_page_palacify' !== $hook ) {
			return;
		}

		wp_enqueue_script( 'palacify-bundle', PALACIFY__PLUGIN_URL . '/assets/admin/bundle.js', array(), PALACIFY__VERSION, true );
		wp_enqueue_style( 'palacify-bundle', PALACIFY__PLUGIN_URL . '/assets/admin/bundle.css', array(), PALACIFY__VERSION );
		wp_enqueue_style( 'palacify-admin', PALACIFY__PLUGIN_URL . '/assets/admin/style.css', array(), PALACIFY__VERSION );
		wp_enqueue_style( 'palacify-general', PALACIFY__PLUGIN_URL . '/assets/general.css', array(), PALACIFY__VERSION );
		wp_enqueue_style( 'palacify-ui', PALACIFY__PLUGIN_URL . '/assets/admin/ui.css', array(), PALACIFY__VERSION );

		// Inline scripts.
		wp_add_inline_script(
			'palacify-bundle',
			sprintf(
				'window.palacify = { ajaxurl: "%s", _wpnonce: "%s" };',
				esc_url( admin_url( 'admin-ajax.php' ) ),
				wp_create_nonce( 'palacify-api' ),
			),
			'before'
		);
	}

	public function admin_page() {
		include PALACIFY__PLUGIN_DIR . '/templates/admin.php';
	}
}
