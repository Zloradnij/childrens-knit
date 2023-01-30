<?php

namespace app\modules\basket\controllers;

use app\controllers\ShopController;
use app\modules\basket\models\handler\OrderMaker;
use app\modules\basket\models\Order;
use app\modules\basket\models\OrderItem;
use app\modules\basket\models\search\OrderSearch;
use app\modules\catalogHeight\models\PropertyValue;
use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BasketController implements the CRUD actions for Catalog model.
 */
class BasketController extends ShopController
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'view'],
                        'allow'   => true,
                        'roles'   => ['manager'],
                    ],
                    [
                        'actions' => ['add-product', 'buyerCreate', 'buyerUpdate', 'buyerView'],
                        'allow'   => true,
                        'roles'   => ['?', '@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow'   => true,
                        'roles'   => ['admin'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete'      => ['POST'],
                    'add-product' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function actionSmall()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $searchModel = new OrderSearch();

        Yii::$app->request->queryParams['OrderSearch']['status'] = Order::STATUS_NEW;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var Order $order */
        $order = \Yii::$app->basket->getOrder();

        return $this->render($order->getItems()->count() ? 'index' : 'empty', [
            'basket'         => $order,
            'propertyValues' => PropertyValue::VALUE_LIST,
        ]);
    }

    /**
     * @param Integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(Integer $id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
