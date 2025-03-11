<?php

namespace app\models\contactus;

use Yii;

/**
 * This is the model class for table "{{%contactus}}".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $body
 * @property int|null $readed
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Contactus extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contactus}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['readed'], 'default', 'value' => 0],
            [['name', 'email', 'subject', 'body'], 'required'],
            [['body'], 'string'],
            [['readed'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
            'readed' => Yii::t('app', 'Readed'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ContactusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactusQuery(get_called_class());
    }

}
