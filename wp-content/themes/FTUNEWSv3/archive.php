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
      the_vertical_thumbnail_rows(false, 3);

    else: echo "no posts.";
    endif;
    ?>
  </main>


<?php get_footer(); ?>