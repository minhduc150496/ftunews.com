<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file footer.php
 */
?>
  <!-- footer -->
  <footer class="container no-padding">
      <div class="text-white background-black">
        <div class="row">

          <div class="col-md-3">
            <h1 class="footer-ftunews"><a href="<?php echo get_site_url() ?>">FTUNEWS</a></h1>
            <form role="search" method="get" action="<?php echo get_site_url(),'/'; ?>">
              <div class="input-group">
                  <input type="text" class="form-control no-border-radius" placeholder="Search for article & more" name="s">
                  <span class="input-group-btn">
                      <button class="btn btn-default no-border-radius" type="submit" ><span class="fa fa-search"></span></button>
                  </span>
              </div><!-- /input-group -->
            </form>
          </div>


          <?php
          $args = array(
              'hide_empty' => 0,
              'parent' => 0,
              'exclude' => '1',
          );
          $cats = get_categories($args);
          $nCats = count($cats);
          ?>
          <div class="col-md-3 margin-top-20px footer-category">
            <?php
              for($i=0; $i<$nCats/2; $i++) {
                $cat = $cats[$i];
                ?>
                <div><a href ="<?php echo get_site_url(),'/category/',$cat->slug ?>"><?php echo $cat->cat_name ?></a></div>
                <?php
              }
            ?>
          </div>
          <div class="col-md-3 margin-top-20px footer-category">
            <?php
            for($i=$nCats/2; $i<$nCats; $i++) {
              $cat = $cats[$i];
              ?>
              <div><a href ="<?php echo get_site_url(),'/category/',$cat->slug ?>"><?php echo $cat->cat_name ?></a></div>
              <?php
            }
            ?>
          </div>
          <div class="col-md-3 margin-top-20px text-white">
              <div><a href="">contact us</a></div>
              <div><a href="">advertise</a></div>
              <div><a href="">privacy</a></div>
              <div><a href="">terms of service</a></div>
              <div><a href="">site map</a></div>
              <div><a href="">help</a></div>
              <div><a href="">subscription</a></div>
          </div>
        </div>
        <div class="footer-copy">
          &copy; 2016 FTUNEWS
        </div>
      </div>
  </footer>


  <!-- Javascript at the bottom for fast page loading -->
  <script src="<?php echo get_template_directory_uri(); ?>/html5-boilerplate/js/jquery-1.12.1.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/html5-boilerplate/js/bootstrap.min.js"></script>

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="<?php echo $GLOBALS["TEMPLATE_RELATIVE_URL"] ?>js/vendor/jquery-1.8.0.min.js"><\/script>')</script>


  <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/plugins.js") ?>
  <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/main.js") ?>

  <!-- asynchronous google analytics: mathiasbynens.be/notes/async-analytics-snippet
       change the UA-XXXXX-X to be your site's ID -->
  <!-- WordPress.com does not allow Google Analytics code to be built into themes they host. 
       Add this section from HTML Boilerplate manually (html5-boilerplate/index.html), or use a Google Analytics WordPress Plugin-->
	   
  <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
  <script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  </script>
			   
  <?php wp_footer(); ?>

  <script src="<?php echo get_template_directory_uri(); ?>/html5-boilerplate/slick/slick.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/ftunews.js"></script>

</body>
</html>
