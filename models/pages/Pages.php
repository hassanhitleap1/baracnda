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

    public $file;
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

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
            [['title', 'desc', 'image'], 'default', 'value' => null,'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['key'], 'required','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['desc'], 'string','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['created_at', 'updated_at'], 'safe','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['key', 'title', 'image'], 'string', 'max' => 255,'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['key'], 'unique','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['file'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg,gif,webp,webm', 'maxFiles' => 20, 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],

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
