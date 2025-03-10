<?php

use yii\db\Migration;

class m150309_010349_create_status_tables extends Migration
{
    public $data=[
        ['name'=>'اجراء مكالمة','color'=>'#292fca'], // 1
        ['name'=>'مطلوب تجهيزه','color'=>'#237923'], // 2
        ['name'=>'تم تجهيزه','color'=>'#237923'], // 3
        ['name'=>'قيد التوصيل','color'=>'#237923'],// 4
        ['name'=>'تم توصيله','color'=>'#237923'],// 5
        ['name'=>'ملغي','color'=>'#eb0017'],// 6
        ['name'=>'ملغي من الشركة','color'=>'#eb0017'], // 7
        ['name'=>'مؤجل','color'=>'#292fca'], // 8
        ['name'=>'مؤجل من الشركة','color'=>'#292fca'], // 9
        ['name'=>'لا يرد','color'=>'#292fca'], // 10
        ['name'=>'لا يرد من الشركة','color'=>'#292fca'], // 11
        ['name'=>'تم استلام المبلغ','color'=>'#292fca'], // 12
        ['name'=>'تم استلام الطلب الملغي وفع المبلغ توصيل','color'=>'#292fca'], // 13
        ['name'=>'تم استلام الطلب الملغي بدون المبلغ توصيل','color'=>'#292fca'], // 14
       
      
    ];
    
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)->notNull(),
            'color' => $this->string(250)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        Yii::$app->db
        ->createCommand()
        ->batchInsert('status', ['name','color'], $this->data)
        ->execute();
    }

    public function down()
    {
        $this->dropTable('{{%status}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_010349_create_status_tables cannot be reverted.\n";

        return false;
    }
    */
}
