<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240728_015321_unit_table
 */
class m240728_015321_unit_table extends Migration
{
    public const TABLE_NAME = 'unit';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    /**
     * @return void
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'parentId' =>  $this->string()->null()->defaultValue(null)->comment('Осн. подразделение'),
            'name' => $this->string()->notNull()->comment('Подразделение'),
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
        $this->createIndex(
            'idx-parentId-'.self::TABLE_NAME,
            $this->table,
            'parentId'
        );
        $this->addForeignKey(
            'fk-parentId-'.self::TABLE_NAME,
            $this->table,
            'parentId',
            $this->table,
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * @return void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-parentId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
