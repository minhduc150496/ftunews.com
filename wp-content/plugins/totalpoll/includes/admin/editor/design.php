<?php if ( !defined('ABSPATH') ) exit; // Shhh   ?>
<!-- .design -->
<div class="design section <?php echo!isset($last_opened_tabs['design']) ? 'collapsed' : '' ?>">
    <input type="checkbox" name="tp_opened_tabs[design]" class="tab-state" <?php checked(isset($last_opened_tabs['design']), true); ?>>
    <h3 class="title"><?php _e('Design', TP_TD); ?></h3>
    <div class="content">

	<?php do_tp_action('tp_admin_editor_before_design_content', $options); ?>

	<?php
        TotalPoll('template')->fetch();
	$templates = TotalPoll('template')->available;
	$presets = TotalPoll('template')->presets;
	$current_preset = isset($options->template->name) ? $options->template->name : 'default';
	$current_preset .= '-preset-';
	$current_preset .= isset($options->template->preset->name) ? $options->template->preset->name : 'default';
	?>

        <div class="customizer">
            <div class="settings-sections">
                <div class="settings-section toggled" data-toggle="section-current-settings">
                    <p>
                        <label class="widefat">
                            <span><?php _e('Presets', TP_TD); ?></span>
                            <select name="tp_options[template][preset][name]" class="widefat">
				<?php foreach ( $templates as $slug => $template ): ?>
                                    <optgroup label="<?php echo $template->name; ?>">
					<?php if ( isset($presets->{$slug}) ): ?>
					    <?php foreach ( $presets->{$slug} as $name => $preset ): $preset_id = $slug . '-preset-' . $name; ?>
	    				    <option value="<?php echo $preset_id; ?>" <?php selected($preset_id, $current_preset); ?>><?php echo $name; ?></option>
					    <?php endforeach; ?>
					<?php endif; ?>
                                    </optgroup>
				<?php endforeach; ?>
                            </select>
                        </label>
                        <input name="tp_options[template][preset][load]" value="" type="hidden">
                    </p>
                    <hr>
                    <p>
                        <button type="submit" class="button button-primary" name="tp_options[template][preset][new]"><?php _e('Save as', TP_TD); ?></button>
                        <button type="submit" class="button" name="tp_options[template][preset][delete]">
			    <?php
			    if ( isset($options->template->preset->name) &&
				    isset($poll->template->presets[$options->template->preset->name]) ):
				_e('Reset', TP_TD);
			    else:
				_e('Delete', TP_TD);
			    endif;
			    ?>
                        </button>
                    </p>
		    <hr>

		    <div class="settings-field-container need-refresh">
			<label><?php _e('Preview Background', TP_TD); ?></label>
			<input type="text" name="tp_preview_background" value="#dddddd" class="tp-color-field">
		    </div>

		    <div class="settings-field-container need-refresh">
			<label><?php _e('Preview Container Background', TP_TD); ?></label>
			<input type="text" name="tp_preview_container_background" value="#ffffff" class="tp-color-field">
		    </div>

                </div>
		<?php if ( isset($poll->template->settings['sections']) ):
			foreach ( $poll->template->settings['sections'] as $section_id => $section ): ?>
			<a href="#" class="" data-toggler="section-<?php echo $section_id; ?>-fields"><?php echo $section['label']; ?></a>
			<div class="hide settings-section need-refresh" data-toggle="section-<?php echo $section_id; ?>-fields">
			    <?php
			    unset($section['label']);
			    foreach ( $section['fields'] as $field_id => $field ):
			    ?>
	    		    <div class="settings-field-container">
				    <?php
				    if ( isset($options->template->preset->settings->{$section_id}->{$field_id}) ):
					$field['value'] = $options->template->preset->settings->{$section_id}->{$field_id};
					if ( isset($field['states']) && is_array($field['states']) ):
					    foreach ( $field['states'] as $id => $state ):
						if ( isset($options->template->preset->settings->{$section_id}->{$field_id . ':' . $id}) ):
						    $field['states'][$id]['value'] = $options->template->preset->settings->{$section_id}->{$field_id . ':' . $id};
						endif;
					    endforeach;
					endif;
				    endif;
				    ?>
				    <?php echo TotalPoll('customizer')->field($section_id, $field_id, (object) $field); ?>
	    		    </div>
			    <?php endforeach; ?>
			</div>
		<?php
			endforeach;
		    endif;
		?>
            </div>
            <div class="preview-pane">
                <iframe height="100%" width="100%" src="<?php echo home_url('?tp_action=preview&tp_poll_id=' . get_the_ID()); ?>"></iframe>
            </div>
        </div>

	<?php do_tp_action('tp_admin_editor_after_design_content', $options); ?>
    </div>
</div>
<!-- /.design -->