<?php

defined('ABSPATH') || exit;

class Api {

	private static $instance;

	protected $version = 1;

    public static function instance() {
		if(!self::$instance) {
			self::$instance = new static();
		}

		return self::$instance;
	}
	public function init() {

		add_action('rest_api_init', function() {

			register_rest_route('announcement-notice/v' . $this->version, '/notice-enable', array(
					'methods'             => 'POST',
					'callback'            => [$this, 'enable_notice'],
					'permission_callback' => function() {
						return true;
					},
				)
			);
		});
	}


	public function enable_notice(\WP_REST_Request $request) {

		if ( !isset($request['nonce']) || !wp_verify_nonce(sanitize_text_field($request['nonce']), 'wp_rest')) {
			return new WP_Error('invalid_nonce', 'Invalid nonce', array('status' => 403));
		}
        
        $post_id = sanitize_text_field( $request['post_id'] );
        $is_active = sanitize_text_field( $request['is_active'] );

        update_post_meta($post_id, 'announcement_notice_enable', $is_active);

        $notice_status = get_post_meta($post_id, 'announcement_notice_enable', true);

        return $notice_status;
	}
}
