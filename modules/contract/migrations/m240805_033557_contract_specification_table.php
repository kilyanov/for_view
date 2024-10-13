<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240805_033557_contract_specification_table
 */
class m240805_033557_contract_specification_table extends Migration
{
    public const TABLE_NAME = 'contract_specification';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    private string $product = '{{%product}}';
    private string $product_node = '{{%product_node}}';
    private string $product_block = '{{%product_block}}';
    private string $contract = '{{%contract}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'contractId' => $this->string()->notNull()->comment('Контракт'),
            'productId' => $this->string()->notNull()->comment('Изделие'),
            'productNodeId' => $this->string()->null()->comment('Система, узел'),
            'productBlockId' => $this->string()->null()->comment('Блок'),
            'factoryNumber' => $this->string()->notNull()->comment('Заводской номер'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'comment' => $this->text()->null()->comment('Примечание'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-contractId-'.self::TABLE_NAME,
            $this->table,
            'contractId'
        );
        $this->addForeignKey(
            'fk-contractId-'.self::TABLE_NAME,
            $this->table,
            'contractId',
            $this->contract,
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
        $this->createIndex(
            'idx-productBlockId-'.self::TABLE_NAME,
            $this->table,
            'productBlockId'
        );
        $this->addForeignKey(
            'fk-productBlockId-'.self::TABLE_NAME,
            $this->table,
            'productBlockId',
            $this->product_block,
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-productId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productNodeId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productBlockId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-contractId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
