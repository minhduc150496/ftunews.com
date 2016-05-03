<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<div class="wrap">
    <h2><?php _e('reCaptcha Settings', 'tp-captcha-addon'); ?></h2>
    <?php if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ): ?>
        <div class="updated">
            <p><?php _e('Changes has been saved.', 'tp-captcha-addon'); ?></p>
        </div>
    <?php endif; ?>
    <p><?php _e('You can get private & public keys from', 'tp-captcha-addon'); ?> <a href="https://www.google.com/recaptcha/" target="_blank">Google reCaptcha</a>.</p>
    <form method="post" action="options.php">
        <?php settings_fields('tp-captcha-settings'); ?>
        <?php do_settings_sections('tp-captcha-settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php _e('Public key', 'tp-captcha-addon'); ?>
                </th>
                <td>
                    <input type="text" name="tp_captcha_public_key" class="widefat" value="<?php echo esc_attr(get_option('tp_captcha_public_key')); ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Private key', 'tp-captcha-addon'); ?>
                </th>
                <td>
                    <input type="text" name="tp_captcha_private_key" class="widefat" value="<?php echo esc_attr(get_option('tp_captcha_private_key')); ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php _e('Users', 'tp-captcha-addon'); ?>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="tp_captcha_only_for_guests" class="widefat" value="1" <?php checked(1, esc_attr(get_option('tp_captcha_only_for_guests'))); ?>>
                        <?php _e('Only for guests.', 'tp-captcha-addon'); ?>
                    </label>
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