<?php

declare(strict_types=1);

namespace app\modules\application\models;

use Exception;
use kilyanov\architect\models\BaseImportModel;
use yii\helpers\ArrayHelper;

class ImportModel extends BaseImportModel
{
    /**
     * @var int|null
     */
    public ?int $mark = null;

    /**
     * @var int|null
     */
    public ?int $type = null;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'mark' => 'ЗИП/Материалы',
            'type' => '100% замена/По дефектации/ЗИП-0',
            'file' => 'Файл',
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        $rules = [
            [['mark', 'type'], 'integer'],
        ];
        return ArrayHelper::merge($rules, parent::rules());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function afterValidate(): void
    {
        parent::afterValidate();
        $fileMap = ArrayHelper::getValue($this->fileMap, $this->mark);
        $this->fileMap = $fileMap;
        $this->cfgParams = ArrayHelper::merge(
            $this->cfgParams,
            [
                'mark' => $this->mark,
                'type' => $this->type,
            ]
        );
    }
}
