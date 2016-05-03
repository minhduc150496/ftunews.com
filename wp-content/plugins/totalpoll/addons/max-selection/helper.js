jQuery(document).delegate('[data-limit-selection] input[type="checkbox"]', 'change', function(e) {
    var $parent = jQuery(this).closest('.tp-poll-container');
    if ($parent.find('input[type="checkbox"]:checked').length >= $parent.data('limit-selection')) {
        $parent.find('input[type="checkbox"]').not(':checked').attr('disabled', '');
    } else {
        $parent.find('input[type="checkbox"]').removeAttr('disabled');
    }

});