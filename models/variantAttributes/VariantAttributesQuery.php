<?php

namespace app\models\variantAttributes;

/**
 * This is the ActiveQuery class for [[VariantAttributes]].
 *
 * @see VariantAttributes
 */
class VariantAttributesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return VariantAttributes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return VariantAttributes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
