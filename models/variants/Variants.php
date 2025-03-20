<?php

namespace app\models\variants;

use app\models\images\Images;
use app\models\orderItems\OrderItems;
use app\models\products\Products;
use app\models\variantAttributes\VariantAttributes;
use Yii;

/**
 * This is the model class for table "{{%variants}}".
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property float $price
 * @property int $quantity
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Image[] $images
 * @property OrderItem[] $orderItems
 * @property Product $products
 * @property VariantAttribute[] $variantAttributes
 */
class Variants extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%variants}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity'], 'default', 'value' => 1],
            [['product_id', 'name', 'price'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['price','cost'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'name' => Yii::t('app', 'Name'),
            'price' => Yii::t('app', 'Price'),
            'cost' => Yii::t('app', 'cost'),
            'quantity' => Yii::t('app', 'Quantity'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery|ImageQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::class, ['variant_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['variant_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[VariantAttributes]].
     *
     * @return \yii\db\ActiveQuery|VariantAttributeQuery
     */
    public function getVariantAttributes()
    {
        return $this->hasMany(VariantAttributes::class, ['variant_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return VariantsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VariantsQuery(get_called_class());
    }

}
