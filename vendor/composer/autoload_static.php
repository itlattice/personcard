<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc3c301faafd967683485efad4fb9df5e
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        '1cfd2761b63b0a29ed23657ea394cb2d' => __DIR__ . '/..' . '/iboxs/captcha/src/helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'iboxs\\composer\\' => 15,
            'iboxs\\captcha\\' => 14,
        ),
        'i' => 
        array (
            'iboxs\\payment\\' => 14,
            'iboxs\\appauth\\' => 14,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'Pl1998\\ThirdpartyOauth\\' => 23,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'iboxs\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/iboxs/iboxs-installer/src',
        ),
        'iboxs\\captcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/iboxs/captcha/src',
        ),
        'iboxs\\payment\\' => 
        array (
            0 => __DIR__ . '/..' . '/iboxs/payment/src',
        ),
        'iboxs\\appauth\\' => 
        array (
            0 => __DIR__ . '/..' . '/iboxs/appauth/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
            1 => __DIR__ . '/..' . '/psr/http-factory/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'Pl1998\\ThirdpartyOauth\\' => 
        array (
            0 => __DIR__ . '/..' . '/pltrue/thirdparty_oauth/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc3c301faafd967683485efad4fb9df5e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc3c301faafd967683485efad4fb9df5e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc3c301faafd967683485efad4fb9df5e::$classMap;

        }, null, ClassLoader::class);
    }
}
