<?php if ( !defined('ABSPATH') ) exit; // Shhh  ?>
<!-- .shortcode -->
<div class="shortcode section <?php echo!isset($last_opened_tabs['shortcode']) ? 'collapsed' : '' ?>">
    <input type="checkbox" name="tp_opened_tabs[shortcode]" class="tab-state" <?php checked(isset($last_opened_tabs['shortcode']), true); ?>>
    <h3 class="title"><?php _e('Shortcode', TP_TD); ?></h3>
    <div class="content">
        <?php do_tp_action('tp_admin_editor_before_shortcode_content', $options); ?>
        <input type="text" class="widefat" value="[total-poll id=<?php echo get_the_ID(); ?>]" readonly="readonly">
        <?php do_tp_action('tp_admin_editor_after_shortcode_content', $options); ?>
    </div>
</div>
 <!--/.shortcode -->