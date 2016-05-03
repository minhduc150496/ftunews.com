<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<div class="wrap">
    <h2><?php _e('IP Restriction', 'tp-ipr-addon'); ?></h2>
    <?php if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ): ?>
        <div class="updated">
            <p><?php _e('Changes has been saved.', 'tp-ipr-addon'); ?></p>
        </div>
    <?php endif; ?>
    <form method="post" action="options.php">
        <?php settings_fields('tp-ipr-settings'); ?>
        <?php do_settings_sections('tp-ipr-settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php _e('Whitelist IPs', 'tp-ipr-addon'); ?>
                </th>
                <td>
                    <textarea name="tp_ipr_whitelist" rows="10" class="widefat"><?php echo esc_attr(get_option('tp_ipr_whitelist')); ?></textarea>
                    <p class="description"><?php _e('Use * for wildcard', 'tp-ipr-addon'); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Blacklist IPs', 'tp-ipr-addon'); ?></th>
                <td>
                    <textarea name="tp_ipr_blacklist" rows="10" class="widefat"><?php echo esc_attr(get_option('tp_ipr_blacklist')); ?></textarea>
                    <p class="description"><?php _e('Use * for wildcard', 'tp-ipr-addon'); ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"></th>
                <td>
                    <?php submit_button(); ?>
                </td>
            </tr>
        </table>
    </form>
</div>