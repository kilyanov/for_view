<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240805_033531_contract_table
 */
class m240805_033531_contract_table extends Migration
{
    public const TABLE_NAME = 'contract';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $entity = '{{%institution}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'institutionId' => $this->string()->notNull()->comment('Организация'),
            'number' => $this->text()->notNull()->comment('Номер'),
            'name' => $this->text()->notNull()->comment('Название'),
            'description' => $this->text()->null()->comment('Описание'),
            'dateFinding' => $this->date()->null()->comment('Дата заключения'),
            'validityPeriod' => $this->date()->notNull()->comment('Срок действия'),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Статус'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-institutionId-'.self::TABLE_NAME,
            $this->table,
            'institutionId'
        );
        $this->addForeignKey(
            'fk-institutionId-'.self::TABLE_NAME,
            $this->table,
            'institutionId',
            $this->entity,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-institutionId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
