<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<div class="wrap">
    <h2><?php _e('Google Analytics Code', 'tp-ga-addon'); ?></h2>
    <?php if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ): ?>
        <div class="updated">
            <p><?php _e('Changes has been saved.', 'tp-ga-addon'); ?></p>
        </div>
    <?php endif; ?>
    <p><?php _e("Insert Google Analytics Code with &lt;script&gt; tag. Leave this field empty if you already have it.", 'tp-ga-addon'); ?></p>
    <form method="post" action="options.php">
        <?php settings_fields('tp-ga-settings'); ?>
        <?php do_settings_sections('tp-ga-settings'); ?>
        <textarea name="tp_ga_code" rows="10" class="widefat"><?php echo esc_attr(get_option('tp_ga_code')); ?></textarea>
        <?php submit_button(); ?>
    </form>
</div>