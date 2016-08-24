<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 */

get_header(); ?>

<div id="main" role="main" class="main">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <article class="post" id="post-<?php the_ID(); ?>">
    <div class="container">
        <h1 class="archive-title"><?php the_title(); ?></h1>
    </div>
    <!--header>
      <h2><?php the_title(); ?></h2>
    </header-->

    <div class="container">
      <?php the_content() ?>
      <!--?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?-->
    </div>

    <!--?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?-->

  </article>
  <?php endwhile; endif; ?>
  <!--?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?-->

  <!--?php comments_template(); ?-->

</div>


<?php get_footer(); ?>
