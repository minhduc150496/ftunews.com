<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file index.php
 */

get_header();
the_load_more_pattern();
if (have_posts()):
    ?>

    <!-- BODY -->
    <div class="main">
        <?php if ($wp_query->post_count >= 7): ?>
        <!-- Grid -->
        <div class="container-fluid grid">
            <div class="row">
                <!-- Cell 1 -->
                <div class="col-lg-6 col-sm-8 cell-1">
                    <div class="ratio-wrapper">
                        <div class="ratio-content">
                            <div class="img" style="background-image: url(<?php echo get_template_directory_uri()?>/images/girl.jpg)"></div>
                        </div>
                        <a href="<?php echo get_site_url()?>/poll/ftucharm-2016/" class="d-block table-wrapper">
                            <div class="d-table">
                                <div class="table-cell">
                                    <span class="cate">FTUCHARM</span>
                                    <div class="three-dots title">
                                        BÌNH CHỌN HOA KHÔI ĐƯỢC YÊU THÍCH NHẤT FTUCHARM 2016
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- /Cell 1 -->
                <?php if (have_posts())
                the_post() ?>
                <!-- Cell 2 -->
                <div class="col-lg-3 col-sm-4 cell-2">
                    <?php the_grid_cell_inner() ?>
                </div>
                <!-- /Cell 2 -->
                <?php if (have_posts())
                the_post() ?>
                <!-- Cell 3 -->
                <div class="col-lg-3 col-sm-4 cell-3">
                    <?php the_grid_cell_inner() ?>
                </div>
                <!-- /Cell 3 -->
                <div class="scroll-box" style="padding:0;">
                    <?php if (have_posts())
                    the_post() ?>
                    <!-- Cell 4 -->
                    <div class="item col-lg-3 col-sm-4 cell-4">
                        <?php the_grid_cell_inner() ?>
                    </div>
                    <!-- /Cell 4 -->
                    <?php if (have_posts())
                    the_post() ?>
                    <!-- Cell 5 -->
                    <div class="item col-lg-3 col-sm-4 cell-5">
                        <?php the_grid_cell_inner() ?>
                    </div>
                    <!-- /Cell 5 -->
                    <?php if (have_posts())
                    the_post() ?>
                    <!-- Cell 6 -->
                    <div class="item col-lg-3 col-sm-4 cell-6">
                        <?php the_grid_cell_inner() ?>
                    </div>
                    <!-- /Cell 6 -->
                    <?php if (have_posts())
                    the_post() ?>
                    <!-- Cell 7 -->
                    <div class="item col-lg-3 col-sm-4 cell-7">
                        <?php the_grid_cell_inner() ?>
                    </div>
                    <!-- /Cell 7 -->
                </div>
            </div>
        </div>
        <!-- /Grid -->
        <?php endif; ?>

        <!-- News -->
        <div class="container news">
            <div class="row">
                <!-- sidebar -->
                <div class="col-md-4 col-md-push-8 sidebar">
                    <div class="inner">
                        <?php get_sidebar() ?>
                    </div>
                </div>
                <!-- /sidebar -->

                <?php if (have_posts()): ?>
                    <!-- main -->
                    <div id="load-more-container" class="col-md-8 col-md-pull-4 main-news load-more-container">
                        <?php
                        the_post();
                        the_news_section_1();
                        for ($i = 0; $i < 5; $i++):
                            if (have_posts())
                            the_post();
                            ?>
                            <hr>
                            <?php
                            the_news_section();
                        endfor; ?>
                    </div>
                    <!-- /main -->
                <?php endif; ?>
            </div>
        </div>
        <!-- /News -->
    </div>
    <!-- /BODY -->

    <?php
else: echo "No Posts.";
endif;
get_footer(); ?>
