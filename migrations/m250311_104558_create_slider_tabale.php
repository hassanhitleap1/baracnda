<?php

use yii\db\Migration;

class m250311_104558_create_slider_tabale extends Migration
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
        $this->createTable('{{%slider}}', [
            'id' => $this->primaryKey(),
            'src' => $this->string(255)->notNull(),
            'title' => $this->string()->null(),
            'sub_title' => $this->string()->null(),
            'text' => $this->string()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);


        $data = [
            [
                'src' => 'mb-1.jpg',
                'text' => 'Engineering business is my passion',
 
            ],
            [
                'src' => 'chemicalengineeringbackground.jpeg',
                'text' => 'Engineering business is my passion',
            ]
        ];


        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                'slider',
                [
                    'src',
                    'text',
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
        echo "m250311_104558_create_slider_tabale cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250311_104558_create_slider_tabale cannot be reverted.\n";

        return false;
    }
    */
}
