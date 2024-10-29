<?php 

defined( 'ABSPATH' ) || exit;

class Cpt {

	public function __construct() {
		add_action('elementor/init', array($this, 'post_type'));
		add_action( 'admin_init', array( $this, 'add_author_support_to_column' ), 10 );
		add_filter( 'manage_announcement_notice_posts_columns', array( $this, 'set_columns' ) );
		add_action( 'manage_announcement_notice_posts_custom_column', array( $this, 'render_column' ), 10, 2 );
	}

	public function post_type() {
		
		$labels = array(
			'name'               => esc_html__( 'Notice', 'announcement-notice' ),
			'singular_name'      => esc_html__( 'Notice', 'announcement-notice' ),
			'menu_name'          => esc_html__( 'Create Notice', 'announcement-notice' ),
			'name_admin_bar'     => esc_html__( 'Create Notice', 'announcement-notice' ),
			'add_new'            => esc_html__( 'Add New', 'announcement-notice' ),
			'add_new_item'       => esc_html__( 'Add New Notice', 'announcement-notice' ),
			'new_item'           => esc_html__( 'New Notice', 'announcement-notice' ),
			'edit_item'          => esc_html__( 'Edit Notice', 'announcement-notice' ),
			'view_item'          => esc_html__( 'View Notice', 'announcement-notice' ),
			'all_items'          => esc_html__( 'All Notices', 'announcement-notice' ),
			'search_items'       => esc_html__( 'Search Notice', 'announcement-notice' ),
			'parent_item_colon'  => esc_html__( 'Parent Notice:', 'announcement-notice' ),
			'not_found'          => esc_html__( 'No Notice found.', 'announcement-notice' ),
			'not_found_in_trash' => esc_html__( 'No Notice found in Trash.', 'announcement-notice' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'show_in_menu'        => 'announcement-notice',
			'hierarchical'        => false,
			'capability_type'     => 'page',
			'supports'            => array( 'title', 'thumbnail', 'elementor' ),
		);

		register_post_type( 'announcement_notice', $args );
	}

	public function add_author_support_to_column() {
		add_post_type_support( 'announcement_notice', 'author' ); 
	}

	public function set_columns( $columns ) {

		$date_column   = $columns['date'];
		$author_column = $columns['author'];

		unset( $columns['date'] );
		unset( $columns['author'] );

		$columns['action']      = esc_html__( 'Enable/Disable', 'announcement-notice' );
		$columns['author']    = $author_column;
		$columns['date']      = $date_column;

		return $columns;
	}

	public function status_checking( $post_id ){

		$args = array(
			'post_type' => 'announcement_notice',
			'p' => $post_id, // Specify the post ID to retrieve
			'meta_key' => 'announcement_notice_enable',
			'meta_value' => 1
		);
	
		$query = new WP_Query($args);
	
		// Check if a post with the specified ID and meta_key/meta_value exists
		if ($query->have_posts()) {
			return true; // Return true if a post exists with the specified ID and meta_key/meta_value
		} else {
			return false; // Return false if a post does not exist with the specified ID and meta_key/meta_value
		}
	}	

	public function render_column( $column, $post_id ) {
		$checking_text = $this->status_checking($post_id) ? "checked" : "";
		switch ( $column ) {
			case 'action':
				include WACM_DIR.'/template-parts/cpt-template.php';
				break;
		}
	}
}

new Cpt();
