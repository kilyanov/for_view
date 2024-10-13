<?php

use yii\db\Migration;

/**
 * Class m240814_032000_presentation_book_table
 */
class m240814_032000_presentation_book_table extends Migration
{
    public const TABLE_NAME = 'presentation_book';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $orders = '{{%order_list}}';
    private string $group = '{{%personal_group}}';
    private string $personal = '{{%personal}}';
    private string $impact = '{{%impact}}';
    private string $device = '{{%device}}';
    private string $stand = '{{%stand}}';
    private string $unit = '{{%unit}}';
    private string $order_rationing = '{{%order_rationing}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'orderId' => $this->string()->notNull()->comment('Заказ'),
            'typeOrder' => $this->string()->notNull()->comment('Тип заказа'),
            'groupId' => $this->string()->notNull()->comment('Группа'),
            'personalId' => $this->string()->notNull()->comment('Специалист'),
            'impactId' => $this->string()->notNull()->comment('Вид воздействия'),
            'unitId' => $this->string()->null()->comment('Подразделение'),
            'name' => $this->string(800)->notNull()->comment('Наименование'),
            'number' => $this->string()->null()->comment('Зав. номер'),
            'inventoryNumber' => $this->string()->null()->comment('Инв. номер'),
            'deviceVerificationId' => $this->string()->null()->comment('Прибор (поверка)'),
            'deviceRepairId' => $this->string()->null()->comment('Прибор (ремонт)'),
            'orderRationingId' => $this->string()->null()->comment('Нормировка'),
            'standId' => $this->string()->null()->comment('Стенд'),
            'date' => $this->date()->notNull()->comment('Дата'),
            'year' => $this->integer()->notNull()->comment('Год'),
            'month' => $this->integer()->notNull()->comment('Месяц'),
            'norma' => $this->decimal(10, 2)->null()->comment('Н/Ч'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'status' => $this->smallInteger()->null()->comment('Статус'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->null(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-groupId-'.self::TABLE_NAME,
            $this->table,
            'groupId'
        );
        $this->addForeignKey(
            'fk-groupId-'.self::TABLE_NAME,
            $this->table,
            'groupId',
            $this->group,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-typeOrder-'.self::TABLE_NAME,
            $this->table,
            'typeOrder'
        );
        $this->createIndex(
            'idx-orderId-'.self::TABLE_NAME,
            $this->table,
            'orderId'
        );
        $this->addForeignKey(
            'fk-orderId-'.self::TABLE_NAME,
            $this->table,
            'orderId',
            $this->orders,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-personalId-'.self::TABLE_NAME,
            $this->table,
            'personalId'
        );
        $this->addForeignKey(
            'fk-personalId-'.self::TABLE_NAME,
            $this->table,
            'personalId',
            $this->personal,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-impactId-'.self::TABLE_NAME,
            $this->table,
            'impactId'
        );
        $this->addForeignKey(
            'fk-impactId-'.self::TABLE_NAME,
            $this->table,
            'impactId',
            $this->impact,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-deviceVerificationId-'.self::TABLE_NAME,
            $this->table,
            'deviceVerificationId'
        );
        $this->addForeignKey(
            'fk-deviceVerificationId-'.self::TABLE_NAME,
            $this->table,
            'deviceVerificationId',
            $this->device,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-deviceRepairId-'.self::TABLE_NAME,
            $this->table,
            'deviceRepairId'
        );
        $this->addForeignKey(
            'fk-deviceRepairId-'.self::TABLE_NAME,
            $this->table,
            'deviceRepairId',
            $this->device,
            'id',
            'CASCADE'
        );
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
        $this->createIndex(
            'idx-orderRationingId-'.self::TABLE_NAME,
            $this->table,
            'orderRationingId'
        );
        $this->addForeignKey(
            'fk-orderRationingId-'.self::TABLE_NAME,
            $this->table,
            'orderRationingId',
            $this->order_rationing,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-unitId-'.self::TABLE_NAME,
            $this->table,
            'unitId'
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
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-orderId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-groupId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-personalId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-impactId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-deviceRepairId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-deviceVerificationId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-standId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-orderRationingId-'.self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-unitId-'.self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
