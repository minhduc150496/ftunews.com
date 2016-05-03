<?php if ( !defined('ABSPATH') ) exit; // Shhh  ?>
<!-- .limirations -->
<div class="limitations section <?php echo!isset($last_opened_tabs['limitations']) ? 'collapsed' : '' ?>">
    <input type="checkbox" name="tp_opened_tabs[limitations]" class="tab-state" <?php checked(isset($last_opened_tabs['limitations']), true); ?>>
    <h3 class="title"><?php _e('Limitations', TP_TD); ?></h3>
    <div class="content">
        
        <?php do_tp_action('tp_admin_editor_before_limitations_content', $options); ?>
        <p><?php _e('Prevent re-vote using', TP_TD); ?></p>
        <p>
            <label>
                <input type="checkbox" name="tp_options[limitations][revote][session]" value="1" <?php checked(true, isset($options->limitations->revote->session)); ?>> <?php _e('Sessions', TP_TD) ?>
            </label>
            <label>
                <input type="checkbox" name="tp_options[limitations][revote][cookies]" value="1" <?php checked(true, isset($options->limitations->revote->cookies)); ?> data-toggler="cookies-limitation"> <?php _e('Cookies', TP_TD) ?>
            </label>
            <label>
                <input type="checkbox" name="tp_options[limitations][revote][ip]" value="1" <?php checked(true, isset($options->limitations->revote->ip)); ?> data-toggler="ip-limitation"> <?php _e('IP', TP_TD) ?>
            </label>
            <label>
                <input type="checkbox" name="tp_options[limitations][revote][ip_range]" value="1" <?php checked(true, isset($options->limitations->revote->ip_range)); ?> data-toggler="ip-range-limitation"> <?php _e('IP Range (IPv4 only)', TP_TD) ?>
            </label>
        </p>

        <p data-toggle="cookies-limitation" class="<?php echo isset($options->limitations->revote->cookies) ? '' : 'hide'; ?>">
            <label>
                <span><?php _e('Cookies timeout (minutes)', TP_TD); ?></span>
                <input type="text" name="tp_options[limitations][cookies][timeout]" value="<?php echo isset($options->limitations->cookies->timeout) ? $options->limitations->cookies->timeout : 1440; ?>">
            </label>
        </p>

        <p data-toggle="ip-limitation" class="<?php echo isset($options->limitations->revote->ip) ? '' : 'hide'; ?>">
            <label>
                <span><?php _e('IP timeout (minutes)', TP_TD); ?></span>
                <input type="text" name="tp_options[limitations][ip][timeout]" value="<?php echo isset($options->limitations->ip->timeout) ? $options->limitations->ip->timeout : 15; ?>">
            </label>
        </p>

        <p data-toggle="ip-range-limitation" class="<?php echo isset($options->limitations->revote->ip_range) ? '' : 'hide'; ?>">
            <label>
                <span><?php _e('IP range width (1 to 10)', TP_TD); ?></span>
                <input type="text" name="tp_options[limitations][ip_range][width]" value="<?php echo isset($options->limitations->ip_range->width) ? $options->limitations->ip_range->width : 5; ?>">
            </label>
            <label>
                <span><?php _e('IP range limits (votes per range)', TP_TD); ?></span>
                <input type="text" name="tp_options[limitations][ip_range][limits]" value="<?php echo isset($options->limitations->ip_range->limits) ? $options->limitations->ip_range->limits : 10; ?>">
            </label>
        </p>


        <hr>

        <p>
            <label>
                <input type="checkbox" name="tp_options[limitations][vote_for_results]" value="1" <?php checked(true, isset($options->limitations->vote_for_results)); ?>> <?php _e('User must vote to see results', TP_TD) ?>
            </label>
        </p>

        <hr>

        <p>
            <label>
                <input type="checkbox" name="tp_options[limitations][multiselection]" value="1" <?php checked(true, isset($options->limitations->multiselection)); ?>> <?php _e('User can give multiple answers', TP_TD) ?>
            </label>
        </p>

        <hr>

        <p>
            <label>
                <input type="checkbox" data-toggler="quota-limitation" name="tp_options[limitations][revote][quota]" value="1" <?php checked(true, isset($options->limitations->revote->quota)); ?>> <?php _e('Close poll when votes quota exceed', TP_TD) ?>
            </label>
        </p>

        <p data-toggle="quota-limitation" class="<?php echo isset($options->limitations->revote->quota) ? '' : 'hide'; ?>">
            <label>
                <span><?php _e('Votes quota', TP_TD); ?></span>
                <input type="text" name="tp_options[limitations][quota]" value="<?php echo isset($options->limitations->quota) ? $options->limitations->quota : 0; ?>">
            </label>
        </p>

        <hr>

        <p>
            <label>
                <input type="checkbox" data-toggler="date-limitation" name="tp_options[limitations][revote][date]" value="1"  <?php checked(true, isset($options->limitations->revote->date)); ?>> <?php _e('Date limited', TP_TD) ?>
            </label>
        </p>

        <p data-toggle="date-limitation" class="<?php echo isset($options->limitations->revote->date) ? '' : 'hide'; ?>">
            <label>
                <span><?php _e('Start date', TP_TD); ?></span>
                <input type="text" name="tp_options[limitations][date][start]" value="<?php echo isset($options->limitations->date->start) ? $options->limitations->date->start : ''; ?>" class="datepicker" maxlength="10">
            </label>
            <label>
                <span><?php _e('End date', TP_TD); ?></span>
                <input type="text" name="tp_options[limitations][date][end]" value="<?php echo isset($options->limitations->date->end) ? $options->limitations->date->end : ''; ?>" class="datepicker" maxlength="10">
            </label>
        </p>

        <?php do_tp_action('tp_admin_editor_after_limitations_content', $options); ?>
    </div>
</div>
<!-- /.limitations -->