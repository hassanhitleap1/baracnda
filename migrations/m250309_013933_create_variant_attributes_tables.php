<?php

use yii\db\Migration;

class m250309_013933_create_variant_attributes_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Variant Attributes Table
        $this->createTable('variant_attributes', [
            'id' => $this->primaryKey(),
            'is_default'=>$this->boolean()->notNull()->defaultValue(0),
            'variant_id' => $this->integer()->notNull(),
            'attribute_id' => $this->integer()->notNull(),
            'option_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey('fk_variant_attributes_variant', 'variant_attributes', 'variant_id', 'variants', 'id', 'CASCADE');
        $this->addForeignKey('fk_variant_attributes_attribute', 'variant_attributes', 'attribute_id', 'attributes', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('variant_attributes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_013933_create_variant_attributes_tables cannot be reverted.\n";

        return false;
    }
    */
}
