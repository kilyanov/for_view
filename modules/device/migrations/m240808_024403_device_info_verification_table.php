<?php

use app\common\interface\HiddenAttributeInterface;
use yii\db\Migration;

/**
 * Class m240808_024403_device_info_verification_table
 */
class m240808_024403_device_info_verification_table extends Migration
{
    public const TABLE_NAME = 'device_info_verification';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $device = '{{%device}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->comment('ID'),
            'deviceId' => $this->string()->notNull()->comment('СИ'),
            'linkView' => $this->text()->null()->defaultValue(null)->comment('Сведения о результатах поверки СИ'),
            'linkBase' => $this->text()->null()->defaultValue(null)->comment('Сведения о регистрационном номере типа СИ'),
            'certificateNumber' => $this->text()->null()->defaultValue(null)->comment('Номер свидетельства/ Номер извещения'),
            'status' => $this->string()->null()->defaultValue('active')->comment('Статус'),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(HiddenAttributeInterface::HIDDEN_NO),
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
            $this->device,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-deviceId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
