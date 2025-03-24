<?php

use yii\db\Migration;

class m250310_141626_create_opattribute_option_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('attribute_options', [
            'id' => $this->primaryKey(),
            'value'=>$this->string()->notNull(),
            'attribute_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey('fk_attribute_options_attribute', 'attribute_options', 'attribute_id', 'attributes', 'id', 'CASCADE');
    
        $this->insert('attribute_options', [
            'value' => 'Red',
            'attribute_id' => 1,
        ]);

        $this->insert('attribute_options', [
            'value' => 'Blue',
            'attribute_id' => 1,
        ]);

        $this->insert('attribute_options', [
            'value' => 'Yellow',
            'attribute_id' => 1,
        ]);
        $this->insert('attribute_options', [
            'value' => 'Green',
            'attribute_id' => 1,
        ]);
        $this->insert('attribute_options', [
            'value' => 'Small',
            'attribute_id' => 2,
        ]);
        $this->insert('attribute_options', [
            'value' => 'Medium',
            'attribute_id' => 2,
        ]);
        $this->insert('attribute_options', [
            'value' => 'Large',
            'attribute_id' => 2,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('attribute_options');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250310_141626_create_opattribute_option_tables cannot be reverted.\n";

        return false;
    }
    */
}
