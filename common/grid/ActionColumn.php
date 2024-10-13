<?php

declare(strict_types=1);

namespace app\common\grid;

use kilyanov\architect\factory\BaseActionMenuFactory;
use yii\grid\ActionColumn as ActionColumnAlias;

class ActionColumn extends ActionColumnAlias
{
    /**
     * @var string
     */
    public string $factory = BaseActionMenuFactory::class;

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->headerOptions = ['style' => 'width:20px'];
        $this->header = '';
        $this->template = '{menu}';
        $this->buttons = [
            'menu' => function ($url, $model) {
            $classFactory = $this->factory;
                return $classFactory::create(['id' => $model->id]);
            }
        ];
        $this->visible = true;
    }
}
