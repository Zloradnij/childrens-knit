<?php

namespace app\modules\basket\controllers;

use app\controllers\ShopController;
use app\modules\basket\models\handler\OrderMaker;
use app\modules\basket\models\Order;
use app\modules\basket\models\OrderItem;
use app\modules\basket\models\search\OrderSearch;
use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CatalogController implements the CRUD actions for Catalog model.
 */
class OrderController extends ShopController
{
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
                        'actions' => ['index', 'create', 'update', 'manager-view', 'delete'],
                        'allow'   => true,
                        'roles'   => ['manager'],
                    ],
                    [
                        'actions' => [
                            'add-product',
                            'buyerCreate',
                            'buyerUpdate',
                            'buyerView',
                            'add-param',
                            'finish-order',
                        ],
                        'allow'   => true,
                        'roles'   => ['?', '@'],
                    ],
                    [
                        'actions' => [
                            'my',
                            'view',
                        ],
                        'allow'   => true,
                        'roles'   => ['@'],
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

    public function actionMy()
    {
        $queryParams = Yii::$app->request->queryParams;
        $orderSearchClass = explode('\\', OrderSearch::class);
        $queryParams[end($orderSearchClass)]['user_id'] = Yii::$app->user->id;

        $searchModel = new OrderSearch();

        $dataProvider = $searchModel->searchForBayer($queryParams);

        return $this->render('my-order', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function actionAddProduct()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = \Yii::createObject(OrderMaker::class);

        $count = Yii::$app->request->post()['count'];

        if ($count) {
            $requestData = ['OrderItem' => Yii::$app->request->post()];

            if ($model->load($requestData) && $model->save()) {
                return [
                    'success'  => 'add',
                    'count'   => $count,
                    'offer_id' => Yii::$app->request->post()['offer_id'],
                ];
            }

            throw new InvalidArgumentException('Ошибка добавления товара в корзину');
        }

        $delete = OrderItem::findOne([
            'offer_id' => Yii::$app->request->post()['offer_id'],
            'order_id' => \Yii::$app->basket->getOrder()->id,
        ])->delete();

        if ($delete) {
            return [
                'success'  => 'delete',
                '$count'   => $count,
                'offer_id' => Yii::$app->request->post()['offer_id'],
            ];
        }

        throw new InvalidArgumentException('Ошибка удаления товара из корзины');
    }

    public function actionAddParam()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $paramName = Yii::$app->request->post()['param'];
        $paramValue = Yii::$app->request->post()['value'];

        if (!in_array($paramName, OrderMaker::SUCCESS_PARAMS)) {
            throw new InvalidArgumentException('Bad parameter');
        }

        /** @var Order $order */
        $order = \Yii::$app->basket->getOrder();
        $order->load(['Order' => [$paramName => $paramValue]]);

        if ($order->save()) {
            return [
                'success' => true,
            ];
        }

        return [
            'error' => $order->getErrors(),
        ];
    }

    public function actionFinishOrder()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $comment = Yii::$app->request->post()['comment'];

        /** @var Order $order */
        $order = \Yii::$app->basket->getOrder();
        if (!$order->load(['Order' => ['comment' => $comment]])) {
            return json_encode([
                'error' => $order->getErrors(),
            ]);
        }

        $order->status = Order::STATUS_JOB;

        if (!$order->save()) {
            return $this->asJson([
                'error' => $order->getErrors(),
            ]);
        }

        \Yii::$app->session->setFlash(
            'success',
            "Заказ успешно оформлен. В ближайшее время с Вами свяжется наш менеджер."
        );

        if (!empty(Yii::$app->request->post()['username'])) {
            $user = Yii::$app->user->identity;
            $user->username = Yii::$app->request->post()['username'];
            $user->save();
        }

        return $this->asJson([
            'success' => 200,
        ]);
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $order = $this->findModel($id);

        if (!Yii::$app->user->can('manager') && $order->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Заказ не найден', 404);
        }

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
