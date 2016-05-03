<?php if ( !defined('ABSPATH') ) exit; // Silence please! ?>
<div class="wrap">

    <?php screen_icon('plugins'); ?> <h2><?php _e('Install Addons', TP_TD); ?></h2>
    <?php if ( isset($_POST['upload-addon-nonce']) || isset($_POST['manage-addons-nonce']) ): ?>
        <div class="updated">
            <p><?php _e('Changes has been saved.', TP_TD); ?></p>
        </div>
    <?php endif; ?>
    <h4><?php _e('Install an addon in .zip format', TP_TD); ?></h4>
    <p class="install-help"><?php _e('If you have an addon in a .zip format, you may install it by uploading it here.', TP_TD); ?></p>

    <form method="post" enctype="multipart/form-data" class="wp-upload-form">
	<?php wp_nonce_field('upload-addon', 'upload-addon-nonce'); ?>
        <label class="screen-reader-text" for="addonzip"><?php _e('Addon zip file', TP_TD); ?></label>
        <input type="file" id="addonzip" name="addonzip">
        <input type="submit" name="install-addon-submit" id="install-addon-submit" class="button" value="<?php _e('Install Now', TP_TD); ?>" disabled="">
    </form>

    <p>&nbsp;</p>

    <h2><?php _e('Available Addons', TP_TD); ?></h2>
    <p><?php _e('Available installed addons', TP_TD); ?></p>

    <form method="post">
	<?php wp_nonce_field('manage-addons', 'manage-addons-nonce'); ?>
        <table class="wp-list-table widefat">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox"></th>
                    <th><?php _e('Status', TP_TD); ?></th>
                    <th><?php _e('Name', TP_TD); ?></th>
                    <th><?php _e('Description', TP_TD); ?></th>
                    <th><?php _e('Author', TP_TD); ?></th>
                    <th><?php _e('Version', TP_TD); ?></th>
                    <th><?php _e('Compatiblity', TP_TD); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $addons = TotalPoll('addons')->fetch();
                foreach ( $addons as $slug => $addon ):
            ?>
    		<tr>
    		    <th scope="row" class="check-column">
    			<input type="checkbox" name="addons[]" value="<?php echo $slug != 'default' ? $slug : ''; ?>" <?php disabled('default', $slug); ?>>
    		    </th>
    		    <td width="5%" style="color: <?php echo $addon->activated ? 'green' : 'red'; ?>;font-weight: bold;">
			    <?php $addon->activated ? _e('Active', TP_TD) : _e('Inactive', TP_TD); ?>
    		    </td>
    		    <td width="15%">
    			<a href="<?php echo $addon->website; ?>" target="_blank"><?php echo $addon->name; ?></a>
    		    </td>
    		    <td width=60%">
			    <?php echo $addon->description; ?>
    		    </td>
    		    <td width="10%">
			    <?php if ( !empty($addon->authorURI) ): ?>
				<a href="<?php echo esc_attr($addon->authorURI); ?>#">
				    <?php echo $addon->author; ?>
				</a>
				<?php
			    else:
				echo $addon->author;
			    endif;
			    ?>
    		    </td>
    		    <td width="5%" style="text-align: center;">
			    <?php echo $addon->version; ?>
    		    </td>
    		    <td width="5%" style="color: <?php echo $addon->compatible ? 'green' : 'red'; ?>;font-weight: bold;">
			    <?php $addon->compatible ? _e('Compatible', TP_TD) : _e('Incompatible', TP_TD); ?>
    		    </td>
    		</tr>
		<?php endforeach; ?>
		<?php if ( !$addons ): ?>
    		<tr>
    		    <td colspan="6" style="padding: 32px;text-align: center;font-size: 16px;font-weight: bold;"><?php _e('Nothing. Seriously.', TP_TD); ?></td>
    		</tr>
		<?php endif; ?>
            </tbody>
        </table>
        <br>
        <button class="button button-primary button-large" name="activate"><?php _e('Activate', TP_TD); ?></button>
        &nbsp;&nbsp;
        <button class="button button-large" name="deactivate"><?php _e('Deactivate', TP_TD); ?></button>
        &nbsp;&nbsp;
        <button class="button button-large" name="delete" onclick="return confirm('<?php _e('Are you sure?', TP_TD); ?>')"><?php _e('Delete', TP_TD); ?></button>
    </form>

</div>