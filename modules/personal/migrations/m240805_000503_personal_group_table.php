<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240805_000503_personal_group_table
 */
class m240805_000503_personal_group_table extends Migration
{
    public const TABLE_NAME = 'personal_group';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $unit = '{{%unit}}';

    /**
     * @return void
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'unitId' => $this->string()->notNull()->comment('Подразделение'),
            'name' => $this->string()->notNull()->comment('Название'),
            'description' => $this->text()->null()->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'sort' => $this->bigInteger()->null(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-unitId-'.self::TABLE_NAME,
            $this->table,
            'unitId'
        );
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
        $this->addForeignKey(
            'fk-unitId-'.self::TABLE_NAME,
            $this->table,
            'unitId',
            $this->unit,
            'id',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-unitId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
