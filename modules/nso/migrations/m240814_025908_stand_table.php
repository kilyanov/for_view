<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240814_025908_stand_table
 */
class m240814_025908_stand_table extends Migration
{
    public const TABLE_NAME = 'stand';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $unit = '{{%unit}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'number' => $this->string()->null()->defaultValue(null)->comment('Номер стенда'),
            'name' => $this->text()->null()->defaultValue(null)->comment('Название стенда'),
            'mark' => $this->string()->null()->defaultValue(null)->comment('Чертежный номер'),
            'inventoryNumber' => $this->string()->null()->defaultValue(null)->comment('Инвентарный номер'),
            'standardHours' => $this->decimal(10,2)->null()->defaultValue(null)->comment('Н/ч'),
            'category' => $this->integer()->null()->defaultValue(null)->comment('Категория'),
            'conservation' => $this->smallInteger()->null()->defaultValue(0)->comment('Консервация'),
            'dateVerifications' => $this->date()->notNull()->comment('Дата последнего обслуживания'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
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
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
