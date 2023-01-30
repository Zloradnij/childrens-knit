<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\control\models\LogsVisitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs Visits');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-visit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Logs Visit'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            [
                'attribute' => 'user_id',
                'label' => 'User',
                'format' => 'raw',
                'value' => function($data){
                    if(!empty($data->user->profile)){
                        return '<a href="/user-short/view?id=' . $data->user_id . '">' . $data->user->profile->second_name . ' ' . $data->user->profile->name . ' ' . $data->user->profile->patronymic . '</a>';
                    }
                    return '<a href="/user-short/view?id=' . $data->user_id . '">' . $data->user_id . '</a>';
                }
            ],
            'ip',
            [
                'attribute' => 'comment',
                'label' => 'Domain',
                'format' => 'raw',
                'value' => function($data){
                    return $data->comment;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($data){
                    return date('Y-m-d', $data->created_at);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
