<?php
global $poll;

// Update settings
if ( isset($_POST['tp_options']['template']['preset']['settings']) &&
    is_array($_POST['tp_options']['template']['preset']['settings']) ):
    $poll->template->preset->settings = json_decode(
	    json_encode($_POST['tp_options']['template']['preset']['settings']),
	    false
    );
endif;

// Preview settings
$background = isset($_POST['tp_preview_background']) ? $_POST['tp_preview_background'] : '#dddddd';
$container_background = isset($_POST['tp_preview_container_background']) ? $_POST['tp_preview_container_background'] : '#ffffff';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
    <head>
        <link rel="profile" href="http://gmpg.org/xfn/11" />

        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
        <?php wp_head(); ?>
        <style type="text/css">
            body{position: absolute;top: 0;right: 0;bottom: 0;left: 0;background: <?php echo $background; ?> !important;display: table;width: 80%;margin: 0 10%;height: 100%;}
	    body:before {display: none;}
            .tp-preview-container {display: table-cell;vertical-align: middle;}
            .tp-preview {background: <?php echo $container_background; ?>;box-shadow: 0 1px 2px rgba(0,0,0,0.2);padding: 25px;}
            .hide {display: none!important;}
	    
	    #preview-variables {display: none;}
        </style>
    </head>
    <body <?php body_class(); ?>>

        <!-- Form used to send new variables -->
        <form id="preview-variables" method="post">
            <input type="hidden" name="tp_action" value="<?php echo esc_attr( isset($_POST['tp_action']) ? $_POST['tp_action'] : '' );?>">
            <?php if (isset($poll->id)): ?>
            <input type="hidden" name="tp_poll_id" value="<?php echo esc_attr($poll->id); ?>">
            <?php endif;?>
        </form>
        <?php if ( (isset($_POST['tp_action']) && !isset($_POST['tp_options'])) || (isset($poll->template->preset->settings) && count((array) $poll->template->preset->settings) == 0) ): ?>
            <div class="tp-preview-container">
                <div class="tp-preview">
                    <svg width="16" height="16" viewBox="0 0 300 300"
                         xmlns="http://www.w3.org/2000/svg" version="1.1" style="margin-right: 10px;">
                      <path d="M 150,0
                               a 150,150 0 0,1 106.066,256.066
                               l -35.355,-35.355
                               a -100,-100 0 0,0 -70.711,-170.711 z"
                            fill="#3d7fe6">
                        <animateTransform attributeName="transform" attributeType="XML"
                               type="rotate" from="0 150 150" to="360 150 150"
                               begin="0s" dur="1s" fill="freeze" repeatCount="indefinite" />
                      </path>
                    </svg>
                    <strong><?php _e('Please wait.', TP_TD); ?></strong>
                </div>
            </div>
            <script type="text/javascript">
                parent.window.refreshPreview();
            </script>
        <?php else: ?>

            <div class="tp-preview-container">
                <div class="tp-preview">
                    <?php if ( isset($poll->choices) ): ?>
                        <?php TotalPoll('poll')->render(); ?>
                    <?php else: ?>
                        <strong><?php _e('To preview this poll, please save it.', TP_TD); ?></strong>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>
	
	<!-- Footer -->
	<div class="hide">
        <?php wp_footer(); ?>
        </div>
	
	<!-- Ajax -->
        <script type="text/javascript">
            /**
             * Save current tp_action
             */
            parent.jQuery(document).delegate('.tp-poll-container button[name="tp_action"]', 'click',function(e){
                jQuery('#preview-variables input[name="tp_action"]').val( jQuery(this).val() );
            });
        </script>
    </body>
</html>
