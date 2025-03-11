<?php

namespace app\models\shippings;

/**
 * This is the ActiveQuery class for [[Shippings]].
 *
 * @see Shippings
 */
class ShippingsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Shippings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Shippings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
