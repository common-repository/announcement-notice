<?php

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'my_scripts' );
add_action( 'admin_enqueue_scripts', 'my_admin_scripts' );

function my_scripts() {
    // Enqueue CSS file
    wp_enqueue_style( 'wacm-style', WACM_FILE . 'assets/css/style.css', array(), WACM_VERSION , 'all', false );

    // Enqueue JavaScript file
    wp_enqueue_script( 'wacm-script', WACM_FILE . 'assets/js/script.js', array(), WACM_VERSION , true );
}


function my_admin_scripts() {
    // Enqueue CSS file for admin
    wp_enqueue_style( 'wacm-admin-style', WACM_FILE . 'assets/css/admin.css', array(), WACM_VERSION , 'all', false );

    // Enqueue CSS file for cute alert
    wp_enqueue_style( 'wacm-admin-cute-alert-style', WACM_LIBS_CUTE_ALERT . '/style.css', array(), WACM_VERSION , 'all', false );
   
    // Enqueue JavaScript file admin
    wp_enqueue_script( 'wacm-admin-script', WACM_FILE . 'assets/js/admin.js', array(), WACM_VERSION , true );

    //Enqueue Cute Alert Libs scripts
    wp_enqueue_script( 'wacm-admin-cute-alert-script', WACM_LIBS_CUTE_ALERT . '/cute-alert.js', array(), WACM_VERSION , true );

    wp_localize_script('wacm-admin-script', 'api_base_url', array(
        'root_url' 	  => get_rest_url(),
        'siteurl' => get_option('siteurl'),
        'nonce'   => wp_create_nonce('wp_rest'),
    ));
}

