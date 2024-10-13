<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024334_device_property_table
 */
class m240808_024334_device_property_table extends Migration
{
    public const TABLE_NAME = 'device_property';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $name_device = '{{%device_name}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'deviceNameId' => $this->string()->null()->comment('Тип'),
            'name' => $this->string()->notNull()->comment('Наименование'),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-deviceNameId-' . self::TABLE_NAME,
            $this->table,
            'deviceNameId'
        );
        $this->addForeignKey(
            'fk-deviceNameId-' . self::TABLE_NAME,
            $this->table,
            'deviceNameId',
            $this->name_device,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-deviceNameId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
