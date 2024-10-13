<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240813_010523_rationing_device_data_table
 */
class m240813_010523_rationing_device_data_table extends Migration
{
    public const TABLE_NAME = 'rationing_device_data';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $devices_norm_repair = '{{%rationing_device}}';
    private string $unit = '{{%unit}}';
    private string $special = '{{%personal_special}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'operationNumber' => $this->integer()->notNull()->comment('Операция'),
            'rationingDeviceId' => $this->string()->notNull()->comment('Прибор'),
            'name' => $this->text()->notNull()->comment('Операция'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'specialId' => $this->string()->notNull()->comment('Специальность'),
            'ed' => $this->string()->notNull()->comment('Ед. изм.'),
            'countItems' => $this->integer()->notNull()->comment('Кол-во'),
            'periodicity' => $this->decimal(10, 2)->notNull()->comment('Частота вст.'),
            'category' => $this->integer()->notNull()->comment('Разряд'),
            'norma' => $this->decimal(10, 2)->null()->comment('Н/Ч на ед.'),
            'normaAll' => $this->decimal(10, 2)->null()->comment('На операцию'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'sort' => $this->integer()->null()->comment('Вес'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-rationingDeviceId-' . self::TABLE_NAME,
            $this->table,
            'rationingDeviceId'
        );
        $this->addForeignKey(
            'fk-rationingDeviceId-' . self::TABLE_NAME,
            $this->table,
            'rationingDeviceId',
            $this->devices_norm_repair,
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
        $this->createIndex(
            'idx-specialId-' . self::TABLE_NAME,
            $this->table,
            'specialId'
        );
        $this->addForeignKey(
            'fk-specialId-' . self::TABLE_NAME,
            $this->table,
            'specialId',
            $this->special,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-rationingDeviceId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-specialId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
