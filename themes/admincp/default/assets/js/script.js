$(function() {
    $(document).on('change', '#custom-field-type', function() {
        if ($(this).val() == 'selection') {
            $("#selection-field-container").fadeIn()
        } else {
            $("#selection-field-container").fadeOut();
        }
    })

    $(document).on('click', '#selection-field-container a', function() {
        var input = $("<input style='margin: 10px 0' type='text' class='form-control' name='val[options][]' placeholder='Enter option'/> ");

        input.insertBefore($(this));
        return false;
    });

    $(document).on('change', '.newsletter-to', function() {
        var v = $(this).val();

        if (v == 'selected') {
            $('.selected-members').show();
        } else {
            $('.selected-members').hide();
        }
    });

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

});