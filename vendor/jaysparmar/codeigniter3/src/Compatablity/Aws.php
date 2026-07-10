<?php

namespace CodeIgniter3\Compatablity;

class Aws
{

    public static function init(){
        if (
            (isset($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) && $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        ) {
            $_SERVER['HTTPS'] = 'on';
        }
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $_SERVER['REQUEST_SCHEME'] = 'https';
        }
    }
}