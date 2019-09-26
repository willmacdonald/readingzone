<?php declare(strict_types=1);

/**
 * @param null $array
 * @return array
 */
function array_flatten(Array $array = null) : Array {
    $result = array();

    if (!is_array($array)) {
        $array = func_get_args();
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, \App\Controller\array_flatten($value));
        } else {
            $result = array_merge($result, array($key => $value));
        }
    }

    return $result;
}
