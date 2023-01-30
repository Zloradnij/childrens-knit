<?php

use yii\db\Migration;

/**
 * Class m220413_101627_create_image_table
 */
class m220413_101627_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%image}}', [
            'id'           => $this->primaryKey(),
            'object_id'    => $this->integer()->notNull(),
            'object_type'  => $this->integer()->notNull(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'path'         => $this->string(),
            'title'        => $this->string(),
            'sort'         => $this->integer(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%image}}');
    }
}
