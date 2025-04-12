<?php

use yii\db\Migration;

class m250309_003948_create_products_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Products Table
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'creator_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'price' => $this->decimal(10,2)->notNull(),
            'cost' => $this->decimal(10,2)->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(0),
            'category_id' => $this->integer()->notNull()->defaultValue(1),
            'warehouse_id' => $this->integer()->notNull()->defaultValue(1),
            'sold'=>$this->integer()->notNull()->defaultValue(0),
            'image_path' => $this->string()->null(),
            'type' => "ENUM('simple', 'variant') NOT NULL DEFAULT 'simple'",
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey('fk_products_creator', 'products', 'creator_id', 'users', 'id', 'CASCADE');
        $this->addForeignKey('fk_products_category', 'products', 'category_id', 'categories', 'id', 'CASCADE');
        $this->addForeignKey('fk_products_warehouse', 'products', 'warehouse_id', 'warehouses', 'id', 'CASCADE');

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
        echo "m250309_003948_create_products_tables cannot be reverted.\n";

        return false;
    }
    */
}
