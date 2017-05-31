<?php
declare(strict_types = 1);
/**
 *
 * Easy autoloader
 *
 * @param $className
 * @return bool
 */
function __autoload($className)
{
    $path = realpath(__DIR__.'/..');
    $className = str_replace('\\', '/', $className);
    if (file_exists($path.'/src/'.$className.'.php')) {
        require_once $path.'/src/'.$className.'.php';

        return true;
    }

    return false;
}
