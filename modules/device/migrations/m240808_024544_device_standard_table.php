<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024544_device_standard_table
 */
class m240808_024544_device_standard_table extends Migration
{
    public const TABLE_NAME = 'device_standard';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $list_device = '{{%device}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'deviceId' => $this->string()->notNull()->comment('СИ'),
            'numberStandard' => $this->string()->notNull()->comment('Номер эталона'),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-numberStandard-' . self::TABLE_NAME,
            $this->table,
            'numberStandard'
        );
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
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-deviceId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
