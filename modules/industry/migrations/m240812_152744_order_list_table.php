<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240812_152744_order_list_table
 */
class m240812_152744_order_list_table extends Migration
{
    public const TABLE_NAME = 'order_list';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $contract = '{{%contract}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'type' => $this->string()->notNull()->comment('Тип'),
            'contractId' =>$this->string()->notNull()->comment('Контракт'),
            'numberScore' =>$this->string()->notNull()->comment('Счет'),
            'number' => $this->string()->notNull()->comment('Номер'),
            'year' => $this->integer()->notNull()->comment('Год'),
            'status' => $this->smallInteger(1)->notNull()->comment('Статус'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Комментарии'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO)->comment('Скрыт'),
            'sort' => $this->integer()->null()->comment('Вес'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-number-'.self::TABLE_NAME,
            $this->table,
            'number'
        );
        $this->createIndex(
            'idx-type-'.self::TABLE_NAME,
            $this->table,
            'type'
        );
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
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-contractId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
