<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file: category.php
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
            <div class="col-md-8 col-md-pull-4">
                <?php if (have_posts()):
                    the_post();
                    the_news_section_1();
                    while (have_posts()): the_post();
                        ?>
                        <hr>

                        <?php
                        the_news_section();
                    endwhile;
                else: echo "No Posts.";
                endif;
                ?>

            </div>
            <!-- /main -->

        </div>
        <!-- /News -->


    </div>
    <!-- /BODY -->


<?php get_footer(); ?>