<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserShortSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user-short', 'User Shorts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-short-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('user-short', 'Create User Short'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            'status',
            [
                'attribute' => 'fullName',
                'label' => 'Full Name',
                'content' => function($data){
                    return $data->getFullName();
                }
            ],
            [
                'attribute' => 'userClass',
                'label' => 'User Class',
                'format' => 'text',
                'content' => function($data){
                    return $data->getUserClass();
                }
            ],
            'userClass',

            ['class' => 'yii\grid\ActionColumn'],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{auth}',
                'buttons' => [
                    'auth' => function ($url, $model, $key) {
                        return Html::a('auth', $url);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
