/**
 * Animating votesbar
 */
jQuery.fn.animateVotesBar = function(){
    jQuery(this).find('[data-animate-width]').each(function(){
        jQuery(this).animate({width: jQuery(this).data('animate-width')}, jQuery(this).data('animate-duration'));
    });
};
jQuery(function(){
    jQuery('.tp-poll-container').each(function(){
        // Fastckick
        FastClick.attach(this);
    }).animateVotesBar();
});

/**
 * Ajaxify forms
 */
jQuery(document).delegate('.tp-poll-container button[name^="tp_"]', 'click', function(e) {
    e.preventDefault();
    var $this = jQuery(this);
    var $container = $this.closest('.tp-poll-container');
    
    var fields = $container.find('form').serializeArray();
    fields.push({name: $this.attr('name'), value: $this.val()});
    
    $container.fadeTo('slow', 0.5).css({'pointer-events': 'none', 'min-height' : $container.outerHeight()});
    
    jQuery.ajax({
        url: this.action,
        type: 'POST',
        data: fields,
        success: function(content)
        {
            var $content = jQuery(content).hide();
            $this.closest('.tp-poll-container').after($content).fadeOut(function() {
                jQuery(this).fadeOut(function(){
                    jQuery(this).remove();
                })
                $content.fadeIn().animateVotesBar();
                // Fastckick
                FastClick.attach($content[0]);
                // Refresh Addthis Buttons
                if (window['addthis']){
                    addthis.toolbox('.addthis_toolbox');
                }
            });
        }
    });
});