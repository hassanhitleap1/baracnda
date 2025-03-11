<?php

namespace app\models\pages;

use Yii;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property int $id
 * @property string $key
 * @property string|null $title
 * @property string|null $desc
 * @property string|null $image
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Pages extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'desc', 'image'], 'default', 'value' => null],
            [['key'], 'required'],
            [['desc'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'title', 'image'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'title' => Yii::t('app', 'Title'),
            'desc' => Yii::t('app', 'Desc'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PagesQuery(get_called_class());
    }

}
