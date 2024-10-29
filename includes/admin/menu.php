<?php 

defined( 'ABSPATH' ) || exit;

class Menu {
	
	public static function key() {
		return 'announcement-notice';
	}

	public function __construct() {
		// register admin menus
		add_action( 'admin_menu', array( $this, 'register_settings_menus' ) );

	}


	public function register_settings_menus() {

		// dashboard, main menu
		add_menu_page(
			esc_html__( 'Announcement Notice Settings', 'announcement-notice' ),
			esc_html__( 'Announcement', 'announcement-notice' ),
			'manage_options',
			self::key(),
			array( $this, 'register_settings_contents__settings' ),
			"dashicons-megaphone",
			'60'
		);
	}


	public function register_settings_contents__settings() {
		//no need to do anything
	}
}

new Menu();
