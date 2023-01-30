<?php

use app\modules\catalogHeight\models\Property;
use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\catalogHeight\models\search\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Свойства товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-visit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить свойство'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'alias',
            [
                'attribute' => 'status',
                'format'    => 'raw',
                'value'     => function ($data) {
                    return $data->status === \Yii::$app->params['statusActive']
                        ? '<i class="btn btn-success fa fa-flag-o fa-lg"></i>'
                        : '<i class="btn btn-danger fa fa-trash-o fa-lg"></i>';
                },
            ],
            [
                'attribute' => 'property_type_id',
                'value'     => function ($data) {
                    return Property::PROPERTY_TYPE_IDS[$data->property_type_id];
                },
            ],
            [
                'attribute' => 'created_user',
                'value'     => function ($data) {
                    return User::find()->where(['id' => $data->created_user])->select('username')->scalar();
                },
            ],
            [
                'attribute' => 'updated_user',
                'value'     => function ($data) {
                    return User::find()->where(['id' => $data->updated_user])->select('username')->scalar();
                },
            ],
            [
                'attribute' => 'created_at',
                'value'     => function ($data) {
                    return date('Y-m-d', $data->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value'     => function ($data) {
                    return date('Y-m-d', $data->updated_at);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
