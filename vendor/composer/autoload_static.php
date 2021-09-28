<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2055b299382142317b90a076cc01cb56
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Socketlabs\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Socketlabs\\' => 
        array (
            0 => __DIR__ . '/..' . '/socketlabs/email-delivery/InjectionApi/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2055b299382142317b90a076cc01cb56::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2055b299382142317b90a076cc01cb56::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2055b299382142317b90a076cc01cb56::$classMap;

        }, null, ClassLoader::class);
    }
}