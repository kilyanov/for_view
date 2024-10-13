<?php

use yii\db\Migration;

/**
 * Class m240814_032019_presentation_book_data_device_repair_table
 */
class m240814_032019_presentation_book_data_device_repair_table extends Migration
{
    public const TABLE_NAME = 'presentation_book_data_device_repair';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $presentation_book = '{{%presentation_book}}';
    private string $rationing_device = '{{%rationing_device}}';
    private string $rationing_device_data = '{{%rationing_device_data}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'bookId' => $this->string()->notNull()->comment('Книга предъявления'),
            'rationingDeviceId' => $this->string()->notNull()->comment('Нормировка'),
            'rationingDeviceDataId' => $this->string()->notNull()->comment('Пункт нормировки'),
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
            'idx-rationingDeviceId-'.self::TABLE_NAME,
            $this->table,
            'rationingDeviceId'
        );
        $this->addForeignKey(
            'fk-rationingDeviceId-'.self::TABLE_NAME,
            $this->table,
            'rationingDeviceId',
            $this->rationing_device,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-rationingDeviceDataId-'.self::TABLE_NAME,
            $this->table,
            'rationingDeviceDataId'
        );
        $this->addForeignKey(
            'fk-rationingDeviceDataId-'.self::TABLE_NAME,
            $this->table,
            'rationingDeviceDataId',
            $this->rationing_device_data,
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
        $this->dropForeignKey('fk-rationingDeviceId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-rationingDeviceDataId-'.self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
