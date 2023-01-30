<?php

namespace app\modules\control\controllers;

use app\models\Zloradnij;
use Yii;
use yii\base\Model;

use app\models\FactoryTeacherElement;

use app\models\User;
use app\models\Portfolio;
use app\models\PortfolioSearch;
use app\models\Profile;
use app\models\ProfileSearch;
use app\models\Olympiad;
use app\models\OlympiadSearch;
use app\models\Completition;
use app\models\CompletitionSearch;
use app\models\ScientificConference;
use app\models\ScientificConferenceSearch;
use app\models\Essay;
use app\models\EssaySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

use app\models\TeacherFields;
use app\models\TeacherFieldsSearch;
use app\models\TeacherCertifications;
use app\models\TeacherCertificationsSearch;
use app\models\TeacherTrainings;
use app\models\TeacherTrainingsSearch;
use app\models\TeacherTopic;
use app\models\TeacherTopicSearch;
use app\models\TeacherCompetition;
use app\models\TeacherCompetitionSearch;

use app\models\TeacherExperiment;
use app\models\TeacherExperimentSearch;

use app\models\TeacherNpk;
use app\models\TeacherNpkSearch;

use app\models\TeacherOlympics;
use app\models\TeacherOlympicsSearch;

use app\models\TeacherScan;
use app\models\TeacherScanSearch;

use yii\web\UploadedFile;
use app\controllers\PortfolioFileController;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','class'],
                        'allow' => true,
                        'roles' => ['teacher'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Portfolio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $allRoles = Yii::$app->authManager->getRoles();
        $myRole = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
        Zloradnij::printArr($allRoles);
        print '123';
        Zloradnij::printArr($myRole);

        $searchModel = new PortfolioSearch();
        $dataProvider = $searchModel->searchClasses(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Portfolio model.
     * @param integer $id
     * @return mixed
     */
    public function actionClass($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Portfolio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Portfolio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Portfolio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMyPortfolioPrint(){
        $user = \Yii::$app->user->identity;
        if (\Yii::$app->user->can('teacher')) {
            return $this->getTeacherPortfolio($user,'teacher-portfolio-print');
        }else{
            return $this->getUserPortfolio($user,'my-portfolio-print');
        }
    }
}
