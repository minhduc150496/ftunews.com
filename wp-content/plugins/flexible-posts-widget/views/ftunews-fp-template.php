<?php
/**
 * Flexible Posts Widget: FTUNEWS Editors' Choice Widget Template
 *
 * @since 1.0.0
 *
 * This is the ORIGINAL default template used by the plugin.
 * There is a new default template (default.php) that will be
 * used by default if no template was specified in a widget.
 */

// Block direct requests
if (!defined('ABSPATH'))
    die('-1');

echo $before_widget;

if (!empty($title))
    echo $before_title . $title . $after_title;

if ($flexible_posts->have_posts()):
    ?>
    <ul class="dpe-flexible-posts editors-choice">
        <?php while ($flexible_posts->have_posts()) : $flexible_posts->the_post();
            global $post; ?>

            <li class="item">
                <div class="row">
                    <div class="col-xs-5 image">
                        <div class="ratio-wrapper">
                            <div class="ratio-content img"
                                 style="background-image: url(<?php echo get_thumbnail_photo_url(get_the_ID()) ?>)">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-7 text">
                        <a class="cate"
                           href="<?php echo get_last_category_url(get_the_ID()) ?>"><?php echo get_last_category_name(get_the_ID()) ?></a>
                        <a class="three-dots title" href="<?php the_permalink() ?>"><?php the_title() ?></a>
                    </div>
                </div>
                <hr>
            </li>

        <?php endwhile; ?>
    </ul><!-- .dpe-flexible-posts -->
<?php else: // We have no posts ?>
    <div class="dpe-flexible-posts no-posts">
        <p><?php _e('No post found', 'flexible-posts-widget'); ?></p>
    </div>
    <?php
endif; // End have_posts()

echo $after_widget;
