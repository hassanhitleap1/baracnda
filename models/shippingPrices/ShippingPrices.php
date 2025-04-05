<?php

namespace app\models\shippingPrices;

use app\models\regions\Regions;
use app\models\shippings\Shippings;
use Yii;

/**
 * This is the model class for table "{{%shipping_prices}}".
 *
 * @property int $id
 * @property int $shipping_id
 * @property int $region_id
 * @property float $price
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Region $regions
 * @property Shipping $shippings
 */
class ShippingPrices extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shipping_prices}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipping_id', 'region_id', 'price'], 'required'],
            [['shipping_id', 'region_id'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::class, 'targetAttribute' => ['region_id' => 'id']],
            [['shipping_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shippings::class, 'targetAttribute' => ['shipping_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shipping_id' => Yii::t('app', 'Shipping ID'),
            'region_id' => Yii::t('app', 'Region ID'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Regions]].
     *
     * @return \yii\db\ActiveQuery|RegionQuery
     */
    public function getRegions()
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }

    /**
     * Gets query for [[Shippings]].
     *
     * @return \yii\db\ActiveQuery|ShippingQuery
     */
    public function getShippings()
    {
        return $this->hasOne(Shipping::class, ['id' => 'shipping_id']);
    }

    /**
     * {@inheritdoc}
     * @return ShippingPricesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShippingPricesQuery(get_called_class());
    }

}
