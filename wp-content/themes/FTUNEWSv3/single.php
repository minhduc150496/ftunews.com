<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file: single.php
 */

get_header(); ?>


<?php if (have_posts()) : while(have_posts()): the_post(); ?>

<!-- first look -->
<div class="container-fluid single-bigpic ratio-16-9"
     style="background-image: url(<?php
     $has_thumb = true;
     if (has_post_thumbnail()) the_post_thumbnail_url('large');
     else {
        $url = catch_that_image();
        if (empty($url)) $has_thumb = false;
        else echo $url;
     } ?> );">
  <div class="single-table">
    <div class="single-tablecell">
    <div class="single-posthead <?php if (!$has_thumb) echo 'single-text-black'; ?>">
        <div class="single-category">
          <?php
            $category = get_the_category( get_the_ID() );
            echo $category[0]->cat_name;
          ?>
        </div>
        <h2 class="single-title"><?php the_title() ?></h2>
        <div class="single-details">
          by <span class="text-orange"><?php the_author() ?></span> on <?php the_time('F d, Y') ?> at <?php the_time('G:i') ?>
        </div>
      </div>
    </div>
  </div>
</div>

<main class="container single-main" >
  <div class="row">
    <div class="col-sm-2 single-aside margin-top-20px"></div>
    <div class="col-sm-8 margin-top-20px">
      <div class="single-article">
          <?php the_content(); ?>
      </div>
    </div>
  </div>
 
  <!-- Related -->
  <div class="single-related">
    <div class="single-related-head">RELATED</div>
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8 single-related-body">
        <div class="single-related-slick">

          <?php
            $tags = get_the_tags(); // array of objects
            if ($tags):
              $tagIDs = array();
              foreach ($tags as $tag) {
                $tagIDs[] = $tag->term_id;
              }
              $args = array(
                'posts_per_page' => 10,
                'tag__in' => $tagIDs,
                'order' => 'DESC',
                'orderby' => 'date',
                'post__not_in' => array($post->ID),
              );
              $queryRelated = new WP_Query($args);
              //echo "cnt = ",count($queryRelated->posts),", ",$tagIDs[0],", ",$tagIDs[1];
              if ($queryRelated->have_posts()):
              while ($queryRelated->have_posts()):
                $queryRelated->the_post();
          ?>
          <div>
            <div class="single-related-item-outer">
              <a href="<?php the_permalink()?>" class="background-size-position single-related-item"
                 style="background-image: url(<?php if (has_post_thumbnail()) the_post_thumbnail_url('large');
                 else echo catch_that_image(); ?>)">
                <div class="background-dark single-related-item-dark">
                  <div class="single-related-item-inner">
                    <?php the_title() ?>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <?php
              endwhile;
              else: echo "no related posts.";
              endif;
              //wp_reset_query(); // what for??
            endif;
          ?>
        </div>
        <div class="single-related-prev related-prev fa fa-angle-left"></div>
        <div class="single-related-next related-next fa fa-angle-right"></div>
      </div>
    </div>
  </div>

  <!-- Facebook Comment -->
  <div class="single-fbcmt">
  </div>
</main>

<?php
  endwhile;
  else: ?>

  <p>No posts. :(</p>

<?php endif; ?>



<?php get_footer(); ?>