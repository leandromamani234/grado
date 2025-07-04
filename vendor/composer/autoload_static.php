<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0d35c579e4598840bfda28a0443b2aa6
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'O' => 
        array (
            'Otb\\Registros\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Otb\\Registros\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PhpRbac' => 
            array (
                0 => __DIR__ . '/..' . '/svdaru/phprbac/PhpRbac/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0d35c579e4598840bfda28a0443b2aa6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0d35c579e4598840bfda28a0443b2aa6::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit0d35c579e4598840bfda28a0443b2aa6::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit0d35c579e4598840bfda28a0443b2aa6::$classMap;

        }, null, ClassLoader::class);
    }
}
