$(function() {
    window.autoTranslatorWorking = false;
    $(document).on('click', '.translate-it', function() {
        var id = $(this).data('id');
        var text = $('#to-translate-' + id).html();
        var c = $('#this-translated-container-' + id);
        var o = $(this);

        if (window.autoTranslatorWorking) return false;

        window.autoTranslatorWorking = true;
        o.find('img').fadeIn();
        $.ajax({
            url : baseUrl + 'autotranslator/translate',
            data : {
                text : text
            },
            type : 'POST',
            success : function(data) {
                window.autoTranslatorWorking = false;
                var data = jQuery.parseJSON(data);

                if (data.code == 0) {
                    //failed to translate
                    c.html(data.result).fadeIn();
                } else {
                    o.hide();
                    c.html(data.result).fadeIn();
                }
            }

        })
        return false;
    });
})