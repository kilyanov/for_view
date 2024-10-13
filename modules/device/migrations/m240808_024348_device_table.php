<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024348_device_table
 */
class m240808_024348_device_table extends Migration
{
    public const TABLE_NAME = 'device';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $type_device = '{{%device_type}}';
    private string $name_device = '{{%device_name}}';
    private string $property_device = '{{%device_property}}';
    private string $group = '{{%device_group}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'deviceGroupId' => $this->string()->notNull()->comment('Группа'),
            'deviceTypeId' => $this->string()->notNull()->defaultValue(null)->comment('Тип'),
            'deviceNameId' => $this->string()->null()->defaultValue(null)->comment('Наименование'),
            'devicePropertyId' => $this->string()->null()->defaultValue(null)->comment('Тех. характ.'),
            'stateRegister' => $this->string()->null()->defaultValue(null)->comment('Гос. реестр'),
            'factoryNumber' => $this->string()->null()->defaultValue(null)->comment('Зав. номер'),
            'inventoryNumber' => $this->string()->null()->defaultValue(null)->comment('Инв. номер'),
            'verificationPeriod' => $this->smallInteger()->null()->defaultValue(null)->comment('Период'),
            'norma' => $this->decimal(10, 2)->null()->comment('Н/Ч'),
            'category' => $this->smallInteger()->null()->defaultValue(null)->comment('Разряд'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Комментарии'),
            'yearRelease' => $this->integer()->null()->defaultValue(null)->comment('Год выпуска'),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-deviceGroupId-' . self::TABLE_NAME,
            $this->table,
            'deviceGroupId'
        );
        $this->addForeignKey(
            'fk-deviceGroupId-' . self::TABLE_NAME,
            $this->table,
            'deviceGroupId',
            $this->group,
            'id',
            'CASCADE'
        );
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
        $this->createIndex(
            'idx-devicePropertyId-' . self::TABLE_NAME,
            $this->table,
            'devicePropertyId'
        );
        $this->addForeignKey(
            'fk-devicePropertyId-' . self::TABLE_NAME,
            $this->table,
            'devicePropertyId',
            $this->property_device,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-deviceGroupId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-deviceTypeId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-deviceNameId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-devicePropertyId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
