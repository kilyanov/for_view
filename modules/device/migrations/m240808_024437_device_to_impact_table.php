<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024437_device_to_impact_table
 */
class m240808_024437_device_to_impact_table extends Migration
{
    public const TABLE_NAME = 'device_to_impact';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $list_device = '{{%device}}';
    private string $impact = '{{%impact}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'deviceId' => $this->string()->notNull()->comment('СИ'),
            'impactId' => $this->string()->notNull()->comment('Вид воздействия'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Причина'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-deviceId-' . self::TABLE_NAME,
            $this->table,
            'deviceId'
        );
        $this->addForeignKey(
            'fk-deviceId-' . self::TABLE_NAME,
            $this->table,
            'deviceId',
            $this->list_device,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-impactId-' . self::TABLE_NAME,
            $this->table,
            'impactId'
        );
        $this->addForeignKey(
            'fk-impactId-' . self::TABLE_NAME,
            $this->table,
            'impactId',
            $this->impact,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-deviceId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-impactId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
