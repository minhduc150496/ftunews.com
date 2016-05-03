var tp_qi_media = tp_qi_media || {};

(function ($) {
    var media;

    tp_qi_media.media = media = {
        buttonId: '.button.upload-question-image',
        frame: function () {

            if (this._frame)
                return this._frame;

            this._frame = wp.media({
                title: 'Select Your Images',
                button: {
                    text: 'Choose'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });

            this._frame.on('ready', this.ready);

            this._frame.state('library').on('select', this.select);

            return this._frame;
        },
        ready: function () {
            $('.media-modal').addClass('no-sidebar smaller');
        },
        select: function () {
            this.get('selection').map(media.insertImage);
        },
        insertImage: function (attachment) {
            $('.question-image-image').val(attachment.get('sizes').full.url);

        },
        init: function () {
            $(media.buttonId).on('click', function (e) {
                e.preventDefault();
                media.frame().open();
            });
        }
    };

    $(media.init);
})(jQuery);