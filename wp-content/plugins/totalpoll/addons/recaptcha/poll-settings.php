<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<hr>

<p>
    <label>
        <input type="checkbox" name="tp_options[limitations][captcha]" value="1" <?php checked(true, isset($options->limitations->captcha)); ?>> <?php _e('Captcha', 'tp-captcha-addon') ?>
    </label>
</p>