<?php if ( !defined('ABSPATH') ) exit; // Shhh ?>
<!-- .question -->
<div class="question section <?php echo !isset($last_opened_tabs['question']) ? 'collapsed' : '' ?>">
        <input type="checkbox" name="tp_opened_tabs[question]" class="tab-state" <?php checked(isset($last_opened_tabs['question']), true); ?>>
	<h3 class="title"><?php _e('Question', TP_TD); ?></h3>
	<div class="content">
            <?php do_tp_action('tp_admin_editor_before_question_content', $options); ?>
            <input type="text" name="tp_options[question]" placeholder="<?php _e('Question', TP_TD); ?>" class="tp-question-field widefat" value="<?php echo isset($options->question) ? esc_attr($options->question) : ''; ?>" />
            <?php do_tp_action('tp_admin_editor_after_question_content', $options); ?>
	</div>
</div>
<!-- /.question -->