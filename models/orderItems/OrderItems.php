<?php

namespace app\models\orderItems;

use app\models\orders\Orders;
use app\models\products\Products;
use app\models\variants\Variants;
use Yii;

/**
 * This is the model class for table "{{%order_items}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $variant_id
 * @property int $quantity
 * @property float $price
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Order $orders
 * @property Product $products
 * @property Variant $variants
 */
class OrderItems extends \yii\db\ActiveRecord
{


    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity'], 'default', 'value' => 1],
            [['order_id', 'product_id', 'variant_id', 'price'], 'required'],
            [['order_id', 'product_id', 'variant_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['variant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variants::class, 'targetAttribute' => ['variant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'variant_id' => Yii::t('app', 'Variant ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Orders::class, ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Variants]].
     *
     * @return \yii\db\ActiveQuery|VariantQuery
     */
    public function getVariant()
    {
        return $this->hasOne(Variants::class, ['id' => 'variant_id']);
    }

    /**
     * {@inheritdoc}
     * @return OrderItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderItemsQuery(get_called_class());
    }

}
