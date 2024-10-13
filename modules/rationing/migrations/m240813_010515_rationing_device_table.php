<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240813_010515_rationing_device_table
 */
class m240813_010515_rationing_device_table extends Migration
{
    public const TABLE_NAME = 'rationing_device';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'paragraph' => $this->string()->notNull()->comment('Операция'),
            'name' => $this->text()->notNull()->comment('Название работ'),
            'norma' => $this->decimal(10,2)->null()->comment('Н/Ч'),
            'sort' => $this->integer()->null()->comment('Вес'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-paragraph-'.self::TABLE_NAME,
            $this->table,
            'paragraph'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
