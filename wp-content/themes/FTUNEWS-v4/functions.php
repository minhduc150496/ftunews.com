<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file functions.php
 */

// Custom HTML5 Comment Markup
function mytheme_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment; ?>
    <li>
    <article <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <header class="comment-author vcard">
            <?php echo get_avatar($comment, $size = '48', $default = '<path_to_url>'); ?>
            <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
            <time><a
                    href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a>
            </time>
            <?php edit_comment_link(__('(Edit)'), '  ', '') ?>
        </header>
        <?php if ($comment->comment_approved == '0') : ?>
            <em><?php _e('Your comment is awaiting moderation.') ?></em>
            <br/>
        <?php endif; ?>

        <?php comment_text() ?>

        <nav>
            <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </nav>
    </article>
    <!-- </li> is added by wordpress automatically -->
    <?php
}

automatic_feed_links();

// Widgetized Sidebar HTML5 Markup
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'before_widget' => '<section>',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
}

// Custom Functions for CSS/Javascript Versioning
$GLOBALS["TEMPLATE_URL"] = get_bloginfo('template_url') . "/";
$GLOBALS["TEMPLATE_RELATIVE_URL"] = wp_make_link_relative($GLOBALS["TEMPLATE_URL"]);

// Add ?v=[last modified time] to style sheets
function versioned_stylesheet($relative_url, $add_attributes = "")
{
    echo '<link rel="stylesheet" href="' . versioned_resource($relative_url) . '" ' . $add_attributes . '>' . "\n";
}

// Add ?v=[last modified time] to javascripts
function versioned_javascript($relative_url, $add_attributes = "")
{
    echo '<script src="' . versioned_resource($relative_url) . '" ' . $add_attributes . '></script>' . "\n";
}

// Add ?v=[last modified time] to a file url
function versioned_resource($relative_url)
{
    $file = $_SERVER["DOCUMENT_ROOT"] . $relative_url;
    $file_version = "";

    if (file_exists($file)) {
        $file_version = "?v=" . filemtime($file);
    }

    return $relative_url . $file_version;
}

// Enable post thumbnail - featured image
add_theme_support('post-thumbnails');

// Set posts per page
add_action('pre_get_posts', 'set_posts_per_page');
function set_posts_per_page($query)
{

    global $wp_the_query;

    if ((!is_admin()) && ($query === $wp_the_query) && ($query->is_home())) {
        $query->set('posts_per_page', 25);
    }
    // Etc..

    return $query;
}

// Register header menu
/*
function register_my_menu() {
    register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );
/**/

/**
 * @param $ID
 * @return null
 */
function get_last_category($ID)
{
    $cats = get_the_category($ID);
    if (!empty($cats)) return $cats[count($cats) - 1];
    return null;
}

/**
 * Get last category Name
 * @param $ID
 * @return string
 */
function get_last_category_name($ID)
{
    $catName = '';
    $cat = get_last_category($ID);
    if ($cat != null) $catName = $cat->cat_name;
    return $catName;
}

/**
 * Get last category Url
 * @param $ID
 * @return string
 */
function get_last_category_url($ID)
{
    $catUrl = '';
    $cat = get_last_category($ID);
    if ($cat != null) $catUrl = get_category_link($cat->cat_ID);
    return $catUrl;
}

/**
 * Print the Grid Cell Inner
 */
function the_grid_cell_inner()
{
    ?>
    <div class="ratio-wrapper">
        <div class="ratio-content">
            <div class="img" style="background-image: url(<?php echo get_thumbnail_photo_url(get_the_ID()) ?>)"></div>
        </div>
        <a href="<?php the_permalink() ?>" class="d-block table-wrapper">
            <div class="d-table">
                <div class="table-cell">
                    <span class="cate"><?php echo get_last_category_name(get_the_ID()) ?></span>
                    <?php if (get_the_time('d m Y') == date('d m Y')): ?>
                        <div class="new">NEW</div>
                    <?php endif; ?>
                    <div class="three-dots title">
                        <?php the_title() ?>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php
}

/**
 * The Load More Pattern
 */
function the_load_more_pattern()
{
    if (have_posts()):
        ?>
        <div style="display:none">
            <div class="load-more-item">
                <?php
                for ($i = 1; $i <= 10; $i++)
                    if (have_posts()) {
                        the_post();
                        the_news_section();
                    } else break;
                ?>
            </div>
        </div>
        <?php
        rewind_posts();
    endif;
}

/**
 * The normal news section
 */
function the_news_section()
{
    ?>
    <section class="section">
        <div class="cate"><a
                href="<?php echo get_last_category_url(get_the_ID()) ?>"><?php echo get_last_category_name(get_the_ID()) ?></a>
        </div>
        <header>
            <h3 class="title">
                <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
            </h3>
        </header>
        <div class="row">
            <?php $thumb_url = get_thumbnail_photo_url(get_the_ID());
            if ($thumb_url): ?>
                <div class="col-sm-6 image">
                    <a href="<?php the_permalink() ?>" class="ratio-wrapper">
                        <div class="ratio-content img"
                             style="background-image: url(<?php echo $thumb_url ?>)">
                        </div>
                    </a>
                </div>
            <?php endif ?>
            <div class="col-sm-6 text">
                <p class="detail">
                    BY <span class="author"><?php the_author_posts_link() ?></span>
                    | <?php the_time('j \t\h\á\n\g n, Y') ?>
                </p>
                <p class="three-dots excerpt">
                    <?php echo get_the_excerpt() ?>
                </p>
            </div>
        </div>
    </section>
    <?php
}

