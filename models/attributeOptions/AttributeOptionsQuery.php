<?php

namespace app\models\attributeOptions;

/**
 * This is the ActiveQuery class for [[AttributeOptions]].
 *
 * @see AttributeOptions
 */
class AttributeOptionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AttributeOptions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AttributeOptions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
