<?php

use yii\db\Migration;

class m250309_003949_create_variants_tables  extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

            // Addresses Table
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
          // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
      }
        $this->createTable('variants', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'price' => $this->decimal(10,2)->notNull(),
            'cost'=>  $this->decimal(10,2)->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey('fk_variants_product', 'variants', 'product_id', 'products', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropTable('{{%variants}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_011314_create_variants_tables cannot be reverted.\n";

        return false;
    }
    */
}
