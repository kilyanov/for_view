Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kilyanov/yii2-repository "*"
```

or add

```
"kilyanov/yii2-repository": "*"
```

to the require section of your `composer.json` file.

Use:
----

console.php

```php
[
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@kilyanov/repository/migrations',
            ],
        ],
    ],
];
```

common.php

```php
[
    'container' => [
        'singletons' => [
            \League\Tactician\CommandBus::class => static function () {
                $locator = new \yii\di\ServiceLocator([
                    'components' => [
                        /**
                         * Repository
                         */
                        \krok\repository\bus\RepositoryCreateCommand::class => \krok\repository\bus\RepositoryCreateHandler::class,
                        \krok\repository\bus\RepositoryDeleteCommand::class => \krok\repository\bus\RepositoryDeleteHandler::class,
                        \krok\repository\bus\RepositoryAttachmentCommand::class => \krok\repository\bus\RepositoryAttachmentHandler::class,
                        \krok\repository\bus\RepositoryDetachmentCommand::class => \krok\repository\bus\RepositoryDetachmentHandler::class,
                    ],
                ]);

                $lockingMiddleware = new \League\Tactician\Plugins\LockingMiddleware();
                $commandMiddleware = new \League\Tactician\Handler\CommandHandlerMiddleware(
                    new \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
                    new \League\Tactician\Handler\Locator\CallableLocator([$locator, 'get']),
                    new \League\Tactician\Handler\MethodNameInflector\InvokeInflector()
                );

                return new \League\Tactician\CommandBus([$lockingMiddleware, $commandMiddleware]);
            },
        ],
    ],
];
```

Use: UploaderBehavior
---------------------

Model.php

```php
<?php

use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\base\InvalidConfigException;
use krok\repository\behaviors\UploaderBehavior;
use krok\repository\behaviors\RepositoryBehavior;
use krok\repository\models\Repository;
use krok\repository\models\Relation;
use krok\glide\helpers\Glide;

/**
 * @property Repository $srcRelation
 * @property Repository[] $postersRelation
 */
class Model extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $src;

    /**
     * @var array
     */
    public $srcIds;

    /**
     * @var UploadedFile[]
     */
    public $posters;

    /**
     * @var array
     */
    public $posterIds;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'UploaderBehaviorSrc' => [
                'class' => UploaderBehavior::class,
                'uploadedFileAttribute' => 'src',
                'repositoryAttribute' => 'srcIds',
            ],
            'RepositoryBehaviorSrc' => [
                'class' => RepositoryBehavior::class,
                'attribute' => 'srcIds',
                'relation' => 'srcRelation',
            ],
            'UploaderBehaviorPosters' => [
                'class' => UploaderBehavior::class,
                'uploadedFileAttribute' => 'posters',
                'repositoryAttribute' => 'posterIds',
                'multiple' => true,
            ],
            'RepositoryBehaviorPosters' => [
                'class' => RepositoryBehavior::class,
                'attribute' => 'posterIds',
                'relation' => 'postersRelation',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['src'],
                'image',
                'extensions' => [
                    'png',
                    'jpg',
                    'jpeg',
                    'gif',
                ],
                'skipOnEmpty' => true,
            ],
            [
                ['posters'],
                'each',
                'rule' => [
                    'image',
                    'extensions' => [
                        'png',
                        'jpg',
                        'jpeg',
                        'gif',
                    ],
                    'skipOnEmpty' => true,
                ],
            ],
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getSrcRelation()
    {
        return $this
            ->hasOne(Repository::class, [
                'id' => 'repositoryId',
            ])->viaTable(Relation::tableName(), [
                'identity' => 'id',
            ], static function (ActiveQuery $query) {
                $query->onCondition([
                    'model' => static::class,
                    'attribute' => 'srcIds',
                ]);
            });
    }

    /**
     * @return string|null
     * @throws InvalidConfigException
     */
    public function getSrcUrl(): ?string
    {
        if ($this->srcRelation === null) {
            return null;
        }

        return Url::to('@dl' . Glide::make($this->srcRelation->src, [
                'w' => 300,
                'fm' => 'webp',
            ])
        );
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getPostersRelation()
    {
        return $this
            ->hasMany(Repository::class, [
                'id' => 'repositoryId',
            ])->viaTable(Relation::tableName(), [
                'identity' => 'id',
            ], static function (ActiveQuery $query) {
                $query->onCondition([
                    'model' => static::class,
                    'attribute' => 'posterIds',
                ]);
            });
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getPostersUrl(): array
    {
        $urls = [];

        foreach ($this->postersRelation as $poster) {
            $urls[] = Url::to('@dl' . Glide::make($poster->src, [
                    'w' => 300,
                    'fm' => 'webp',
                ])
            );
        }

        return $urls;
    }
}
```

Use: Dropzone
-------------

DefaultController.php

```php
<?php

use yii\web\Controller;
use krok\repository\actions\DropzoneUploadingAction;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'uploading' => [
                'class' => DropzoneUploadingAction::class,
            ],
        ];
    }
}
```

Model.php

```php
<?php

use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\base\InvalidConfigException;
use krok\repository\behaviors\RepositoryBehavior;
use krok\repository\models\Repository;
use krok\repository\models\Relation;
use krok\glide\helpers\Glide;

/**
 * @property int[] $posterIds
 * @property Repository[] $postersRelation
 */
class Model extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'RepositoryBehavior' => [
                'class' => RepositoryBehavior::class,
                'attribute' => 'posterIds',
                'relation' => 'postersRelation',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posterIds'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getPostersRelation()
    {
        return $this
            ->hasMany(Repository::class, [
                'id' => 'repositoryId',
            ])->viaTable(Relation::tableName(), [
                'identity' => 'id',
            ], static function (ActiveQuery $query) {
                $query->onCondition([
                    'model' => static::class,
                    'attribute' => 'posterIds',
                ]);
            });
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getPostersUrl(): array
    {
        $urls = [];

        foreach ($this->postersRelation as $poster) {
            $urls[] = Url::to('@dl' . Glide::make($poster->src, [
                    'w' => 300,
                    'fm' => 'webp',
                ])
            );
        }

        return $urls;
    }
}
```

_form.php

```php
<?php

use krok\repository\widgets\DropzoneInputWidget;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\base\Model;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model Model */
?>

<?= $form->field($model, 'posterIds')->widget(DropzoneInputWidget::class, [
    'clientOptions' => [
        'url' => Url::to(['uploading']),
    ],
]) ?>
```
