<?php if (!defined('ABSPATH')) exit; // Shhh     ?>
<!-- .choices -->
<div class="choices section <?php echo !isset($last_opened_tabs['choices']) ? 'collapsed' : '' ?>">
    <input type="checkbox" name="tp_opened_tabs[choices]"
           class="tab-state" <?php checked(isset($last_opened_tabs['choices']), true); ?>>
    <h3 class="title"><?php _e('Choices', TP_TD); ?></h3>
    <div class="content clearfix">

        <?php do_tp_action('tp_admin_editor_before_choices_content', $options); ?>
        <label><input type="checkbox" name="tp_options[override_votes]"
                      class="override-votes"><?php _e('Override votes', TP_TD); ?></label>
        <hr>
        <ul class="choice-types">
            <li>
                <button type="button" class="button" data-template="choice-image"><?php _e('Add', TP_TD); ?></button>
            </li>
            <?php do_tp_action('tp_admin_editor_choice_types_buttons'); ?>
        </ul>
        <div class="choices-container show-votes-field">
            <?php if (isset($options->choices)): ?>
                <?php foreach ($options->choices as $index => $choice): ?>
                <?php if ( $choice->type == 'text' ): ?>
                    <div class="choice choice-text">
                        <input type="hidden" name="tp_options[choices][<?php echo $index; ?>][type]" value="text">
                        <label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
                        <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>" name="tp_options[choices][<?php echo $index; ?>][votes]" value="<?php echo esc_attr($choice->votes) ?>" class="votes-counter widefat">
                        <label class="horizontal-label"><?php _e('Text', TP_TD); ?>:</label>
                        <input type="text" placeholder="<?php _e('Text', TP_TD); ?>" name="tp_options[choices][<?php echo $index; ?>][text]" value="<?php echo esc_attr($choice->text) ?>" class="widefat">
                        <?php do_tp_action("tp_admin_editor_text_choice_fields", $choice, $index); ?>
                        <input type="hidden" name="tp_options[choices][<?php echo $index; ?>][last_index]" value="<?php echo $index; ?>">
                        <ul class="choice-controllers">
                            <li><button type="button" class="move">&equiv;</button></li>
                            <li><button type="button" class="delete">&#10006;</button></li>
                            <?php do_tp_action("tp_admin_editor_text_choice_buttons", $choice, $index); ?>
                        </ul>
                    </div>
                <?php elseif ($choice->type == 'image'): ?>
                        <div class="choice choice-image">
                            <input type="hidden" name="tp_options[choices][<?php echo $index; ?>][type]" value="image">
                            <label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
                            <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>"
                                   name="tp_options[choices][<?php echo $index; ?>][votes]"
                                   value="<?php echo esc_attr($choice->votes) ?>" class="votes-counter widefat">
                            <label class="horizontal-label"><?php _e('Image', TP_TD); ?>:</label>
                            <input type="text" placeholder="<?php _e('Image', TP_TD); ?>"
                                   name="tp_options[choices][<?php echo $index; ?>][image]"
                                   value="<?php echo esc_attr($choice->image) ?>" class="widefat upload-holder">
                            <label class="horizontal-label"><?php _e('Full', TP_TD); ?>:</label>
                            <input type="text" placeholder="<?php _e('Full Size URL', TP_TD); ?>"
                                   name="tp_options[choices][<?php echo $index; ?>][full]"
                                   value="<?php echo esc_attr($choice->full) ?>" class="widefat">
                            <label class="horizontal-label"><?php _e('Name', TP_TD); ?>:</label>
                            <input type="text" placeholder="<?php _e('Name', TP_TD); ?>"
                                   name="tp_options[choices][<?php echo $index; ?>][label]"
                                   value="<?php echo esc_attr($choice->label) ?>" class="widefat">
                            <label class="horizontal-label"><?php _e('About', TP_TD); ?>:</label>
                            <?php wp_editor($choice->html, "tp-html-tinymce-$index-content", array('textarea_name' => "tp_options[choices][$index][html]", 'textarea_rows' => 2)); ?>

                            <?php do_tp_action("tp_admin_editor_image_choice_fields", $choice, $index); ?>
                            <input type="hidden" name="tp_options[choices][<?php echo $index; ?>][last_index]"
                                   value="<?php echo $index; ?>">
                            <ul class="choice-controllers">
                                <li>
                                    <button type="button" class="upload"><?php _e('upload', TP_TD); ?></button>
                                </li>
                                <li>
                                    <button type="button" class="move">&equiv;</button>
                                </li>
                                <li>
                                    <button type="button" class="delete">&#10006;</button>
                                </li>
                                <?php do_tp_action("tp_admin_editor_image_choice_buttons", $choice, $index); ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <?php $registered = has_filter("tp_admin_editor_{$choice->type}_choice_fields") || has_filter("tp_admin_editor_{$choice->type}_choice_buttons"); ?>
                        <div
                            class="choice choice-<?php echo $choice->type; ?> <?php echo $registered ? '' : 'hide'; ?>">
                            <input type="hidden" name="tp_options[choices][<?php echo $index; ?>][type]"
                                   value="<?php echo $choice->type; ?>">
                            <label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
                            <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>"
                                   name="tp_options[choices][<?php echo $index; ?>][votes]"
                                   value="<?php echo esc_attr($choice->votes) ?>" class="votes-counter widefat">
                            <?php
                            if ($registered):
                                do_tp_action("tp_admin_editor_{$choice->type}_choice_fields", $choice, $index);
                            else:
                                // Recovery mode
                                foreach ($choice as $key => $value):
                                    if (in_array($key, array('id', 'type', 'votes', 'votes_percentage')))
                                        continue;
                                    // Array
                                    if (is_array($value)):
                                        foreach ($value as $subkey => $subvalue):
                                            ?>
                                            <textarea
                                                name="tp_options[choices][<?php echo $index; ?>][<?php echo $key; ?>][<?php echo $subkey; ?>]"><?php echo esc_attr($subvalue); ?></textarea>
                                            <?php
                                        endforeach;
                                    // Single value
                                    else:
                                        ?>
                                        <textarea
                                            name="tp_options[choices][<?php echo $index; ?>][<?php echo $key; ?>]"><?php echo esc_attr($value); ?></textarea>
                                        <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                            <input type="hidden" name="tp_options[choices][<?php echo $index; ?>][last_index]"
                                   value="<?php echo $index; ?>">
                            <ul class="choice-controllers">
                                <li>
                                    <button type="button" class="move">&equiv;</button>
                                </li>
                                <li>
                                    <button type="button" class="delete">&#10006;</button>
                                </li>
                                <?php do_tp_action("tp_admin_editor_{$choice->type}_choice_buttons", $choice, $index); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php do_tp_action('tp_admin_editor_after_choices_content', $options); ?>
    </div>
</div>