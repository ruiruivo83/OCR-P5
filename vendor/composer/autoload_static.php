<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit514f4db9e489bd73c15e2e89582bdabd
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit514f4db9e489bd73c15e2e89582bdabd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit514f4db9e489bd73c15e2e89582bdabd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}