<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file: single.php
 */

get_header();

if (have_posts()) : while (have_posts()): the_post();

    /* PREPARATIONS */
    /* get category */
    $cats = get_the_category(get_the_ID());
    $catName = '';
    $catUrl = '';
    if (!empty($cats)) {
        $cat = $cats[count($cats) - 1];
        $catName = $cat->cat_name;
        $catUrl = get_category_link($cat->cat_ID);
    }

    ?>

    <!-- BODY -->
    <div class="main">
        <?php
        /* write banner */
        $thumb_url = get_thumbnail_photo_url(get_the_ID());
        if ($thumb_url):
            ?>
            <!-- Banner -->
            <div class="ratio-wrapper single-banner">
                <div class="ratio-content img" style="background-image: url(<?php echo $thumb_url ?>)"></div>
            </div>
            <!-- /Banner -->
            <?php
        endif;

        ?>

        <!-- Post -->
        <div class="container one-post">
            <!-- Heading -->
            <div class="title"><?php the_title() ?></div>
            <div class="detail">
                <a href="<?php echo $catUrl ?>" class="cate"><?php echo $catName ?></a> | by
                <b><?php the_author_posts_link() ?></b>, <?php the_time('j \t\h\á\n\g n, Y') ?>
            </div>
            <!-- /Heading -->

            <!-- Content -->
            <div class="content">
                <?php the_content() ?>
            </div>
            <!-- /Content -->

            <!-- More Posts Of -->
            <div class="more">
                <div class="head">
                    <span class="more-of">MORE OF</span>
                    <span class="cate active"><?php echo $catName ?></span>
                    <span class="author"><?php the_author() ?></span>
                </div>

                <div class="body">

                    <div class="row same-cate">
                        <?php
                        $ps = get_posts_in_same_category(3);
                        if ($ps):
                            foreach ($ps as $p) {
                                the_single_more($p);
                            }
                        else: echo 'No Posts';
                        endif;
                        ?>
                    </div>

                    <div class="row same-author">
                        <?php
                        $ps = get_posts_in_same_author(3);
                        if ($ps):
                            foreach ($ps as $p) {
                                the_single_more($p);
                            }
                        else: echo "No Posts.";
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <!-- /More Posts Of -->
        </div>
        <!-- /Post -->


    </div>
    <!-- /BODY -->


    <?php
endwhile;
else: ?>

    <p>No posts. :(</p>

    <?php
endif;
get_footer(); ?>