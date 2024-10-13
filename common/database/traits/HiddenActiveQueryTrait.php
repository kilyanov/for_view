<?php

declare(strict_types=1);

namespace app\common\database\traits;

use app\common\interface\HiddenAttributeInterface;
use app\common\rbac\CollectionRolls;
use Yii;
use yii\db\ActiveQuery;
use yii\web\Application;

trait HiddenActiveQueryTrait
{
    /**
     * @param int|null $hidden
     * @return ActiveQuery
     */
    public function hidden(?int $hidden = HiddenAttributeInterface::HIDDEN_NO): ActiveQuery
    {
        if (Yii::$app instanceof Application) {
            if (!Yii::$app->user->can(CollectionRolls::ROLE_ROOT)) {
                /** @var ActiveQuery $this */
                $this->andFilterWhere([$this->modelClass::tableName() . '.[[hidden]]' => $hidden]);
            }
            else {
                if (!Yii::$app->params['showHidden']) {
                    /** @var ActiveQuery $this */
                    $this->andFilterWhere([$this->modelClass::tableName() . '.[[hidden]]' => $hidden]);
                }
            }
        }
        else {
            $this->andFilterWhere([$this->modelClass::tableName() . '.[[hidden]]' => HiddenAttributeInterface::HIDDEN_NO]);
        }
        return $this;
    }
}
