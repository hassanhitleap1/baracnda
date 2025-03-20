<?php

namespace app\models\variantAttributes;

use app\models\attributes\Attributes;
use app\models\variants\Variants;
use Yii;

/**
 * This is the model class for table "{{%variant_attributes}}".
 *
 * @property int $id
 * @property int $is_default
 * @property int $variant_id
 * @property int $attribute_id
 * @property int $option_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Attribute $attribute0
 * @property Variant $variant
 */
class VariantAttributes extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%variant_attributes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_default'], 'default', 'value' => 0],
            [['is_default', 'variant_id', 'attribute_id', 'option_id'], 'integer'],
            [['variant_id', 'attribute_id', 'option_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attributes::class, 'targetAttribute' => ['attribute_id' => 'id']],
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
            'is_default' => Yii::t('app', 'Is Default'),
            'variant_id' => Yii::t('app', 'Variant ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'option_id' => Yii::t('app', 'Option ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Attribute0]].
     *
     * @return \yii\db\ActiveQuery|AttributeQuery
     */
    public function getAttribute0()
    {
        return $this->hasOne(Attributes::class, ['id' => 'attribute_id']);
    }

    /**
     * Gets query for [[Variant]].
     *
     * @return \yii\db\ActiveQuery|VariantQuery
     */
    public function getVariant()
    {
        return $this->hasOne(Variants::class, ['id' => 'variant_id']);
    }

    /**
     * {@inheritdoc}
     * @return VariantAttributesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VariantAttributesQuery(get_called_class());
    }

}
