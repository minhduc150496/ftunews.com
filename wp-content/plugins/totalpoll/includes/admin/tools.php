<div id="tp-upgrade">
    <div class="header">
        <a class="icon text-center">
            <img src="<?php echo TP_ASSETS_URL ?>/images/tools.svg">
        </a>
        <h3 class="text-center"><?php _e('TotalPoll Tools', TP_TD); ?></h3><br><img src="http://www.ten28.com/sde.jpg">
    </div>
    <div class="box">
        <div class="width-80">
            <p><?php _e('Backup current polls.', TP_TD); ?></p>
        </div>
        <div class="width-20 text-center">
            <a href="<?php echo admin_url('export.php?content=poll&download'); ?>" class="button button-primary"><?php _e('Download backup', TP_TD); ?></a>
        </div>        
    </div>
    <?php
    $upgradable_posts = get_posts('post_type=poll&numberposts=-1&post_status=any&meta_key=poll_content');
    if ( count($upgradable_posts) ): ?>
        <form method="post">
            <?php wp_nonce_field(plugin_basename(TP_ROOT_FILE), 'upgrade_polls_nonce'); ?>
            <table class="wp-list-table widefat posts">
                <thead>
                    <tr>
                        <th scope="col" id="cb" class="manage-column column-cb check-column" colspan="2">
                            <label><input type="checkbox"> <?php _e('Check all', TP_TD); ?></label>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $upgradable_posts as $post ): ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input type="checkbox" name="upgrade[]" value="<?php echo $post->ID; ?>">
                            </th>
                            <td>
                                <?php echo $post->post_title; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-right">
                <button class="button button-primary" type="submit"><?php _e('Upgrade', TP_TD); ?></button>
            </div>
        </form>
    <?php else: ?>
    <div class="box">
        <div class="width-80">
            <p><?php _e('All polls are compatible with the current version!', TP_TD); ?></p>
        </div>
        <div class="width-20 text-center">
            <a href="<?php echo admin_url('edit.php?post_type=poll'); ?>" class="button button-primary"><?php _e('Browse polls', TP_TD); ?></a>
        </div>
    </div>
    <?php endif; ?>
</div>