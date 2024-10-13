<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_014123_resource_table
 */
class m240808_014123_resource_table extends Migration
{
    public const TABLE_NAME = 'resource';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'name' => $this->text()->notNull()->comment('Наименование'),
            'mark' => $this->string()->null()->comment('Чертеж, ГОСТ, ТУ'),
            'ed' => $this->text()->null()->comment('Ед. изм.'),
            'stamp' => $this->string()->null()->comment('Обозначение'),
            'size' => $this->string()->null()->comment('Размер'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
    }

    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
