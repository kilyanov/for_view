<?php

declare(strict_types=1);

namespace app\common\grid;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\rbac\CollectionRolls;
use Yii;
use yii\grid\DataColumn;

class HiddenColumn extends DataColumn
{
    use HiddenAttributeTrait;

    /**
     * @var string
     */
    public $attribute = 'hidden';

    /**
     * @var string
     */
    public $format = 'boolean';

    /**
     * @var string[]
     */
    public $headerOptions = ['style' => 'width:100px'];

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->filter = self::getHiddenList();
        $this->visible = Yii::$app->user->can(CollectionRolls::ROLE_ROOT) && Yii::$app->params['showHidden'];
    }
}
