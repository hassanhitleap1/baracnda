<?php

use yii\db\Migration;

class m250311_104529_create_pages_tabale extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%pages}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(255)->unique()->notNull(),
            'title' => $this->string(255)->null(),
            'desc' => $this->text()->null(),
            'image' => $this->string()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);


        $data = [
            [
                'key' => "aboutus",
                'title' => "aboutus",
                'desc' => "aboutus",
                'image' => 'service.jpg',
            ],
            [
                'key' => "termsandconditions",
                'title' => "termsandconditions",
                'desc' => "termsandconditions",
                'image' => 'service.jpg',
    
            ],
            [
                'key' => "privacypolicy",
                'title' => "privacypolicy",
                'desc' => "privacypolicy",
                'image' => 'service.jpg',
            ]
        ];
        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                'pages',
                [
                    'key',
                    'title',
                    'desc',
                    'image',
                ],
                $data
            )
            ->execute();


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250311_104529_create_pages_tabale cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250311_104529_create_pages_tabale cannot be reverted.\n";

        return false;
    }
    */
}
