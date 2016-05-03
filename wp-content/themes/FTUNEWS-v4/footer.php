<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 * @file footer.php
 */
?>

<!-- FOOTER -->
<div class="footer">
    <ul>
        <li class="clear">
            <img class="logo" width="30" height="30" src="<?php echo get_template_directory_uri()?>/images/Logo%20ftunews%20tron.png" alt="">
            <a class="title" href="<?php echo get_site_url()?>">FTUNEWS</a>
        </li>
        <li>
            <a href="#">contact us</a>
        </li>
        <li>
            <a href="#">advertise</a>
        </li>
        <li>
            <a href="#">privacy</a>
        </li>
        <li>
            <a href="#">privacy</a>
        </li>
        <li>
            &copy; FTUNEWS 2016
        </li>
    </ul>
</div>
<!-- /FOOTER -->


<!-- Javascript at the bottom for fast page loading -->
<script src="<?php echo get_template_directory_uri()?>/html5-boilerplate/js/jquery-1.12.1.min.js"></script>
<script src="<?php echo get_template_directory_uri()?>/html5-boilerplate/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri()?>/html5-boilerplate/slick/slick.min.js"></script>
<script src="<?php echo get_template_directory_uri()?>/js/ftunews.js"></script>

<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $GLOBALS["TEMPLATE_RELATIVE_URL"] ?>js/vendor/jquery-1.8.0.min.js"><\/script>')</script>


<?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"] . "html5-boilerplate/js/plugins.js") ?>
<?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"] . "html5-boilerplate/js/main.js") ?>

<!-- asynchronous google analytics: mathiasbynens.be/notes/async-analytics-snippet
     change the UA-XXXXX-X to be your site's ID -->
<!-- WordPress.com does not allow Google Analytics code to be built into themes they host.
     Add this section from HTML Boilerplate manually (html5-boilerplate/index.html), or use a Google Analytics WordPress Plugin-->

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
    (function (d, t) {
        var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
        g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g, s)
    }(document, 'script'));
</script>

<?php wp_footer(); ?>

<script src="<?php echo get_template_directory_uri(); ?>/html5-boilerplate/slick/slick.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/ftunews.js"></script>

</body>
</html>
