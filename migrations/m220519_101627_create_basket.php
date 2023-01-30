<?php

use yii\db\Migration;

/**
 * Class m220519_101627_create_basket
 */
class m220519_101627_create_basket extends Migration
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

        $this->createTable('{{%order}}', [
            'id'               => $this->primaryKey(),
            'user_id'          => $this->integer(),
            'price'            => $this->double(2),
            'pay_type_id'      => $this->smallInteger()->notNull()->defaultValue(10),
            'delivery_id'      => $this->smallInteger()->notNull()->defaultValue(10),
            'delivery_address' => $this->string(),
            'delivery_date'    => $this->integer()->notNull(),
            'promo'            => $this->string(),
            'sale'             => $this->double(2),
            'sale_type_id'     => $this->smallInteger()->defaultValue(10),
            'comment'          => $this->text(),
            'status'           => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at'       => $this->integer()->notNull(),
            'updated_at'       => $this->integer()->notNull(),
            'created_user'     => $this->integer()->notNull(),
            'updated_user'     => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%order_item}}', [
            'id'           => $this->primaryKey(),
            'order_id'     => $this->integer()->notNull(),
            'offer_id'     => $this->integer()->notNull(),
            'count'        => $this->integer()->notNull(),
            'sale'         => $this->double(2),
            'sale_type_id' => $this->smallInteger()->notNull()->defaultValue(10),
            'promo'        => $this->string(),
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
        $this->dropTable('{{%order}}');
        $this->dropTable('{{%order_item}}');
    }
}
