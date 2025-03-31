<?php

use yii\db\Migration;

class m250309_012312_create_images_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('images', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->null(),
            'variant_id' => $this->integer()->null(),
            'image_path' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // $this->addForeignKey('fk_images_product', 'images', 'product_id', 'products', 'id', 'CASCADE');
        // $this->addForeignKey('fk_images_variant', 'images', 'variant_id', 'variants', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('products');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_012312_create_images_tables cannot be reverted.\n";

        return false;
    }
    */
}
