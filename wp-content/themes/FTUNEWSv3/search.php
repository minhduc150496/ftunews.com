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
      <?php the_search_query() ?>
    </div>
  </div>

  <!-- main -->
  <main class="container load-more-container">
    <?php
    the_load_more_pattern();
    if (have_posts()):
      the_vertical_thumbnail_rows(false, 3);
      ?>

      <?php
    else: echo "no post";
    endif;
    ?>
  </main>


<?php get_footer(); ?>