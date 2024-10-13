<?php

use yii\db\Migration;

/**
 * Class m181004_061353_relation
 */
class m181004_061353_relation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%repository_relation}}', [
            'id' => $this->string(),
            'model' => $this->string(128)->notNull(),
            'identity' => $this->string()->notNull(),
            'attribute' => $this->string(64)->notNull(),
            'repositoryId' => $this->string()->notNull(),
            'createdAt' => $this->dateTime()->null()->defaultValue(null),
            'updatedAt' => $this->dateTime()->null()->defaultValue(null),
        ], $options);
        $this->addPrimaryKey('id_pk_repository_relation', '{{%repository_relation}}', ['id']);
        $this->createIndex('idx-unique', '{{%repository_relation}}', [
            'model',
            'identity',
            'attribute',
            'repositoryId',
        ], true);

        $this->addForeignKey(
            'fk-repository',
            '{{%repository_relation}}',
            'repositoryId',
            '{{%repository}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-repository', '{{%repository_relation}}');
        $this->dropTable('{{%repository_relation}}');
    }
}
