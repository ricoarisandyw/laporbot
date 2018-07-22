<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8e6f5d8bdf00f3da716ab659830a7909
{
    public static $files = array (
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
        '89c5c8c5396a3a1f7402830cd218dcc2' => __DIR__ . '/..' . '/dhaval/image-uploader/src/ImageUploader.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Slim\\' => 5,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Container\\' => 14,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'L' => 
        array (
            'LINE\\' => 5,
        ),
        'I' => 
        array (
            'Interop\\Container\\' => 18,
        ),
        'F' => 
        array (
            'FastRoute\\' => 10,
        ),
        'D' => 
        array (
            'DG\\' => 3,
        ),
        'A' => 
        array (
            'Abraham\\TwitterOAuth\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Slim\\' => 
        array (
            0 => __DIR__ . '/..' . '/slim/slim/Slim',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'LINE\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
            1 => __DIR__ . '/..' . '/linecorp/line-bot-sdk/src',
        ),
        'Interop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/container-interop/container-interop/src/Interop/Container',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
        'DG\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Abraham\\TwitterOAuth\\' => 
        array (
            0 => __DIR__ . '/..' . '/abraham/twitteroauth/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static $classMap = array (
        'DG\\Twitter_OAuthConsumer' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthDataStore' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthException' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthRequest' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthServer' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthSignatureMethod' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthSignatureMethod_HMAC_SHA1' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthSignatureMethod_PLAINTEXT' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthSignatureMethod_RSA_SHA1' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthToken' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter_OAuthUtil' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'Twitter' => __DIR__ . '/..' . '/dg/twitter-php/src/Twitter.php',
        'TwitterException' => __DIR__ . '/..' . '/dg/twitter-php/src/Twitter.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8e6f5d8bdf00f3da716ab659830a7909::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8e6f5d8bdf00f3da716ab659830a7909::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit8e6f5d8bdf00f3da716ab659830a7909::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit8e6f5d8bdf00f3da716ab659830a7909::$classMap;

        }, null, ClassLoader::class);
    }
}
