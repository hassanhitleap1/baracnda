<?php

namespace app\models\images;

use Yii;

/**
 * This is the model class for table "{{%images}}".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $variant_id
 * @property string $image_path
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Product $products
 * @property Variant $variants
 */
class Images extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%images}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'variant_id'], 'default', 'value' => null],
            [['product_id', 'variant_id'], 'integer'],
            [['image_path'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['image_path'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['variant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variant::class, 'targetAttribute' => ['variant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'variant_id' => Yii::t('app', 'Variant ID'),
            'image_path' => Yii::t('app', 'Image Path'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Variants]].
     *
     * @return \yii\db\ActiveQuery|VariantQuery
     */
    public function getVariants()
    {
        return $this->hasOne(Variant::class, ['id' => 'variant_id']);
    }

    /**
     * {@inheritdoc}
     * @return ImagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ImagesQuery(get_called_class());
    }

}
