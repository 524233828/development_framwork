<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    /**
     * The application name.
     */
    'name' => 'ant-fd',

    /**
     * Application logger path
     */
    'log' => [
        [\Monolog\Handler\StreamHandler::class, 'error.log', \Monolog\Logger::ERROR]
    ],

    /*
     * Exception handle
     */
    'exception' => [
        'response' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        },
        'log' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ];
        },
    ],

    /**
     * Bootstrap service.
     */
    'services' => [
        \ServiceProvider\EnvConfigServiceProvider::class,
        \FastD\ServiceProvider\RouteServiceProvider::class,
        \FastD\ServiceProvider\LoggerServiceProvider::class,
        \FastD\ServiceProvider\DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
    ],

    /**
     * Application Consoles
     */
    'consoles' => [

    ],

    /**
     * Http middleware
     */
    'middleware' => [
        'basic.auth' => new FastD\BasicAuthenticate\HttpBasicAuthentication([
            'authenticator' => [
                'class' => \FastD\BasicAuthenticate\PhpAuthenticator::class,
                'params' => [
                    'foo' => 'bar'
                ]
            ],
            'response' => [
                'class' => \FastD\Http\JsonResponse::class,
                'data' => [
                    'msg' => 'not allow access',
                    'code' => 401
                ]
            ]
        ]),
        // 分发
        'dispatch' => new Middleware\Dispatch(),
        //入参过滤
        'filter' => new Middleware\IncomeFilter(),
        //rsa解密
        'rsa' => new \Middleware\RSADecryptMiddleware(),
        //rsa验签
        'verify' => new \Middleware\RSAVerifyMiddleware(),
        //防止重放攻击
        'reply'  => new \Middleware\TimestampMiddleware()
    ],
];