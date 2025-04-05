<?php

namespace app\models\addresses;

use app\models\countries\Countries;
use app\models\orders\Orders;
use app\models\regions\Regions;
use app\models\users\Users;
use Yii;

/**
 * This is the model class for table "{{%addresses}}".
 *
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property string $full_name
 * @property string $phone
 * @property string $address
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Order[] $orders
 * @property User[] $users
 */
class Addresses extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addresses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id'], 'default', 'value' => 1],
            [['country_id', 'region_id'], 'integer'],
            [['full_name', 'phone', 'address'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['full_name', 'phone', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'region_id' => Yii::t('app', 'Region ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'phone' => Yii::t('app', 'Phone'),
            'address' => Yii::t('app', 'Address'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['address_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['address_id' => 'id']);
    }

    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['id' => 'country_id']);
    }
    public function getRegion()
    {
        return $this->hasOne(Regions::class, ['id' => 'region_id']);
    }

    /**
     * {@inheritdoc}
     * @return AddressesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AddressesQuery(get_called_class());
    }

}
