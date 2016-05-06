<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file header.php
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
       Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <meta name="viewport" content="width=device-width">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"] . "html5-boilerplate/css/normalize.css") ?>
    <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"] . "html5-boilerplate/css/main.css") ?>

    <!-- Wordpress Templates require a style.css in theme root directory -->
    <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"] . "style.css") ?>

    <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
    <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"] . "html5-boilerplate/js/vendor/modernizr-2.6.1.min.js") ?>

    <!-- Wordpress Head Items -->
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>

    <?php wp_head(); ?>

    <link href="<?php echo get_template_directory_uri() ?>/images/Logo%20ftunews%20tron.png" rel="shortcut icon"
          type="image/x-icon"/>
    <link href="<?php echo get_template_directory_uri() ?>/html5-boilerplate/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri() ?>/html5-boilerplate/css/font-awesome.min.css" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Roboto&subset=latin,vietnamese' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Noto+Serif&subset=latin,vietnamese' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro&subset=latin,vietnamese' rel='stylesheet'
          type='text/css'>

    <link href="<?php echo get_template_directory_uri() ?>/html5-boilerplate/slick/slick.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri() ?>/css/WP-default.css" rel="stylesheet"/>
    <link href="<?php echo get_template_directory_uri() ?>/ftunews.css" rel="stylesheet"/>

</head>
<body <?php body_class(); ?>>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser
    today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better
    experience this site.</p>
<![endif]-->

<?php
/*
$uncat = get_cat_ID('Uncategorized');
$args = array(
    'hide_empty' => false,
    'exclude' => array($uncat)
);
$cats = get_categories($args);
foreach ($cats as $cat) {
    $p = get_category_parents($cat->cat_ID, false, '', false);
    if ($p == $cat->cat_name) echo $cat->cat_name . " " . $cat->cat_ID . "<br> ";
}
$cats = get_term_children(get_cat_ID('Cảm thức'), 'category');
foreach ($cats as $cat) {
    echo get_cat_name($cat);
}/**/
?>

<!-- HEADER -->
<div class="header clear">

    <!-- Search Form -->
    <form class="search" role="search" method="get">
        <input class="input" type="text" autocomplete="off" placeholder=" search for article & more" name="s">
        <button class="btn btn-link"><i class="fa fa-search"></i></button>
    </form>
    <!-- /Search Form -->

    <!-- Logo -->
    <div class="logo">
        <a href="<?php echo get_site_url() ?>">
            <img width="80" height="80"
                 src="<?php echo get_template_directory_uri() ?>/images/Logo%20ftunews%20tron.png" alt="">
        </a>
    </div>
    <!-- /Logo -->

    <!-- Big Stories -->
    <div class="clear">
        <div class="title">
            CLB TRUYỀN THÔNG FTUNEWS<br>
            'TIL EVERYTHING IS SHARED
        </div>
        <div class="social">
            <i class="follow">FOLLOW US ON </i>
            <a href="https://www.facebook.com/iloveftunews" class="btn btn-link"><i class="fa fa-facebook"></i></a>
            <a href="https://www.youtube.com/user/ftunews" class="btn btn-link"><i class="fa fa-youtube"></i></a>
        </div>
        <div class="menu header-menu">
            <ul>
                <?php
                $cats = get_root_categories();
                foreach ($cats as $cat): ?>
                    <li class="menu-item">
                        <a href="<?php echo get_category_link($cat->cat_ID) ?>"><?php echo $cat->cat_name ?></a>
                        <div class="mega-menu clear">
                            <!-- sub menu -->
                            <div class="sub-menu">
                                <ul>
                                    <?php
                                    $subs = get_children_categories($cat->cat_ID);
                                    foreach ($subs as $sub):
                                    ?>
                                    <li>
                                        <a href="<?php echo get_category_link($sub) ?>"><?php echo get_cat_name($sub)?> <i class="fa fa-play"></i></a>
                                    </li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                            <a class="all" href="<?php echo get_category_link($cat->cat_ID) ?>">TẤT CẢ</a>
                            <!-- /sub menu -->
                            <!-- slick mega -->
                            <div class="slick-mega clear">
                                <div class="wrapper">
                                    <?php
                                    $args = array(
                                        'posts_per_page' => 5,
                                        'category' => $cat->cat_ID,
                                        'orderby' => 'data',
                                        'order' => 'DESC',
                                    );
                                    $ps = get_posts($args);
                                    foreach ($ps as $p): ?>
                                    <a href="<?php echo get_permalink($p->ID) ?>" class="item">
                                        <?php $thumb_url = get_thumbnail_photo_url($p->ID);
                                        if ($thumb_url):?>
                                        <div class="ratio-wrapper">
                                            <div class="ratio-content img"
                                                 style="background-image: url(<?php echo $thumb_url?>)">
                                            </div>
                                        </div>
                                        <?php endif ?>
                                        <h5 class="three-dots title"><?php echo $p->post_title ?></h5>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <!-- /slick mega -->
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!-- /Big Stories -->

    <!-- Mobi menu -->
    <div class="mobi-menu">
        <div class="btn btn-link btn-harmburger"><i class="fa fa-bars"></i></div>
        <ul class="body">
            <?php
                $cats = get_root_categories();
                foreach ($cats as $cat):
            ?>
            <li>
                <a href="<?php echo get_category_link($cat->cat_ID) ?>"><?php echo $cat->cat_name ?></a>
                <ul>
                    <?php
                    $subs = get_children_categories($cat->cat_ID);
                    foreach ($subs as $sub):
                    ?>
                    <li>
                        <a href="<?php echo get_category_link($sub) ?>"><?php echo get_cat_name($sub)?></a>
                    </li>
                    <?php endforeach ?>
                </ul>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <!-- /Mobi menu -->
</div>
<!-- /HEADER -->
