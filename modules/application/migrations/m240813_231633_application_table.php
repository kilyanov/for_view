<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240813_231633_application_table
 */
class m240813_231633_application_table extends Migration
{
    public const TABLE_NAME = 'application';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $order_to_product = '{{%order_to_product}}';
    private string $order = '{{%order_list}}';
    private string $unit = '{{%unit}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'orderId' => $this->string()->notNull()->comment('Заказ'),
            'productId' => $this->string()->null()->comment('Изделие'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'number' => $this->string()->notNull()->comment('Номер'),
            'dateFiling' => $this->date()->notNull()->comment('Дата обеспечения'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'comment' => $this->text()->null()->comment('Примечание'),
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
            $this->order,
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
            $this->order_to_product,
            'id',
            'CASCADE'
        );
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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-productId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-orderId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
