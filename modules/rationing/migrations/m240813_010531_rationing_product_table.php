<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240813_010531_rationing_product_table
 */
class m240813_010531_rationing_product_table extends Migration
{
    public const TABLE_NAME = 'rationing_product';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $unit = '{{%unit}}';
    private string $impact = '{{%impact}}';
    private string $product = '{{%product}}';
    private string $product_node = '{{%product_node}}';
    private string $product_block = '{{%product_block}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'name' => $this->text()->notNull()->comment('Название'),
            'productId' => $this->string()->null()->defaultValue(null)->comment('Изделие'),
            'productNodeId' => $this->string()->null()->defaultValue(null)->comment('Система'),
            'productBlockId' => $this->string()->null()->defaultValue(null)->comment('Блок'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'impactId' => $this->string()->notNull()->comment('Вид ремонта'),
            'norma' => $this->decimal(10, 2)->null()->defaultValue(null)->comment('Н/Ч'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарии'),
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

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-productId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-impactId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productNodeId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-productBlockId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
