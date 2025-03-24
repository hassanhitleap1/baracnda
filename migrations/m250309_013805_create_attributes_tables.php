<?php

use yii\db\Migration;

class m250309_013805_create_attributes_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Attributes Table
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('attributes', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type'=>'ENUM("color","select","text","number","checkbox","dropdown")',
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->insert('attributes', [
            'name' => 'Color',
            'type' => 'color',
        ]);

        $this->insert('attributes', [
            'name' => 'Size',
            'type' => 'select',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('attributes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_013805_create_attributes_tables cannot be reverted.\n";

        return false;
    }
    */
}
