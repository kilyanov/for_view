<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240812_153502_order_to_product_table
 */
class m240812_153502_order_to_product_table extends Migration
{
    public const TABLE_NAME = 'order_to_product';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $orders = '{{%order_list}}';
    private string $repaired_products= '{{%repair_product}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'orderId' => $this->string()->null()->comment('Заказ'),
            'productId' => $this->string()->null()->comment('Изделие'),
            'comment' => $this->text()->null()->comment('Комментарии'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO)->comment('Скрыт'),
            'sort' => $this->integer()->null()->comment('Вес'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-orderId-'.self::TABLE_NAME,
            $this->table,
            'orderId'
        );
        $this->addForeignKey(
            'fk-orderId-'.self::TABLE_NAME,
            $this->table,
            'orderId',
            $this->orders,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-productId-'.self::TABLE_NAME,
            $this->table,
            'productId'
        );
        $this->addForeignKey(
            'fk-productId-'.self::TABLE_NAME,
            $this->table,
            'productId',
            $this->repaired_products,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-orderId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productId-'.self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
