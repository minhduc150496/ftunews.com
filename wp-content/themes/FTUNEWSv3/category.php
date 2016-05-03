<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file: category.php
 */

get_header();

?>

<!-- category name -->
<div class="background-dark-gray">
    <div class="container text-category-name">
        <div><?php single_cat_title() ?></div>
    </div>
</div>

<!-- main -->
<main class="container load-more-container">
<?php
the_load_more_pattern();
if (have_posts()):
    if (have_posts()):
        the_post();
        ?>
        <!-- full width thumbnail -->
        <div class="row">
            <div class="col-md-35 margin-top-20px">
                <a href="<?php the_permalink() ?>" class="display-block background-size-position ratio-16-9"
                   style="background-image: url(<?php if (has_post_thumbnail()) the_post_thumbnail_url('large');
                   else echo catch_that_image(); ?>)">
                </a>
            </div>
            <div class="col-md-25 margin-top-20px">
                <div class="thumbnail-text">
                    <a href="<?php the_permalink()?>" class="thumbnail-title">
                        <?php the_title() ?>
                    </a>
                    <div class="thumbnail-text-small">
                        by <span class="text-orange"><?php the_author() ?></span>, <?php the_time('d/m/Y')?>
                    </div>
                    <div class="thumbnail-text-excerpt">
                        <?php echo removeImageTags(get_the_excerpt()) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php  endif; ?>
        <!-- vertical thumbnails -->
        <?php
        the_vertical_thumbnail_rows(false, 3);

else: echo "no posts.";
endif;
?>
</main>


<?php get_footer(); ?>