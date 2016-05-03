jQuery(function ($) {

    /**
     * Wrapper
     */
    var $wrapper = $('#tp-wrapper');

    /**
     * Serialize To JSON
     */
    $.fn.serializeObject = function ()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    /**
     * TinyMCE Dynamic Loading
     */
    $.fn.initTinyMCE = function () {
        var id = this[0].id;
        var settings = $.extend(true, {}, tinyMCEPreInit.mceInit['totalpoll']);
        settings.selector = '#' + id;

        tinyMCEPreInit.mceInit[id] = settings;
        tinymce.init(settings);

        quicktags({'id': id, 'buttons': tinyMCEPreInit.qtInit['totalpoll'].buttons});
        QTags._buttonsInit();
    };



    /**
     * Compile templates
     */
    var templates = {};
    $wrapper.find('.choice-template').each(function (elementIndex, template) {
        templates[template.id] = function (index) {
            return template.innerHTML.replace(/\{\{index\}\}/g, index);
        }
    });

    /**
     * Tabs
     */
    $wrapper.find('> .section .title').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.prev('.tab-state').attr('checked', $this.parent().hasClass('collapsed') ? true : false);
        $this.next('.content').slideToggle(function () {
            $(this).parent().toggleClass('collapsed');
        });
    });

    /**
     * Related toggle
     */
    $wrapper.on('click', '[data-toggler]', function (e) {
        if (this.tagName == 'A') {
            e.preventDefault();
        }

        $(this).toggleClass('toggled');

        $('[data-toggle="' + $(this).data('toggler') + '"]').stop().slideToggle(function () {
            $(this).toggleClass('toggled');
        });
    });

    /**
     * Choices
     */
    $wrapper.find('.choice-types button').click(function (e) {
        e.preventDefault();
        var template = templates[$(this).data('template')]($('.choices-container > .choice').length);
        $wrapper.find('.choices-container').append(template).trigger('refresh');
    });

    /**
     * Sortable choices
     */
    $wrapper.find('.choices-container').sortable({
        axis: 'y',
        items: '> .choice',
        handle: '.choice-controllers .move',
        cancel: 'input',
        containment: 'parent',
        revert: 100,
        tolerance: 'pointer',
        placeholder: 'choice-sortable-placeholder',
        forceHelperSize: true,
        forcePlaceholderSize: true,
        update: function () {
            $wrapper.find('.choices-container > .choice').trigger('refresh');
        }
    });

    /**
     * Refresh indexes
     */
    $wrapper.find('.choices-container').on('refresh', function () {
        
        if (typeof tinymce.editors !== 'undefined') {
            $(tinymce.editors).each(function () {
                var selector = '#wp-' + this.settings.id + '-wrap.tmce-active';
                this.destroy();
                setTimeout(function () {
                    $(selector).find('.switch-tmce').click();
                }, 100);
            });
        }
        $wrapper.find('.choices-container').sortable('refresh');
        $wrapper.find('.choices-container .choice').each(function (choiceIndex) {
            $('input, select, textarea', this).attr('name', function () {
                this.name = this.name.replace(/tp_options\[choices\]\[(\d+)\]/g, function (match, index, position, search) {
                    return match.replace(index, choiceIndex);
                });
            });
        });
    });

    /**
     * Delete choice
     */
    $wrapper.on('click', '.choice .choice-controllers .delete', function (e) {
        e.preventDefault();
        $(this).closest('.choice').remove();
        $wrapper.find('.choices-container').trigger('refresh');
    });

    /**
     * Upload file to choice
     */
    $wrapper.on('click', '.choice .choice-controllers .upload', function (e) {
        e.preventDefault();
        TPMediaUploader.frame().open();
        $('[data-upload-holder]').removeAttr('data-upload-holder');
        $(this).closest('.choice').find('.upload-holder').attr('data-upload-holder', '');
    });

    /**
     * Override votes
     */
    $('.override-votes').change(function () {
        $('.choices-container').toggleClass('show-votes-fields');
    });

    /**
     * Datepicker field
     */
    $wrapper.find('.datepicker').each(function () {
        $(this).datepicker()
    });

    /**
     * Color field
     */
    if (typeof $.farbtastic == "function") {

        var color_picker_HTML = '<div class="tp-color-picker"></div>';
        $('.tp-color-field')
                .focus(function () {
                    $(this).after(color_picker_HTML).next(".tp-color-picker").farbtastic(this).slideDown();
                })
                .blur(function () {
                    $(this).next(".tp-color-picker").slideUp(function () {
                        $(this).remove();
                    });
                });

    } else {
        $(".tp-color-field").wpColorPicker({change: function () {
                setTimeout(refreshPreview, 0);
            }});
    }

    /**
     * Need refresh fields
     */
    $(document).on('change', '.need-refresh input, .need-refresh textarea, .need-refresh select', function () {
        refreshPreview();
    });

    /**
     * Refresh preview
     */
    window.refreshPreview = function () {
        var $form = $('.preview-pane iframe').contents().find('#preview-variables');
        $('.need-refresh').find('input, textarea, select').each(function () {
            $('<textarea></textarea>').addClass('hide').attr({name: $(this).attr('name')}).val($(this).val()).appendTo($form);
        });
        $form.submit();
    }

    /**
     * Preset loader
     */
    $('select[name="tp_options[template][preset][name]"]').on('change', function (e) {
        if (confirm('Are you sure?')) {
            $('input[name="tp_options[template][preset][load]"]').val($(this).val());
            $('#publish').click();
        } else {
            $(this).val($(this).find('[selected]').val());
        }
    });

    /**
     * Preset Save
     */
    $('button[name="tp_options[template][preset][new]"]').on('click', function (e) {
        $(this).val(prompt('Preset ID'));
    });
    /**
     * Preset delete
     */
    $('button[name="tp_options[template][preset][delete]"]').on('click', function (e) {
        if (!confirm('Are you sure?')) {
            e.preventDefault();
        }
    });

    /**
     * Auto-collapse
     */
    $wrapper.on('click', '.customizer a[data-toggler]:not(.toggled)', function (e) {
        $('.customizer a.toggled').not(this).click();
    });

    /**
     * Choice types floating
     */
    var floatChoiceTypes = function () {
        var reference = $(window).scrollTop();
        var parent = $wrapper.find('.choices-container');
        var child = $wrapper.find('.choice-types');
        if (reference > parent.offset().top) {

            if (reference + child.outerHeight() < parent.offset().top + parent.outerHeight()) {
                child.css({marginTop: reference - parent.offset().top + 42});
            }

        } else {
            child.css({marginTop: 0});
        }
    }
    $(window).scroll(floatChoiceTypes);

    /**
     * Media Uploader
     */
    TPMediaUploader = {
        frame: function () {

            if (this._frame)
                return this._frame;

            this._frame = wp.media({
                title: wp.media.view.l10n.insertMediaTitle,
                multiple: false,
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
            this.get('selection').map(TPMediaUploader.insertImage);
        },
        insertImage: function (attachment) {
            var $parent = $('[data-upload-holder]').parent();
            $parent.find('input[name*="label"]').val(attachment.get('title'));
            $parent.find('[data-upload-holder]').val(attachment.get('sizes').thumbnail.url);
            $parent.find('input[name*="full"]').val(attachment.get('sizes').full.url);
        }
    }
});