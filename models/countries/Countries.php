<?php

namespace app\models\countries;

use Yii;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property int $id
 * @property string|null $country_code
 * @property string $name_en
 * @property string $name_ar
 * @property string|null $nationality_en
 * @property string|null $nationality_ar
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Region[] $regions
 */
class Countries extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_code', 'nationality_en', 'nationality_ar'], 'default', 'value' => null],
            [['name_en', 'name_ar'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['country_code'], 'string', 'max' => 5],
            [['name_en', 'name_ar', 'nationality_en', 'nationality_ar'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'country_code' => Yii::t('app', 'Country Code'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_ar' => Yii::t('app', 'Name Ar'),
            'nationality_en' => Yii::t('app', 'Nationality En'),
            'nationality_ar' => Yii::t('app', 'Nationality Ar'),
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
        return $this->hasMany(Region::class, ['country_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CountriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountriesQuery(get_called_class());
    }

}
