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
 * @property float $shopping_price
 * @property float $sub_total
 * @property float $profit
 * @property float $discount
 * @property int $shipping_id
 * @property string|null $note
 * @property string|null $created_at
 * @property string|null $updated_at
 *
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

    public $region_id = null;

    public $subtotal = 0.00;
    public $shipping = 0.00;
    public $total = 0.00;
    public $discount = 0.00;
    public $shopping_price = 0.00;
    public $profit = 0.00;
    
    public $full_name = null;

    public $phone=  null;

    public $address = null;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

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
            [['discount'], 'default', 'value' => 0.00, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [[ 'status_id', 'shipping_id'], 'integer', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['address'], 'string', 'max' => 255, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],


            // [['total', 'shopping_price', 'sub_total', 'profit', 'discount'], 'number', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['note'], 'string', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['created_at', 'updated_at'], 'safe', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Addresses::class, 'targetAttribute' => ['address_id' => 'id'], 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['creator_id' => 'id'], 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id'], 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['shipping_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shippings::class, 'targetAttribute' => ['shipping_id' => 'id'], 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id'], 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['country_id'], 'safe', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            // [['creator_id', 'address_id', 'user_id'], 'required', 'on' => [self::SCENARIO_UPDATE]],
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
        return $this->hasOne(Addresses::class, ['id' => 'address_id']);
    }

    public function setCreator()
    {
        $this->creator_id = Yii::$app->user->id;

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

        $this->shopping_price = $shipping->price;

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
    
        if (!$orderItem->save()) {
            Yii::$app->session->setFlash('error', $orderItem->getFirstErrors());
            return false;
        }

        return true;
    }
    
    public function setUser()
    {

         $user = null;
        if($this->isNewRecord){
            $user = new Users();
            $user->full_name = $this->full_name;
            $user->phone = $this->phone;
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->phone);
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->role_id = Users::ROLE_CLIENT;
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
    public function getShippings()
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
        return new OrdersQuery(get_called_class());
    }

    /**
     * Calculates the subtotal, shipping, and total for the order.
     */
    public function calculateTotals()
    {
        $this->subtotal = 0;

        // Load OrderItems if not already loaded
        if ($this->isNewRecord && empty($this->orderItems)) {
            $orderItemsData = Yii::$app->request->post('Orders')['OrderItems'] ?? [];
            foreach ($orderItemsData as $itemData) {
                $orderItem = new OrderItems();
                $orderItem->order_id = $this->id;
                $orderItem->product_id = $itemData['product_id'];
                $orderItem->variant_id = $itemData['variant_id'];
                $orderItem->quantity = $itemData['variant_quantity'];
                $orderItem->price = $itemData['variant_price'];
                $this->orderItems[] = $orderItem;
            }
        }

        // Calculate subtotal
        foreach ($this->orderItems as $item) {
            $this->subtotal += $item->quantity * $item->price;
        }

        $this->shipping = $this->calculateShipping(); // Custom logic for shipping
        $this->total = $this->subtotal + $this->shipping;
    }

    /**
     * Custom logic to calculate shipping cost.
     * @return float
     */
    protected function calculateShipping()
    {
        // Example: Flat rate shipping
        return 10.00;
    }

}
