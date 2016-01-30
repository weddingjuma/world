<?php
/**
*Admincp default theme boot file
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/

Theme::asset()->add('bootstrap-css', 'theme/css/bootstrap.min.css');
Theme::asset()->add('jasny-bootstrap-css', 'theme/css/jasny-bootstrap.min.css');
Theme::asset()->add('ionicons-css', 'theme/css/ionicons.min.css');
Theme::asset()->add('wysiwyg-editor-css', 'theme/css/wysiwyg-editor.css');
//Theme::asset()->add('dropdown-css', 'theme/css/jquery.dropdown.css');
Theme::asset()->add('style-css', 'theme/css/style.css');


/**Javascript**/
Theme::asset()->add('jquery', 'theme/js/jquery.js');
Theme::asset()->add('bootstrap-js', 'theme/js/bootstrap.min.js');
Theme::asset()->add('jasny-bootstrap-js', 'theme/js/jasny-bootstrap.min.js');
Theme::asset()->add('wysiwyg.js', 'theme/js/wysiwyg.js');
Theme::asset()->add('wysiwyg-editor.js', 'theme/js/wysiwyg-editor.js');

Theme::asset()->add('script-js', 'theme/js/script.js');
