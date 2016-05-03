<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<hr>

<p>
    <label>
        <input type="checkbox" data-toggler="pagination" name="tp_options[misc][pagination]" value="1" <?php checked(true, isset($options->misc->pagination)); ?>> <?php _e('Pagination', 'tp-pager-addon') ?>
    </label>
</p>

<p data-toggle="pagination" class="<?php echo isset($options->misc->pagination)
            ? '' : 'hide'; ?>">
    <label>
        <span><?php _e('Choices per page', 'tp-pager-addon'); ?></span>
        <input type="text" name="tp_options[misc][per_page]" value="<?php echo isset($options->misc->per_page)
            ? $options->misc->per_page : 5; ?>">
    </label>
</p>
<p data-toggle="pagination" class="<?php echo isset($options->misc->pagination)
            ? '' : 'hide'; ?>">
    <label>
        <input type="checkbox" name="tp_options[misc][display_pages]" value="1" <?php checked(true, isset($options->misc->display_pages)); ?>><?php _e('Show pages', 'tp-pager-addon'); ?>
    </label>
</p>