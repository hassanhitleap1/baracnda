<?php

namespace app\models\regions;

/**
 * This is the ActiveQuery class for [[Regions]].
 *
 * @see Regions
 */
class RegionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Regions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Regions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
