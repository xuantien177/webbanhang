<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Blog;

/**
 * BlogSearch represents the model behind the search form of `common\models\Blog`.
 */
class BlogSearch extends Blog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nguoi_dang_id', 'nguoi_sua_id'], 'integer'],
            [['tieu_de', 'mo_ta_ngan', 'noi_dung', 'ngay_dang', 'ngay_sua', 'anh_dai_dien'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Blog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ngay_dang' => $this->ngay_dang,
            'ngay_sua' => $this->ngay_sua,
            'nguoi_dang_id' => $this->nguoi_dang_id,
            'nguoi_sua_id' => $this->nguoi_sua_id,
        ]);

        $query->andFilterWhere(['like', 'tieu_de', $this->tieu_de])
            ->andFilterWhere(['like', 'mo_ta_ngan', $this->mo_ta_ngan])
            ->andFilterWhere(['like', 'noi_dung', $this->noi_dung])
            ->andFilterWhere(['like', 'anh_dai_dien', $this->anh_dai_dien]);

        return $dataProvider;
    }
}
