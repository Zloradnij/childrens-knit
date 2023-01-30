<?php

namespace app\modules\catalogHeight\controllers;

use app\modules\catalogHeight\models\handler\ProductMaker;
use app\modules\catalogHeight\models\Image;
use app\modules\catalogHeight\models\Offer;
use app\modules\catalogHeight\models\Product;
use app\modules\catalogHeight\models\PropertyValue;
use app\modules\catalogHeight\models\search\ProductSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManagerProductController implements the CRUD actions for Product model.
 */
class ManagerProductController extends Controller
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
                        'actions' => ['index','create','update','view','delete', 'create-test'],
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreateTest()
    {
//        $product = new Product();
//        $product->status = \Yii::$app->params['statusActive'];
//        $product->title = 'Ползунки';
//        $product->alias = 'polsunki';
//        $product->description = 'Ползунки описание';
//        $product->description_short = 'Ползунки описание короткое';
//        $product->sort = 100;
//        $product->created_user = Yii::$app->user->id;
//        $product->updated_user = Yii::$app->user->id;
//        $product->created_at = strtotime(date(DATE_W3C));
//        $product->updated_at = strtotime(date(DATE_W3C));
//        $product->save();
//
//
//        foreach ([18, 20, 22, 24, 26, 28] as $i => $size) {
//            $offer = new Offer();
//            $offer->product_id = $product->id;
//            $offer->status = \Yii::$app->params['statusActive'];
//            $offer->wholesale_price = 52;
//            $offer->retail_price = 60;
//            $offer->price = 60;
//            $offer->wholesale_count = 50;
//            $offer->sort = ($i + 1) * 100;
//            $offer->created_user = Yii::$app->user->id;
//            $offer->updated_user = Yii::$app->user->id;
//            $offer->created_at = strtotime(date(DATE_W3C));
//            $offer->updated_at = strtotime(date(DATE_W3C));
//            $offer->save();
//
//            $propertyValue = new PropertyValue();
//            $propertyValue->offer_id = $offer->id;
//            $propertyValue->property_id = 1;
//            $propertyValue->value = "$i";
//            $propertyValue->status = \Yii::$app->params['statusActive'];
//            $propertyValue->created_user = Yii::$app->user->id;
//            $propertyValue->updated_user = Yii::$app->user->id;
//            $propertyValue->created_at = strtotime(date(DATE_W3C));
//            $propertyValue->updated_at = strtotime(date(DATE_W3C));
//            $propertyValue->save();
//
//            $propertyValue = new PropertyValue();
//            $propertyValue->offer_id = $offer->id;
//            $propertyValue->property_id = 2;
//            $propertyValue->value = "0";
//            $propertyValue->status = \Yii::$app->params['statusActive'];
//            $propertyValue->created_user = Yii::$app->user->id;
//            $propertyValue->updated_user = Yii::$app->user->id;
//            $propertyValue->created_at = strtotime(date(DATE_W3C));
//            $propertyValue->updated_at = strtotime(date(DATE_W3C));
//            $propertyValue->save();
//        }
//
//        $product = new Product();
//        $product->status = \Yii::$app->params['statusActive'];
//        $product->title = 'Штаны на манжете';
//        $product->alias = 'shtany';
//        $product->description = 'Штаны на манжете описание';
//        $product->description_short = 'Штаны на манжете описание короткое';
//        $product->sort = 200;
//        $product->created_user = Yii::$app->user->id;
//        $product->updated_user = Yii::$app->user->id;
//        $product->created_at = strtotime(date(DATE_W3C));
//        $product->updated_at = strtotime(date(DATE_W3C));
//        $product->save();
//
//
//        foreach (PropertyValue::SIZE as $i => $size) {
//            $offer = new Offer();
//            $offer->product_id = $product->id;
//            $offer->status = \Yii::$app->params['statusActive'];
//            $offer->wholesale_price = 55;
//            $offer->retail_price = 70;
//            $offer->price = 70;
//            $offer->wholesale_count = 50;
//            $offer->sort = ($i + 1) * 100;
//            $offer->created_user = Yii::$app->user->id;
//            $offer->updated_user = Yii::$app->user->id;
//            $offer->created_at = strtotime(date(DATE_W3C));
//            $offer->updated_at = strtotime(date(DATE_W3C));
//            $offer->save();
//
//            $propertyValue = new PropertyValue();
//            $propertyValue->offer_id = $offer->id;
//            $propertyValue->property_id = 1;
//            $propertyValue->value = "$i";
//            $propertyValue->status = \Yii::$app->params['statusActive'];
//            $propertyValue->created_user = Yii::$app->user->id;
//            $propertyValue->updated_user = Yii::$app->user->id;
//            $propertyValue->created_at = strtotime(date(DATE_W3C));
//            $propertyValue->updated_at = strtotime(date(DATE_W3C));
//            $propertyValue->save();
//
//            $propertyValue = new PropertyValue();
//            $propertyValue->offer_id = $offer->id;
//            $propertyValue->property_id = 2;
//            $propertyValue->value = "0";
//            $propertyValue->status = \Yii::$app->params['statusActive'];
//            $propertyValue->created_user = Yii::$app->user->id;
//            $propertyValue->updated_user = Yii::$app->user->id;
//            $propertyValue->created_at = strtotime(date(DATE_W3C));
//            $propertyValue->updated_at = strtotime(date(DATE_W3C));
//            $propertyValue->save();
//        }
//
//        $product = new Product();
//        $product->status = \Yii::$app->params['statusActive'];
//        $product->title = 'Пижама';
//        $product->alias = 'pizhama';
//        $product->description = 'Пижама описание';
//        $product->description_short = 'Пижама описание короткое';
//        $product->sort = 300;
//        $product->created_user = Yii::$app->user->id;
//        $product->updated_user = Yii::$app->user->id;
//        $product->created_at = strtotime(date(DATE_W3C));
//        $product->updated_at = strtotime(date(DATE_W3C));
//        $product->save();
//
//
//        foreach (PropertyValue::SIZE as $i => $size) {
//            $offer = new Offer();
//            $offer->product_id = $product->id;
//            $offer->status = \Yii::$app->params['statusActive'];
//            $offer->wholesale_price = $i < 7 ? 170 : 200;
//            $offer->retail_price = $i < 7 ? 200 : 250;
//            $offer->price = $i < 7 ? 200 : 250;
//            $offer->wholesale_count = 10;
//            $offer->sort = ($i + 1) * 100;
//            $offer->created_user = Yii::$app->user->id;
//            $offer->updated_user = Yii::$app->user->id;
//            $offer->created_at = strtotime(date(DATE_W3C));
//            $offer->updated_at = strtotime(date(DATE_W3C));
//            $offer->save();
//
//            $propertyValue = new PropertyValue();
//            $propertyValue->offer_id = $offer->id;
//            $propertyValue->property_id = 1;
//            $propertyValue->value = "$i";
//            $propertyValue->status = \Yii::$app->params['statusActive'];
//            $propertyValue->created_user = Yii::$app->user->id;
//            $propertyValue->updated_user = Yii::$app->user->id;
//            $propertyValue->created_at = strtotime(date(DATE_W3C));
//            $propertyValue->updated_at = strtotime(date(DATE_W3C));
//            $propertyValue->save();
//
//            $propertyValue = new PropertyValue();
//            $propertyValue->offer_id = $offer->id;
//            $propertyValue->property_id = 2;
//            $propertyValue->value = "0";
//            $propertyValue->status = \Yii::$app->params['statusActive'];
//            $propertyValue->created_user = Yii::$app->user->id;
//            $propertyValue->updated_user = Yii::$app->user->id;
//            $propertyValue->created_at = strtotime(date(DATE_W3C));
//            $propertyValue->updated_at = strtotime(date(DATE_W3C));
//            $propertyValue->save();
//        }


//        $model = \Yii::createObject(ProductMaker::class);
//
//        return $this->render('create-test', [
//            'model' => $model,
//        ]);
    }

    /**
     * @return string|yii\web\Response
     * @throws yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = \Yii::createObject(ProductMaker::class);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->getProduct()->id]);
        } else {
            $model->setOffer();
            $model->setOffer();
            $model->setOffer();

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = \Yii::createObject(ProductMaker::class);
        $model->findByProductId($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->getProduct()->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deactivate();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
