<?php

namespace app\models\orders;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\orders\Orders;

/**
 * OrdersSearch represents the model behind the search form of `app\models\orders\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * {@inheritdoc}
     */    
    public $date_range;
    public $start_date;
    public $end_date;

    public function rules()
    {
        return [
            [['id', 'user_id', 'creator_id', 'address_id', 'status_id', 'shipping_id'], 'integer'],
            [['total', 'shipping_price', 'sub_total', 'profit', 'discount'], 'number'],
            [['note', 'created_at', 'updated_at'.'date_range','delivery_status','payment_status','start_date','end_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Orders::find()->joinWith(['user', 'creator', 'status'])->byAuthedUser();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->date_range) {
            list($start_date, $end_date) = explode(' - ', $this->date_range);
            $query->andFilterWhere(['>=', 'purchase_date', $start_date])
                ->andFilterWhere(['<=', 'purchase_date', $end_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'creator_id' => $this->creator_id,
            'address_id' => $this->address_id,
            'status_id' => $this->status_id,
            'total' => $this->total,
            'shipping_price' => $this->shipping_price,
            'sub_total' => $this->sub_total,
            'profit' => $this->profit,
            'discount' => $this->discount,
            'shipping_id' => $this->shipping_id,
            'delivery_status' => $this->delivery_status,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        // Filter by date range
        if (!empty($this->created_at)) {
            $dateRange = explode(' - ', $this->created_at);
            $query->andFilterWhere(['>=', 'created_at', $dateRange[0]])
                  ->andFilterWhere(['<=', 'created_at', $dateRange[1]]);
        }

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
