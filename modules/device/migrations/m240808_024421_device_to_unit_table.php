<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024421_device_to_unit_table
 */
class m240808_024421_device_to_unit_table extends Migration
{
    public const TABLE_NAME = 'device_to_unit';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $list_device = '{{%device}}';
    private string $unit = '{{%unit}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'deviceId' => $this->string()->notNull()->comment('СИ'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Причина передачи'),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-deviceId-' . self::TABLE_NAME,
            $this->table,
            'deviceId'
        );
        $this->addForeignKey(
            'fk-deviceId-' . self::TABLE_NAME,
            $this->table,
            'deviceId',
            $this->list_device,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-unitId-' . self::TABLE_NAME,
            $this->table,
            'unitId'
        );
        $this->addForeignKey(
            'fk-unitId-' . self::TABLE_NAME,
            $this->table,
            'unitId',
            $this->unit,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-deviceId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
