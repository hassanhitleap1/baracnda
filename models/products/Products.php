<?php

namespace app\models\products;

use app\models\categories\Categories;
use app\models\images\Images;
use app\models\orderItems\OrderItems;
use app\models\orders\Orders;
use app\models\users\Users;
use app\models\variants\Variants;
use app\models\warehouses\Warehouses;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

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
    public $files;

    public $variant_name;
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const SIMPLE = 'simple';
    const VARIANT = 'variant';
    
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
            [['description', 'image_path'], 'default', 'value' => null, 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['warehouse_id','creator_id'], 'default', 'value' => 1, 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['creator_id', 'name', 'price', 'quantity','cost','type'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['creator_id', 'category_id', 'warehouse_id'], 'integer', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['description'], 'string', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['price', 'cost','quantity'], 'number', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image_path'], 'string', 'max' => 255, 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id'], 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['creator_id' => 'id'], 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouses::class, 'targetAttribute' => ['warehouse_id' => 'id'], 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            ['type', 'in', 'range' => array_keys(self::productType())],
            [['files'],'required', 'on' => [ self::SCENARIO_CREATE]],
            [['files'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg,gif,webp', 'maxFiles' => 20, 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            ['type', 'validateVariants','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
        ];
    }


    /**
     * Validates that the product type is consistent with the existence of variants.
     *
     * Simple products cannot have variants, and variant products must have at least 2 variants.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the validation parameters
     */
    public function validateVariants($attribute, $params)
    {


        $postData = Yii::$app->request->post('Product');
        $variantNames = ArrayHelper::getValue($postData, 'variant_name', []);
       
        if ($this->type === self::SIMPLE && count($variantNames) > 0) {
            $this->addError($attribute, 'Simple products cannot have variants.');
        }
       
        // if ($this->type === self::VARIANT) {
        //     // Validate variant fields
        //     $postData = Yii::$app->request->post('Product');
        //     $variantNames = ArrayHelper::getValue($postData, 'variant_name', []);
        //     $variantPrices = ArrayHelper::getValue($postData, 'variant_price', []);
        //     $variantQuantities = ArrayHelper::getValue($postData, 'variant_quantity', []);
        //     $variantCosts = ArrayHelper::getValue($postData, 'variant_cost', []);
            
        //     // Ensure at least 2 variants are provided
        //     if (count($variantNames) < 2) {
        //         $this->addError($attribute, 'Variant products must have at least 2 variants.');
        //         return false;
        //     }

        //     foreach ($variantNames as $index => $name) {
        //         $dynamicModel = DynamicModel::validateData([
        //             'name' => $name,
        //             'price' => $variantPrices[$index],
        //             'quantity' => $variantQuantities[$index],
        //             'cost' => $variantCosts[$index],
        //         ], [
        //             [
        //                 ['name', 
        //                 'price',
        //                 'quantity'
        //             ]
        //             , 'required'
        //             ],
        //             [['name'], 'string', 'max' => 255],
        //             [['price', 'quantity','cost'], 'number'],
        //         ]);
        //         if ($dynamicModel->hasErrors()) {
        //             foreach ($dynamicModel->errors as $field => $errors) {
        //                 $this->addError("variant_{$field}_{$index}", implode(', ', $errors));
                        
        //             }
        //             return false;
        //         }
        //     }
        //     return true;
        // }
    }

        /**
     * {@inheritdoc}
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        // Run default validation
        $isValid = parent::validate($attributeNames, $clearErrors);

        // Run custom validation for variants
        if ($this->type === self::VARIANT) {
            $isValid = $this->validateVariants('type', []) && $isValid;
        }

        return $isValid;
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            // 'creator_id' => Yii::t('app', 'Creator ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'price' => Yii::t('app', 'Price'),
            'cost' => Yii::t('app', 'Cost'),
            'category_id' => Yii::t('app', 'Category ID'),
            'warehouse_id' => Yii::t('app', 'Warehouse ID'),
            'image_path' => Yii::t('app', 'Image Path'), 
            'quantity' => Yii::t('app', 'Quantity'),
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
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery|ImageQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'creator_id']);
    }

    /**
     * Gets query for [[Variants]].
     *
     * @return \yii\db\ActiveQuery|VariantQuery
     */
    public function getVariants()
    {
        return $this->hasMany(Variants::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Warehouses]].
     *
     * @return \yii\db\ActiveQuery|WarehouseQuery
     */
    public function getWarehouses()
    {
        return $this->hasOne(Warehouses::class, ['id' => 'warehouse_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsQuery(get_called_class());
    }



    public static function productType()
    {
        return [
            self::SIMPLE => Yii::t('app', 'simple'),
            self::VARIANT => Yii::t('app', 'variant'),
        ];
    }

    public function getCreator()
    {
        return $this->hasOne(Users::class, ['id' => 'creator_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    public function getWarehouse()
    {
        return $this->hasOne(Warehouses::class, ['id' => 'warehouse_id']);
    }


    public function getImageUrl()
    {
        // Assuming your imageFile attribute stores the path to the image
        return \Yii::getAlias('@web/' . $this->image_path);
    }


    public function getOrderItem(){
        return $this->hasMany(OrderItems::class, ['product_id' => 'id']);
    }
}


