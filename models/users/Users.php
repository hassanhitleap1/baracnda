<?php

namespace app\models\users;

use app\models\addresses\Addresses;
use app\models\orders\Orders;
use app\models\products\Products;
use Yii;
use yii\rbac\Role;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $email
 * @property string $phone
 * @property string $password_hash
 * @property string|null $auth_key
 * @property string|null $full_name
 * @property string|null $birth_date
 * @property int $role_id
 * @property int|null $address_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Address $address
 * @property Order[] $orders
 * @property Order[] $orders0
 * @property Product[] $products
 * @property Role $role
 */
class Users extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const ROLE_SUPER_ADMIN = 1;
    const ROLE_MANAGER = 2;
    const ROLE_CLIENT = 3;
    const ROLE_SELLER = 4;
    const ROLE_GUEST = 5;

    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'auth_key', 'full_name', 'birth_date', 'address_id'], 'default', 'value' => null],
            [['phone', 'role_id'], 'required'],
            [['birth_date', 'created_at', 'updated_at'], 'safe'],
            [['role_id', 'address_id'], 'integer'],
            [['username', 'email', 'password_hash', 'full_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['auth_key'], 'string', 'max' => 32],
            [['phone'], 'unique'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            // [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Addresses::class, 'targetAttribute' => ['address_id' => 'id']],
            // [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'full_name' => Yii::t('app', 'Full Name'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'role_id' => Yii::t('app', 'Role ID'),
            'address_id' => Yii::t('app', 'Address ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Address]].
     *
     * @return \yii\db\ActiveQuery|AddressQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Addresses::class, ['id' => 'address_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['creator_id' => 'id']);
    }

    /**
     * Gets query for [[Orders0]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrders0()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['creator_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery|RoleQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * Checks if the user has a specific role.
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->id);
        return isset($roles[$roleName]);
    }

    /**
     * {@inheritdoc}
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }


    public function beforeSave($insert)
    {
        
        $this->auth_key = Yii::$app->security->generateRandomString();
        if($this->password){
            $this->password_hash  = Yii::$app->security->generatePasswordHash($this->password);
        }else{
            $this->password_hash = Yii::$app->security->generatePasswordHash("123456789");
        }

        return parent::beforeSave($insert);;
    }
        
}

