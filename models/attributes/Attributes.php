<?php

namespace app\models\attributes;

use Yii;

/**
 * This is the model class for table "{{%attributes}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $type
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property AttributeOption[] $attributeOptions
 * @property VariantAttribute[] $variantAttributes
 */
class Attributes extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_COLOR = 'color';
    const TYPE_SELECT = 'select';
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_DROPDOWN = 'dropdown';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%attributes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'default', 'value' => null],
            [['name'], 'required'],
            [['type'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
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
            'type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[AttributeOptions]].
     *
     * @return \yii\db\ActiveQuery|AttributeOptionQuery
     */
    public function getAttributeOptions()
    {
        return $this->hasMany(AttributeOption::class, ['attribute_id' => 'id']);
    }

    /**
     * Gets query for [[VariantAttributes]].
     *
     * @return \yii\db\ActiveQuery|VariantAttributeQuery
     */
    public function getVariantAttributes()
    {
        return $this->hasMany(VariantAttribute::class, ['attribute_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return AttributesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttributesQuery(get_called_class());
    }


    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_COLOR => Yii::t('app', 'color'),
            self::TYPE_SELECT => Yii::t('app', 'select'),
            self::TYPE_TEXT => Yii::t('app', 'text'),
            self::TYPE_NUMBER => Yii::t('app', 'number'),
            self::TYPE_CHECKBOX => Yii::t('app', 'checkbox'),
            self::TYPE_DROPDOWN => Yii::t('app', 'dropdown'),
        ];
    }

    /**
     * @return string
     */
    public function displayType()
    {
        return self::optsType()[$this->type];
    }

    /**
     * @return bool
     */
    public function isTypeColor()
    {
        return $this->type === self::TYPE_COLOR;
    }

    public function setTypeToColor()
    {
        $this->type = self::TYPE_COLOR;
    }

    /**
     * @return bool
     */
    public function isTypeSelect()
    {
        return $this->type === self::TYPE_SELECT;
    }

    public function setTypeToSelect()
    {
        $this->type = self::TYPE_SELECT;
    }

    /**
     * @return bool
     */
    public function isTypeText()
    {
        return $this->type === self::TYPE_TEXT;
    }

    public function setTypeToText()
    {
        $this->type = self::TYPE_TEXT;
    }

    /**
     * @return bool
     */
    public function isTypeNumber()
    {
        return $this->type === self::TYPE_NUMBER;
    }

    public function setTypeToNumber()
    {
        $this->type = self::TYPE_NUMBER;
    }

    /**
     * @return bool
     */
    public function isTypeCheckbox()
    {
        return $this->type === self::TYPE_CHECKBOX;
    }

    public function setTypeToCheckbox()
    {
        $this->type = self::TYPE_CHECKBOX;
    }

    /**
     * @return bool
     */
    public function isTypeDropdown()
    {
        return $this->type === self::TYPE_DROPDOWN;
    }

    public function setTypeToDropdown()
    {
        $this->type = self::TYPE_DROPDOWN;
    }
}
