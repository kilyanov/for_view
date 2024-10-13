<?php

use yii\db\Migration;

/**
 * Class m240814_032039_presentation_book_data_product_table
 */
class m240814_032039_presentation_book_data_product_table extends Migration
{
    public const TABLE_NAME = 'presentation_book_data_product';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $presentation_book = '{{%presentation_book}}';
    private string $order_rationing = '{{%order_rationing}}';
    private string $order_rationing_data = '{{%order_rationing_data}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'bookId' => $this->string()->notNull()->comment('Книга предъявления'),
            'orderRationingId' => $this->string()->notNull()->comment('Нормировка'),
            'orderRationingDataId' => $this->string()->notNull()->comment('Пункт нормировки'),
            'norma' => $this->decimal(10, 2)->null()->comment('Н/Ч'),
            'sort' => $this->integer()->null()->comment('Вес'),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-bookId-'.self::TABLE_NAME,
            $this->table,
            'bookId'
        );
        $this->addForeignKey(
            'fk-bookId-'.self::TABLE_NAME,
            $this->table,
            'bookId',
            $this->presentation_book,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-orderRationingId-'.self::TABLE_NAME,
            $this->table,
            'orderRationingId'
        );
        $this->addForeignKey(
            'fk-orderRationingId-'.self::TABLE_NAME,
            $this->table,
            'orderRationingId',
            $this->order_rationing,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-orderRationingDataId-'.self::TABLE_NAME,
            $this->table,
            'orderRationingDataId'
        );
        $this->addForeignKey(
            'fk-orderRationingDataId-'.self::TABLE_NAME,
            $this->table,
            'orderRationingDataId',
            $this->order_rationing_data,
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bookId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-orderRationingId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-orderRationingDataId-'.self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
