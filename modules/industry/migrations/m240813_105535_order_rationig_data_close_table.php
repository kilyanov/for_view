<?php

use yii\db\Migration;

/**
 * Class m240813_105535_order_rationig_data_close_table
 */
class m240813_105535_order_rationig_data_close_table extends Migration
{
    public const TABLE_NAME = 'order_rationing_data_close';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $order_rationing_data = '{{%order_rationing_data}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'orderRationingDataId' => $this->string()->null()->comment('Ссылка на пункт'),
            'norma' => $this->decimal(10, 2)->null()->comment('Н/Ч'),
            'year' => $this->integer()->notNull()->comment('Год'),
            'month' => $this->integer()->notNull()->comment('Месяц'),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk', $this->table, ['id']);
        $this->createIndex(
            'idx-orderRationingDataId-' . self::TABLE_NAME,
            $this->table,
            'orderRationingDataId'
        );
        $this->addForeignKey(
            'fk-orderRationingDataId-' . self::TABLE_NAME,
            $this->table,
            'orderRationingDataId',
            $this->order_rationing_data,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-orderRationingDataId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
