<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024248_device_group_table
 */
class m240808_024248_device_group_table extends Migration
{
    public const TABLE_NAME = 'device_group';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'name' => $this->string()->notNull()->comment('Название'),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'sort' => $this->bigInteger()->null(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
    }

    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
