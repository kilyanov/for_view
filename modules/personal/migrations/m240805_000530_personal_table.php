<?php

use app\common\interface\HiddenAttributeInterface;
use app\common\interface\StatusAttributeInterface;
use yii\db\Migration;

/**
 * Class m240805_000530_personal_table
 */
class m240805_000530_personal_table extends Migration
{
    public const TABLE_NAME = 'personal';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $unit = '{{%unit}}';
    private string $special = '{{%personal_special}}';
    private string $group = '{{%personal_group}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'type' => $this->smallInteger()->null()->defaultValue(null),
            'specialId' => $this->string()->notNull()->comment('Специальность'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'groupId' => $this->string()->null()->comment('Группа'),
            'fistName' => $this->string()->null()->comment('Фамилия'),
            'lastName' => $this->string()->null()->comment('Имя'),
            'secondName' => $this->string()->null()->comment('Отчество'),
            'discharge' => $this->integer()->null()->comment('Разряд'),
            'salary' => $this->decimal(10, 2)->notNull()->comment('Зарплата'),
            'ratio' => $this->decimal(10, 2)->notNull()->comment('Коэффициент премии'),
            'description' => $this->text()->null()->comment('Примечание'),
            'typeSalary' => $this->smallInteger(1)->defaultValue(0)->notNull()->comment('Для расчета З/П'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(StatusAttributeInterface::STATUS_ACTIVE),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-hidden-'.self::TABLE_NAME,
            $this->table,
            'hidden'
        );
        $this->createIndex(
            'idx-status-'.self::TABLE_NAME,
            $this->table,
            'status'
        );
        $this->createIndex(
            'idx-unitId-'.self::TABLE_NAME,
            $this->table,
            'unitId'
        );
        $this->addForeignKey(
            'fk-unitId-'.self::TABLE_NAME,
            $this->table,
            'unitId',
            $this->unit,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-specialId-'.self::TABLE_NAME,
            $this->table,
            'specialId'
        );
        $this->addForeignKey(
            'fk-specialId-'.self::TABLE_NAME,
            $this->table,
            'specialId',
            $this->special,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-groupId-'.self::TABLE_NAME,
            $this->table,
            'groupId'
        );
        $this->addForeignKey(
            'fk-groupId-'.self::TABLE_NAME,
            $this->table,
            'groupId',
            $this->group,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-groupId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-specialId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
