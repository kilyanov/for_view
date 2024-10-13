<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240708_152118_product_block_table
 */
class m240708_152118_product_block_table extends Migration
{
    public const TABLE_NAME = 'product_block';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $product = '{{%product}}';
    private string $product_node = '{{%product_node}}';

    /**
     * @return void
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'productId' => $this->string()->notNull()->comment('Изделие'),
            'productNodeId' => $this->string()->null()->comment('Система/узел'),
            'name' => $this->string()->notNull()->comment('Название'),
            'mark' => $this->string()->null()->comment('Обозначение'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'sort' => $this->integer()->null(),
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
            'idx-sort-'.self::TABLE_NAME,
            $this->table,
            'sort'
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
            $this->product,
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-productNodeId-'.self::TABLE_NAME,
            $this->table,
            'productNodeId'
        );

        $this->addForeignKey(
            'fk-productNodeId-'.self::TABLE_NAME,
            $this->table,
            'productNodeId',
            $this->product_node,
            'id',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-productId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productNodeId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