/**
 * The news section-1
 */
function the_news_section_1()
{
    ?>
    <section class="section-1">
        <div class="cate"><a
                href="<?php echo get_last_category_url(get_the_ID()) ?>"><?php echo get_last_category_name(get_the_ID()) ?></a>
        </div>
        <header>
            <h3 class="title">
                <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
            </h3>
        </header>
        <?php $thumb_url = get_thumbnail_photo_url(get_the_ID());
        if ($thumb_url): ?>
        <a href="<?php the_permalink() ?>" class="ratio-wrapper">
            <div class="ratio-content img"
                 style="background-image: url(<?php echo $thumb_url ?>)">
            </div>
        </a>
        <?php endif ?>
        <p class="detail">
            BY <span class="author"><?php the_author_posts_link() ?></span>
            | <?php the_time('j \t\h\á\n\g n, Y') ?>
        </p>
        <p class="three-dots excerpt">
            <?php echo get_the_excerpt() ?>
        </p>
    </section>
    <?php
}

/**
 * The single more post
 */
function the_single_more($post)
{
    ?>
    <div class="col-sm-4">
        <div class="row">
            <div class="col-md-12 col-xs-5 image">
                <a href="<?php echo get_permalink($post->ID) ?>" class="ratio-wrapper">
                    <div class="ratio-content img"
                         style="background-image: url(<?php echo get_thumbnail_photo_url($post->ID) ?>)">
                    </div>
                </a>
            </div>
            <div class="col-md-12 col-xs-7 text">
                <h4>
                    <b><a href="<?php echo get_permalink($post->ID) ?>"><?php echo $post->post_title ?></a></b>
                </h4>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Get posts in same category
 * @param $postsNumber
 * @return array
 */
function get_posts_in_same_category($postsNumber)
{
    global $post;
    $cat_ID = array();
    $categories = get_the_category(); //get all categories for this post
    foreach ($categories as $category) {
        array_push($cat_ID, $category->cat_ID);
    }
    $args = array(
        'orderby' => 'date',
        'order' => 'DESC',
        'post_type' => 'post',
        'numberposts' => $postsNumber,
        'post__not_in' => array($post->ID),
        'category__in' => $cat_ID
    ); // post__not_in will exclude the post we are displaying
    $cat_posts = get_posts($args);
    return $cat_posts;
}

/**
 * Get posts in same author
 * @param $postsNumber
 * @return array
 */
function get_posts_in_same_author($postsNumber)
{
    global $post;
    $args = array(
        'orderby' => 'date',
        'order' => 'DESC',
        'post_type' => 'post',
        'numberposts' => $postsNumber,
        'post__not_in' => array($post->ID),
        'author' => $post->post_author
    ); // post__not_in will exclude the post we are displaying
    $au_posts = get_posts($args);
    return $au_posts;
}

/**
 * @return array of root categories
 */
function get_root_categories()
{
    $uncat = get_cat_ID('Uncategorized');
    $args = array(
        'hide_empty' => false,
        'exclude' => array($uncat)
    );
    $cats = get_categories($args);
    $res = array();
    foreach ($cats as $cat) {
        $p = get_category_parents($cat->cat_ID, false, '', false);
        if ($p == $cat->cat_name) array_push($res, $cat);
    }
    return $res;
}

/**
 * Get array of children cats' IDs
 * @param $ID
 * @return array|WP_Error
 */
function get_children_categories($ID)
{
    $res = get_term_children($ID, 'category');
    return $res;
}

/**
 * Get the first image in a post
 * @return string
 */
function catch_that_image($p = null)
{
    global $post, $posts;
    if ($p == null) $p = $post;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $p->post_content, $matches);
    $first_img = $matches[1][0];
    /*
        if(empty($first_img)) {
            $first_img = "/path/to/default.png";
        }
    /**/
    return $first_img;
}

/**
 * Get the thumb url from post id
 * @param $ID
 * @return bool|""|string
 */
function get_thumbnail_photo_url($ID)
{
    $thumb_url = ""; // thumb url
    if (has_post_thumbnail($ID)) $thumb_url = get_the_post_thumbnail_url($ID, 'large');
    else $thumb_url = catch_that_image(get_post($ID));
    return $thumb_url;
}

/**
 * remove all img tags
 * @param string $s : input html
 * @return string
 */
function removeImageTags($s = "")
{
    $res = "";
    $add = true;
    $len = strlen($s);
    for ($i = 0; $i < $len; $i++) {
        // detect <img
        if ($i < $len - 4) {
            $t = $s[$i] . $s[$i + 1] . $s[$i + 2] . $s[$i + 3];
            if ($t == "<img") $add = false;
        }
        // append
        if ($add == true) $res .= $s[$i];
        //detect >
        if ($s[$i] == ">") $add = true;
    }/**/
    return $res;
}