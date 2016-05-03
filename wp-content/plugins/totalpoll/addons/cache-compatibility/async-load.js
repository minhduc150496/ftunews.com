if (window['tp_async_polls']) {
    jQuery(document).ready(function ($) {
        $(tp_async_polls).each(function (index, poll) {
            $(poll.container).load(totalpoll_cache_compatibility.ajaxurl, {action: 'load_tp', tp_poll_id: poll.id}, function () {
                FastClick.attach(this);
                $(this).animateVotesBar();
            });
        });
    });
}