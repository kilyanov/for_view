<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240812_030239_repair_product_table
 */
class m240812_030239_repair_product_table extends Migration
{
    public const TABLE_NAME = 'repair_product';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $product = '{{%product}}';
    private string $product_node = '{{%product_node}}';
    private string $product_block = '{{%product_block}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'productId' => $this->string()->notNull()->comment('Изделие'),
            'productNodeId' => $this->string()->null()->comment('Система'),
            'productBlockId' => $this->string()->null()->comment('Блок'),
            'number' => $this->string()->notNull()->comment('Зав. №'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
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
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-productId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productNodeId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productBlockId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
