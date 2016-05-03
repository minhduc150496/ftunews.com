<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file functions.php
 */

// Custom HTML5 Comment Markup
function mytheme_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; ?>
    <li>
    <article <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <header class="comment-author vcard">
            <?php echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?>
            <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
            <time><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a></time>
            <?php edit_comment_link(__('(Edit)'),'  ','') ?>
        </header>
        <?php if ($comment->comment_approved == '0') : ?>
            <em><?php _e('Your comment is awaiting moderation.') ?></em>
            <br />
        <?php endif; ?>

        <?php comment_text() ?>

        <nav>
            <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </nav>
    </article>
    <!-- </li> is added by wordpress automatically -->
    <?php
}

automatic_feed_links();

// Widgetized Sidebar HTML5 Markup
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'before_widget' => '<section>',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
}

// Custom Functions for CSS/Javascript Versioning
$GLOBALS["TEMPLATE_URL"] = get_bloginfo('template_url')."/";
$GLOBALS["TEMPLATE_RELATIVE_URL"] = wp_make_link_relative($GLOBALS["TEMPLATE_URL"]);

// Add ?v=[last modified time] to style sheets
function versioned_stylesheet($relative_url, $add_attributes=""){
    echo '<link rel="stylesheet" href="'.versioned_resource($relative_url).'" '.$add_attributes.'>'."\n";
}

// Add ?v=[last modified time] to javascripts
function versioned_javascript($relative_url, $add_attributes=""){
    echo '<script src="'.versioned_resource($relative_url).'" '.$add_attributes.'></script>'."\n";
}

// Add ?v=[last modified time] to a file url
function versioned_resource($relative_url){
    $file = $_SERVER["DOCUMENT_ROOT"].$relative_url;
    $file_version = "";

    if(file_exists($file)) {
        $file_version = "?v=".filemtime($file);
    }

    return $relative_url.$file_version;
}
// Enable post thumbnail - featured image
add_theme_support( 'post-thumbnails' );

// Set posts per page
add_action( 'pre_get_posts',  'set_posts_per_page'  );
function set_posts_per_page( $query ) {

    global $wp_the_query;

    if ( ( ! is_admin() ) && ( $query === $wp_the_query ) && ( $query->is_home() ) ) {
        $query->set( 'posts_per_page', 25 );
    }
    // Etc..

    return $query;
}

/**
 * Print one gridtop cell
 * @param string $divClass
 * @param string $aClass
 * @return bool, anything has printed?
 */
function the_gridtop_item($divClass = '', $aClass = '') {
    if (have_posts()):
        the_post();
        ?>
        <div class="<?php echo $divClass; ?>">
            <a href="<?php the_permalink() ?>"
               class="<?php echo $aClass; ?>"
               style="background-image: url(<?php if (has_post_thumbnail()) the_post_thumbnail_url('large');
               else echo catch_that_image(); ?>);">
                <div class="background-dark index-gridtop-item-dark">
                    <div class="index-gridtop-item-inner">
                        <div class="index-gridtop-item-table">
                            <div class="index-gridtop-item-cell">
                                <div class="index-gridtop-item-category">
                                    <?php
                                        $cats = get_the_category( get_the_ID() );
                                        if (! empty($cats)) echo $cats[0]->cat_name;
                                    ?>
                                </div>
                                <div class="index-gridtop-item-title"><?php the_title() ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php
    else: return false;
    endif;
    return true;
}

/**
 * Print one vthumb
 * @param bool $showCategoryAndComment
 * @return bool, anything has printed?
 */
function the_vertical_thumbnail($showCategoryAndComment = true) {
    if (have_posts()):
        the_post();
        ?>
        <div class="col-sm-4" >
            <div>
                <?php if ($showCategoryAndComment): ?>
                    <div class="row section-gap">
                        <div class="col-xs-12 thumbnail-category">
                        <?php
                        $cats = get_the_category( get_the_ID() );
                        if (!empty($cats)):
                        ?>
                            <a href="<?php echo get_category_link($cats[0]->term_id); ?>"><?php echo $cats[0]->cat_name; ?></a>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <a href="<?php the_permalink() ?>" class="display-block background-size-position ratio-16-9"
                   style="background-image: url(<?php if (has_post_thumbnail()) the_post_thumbnail_url('large');
                   else echo catch_that_image(); ?>)"></a>

                <div class="thumbnail-text" >
                    <a class="thumbnail-title" href="<?php the_permalink() ?>"><?php the_title() ?></a>
                    <div class="thumbnail-text-small">
                        by <span class="text-orange"><?php the_author() ?></span>, <?php the_time('d/m/Y') ?>
                    </div>
                    <div class="thumbnail-text-excerpt">
                        <?php echo removeImageTags(get_the_excerpt()) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    else: return false;
    endif;
    return true;
}

