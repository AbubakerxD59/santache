<?php

namespace CodeIgniter3\Constants;

class Env
{

    public static function init(): void
    {
        $envFromEnvFile = $_ENV['CI_ENV'] ?? 'development';

        define('ENVIRONMENT', $envFromEnvFile);
    }

}