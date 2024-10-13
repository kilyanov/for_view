<?php

use kilyanov\architect\factory\BaseAnswerFactory;
use kilyanov\architect\interfaces\AnswerInterface;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\Flysystem\Visibility;
use League\Tactician\CommandBus;
use Symfony\Component\Mailer\Mailer;
use yii\di\Container;
use yii\di\ServiceLocator;
use yii\rbac\DbManager;

return [
    'name' => 'ЦИЛ',
    'timeZone' => 'UTC',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'aliases' => [
        '@root' => dirname(__DIR__),
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@storage' => '@app/web/storage',
        '@import' => '@app/import',
        '@templates' => '@app/templates',
    ],
    'container' => [
        'singletons' => [
            CommandBus::class => static function () {
                $locator = new ServiceLocator([
                    'components' => [
                        /**
                         * Filesystem
                         */
                        kilyanov\filesystem\bus\FilesystemGridBuilderCommand::class => kilyanov\filesystem\bus\FilesystemGridBuilderHandler::class,
                        kilyanov\filesystem\bus\FilesystemWriteStreamCommand::class => kilyanov\filesystem\bus\FilesystemWriteStreamHandler::class,
                        kilyanov\filesystem\bus\FilesystemUpdateStreamCommand::class => kilyanov\filesystem\bus\FilesystemUpdateStreamHandler::class,
                        kilyanov\filesystem\bus\FilesystemDeleteCommand::class => kilyanov\filesystem\bus\FilesystemDeleteHandler::class,
                        /**
                         * Repository
                         */
                        kilyanov\repository\bus\RepositoryCreateCommand::class => kilyanov\repository\bus\RepositoryCreateHandler::class,
                        kilyanov\repository\bus\RepositoryDeleteCommand::class => kilyanov\repository\bus\RepositoryDeleteHandler::class,
                        kilyanov\repository\bus\RepositoryAttachmentCommand::class => kilyanov\repository\bus\RepositoryAttachmentHandler::class,
                        kilyanov\repository\bus\RepositoryDetachmentCommand::class => kilyanov\repository\bus\RepositoryDetachmentHandler::class,
                    ],
                ]);

                $lockingMiddleware = new League\Tactician\Plugins\LockingMiddleware();
                $commandMiddleware = new League\Tactician\Handler\CommandHandlerMiddleware(
                    new League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
                    new League\Tactician\Handler\Locator\CallableLocator([$locator, 'get']),
                    new League\Tactician\Handler\MethodNameInflector\InvokeInflector()
                );

                return new League\Tactician\CommandBus([$lockingMiddleware, $commandMiddleware]);
            },
        ],
        'definitions' => [
            League\MimeTypeDetection\FinfoMimeTypeDetector::class => kilyanov\filesystem\MimeTypeDetector::class,
            kilyanov\filesystem\grid\GridBuilderInterface::class => kilyanov\filesystem\grid\GridBuilder::class,
            League\Glide\Server::class => static function () {
                $source = new League\Flysystem\Filesystem(
                    new League\Flysystem\Local\LocalFilesystemAdapter(Yii::getAlias('@storage')
                    )
                );
                $cache = new League\Flysystem\Filesystem(
                    new League\Flysystem\Local\LocalFilesystemAdapter(Yii::getAlias('@storage/cache')
                    )
                );

                return League\Glide\ServerFactory::create([
                    'source' => $source,
                    'cache' => $cache,
                    'driver' => 'imagick',
                    'defaults' => [
                        'fm' => 'webp',
                    ],
                    'cache_with_file_extensions' => true,
                ]);
            },
            kilyanov\filesystem\glide\GlideInterface::class => static function (Container $container) {
                $server = $container->get(League\Glide\Server::class);

                return new kilyanov\filesystem\glide\Glide($server, '/', '/cache');
            },
            League\Flysystem\FilesystemOperator::class => static function () {
                return new League\Flysystem\Filesystem(
                    new League\Flysystem\Local\LocalFilesystemAdapter(
                        Yii::getAlias('@storage'),
                        new PortableVisibilityConverter(
                            0644,
                            0600,
                            0755,
                            0700,
                            Visibility::PUBLIC
                        )
                    )
                );
            },
            AnswerInterface::class => static function () {
                return BaseAnswerFactory::create();
            },
        ],
    ],
    'modules' => [
        'product' => [
            'class' => app\modules\product\Module::class,
        ],
        'unit' => [
            'class' => app\modules\unit\Module::class,
        ],
        'personal' => [
            'class' => app\modules\personal\Module::class,
            'modules' => [
                'group' => [
                    'class' => app\modules\personal\modules\group\Module::class,
                ],
                'special' => [
                    'class' => app\modules\personal\modules\special\Module::class,
                ],
            ],
        ],
        'impact' => [
            'class' => app\modules\impact\Module::class,
        ],
        'institution' => [
            'class' => app\modules\institution\Module::class,
        ],
        'contract' => [
            'class' => app\modules\contract\Module::class,
        ],
        'resource' => [
            'class' => app\modules\resource\Module::class,
        ],
        'device' => [
            'class' => app\modules\device\Module::class,
            'modules' => [
                'group' => [
                    'class' => app\modules\device\modules\group\Module::class,
                ],
                'type' => [
                    'class' => app\modules\device\modules\type\Module::class,
                ],
                'name' => [
                    'class' => app\modules\device\modules\name\Module::class,
                ],
                'property' => [
                    'class' => app\modules\device\modules\property\Module::class,
                ],
            ],
        ],
        'industry' => [
            'class' => app\modules\industry\Module::class,
            'modules' => [
                'machine' => [
                    'class' => app\modules\industry\modules\machine\Module::class,
                ],
                'product' => [
                    'class' => app\modules\industry\modules\product\Module::class,
                ],
                'order' => [
                    'class' => app\modules\industry\modules\order\Module::class,
                ],
                'presentation' => [
                    'class' => app\modules\industry\modules\presentation\Module::class,
                ],
            ],
        ],
        'rationing' => [
            'class' => app\modules\rationing\Module::class,
        ],
        'application' => [
            'class' => app\modules\application\Module::class,
        ],
        'nso' => [
            'class' => app\modules\nso\Module::class,
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => DbManager::class,
            'cache' => 'cache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Europe/Moscow',
            'sizeFormatBase' => 1000,
            'thousandSeparator' => ' ',
            'numberFormatterSymbols' => [
                NumberFormatter::CURRENCY_SYMBOL => '₽',
            ],
            'numberFormatterOptions' => [
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
        ],
        'security' => [
            'class' => 'yii\base\Security',
            'passwordHashCost' => 15,
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'cache' => [
                'class' => 'yii\redis\Cache',
                'defaultDuration' => 0,
                'keyPrefix' => hash('crc32', __FILE__),
                'redis' => [
                    'hostname' => getenv('REDIS_HOST'),
                    'port' => getenv('REDIS_PORT'),
                    'database' => 1,
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'defaultDuration' => 24 * 60 * 60,
            'keyPrefix' => hash('crc32', __FILE__),
            'redis' => [
                'hostname' => getenv('REDIS_HOST'),
                'port' => getenv('REDIS_PORT'),
                'database' => 0,
            ],
        ],
        'mailer' => [
            'class' => Mailer::class,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => getenv('SMTP_ENCRYPTION'),
            ],
            'useFileTransport' => YII_DEBUG, // @runtime/mail/
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        //'yii\web\HttpException:403',
                    ],
                    'enabled' => YII_ENV_PROD,
                ],
            ],
        ],
        'db' => require(__DIR__ . DIRECTORY_SEPARATOR . 'db.php'),
    ],
    'params' => require(__DIR__ . DIRECTORY_SEPARATOR . 'params.php'),
];
