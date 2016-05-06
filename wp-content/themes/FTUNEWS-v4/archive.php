<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file: archive.php
 */
get_header();
?>

    <!-- BODY -->
    <div class="main">

        <!-- archive title -->
        <div class="container">
            <div class="col-xs-12">
                <h1 class="archive-title"><?php single_cat_title(); ?></h1>
            </div>
        </div>
        <!-- /archive title -->

        <!-- News -->
        <div class="container news">

            <!-- choice -->
            <div class="col-md-4 col-md-push-8 choice">
                <div class="inner">
                    <?php get_sidebar() ?>
                </div>
            </div>
            <!-- /choice -->

            <!-- main -->
            <div class="col-md-8 col-md-pull-4 load-more-container">
                <?php
                the_load_more_pattern();
                if (have_posts()):
                    the_post();
                    the_news_section_1();
                    for($i=1; $i<=9; $i++):
                        ?>
                        <hr>
                        <?php
                        if (have_posts()) {
                            the_post();
                            the_news_section();
                        } else break;
                    endfor;
                else: echo "No Posts.";
                endif;
                ?>
<!--
                <div style="padding: 0 15px;">
                    <button id="wp-load-more-btn" class="btn btn-default btn-load-more">XEM THÊM</button>
                </div>-->
            </div>
            <!-- /main -->

        </div>
        <!-- /News -->


    </div>
    <!-- /BODY -->


<?php get_footer(); ?>