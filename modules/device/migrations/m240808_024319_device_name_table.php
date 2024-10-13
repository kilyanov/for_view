<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024319_device_name_table
 */
class m240808_024319_device_name_table extends Migration
{
    public const TABLE_NAME = 'device_name';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $type_device = '{{%device_type}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'deviceTypeId' => $this->string()->null()->comment('Тип'),
            'name' => $this->string()->notNull()->comment('Наименование'),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-deviceTypeId-' . self::TABLE_NAME,
            $this->table,
            'deviceTypeId'
        );
        $this->addForeignKey(
            'fk-deviceTypeId-' . self::TABLE_NAME,
            $this->table,
            'deviceTypeId',
            $this->type_device,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-deviceTypeId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
