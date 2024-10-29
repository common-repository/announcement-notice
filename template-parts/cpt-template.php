
<label id="<?php echo esc_attr( $post_id ); ?>" class="switch">
    <input value="<?php echo esc_attr( $post_id ); ?>" onChange="enable_disable_notice(this)" class="announcement-switch" type="checkbox" <?php echo esc_html($checking_text) ?>>
    <span class="slider round"></span>
</label>