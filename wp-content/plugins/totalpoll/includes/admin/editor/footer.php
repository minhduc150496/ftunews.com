<?php if ( !defined('ABSPATH') ) exit; // Shhh   ?>
<?php do_tp_action('tp_admin_editor_footer', $options); ?>

<script type="text/template" class="choice-template" id="choice-text">
    <div class="choice choice-text">
    <input type="hidden" name="tp_options[choices][{{index}}][type]" value="text">
    <label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>" name="tp_options[choices][{{index}}][votes]" class="votes-counter widefat">
    <label class="horizontal-label"><?php _e('Text', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Text', TP_TD); ?>" name="tp_options[choices][{{index}}][text]" class="widefat">
    <?php do_tp_action("tp_admin_editor_text_choice_fields_template"); ?>
    <ul class="choice-controllers">
    <li><button type="button" class="move">&equiv;</button></li>
    <li><button type="button" class="delete">&#10006;</button></li>
    <?php do_tp_action("tp_admin_editor_text_choice_buttons_template"); ?>
    </ul>
    </div>
</script>

<script type="text/template" class="choice-template" id="choice-link">
    <div class="choice choice-link">
    <input type="hidden" name="tp_options[choices][{{index}}][type]" value="link">
    <label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>" name="tp_options[choices][{{index}}][votes]" class="votes-counter widefat">
    <label class="horizontal-label"><?php _e('URL', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('URL', TP_TD); ?>" name="tp_options[choices][{{index}}][url]" class="widefat">
    <label class="horizontal-label"><?php _e('Label', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Label', TP_TD); ?>" name="tp_options[choices][{{index}}][label]" class="widefat">
    <?php do_tp_action("tp_admin_editor_link_choice_fields_template"); ?>
    <ul class="choice-controllers">
    <li><button type="button" class="move">&equiv;</button></li>
    <li><button type="button" class="delete">&#10006;</button></li>
    <?php do_tp_action("tp_admin_editor_link_choice_buttons_template"); ?>
    </ul>
    </div>
</script>

<script type="text/template" class="choice-template" id="choice-image">
    <div class="choice choice-image">
    <input type="hidden" name="tp_options[choices][{{index}}][type]" value="image">
    <label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>" name="tp_options[choices][{{index}}][votes]" class="votes-counter widefat">
    <label class="horizontal-label"><?php _e('Image', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Image', TP_TD); ?>" name="tp_options[choices][{{index}}][image]" class="widefat upload-holder">
    <label class="horizontal-label"><?php _e('Full', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Full Size URL', TP_TD); ?>" name="tp_options[choices][{{index}}][full]" class="widefat">
    <label class="horizontal-label"><?php _e('Label', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Label', TP_TD); ?>" name="tp_options[choices][{{index}}][label]" class="widefat">
    <?php do_tp_action("tp_admin_editor_image_choice_fields_template"); ?>
    <ul class="choice-controllers">
    <li><button type="button" class="upload"><?php _e('upload', TP_TD); ?></button></li>
    <li><button type="button" class="move">&equiv;</button></li>
    <li><button type="button" class="delete">&#10006;</button></li>
    <?php do_tp_action("tp_admin_editor_image_choice_buttons_template"); ?>
    </ul>
    </div>
</script>

<script type="text/template" class="choice-template" id="choice-video">
    <div class="choice choice-video">
    <input type="hidden" name="tp_options[choices][{{index}}][type]" value="video">
    <label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>" name="tp_options[choices][{{index}}][votes]" class="votes-counter widefat">
    <label class="horizontal-label"><?php _e('Thumbnail', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Thumbnail', TP_TD); ?>" name="tp_options[choices][{{index}}][image]" class="widefat upload-holder">
    <label class="horizontal-label"><?php _e('Embed', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Video embed code', TP_TD); ?>" name="tp_options[choices][{{index}}][video]" class="widefat">
    <label class="horizontal-label"><?php _e('Label', TP_TD); ?>:</label>
    <input type="text" placeholder="<?php _e('Label', TP_TD); ?>" name="tp_options[choices][{{index}}][label]" class="widefat">
    <?php do_tp_action("tp_admin_editor_video_choice_fields_template"); ?>
    <ul class="choice-controllers">
    <li><button type="button" class="upload"><?php _e('upload', TP_TD); ?></button></li>
    <li><button type="button" class="move">&equiv;</button></li>
    <li><button type="button" class="delete">&#10006;</button></li>
    <?php do_tp_action("tp_admin_editor_video_choice_buttons_template"); ?>
    </ul>
    </div>
</script>

<script type="text/template" class="choice-template" id="choice-html">
    <div class="choice choice-html">
        <input type="hidden" name="tp_options[choices][{{index}}][type]" value="html">
	<label class="horizontal-label votes-label"><?php _e('Votes', TP_TD); ?>:</label>
        <input type="text" placeholder="<?php _e('Votes', TP_TD); ?>" name="tp_options[choices][{{index}}][votes]" class="votes-counter widefat">
	<label class="horizontal-label"><?php _e('Code', TP_TD); ?>:</label>
        <div id="wp-tp-html-tinymce-{{index}}-content-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
            <div id="wp-tp-html-tinymce-{{index}}-content-editor-tools" class="wp-editor-tools hide-if-no-js">
                <div id="wp-tp-html-tinymce-{{index}}-content-media-buttons" class="wp-media-buttons">
                <a href="#" class="button insert-media add_media" data-editor="tp-html-tinymce-{{index}}-content" title="<?php _e('Add Media'); ?>"><span class="wp-media-buttons-icon"></span> <?php _e('Add Media'); ?></a>
                </div>
                <div class="wp-editor-tabs">
                <button type="button" id="tp-html-tinymce-{{index}}-content-tmce" class="wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);"><?php _e('Visual'); ?></button>
                <button type="button" id="tp-html-tinymce-{{index}}-content-html" class="wp-switch-editor switch-html" onclick="switchEditors.switchto(this);"><?php _e('Text'); ?></button>
             </div>
        </div>
        <div id="wp-tp-html-tinymce-{{index}}-content-editor-container" class="wp-editor-container">
            <textarea class="wp-editor-area" rows="3" autocomplete="off" cols="40" name="tp_options[choices][{{index}}][html]" id="tp-html-tinymce-{{index}}-content"><?php _e('HTML Code', TP_TD); ?></textarea></div>
        </div>
    <?php do_tp_action("tp_admin_editor_html_choice_fields_template"); ?>
        <ul class="choice-controllers">
            <li><button type="button" class="move">&equiv;</button></li>
            <li><button type="button" class="delete">&#10006;</button></li>
    <?php do_tp_action("tp_admin_editor_html_choice_buttons_template"); ?>
        </ul>
    </div>
    <script type="text/javascript">jQuery('#tp-html-tinymce-{{index}}-content').initTinyMCE();</script>
</script>

<?php do_tp_action('tp_admin_editor_choice_types_templates', $options); ?>

</div>
<!-- /.tp-wrapper -->