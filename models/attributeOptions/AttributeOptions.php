<?php

namespace app\models\attributeOptions;

use Attribute;
use Yii;

/**
 * This is the model class for table "{{%attribute_options}}".
 *
 * @property int $id
 * @property string $value
 * @property int $attribute_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Attribute $attributes0
 */
class AttributeOptions extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%attribute_options}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'attribute_id'], 'required'],
            [['attribute_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
           // [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attribute::class, 'targetAttribute' => ['attribute_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Attributes0]].
     *
     * @return \yii\db\ActiveQuery|AttributeQuery
     */
    public function getAttributes0()
    {
        return $this->hasOne(Attribute::class, ['id' => 'attribute_id']);
    }

    /**
     * {@inheritdoc}
     * @return AttributeOptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttributeOptionsQuery(get_called_class());
    }

}
