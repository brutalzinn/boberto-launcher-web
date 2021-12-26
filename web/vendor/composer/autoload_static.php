<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb144047e2b6b253ee2ada94a44d11dd3
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb144047e2b6b253ee2ada94a44d11dd3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb144047e2b6b253ee2ada94a44d11dd3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb144047e2b6b253ee2ada94a44d11dd3::$classMap;

        }, null, ClassLoader::class);
    }
}
