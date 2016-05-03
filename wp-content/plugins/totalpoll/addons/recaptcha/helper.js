var _tpReCaptcha = function () {
    jQuery('.tp-poll-container .g-recaptcha').each(function () {
        this.innerHTML = '';
        this.id = 'tp-recaptcha-' + Math.floor(Math.random() * (new Date().getTime() - 1 + 1)) + 1;
        grecaptcha.render(this.id, {
            'sitekey': jQuery(this).data('sitekey')
        });
    });
}