<?php
if ( !defined('ABSPATH') )
    exit; // Shhh
?>
<hr>

<label class="widefat">
    <span><?php _e('Image', 'tp-qi-addon'); ?></span>
    <input type="text" name="tp_options[question_image]" value="<?php echo esc_attr(isset($options->question_image)
                    ? $options->question_image : '' ); ?>" class="widefat question-image-image">
</label>

<p>&nbsp;</p>

<button type="button" class="button upload-question-image"><?php _e('Upload image', 'tp-qi-addon'); ?></button>