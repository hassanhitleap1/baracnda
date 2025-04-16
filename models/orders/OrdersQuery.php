<?php

namespace app\models\orders;

use Yii;

/**
 * This is the ActiveQuery class for [[Orders]].
 *
 * @see Orders
 */
class OrdersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Orders[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    public function byUser($user_id)
    {
        return $this->andWhere(['user_id' => $user_id]);
    }

    public function byStatus($status)
    {
        return $this->andWhere(['status_id' => $status]);
    }


    public function byDate($date)
    {
        return $this->andWhere(['date' => $date]);
    }

    public function byDateRange($start_date, $end_date)
    {
        return $this->andWhere(['>=', 'created_at', $start_date])->andWhere(['<=', 'created_at', $end_date]);
    }

    public function byCreator($creator_id)
    {
        return $this->andWhere(['creator_id' => $creator_id]);
    }

    public function byAuthedUser()
    {
        if (Yii::$app->user->can('orders/view')) {
            if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'super-admin') ||
                Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager') ||
                Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'dataEntry')) {
                return $this; // Allow access to all orders
            }
            return $this->andWhere(['creator_id' => Yii::$app->user->id]); // Restrict to creator's orders
        }
        return $this->andWhere('0=1'); // Deny access if no permissions
    }

    public function paid()
    {
        return $this->andWhere(['payment_status' => 'paid']);
    }


    public function un_paid()
    {
        return $this->andWhere(['payment_status' => 'unpaid']);
    }

    public function completed()
    {
        return $this->andWhere(['status_order' => 'completed']);
    }
    /**
     * {@inheritdoc}
     * @return Orders|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
