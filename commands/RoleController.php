<?php

declare(strict_types=1);

namespace app\commands;

use Exception;
use Yii;
use app\common\rbac\CollectionRolls;
use yii\console\Controller;

class RoleController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;

        $ROLE_ROOT = $auth->createRole(CollectionRolls::ROLE_ROOT);
        $auth->add($ROLE_ROOT);
        echo 'Create role ROLE_SUPER_ADMIN' . PHP_EOL;

        $ROLE_PRIMARY_CIL = $auth->createRole(CollectionRolls::ROLE_PRIMARY_CIL);
        $auth->add($ROLE_PRIMARY_CIL);
        echo 'Create role ROLE_PRIMARY_CIL' . PHP_EOL;
        $auth->addChild($ROLE_ROOT, $ROLE_PRIMARY_CIL);

        $ROLE_SECONDARY_CIL = $auth->createRole(CollectionRolls::ROLE_SECONDARY_CIL);
        $auth->add($ROLE_SECONDARY_CIL);
        echo 'Create role ROLE_SECONDARY_CIL' . PHP_EOL;
        $auth->addChild($ROLE_ROOT, $ROLE_SECONDARY_CIL);
        $auth->addChild($ROLE_PRIMARY_CIL, $ROLE_SECONDARY_CIL);

        $ROLE_ENGINEER_CIL = $auth->createRole(CollectionRolls::ROLE_ENGINEER_CIL);
        $auth->add($ROLE_ENGINEER_CIL);
        echo 'Create role ROLE_ENGINEER_CIL' . PHP_EOL;
        $auth->addChild($ROLE_ROOT, $ROLE_ENGINEER_CIL);
        $auth->addChild($ROLE_SECONDARY_CIL, $ROLE_ENGINEER_CIL);

        $ROLE_VERIFIER = $auth->createRole(CollectionRolls::ROLE_VERIFIER);
        $auth->add($ROLE_VERIFIER);
        echo 'Create role ROLE_VERIFIER' . PHP_EOL;
        $auth->addChild($ROLE_ROOT, $ROLE_VERIFIER);
        $auth->addChild($ROLE_ENGINEER_CIL, $ROLE_VERIFIER);
    }
}
