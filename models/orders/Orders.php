<?php

namespace app\models\orders;

use app\models\addresses\Addresses;
use app\models\orderItems\OrderItems;
use app\models\shippingPrices\ShippingPrices;
use app\models\shippings\Shippings;
use app\models\status\Status;
use app\models\users\Users;
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
 * @property float $shipping_price
 * @property float $sub_total
 * @property float $profit
 * @property float $discount
 * @property int $shipping_id
 * @property int $payment_id
 * @property int $region_id
 * @property int $country_id
 * @property string $full_name
 * @property string $phone
 * @property string $delivery_status
 * @property string $address
 * @property string $status_order
 * @property string|null $note
 * @property string|null $status_order
 * @property string $delivery_status
 * @property string $payment_status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property Addresses $addresses
 * @property OrderItems[] $orderItems
 * @property Shippings $shippings
 * @property Status $status
 * @property Users $creator
 * @property Users $user
 */
class Orders extends \yii\db\ActiveRecord
{
  

    public $country_id = null;

    public $region_id ;

    public $full_name = null;

    public $phone=  null;

    public $address = null;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const STATUS_RESERVED = 'reserved';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REFUNDED = 'refunded';

    const DELIVERY_PENDING = 'pending';
    const DELIVERY_DELIVERED = 'delivered';

    const DELIVERY_REFUNDED = 'refunded';


    /**
     * @var int|null Virtual property for country ID
     */

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
            [['phone','full_name','region_id'],'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['user_id', 'note'], 'default', 'value' => null, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['shipping_id'], 'default', 'value' => 1, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [[ 'status_id', 'shipping_id','creator_id'], 'integer', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['address'], 'string', 'max' => 255, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['total', 'shipping_price', 'sub_total', 'profit', 'discount'], 'number', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['discount'], 'default', 'value' => 0.00, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
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
            'shipping_price' => Yii::t('app', 'Shopping Price'),
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
        return $this->hasOne(Addresses::class, ['id' => 'address_id']);
    }


    public function setCreator()
    {
        if (\Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'super-admin')
        || \Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager')
        || \Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'dataEntry')) {
            $this->creator_id = $this->creator_id;   
        }else {
        $this->creator_id = Yii::$app->user->id;
        }
        return true;
    }

    /**
     * Set the address for the order.
     * If the order is new, it will create a new address.
     * If the order is not new, it will find the address by id.
     * If the address is not found, it will flash an error message.
     * @return bool Whether the address was successfully set
     */
    public function setAddress()
    {
        $address = null;
        if($this->isNewRecord){
            $address = new Addresses();
            $address->country_id = 1;
            $address->region_id = $this->region_id;
            $address->full_name = $this->full_name;
            $address->address = $this->address;
            $address->phone = $this->phone;
            if (!$address->save()) {
                Yii::$app->session->setFlash('error', $address->getFirstErrors());
                return false;
            }
        }else{
            $address = Addresses::findOne($this->address_id);
            if (!$address) {
                Yii::$app->session->setFlash('error', 'Address not found');
                return false;
            }
        }

        $this->address_id = $address->id;
        return true;
    }


    public function setProfit()
    {
        $this->profit = 0;
        foreach ($this->orderItems as $item) {
            $product = $item->products;
            if ($product) {
                $cost = $product->cost * $item->quantity;
                $revenue = $item->price * $item->quantity;
                $this->profit += $revenue - $cost;
            }
        }
        return true;
    }

    public function setShippingPrice()
    {
        $shipping= ShippingPrices::find()->where(['shipping_id' => $this->shipping_id,'region_id' => $this->region_id])->one();
        if (!$shipping) {
            
            Yii::$app->session->setFlash('error', 'Shipping not found');
            return false;
        }

        $this->shipping_price = (float) $shipping->price ?? 0;

        return true;
      
    }


    public function addItem($item)
    {
        $orderItem = new OrderItems();
        $orderItem->order_id = $this->id;
        $orderItem->product_id = $item->product_id;
        $orderItem->quantity = $item->variant_quantity;
        $orderItem->variant_id = $item->variant_id;
        $orderItem->price = $item->variant_price;
        $orderItem->cost = $item->variant_cost ?? 0 ;
    
        if (!$orderItem->save(false)) {
            Yii::$app->session->setFlash('error', $orderItem->getFirstErrors());
            return false;
        }

        return true;
    }
    
    public function setUser()
    {

         $user =  Users::findOne(['phone' => $this->phone]);
        if($this->isNewRecord && !$user){
            $user = new Users();
            $user->full_name = $this->full_name;
            $user->phone = $this->phone;
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->phone);
            $user->auth_key = Yii::$app->security->generateRandomString();
            if (!$user->save(false)) {
                Yii::$app->session->setFlash('error', $user->getFirstErrors());
                return false;
            } 
        }else{
            $user = Users::findOne($this->user_id);
            if (!$user) {
                Yii::$app->session->setFlash('error', 'Address not found');
                return false;
            }
        }

        $this->user_id   = $user->id;
        return true;
    }
    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Shippings]].
     *
     * @return \yii\db\ActiveQuery|ShippingsQuery
     */
    public function getShipping()
    {
        return $this->hasOne(Shippings::class, ['id' => 'shipping_id']);
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
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Users::class, ['id' => 'creator_id']);
    }

    /**
     * Gets query for [[Users0]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

  
  
    /**
     * {@inheritdoc}
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new OrdersQuery(get_called_class());
        return $query; // Deny access by default
    }


    public function calculateTotal(){

        $this->total = (float) $this->sub_total + (float) $this->shipping_price - (float) $this->discount;
        return $this->total ;
    }


    public function calculateSubTotal($items){
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (int) $item['variant_quantity'] * (float) $item['variant_price'];
        }

        return $this->sub_total =  $subtotal;
    }

    public function calculateSubTotalFromOrderItems(){
        $subtotal = 0;
        foreach ($this->orderItems as $item) {
            $subtotal += $item->quantity * $item->price;
        }
        return $this->sub_total =  $subtotal;

    }
  
    public function calculateProfit(){
        $cost = 0;
        foreach ($this->orderItems as $item) {
            $product = $item->product;
            if ($product) {
                $cost += $product->cost * $item->quantity;
            }
        }
        $cost = (float) $cost  +  $this->shipping_price - $this->discount;
        $this->profit = (float) $this->total - $cost;
        return $this->profit;
    }

    public function beforeSave($insert)
    {

        parent::beforeSave($insert);
        $this->calculateProfit();
        if ($this->isNewRecord) {
            $this->status_order = SELF::STATUS_RESERVED;
        }else{
          switch ($this->delivery_status) {
                case self::DELIVERY_DELIVERED :
                    $this->status_order = SELF::STATUS_COMPLETED;
                    break;
                case self::DELIVERY_REFUNDED :
                    $this->status_order = SELF::STATUS_CANCELED;
                    break;
            }
        }


        return true;
    }
}
