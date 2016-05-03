<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file index.php
 */

get_header();

the_load_more_pattern();
if (have_posts()):
  $cont = true; // continuing flag
?>


<!-- first look/ grid top -->
<div class="container-fluid ">
  <div class="row gridtop">
    <?php the_gridtop_item('col-lg-15 col-sm-3 col-xs-12 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1 ratio-2-1-xs') ?>
    <?php the_gridtop_item('col-lg-25 col-sm-6 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-2-1 ratio-1-1-xs') ?>
    <?php the_gridtop_item('col-lg-15 col-sm-3 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1') ?>
    <?php the_gridtop_item('col-lg-15 col-sm-3 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1') ?>
    <?php the_gridtop_item('col-lg-15 col-sm-3 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1') ?>
    <?php the_gridtop_item('col-lg-15 col-sm-3 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1') ?>
    <?php the_gridtop_item('col-lg-15 col-sm-3 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1') ?>

    <?php the_gridtop_item('col-lg-15 col-sm-6 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1 ratio-2-1-md ratio-1-1-xs') ?>
    <?php the_gridtop_item('col-lg-15 col-sm-6 col-xs-6 no-padding gridtop-item',
        'display-block background-size-position ratio-1-1 ratio-2-1-md ratio-1-1-xs') ?>

  </div>
</div>


    <!-- MAIN -->
<main class="container load-more-container">
    <!-- SECTION 1: -->
    <?php
      if ($cont)
        $cont = $cont && the_vertical_thumbnail_row();
    ?>

    <!-- SECTION 2: Videos -->
    <div class="row section2">
    </div>

    <!-- SECTION 3: 3 horizon, shortnews -->
    <div class="row section3 section-gap">
      <div class="col-sm-8">
        <?php
          if ($cont)
            for($i = 0; $i<3 && ($cont = $cont && the_horizontal_thumbnail()); $i++);
        ?>
      </div>
      <div class="col-sm-4">
          <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/images/FK%20banner%20FTU2.jpg">
      </div>
    </div>

    <div class="section4">
      <?php
        if ($cont)
          $cont = $cont && the_banner_post();
      ?>
    </div>

    <!-- SECTION 5: 3 vertical -->
    <?php
      if ($cont)
        $cont = $cont && the_vertical_thumbnail_row();
    ?>

    <!-- SECTION 6: 3 horizon -->
    <div class="row section6 section-gap">
      <div class="col-sm-4">
          <div class="banner-drthanh">
              <embed style="position: absolute; top:0; left:0; width:100%; height: 100%" src="<?php echo get_template_directory_uri(); ?>/flash/PHN_350x340.swf"></embed>
          </div>
      </div>
      <div class="col-sm-8">
        <?php
          if ($cont)
            for($i = 0; $i<3 && ($cont = $cont && the_horizontal_thumbnail()); $i++);
        ?>
      </div>
    </div>

    <!-- SECTION 7: -->
    <?php
      if ($cont)
        $cont = $cont && the_vertical_thumbnail_row();
    ?>

</main>

<?php
else: echo "no posts.";
endif;
?>

<?php get_footer(); ?>


