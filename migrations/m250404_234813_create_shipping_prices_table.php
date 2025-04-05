<?php

use app\models\regions\Regions;
use app\models\shippings\Shippings;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%shipping_prices}}`.
 */
class m250404_234813_create_shipping_prices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipping_prices}}', [
            'id' => $this->primaryKey(),
            'shipping_id' => $this->integer()->notNull(),
            'region_id' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            
        ]);

        $this->addForeignKey(
            'fk-shipping_prices-shipping_id',
            '{{%shipping_prices}}',
            'shipping_id',
            '{{%shippings}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-shipping_prices-region_id',
            '{{%shipping_prices}}',
            'region_id',
            '{{%regions}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $regions = Regions::find()->all();
        $shippingPrices = [];
        $shippings=Shippings::find()->all();
        foreach ($shippings as $shipping) {
            foreach ($regions as $region) {
                $shippingPrices[] = [
                    'shipping_id' => $shipping->id, // Assuming 1 is the default shipping ID
                    'region_id' => $region->id,
                    'price' => 3, // Default price
                ];
            }
        }

        Yii::$app->db->createCommand()
            ->batchInsert(
                '{{%shipping_prices}}',
                ['shipping_id', 'region_id', 'price'],
                $shippingPrices
            )
            ->execute();
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipping_prices}}');
    }
}
