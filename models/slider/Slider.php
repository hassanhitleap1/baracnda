<?php

namespace app\models\slider;

use Yii;

/**
 * This is the model class for table "{{%slider}}".
 *
 * @property int $id
 * @property string $src
 * @property string|null $title
 * @property string|null $sub_title
 * @property string|null $text
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Slider extends \yii\db\ActiveRecord
{

    public $file;
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%slider}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'sub_title', 'text'], 'default', 'value' => null ,'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['src'], 'required','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['created_at', 'updated_at'], 'safe', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['src', 'title', 'sub_title', 'text'], 'string', 'max' => 255],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif','webm','webp'], 'maxFiles' => 20, 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'src' => Yii::t('app', 'Src'),
            'title' => Yii::t('app', 'Title'),
            'sub_title' => Yii::t('app', 'Sub Title'),
            'text' => Yii::t('app', 'Text'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SliderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SliderQuery(get_called_class());
    }

}
