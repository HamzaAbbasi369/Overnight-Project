<?php

function flattenWithKeys(array $array, $childPrefix = '.', $root = '', $result = array()) {
    //if(!is_array($array)) return $result;

    ### print_r(array(__LINE__, 'arr' => $array, 'prefix' => $childPrefix, 'root' => $root, 'result' => $result));

    foreach($array as $k => $v) {
        if(is_array($v) || is_object($v)) $result = flattenWithKeys( (array) $v, $childPrefix, $root . $k . $childPrefix, $result);
        else $result[ $root . $k ] = $v;
    }
    return $result;
}