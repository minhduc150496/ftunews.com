<?php

$tp_templates_feed = fetch_feed( 'http://wpsto.re/addons/downloads/category/total-poll/feed/' );
if ( ! is_wp_error( $tp_templates_feed ) ) :
    $tp_templates = $tp_templates_feed->get_items( 0, 999 );
endif;
?>
<div id="tp-store" class="wrap">
    <?php screen_icon('themes'); ?> <h2><?php _e('Templates', TP_TD); ?></h2>
    <p><?php _e('Available templates', TP_TD); ?></p>
    <ul class="items" class="clearfix">
        <?php foreach ( (array) $tp_templates as $template ) : ?>
            <li>
                <?php 
                $title = $template->get_title();
                $description = $template->get_description();
                $permalink = esc_url( $template->get_permalink() );
                $thumbnail = $template->data['child']['']['thumbnail'][0]['child']['']['url'][0]['data'];
                $price = $template->data['child']['']['price'][0]['data'];
                ?>
                <div class="template">
                    <a href="<?php echo $permalink; ?>" target="_blank">
                        <img src="<?php echo $thumbnail; ?>" />
                        <div class="expandable">
                            <div class="card">
                                <span class="title"><?php echo $title; ?></span>
                                <span class="price"><?php echo $price; ?></span>
                            </div>
                            <div class="description"><?php echo strip_tags($description); ?></div>
                        </div>
                    </a>
                    
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>