<?php
/**
 * Created by PhpStorm.
 * User: liche
 * Date: 2017/10/24
 * Time: 15:07
 */
if (! function_exists('admin_url')) {
    function admin_url($uri)
    {
        return url('/dashboard'.$uri);
    }
}

if (! function_exists('human_filesize')) {
    function human_filesize($bytes, $decimals = 2) {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $floor = floor((strval($bytes)-1)/3);
        return sprintf("%.{$decimals}f", $bytes/pow(1024, $floor)).@$size[$floor];
    }
}

if (! function_exists('isActive')) {
    function isActive($nav) {
        return Route::currentRouteName() == $nav ? 'active' : '';
    }
}

if (! function_exists('translug')) {
    function translug($slug) {
        return app('Translug')->translate($slug);
    }
}

if (! function_exists('lang')) {
    function lang($text, $parameters = []) {
        return trans('blog.'.$text, $parameters);
    }
}