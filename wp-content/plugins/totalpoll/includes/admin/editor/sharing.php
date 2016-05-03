<?php if ( !defined('ABSPATH') ) exit; // Shhh ?>
<!-- .sharing -->
<div class="sharing section <?php echo !isset($last_opened_tabs['sharing']) ? 'collapsed' : '' ?>">
        <input type="checkbox" name="tp_opened_tabs[sharing]" class="tab-state" <?php checked(isset($last_opened_tabs['sharing']), true); ?>>
	<h3 class="title"><?php _e('Sharing', TP_TD); ?></h3>
	<div class="content">
                <?php do_tp_action('tp_admin_editor_before_sharing_content', $options); ?>
		<p>
                    <label>
                        <input type="checkbox" name="tp_options[sharing][show]" value="1" <?php checked(true, isset($options->sharing->show));?> data-toggler="share-fields"> <?php _e( 'Show share icons', TP_TD ) ?>
                    </label>
		</p>
                
                <div class="<?php echo isset($options->sharing->show) ? '' : 'hide'; ?>" data-toggle="share-fields">
                    <br>
                    <table class="wp-list-table widefat fixed">
                        <thead>
                            <tr>
                                <th width="20%"><?php _e('Variable', TP_TD); ?></th>
                                <th><?php _e('Value', TP_TD); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{question}}</td>
                                <td><?php _e('Question', TP_TD); ?></td>
                            </tr>
                            <tr>
                                <td>{{link}}</td>
                                <td><?php _e('Current link', TP_TD); ?></td>
                            </tr>
                            <?php do_tp_action( 'tp_admin_editor_sharing_variables' ); ?>
                        </tbody>
                    </table>
                    <br>
                    <p>
                        <label class="widefat">
                            <span><?php _e('Twitter expression', TP_TD); ?></span>
                            <input type="text" name="tp_options[sharing][expressions][twitter]" class="widefat" value="<?php echo isset($options->sharing->expressions->twitter) ? esc_attr($options->sharing->expressions->twitter) : ''; ?>">
                        </label>
                    </p>

                    <p>
                        <label class="widefat">
                            <span><?php _e('Facebook expression', TP_TD); ?></span>
                            <input type="text" name="tp_options[sharing][expressions][facebook]" class="widefat" value="<?php echo isset($options->sharing->expressions->facebook) ? esc_attr($options->sharing->expressions->facebook) : ''; ?>">
                        </label>
                    </p>

                    <p>
                        <label class="widefat">
                            <span><?php _e('Google+ expression', TP_TD); ?></span>
                            <input type="text" name="tp_options[sharing][expressions][googleplus]" class="widefat" value="<?php echo isset($options->sharing->expressions->googleplus) ? esc_attr($options->sharing->expressions->googleplus) : ''; ?>">
                        </label>
                    </p>
                    
                    <?php do_tp_action('tp_admin_editor_after_sharing_content', $options); ?>
                </div>
	</div>
</div>
<!-- /.sharing -->