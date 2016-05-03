<?php if ( !defined('ABSPATH') ) exit; // Shhh   ?>
<?php if ( is_poll_shareable() ): ?>
    <div class="addthis_toolbox addthis_default_style addthis_20x20_style">
        <a class="addthis_button_facebook" addthis:title="<?php echo esc_attr(get_poll_sharing_expression('facebook')); ?>"></a>
        <a class="addthis_button_twitter" addthis:title="<?php echo esc_attr(get_poll_sharing_expression('twitter')); ?>"></a>
        <a class="addthis_button_google_plusone_share" addthis:title="<?php echo esc_attr(get_poll_sharing_expression('googleplus')); ?>"></a>
    </div>
    <script>!function(d, s, id) {
    	var js, fjs = d.getElementsByTagName(s)[0];
    	if (!d.getElementById(id)) {
    	    js = d.createElement(s);
    	    js.id = id;
    	    js.src = "//s7.addthis.com/js/300/addthis_widget.js";
    	    fjs.parentNode.insertBefore(js, fjs);
    	}
    	}(document, "script", "addthis");</script>
<?php endif; ?>