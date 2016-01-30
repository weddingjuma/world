var Pagelet = {
    list : [],

    process : function(){
        $('.pagelets').each(function() {
            var pagelet = $(this);

            if (jQuery.inArray(pagelet.data('id'), Pagelet.list) != -1) {

            } else {

                Pagelet.list.push(pagelet.data('id'));

                $.ajax({
                    url : baseUrl + 'load/pagelets',
                    data : {pagelets : pagelet.data('content')},
                    type : 'POST',
                    success : function(c) {
                        //Pagelet.removeIndicator(pagelet);
                        pagelet.hide().html(c).slideDown();
                        Pagelet.process();//do process again
                        reloadPlugins();
                        nailImages();
                    },
                    error : function() {
                        Pagelet.reset();
                        Pagelet.process();
                    }
                })
            }
        })
    },

    reset : function() {
        this.list = [];
    }
}

$(function() {
    Pagelet.process();
})