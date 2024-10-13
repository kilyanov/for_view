<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240708_152049_product_table
 */
class m240708_152049_product_table extends Migration
{
    public const TABLE_NAME = 'product';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    /**
     * @return void
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'name' => $this->string()->null()->comment('Название'),
            'mark' => $this->string()->notNull()->comment('Обозначение'),
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
    }

    /**
     * @return void
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
