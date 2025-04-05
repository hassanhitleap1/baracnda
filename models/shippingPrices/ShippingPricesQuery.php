<?php

namespace app\models\shippingPrices;

/**
 * This is the ActiveQuery class for [[ShippingPrices]].
 *
 * @see ShippingPrices
 */
class ShippingPricesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ShippingPrices[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ShippingPrices|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
