<?php

use yii\db\Migration;

class m250309_004112_create_order_items_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Order Items Table
        $this->createTable('order_items', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'variant_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'price' => $this->decimal(10,2)->notNull(),
            'cost' => $this->decimal(10,2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey('fk_order_items_order', 'order_items', 'order_id', 'orders', 'id', 'CASCADE');
        $this->addForeignKey('fk_order_items_product', 'order_items', 'product_id', 'products', 'id', 'CASCADE');
        $this->addForeignKey('fk_order_items_variant', 'order_items', 'variant_id', 'variants', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_items}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_004112_create_order_items_tables cannot be reverted.\n";

        return false;
    }
    */
}
