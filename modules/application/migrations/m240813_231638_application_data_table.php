<?php

use yii\db\Migration;

/**
 * Class m240813_231638_application_data_table
 */
class m240813_231638_application_data_table extends Migration
{
    public const TABLE_NAME = 'application_data';

    private string $table = '{{%' . self::TABLE_NAME . '}}';
    private string $application = '{{%application}}';
    private string $resource = '{{%resource}}';

    /**
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->string()->unique()->comment('ID'),
            'applicationId' => $this->string()->notNull()->comment('Заявка'),
            'resourceId' => $this->string()->notNull()->comment('Ресурс'),
            'quantity' => $this->decimal(10, 3)->null()->comment('Кол-во'),
            'mark' => $this->smallInteger()->null()->comment('ЗИП/Материалы'),
            'type' => $this->smallInteger()->null()->comment('100% замена/По дефектации/ЗИП-0'),
            'comment' => $this->text()->null()->comment('Комментарии'),
            'deliveryTime' => $this->date()->null()->comment('Срок поставки'),
            'quantityReceipt' => $this->decimal(10, 3)->null()->comment('Кол-во получено'),
            'receiptDate' => $this->date()->null()->comment('Дата получения'),
            'designation' => $this->json()->null()->comment('Обозначение по учету'),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->null(),
        ], Yii::$app->params['tableOptions']);
        $this->addPrimaryKey('id_pk_' . self::TABLE_NAME, $this->table, ['id']);
        $this->createIndex(
            'idx-applicationId-' . self::TABLE_NAME,
            $this->table,
            'applicationId'
        );
        $this->addForeignKey(
            'fk-applicationId-' . self::TABLE_NAME,
            $this->table,
            'applicationId',
            $this->application,
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-resourceId-' . self::TABLE_NAME,
            $this->table,
            'resourceId'
        );
        $this->addForeignKey(
            'fk-resourceId-' . self::TABLE_NAME,
            $this->table,
            'resourceId',
            $this->resource,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-applicationId-' . self::TABLE_NAME, $this->table);
        $this->dropForeignKey('fk-resourceId-' . self::TABLE_NAME, $this->table);
        $this->dropTable($this->table);
    }
}
