<?php

namespace app\models\orders;

use Yii;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $creator_id
 * @property int $address_id
 * @property int $status_id
 * @property float $total
 * @property float $shopping_price
 * @property float $sub_total
 * @property float $profit
 * @property float $discount
 * @property int $shipping_id
 * @property string|null $note
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Address $addresses
 * @property OrderItem[] $orderItems
 * @property Shipping $shippings
 * @property Status $status
 * @property User $users
 * @property User $users0
 */
class Orders extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'note'], 'default', 'value' => null],
            [['shipping_id'], 'default', 'value' => 1],
            [['discount'], 'default', 'value' => 0.00],
            [['user_id', 'creator_id', 'address_id', 'status_id', 'shipping_id'], 'integer'],
            [['creator_id', 'address_id'], 'required'],
            [['total', 'shopping_price', 'sub_total', 'profit', 'discount'], 'number'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::class, 'targetAttribute' => ['address_id' => 'id']],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['shipping_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipping::class, 'targetAttribute' => ['shipping_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'creator_id' => Yii::t('app', 'Creator ID'),
            'address_id' => Yii::t('app', 'Address ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'total' => Yii::t('app', 'Total'),
            'shopping_price' => Yii::t('app', 'Shopping Price'),
            'sub_total' => Yii::t('app', 'Sub Total'),
            'profit' => Yii::t('app', 'Profit'),
            'discount' => Yii::t('app', 'Discount'),
            'shipping_id' => Yii::t('app', 'Shipping ID'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Addresses]].
     *
     * @return \yii\db\ActiveQuery|AddressQuery
     */
    public function getAddresses()
    {
        return $this->hasOne(Address::class, ['id' => 'address_id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Shippings]].
     *
     * @return \yii\db\ActiveQuery|ShippingQuery
     */
    public function getShippings()
    {
        return $this->hasOne(Shipping::class, ['id' => 'shipping_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery|StatusQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * Gets query for [[Users0]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers0()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrdersQuery(get_called_class());
    }

}
