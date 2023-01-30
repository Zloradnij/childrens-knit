<?php

namespace app\modules\control\controllers;

use app\models\TeacherClassLink;
use app\models\Zloradnij;
use app\modules\catalog\models\Property;
use Yii;
use yii\base\Model;

use app\models\UserShort;
use app\models\UserShortSearch;
use app\models\User;
use app\models\Profile;
use app\models\Portfolio;
use app\models\OlympiadSearch;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PropertiesController implements the CRUD actions for Properties model.
 */
class PropertiesController extends Controller
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
                        'actions' => ['index','view','create','update','delete'],
                        'allow' => true,
                        'roles' => ['admin'],
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
     * Lists all Property models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Property();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Property model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $model->role = \Yii::$app->authManager->getRolesByUser($id);
        $model->role = $model->role[key($model->role)]->name;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new UserShort model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserShort();
        $roles = Yii::$app->authManager->roles;

        if ($model->load(Yii::$app->request->post())){
            if($model->save()) {
                if(isset($model->password) && !empty($model->password)){
                    $user = User::findById($model->id);
                    $user->setPassword($model->password);
                    $user->save();
                }

                $auth = Yii::$app->authManager;
                $role = $auth->getRole('user');
                $auth->revokeAll($model->id);
                $auth->assign($role,$model->id);

                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('create', [
                    'model' => $model,
                    'roles' => $roles,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'roles' => $roles,
            ]);
        }
    }

    /**
     * Updates an existing UserShort model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $profile = Profile::find()->where(['user_id' => $id])->one();
        $teacherClasses = [];
        if($profile){
            $teacherClasses = TeacherClassLink::find()->where(['teacher_id' => $id])->all();
        }else{
            $profile = new Profile();
        }
        $teacherClasses[] = new TeacherClassLink();

        $portfolio = Portfolio::find()->where(['user_id' => $id])->one();
        $searchModelOlympiad = new OlympiadSearch();
        if($portfolio){
            $dataProviderOlympiad = $searchModelOlympiad->searchByPortfolio($portfolio->id,Yii::$app->request->queryParams);
        }else{
            $dataProviderOlympiad = [];
        }

        $roles = Yii::$app->authManager->roles;
        $model->role = \Yii::$app->authManager->getRolesByUser($id);
        $model->role = ArrayHelper::map($model->role, 'name', 'name');

        if ($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())){
            $profile->user_id = $id;
            if(isset($model->password) && !empty($model->password)){
                $user = User::findById($id);
                $user->setPassword($model->password);
                $user->save();
            }

            Yii::$app->authManager->revokeAll($id);
            foreach ($model->role as $role) {
                $new_role = Yii::$app->authManager->getRole($role);
                Yii::$app->authManager->assign($new_role, $id);
            }

            if($model->save() && $profile->save()) {
                if (Model::loadMultiple($teacherClasses, Yii::$app->request->post()) && Model::validateMultiple($teacherClasses)) {
                    foreach ($teacherClasses as $key => $item) {
                        if(!isset($item->user_id) || empty($item->user_id)){
                            $item->teacher_id = $id;
                        }
                        if (isset($item->class_id) && !empty($item->class_id) && $item->save()) {

                        }
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                    'roles' => $roles,
                    'profile' => $profile,
                    'dataProviderOlympiad' => $dataProviderOlympiad,
                    'searchModelOlympiad' => $searchModelOlympiad,
                    'teacherClasses' => $teacherClasses,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'roles' => $roles,
                'profile' => $profile,
                'dataProviderOlympiad' => $dataProviderOlympiad,
                'searchModelOlympiad' => $searchModelOlympiad,
                'teacherClasses' => $teacherClasses,
            ]);
        }
    }

    /**
     * Deletes an existing UserShort model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserShort model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserShort the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserShort::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAuth($id)
    {
        Yii::$app->user->login(User::findById($id), 0);
        $this->redirect('/');
    }
}
