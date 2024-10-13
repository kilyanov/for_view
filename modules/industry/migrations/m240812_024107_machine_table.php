<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240812_024107_machine_table
 */
class m240812_024107_machine_table extends Migration
{
    public const TABLE_NAME = 'machine';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $product = '{{%product}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'productId' => $this->string()->null()->comment('Изделие'),
            'number' => $this->string()->null()->comment('Номер'),
            'name' => $this->string()->notNull()->comment('Название'),
            'comment' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'sort' => $this->integer()->null()->comment('Сортировка'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
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
            'idx-sort-'.self::TABLE_NAME,
            $this->table,
            'sort'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-productId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
