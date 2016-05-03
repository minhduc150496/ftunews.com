<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<hr>

<p>
    <label>
        <input type="checkbox" data-toggler="private-results" name="tp_options[limitations][private_results]" value="1" <?php checked(true, isset($options->limitations->private_results)); ?>> <?php _e('Keep results private', 'tp-pr-addon') ?>
    </label>
</p>

<div data-toggle="private-results" class="<?php echo isset($options->limitations->private_results)
            ? '' : 'hide';
?>">
    <hr>
    <p>
<?php _e('Show results when', 'tp-pr-addon'); ?>
    </p>
    <p>
        <label>
            <input type="radio" name="tp_options[limitations][show_results_after]" value="quota" <?php checked('quota', isset($options->limitations->show_results_after)
                    ? $options->limitations->show_results_after : 'never');
?>> <?php _e('Quota reached', 'tp-pr-addon') ?>
        </label>
        <label>
            <input type="radio" name="tp_options[limitations][show_results_after]" value="date" <?php checked('date', isset($options->limitations->show_results_after)
                    ? $options->limitations->show_results_after : 'never');
?>> <?php _e('End date reached', 'tp-pr-addon') ?>
        </label>
        <label>
            <input type="radio" name="tp_options[limitations][show_results_after]" value="never" <?php checked('never', isset($options->limitations->show_results_after)
                    ? $options->limitations->show_results_after : 'never');
?>> <?php _e('Never', 'tp-pr-addon') ?>
        </label>
    </p>
    <hr>
    <p><?php _e('Results content (eg. Thank you)', 'tp-pr-addon'); ?></p>
    <p></p>
    <table class="wp-list-table widefat">
        <thead>
            <tr>
                <th width="20%"><?php _e('Tag', 'tp-pr-addon'); ?></th>
                <th><?php _e('Replaced with', 'tp-pr-addon'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>[results]</td>
                <td><?php _e('Results of the poll', 'tp-pr-addon'); ?></td>
            </tr>
        </tbody>
    </table>
    <?php
    wp_editor(isset(
                    $options->limitations->private_results_content) ? $options->limitations->private_results_content
                        : '', 'private-results-content', array( 'textarea_name' => 'tp_options[limitations][private_results_content]' )
    );
    ?>
</div>