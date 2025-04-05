<?php

use yii\db\Migration;

class m250309_004101_create_orders_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Orders Table
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'creator_id' => $this->integer()->null(),
            'address_id' => $this->integer()->null(),
            'status_id' => $this->integer()->notNull()->defaultValue(1), // pending, completed, canceled, shipped, refunded
            'total' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'shopping_price' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'sub_total' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'profit' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'discount' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'shipping_id'=>$this->integer()->notNull()->defaultValue(1),
            'payment_id'=>$this->integer()->notNull()->defaultValue(1),
            'note' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk_orders_user', 'orders', 'user_id', 'users', 'id', 'CASCADE');
        $this->addForeignKey('fk_orders_creator', 'orders', 'creator_id', 'users', 'id', 'CASCADE');
        $this->addForeignKey('fk_orders_address', 'orders', 'address_id', 'addresses', 'id', 'CASCADE');
        $this->addForeignKey('fk_status', 'orders', 'status_id', 'status', 'id', 'CASCADE');
        $this->addForeignKey('fk_shipping', 'orders', 'shipping_id', 'shippings', 'id', 'CASCADE');
        $this->addForeignKey('fk_payment', 'orders', 'payment_id', 'payments', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250309_004101_create_orders_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_004101_create_orders_tables cannot be reverted.\n";

        return false;
    }
    */
}
