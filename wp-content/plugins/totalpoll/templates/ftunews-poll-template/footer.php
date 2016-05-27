<?php if (!defined('ABSPATH')) exit; // Shhh   ?>
<?php if (is_poll_shareable()): ?>
    <div class="addthis_toolbox addthis_default_style addthis_20x20_style">
        <a class="addthis_button_facebook"
           addthis:title="<?php echo esc_attr(get_poll_sharing_expression('facebook')); ?>"></a>
        <a class="addthis_button_twitter"
           addthis:title="<?php echo esc_attr(get_poll_sharing_expression('twitter')); ?>"></a>
        <a class="addthis_button_google_plusone_share"
           addthis:title="<?php echo esc_attr(get_poll_sharing_expression('googleplus')); ?>"></a>
    </div>
    <script>!function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (!d.getElementById(id)) {
                js = d.createElement(s);
                js.id = id;
                js.src = "//s7.addthis.com/js/300/addthis_widget.js";
                fjs.parentNode.insertBefore(js, fjs);
            }
        }(document, "script", "addthis");
    </script>
<?php endif; ?>

<?php if (!is_poll_finished()):?>
<script type="text/javascript">
    var remainingTime = <?php echo get_remaining_time_to_end() ?>;

    document.getElementById("remaining-time").innerHTML = getTimeString(remainingTime);
    var myTimer = setInterval(function() {
        --remainingTime;
        var newTime = getTimeString(remainingTime);
        document.getElementById("remaining-time").innerHTML = newTime;
        if (remainingTime==0) {
            clearInterval(myTimer);
            document.getElementById("remaining-time-box").innerHTML = "Cuộc bình chọn đã kết thúc!";
        }
    }, 1000);

    function getTimeString(par) {
        var t =  par;
        var hours = Math.floor( t / (60*60) );
        t %= (60*60);
        var mins = Math.floor( t / 60 );
        if (mins<10) mins = '0'+mins;
        t %= 60;
        var secs = t;
        if (secs<10) secs = '0'+secs;
        var s = hours + ':' + mins + ':' + secs;
        return s;
    }
</script>
<?php endif; ?>