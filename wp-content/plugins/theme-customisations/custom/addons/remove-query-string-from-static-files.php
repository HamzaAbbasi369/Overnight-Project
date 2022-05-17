<?php

// Remove query string from static files
function remove_cssjs_ver( $src ) {
    // $reg = '~((?:\.min)?\.(?:js|css))(?:\?ver=(.*?))$~mis';
    //
    // $src = preg_replace($reg, '.v$2$1', $src);

    // $src = preg_replace_callback($reg, function($matches){
    //     return '.'.$matches[2].$matches[1];
    // }, $src);


    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
