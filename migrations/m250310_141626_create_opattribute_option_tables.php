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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250310_141626_create_opattribute_option_tables cannot be reverted.\n";

        return false;
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
