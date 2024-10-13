<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240813_010540_rationing_product_data_table
 */
class m240813_010540_rationing_product_data_table extends Migration
{
    public const TABLE_NAME = 'rationing_product_data';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $unit = '{{%unit}}';
    private string $basic_rationing = '{{%rationing_product}}';
    private string $machine = '{{%machine}}';
    private string $special = '{{%personal_special}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'rationingId' => $this->string()->notNull()->comment('Нормировка'),
            'type' => $this->integer()->notNull()->comment('Тип пункта'),
            'point' => $this->integer()->null()->comment('Пункт'),
            'subItem' => $this->integer()->null()->comment('Параграф'),
            'name' => $this->text()->notNull()->comment('Операция'),
            'machineId' => $this->string()->null()->comment('МК'),
            'unitId' => $this->string()->null()->comment('Подразделение'),
            'ed' => $this->string()->null()->comment('Ед. изм.'),
            'countItems' => $this->integer()->null()->comment('Кол-во'),
            'periodicity' => $this->decimal(10, 2)->null()->comment('Частота вст.'),
            'category' => $this->integer()->null()->comment('Разряд'),
            'norma' => $this->decimal(10, 2)->null()->comment('Н/Ч на ед.'),
            'normaAll' => $this->decimal(10, 2)->null()->comment('Н/Ч общ.'),
            'specialId' => $this->string()->null()->comment('Специальность'),
            'comment' => $this->text()->null()->comment('Комментарии'),
            'sort' => $this->integer()->null()->comment('Вес'),
            'checkList' => $this->integer()->null()->comment('Прикрепленный список'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->null(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
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
            'idx-machineId-' . self::TABLE_NAME,
            $this->table,
            'machineId'
        );
        $this->addForeignKey(
            'fk-machineId-' . self::TABLE_NAME,
            $this->table,
            'machineId',
            $this->machine,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-rationingId-' . self::TABLE_NAME,
            $this->table,
            'rationingId'
        );
        $this->addForeignKey(
            'fk-rationingId-' . self::TABLE_NAME,
            $this->table,
            'rationingId',
            $this->basic_rationing,
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
        $this->dropForeignKey('fk-machineId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-rationingId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-specialId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
