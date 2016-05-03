<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<hr>

<p>
    <label>
        <input type="checkbox" data-toggler="ip-restrict" name="tp_options[limitations][restrict_by_ip]" value="1" <?php checked(true, isset($options->limitations->restrict_by_ip)); ?>> <?php _e('Restrict vote by ip', 'tp-ipr-addon') ?>
    </label>
</p>

<p data-toggle="ip-restrict" class="<?php echo isset($options->limitations->restrict_by_ip)
            ? '' : 'hide'; ?>">
    <label>
        <input type="radio" name="tp_options[limitations][ip][list]" value="whitelist" <?php checked('whitelist', isset($options->limitations->ip->list)
                    ? $options->limitations->ip->list : 'whitelist'); ?>><?php printf(__('Use <a href="%s">Whitelist</a>', 'tp-ipr-addon'), admin_url('edit.php?post_type=poll&page=tp-ip-restrict')); ?>
    </label>
    <label>
        <input type="radio" name="tp_options[limitations][ip][list]" value="blacklist" <?php checked('blacklist', isset($options->limitations->ip->list)
                    ? $options->limitations->ip->list : ''); ?>><?php printf(__('Use <a href="%s">Blacklist</a>', 'tp-ipr-addon'), admin_url('edit.php?post_type=poll&page=tp-ip-restrict')); ?>
    </label>
</p>