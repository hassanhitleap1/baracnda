<?php

use yii\db\Migration;

class m250309_003912_create_addresses_tables extends Migration
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

        $this->createTable('addresses', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer()->notNull()->defaultValue(1),
            'region_id' => $this->integer()->notNull()->defaultValue(1),
            'full_name' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('addresses');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_004030_create_addresses_tables cannot be reverted.\n";

        return false;
    }
    */
}
