<?php if ( !defined('ABSPATH') ) exit; // Shhh  ?>
<!-- .misc -->
<div class="misc section <?php echo!isset($last_opened_tabs['misc']) ? 'collapsed' : '' ?>">
    <input type="checkbox" name="tp_opened_tabs[misc]" class="tab-state" <?php checked(isset($last_opened_tabs['misc']), true); ?>>
    <h3 class="title"><?php _e('Misc', TP_TD); ?></h3>
    <div class="content">
        <?php do_tp_action('tp_admin_editor_before_misc_content', $options); ?>
        <p>
            <label>
                <input type="checkbox" name="tp_options[misc][orderby_votes]" value="1" <?php checked(true, isset($options->misc->orderby_votes)); ?> data-toggler="order-votes"> <?php _e('Order results by votes', TP_TD) ?>
            </label>
        </p>
        <p data-toggle="order-votes" class="<?php echo isset($options->misc->orderby_votes) ? '' : 'hide'; ?>">
            <label>
                <input type="radio" name="tp_options[misc][orderby_votes_direction]" value="asc" <?php checked('asc', isset($options->misc->orderby_votes_direction) ? $options->misc->orderby_votes_direction : ''); ?>> <?php _e('Ascending', TP_TD) ?>
            </label>
            <label>
                <input type="radio" name="tp_options[misc][orderby_votes_direction]" value="desc" <?php checked('desc', isset($options->misc->orderby_votes_direction) ? $options->misc->orderby_votes_direction : ''); ?>> <?php _e('Descending', TP_TD) ?>
            </label>
        </p>

        <hr>

        <p>
            <label>
                <input type="checkbox" name="tp_options[misc][shuffle]" value="1" <?php checked(true, isset($options->misc->shuffle)); ?>> <?php _e('Shuffle choices order', TP_TD); ?>
            </label>
        </p>

        <hr>

        <p><?php _e('Results are shown as', TP_TD); ?></p>

        <p>
            <label>
                <input type="radio" name="tp_options[misc][show_results]" value="number" <?php checked('number', isset($options->misc->show_results) ? $options->misc->show_results : ''); ?>> <?php _e('Number', TP_TD) ?>
            </label>
            <label>
                <input type="radio" name="tp_options[misc][show_results]" value="percentage" <?php checked('percentage', isset($options->misc->show_results) ? $options->misc->show_results : ''); ?>> <?php _e('Percentage', TP_TD) ?>
            </label>
            <label>
                <input type="radio" name="tp_options[misc][show_results]" value="both" <?php checked('both', isset($options->misc->show_results) ? $options->misc->show_results : ''); ?>> <?php _e('Both', TP_TD) ?>
            </label>
            <label>
                <input type="radio" name="tp_options[misc][show_results]" value="nothing" <?php checked('nothing', isset($options->misc->show_results) ? $options->misc->show_results : ''); ?>> <?php _e('Nothing', TP_TD) ?>
            </label>
        </p>
        <?php do_tp_action('tp_admin_editor_after_misc_content', $options); ?>
    </div>
</div>
<!-- /.misc -->