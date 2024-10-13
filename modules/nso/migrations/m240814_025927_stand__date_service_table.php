<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240814_025927_stand__date_service_table
 */
class m240814_025927_stand__date_service_table extends Migration
{
    public const TABLE_NAME = 'stand_date_service';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $stand = '{{%stand}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'standId' => $this->string()->notNull()->comment('Стенд'),
            'dateService' => $this->date()->null()->defaultValue(null)->comment('Дата обслуживания'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Примечание'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
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
