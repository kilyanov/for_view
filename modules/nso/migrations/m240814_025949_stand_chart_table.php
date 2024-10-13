<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240814_025949_stand_chart_table
 */
class m240814_025949_stand_chart_table extends Migration
{
    public const TABLE_NAME = 'stand_chart';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $stand = '{{%stand}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'standId' => $this->string()->notNull()->comment('Стенд'),
            'year' => $this->integer()->notNull()->comment('Год'),
            'monthPlan' => $this->integer()->notNull()->comment('Плановый месяц'),
            'monthFact' => $this->integer()->null()->comment('Фактический месяц'),
            'dateFact' => $this->date()->null()->comment('Фактическая дата'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'comment' => $this->text()->null()->comment('Примечание'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk', $this->table, ['id']);
        $this->createIndex(
            'idx-standId-'.self::TABLE_NAME,
            $this->table,
            'standId'
        );
        $this->addForeignKey(
            'fk-standId-'.self::TABLE_NAME,
            $this->table,
            'standId',
            $this->stand,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-standId-'.self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