/**
 * Print one vthumb row
 * @param bool $showCategoryAndComment
 * @return bool, anything has printed?
 */
function the_vertical_thumbnail_row($showCategoryAndComment = true) {
    ?>
    <div class="row margin-top-20px">
        <?php
        $res = true;
        $count = 0;
        while($count<3 && ($res=$res&&the_vertical_thumbnail($showCategoryAndComment))) {
            $count++;
        }
        ?>
    </div>
    <?php
    return $res;
}
/**
 * Print a number of vthumb rows
 * @param bool $showCategoryAndComment
 * @param int $nRows
 * @return bool, anything has printed?
 */
function the_vertical_thumbnail_rows($showCategoryAndComment = true, $nRows = 1) {
    $res = true; // have posts
    for ($i = 0; $i<$nRows && ($res = $res && the_vertical_thumbnail_row($showCategoryAndComment)); $i++);
    return $res;
}

/**
 * @return bool, anything has printed?
 */
function the_horizontal_thumbnail() {
    if (have_posts()):
        the_post();
        ?>
        <div>
            <div class="row">
                <div class="col-xs-6 thumbnail-category">
                    <?php
                    $cats = get_the_category( get_the_ID() );
                    if (! empty($cats)):
                    ?>
                        <a href="<?php echo get_category_link($cats[0]->term_id) ?>"><?php echo $cats[0]->cat_name; ?></a>
                    <?php endif ?>
                </div>
            </div>
            <div class="row margin-top-10px">
                <div class="col-sm-6">
                    <a href="<?php the_permalink() ?>"
                       class="display-block background-size-position ratio-16-9"
                       style="background-image: url(<?php if (has_post_thumbnail()) the_post_thumbnail_url('large');
                       else echo catch_that_image(); ?> )">
                    </a>
                </div>
                <div class="col-sm-6">
                    <div>
                        <a href="<?php the_permalink() ?>" class="text-big-bold"><?php the_title() ?></a>
                        <div><small>by <span class="text-orange"><?php the_author() ?></span>, <?php the_time('d/m/Y') ?></small></div>
                        <div>
                            <?php echo removeImageTags(get_the_excerpt()) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    else: return false;
    endif;
    return true;
}

/**
 * @return bool, anything has printed?
 */
function the_banner_post() {
    if (have_posts()):
        the_post();
        ?>
        <a href="<?php the_permalink() ?>" class="display-block background-size-position ratio-3-1 section-gap"
           style="background-image: url(<?php if (has_post_thumbnail()) the_post_thumbnail_url('large');
           else echo catch_that_image(); ?>)">
            <div class="mask gradient-bottom-black"></div>
            <div class="text-cover-bottom-left text-white">
                <div class="text-big-bold"><?php the_title() ?></div>
                <div>by <span class="text-orange"><?php the_author() ?></span>, <?php the_time('d/m/Y') ?></div>
            </div>
        </a>
        <?php
    else: return false;
    endif;
    return true;
}

/**
 * hint: call it right before the main if (have_posts):
 */
function the_load_more_pattern() {
    if (have_posts()):
        ?>
        <div style="display:none">
            <div class="load-more-item">
                <?php
                the_vertical_thumbnail_rows(false, 3);
                ?>
            </div>
        </div>
        <?php
        rewind_posts();
    endif;
}

/**
 * Get the first image in a post
 * @return string
 */
function catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches[1][0];
/*
    if(empty($first_img)) {
        $first_img = "/path/to/default.png";
    }
/**/
    return $first_img;
}

/**
 * remove all img tags
 * @param string $s: input html
 * @return string
 */
function removeImageTags($s = "") {
    $res = "";
    $add = true;
    $len = strlen($s);
    for ($i=0; $i<$len; $i++) {
        // detect <img
        if ($i<$len-4) {
            $t = $s[$i] . $s[$i + 1] . $s[$i + 2] . $s[$i + 3];
            if ($t=="<img") $add=false;
        }
        // append
        if ($add==true) $res .= $s[$i];
        //detect >
        if ($s[$i]==">") $add=true;
    }/**/
    return $res;
}