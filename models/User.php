<?php

declare(strict_types=1);

namespace app\models;

use app\common\database\traits\StatusAttributeTrait;
use app\common\interface\StatusAttributeInterface;
use kilyanov\behaviors\ActiveRecord;
use Yii;
use yii\base\Exception;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id ID
 * @property string $username Логин
 * @property string $unitId Подразделение
 * @property string $auth_key Ключ
 * @property string $password_hash Пароль
 * @property string|null $password_reset_token Токен для сброса пароля
 * @property string $email Email
 * @property string|null $verification_token Токен регистрации
 * @property int $status
 * @property string $role Роль
 * @property string $createdAt
 * @property string $updatedAt
 *
 */
class User extends ActiveRecord implements IdentityInterface, StatusAttributeInterface
{
    use StatusAttributeTrait;

    /**
     * @var string|null
     */
    public ?string $unitId = null;


    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'password_hash', 'email',], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['status',], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token',], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'auth_key' => 'Ключ',
            'password_hash' => 'Пароль',
            'password_reset_token' => 'Токен для сброса пароля',
            'email' => 'Email',
            'verification_token' => 'Токен регистрации',
            'status' => 'Статус',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @param $id
     * @return User|IdentityInterface|null
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return array|ActiveRecord|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null): array|ActiveRecord|IdentityInterface|null
    {
        return null;
    }

    /**
     * @param string $username
     * @return User|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::findOne([
            'username' => $username,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @param string $token
     * @return User|null
     */
    public static function findByPasswordResetToken(string $token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param string $token
     * @return User|null
     */
    public static function findByVerificationToken(string $token): ?User
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public static function findByEmail(string $email): ?User
    {
        return static::findOne([
            'email' => $email,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @param string $token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @return string|int|null
     */
    public function getId(): string|int|null
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * @param $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @return void
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }
}
