<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd065907c0eec875f901de0725baeafea
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Composer\\CustomDirectoryInstaller' => 
            array (
                0 => __DIR__ . '/..' . '/mnsami/composer-custom-directory-installer/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd065907c0eec875f901de0725baeafea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd065907c0eec875f901de0725baeafea::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitd065907c0eec875f901de0725baeafea::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
