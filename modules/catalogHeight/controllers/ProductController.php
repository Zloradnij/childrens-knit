<?php

namespace app\modules\catalogHeight\controllers;

use app\controllers\ShopController;
use app\modules\catalogHeight\models\Product;
use app\modules\catalogHeight\models\Property;
use app\modules\catalogHeight\models\search\ProductSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends ShopController
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
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
        $orderItems = \Yii::$app->basket->getOrder()->getItems()->indexBy('offer_id')->all();

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->catalogSearch(Yii::$app->request->queryParams);

        return $this->render('free-index', [
            'searchModel'  => $searchModel,
            'products'     => $dataProvider->models,
            'orderItems'   => $orderItems,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param string $alias
     * @return mixed
     */
    public function actionView($alias)
    {
        $orderItems = \Yii::$app->basket->getOrder()->getItems()->indexBy('offer_id')->all();

        $product = $this->findModel($alias);
        $offers  = $product->getOffers()->active()->all();
        $sizes = [];
        $sizePropertyId = Property::find()->active()->andWhere(['alias' => 'size'])->one()->id;

        foreach ($offers as $offer) {
            $sizes[] = $offer->getProperty($sizePropertyId)->one()->value;
        }

        $sizes = array_unique($sizes);
        sort($sizes);

        $this->setMetaTags(
            $product->title . ' | ' . Yii::$app->params['shopName'],
            '',
            $product->description_short
        );

        return $this->render('free-view', [
            'product'        => $product,
            'offers'         => $offers,
            'sizes'          => $sizes,
            'orderItems'     => $orderItems,
            'sizePropertyId' => $sizePropertyId,
        ]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $alias
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($alias)
    {
        if (($model = Product::find()->findByAlias($alias)->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
