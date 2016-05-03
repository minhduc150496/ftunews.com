<?php if ( !defined('ABSPATH') ) exit; // Shhh    ?>
<div class="wrap">
    <?php screen_icon('plugins'); ?> <h2><?php _e('Install Templates', TP_TD); ?></h2>
    <?php if ( isset($_POST['upload-template-nonce']) || isset($_POST['delete-template-nonce']) ): ?>
        <div class="updated">
            <p><?php _e('Changes has been saved.', TP_TD); ?></p>
        </div>
    <?php endif; ?>
    
    <h4><?php _e('Install a template in .zip format', TP_TD); ?></h4>
    <p class="install-help"><?php _e('If you have a template in a .zip format, you may install it by uploading it here.', TP_TD); ?></p>

    <form method="post" enctype="multipart/form-data" class="wp-upload-form">
	<?php wp_nonce_field('upload-template', 'upload-template-nonce'); ?>
        <label class="screen-reader-text" for="templatezip"><?php _e('Template zip file', TP_TD); ?></label>
        <input type="file" id="templatezip" name="templatezip">
        <input type="submit" name="install-template-submit" id="install-template-submit" class="button" value="<?php _e('Install Now', TP_TD); ?>" disabled="">
    </form>

    <p>&nbsp;</p>

    <h2><?php _e('Available Templates', TP_TD); ?></h2>
    <p><?php _e('Available installed templates', TP_TD); ?></p>

    <form method="post">
	<?php wp_nonce_field('delete-template', 'delete-template-nonce'); ?>
        <table class="wp-list-table widefat">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox"></th>
                    <th><?php _e('Name', TP_TD); ?></th>
                    <th><?php _e('Description', TP_TD); ?></th>
                    <th><?php _e('Author', TP_TD); ?></th>
                    <th><?php _e('Version', TP_TD); ?></th>
                </tr>
            </thead>
            <tbody>
		<?php foreach ( TotalPoll('template')->fetch() as $slug => $template ): ?>
    		<tr>
    		    <th scope="row" class="check-column">
    			<input type="checkbox" name="template[]" value="<?php echo $slug != 'default' ? esc_attr($slug) : ''; ?>" <?php disabled('default', $slug); ?>>
    		    </th>
    		    <td>
			    <?php if ( !empty($template->website) ): ?>
				<a href="<?php echo esc_attr($template->website); ?>" target="_blank"><?php echo $template->name; ?></a>
				<?php
			    else:
				_e('Unknown', TP_TD);
			    endif;
			    ?>
    		    </td>
    		    <td width="65%">
			    <?php echo $template->description; ?>
    		    </td>
    		    <td width="10%">
			    <?php if ( !empty($template->authorURI) ): ?>
				<a href="<?php echo esc_attr($template->authorURI); ?>#">
				    <?php echo $template->author; ?>
				</a>
				<?php
			    else:
				echo $template->author;
			    endif;
			    ?>
    		    </td>
    		    <td width="10%">
			    <?php echo $template->version; ?>
    		    </td>
    		</tr>
		<?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button class="button button-primary button-large" onclick="return confirm('<?php _e('Are you sure?', TP_TD); ?>')"><?php _e('Delete', TP_TD); ?></button>
    </form>

</div>