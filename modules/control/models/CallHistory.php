<?php

namespace app\modules\control\models;

use app\models\User;
use app\models\Zloradnij;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "call_history".
 *
 * @property integer $id
 * @property string $phone
 * @property string $response
 * @property integer $updated_at
 * @property integer $created_at
 */
class CallHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call_history';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['phone'], 'string', 'max' => 10],
            [['response'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'id',
            'phone'      => 'phone',
            'response'   => 'response',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }
}
