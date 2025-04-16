<?php

namespace app\commands;

use app\models\addresses\Addresses;
use app\models\countries\Countries;
use Yii;
use yii\console\Controller;
use app\models\User;
use app\models\products\Products;
use app\models\variants\Variants;
use app\models\orders\Orders;
use app\models\payments\Payments;
use app\models\regions\Regions;
use app\models\shippings\Shippings;
use app\models\status\Status;
use app\models\users\Users;
use Faker\Factory;


class SeedController extends Controller
{
    public function actionIndex()
    {
        $faker = Factory::create();
        $this->seedUsers($faker);
        $this->seedProductsAndVariants($faker);
        $this->seedAddresses($faker);
        $this->seedOrdersAndOrderItems($faker);
        echo "Seeding completed successfully.\n";
    }

    private function seedUsers($faker)
    {
  
        try {
            $dataUser = [
                [
                'username' =>'0799263494',
                'email' => '0799263494',
                'phone' => '0799263494',
                'password_hash' => Yii::$app->security->generatePasswordHash("0799263494"),              
                'auth_key' => Yii::$app->security->generateRandomString(),
                'full_name' => "superAdmin",
                'birth_date' => null,
                'address_id' => null,
                'status' => 10
     
                ],
                [
                    'username' =>'0799263492',
                    'email' => '0799263492',
                    'phone' => '0799263492',
                    'password_hash' => Yii::$app->security->generatePasswordHash("0799263492"),              
                    'auth_key' => Yii::$app->security->generateRandomString(),
                    'full_name' => "Admin",
                    'birth_date' => null,
                    'address_id' => null,
                    'status' => 10
         
                ],
                [
                    'username' =>'0799263493',
                    'email' => '0799263493',
                    'phone' => '0799263493',
                    'password_hash' => Yii::$app->security->generatePasswordHash("0799263493"),              
                    'auth_key' => Yii::$app->security->generateRandomString(),
                    'full_name' => "Saller",
                    'birth_date' => null,
                    'address_id' => null,
                    'status' => 10
         
                ],
                [
                    'username' =>'0799263495',
                    'email' => '0799263495',
                    'phone' => '0799263495',
                    'password_hash' => Yii::$app->security->generatePasswordHash("0799263495"),              
                    'auth_key' => Yii::$app->security->generateRandomString(),
                    'full_name' => "dataEntry",
                    'birth_date' => null,
                    'address_id' => null,
                    'status' => 10
         
                ]
            ];
    
    
            Yii::$app->db
                ->createCommand()
                ->batchInsert(
                    'users',
                    [
                        'username' ,
                        'email' ,
                        'phone' ,
                        'password_hash',              
                        'auth_key' ,
                        'full_name' ,
                        'birth_date',
                        'address_id' ,
                        'status'
                    ],
                    $dataUser
                )
                ->execute();
        }catch (\Exception $e) {
            // echo $e->getMessage();
        }
   
        $dataUser = [];
        for ($i = 1; $i <= 4; $i++) {

            $dataUser [] = [
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->unique()->phoneNumber,
                'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
                'full_name' => $faker->name,
                'auth_key' => Yii::$app->security->generateRandomString(),
                'birth_date'=> null,
                'address_id' => null,
                'status' => 10,
            ];
          
        }

        Yii::$app->db
        ->createCommand()
        ->batchInsert(
            'users',
            [
                'username' ,
                'email' ,
                'phone' ,
                'password_hash',              
                'auth_key' ,
                'full_name' ,
                'birth_date',
                'address_id' ,
                'status'
            ],
            $dataUser
        )
        ->execute();

  


    }

    private function seedProductsAndVariants($faker)
    {
       
        for($i = 1; $i < 20 ; $i++) { 
            Yii::$app->db->createCommand()->insert('products', [
                'name' => $faker->word,
                'type' => Products::SIMPLE,
                'price' =>$faker->numberBetween(1, 100),
                'cost' => $faker->numberBetween(1, 100)/2,
                'quantity' => 10,
                'warehouse_id' => 1,
                'creator_id' => 1,
                'category_id' => 1,
                'description' => $faker->text(400),
                'image_path'=>"product-empty.webp",
            ])->execute();
            $productId = Yii::$app->db->getLastInsertID();
            Yii::$app->db->createCommand()->insert('variants', [
                'product_id' => $productId,
                'is_default' => 1,
                'name' =>$faker->word,
                'price' =>$faker->numberBetween(1, 100),
                'cost' => $faker->numberBetween(1, 100)/2,
                'quantity' =>$faker->numberBetween(1, 100),
            ])->execute();

        }
    
    }

    public function seedAddresses($faker)
    {
        for ($i = 1; $i <= 20; $i++) {
            Yii::$app->db->createCommand()->insert('addresses', [
                'country_id' => $faker->randomElement(Countries::find()->select('id')->limit(50)->column()),
                'region_id' => $faker->randomElement(Regions::find()->select('id')->limit(50)->column()),
                'full_name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'address' => $faker->word,
            ])->execute();

        }

    }
    private function seedOrdersAndOrderItems($faker)
    {
        $date = new \DateTime();
        for ($i = 1; $i <= 50; $i++) {

            $crated_at = $date->modify("-$i days")->format('Y-m-d H:i:s');
            Yii::$app->db->createCommand()->insert('orders', [
                'user_id' => $faker->randomElement(Users::find()->select('id')->limit(50)->column()),
                'creator_id' => $faker->randomElement(Users::find()->select('id')->limit(50)->column()),
                'address_id' =>$faker->randomElement(Addresses::find()->select('id')->limit(50)->column()),
                'status_id' => $faker->randomElement(Status::find()->select('id')->column()),
                'sub_total' => $faker->numberBetween(1, 100),
                'shipping_price' => $faker->numberBetween(1, 100),
                'total' => $faker->numberBetween(1, 100),
                'profit' => $faker->numberBetween(1, 100),
                'discount' => $faker->numberBetween(1, 100),
                'shipping_id'=>$faker->randomElement(Shippings::find()->select('id')->column()),
                'payment_id'=>$faker->randomElement(Payments::find()->select('id')->column()),
                'status_order'=>$faker->randomElement(["reserved","canceled","processing","refunded","completed"]),
                'delivery_status'=>$faker->randomElement(['delivered','pending','refunded']),
                'payment_status'=>$faker->randomElement(['paid','unpaid']),
                'note' => $faker->text(400),
                'created_at' =>  $crated_at,

            ])->execute();
            $orderId = Yii::$app->db->createCommand('SELECT MAX(id) FROM orders')->queryScalar();
            for ($j = 1; $j <= 3 ; $j++) {  
                 Yii::$app->db->createCommand()->insert('order_items', [
                    'order_id' => $orderId ,
                    'product_id'  =>$faker->randomElement(Products::find()->select('id')->limit(50)->column()),
                    'variant_id' =>$faker->randomElement(Variants::find()->select('id')->limit(50)->column()),
                    'quantity' => $faker->numberBetween(1, 100),
                    'price' =>  $faker->numberBetween(1, 100),
                    'cost' =>  $faker->numberBetween(1, 100),
                ])->execute();
            }  
        
        }
    }
}
