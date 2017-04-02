/**
 * Created by Chuot Bach on 4/29/2016.
 */

/**
 * Initialization
 */
$(document).ready(function () {

    /* show mega menu */
    $(".menu-item > a").mouseenter(function() {
        var t = $(this).parent().children(".mega-menu");
        t.css("z-index",200);
        t.fadeIn(250);
    });
    /* hide mega menu */
    $(".menu-item").mouseleave(function() {
        var t = $(this).children(".mega-menu");
        t.css("z-index",100);
        t.fadeOut(250);
    });
    /* toggle harmburger */    
    $(".mobi-menu .btn-harmburger").click(function() {
        $(".mobi-menu .body").slideToggle(250);
    })

    var fading = false;	
    /* single: more of cate */
    $(".more .head .cate").click(function(){
        if (fading) return;
        $(this).addClass("active");
        $(".more .head .author").removeClass("active");
        fading = true;
        $(".more .body .same-author").fadeOut(500, function() {
            $(".more .body .same-cate").fadeIn(500, function() {    
	    fading = false;
             });
        });
    })

    /* single: more of author */
    $(".more .head .author").click(function(){
        if (fading) return;
        $(this).addClass("active");
        $(".more .head .cate").removeClass("active");
        fading = true;
        $(".more .body .same-cate").fadeOut(500, function() {
            $(".more .body .same-author").fadeIn(500, function() {
                 fading = false;
             });
        });
    })

});