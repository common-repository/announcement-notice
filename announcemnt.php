<?php

/**
 * plugin name: Announcement Notice Builder for Elementor - Enhance Your Visitor Engagement
 * plugin uri: https://wordpress.org/plugins/announcement-notice/ 
 * description: Simple announcemnet notice showing plugin for wordpress. Build/Edit notice bar with Elementor.
 * author: wpsharif
 * version: 1.2.4
 * author uri: thesharif.dev
 * text-domain: announcement-notice
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */

 defined( 'ABSPATH' ) || exit;

 final class Announcement {

    public function __construct(){

        //define all constants
        $this->define_constants();

        //load the text domain for localization
        $this->load_text_domain();
            
        //check the prerequisites required to run the plugin
        add_action('plugins_loaded', [$this, 'init']);

        $this->save_userinfo();
    }

    private function define_constants(){

        define('WACM_VERSION', '1.2.4');
        define('WACM_DIR', plugin_dir_path( __FILE__ ));
        define('WACM_FILE', plugin_dir_url( __FILE__ ));
        define('WACM_LANG_DIR', WACM_DIR.'/languages');
        define('WACM_LIBS', WACM_FILE.'/includes/libs');
        define('WACM_LIBS_CUTE_ALERT', WACM_LIBS.'/cute-alert-master');
        define('WACM_ASSETS', WACM_DIR.'/assets');
        define('WACM_IMG_DIR', WACM_ASSETS.'/img');
        define('WACM_MENU_ICON', WACM_IMG_DIR.'/annoncement-notice-icon.png');
    }

    private function save_userinfo(){

        $first_installation = get_option('wacm_install_date');

        if($first_installation){
            return;
        }

        update_option( 'wacm_install_data', time() );
    }

  // Function to load the text domain for localization
	public function load_text_domain() {
		load_plugin_textdomain('announcement-notice', false, WACM_LANG_DIR);
	}
    public function init(){

        // Check if Elementor plugin is loaded, if not display admin notice
		if ( ! did_action( 'elementor/loaded' ) ) {
            
            add_action('admin_notices', [$this, 'missing_elementor']);

			return;
		}
        
        include WACM_DIR.'/includes/init.php';

        $api_instance = new Api();
        $api_instance->init();
    }

    /**
	 * Admin notice
	 *
	 * Check elementor version and activation
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function missing_elementor() {
        ?>
       <style>
            .wacm-admin-notice{
                padding: .5rem !important;
            }
            .wacm-admin-notice .wacm-admin-notice-link{
                padding: 0.25rem 0.5rem;
                background: #2271b1;
                color: #fff;
                border-radius: 0.25rem;
                text-decoration: none;
                font-size: .75rem;
            }
        </style>
        <?php
		
        //define the message to display
		$message_content = file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php') ? 
        "Announcement Notice requires Elementor plugin. Please activate Elementor" : 
        "Announcement Notice requires Elementor plugin. Please install and activate Elementor";

        //define the button label and url based on whether the plugin is installed or not
        $btn['label'] = file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php') ?
            esc_html__('Activate Elementor', 'announcement-notice') :
            esc_html__('Install Elementor', 'announcement-notice');
        $btn['url'] = file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php') ? 
            wp_nonce_url('plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php') :
            wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');

        //generate and display the admin notice
        $message = sprintf(esc_html__($message_content, 'announcement-notice'));
        $html_message = sprintf('<div class="error wacm-admin-notice"> %s <a class="wacm-admin-notice-link" href="%s"> %s</a> </div>', wpautop($message), $btn['url'], $btn['label']);
        echo wp_kses_post($html_message);

	}
 }

 //start the plugin
 new Announcement();
 