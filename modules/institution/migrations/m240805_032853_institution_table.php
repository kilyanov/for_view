<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240805_032853_institution_table
 */
class m240805_032853_institution_table extends Migration
{
    public const TABLE_NAME = 'institution';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'name' => $this->string()->notNull()->comment('Название'),
            'address' => $this->text()->null()->comment('Адрес'),
            'requisites' => $this->text()->null()->comment('Реквизиты'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'sort' => $this->integer()->null(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-name-'.self::TABLE_NAME,
            $this->table,
            'name'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
