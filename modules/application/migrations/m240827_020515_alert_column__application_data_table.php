<?php

use yii\db\Migration;

/**
 * Class m240827_020515_alert_column__application_data_table
 */
class m240827_020515_alert_column__application_data_table extends Migration
{
    public const TABLE_NAME = 'application_data';

    private string $table = '{{%' . self::TABLE_NAME . '}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->table,'quantity',$this->decimal(10, 6)->null()->comment('Кол-во'));
        $this->alterColumn($this->table,'quantityReceipt',$this->decimal(10, 6)->null()->comment('Кол-во получено'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->table,'quantity',$this->decimal(10, 3)->null()->comment('Кол-во'));
        $this->alterColumn($this->table,'quantityReceipt',$this->decimal(10, 3)->null()->comment('Кол-во получено'));
    }
}
