
/**
 * function to search a string and return first link found
 * @param str
 * @return string
 */
function searchTextForLink(str) {
    pattern = /(^|[\s\n]|<br\/?>)((?:https?|ftp):\/\/[\-A-Z0-9+\u0026\u2019@#\/%?=()~_|!:,.;]*[\-A-Z0-9+\u0026@#\/%=~()_|])/gi;
    pattern = /(^|\s)((https?:\/\/|www\.)[\w-]+(\.[\w-]{2,})+\.?(:\d+)?(\/\S*)?)/gi;
    //pattern = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    pattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;

    //test for links without http|s://
    matches = pattern.exec(str);
    if (matches && matches[2]) return matches[2];
    return '';
}

function autoResizeTextarea()
{
    $('body').on('keyup', 'textarea', function(){
        var minH = $(this).data('height');
        if ($(this).data('off') != undefined) return false;
        if (minH != undefined) {
            $(this).height(minH);
        } else {
            $(this).height(0);
        }
        $(this).height(this.scrollHeight);
    });
}
function file_chooser(id) {
    var input = $(id);
    input.click();
    return false;
}
function toggleFadeCover(t) {

    if (t == 'remove') {
        $('.fade-cover').delay(350).fadeOut('slow');
    } else {
        $('.fade-cover').fadeIn();
    }
}



/**
     * Assign of functions that need to be call after ajax process
     */
    function reloadPlugins(update) {

        //assign
        $('.post-time span').timeago();

        /**$('.post-textarea').autoresize({

        });**/
        element = $('.editor');
        $('.editor').wysiwyg({
            classes: 'some-more-classes',
            // 'selection'|'top'|'top-selection'|'bottom'|'bottom-selection'
            toolbar:'top-selection',
            buttons: {
                // Smiley plugin
                smilies: {
                    title: 'Smilies',
                    image: '\uf118', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    popup: function( $popup, $button ) {
                        var list_smilies = emoticons;
                        var $smilies = $('<div/>').addClass('wysiwyg-toolbar-smilies')
                            .attr('unselectable','on');
                        $.each( list_smilies, function(index,smiley){
                            if( index != 0 )
                                $smilies.append(' ');
                            var $image = $(smiley).attr('unselectable','on');
                            // Append smiley
                            var imagehtml = ' '+$('<div/>').append($image.clone()).html()+' ';
                            $image
                                .css({ cursor: 'pointer' })
                                .click(function(event){
                                    $(element).wysiwyg('shell').insertHTML(imagehtml); // .closePopup(); - do not close the popup
                                })
                                .appendTo( $smilies );
                        });
                        var $container = $(element).wysiwyg('container');
                        $smilies.css({ maxWidth: parseInt($container.width()*0.95)+'px' });
                        $popup.append( $smilies );
                        // Smilies do not close on click, so force the popup-position to cover the toolbar
                        var $toolbar = $button.parents( '.wysiwyg-toolbar' );
                        if( ! $toolbar.length ) // selection toolbar?
                            return ;
                        return { // this prevents applying default position
                            left: parseInt( ($toolbar.outerWidth() - $popup.outerWidth()) / 2 ),
                            top: $toolbar.hasClass('wysiwyg-toolbar-bottom') ? ($container.outerHeight() - parseInt($button.outerHeight()/4)) : (parseInt($button.outerHeight()/4) - $popup.height())
                        };
                    },
                    //showstatic: true,    // wanted on the toolbar
                    showselection: true    // wanted on selection
                },
                insertimage: {
                    title: 'Insert image',
                    image: '\uf030', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    showstatic: true    // wanted on the toolbar
                    //showselection:  false    // wanted on selection
                },

                insertlink: {
                    title: 'Insert link',
                    image: '\uf08e' // <img src="path/to/image.png" width="16" height="16" alt="" />
                },
                insertvideo: {
                    title: 'Insert video',
                    image: '\uf03d', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: true    // wanted on selection
                },
                // Fontname plugin
                fontname: {
                    title: 'Font',
                    image: '\uf031', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    popup: function( $popup, $button ) {
                        var list_fontnames = {
                            // Name : Font
                            'Arial, Helvetica' : 'Arial,Helvetica',
                            'Verdana'          : 'Verdana,Geneva',
                            'Georgia'          : 'Georgia',
                            'Courier New'      : 'Courier New,Courier',
                            'Times New Roman'  : 'Times New Roman,Times'
                        };
                        var $list = $('<div/>').addClass('wysiwyg-toolbar-list')
                            .attr('unselectable','on');
                        $.each( list_fontnames, function( name, font ){
                            var $link = $('<a style="display: block"/>').attr('href','#')
                                .css( 'font-family', font )
                                .html( name )
                                .click(function(event){
                                    $(element).wysiwyg('shell').fontName(font).closePopup();
                                    // prevent link-href-#
                                    event.stopPropagation();
                                    event.preventDefault();
                                    return false;
                                });
                            $list.append( $link );
                        });
                        $popup.append( $list );
                    },
                    //showstatic: true,    // wanted on the toolbar
                    showselection: true   // wanted on selection
                },
                // Fontsize plugin
                fontsize: {
                    title: 'Size',
                    image: '\uf034', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    popup: function( $popup, $button ) {
                        // Hack: http://stackoverflow.com/questions/5868295/document-execcommand-fontsize-in-pixels/5870603#5870603
                        var list_fontsizes = [];
                        for( var i=8; i <= 11; ++i )
                            list_fontsizes.push(i+'px');
                        for( var i=12; i <= 28; i+=2 )
                            list_fontsizes.push(i+'px');
                        list_fontsizes.push('36px');
                        list_fontsizes.push('48px');
                        list_fontsizes.push('72px');
                        var $list = $('<div/>').addClass('wysiwyg-toolbar-list')
                            .attr('unselectable','on');
                        $.each( list_fontsizes, function( index, size ){
                            var $link = $('<a style="display: block"/>').attr('href','#')
                                .html( size )
                                .click(function(event){
                                    $(element).wysiwyg('shell').fontSize(7).closePopup();
                                    $(element).wysiwyg('container')
                                        .find('font[size=7]')
                                        .removeAttr("size")
                                        .css("font-size", size);
                                    // prevent link-href-#
                                    event.stopPropagation();
                                    event.preventDefault();
                                    return false;
                                });
                            $list.append( $link );
                        });
                        $popup.append( $list );
                    }
                    //showstatic: true,    // wanted on the toolbar
                    //showselection: true    // wanted on selection
                },
                // Header plugin
                header: {
                    title: 'Header',
                    image: '\uf1dc', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    popup: function( $popup, $button ) {
                        var list_headers = {
                            // Name : Font
                            'Header 1' : '<h1>',
                            'Header 2' : '<h2>',
                            'Header 3' : '<h3>',
                            'Header 4' : '<h4>',
                            'Header 5' : '<h5>',
                            'Header 6' : '<h6>',
                            'Code'     : '<pre>'
                        };
                        var $list = $('<div/>').addClass('wysiwyg-toolbar-list')
                            .attr('unselectable','on');
                        $.each( list_headers, function( name, format ){
                            var $link = $('<a style="display: block"/>').attr('href','#')
                                .css( 'font-family', format )
                                .html( name )
                                .click(function(event){
                                    $(element).wysiwyg('shell').format(format).closePopup();
                                    // prevent link-href-#
                                    event.stopPropagation();
                                    event.preventDefault();
                                    return false;
                                });
                            $list.append( $link );
                        });
                        $popup.append( $list );
                    }
                    //showstatic: true,    // wanted on the toolbar
                    //showselection: false    // wanted on selection
                },
                bold: {
                    title: 'Bold (Ctrl+B)',
                    image: '\uf032', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    hotkey: 'b'
                },
                italic: {
                    title: 'Italic (Ctrl+I)',
                    image: '\uf033', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    hotkey: 'i'
                },
                underline: {
                    title: 'Underline (Ctrl+U)',
                    image: '\uf0cd', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    hotkey: 'u'
                },
                strikethrough: {
                    title: 'Strikethrough (Ctrl+S)',
                    image: '\uf0cc', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    hotkey: 's'
                },
                forecolor: {
                    title: 'Text color',
                    image: '\uf1fc' // <img src="path/to/image.png" width="16" height="16" alt="" />
                },
                highlight: {
                    title: 'Background color',
                    image: '\uf043' // <img src="path/to/image.png" width="16" height="16" alt="" />
                },
                alignleft:  {
                    title: 'Left',
                    image: '\uf036', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                aligncenter:  {
                    title: 'Center',
                    image: '\uf037', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                alignright: {
                    title: 'Right',
                    image: '\uf038', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                alignjustify:  {
                    title: 'Justify',
                    image: '\uf039', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                subscript:  {
                    title: 'Subscript',
                    image: '\uf12c', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: true    // wanted on selection
                },
                superscript: {
                    title: 'Superscript',
                    image: '\uf12b', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: true    // wanted on selection
                },
                indent:{
                    title: 'Indent',
                    image: '\uf03c', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                outdent:{
                    title: 'Outdent',
                    image: '\uf03b', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                orderedList: {
                    title: 'Ordered list',
                    image: '\uf0cb', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                unorderedList: {
                    title: 'Unordered list',
                    image: '\uf0ca', // <img src="path/to/image.png" width="16" height="16" alt="" />
                    //showstatic: true,    // wanted on the toolbar
                    showselection: false    // wanted on selection
                },
                removeformat: {
                    title: 'Remove format',
                    image: '\uf12d' // <img src="path/to/image.png" width="16" height="16" alt="" />
                }
            },
            // Submit-Button
            submit: {
                title: 'Submit',
                image: '\uf00c' // <img src="path/to/image.png" width="16" height="16" alt="" />
            },
            // Other properties
            placeholderUrl: 'www.example.com',
            placeholderEmbed: '<embed/>',
            maxImageSize: [600,200],

            forceImageUpload: false,    // upload images even if File-API is present
            videoFromUrl: function( url ) {
                // Contributions are welcome :-)

                // youtube - http://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id
                var youtube_match = url.match( /^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?(?:youtu\.be|youtube\.com)\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/)([^\?&\"'>]+)/ );
                if( youtube_match && youtube_match[1].length == 11 )
                    return '<iframe src="//www.youtube.com/embed/' + youtube_match[1] + '" width="640" height="360" frameborder="0" allowfullscreen></iframe>';

                // vimeo - http://embedresponsively.com/
                var vimeo_match = url.match( /^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?vimeo\.com\/([0-9]+)$/ );
                if( vimeo_match )
                    return '<iframe src="//player.vimeo.com/video/' + vimeo_match[1] + '" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

                // dailymotion - http://embedresponsively.com/
                var dailymotion_match = url.match( /^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?dailymotion\.com\/video\/([0-9a-z]+)$/ );
                if( dailymotion_match )
                    return '<iframe src="//www.dailymotion.com/embed/video/' + dailymotion_match[1] + '" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

                // undefined -> create '<video/>' tag
            },
            onKeyPress: function( code, character, shiftKey, altKey, ctrlKey, metaKey ) {
                // E.g.: submit form on enter-key:
                //if( (code == 10 || code == 13) && !shiftKey && !altKey && !ctrlKey && !metaKey ) {
                //    submit_form();
                //    return false; // swallow enter
                //}
            }
        })





        //paginating posts
        $(window).scroll(function() {
            if (document.body.scrollHeight ==
                document.body.scrollTop +
                    window.innerHeight ) {
                if ($('#post-list').length) {

                    paginatePost($('#post-list'));
                };
            }
        });

        if (!update) {
            /**Emoticons process**/

            $('.emoticon-selector').each(function() {

                $(this).qtip({
                    show : {
                        event : 'click'
                    },
                    content : {
                        text : $(this).next().html()
                    },
                    hide : {
                        fixed : true,
                        delay : 500
                    },
                    style : {
                        classes : 'qtip-light emoticons-container'
                    },
                    position : {
                        at : 'bottom center',
                        my : 'top center'
                    }
                })
            });

            /**User popover***/
            $('.user-popover').each(function() {
                var o = $(this);
                $(this).qtip({
                    content : {
                        text : function(e, api) {
                            $.ajax({
                                url : o.data('url'),
                                cache : false
                            })
                                .then(function(content) {
                                    api.set('content.text', content)
                                }, function(xhr, status, error) {
                                    api.set('content.text', status + ': ' + error);
                                })
                            return 'Loading...';
                        }
                    },
                    style : {
                        classes : 'qtip-light user-popover'
                    },
                    hide : {
                        fixed : true
                    }
                })
            });


        }

        //it help initiate back post pagination
        window.stopPaginate = false;



        /**qtip titles**/
        $("[title!='']").qtip({
            position : {
                my : 'top center',
                at : 'bottom center'
            },
            style : {
                classes : 'qtip-dark'
            }
        });



        //post images
        $('.preview-image').colorbox({
            scrolling : false,
            top : '10px',
            width : '96%',
            initialWidth : '96%',
            initialHeight : '96%',
            scalePhotos : false,
            maxHeight : '96%',
            height : '96%',
            fixed : true,
            className : 'photo-viewer',
            onOpen : function() {
                $('#append-content').html('');
                $('body').css('overflow', 'hidden');
            },
            onClosed : function() {
                $('body').css('overflow', 'auto');
            },
            onComplete :  function() {
                //$('#cboxContent').css('height', 'auto')

                var img = $('#cboxLoadedContent').find('img').attr('src');
                $.ajax({
                    url : baseUrl + 'photo/details',
                    data : {photo : img},
                    cache : false,
                    success : function(data) {
                        var c = $('<div id="append-content"></div>');
                        $('#cboxContent').append(c).css('height', 'auto');

                        c.html(data);
                        reloadPlugins();
                    }
                })
            }
        });

        //Load post medias for optimize page load
        $('.load-sound').each(function() {
            var loaded = $(this).data('loaded')

            if (loaded == undefined) {
                $(this).data('loaded', true);
               var c = '<iframe style="max-width:100%;" width="100%" height="120" scrolling="no" frameborder="no" src="'+$(this).data('url')+'"></iframe>';
                $(this).html(c);
           }
        });

        $('.load-video').each(function() {
            var loaded = $(this).data('loaded')

            if (loaded == undefined) {
                $(this).data('loaded', true);
                var c = '<iframe style="width: 100%;max-width:100%;height: 320px" allowfullscreen frameborder="no" src="'+$(this).data('url')+'"></iframe>'
                $(this).html(c);
            }
        });
        
        


        
    }///end of reloadPlugins

    /**paginating post***/
    window.stopPaginate = false;
    function paginatePost(o) {

        if (window.stopPaginate) return false;
        window.stopPaginate = true;
        var type = o.data('type');
        var offset = o.data('offset');
        var userid = o.data('userid');

        $.ajax({
            url : baseUrl + 'post/paginate',
            data : {type : type, offset: offset, userid : userid},
            cache : false,
            success : function(result) {
                result = jQuery.parseJSON(result);
                o.append(result.posts).data('offset', result.offset);

                if (result.posts != '') {
                    window.stopPaginate = false;
                    reloadPlugins();
                    nailImages();
                }
            }
        })
    }

    
    function nailImages()
    {
        jQuery('.post-thumb-image').nailthumb({fitDirection:'top left'});
        //jQuery('.post-thumb-one-image').nailthumb({ proportions : 1 });
    }

function reposition_cover() {
    var rWrapper = $('.profile-resize-cover');
    var oWrapper = $('.original-profile-cover');
    if (oWrapper.find('img').attr('src') == '') return false;
    rWrapper.hide();
    oWrapper.show();
    $(".profile-cover-changer").hide();
    $('.profile-cover-reposition-button').show();
    oWrapper.find('img').draggable({
        scroll: false,
        axis: "y",
        cursor: "s-resize",
        drag: function (event, ui) {
            y1 = $('#profile-header').height();
            y2 = oWrapper.find('img').height();

            if (ui.position.top >= 0) {
                ui.position.top = 0;
            }
            else
            if (ui.position.top <= (y1-y2)) {
                ui.position.top = y1-y2;
            }
        },

        stop: function(event, ui) {
            //alert(ui.position.top);
            $('#profile-cover-resized-top').val(ui.position.top);
        }
    });
    return false;
}

function cancelReposition() {
    var rWrapper = $('.profile-resize-cover');
    var oWrapper = $('.original-profile-cover');
    oWrapper.hide();
    rWrapper.show();
    $(".profile-cover-changer").show();
    $('.profile-cover-reposition-button').hide();
    return false;
}

function saveProfileCover(l){
    var top = $('#profile-cover-resized-top').val();
    var indicator = $(".profile-header .profile-cover-indicator");
    if (top == 0) return cancelReposition();

    indicator.fadeIn().find('span').hide();
    $.ajax({
        url : baseUrl + l +'?top=' + top,
        success : function(data) {
            indicator.hide();
            var json = jQuery.parseJSON(data);
            $('.profile-resize-cover img').attr('src', json.url);
            cancelReposition();
        }
    })
    return false;
}

function remove_user_cover(img, message) {
    bootbox.confirm(message, function(r) {
        if (r) {
            $('.profile-resize-cover img').attr('src', img);
            $('.original-profile-cover img').attr('src', '');
            $.ajax({
                url : baseUrl +'profile/remove/cover'
            });
        }
    })
    return false;
}

function remove_page_cover(id,img, message) {
    bootbox.confirm(message, function(r) {
        if (r) {
            $('.profile-resize-cover img').attr('src', img);
            $('.original-profile-cover img').attr('src', '');
            $.ajax({
                url : baseUrl +'page/remove/cover?id=' + id
            });
        }
    })
    return false;
}

$(function() {
    autoResizeTextarea();
    toggleFadeCover('remove');
    bootbox.setDefaults({
        closeButton : false,
        className: "alert-modal"
    });

    $(document).on('click', '.confirm-delete', function() {
        var message = "Are you sure you want to do this?";
        var dMessage = $(this).data('message');
        message = (dMessage != undefined) ? dMessage : message;
        var o = $(this);
        bootbox.confirm(message, function(result) {
            if (result) window.location = o.attr('href');
        });
        return false;
    });

    nailImages();
    /*$('#sidebar-menu').slimScroll({
        height : '500px'
    });*/

    /**var menu = $.offCanvasMenu({
        direaction : 'left',
        coverage : '250px',
        trigger : '#sidebar-trigger',
        menu : '#sidebar',
        duration : 250
    });



    menu.on();**/



    $('#sidebar').mmenu({

    });
    $('#sidebar-trigger').click(function() {
        $('#sidebar').trigger('open.mm');
    });

    $('#sidebar a').click(function() {
        $('#sidebar').trigger('close.mm');
    })

    //function to text if browser support FormData
    function supportFormData()
    {
        return !! window.FormData;
    }

    


    function scrollMessageBox()
    {
        //scroll message-list-container to bottom on page load or ajax load
        $(".message-list-container").animate({ scrollTop: $('.message-list-container').prop('scrollHeight')}, 1000);

    }
    
    scrollMessageBox();


    function coverPluginLoading()
    {
        $(document).on('change', '.user-profile-cover-chooser', function() {
            var form = $("#profile-cover-form");
            var indicator = $(".profile-header .profile-cover-indicator");
            indicator.fadeIn();
            form.ajaxSubmit({
                url :baseUrl + 'profile/upload/cover',
                uploadProgress : function(event, position, total, percent) {

                    var uI = indicator.find('span')
                    uI.html(percent + '%').fadeIn();
                    if (percent == 100) {
                        uI.fadeOut().html("0%")
                    }
                },
                success : function(data) {
                    var json = jQuery.parseJSON(data);
                    indicator.hide();
                    if (json.status == 'error') {
                        bootbox.alert(json.message);
                    } else {
                        $('.profile-resize-cover img').attr('src', json.url);
                        $('.original-profile-cover img').attr('src', json.url);
                        reposition_cover();
                    }
                }
            })
        });

        $(document).on('change', '.page-profile-cover-chooser', function() {
            var form = $("#profile-cover-form");
            var indicator = $(".profile-header .profile-cover-indicator");
            indicator.fadeIn();
            form.ajaxSubmit({
                url :baseUrl + 'page/upload/cover?id=' + $(this).data('id'),
                uploadProgress : function(event, position, total, percent) {

                    var uI = indicator.find('span')
                    uI.html(percent + '%').fadeIn();
                    if (percent == 100) {
                        uI.fadeOut().html("0%")
                    }
                },
                success : function(data) {
                    var json = jQuery.parseJSON(data);
                    indicator.hide();
                    if (json.status == 'error') {
                        bootbox.alert(json.message);
                    } else {
                        $('.profile-resize-cover img').attr('src', json.url);
                        $('.original-profile-cover img').attr('src', json.url);
                        reposition_cover();
                    }
                }
            })
        });

        //community logo upload
        prepareCommunityLogoUpload();

        //pages cover upload
        //preparePageCover();
    }

    coverPluginLoading();

    /**
     * General way for action to required login
     */
    function actionRequiredLoggedIn()
    {
        return alert('You must be logged in to like or comment');
    }

    reloadPlugins();

    $(document).on('keyup','.slug-input', function() {
        var target = $($(this).data('target'));
        target.html($(this).val());
    });

    /**
     * Tagging
     */

    function insertTag(tags, input, userid, name, sc, sl)
    {
        if (tags.find("#tag-" + userid).length) return false;
        var div = $("<span id='tag-"+userid+"' class='tag'><input name='val[tags][]' type='hidden' value='"+userid+"'/>"+name+" <a href=''><i class='icon ion-close'></i></a> </span> ");
        div.insertBefore(input);

        //attach event to this by the user
        div.find('a').click(function() {
            div.remove();
            return false;
        });
        input.val('').focus();
        sc.slideUp('slow');
        sl.html('');
    }

    function resetTags()
    {
        var c = $('.people-tagging');
        c.find('.tag').remove();
        c.hide();
    }

    function enableTagging(c)
    {
        var tags = c.find('.tags');
        var tagForm = tags.find('form');
        var tagInput = tags.find('input[type=text]');
        var suggestionListContainer = tags.next();
        var suggestionIndicator = tags.find('.indicator');

        /**
         * if user what to tag non existing user this take of it
         */
        suggestionListContainer.find('.suggestion-info a').unbind('click').click(function() {
            insertTag(tags,tagInput, tagInput.val(), tagInput.val(), suggestionListContainer, suggestionListContainer.find('.suggestion-list'));
            return false;
        });

        /**on keyup make suggestions***/
        window.suggestionIsOn = false;
        tagInput.keyup(function() {
            var text = $(this).val();
            if (text.length < 3) suggestionListContainer.slideUp();
            if (window.suggestionIsOn || text.length < 3) {

                return false;
            }

            window.suggestionIsOn = true;


            suggestionListContainer.show();
            suggestionIndicator.show();
            //send to server to get people suggestions
            $.ajax({
                url : baseUrl + 'user/tag/suggestion',
                data : {text : text},
                cache : false,
                success : function(r) {
                    var sc = suggestionListContainer.find('.suggestion-list');
                    sc.html(r);
                    window.suggestionIsOn = false;
                    suggestionIndicator.hide();

                    sc.find('a').click(function() {
                        var userid = $(this).data('userid');
                        var name = $(this).data('name');
                        insertTag(tags,tagInput, userid, name, suggestionListContainer, sc);
                        return false;
                    })

                }
            });
        });
    }

    /***Login form processing*****/
    $(document).on('submit', '#login-form', function() {
        Pace.restart();
        var loginForm = $(this);
        var mc = loginForm.find('.message');
        mc.fadeOut();
        $(this).ajaxSubmit({
            type : 'POST',
            url  : baseUrl + 'login',
            cache : false,
            success : function(data) {
                data = jQuery.parseJSON(data);

                if (data.response == 1) {
                    window.location = data.url;
                } else {
                    Pace.stop();
                    mc.html(data.message).fadeIn();
                }
            }
        });

        return false;
    });
    /**End of login process***/

    /**Signup form process***/
    window.signupOnGoing = false;
    $(document).on('submit', '#signup-form', function() {
        if (window.signupOnGoing) return false;
        window.signupOnGoing = true;
        Pace.restart();
        var signupForm = $(this);
        var mc = signupForm.find('.message');
        mc.fadeOut();

        $(this).ajaxSubmit({
            type : 'POST',
            url : baseUrl + 'signup',
            cache : false,
            success : function(data) {
                window.signupOnGoing = false;
                data = jQuery.parseJSON(data);
                if (data.response == 1) {
                    window.location = data.url;
                } else {
                    Pace.stop();
                    mc.html(data.message).fadeIn();
                }
            },
            error : function() {
                window.signupOnGoing = false;
            }
        })
        return false;
    });
    /**End of Signup form***/

    /**Getstarted ***/
    function changePhoto(form, input) {
        var img = form.find('.media-object img');

        img.css('opacity', '0.6');
        form.ajaxSubmit({
            type : 'POST',
            url : baseUrl + 'getstarted/save/photo',
            success : function(data) {
                data = jQuery.parseJSON(data);
                if (data.code == 1) {
                    img.attr('src', data.url);
                } else {
                    alert(data.message);
                }
                input.val('');
                img.css('opacity', '1');
            }
        });
    }
    $(document).on('change', '#getstarted-image-input', function() {
        var form = $("#getstarted-form");
        var input = $(this);
        changePhoto(form, input);
    });

    $(document).on('submit', '#getstarted-form', function(e) {
        
        
        var form = $("#getstarted-form");
        var left = $('.getstarted-left');
        var right = $('.getstarted-right');

        left.css('opacity', '0.4');
        right.css('opacity', 1);
        var c = right.find('.member-container');
        c.css('background', 'white');
        $('#gestarted-continue-button').removeAttr('disabled');
        if (right.css('display') == 'none') {
            window.location = baseUrl + 'getstarted/finish';
            return false;
        }

        $.ajax({
            url : baseUrl + 'getstarted/members',
            cache : false,
            success : function(data) {
                c.html(data);
            }
        })
        
        
        e.preventDefault();
        if (supportFormData()) {
            $.ajax({
                url : baseUrl + 'getstarted/save/info',
                type : 'POST',
                data : new FormData(this),
                cache : false,
                contentType : false,
                processData : false
            });
        } else {
            
            form.ajaxSubmit({
            url : baseUrl + 'getstarted/save/info',
            type : 'POST'                                   
        });
        }
    });
    /**End of getstarted process***/

    /**AJaxify content***/

    if (doAjaxify) {

        function ajaxify(url, force) {
            window.onpopstate = function(e) {
                url = window.location;

                ajaxify(url, true);
            }
            if (url == window.location && force == undefined) return false;
            Pace.restart();

            $('.header-dropdown-box').fadeOut();
            Pagelet.reset();
            $.ajax({
                url : url,
                cache : false,
                //async : false,
                dataType : 'json',
                type : 'GET',
                success : function(data) {
                    //toggleFadeCover('add');
                    //data = jQuery.parseJSON(data);
                    var content = data.content;

                    if (content == 'login') {
                        window.location = baseUrl;
                        return false;
                    }
                    var container = data.container;
                    var title = data.title;


                    $(container).html(content);

                    if (data.design) {
                        design = jQuery.parseJSON(data.design);

                        changePageDesign(design.bg_image, design.bg_color, design.bg_position, design.bg_attachment, design.bg_repeat, design.link_color, design.content_bg_color );
                    }


                    document.title = title;
                    //window.history.pushState(null, null,  url);
                    //url = url.replace(baseUrl, '');

                    window.history.pushState({},'New URL:' + url, url);
                    //start paglets loading
                    Pagelet.process();
                    $('body').click().animate({scrollTop : 0}, 200);
                    Pace.stop();

                    reloadPlugins();
                    nailImages();
                    //help reload cover photo loading
                    coverPluginLoading();
                    
                    scrollMessageBox();
                    toggleFadeCover('remove');


                }
            });
        }



        $(document).on('click', 'a[data-ajaxify=true]', function() {
            var obj = $(this);
            var url = obj.attr('href');
            ajaxify(url);
            return false;
        });

        $(document).on('submit', '#search-box', function() {
           var term = $(this).find('input[type=text]').val();
            if (term != '') {
                url = $(this).attr('action')+ '?term='+ term;
                $(this).find('.dropdown').slideUp();
                ajaxify(url);
            }
            return false;
        });
    }

    /**END OF AJAXIFY**/
    $(document).on('click', '#content-container', function() {
       $('.header-dropdown-box').hide();
    });

    /**Search box dropdown**/
    $(document).on('keyup', '#search-box input[type=text]', function() {
        var term = $(this).val();
        var container = $('#search-box');
        var dropdown = container.find('.dropdown');
        if (term.length > 1) {
            if (dropdown.css('display') == 'none') $('.header-dropdown-box').hide(); //hide others
            dropdown.slideDown('fast');
            dropdown.find('.indicator').fadeIn();
            container.ajaxSubmit({
                url : baseUrl + 'search/dropdown',
                type : 'GET',
                success : function(data) {
                    dropdown.find('.dropdown-content').html(data);
                    dropdown.find('.indicator').fadeOut();
                }
            })
        } else {
            dropdown.slideUp('fast');
        }

        dropdown.find('.footer-link').unbind().click(function() {
           dropdown.slideUp('fast');
           container.submit();
            return false;
        });

        dropdown.find('.close-button').unbind().click(function() {
            dropdown.slideUp('fast');
            return false;
        });
        return false;
    });


    /**POST**/

    window.postingStatus = false;
    function showPostIndicator()
    {
        $('.post-editor-indicator').fadeIn();
    }

    function hidePostIndicator()
    {
        $('.post-editor-indicator').fadeOut();
    }
    function postingStatus(indicator) {
        window.postingStatus = true;
        if (!indicator) showPostIndicator();
        $("#post-submit-button").attr("disabled", "disabled");
        $("#post-editor-privacy-selector").attr("disabled", "disabled");
    }
    function isPosting() {
        return window.postingStatus;
    }
    function removePostingStatus(temp)
    {
        window.postingStatus = false;
        hidePostIndicator();
        $("#post-submit-button").removeAttr("disabled");
        $("#post-editor-privacy-selector").removeAttr("disabled");
        if (!temp) {
            /**
             * take time to clean editor up
             */
            $('#post-type-content').hide().find('input[type=text]').val('');
            $('#post-type-content').find('.image').remove();
            $('#content_type').val('text');
            $('.post-textarea').val('').css('height', '50px');
            $('#post-image-input').val('');
            $('#post-editor-video-upload').val('');
            resetTags();
            hidePostFile();
        }

    }

    function postHasUpload() {
        var img = $('#post-image-input').val();
        var video = $('#post-editor-video-upload').val();
        var file = $("#post-editor-file-upload").val();
        if (img != '' || video != '' || file != '') return true;
        return false;
    }

    function disablePost()
    {
        $("#post-submit-button").attr('disabled', 'disabled');
    }

    function enablePost()
    {
        $("#post-submit-button").removeAttr('disabled');
    }

    function showPostFile()
    {
        $('.add-file-container').fadeIn();
    }

    function hidePostFile()
    {
        $('.add-file-container').fadeOut();
        $('.add-file-container').find('input[type=file]').val('');
    }
    $(document).on('click', '.post-add-file-trigger', function() {

        var c = $('.add-file-container');
        if (c.css('display') == 'none') {
            c.fadeIn();
        } else {
            c.fadeOut();
        }
        return false;
    });

    window.currentPostEditorLink = '';

    function clearLinkPreview(clearCurrent) {
        if (clearCurrent != undefined) window.currentPostEditorLink = '';
        var linkContainer = $('.post-editor-link-container');
        var previewContainer = linkContainer.find('.link-preview-container');
        var titleInput = linkContainer.find('.link_title');
        var descriptionInput = linkContainer.find('.link_description');
        var imageInput = linkContainer.find('.link_image')
        var theLink = linkContainer.find('.the_link');

        linkContainer.hide();
        previewContainer.html('');
        titleInput.val('');
        descriptionInput.val('');
        imageInput.val('');
        theLink.val('');
        linkContainer.find('.link-preview-indicator').hide();
    }

    function processLinkLoader(str) {
        var spl = str.split(" ");
        var linkContainer = $('.post-editor-link-container');
        var indicator = linkContainer.find('.link-preview-indicator');
        var previewContainer = linkContainer.find('.link-preview-container');
        if (spl.length > 0) {
            var foundlink = searchTextForLink(str);
            if (foundlink != '' && foundlink != window.currentPostEditorLink) {
                window.currentPostEditorLink = foundlink;
                //linkContainer.show();
                showPostIndicator();
                $.ajax({
                    url : baseUrl + 'post/link-preview',
                    type : 'POST',
                    cache : false,
                    data : {link : foundlink},
                    success : function(data) {
                        var data = jQuery.parseJSON(data);
                        var title = data.title;
                        var description = data.description;
                        var images = data.image;
                        var titleInput = linkContainer.find('.link_title');
                        var descriptionInput = linkContainer.find('.link_description');
                        var imageInput = linkContainer.find('.link_image')
                        var theLink = linkContainer.find('.the_link');

                        hidePostIndicator();
                        if (title == '') {
                            clearLinkPreview();

                            return false; //we can't go on from here
                        }
                        previewContainer.html(data.preview);
                        linkContainer.show();
                        //let set the title and description
                        titleInput.val(title);
                        descriptionInput.val(description);
                        theLink.val(data.link);

                        if (images.length > 0) {
                            mainPreviewImage = previewContainer.find('.main-preview-image');
                            imageInput.val(images[0]); //set the first image as selected
                            window.currentPostLinkImage = 0;
                            //attached event to preview image navigation
                            previewContainer.find('.navigate-left').click(function() {
                                if (window.currentPostLinkImage != 0) {
                                    var n = window.currentPostLinkImage - 1;

                                    window.currentPostLinkImage = n;
                                    var cImage = images[n];
                                    imageInput.val(cImage);
                                    mainPreviewImage.attr('src', cImage)
                                }
                                return false;
                            });

                            previewContainer.find('.navigate-right').click(function() {
                                if (images.length - 1 != window.currentPostLinkImage) {
                                    var n = window.currentPostLinkImage + 1;

                                    window.currentPostLinkImage = n;
                                    var cImage = images[n];
                                    imageInput.val(cImage);
                                    mainPreviewImage.attr('src', cImage)
                                }
                                return false;
                            });

                        }
                        previewContainer.find('.delete-link-preview').click(function() {

                            linkContainer.hide();
                            previewContainer.html('');

                            titleInput.val('');
                            descriptionInput.val('');
                            imageInput.val('');

                            processLinkLoader($('.post-textarea').val() + ' ');
                            $('.post-textarea').val($('.post-textarea').val()).focus();
                            return false;
                        });



                        indicator.hide();

                    }
                })
            }
        }

    }

    $(document).on('focus', '.post-textarea', function() {
        $('.editor-footer').fadeIn('slow');
    });

    $(document).on('keyup', '.post-textarea', function() {
        if ($('.post-editor-link-container').css('display') == 'none') {
            processLinkLoader($(this).val());
        }
    });

    /***Emoticons***/
    $(document).on('click', '.each-emoticon-selector', function() {
        var code = $(this).data('code')
        var target = $($(this).data('target'));
        var val = target.val() + ' ' + code + ' ';
        target.select().val(val).focus();
        //$('body').click();//auto hide this emoticon dropdown
        return false;
    })

    window.mentionSuggestionIsOn = false;
    $(document).on('keyup', '.mention', function(e) {
        var str = $(this).val();
        var aStr = str.split(' ');
        var o = $(this);
        var container = $($(this).data('target'));
        var lContainer = container.find('.listing');


        if (aStr.length > 0) {
            var lStr = aStr[aStr.length - 1];
            var nStr = lStr.split('');
            if (lStr.length > 2 && nStr.length > 0) {
                var char = nStr[0];

                if (char.toLowerCase() == '@' || char.toLowerCase() == '#') {
                    container.fadeIn();
                    lContainer.fadeIn();
                    $.ajax({
                        url : baseUrl + 'user/tag/' + ((char == '@') ? 'username' : 'hashtag'),
                        data : {text : lStr.replace('@', '')},
                        cache : false,
                        success : function(r) {
                            window.mentionSuggestionIsOn = false;
                            if (r != '') {

                                lContainer.html(r).find('a').click(function() {
                                    var tagName = $(this).data('tag');
                                    aStr[aStr.length - 1] = tagName;

                                    var s = '';

                                    for(i = 0; i < aStr.length; i++) {
                                        s += aStr[i] + ' ';
                                    }

                                    o.select().val(s + ' ');

                                    lContainer.hide('fast', function() {
                                        //o.focus()
                                    });
                                    return false;
                                });
                            }
                        }
                    });
                } else {
                    lContainer.html('').hide();
                }
            } else {
                lContainer.html('').hide();
            }
        }

    });

    //tagging people in posts
    $(document).on('click', '#editor .add-people', function() {
        var c = $('#editor .post-with-friend');
        if (c.css('display') == 'none') {
            c.fadeIn().find('.tags input[type=text]').focus();

            enableTagging(c);
        } else {
            c.fadeOut();
        }
        return false;
    });

    //switch header nav
    $(document).on('click', '.editor-header .nav a', function(e) {

        if (!$(this).hasClass("photo")) {
            $('.editor-header .nav a').removeClass('current'); //remove current from all
            $(this).addClass('current');

        }
        if (!$(this).hasClass('video')) {
            $('#post-type-content').hide();
            $('#post-editor-video-upload-container').hide();

        } else  {
            $('#post-editor-video-upload-container').show();
        }
        if ($(this).hasClass('status')) return false;


    });

    //share post
    $(document).on('click', '.post-share-button', function() {
        var o = $(this);
        var id = o.data('id');


        if (!o.data('is-login')) {

            actionRequiredLoggedIn();
            return false;
        }

        bootbox.confirm('Are you sure you want to share this', function(result) {
            if (result) {
                $.ajax({
                    url : baseUrl + 'post/share/' + id,
                    cache : false,
                    success : function(data) {
                        data = jQuery.parseJSON(data);
                        if(data.code == 1) {
                            $('.post-share-count-' + id).html(data.count);
                        } else {
                            bootbox.alert(data.message)
                        }
                    }
                })
            }
        })
        return false;
    });

    //delete post
    $(document).on('click', '.delete-post', function() {

        var id = $(this).data('id');
        var o = $('#post-' + id);

        bootbox.confirm($(this).data('message'), function(r) {
            if (r) {
                o.fadeOut('fast');
                o.remove();
                $.ajax({
                    url : baseUrl + 'post/delete/'+id
                });
            }
        })
        return false;
    });

    //hide post
    $(document).on('click', '.hide-post', function() {
        var id = $(this).data('id');
        var o  = $('#post-' + id);
        var content = o.html();

        o.html('<div style="padding: 10px">Hiding post....</div>');
        $.ajax({
            url : baseUrl + 'post/hide/' + id,
            cache : false,
            success : function(data) {
                o.html(data);
                o.find('.undo').click(function() {
                    o.html(content);
                    $.ajax({
                        url : baseUrl + 'post/unhide/' + id
                    });
                    return false;
                });
                o.find('.delete').click(function() {
                    o.remove();
                    return false;
                });
            }
        })
        return false;
    });

    var postSelectedImage = 0;
    function postEditorPrepareImage(input) {

        $('#content_type').val('image');


        image = document.getElementById("post-image-input");
        $('#post-type-content').find('input[type=text]').hide();
        $('.post-media-suggestion').hide();
        var containerDiv = $('#post-type-content');
        var imagesContainer = containerDiv.find('.images-container');
        imagesContainer.html('');



        if (image.files) {
            $('.editor-header .nav a').removeClass('current'); //remove current from all
            $("#post-editor-photos-selector").addClass("current");
            if (image.files.length > maxPostImage) {
                bootbox.alert('Allowed only ' + maxPostImage + ' photos at once');

                return false;
            }

            containerDiv.show();
            for(i = 0; i < image.files.length; i++) {
                if (typeof FileReader != "undefined") {


                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var div = $("<div class='image'></div>");
                        var img = $("<img/>");

                        img.attr('src', e.target.result);
                        div.append(img);
                        imagesContainer.append(div);
                    }
                    reader.readAsDataURL(image.files[i]);
                }
            }
        } else {

        }



    }

    function showPostError()
    {
        $('#post-error').fadeIn();
        setTimeout(function() {
            $('#post-error').slideUp();
        }, 5000)
    }

    $(document).on('submit', '#post-form', function (e) {

        if (isPosting()) return false;
        if (postHasUpload()) {
            postingStatus(true);
        } else {
            postingStatus();
        }
        e.preventDefault();
        $(this).ajaxSubmit({
            url : baseUrl + 'post/add',
            type : 'POST',
            success : function(data) {
                removePostingStatus();
                if (data != 0) {
                    var div = $('<div></div>');
                    div.html(data).hide();
                    $('#post-list').prepend(div)
                    $('#post-list').data('lastcheck', '');
                    div.fadeIn();
                    removePostingStatus();
                    reloadPlugins();
                    //clearInterval(window.usercheck);
                } else {

                    hidePostIndicator();
                    showPostError();
                }

            },
            error : function() {
                removePostingStatus(true);
            },
            uploadProgress : function(event, position, total, percent) {
                if (postHasUpload()) {
                    var uI = $("#post-editor-uploading-indicator");
                    uI.show();
                    uI.find('span').html(percent + "%");
                    if (percent == 100) {
                        uI.hide();
                        uI.find('span').html("0%");
                        showPostIndicator();
                    }
                }
            }
        });
        
    });
    $(document).on('click', '.content-type-toggle', function() {
        var type = $(this).data('type');
        var placeholder = $(this).data('placeholder');
        var container = $('#post-type-content');
        $('.post-media-suggestion').hide();
        $('.post-media-suggestion').find('.listing').html('').hide();

        if (type != 'video') {
            $('#post-editor-video-upload-container').hide();
            $('.editor-header .nav a').removeClass('current'); //remove current from all
            $('.editor-header .nav .status').addClass("current");
        }

        container.find('.image').remove();
        postSelectedImage = 0;
        container.fadeIn('slow').find('input[type=text]').val('').attr('placeholder', placeholder).fadeIn('slow').focus();
        $('#content_type').val(type);

        return false;
    });
    $(document).on('keyup', '#post-type-content input[type=text]', function() {
        var type = $('#content_type').val();

        if (type == 'link') {
            processLinkLoader($(this).val());
        }
    });
    $(document).on('keyup', '#post-type-content input[type=text]', function() {
        type = $('#content_type').val();
        
        
        if (type == 'audio' || type == 'video') {
            var container = $('.post-media-suggestion')
            var indicator = container.find('.search-indicator');
            var listing = container.find('.listing');
            listing.html('');

            //indicator.fadeIn();
            showPostIndicator();
            $.ajax({
                url : baseUrl + 'post/search/media',
                cache : false,
                data:{type: type, v : $(this).val()},
                success : function(data) {
                    hidePostIndicator();
                    if (!listing.find('.selected').length && data != '') {
                        listing.html(data);
                        container.show();
                        listing.show();
                    } else {
                        container.hide();
                        listing.hide();
                    }
                }
            });
        }
    });
    
    $(document).on('click', '.post-media-suggestion .listing a', function() {
        var l = $(this).attr('href');
        var image = $(this).data('image');
        var title = $(this).data('title');
        var div = $("<div class='media selected'><div class='media-object pull-left'><img src='"+image+"' width='30' height='30'/></div><div class='media-body'><h3 class='media-heading'>"+title+"</h3><a class='pull-right close' href=''><i class='icon ion-close'></i></a></div> </div>")
        $('.post-media-suggestion').find('.listing').html(div);

        div.find('.close').click(function() {
            div.remove();
            $('#post-type-content').find('input[type=text]').val('');
            $('.post-media-suggestion').hide();
            $('#post-type-content').hide();
            $('.post-media-suggestion').find('.listing').hide();
            return false;
        });
        $('#post-type-content').find('input[type=text]').val(l).hide();
        return false;
    })

    $(document).on('change', '#post-editor-video-upload', function() {
        $('#post-editor-video-upload-container').css('opacity', '0.5')
    });

    $(document).on('change', '#post-image-input', function() {
        postEditorPrepareImage($(this));
    });

    //inline post editoru
    $(document).on('click', '.edit-post-trigger', function() {
        var form = $('#post-inline-editor-' + $(this).data('id'));

        form.slideDown().find('textarea').focus();
        $('body').click();
        return false;
    });

    $(document).on('click', '.cancel-post-inline-editor', function() {
        var form = $('#post-inline-editor-' + $(this).data('id'));

        form.slideUp();
        return false;
    });

    $(document).on('click', '.save-post-inline-editor', function() {
        var container = $('#post-inline-editor-' + $(this).data('id'));
        var form = container.find('form');
        var text = form.find('textarea').val();
        var id = $(this).data('id');
        var textC = $('#post-text-content-' + id);
        var edited = $(".post-is-edited-" + id);
        textC.html(text);

        if($(this).data('text') != text) {
            edited.html($(this).data('edited'));
        }

        $.ajax({
            url : baseUrl + 'post/edit/'+id,
            data : {text: text},
            cache : false,
            type : 'POST',
            success : function(c) {
                if (c) textC.html(c);
            }
        })
        container.fadeOut();
        return false;
    });


    /**Process comments**/

    $(document).on('click', '.comment-edit-button', function() {
        var commentId = $(this).data('comment-id');
        var cTextContainer = $("#" +commentId + "-comment-text");
        var cForm = $("#comment-inline-editor-" + commentId);
        cTextContainer.hide();
        cForm.fadeIn().find('textarea').focus();
        return false;
    });

    $(document).on('click', '.cancel-inline-editor', function() {
        var commentId = $(this).data('id');
        var cTextContainer = $("#" +commentId + "-comment-text");
        var cForm = $("#comment-inline-editor-" + commentId);

        cForm.hide();
        cTextContainer.show();
        return false;
    });
    $(document).on('click', '.comment-edit-save-button', function() {

        var commentId = $(this).data('id');
        var $form = $("#comment-inline-editor-" + commentId);
        var cTextContainer = $("#" +commentId + "-comment-text");
        var oText = cTextContainer.html();
        var nText =  $form.find('textarea').val();

        if (nText == oText) return false;

        $form.hide();
        cTextContainer.html(nText).fadeIn();

        $.ajax({
            url : baseUrl + 'comment/edit/'+ commentId,
            data : {text: nText},
            cache : false,
            type : 'POST',
            success : function(c) {
                if (c) {
                    cTextContainer.html(c);
                } else {
                    cTextContainer.html(oText);
                }
            }
        });

        return false;
    });

    function updatePostCommentCount(type, id)
    {
        $.ajax({
            url: baseUrl + 'comment/count/'+type + '/' + id,
            success: function(c)
            {
                $('.post-reply-count-' + id).html(c);
                $('.photo-post-reply-count-' + id).html(c);
            }
        })
    }

    function toggleReplyForm(form,type) {
        var t = form.find('input[type=text]');
        var c = form.find('.real-comment-form')
        if (type) {
            //on the reply
            t.hide();
            c.fadeIn().find('textarea').focus();
        } else {
            c.hide();
            c.find('textarea').val('').css('height', '32px')
            t.fadeIn();
        }
    }
    $(document).on('focus', '.post-replies form input[type=text]', function() {
        var id = $(this).data('id');
        var type = $(this).data('type');

        var form = $('#reply-form-' + type + '-' + id);
        toggleReplyForm(form, true);
    });

    $(document).on('click', '.cancel-reply-form', function() {
        var id = $(this).data('id');
        var type = $(this).data('type');

        var form = $('#reply-form-' + type + '-' + id);
        toggleReplyForm(form, false);
        return false;
    });

    $(document).on('submit', '.post-replies form', function(e) {
        var form = $(this);
        var id = form.data('id');
        var type = form.data('type');
        var c = $('#'+type+'-'+ id + '-reply-lists');
        if (form.data('cloneid') != undefined) {
            var c2 = $('#'+form.data('cloneid')+ '-reply-lists');
        }
        var t = form.find('textarea');
        var f = form.find('input[type=file]');

        if (t.val() == '' && f.val() == '') {

            return false;
        }
        form.css('opacity', '0.6');

        e.preventDefault();
        if (supportFormData()) {
            $.ajax({
            url : baseUrl + 'comment/add',
            type : 'POST',
            data : new FormData(this),
            cache : false,
            contentType : false,
            processData : false,
            success : function(result) {
                c.append(result);
                if (c2) c2.append(result);
                toggleReplyForm(form, false);
                form.css('opacity', 1).find('input[type=text]').val('');
                form.find('textarea').val('').css('height', '32px');
                form.find('input[type=file]').val('');
                reloadPlugins();
                updatePostCommentCount(type,id);
            }
            });

        } else {
            form.ajaxSubmit({
            url : baseUrl + 'comment/add',
            type : 'POST',            
            success : function(result) {
                c.append(result);
                if (c2) c2.append(result);
                form.css('opacity', 1).find('input[type=text]').val('');
                form.find('input[type=file]').val('');
                reloadPlugins();
                updatePostCommentCount(type,id);
            }
            });

        }
        
    });
    $(document).on('click', '.reply .delete-button', function(){
        var o = $(this);

        var r = confirm($(this).data('warning'));

        if(r) {
            var reply = $("#reply-" + o.data('id'));
            var reply2 = $(".reply-" + o.data('id'));
            reply.fadeOut();
            reply2.fadeOut();
            $.ajax({url:baseUrl + 'comment/delete/' + o.data('id'), success:function() {
                updatePostCommentCount(o.data('type'), o.data('type-id'));
            }})

        }

        return false;
    });

    /**Loading more comments ***/
    function loadMoreComments(o, t)
    {
        var limit = o.data('limit');
        var type = o.data('type');
        var offset = o.data('offset');
        var typeId = o.data('type-id');

        var url = baseUrl + 'comment/load/more';
        t.find('.indicator').show();
        $.ajax({
            url : url,
            data : {
                limit : limit,
                offset : offset,
                type : type,
                typeId : typeId
            },
            cache : false,
            success : function(data) {
                data = jQuery.parseJSON(data)
                t.find('.indicator').hide();
                if (data.content == '') {
                    o.find('.load-more-comment').hide();
                } else {
                    o.find('.replies-list').prepend(data.content);
                    o.data('offset', data.offset);
                    reloadPlugins();
                }
            }
        })
    }
    $(document).on('click', '.load-more-comment', function() {
        loadMoreComments($($(this).data('target')), $(this));
        return false;
    })

    $(document).on('click', '.post-activity-loader', function() {
        var o = $(this);
        var id = o.data('id');
        var c = $('#post-activity-' + id);
        var l = o.data('loading');

        c.html('<div style="padding: 10px">'+l+'</div>').fadeIn();
        $.ajax({
            url : o.attr('href'),
            cache : false,
            success: function(content) {
                c.html(content);
            }
        })
        return false;
    });

    $(document).on('click', '#post-privacy-container a', function() {
       var o = $(this);
        var c = $('#post-privacy-container');
        var b = c.find('button');
        var input = c.find('input');
        var v = o.data('value');
        var t = o.data('text');
        
        $.ajax({
            url : baseUrl + 'user/update/post-privacy',
            data : {v : v}
        });

        b.find('span').html(t);
        input.val(v);
        $('body').click();
        return false;
    });



    $(document).on('click', '.post-load-more', function() {
        paginatePost($('#post-list'));
        return false;
    });

    //Processing post text limit
    function processPostTextLimit(o)
    {
        var limit = o.data('text-limit');
        var cVal = o.val();
        var counter = $(o.data('counter-target'));
        if (cVal.length > limit) {
            var cVal = cVal.substr(0, limit);
            o.val(cVal);
        }
        counter.html(limit - cVal.length);
    }

    $(document).on('keyup', '.post-text-limit', function() {
       processPostTextLimit($(this))
    });

    $(document).on('focus', '.post-text-limit', function() {
        processPostTextLimit($(this))
    });
    
    $(document).on('click', '.post-read-more', function() {
        var id = $(this).data('id');
        var content = $('#full-text-content-' + id);
        content = content.html();
        var container = $("#post-text-content-" + id);
        
        container.hide().html(content).fadeIn();
        return false;
    })

    /**End of post**/

    /**processing likes***/
    $(document).on('click', '.like-button', function() {
        var o = $(this);
        var like = o.data('like');
        var unlike = o.data('unlike');
        var status = o.data('status');
        var span = o.find('span');
        var type = o.data('type')
        var id = o.data('id');
        var target = $('.post-like-count-' + id);
        var target2 = $('.photo-post-like-count-' + id);

        if (o.data('target') != undefined) {
            target = $(o.data('target'));
        }

        if (!o.data('is-login')) {

            actionRequiredLoggedIn();
            return false;
        }

        if (status == 0) {
            //like the post
            span.html(unlike);
            o.data('status', 1);
            $.ajax({
                url : baseUrl + 'like/'+type +'/'+id,
                cache : false,
                success : function(r) {
                    target.html(r);
                    target2.html(r);
                }
            })
        } else {
            //unlike the post
            span.html(like);
            o.data('status', 0);
            $.ajax({
                url : baseUrl + 'unlike/'+type +'/'+id,
                success : function(r) {
                    target.html(r)
                    target2.html(r);
                }
            })
        }
        return false;
    });


    /**user account settings**/
    $(document).on('change', '#account-form #image-input', function () {
        var form = $('#account-form');
        changePhoto(form, $(this));
    })

    /**User design techs**/
    function changePageDesign(bgImg, bgColor, bgPosition, bgAttach, bgRepeat, lColor, pageColor )
    {
        //return false;
        if (bgImg != '' && bgImg != null) {
            $('body').css('background-image', 'url('+bgImg+')');
        } else {
            $('body').css('background-image', 'none');
        }

        //alert(bgImg);

        $('body').css('background-color', ''+bgColor+'');
        $('body').css('background-position', ''+bgPosition+'');
        $('body').css('background-attachment', ''+bgAttach+'');
        $('body').css('background-repeat', ''+bgRepeat+'');
        $('.design-link').css('color', lColor);

        $('.page-content').css('background-color', pageColor );
    }


    $(document).on('click', 'a[data-toggle=design]', function() {
        var bgImg = $(this).attr('background-image');
        var bgColor = $(this).attr('background-color');
        var bgPosition = $(this).attr('background-position');
        var bgAttach = $(this).attr('background-attachment');
        var bgRepeat = $(this).attr('background-repeat');
        var lColor = $(this).attr('link-color');
        var pageColor = $(this).attr('page-content-bg-color');

        changePageDesign(bgImg, bgColor, bgPosition, bgAttach, bgRepeat, lColor, pageColor);
        $('.user-selected-theme').val($(this).data('key'));

        /**lets disable others and enable this**/
        $('#themes').find('a').css('border-color', '#E8E8E8');
        $(this).css('border-color', '#D64541');
        return false;
    });

    $(document).on('change', '#background-image-input', function() {

        var preview = $('.bg-preview-image');
        var input = $(this);
        preview.css('opacity', '0.4');
        var form = $('#design-form');

        form.ajaxSubmit({
            type : 'POST',
            url : baseUrl + 'account/design/bg',
            data : {current: $(this).data('current')},
            success : function(data) {
                data = jQuery.parseJSON(data);
                if (data.response == 1) {

                    changePageDesign(data.image, $('#bgcolorpicker').val(), $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), $('#linkcolorpicker').val(), $('#pagecolorpicker').val() )
                    preview.attr('src', data.image);
                    input.next().val(data.imagePath);
                } else {
                    $('.page-bg-upload-error').fadeIn();
                    setTimeout(function() {
                        $('.page-bg-upload-error').hide();
                    }, 5000)
                }

                preview.css('opacity', '1');
            }
        });
    });

    $(document).on('focus','#bgcolorpicker', function()
    {
        $(this).ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                jQuery(el).val('#'+hex);
                jQuery(el).ColorPickerHide();
                $('body').css('background-color','#'+hex+'');

                changePageDesign($('#background-image-input'), hex, $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), $('#linkcolorpicker').val(), $('#pagecolorpicker').val() )
            },
            onBeforeShow: function () {
                jQuery(this).ColorPickerSetColor(this.value);
            }
        });
    });

    $(document).on('focus','#linkcolorpicker', function()
    {
        $(this).ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                jQuery(el).val('#'+hex);
                jQuery(el).ColorPickerHide();

                changePageDesign($('#background-image-input'), $('#bgcolorpicker').val(), $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), hex, $('#pagecolorpicker').val() )
            },
            onBeforeShow: function () {
                jQuery(this).ColorPickerSetColor(this.value);
            }
        });
    });

    $(document).on('focus','#pagecolorpicker', function()
    {
        $(this).ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                var color = 'rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.2)';
                jQuery(el).val(color);
                jQuery(el).ColorPickerHide();
                $('.page-content').css('background-color',color);

                changePageDesign($('#background-image-input'), $('#bgcolorpicker').val(), $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), $('#linkcolorpicker').val(), color )
            },
            onBeforeShow: function () {
                jQuery(this).ColorPickerSetColor(this.value);
            }
        });
    });

    //changePageDesign($('#background-image-input'), $('#bgcolorpicker').val(), $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), $('#linkcolorpicker').val(), $('#pagecolorpicker').val() )

    $(document).on('change', '#bg-attachment-input', function() {
        changePageDesign($('#background-image-input'), $('#bgcolorpicker').val(), $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), $('#linkcolorpicker').val(), $('#pagecolorpicker').val() )
    });
    $(document).on('change', '#bg-repeat-input', function() {
        changePageDesign($('#background-image-input'), $('#bgcolorpicker').val(), $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), $('#linkcolorpicker').val(), $('#pagecolorpicker').val() )
    });
    $(document).on('change', '#bg-position-input', function() {
        changePageDesign($('#background-image-input'), $('#bgcolorpicker').val(), $('#bg-position-input').val(), $('#bg-attachment-input').val(), $('#bg-repeat-input').val(), $('#linkcolorpicker').val(), $('#pagecolorpicker').val() )
    });




    /**Connections process***/
    $(document).on('click', '.follow-button', function() {
        var userid = $(this).data('userid')
        var toUserid = $(this).data('touserid');
        var e = $('.' + toUserid + '-follow-button');
        var o = $(this);

        e.removeClass('follow-button btn-lightblue').addClass('unfollow-button btn-danger').html(o.data('unfollow-title'));

        $.ajax({
            url : baseUrl + 'connection/add/'+userid+'/'+toUserid+'/1'
        });
        return false;
    });
    ///unfollow
    $(document).on('click', '.unfollow-button', function() {
        var userid = $(this).data('userid')
        var toUserid = $(this).data('touserid');
        var e = $('.' + toUserid + '-follow-button');
        var o = $(this);

        e.addClass('follow-button btn-lightblue').removeClass('unfollow-button btn-danger').html(e.data('follow-title'));

        $.ajax({
            url : baseUrl + 'connection/remove/'+userid+'/'+toUserid+'/1'
        })
        return false;
    });

    //add friend
    $(document).on('click', '.add-friend-button', function() {
        var userid = $(this).data('userid')
        var toUserid = $(this).data('touserid');
        var e = $('.' + toUserid + '-add-friend-button');
        e.removeClass('btn-blue').addClass('btn-default').html(e.data('sent-title'));

        $.ajax({
            url : baseUrl + 'connection/add/'+userid+'/'+toUserid+'/2',
            cache : false,
            success : function() {
                e.click(function() {
                    return false;
                })
            }
        });
        return false;
    });

    /**remove friend**/
    $(document).on('click', '.unfriend-button', function() {
        var url = $(this).attr('href');

        bootbox.confirm('Are you sure?', function(r) {
            if (r) {
                $.ajax({
                    url : url,
                    cache : false,
                    success : function() {
                        window.location.reload();
                    }
                });
            }
        })
        return false;
    });


    /**Notification dropdown***/    
    $(document).on('click', '#notification-link > a', function() {
        var c = $("#notification-link");
        var l = $(this);
        var d = c.find('.notification-dropdown');
        $('.header-dropdown-box').hide(); //hide others
        d.fadeIn().find('.indicator').show();

        $(this).find('span').remove();
        $.ajax({
            url : baseUrl + 'notification/dropdown',
            success : function(data)
            {
                d.find('.content').html(data);
                reloadPlugins();
                d.find('.indicator').hide();
            }
        });
        d.find('.footer-link').unbind().click(function() {
            d.fadeOut();
        });
        d.find('.close-button').unbind().click(function() {
            d.fadeOut();
            return false;
        })
        return false;
    });

    $(document).on('click', '.notification .delete-button', function() {
        var id = $(this).data('id');
        var c = $('.'+id+'-notification');

        c.fadeOut();

        $.ajax({
            url : baseUrl + 'notification/delete/'+id
        });
        return false;
    });

    /**
     * Notification receiver processing
     */
    $(document).on('click', '.toggle-notification-receiver', function() {
        var type = $(this).data('type');
        var typeId = $(this).data('type-id');
        var userid = $(this).data('userid');
        var o = $(this);
        var status = $(this).data('status');

        if (status == 1) {
            o.find('span').html(o.data('on'));
            o.data('status', 0);
            $.ajax({
                url : baseUrl + 'notification/receiver/remove/' + userid + '/' + type + '/' + typeId
            });
        } else {
            o.find('span').html(o.data('off'));
            o.data('status', 1);

            $.ajax({
                url : baseUrl + 'notification/receiver/add/' + userid + '/' + type + '/' + typeId
            });
        }
        return false;
    });

    /**
     * Blocking of user through different location
     */
    $(document).on('click', '.block-user', function() {
       var location = $(this).data('location');
        var userid = $(this).data('userid');
        bootbox.confirm('Are you sure to block this user', function(r) {
            if (r) {
                $.ajax({
                    url : baseUrl + 'block/user/' + userid,
                    cache : false,
                    success : function() {
                        if (location == 'post') {
                            $('.user-post-' + userid).fadeOut();
                        } else if (location == 'profile') {
                            window.location.reload();
                        }
                    }
                });


            }
        });

        return false;
    });
    $(document).on('click', '.unblock-button', function() {
        var id = $(this).data('id');
        var o = $(this);
        bootbox.confirm('Are you sure ?', function(r) {
            if(r) {
                $.ajax({
                    url : baseUrl + 'unblock/' + id
                });

                $("#blocked-" + id).fadeOut();
            }
        });

        return false;
    });

    /**Delete account ***/
    $(document).on('click', '#delete-account', function() {
        var url = $(this).attr('href');
        var wMessage = $(this).data('warning-message');

        bootbox.confirm(wMessage, function(r) {
           if (r) {
               window.location = url;
           }
        });

        return false;
    });


    /**Community scripting**/
    $(document).on('click', '.community-create-form .toggle-create', function() {
        var className = $(this).data('class');
        var privacy = $(this).data('privacy');
        $(this).find('.form-container').fadeIn();

        $('.community-create-form').find('.privacy').val($(this).data('privacy'));
        if (privacy == 1) {
            $('.community-create-form').find('.right .form-container').hide();
        } else {
            $('.community-create-form').find('.left .form-container').hide();
        }
    });

    $(document).on('click', '.join-community', function() {
        var id = $(this).data('id');
        $(this).attr('disabled', 'disabled');

        $.ajax({
            url : baseUrl + 'community/join/' + id,
            success : function() {
                window.location.reload();
            }
        })
        return false;
    });

    $(document).on('click', '.community-create-category-button', function() {
        $(this).hide();
        $('.community-create-category-form').fadeIn().find('input').focus();
        return false;
    });

    $(document).on('submit', '.community-create-category-form', function() {
        var id = $(this).data('id');
        var text = $(this).find('input').val();
        var o = $(this);
        $.ajax({
            url : baseUrl + 'community/category/add',
            cache : false,
            data : {
                id : id,
                text : text
            },
            success : function(content)
            {
                var c = jQuery.parseJSON(content);
                if (c.status == 1) {
                    $("<a data-ajaxify='true' href='"+ c.url+"'>"+ c.title+"</a>").insertBefore($('.community-create-category-button'));
                } else {
                    //failed
                    bootbox.alert(c.message);
                }

                $('.community-create-category-button').show();
                o.hide().find('input').val('');
            }
        })
        return false;
    });

    $(document).on('click', '.community-category-remove', function() {
        var id = $(this).data('id');
        var c = $('.category-' + id);

        c.fadeOut().remove();
        $.ajax({
            url : baseUrl + 'community/category/delete/'+id
        });
    });

    function prepareCommunityLogoUpload() {
        var cropCover = new Croppic('croppic-community-cover', {
            customUploadButtonId: 'change-community-cover',
            uploadUrl: baseUrl + 'community/upload/cover',
            cropUrl: baseUrl + 'community/crop/cover',
            loaderHtml :'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
            modal : false,
            cropData : {
              id : $('#croppic-community-cover').data('id')
            },
            outputUrlId : 'community-cropped-cover-image',
            onBeforeImgUpload: function() {
                $('#croppic-community-cover').show().css('background:','white');
            },
            onAfterImgCrop: function() {
                $('#croppic-community-cover').hide();
                var img = $('#community-cropped-cover-image').val();

                $('#croppic-community-cover').css('background', 'url('+img+')');
                $('.community-cover').css('background', 'url('+img+')');
            },
            onAfterClose : function() {
                $('#croppic-community-cover').hide();
            }
        });
    }

    $(document).on('click', '.delete-community', function() {
        var o = $(this);
        bootbox.confirm('Are you sure?', function(c) {
            if (c) window.location = o.attr('href');
        })
        return false;
    })

    /**Member invite button**/
    $(document).on('click', '.invite-member', function() {
        var o = $(this);
        o.attr('disabled', 'disabled').removeClass('btn-success').addClass('btn-default').html('Invited');
        $.ajax({
            url : o.attr('href'),
            success : function() {

            }
        });
        return false;
    });

    /**Photo albums**/
    $(document).on('click', '.album-create-button', function() {
        var c = $('.album-create');

        c.find('span').hide();
        c.find('form').fadeIn().find('input').focus();
        return false;
    });

    $(document).on('submit', '.album-create form', function() {
        var text = $(this).find('input').val();
        var c = $('.album-create');
        if (text == '') return false;
        $.ajax({
            url : baseUrl + 'photo/album/create',
            cache : false,
            data : {name : text},
            success : function(data) {
                data = jQuery.parseJSON(data);
                if (data.response != 0) {
                    var d = $('<div></div>');
                    d.html(data.album);
                    d.insertAfter($('.album-create'));
                } else {
                    bootbox.alert(data.message)
                }


                //now reset
                c.find('span').show();
                c.find('form').fadeOut()
            }
        })
        return false;
    });

    $(document).on('change', '#album-image-input', function() {
        $('.photo-add').css('opacity', '0.5');
        var obj = $(this);
        $('#photos-upload-form').ajaxSubmit({
            url : baseUrl + 'photos/upload',
            data : {album : $(this).data('album-id')},
            success : function(data) {
                $('.photo-add').css('opacity', '1');
                obj.val('');
                if (data == '') {
                    $('#album-add-photo-error').fadeIn();
                    setTimeout(function() {
                        $('#album-add-photo-error').fadeOut();
                    }, 5000)
                    return false;
                }
                var v = $('<div></div>')
                v.html(data);
                v.insertAfter($('.photo-add'));

                reloadPlugins();
            }
        });
    });

    $(document).on('click', '.delete-photo', function() {
        var l = $(this).attr('href');

        var c = confirm('Are you sure?');
        if (c) {
            window.location = l;
        }
        return false;
    });

    $(document).on('click', '.photo-album-edit-button', function() {
        var albumId = $(this).data('album-id');
        $('#photo-album-edit-form-' + albumId).fadeIn();
        return false;
    });

    $(document).on('click', '.photo-album-edit-cancel-button', function() {
        var albumId = $(this).data('album-id');
        $('#photo-album-edit-form-' + albumId).fadeOut();
        return false;
    });

    $(document).on('submit', '.photo-album-edit-form', function() {
        var albumId = $(this).data('album-id');
        var text = $(this).find('input[type=text]').val();
        var title = $('#album-title-' + albumId);
        var form = $(this);
        form.css('opacity', '0.6');
        $.ajax({
            url : baseUrl + 'photo/album/edit',
            cache : false,
            data : {id:albumId, text : text},
            success : function(data) {
                if (data != 0) title.html(data);
                form.css('opacity', '0.6').hide();;
            }
        });

        return false;
    });

    $(document).on('mouseover', '.photo', function() {

        $(this).find('.photo-time').fadeIn();
    });

    $(document).on('mouseout', '.photo', function() {

        $(this).find('.photo-time').fadeOut();
    });


    /**Page scripts***/
    $(document).on('change', '#page-image-input', function() {
        var form = $('#page-edit-form');
        var id = form.data('id');
        var img = $('#page-logo-image');
        img.css('opacity', '0.7');
        var input = $(this);

        form.ajaxSubmit({
            type : 'POST',
            url : baseUrl + 'pages/save/photo',
            data : {id : id},
            success : function(data) {
                data = jQuery.parseJSON(data);
                if (data.code == 1) {
                    img.attr('src', data.url);
                } else {
                    $('#page-image-error').fadeIn();
                    setTimeout(function() {
                        $('#page-image-error').fadeIn();
                    }, 5000);
                }
                input.val('');
            }
        });
    });

    $(document).on('click', '.page-delete-button', function() {
        var url = $(this).attr('href');
        bootbox.confirm('Are you sure?', function($r) {
           if ($r) {
               window.location = url;
           }
        });
        return false;
    })
    function preparePageCover() {
        var cropCover = new Croppic('page-profile-cover', {
            customUploadButtonId: 'change-page-profile-cover',
            uploadUrl: baseUrl + 'page/upload/cover',
            cropUrl: baseUrl + 'page/crop/cover',
            outputUrlId : 'cropped-cover-image',
            loaderHtml :'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
            cropData : {
                id : $('#page-profile-cover').data('id')
            },
            onBeforeImgUpload: function() {
                $('#page-profile-cover').show();
            },
            onAfterImgCrop: function() {
                $('#page-profile-cover').hide();
                var img = $('#cropped-cover-image').val();

                $('#profile-header').css('background', 'url('+img+')');
                $('#page-profile-cover').css('background', 'url('+img+')');
            },
            onAfterClose : function() {
                $('#page-profile-cover').hide();
            }
        });
    }

    $(document).on('keyup', '.add-admin-form input[type=text]', function() {

        var sContainer = $('.add-admin-form .suggestion-container');
        var indicator = sContainer.find('.indicator');
        sContainer.fadeIn();
        sContainer.find('.listing').html('')
        indicator.show();
        if ($(this).val().length > 0) {
            $.ajax({
                url : baseUrl + 'pages/suggest',
                type : 'POST',
                cache : false,
                data : {term : $(this).val(), pageid : $(this).data('page-id')},
                success : function(data) {
                    data = jQuery.parseJSON(data);
                    indicator.hide();

                    if (data.content != '') {
                        sContainer.find('.listing').html(data.content);
                    } else {
                        sContainer.find('.listing').html(data.message);
                    }

                }
            })
        }
    });

    $(document).on('click', '.add-admin-form .listing a', function() {
        var userId = $(this).data('user-id');
        var fullname = $(this).data('fullname');
        var avatar = $(this).data('avatar');
        var container = $('.add-admin-form .selected-user');
        var input = $('.add-admin-form .the-selected-user');
        var textInput = $('.add-admin-form input[type=text]');
        var sContainer = $('.add-admin-form .suggestion-container');

        var div = $("<div class='media'><div class='media-object pull-left'><img src='"+avatar+"'/></div><div class='media-body'><h5>"+fullname+"</h5> <a class='delete' href=''><i class='icon ion-close'></i> </a> </div> </div>")
        sContainer.hide().find('.listing').html('');
        container.html(div);
        input.val(userId);
        textInput.hide();
        //assign delete event
        container.find('.delete').click(function() {
            container.html('');
            input.val('');
            textInput.fadeIn();
            return false;
        })
        return false;
    });

    $(document).on('change', '.page-admin-role-selection', function() {
        var admin = $(this).data('admin');
        var moderator = $(this).data('moderator');
        var editor = $(this).data('editor');
        var target = $(this).data('target');

        var message = admin
        var v = $(this).val();

        if (v == 1) {
            message = admin;
        } else if(v == 2) {
            message = editor;
        } else {
            message = moderator;
        }

        $(target).html(message);


    });

    $(document).on('submit', '.each-page-admin-form', function() {
        var adminId = $(this).data('admin-id');
        var type = $(this).find('select').val();
        var form = $(this);

        form.css('opacity', '0.4');
        $.ajax({
            url : baseUrl + 'pages/update/admin',
            data : {adminId : adminId, type : type},
            type : 'POST',
            success : function() {
                form.css('opacity', 1);
            }
        })

        return false;
    })

    function processPageAdmin(data)
    {
        data = jQuery.parseJSON(data);
        if (data.code == 1) {
            //insert it into the lists
            var c = $('.custom-admin-list');
            c.append(data.message);
            var container = $('.add-admin-form .selected-user');
            var input = $('.add-admin-form .the-selected-user');
            var textInput = $('.add-admin-form input[type=text]');

            container.html('');
            input.val('');
            textInput.fadeIn().val('');

        } else {
            bootbox.alert(data.message);
        }
    }

    $(document).on('submit', '.add-admin-form', function(e) {
        e.preventDefault();
        var form = $(this);

        if (form.find('.the-selected-user').val() == '') return false;
        form.css('opacity', '0.4');
        if (!supportFormData()) {
            form.ajaxSubmit({
                url : baseUrl + 'pages/add/admin',
                success : function(data) {

                    processPageAdmin(data);
                    form.css('opacity', '1');
                }
            })
        } else {
            $.ajax({
                url : baseUrl + 'pages/add/admin',
                type : 'POST',
                data: new FormData(this),
                cache : false,
                processData : false,
                contentType : false,
                success : function(data) {
                    processPageAdmin(data);
                    form.css('opacity', '1');
                }
            });
        }

        return false;
    });

    //delete page
    $(document).on('click', '.remove-page-admin', function() {
        var id = $(this).data('admin');

        bootbox.confirm('Are you sure?', function(e) {
           if (e) {
               //delete this page admin
               $('#page-admin-' + id).fadeOut();
               $.ajax({
                   url : baseUrl + 'pages/remove/admin',
                   data : {id : id}
               });
           }
        });
        return false;
    });

    $(document).on('change', '#page-add-image-input', function() {

        var form = $('#page-add-photos-form');
        var o = form.find('a');
        var c = o.data('current');
        var i = o.find('img');
        var e = $('#page-add-photo-error');
        i.fadeIn();
        //$('body').click();

        form.ajaxSubmit({
            url : baseUrl + 'pages/add/photos',
            success : function(data) {


                if (data != '0') {
                    $('#page-photos-container').prepend(data);
                    reloadPlugins();
                    i.fadeOut();
                } else {
                    i.fadeOut();
                    e.fadeIn();
                    setTimeout(function() {
                        e.fadeOut();
                    }, 5000)
                }
            }
        })


    });

    //invite friends to like page pagination
    $(document).on('mouseover', '#page-inviter-members', function() {
        window.pageInviteePaginationn = false;
        $(this).scroll(function() {

            if ($(this).data('stop') == undefined && !window.pageInviteePaginationn) {
                if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
                    var offset = $(this).data('offset');
                    var pageId = $(this).data('page-id');
                    var obj = $(this);
                    window.pageInviteePaginationn = true;
                    $.ajax({
                        url : baseUrl + 'pages/load/more/invitees',
                        type : 'POST',
                        data : {offset : offset,pageid : pageId},
                        success : function(data) {
                            data = jQuery.parseJSON(data);
                            window.pageInviteePaginationn = false;
                            if (data.content == '') {
                                obj.data('stop', true);
                            } else {
                                obj.append(data.content);
                                obj.data('offset', data.offset)
                            }
                        }
                    })
                }
            }
        })

    });

    $(document).on('keyup', '.page-friends-inviter-search', function() {
        var c = $('#page-inviter-members');
        var t = $(this).val();
        var pageId = $(this).data('page-id');
        c.html('').data('stop', true);
        $.ajax({
            url : baseUrl + 'pages/search/for/invitees',
            type : 'POST',
            cache : false,
            data : {text : t, pageid : pageId},
            success : function(data) {
                c.html(data);
            }
        })
    });


    //update game logo
    $(document).on('change', '#game-image-input', function() {

        var form = $('#game-edit-form');
        var id = form.data('id');
        var img = $('#game-logo-image');
        img.css('opacity', '0.7');
        var input = $(this);

        form.ajaxSubmit({
            type : 'POST',
            url : baseUrl + 'games/save/photo',
            data : {id : id},
            success : function(data) {
                data = jQuery.parseJSON(data);
                img.css('opacity', 1);
                if (data.code == 1) {
                    img.attr('src', data.url);
                } else {
                    $('#game-image-error').slideDown();
                    setTimeout(function() {
                        $('#game-image-error').slideUp();
                    }, 5000)
                }
                input.val('');
            }
        });
    });


    //Messaging
    $(document).on('click', '.send-message-button', function() {
        var userid = $(this).data('userid');
        var label = $(this).data('label');
        bootbox.dialog({
            message : '<textarea data-height="80" id="message-content" style="width: 100%;height: 120px" class="form-control"></textarea>',
            title : label,
            buttons : {
                success : {
                    label : label,
                    className : 'btn btn-xs btn-success',
                    callback : function() {
                        txt = $('#message-content').val();

                        if (txt == '') return false;
                        $.ajax({
                            url : baseUrl + 'messages/send',
                            type : 'POST',
                            data: {
                                text : txt,
                                userid : userid,
                                type : 'alert'
                            },
                            success : function(data) {
                                bootbox.alert(data);
                            }
                        })
                    }
                },
                danger: {
                    label: "Close",
                    className: "btn-danger btn-xs",
                    callback: function() {

                    }
                }
            }
        });

        return false;
    });

    $(document).on('click', '#message-dropdown-link > a', function() {
        var c = $("#message-dropdown-link");
        var l = $(this);
        var d = c.find('.message-dropdown');
        $('.header-dropdown-box').hide(); //hide others
        d.fadeIn().find('.indicator').show();

        //$(this).find('span').remove();
        $.ajax({
            url : baseUrl + 'messages/dropdown',
            success : function(data)
            {
                d.find('.content').html(data);
                reloadPlugins();
                d.find('.indicator').hide();
            }
        });
        d.find('.footer-link').unbind().click(function() {
            d.fadeOut();
        });
        d.find('.close-button').unbind().click(function() {
            d.fadeOut();
            return false;
        })
        return false;
    });

    window.messagePosting = false;
    $(document).on('submit', '.message-form-container form', function(e) {
        var form = $(this);
        var text = form.find('input[type=text]').val();
        var img = form.find('input[type=file]').val();
        var userid = form.data('userid');


        if (window.messagePosting) return false;
        if (text != '' || img != '') {
            form.css('opacity', '0.6');
            $('.message-list-container').data('lastcheck', '');
            window.messagePosting = true;
            e.preventDefault();
            if (!supportFormData()) {
                form.ajaxSubmit({
                url : baseUrl + 'messages/send',
                type : 'POST',
                data: {
                    text : text,
                    userid : userid,
                    type : 'content'
                },
                success : function(data) {
                    
                    
                    if (data != 0) {
                        
                        $('.message-list-container').append(data).data('lastcheck', '');
                        reloadPlugins();
                        scrollMessageBox();
                        scrollMessageBox();
                       
                        
                    } else {
                        alert('Failed to send message')
                    }
                    form.find('input').val('');
                    form.css('opacity', '1');
                    window.messagePosting = false;
                }
                })
            } else {
                $.ajax({
                url : baseUrl + 'messages/send?userid=' + userid + '&type=content',
                type : 'POST',
                
                data: new FormData(this),
                cache : false,
                processData : false,
                contentType : false,
                success : function(data) {
                    
                    
                    if (data != 0) {
                        
                        $('.message-list-container').append(data).data('lastcheck', '');
                        reloadPlugins();
                        scrollMessageBox();
                       
                        
                    } else {
                        alert('Failed to send message')
                    }
                    form.find('input').val('');
                    form.css('opacity', '1');
                    window.messagePosting = false;
                }
            })
            }
        } else {

        }
        return false;
    });

    $(document).on('click', '.load-old-message', function() {
        var userid = $(this).data('userid');
        var offset = $(this).data('offset');
        var o = $(this);

        $.ajax({
            url : baseUrl + 'messages/more',
            type : 'POST',
            data: {

                userid : userid,
                offset : offset
            },
            success : function(data) {
                data = jQuery.parseJSON(data);

                if (data.content != '') {
                    var div = $('<div></div>');
                    div.html(data.content).hide();
                    div.insertAfter(o);
                    div.fadeIn();
                    $('.post-time span').timeago();
                    o.data('offset', data.offset);
                } else {
                    o.hide();
                }

            }
        });

        return false;
    });

    $(document).on('click', '.delete-message-button', function() {
        var id = $(this).data('id');
        var message = $("#message-" + id);

                $.ajax({
                    url : baseUrl + 'messages/delete',
                    data : {
                        id : id
                    }
                })
                message.fadeOut();
        
        return false;
    });

    //chatbox
    $(document).on('click', '.chatbox-container .opener', function() {
        var indicator = $('.chatbox-container .indicator');
        var chatbox = $('.chat-list');

        if (chatbox.css('display') == 'block') {
            //indicator.fadeOut();
            chatbox.fadeOut();
        } else {
            //indicator.fadeIn();
            chatbox.fadeIn();
            $.ajax({
                url : baseUrl + 'messages/online',
                success : function(data) {
                    chatbox.html(data).fadeIn();
                    indicator.hide();
                }
            })
        }
        return false;
    });

    $(document).on('click', '.chat-online-status a', function () {
        var chatbox = $('.chat-list');
        if (chatbox.css('display') == 'none') {
            $('.chatbox-container .opener').click();
        }
    });

    $(document).on('click', '.chat-online-status .dropdown-menu a', function(){
         var v = $(this).data('value');
         var t = $(this).html();
         var c = $('.chat-online-status .online-status');
         c.html(t);
         $('body').click();
         $.ajax({
            url : baseUrl + 'set/online/status',
            data : {status : v}
         });
         return false;
    });

    if (isLogin == 'true') {
        initiateUpdateCheck();
    }
    window.updateCheck = false;
    window.lastAccess = '';
    function initiateUpdateCheck() {

        clearInterval(window.updateCheck);
            var postLastCheck = 0;
            var postType = '';
            var postUserid = '';


            if ($('#post-list').length) {
                var oPost = $('#post-list');
                postType = oPost.data('type');
                postLastCheck = oPost.data('lastcheck');
                if (oPost.data('userid') != undefined) {
                    postUserid = oPost.data('userid');
                }
            }

            var cConversation = 0
            var messagelastcheck = 0;
            if($('.message-list-container').length) {
                cConversation = $('.message-list-container').data('userid');
                messagelastcheck  = $('.message-list-container').data('lastcheck');
            }

            $.ajax({
                url :baseUrl + 'user/update',
                type : 'POST',
                data : {
                    postType : postType,
                    messageuserid : cConversation,
                    messagelastcheck : messagelastcheck,
                    postlastcheck : postLastCheck,
                    postUserid : postUserid,
                    lastaccess : window.lastAccess
                },
                success : function(data) {
                    data = jQuery.parseJSON(data);

                    if (data.status == 'login') {
                        return false;
                    } else {
                        //dispatch the return results
                        window.lastAccess = data.lastaccess;
                        alertUser = false;
                            $('.message-list-container').data('lastcheck', data.messagelastcheck)
                            $('#post-list').data("lastcheck", data.postlastcheck);

                        if (data.notifications != undefined) {
                            var nT = $('#notification-dropdown-trigger');
                            var cnT = $('.notification-dropdown-trigger');

                            if (data.notifications != '0') {

                                if (!nT.find('span').length) {
                                    cnT.append('<span></span>');
                                }
                                var nTSpan = nT.find('span');
                                if (nTSpan.html() != '') {
                                    if (nTSpan.html() != data.notifications) {
                                        alertUser = true;
                                    }
                                } else {
                                    alertUser = true;
                                }
                                cnT.find('span').html(data.notifications).show();
                                //alert(data.notifications)
                            } else {
                                cnT.find('span').html('').hide();
                            }
                        }

                        if (data.requests != undefined) {
                            $('.friends-online-count').html(data.onlines);
                            var rT = $('#friend-request-trigger');
                            var crT = $('.friend-request-trigger');
                            if(data.requests != '0') {

                                if (!rT.find('span').length) {
                                    crT.append('<span></span>');
                                }

                                var rTSpan = rT.find('span');
                                if (rTSpan.html() != '') {
                                    if (rTSpan.html() != data.requests) {
                                        alertUser = true;
                                    }
                                } else {
                                    alertUser = true;
                                }
                                crT.find('span').html(data.requests).show();
                            } else {
                                crT.find('span').html('').hide();
                            }
                        }

                        var mT = $('#new-messages-trigger');
                        var cmT = $('.new-messages-trigger');

                        if(data.unreadmessage != 0) {
                            if (!mT.find('span').length) {
                                cmT.append('<span></span>');
                            }

                            var mTSpan = mT.find('span');
                            if (mTSpan.html() != '') {
                                if (mTSpan.html() != data.unreadmessage) {
                                    alertUser = true;
                                }
                            } else {
                                alertUser = true;
                            }

                            cmT.find('span').html(data.unreadmessage).show()
                        } else {
                            cmT.find('span').html('').hide();
                        }

                        if (data.messages != undefined && data.messages != '') {
                            $('.message-list-container').append(data.messages);
                            //alertUser = true;
                        }

                        //if there is post too
                        if (data.posts != undefined && data.posts != '') {
                            var div = $('<div></div>');
                            div.html(data.posts);
                            $('#post-list').prepend(div);
                            //loadEmoticonPopover(div)
                        }


                        reloadPlugins(true);



                        if (!document.hasFocus() && alertUser) {

                            document.getElementById('update-sound').play();
                        }


                    }


                    window.updateCheck = setInterval(function() {
                        initiateUpdateCheck();
                    }, updateSpeed);
                },
                error : function() {
                    initiateUpdateCheck();
                }
            });

    }


    //friend request dropdown
    $(document).on('click', '#friend-request-trigger', function() {
        
        var l = $(this);
        var d = $('.request-dropdown');
        $('.header-dropdown-box').hide(); //hide others
        d.fadeIn().find('.indicator').show();

        //$(this).find('span').remove();
        $.ajax({
            url : baseUrl + 'connection/dropdown',
            success : function(data)
            {
                d.find('.content').html(data);
                
                d.find('.indicator').hide();
            }
        });
        d.find('.footer-link').unbind().click(function() {
            d.fadeOut();
        });
        d.find('.close-button').unbind().click(function() {
            d.fadeOut();
            return false;
        })
        return false;
    });


    $(document).on('click', '.response-friend-request', function() {
        var url = $(this).attr('href');
        var type = $(this).data('type');
        var id = $(this).data('id');
        var c = $("#friend-request-" + id);
        if (type == 'reject') {
            c.fadeOut();
        } else {
            c.find('.response-friend-request').remove();
            c.find('#visit-profile-link').fadeIn();
        }

        $.ajax({
            url:url,
            cache : false,
            success: function(data) {
            var rT = $('#friend-request-trigger');
            var crT = $('.friend-request-trigger');

            if(data != '0') {

                if (!rT.find('span').length) {
                    crT.append('<span></span>');
                }

                crT.find('span').html(data).show();
            } else {
                crT.find('span').html('').hide();
            }
        }});
        return false;
    });

})