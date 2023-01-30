<?php

use yii\db\Migration;

/**
 * Class m220325_101627_create_catalog_tables
 */
class m220325_101627_create_catalog_tables extends Migration
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

        $this->createTable('{{%catalog}}', [
            'id'           => $this->primaryKey(),
            'parent_id'    => $this->integer(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'title'        => $this->string()->notNull()->unique(),
            'alias'        => $this->string()->notNull()->unique(),
            'sort'         => $this->integer(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id'           => $this->primaryKey(),
            'parent_id'    => $this->integer(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'title'        => $this->string()->notNull(),
            'alias'        => $this->string()->notNull()->unique(),
            'sort'         => $this->integer(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%product}}', [
            'id'                => $this->primaryKey(),
            'status'            => $this->smallInteger()->notNull()->defaultValue(10),
            'title'             => $this->string()->notNull(),
            'alias'             => $this->string()->notNull()->unique(),
            'description'       => $this->text(),
            'description_short' => $this->text(),
            'sort'              => $this->integer(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
            'created_user'      => $this->integer()->notNull(),
            'updated_user'      => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%category2catalog}}', [
            'id'           => $this->primaryKey(),
            'catalog_id'   => $this->integer()->notNull(),
            'category_id'  => $this->integer()->notNull(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%product2category}}', [
            'id'           => $this->primaryKey(),
            'category_id'  => $this->integer()->notNull(),
            'product_id'   => $this->integer()->notNull(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%offer}}', [
            'id'           => $this->primaryKey(),
            'product_id'   => $this->integer()->notNull(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'price'        => $this->double(2),
            'sort'         => $this->integer(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%property}}', [
            'id'               => $this->primaryKey(),
            'property_type_id' => $this->integer()->notNull(),
            'status'           => $this->smallInteger()->notNull()->defaultValue(10),
            'title'            => $this->string()->notNull(),
            'alias'            => $this->string()->notNull()->unique(),
            'sort'             => $this->integer(),
            'created_at'       => $this->integer()->notNull(),
            'updated_at'       => $this->integer()->notNull(),
            'created_user'     => $this->integer()->notNull(),
            'updated_user'     => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%property_type}}', [
            'id'           => $this->primaryKey(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'title'        => $this->string()->notNull()->unique(),
            'alias'        => $this->string()->notNull()->unique(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%property_value}}', [
            'id'           => $this->primaryKey(),
            'offer_id'     => $this->integer()->notNull(),
            'property_id'  => $this->integer()->notNull(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'value'        => $this->string()->notNull(),
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
        $this->dropTable('{{%property_value}}');
        $this->dropTable('{{%property}}');
        $this->dropTable('{{%property_type}}');
        $this->dropTable('{{%offer}}');
        $this->dropTable('{{%product2category}}');
        $this->dropTable('{{%category2catalog}}');
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%catalog}}');
    }
}
