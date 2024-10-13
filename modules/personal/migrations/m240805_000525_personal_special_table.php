<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240805_000525_personal_special_table
 */
class m240805_000525_personal_special_table extends Migration
{
    public const TABLE_NAME = 'personal_special';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    /**
     * @return void
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'name' => $this->string()->notNull()->comment('Название'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'sort' => $this->bigInteger()->null(),
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
