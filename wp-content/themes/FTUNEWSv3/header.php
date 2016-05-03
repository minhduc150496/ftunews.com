<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file header.php
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
       Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

    <meta name="viewport" content="width=device-width">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/css/normalize.css") ?>
    <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/css/main.css") ?>
    
    <!-- Wordpress Templates require a style.css in theme root directory -->
    <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"]."style.css") ?>
    
    <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
    <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/vendor/modernizr-2.6.1.min.js") ?>

    <!-- Wordpress Head Items -->
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <?php wp_head(); ?>

    <link href="<?php echo get_template_directory_uri(); ?>/images/Logo ftunews tron.png" rel="shortcut icon" type="image/x-icon" />
    <link href="<?php echo get_template_directory_uri(); ?>/html5-boilerplate/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri(); ?>/html5-boilerplate/css/font-awesome.min.css" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Roboto&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Noto+Serif&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Kanit:400,300,300italic,400italic,500,500italic,600,600italic,700,700italic,800,800italic&subset=latin,vietnamese' rel='stylesheet' type='text/css'>

    <link href="<?php echo get_template_directory_uri(); ?>/html5-boilerplate/slick/slick.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri(); ?>/ftunews.css" rel="stylesheet"/>
</head>
<body <?php body_class(); ?>>
  <!--[if lt IE 7]>
    <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
  <![endif]-->


    <!-- header -->
    <header>
      <div class="container">
        <div class="float-left display-none-xs" style="margin-top:30px;">
            <div class="header-ftunews">
              <a href="<?php echo get_site_url(); ?>">FTUNEWS</a>
            </div>
            <div class="vertical-line"></div>
            <div class="trending-container">
                <div class="float-left" style="height:20px">
                  <div class="label-trending">
                    TRENDING &nbsp;
                  </div><div class="trending-prev fa fa-angle-left"></div><div class="trending-next fa fa-angle-right"></div>
                </div>
                <div class="trending-slick">
                <?php
                // Most view
                  $args = array(
                    'posts_per_page' => 5,
                    'order' => 'DESC',
                    'orderyby' => 'date'
                  );
                  query_posts($args);
                  while (have_posts()):
                    the_post();
                ?>
                <div>
                  <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                </div>
                <?php
                  endwhile;
                  wp_reset_query();
                ?>
              </div>
            </div>
            <div class="header-social-box" style="float: right">
              <div class="header-follow-us">FOLLOW US ON</div>
              <a href="https://www.facebook.com/iloveftunews" class="fa fa-facebook header-social" style="margin-right:15px"></a>
              <a href="https://www.youtube.com/user/ftunews" class="fa fa-youtube header-social" ></a>
            </div>
            <div class="vertical-line" style="float:right"></div>
        </div>
        <nav class="navbar navbar-default">
        <div class="navbar-header" style="position: relative">
          <div class="header-ftunews-collapsed">
            <a href="<?php echo get_site_url() ?>">FTUNEWS</a>
          </div>
          <button type="button" class="navbar-toggle collapsed btn-hamburger" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bars"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <?php
                $args = array(
                    'hide_empty' => 0,
                    'parent' => 0,
                    'exclude' => '1',
                );
                $cats = get_categories($args);
                foreach ($cats as $cat) {
                  ?>
                  <li><a href="<?php echo get_site_url(),'/category/',$cat->slug ?>"><?php echo $cat->cat_name ?></a></li>
                  <?php
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="javascript:jQuery('#header-search-bar').slideToggle();">search</a></li>
            </ul>
          </div>
      </nav>
      </div>
    </header>
    <div id="header-search-bar" class="header-search-bar">
      <form role="search" method="get" action="<?php echo get_site_url(),'/'; ?>">
        <input type="text" class="header-search-bar-input" placeholder="Search for article & more" name="s">
      </form>
    </div>
