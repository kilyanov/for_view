<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240812_153428_order_to_unit_table
 */
class m240812_153428_order_to_unit_table extends Migration
{
    public const TABLE_NAME = 'order_to_unit';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $orders = '{{%order_list}}';
    private string $unit = '{{%unit}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'orderId' => $this->string()->null()->comment('Заказ'),
            'unitId' => $this->string()->null()->comment('Подразделение'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO)->comment('Скрыт'),
            'sort' => $this->integer()->null()->comment('Вес'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-orderId-' . self::TABLE_NAME,
            $this->table,
            'orderId'
        );
        $this->addForeignKey(
            'fk-orderId-' . self::TABLE_NAME,
            $this->table,
            'orderId',
            $this->orders,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-unitId-' . self::TABLE_NAME,
            $this->table,
            'unitId'
        );
        $this->addForeignKey(
            'fk-impactId-' . self::TABLE_NAME,
            $this->table,
            'unitId',
            $this->unit,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-orderId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
