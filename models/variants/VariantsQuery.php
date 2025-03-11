<?php

namespace app\models\variants;

/**
 * This is the ActiveQuery class for [[Variants]].
 *
 * @see Variants
 */
class VariantsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Variants[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Variants|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
