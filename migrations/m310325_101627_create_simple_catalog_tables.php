<?php

use yii\db\Migration;

/**
 * Class m220325_101627_create_catalog_tables
 */
class m310325_101627_create_simple_catalog_tables extends Migration
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

        $this->createTable('{{%low_catalog}}', [
            'id'           => $this->primaryKey(),
            'parent_id'    => $this->integer(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'title'        => $this->string()->notNull()->unique(),
            'alias'        => $this->string()->notNull()->unique(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%low_category}}', [
            'id'           => $this->primaryKey(),
            'parent_id'    => $this->integer(),
            'status'       => $this->smallInteger()->notNull()->defaultValue(10),
            'title'        => $this->string()->notNull(),
            'alias'        => $this->string()->notNull()->unique(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%low_product}}', [
            'id'                => $this->primaryKey(),
            'status'            => $this->smallInteger()->notNull()->defaultValue(10),
            'title'             => $this->string()->notNull(),
            'alias'             => $this->string()->notNull()->unique(),
            'description'       => $this->text(),
            'description_short' => $this->text(),
            'meta_title'        => $this->string(),
            'meta_description'  => $this->text(),
            'price'             => $this->double(2),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
            'created_user'      => $this->integer()->notNull(),
            'updated_user'      => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%low_category2catalog}}', [
            'id'           => $this->primaryKey(),
            'catalog_id'   => $this->integer()->notNull(),
            'category_id'  => $this->integer()->notNull(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%low_product2category}}', [
            'id'           => $this->primaryKey(),
            'category_id'  => $this->integer()->notNull(),
            'product_id'   => $this->integer()->notNull(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'updated_user' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%low_property_value}}', [
            'id'           => $this->primaryKey(),
            'product_id'     => $this->integer()->notNull(),
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
        $this->dropTable('{{%low_property_value}}');
        $this->dropTable('{{%low_product2category}}');
        $this->dropTable('{{%low_category2catalog}}');
        $this->dropTable('{{%low_product}}');
        $this->dropTable('{{%low_category}}');
        $this->dropTable('{{%low_catalog}}');
    }
}
