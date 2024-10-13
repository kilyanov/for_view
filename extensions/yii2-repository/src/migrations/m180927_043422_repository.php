<?php

use yii\db\Migration;

/**
 * Class m180927_043422_repository
 */
class m180927_043422_repository extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%repository}}', [
            'id' => $this->string(),
            'title' => $this->string(128)->notNull(),
            'src' => $this->string(128)->notNull(),
            'meta' => $this->json()->notNull(),
            'createdAt' => $this->dateTime()->null()->defaultValue(null),
            'updatedAt' => $this->dateTime()->null()->defaultValue(null),
        ], $options);
        $this->addPrimaryKey('id_pk_repository', '{{%repository}}', ['id']);
        $this->createIndex('idx-src', '{{%repository}}', ['src']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%repository}}');
    }
}
