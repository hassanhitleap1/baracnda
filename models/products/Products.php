<?php

namespace app\models\products;

use Yii;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property float $cost
 * @property int $category_id
 * @property int $warehouse_id
 * @property string|null $image_path
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Category $categories
 * @property Image[] $images
 * @property OrderItem[] $orderItems
 * @property User $users
 * @property Variant[] $variants
 * @property Warehouse $warehouses
 */
class Products extends \yii\db\ActiveRecord
{

    public $images;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'image_path'], 'default', 'value' => null],
            [['warehouse_id'], 'default', 'value' => 1],
            [['creator_id', 'name', 'price', 'cost'], 'required'],
            [['creator_id', 'category_id', 'warehouse_id'], 'integer'],
            [['description'], 'string'],
            [['price', 'cost'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image_path'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::class, 'targetAttribute' => ['warehouse_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'creator_id' => Yii::t('app', 'Creator ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'price' => Yii::t('app', 'Price'),
            'cost' => Yii::t('app', 'Cost'),
            'category_id' => Yii::t('app', 'Category ID'),
            'warehouse_id' => Yii::t('app', 'Warehouse ID'),
            'image_path' => Yii::t('app', 'Image Path'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery|ImageQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['product_id' => 'id']);
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
     * Gets query for [[Variants]].
     *
     * @return \yii\db\ActiveQuery|VariantQuery
     */
    public function getVariants()
    {
        return $this->hasMany(Variant::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Warehouses]].
     *
     * @return \yii\db\ActiveQuery|WarehouseQuery
     */
    public function getWarehouses()
    {
        return $this->hasOne(Warehouse::class, ['id' => 'warehouse_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsQuery(get_called_class());
    }

}
