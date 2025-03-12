<?php

namespace app\models\categories;

use Yii;

/**
 * This is the model class for table "{{%categories}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $category_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Categories $categories
 * @property Categories[] $categories0
 * @property Product[] $products
 */
class Categories extends \yii\db\ActiveRecord
{

    public $file;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id'], 'default', 'value' => null,'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['name'], 'required','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['category_id'], 'integer','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['created_at', 'updated_at'], 'safe','on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['name'], 'string', 'max' => 255,'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id'] ,'on' => [self::SCENARIO_UPDATE, self::SCENARIO_CREATE]],
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
            'name' => Yii::t('app', 'Name'),
            'category_id' => Yii::t('app', 'Category ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery|CategoriesQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Categories0]].
     *
     * @return \yii\db\ActiveQuery|CategoriesQuery
     */
    public function getCategories0()
    {
        return $this->hasMany(Categories::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriesQuery(get_called_class());
    }

}
