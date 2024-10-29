<?php

defined( 'ABSPATH' ) || exit;

class AnnouncementHooks {

  public function __construct() {
      add_action('wp_loaded', [$this, 'init']);
  }

  public function init() {
      add_action('wp_footer', [$this, 'wp_announcement']);
      add_shortcode('announcement_shortcode', [$this, 'my_elementor_shortcode']);
  }
  public function wp_announcement(){

      $post_id = '';

      $args = array(
        'post_type' => 'announcement_notice',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_key' => 'announcement_notice_enable',
        'meta_value' => 1
      );
      
      $query = new WP_Query($args);
      
      if ($query->have_posts()) {
          while ($query->have_posts()) {
              $query->the_post();
              $post_ids[] = get_the_ID(); // Retrieve the post ID
              // Display the post ID
          }

          $post_id = $post_ids[0];
      }
      
      // Restore original post data
      wp_reset_postdata();
      
      ?>
          <div class="wacm-main announcement-notice-wrapper"> 
            <?php echo do_shortcode('[announcement_shortcode post_id="'.$post_id.'"]') //phpcs:ignore just a shortcode showing; ?>
          </div>
      <?php
  }

    public function my_elementor_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => '10',
            'post_id' => ''
        ), $atts, 'my_elementor');

        $post_id = ! empty( $atts['post_id'] ) ? $atts['post_id'] : $atts['id'];

        if (empty($post_id)) {
            return '';
        }

        // Get the post status
        $post_status = get_post_status($post_id);

        // Return empty string if the post status is not 'publish'
        if ( $post_status !== 'publish' ) {
            return '';
        }

        ob_start();

        if (function_exists('elementor_pro_load_plugin')) {
            // Load Elementor Pro's Frontend class
            $frontend = \ElementorPro\Plugin::instance()->frontend;

            // Set the current post ID to the Elementor page ID
            $frontend->set_post_id($post_id);

            // Output the Elementor page content
            $frontend->get_builder_content(true);
        } else {
            // Load Elementor's Frontend class
            $frontend = \Elementor\Plugin::instance()->frontend;

            $content = $frontend->get_builder_content_for_display($post_id, false);
            return $content;
        }

        $content = ob_get_clean();

        return $content;
    }
}

new AnnouncementHooks();