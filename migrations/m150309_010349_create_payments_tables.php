<?php

use yii\db\Migration;

class m150309_010349_create_payments_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Payments Table
        $this->createTable('payments', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'module' => $this->string()->notNull(), // e.g., 'Cash', 'CreditCard', 'PayPal'
            'status' => $this->integer()->notNull()->defaultValue(1), // 1: active, 0: inactive
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        Yii::$app->db->createCommand()->insert('payments', [
            'name' => 'Cash on Delivery',
            'module' => 'CashOnDelivery',
            'status' => 1,
        ])->execute();
        
        Yii::$app->db->createCommand()->insert('payments', [
            'name' => 'Bank Transfer',
            'module' => 'BankTransfer',
            'status' => 1,
        ])->execute();

        Yii::$app->db->createCommand()->insert('payments', [
            'name' => 'Credit Card',
            'module' => 'CreditCard',
            'status' => 1,
        ])->execute();
        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250401_013125_create_payments_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250401_013125_create_payments_tables cannot be reverted.\n";

        return false;
    }
    */
}
