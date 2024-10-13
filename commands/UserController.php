<?php

declare(strict_types=1);

namespace app\commands;

use app\common\interface\StatusAttributeInterface;
use app\common\rbac\CollectionRolls;
use app\models\User;
use UUID\UUID;
use Yii;
use yii\base\Exception;
use yii\console\Controller;

class UserController extends Controller
{

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $userRoot = new User([
            'id' => UUID::uuid7(),
            'username' => 'username',
            'email' => 'username@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $userRoot->setPassword('username');
        $userRoot->generateAuthKey();
        $userRoot->save();
        $rootRole = $auth->getRole(CollectionRolls::ROLE_ROOT);
        $auth->assign($rootRole, $userRoot->getId());
        $user = new User([
            'id' => UUID::uuid7(),
            'username' => 'username1',
            'email' => 'username1@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $user->setPassword('username');
        $user->generateAuthKey();
        $user->save();
        echo 'ADD OK ' . CollectionRolls::ROLE_ROOT . ' ID# ' . $user->id . PHP_EOL;
        $role = $auth->getRole(CollectionRolls::ROLE_ENGINEER_CIL);
        $auth->assign($role, $user->getId());
        $user = new User([
            'id' => UUID::uuid7(),
            'username' => 'username2',
            'email' => 'username@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $user->setPassword('username2');
        $user->generateAuthKey();
        $user->save();
        echo 'ADD OK ' . CollectionRolls::ROLE_ENGINEER_CIL . ' ID# ' . $user->id . PHP_EOL;
        $role = $auth->getRole(CollectionRolls::ROLE_SECONDARY_CIL);
        $auth->assign($role, $user->getId());
        $user = new User([
            'id' => UUID::uuid7(),
            'username' => 'username3',
            'email' => 'username3@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $user->setPassword('username');
        $user->generateAuthKey();
        $user->save();
        echo 'ADD OK ' . CollectionRolls::ROLE_SECONDARY_CIL . ' ID# ' . $user->id . PHP_EOL;
        $role = $auth->getRole(CollectionRolls::ROLE_VERIFIER);
        $auth->assign($role, $user->getId());
        $user = new User([
            'id' => UUID::uuid7(),
            'username' => 'username4',
            'email' => 'username4@yandex.ru',
            'status' => StatusAttributeInterface::STATUS_ACTIVE,
        ]);
        $user->setPassword('username');
        $user->generateAuthKey();
        $user->save();
        echo 'ADD OK ' . CollectionRolls::ROLE_VERIFIER . ' ID# ' . $user->id . PHP_EOL;
        $role = $auth->getRole(CollectionRolls::ROLE_VERIFIER);
        $auth->assign($role, $user->getId());
    }
}
