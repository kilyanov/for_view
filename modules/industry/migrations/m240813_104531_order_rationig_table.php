<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240813_104531_order_rationig_table
 */
class m240813_104531_order_rationig_table extends Migration
{
    public const TABLE_NAME = 'order_rationing';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $unit = '{{%unit}}';
    private string $impact = '{{%impact}}';
    private string $product = '{{%product}}';
    private string $product_node = '{{%product_node}}';
    private string $product_block = '{{%product_block}}';
    private string $order = '{{%order_list}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'orderId' => $this->string()->null()->comment('Заказ'),
            'name' => $this->text()->notNull()->comment('Название'),
            'productId' => $this->string()->null()->comment('Изделие'),
            'productNodeId' => $this->string()->null()->comment('Система'),
            'productBlockId' => $this->string()->null()->comment('Блок'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'impactId' => $this->string()->notNull()->comment('Вид ремонта'),
            'norma' => $this->decimal(10, 2)->null()->comment('Н/Ч'),
            'comment' => $this->text()->null()->comment('Коментарии'),
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
            'idx-productId-' . self::TABLE_NAME,
            $this->table,
            'productId'
        );
        $this->addForeignKey(
            'fk-productId-' . self::TABLE_NAME,
            $this->table,
            'productId',
            $this->product,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-productNodeId-' . self::TABLE_NAME,
            $this->table,
            'productNodeId'
        );
        $this->addForeignKey(
            'fk-productNodeId-' . self::TABLE_NAME,
            $this->table,
            'productNodeId',
            $this->product_node,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-productBlockId-' . self::TABLE_NAME,
            $this->table,
            'productBlockId'
        );
        $this->addForeignKey(
            'fk-productBlockId-' . self::TABLE_NAME,
            $this->table,
            'productBlockId',
            $this->product_block,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-impactId-' . self::TABLE_NAME,
            $this->table,
            'impactId'
        );
        $this->addForeignKey(
            'fk-impactId-' . self::TABLE_NAME,
            $this->table,
            'impactId',
            $this->impact,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-orderId-' . self::TABLE_NAME,
            $this->table,
            'orderId'
        );
        $this->addForeignKey(
            'fk-orderId-' . self::TABLE_NAME,
            $this->table,
            'orderId',
            $this->order,
            'id',
            'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-productId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-impactId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-orderId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
