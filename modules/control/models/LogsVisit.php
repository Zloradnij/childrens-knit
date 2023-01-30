<?php

namespace app\modules\control\models;

use app\models\User;
use app\models\Zloradnij;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "logs_visit".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property string $comment
 * @property integer $created_at
 */
class LogsVisit extends \yii\db\ActiveRecord
{
    private $whiteList = [
//        '127.0.0.1',
//        '89.189.178.39',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs_visit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['ip', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('logs', 'ID'),
            'user_id' => Yii::t('logs', 'User ID'),
            'ip' => Yii::t('logs', 'Ip'),
            'comment' => Yii::t('logs', 'Comment'),
            'created_at' => Yii::t('logs', 'Created At'),
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }

    public function setVisit($userID,$comment = ''){
        if(!$userID){
            return false;
        }
        if(!empty($_SERVER['REMOTE_ADDR'])){
            if(!in_array($_SERVER['REMOTE_ADDR'],$this->whiteList)){
                $this->user_id = $userID;
                $this->ip = $_SERVER['REMOTE_ADDR'];
                $this->comment = $comment;
                $this->created_at = time();

                if($this->save()){

                }else{
//                    Zloradnij::printArr($this->errors);
                }
            }
        }
    }
}
